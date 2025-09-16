<?php

namespace App\Http\Controllers\Fortify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewEmailVerificationMail;
use App\Mail\OldEmailSecurityMail;


class ProfileController extends Controller
{
    public function edit()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
        ]);

        $newEmail = $request->input('email');

        // If email unchanged, just update name and return
        if ($newEmail === $user->email) {
            $user->name = $request->input('name');
            $user->save();

            return back()->with('status', 'Profile updated successfully.');
        }

        // EMAIL CHANGED: create pending change (do NOT touch email_verified_at)
        $oldEmail = $user->email;

        $user->name = $request->input('name');
        $user->new_email = $newEmail;         // store pending address
        $user->save();

        // Generate signed verification URL for new email (valid for 24 hours)
        $verifyUrl = URL::temporarySignedRoute(
            'user.email.verify-new',
            now()->addHours(24),
            ['id' => $user->id, 'email' => $newEmail]
        );

        // Generate signed cancel URL for old email (valid for 7 days)
        $cancelUrl = URL::temporarySignedRoute(
            'user.email.cancel',
            now()->addDays(7),
            ['id' => $user->id, 'email' => $newEmail]
        );

        // Send verification to the NEW email
        Mail::to($newEmail)->send(new NewEmailVerificationMail($user, $verifyUrl));

        // Send security notification to the OLD email (inform user + cancel link)
        Mail::to($oldEmail)->send(new OldEmailSecurityMail($user, $newEmail, $cancelUrl));

        return back()->with('status', 'We sent a verification link to your new email. Please check it to confirm the change.');
    }

    /**
     * Called from signed link in the NEW email.
     */
    public function verifyNewEmail(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $user = \App\Models\User::findOrFail($request->query('id'));

        $requestedEmail = $request->query('email');

        // If there is no pending new_email or mismatch, abort
        if (! $user->new_email || $user->new_email !== $requestedEmail) {
            return redirect()->route('dashboard')->withErrors('Invalid or expired verification link.');
        }

        // Apply the new email, mark verified, clear pending
        $user->email = $user->new_email;
        $user->new_email = null;
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('dashboard')->with('status', 'Your new email has been verified and updated.');
    }

    /**
     * Called from signed link in the OLD email to cancel a pending change.
     */
    public function cancelEmailChange(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $user = \App\Models\User::findOrFail($request->query('id'));
        $requestedNewEmail = $request->query('email');

        if ($user->new_email && $user->new_email === $requestedNewEmail) {
            $user->new_email = null;
            $user->save();

            // Optionally notify user (mail) that change was cancelled — omitted for brevity
            return redirect()->route('dashboard')->with('status', 'Email change request canceled.');
        }

        return redirect()->route('dashboard')->withErrors('No pending email change found or it was already processed.');
    }
}
