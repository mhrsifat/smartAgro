<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use App\Mail\ContactConfirmation;
use Illuminate\Support\Facades\Mail;
use Mpdf\Mpdf;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class HabizabiController extends Controller
{

public function test()
{
    // Render Blade view into HTML
    $html = view('test')->render();

    // Get default mPDF font directories & data
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    // Initialize mPDF
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'fontDir' => array_merge($fontDirs, [
            storage_path('fonts'), // your fonts location
        ]),
        'fontdata' => $fontData + config('mpdf_fonts'),
        'default_font' => 'test', // use the font key from your config
    ]);

    // Write the HTML
    $mpdf->WriteHTML($html);

    // Output PDF in browser
    return response($mpdf->Output('bangla_test.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
}
    
    public function dashboard() {
      return view('dashboard');
    }
    
    public function contact() {
      return view('contact');
    }

    public function impact() {
      return view('impact');
    } 

    public function achievements() {
      return view('achievements');
    }
    public function submit(ContactRequest $request)
    {
        $data = $request->validated();
        $data['message'] = strip_tags($data['message']);

        // save to DB
        $contact = Contact::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'replied' => false,
        ]);

        // send emails (you already had these mailables)
        $supportAddress = config('mail.from.address', 'support@smartagro.com');
        Mail::to($supportAddress)->queue(new ContactMessage($data));
        Mail::to($data['email'])->queue(new ContactConfirmation($data));

        return redirect()->back()->with('success', 'Your message has been sent. We will get back to you soon.');
    }

    // Admin: contacts list
    public function admin_contacts(Request $request)
    {
        $q = $request->query('q');

        $contactsQuery = Contact::latest();

        if ($q) {
            $contactsQuery->where(function ($qBuilder) use ($q) {
                $qBuilder->where('name', 'like', "%{$q}%")
                         ->orWhere('email', 'like', "%{$q}%")
                         ->orWhere('subject', 'like', "%{$q}%")
                         ->orWhere('message', 'like', "%{$q}%");
            });
        }

        $contacts = $contactsQuery->paginate(20);

        return view('admin.contacts.index', compact('contacts', 'q'));
    }

    // Admin: view single contact
    public function showContact(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    // Admin: mark as replied
    public function markAsReplied(Contact $contact)
    {
        $contact->update([
            'replied'    => true,
            'replied_by' => Auth::id(),
            'replied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Message marked as replied.');
    }

    // Admin: delete contact
    public function destroyContact(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted.');
    }
    
    
public function replyForm(Contact $contact)
{
    return view('admin.contacts.reply', compact('contact'));
}

public function sendReply(Request $request, Contact $contact)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $replyMessage = $request->message;
    
    Mail::raw($replyMessage, function ($mail) use ($contact) {
    $mail->to($contact->email)
         ->subject('Reply: ' . ($contact->subject ?? 'Your Message'));
});

    // Mark as replied
    $contact->update([
        'replied' => true,
        'replied_by' => Auth::id(),
        'replied_at' => now(),
    ]);

    return redirect()->route('admin.contacts.index')->with('success', 'Reply sent successfully.');
}
}
