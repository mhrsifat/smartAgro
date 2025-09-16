<?php

namespace App\Http\Controllers\Fortify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // If the user has a secret, Fortify/Trait provides helper to get the QR SVG
        $qr = null;
        if ($user->two_factor_secret) {
            // twoFactorQrCodeSvg() is provided by Fortify trait on the user
            $qr = $user->twoFactorQrCodeSvg();
        }

        // recoveryCodes helper (returns array)
        $recoveryCodes = $user->two_factor_recovery_codes
            ? json_decode(decrypt($user->two_factor_recovery_codes), true)
            : null;

        return view('auth.two-factor', [
            'qr' => $qr,
            'recoveryCodes' => $recoveryCodes,
            'user' => $user,
        ]);
    }
}

