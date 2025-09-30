@extends('layouts.master')

@section('title', 'Two-Factor Settings')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Two-Factor Authentication Settings</h2>

    @if(session('status'))
        <div class="mb-4 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    {{-- If 2FA not enabled --}}
    @if(! Auth::user()->two_factor_secret)
        <div class="mb-4">
            <p class="text-sm">You have not enabled two-factor authentication. Enable it to increase account security.</p>

            <form method="POST" action="/user/two-factor-authentication" class="mt-3">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Enable Two-Factor Authentication
                </button>
            </form>
        </div>
    @else
        <div class="mb-4">
            <p class="mb-3 text-sm">Two-factor authentication is currently <strong>enabled</strong>.</p>

            {{-- QR Code --}}
            <div class="mb-4">
                <p class="text-sm mb-2">Scan this QR code with your authenticator app:</p>
                <div class="border p-4 rounded bg-gray-50">
                    {!! Auth::user()->twoFactorQrCodeSvg() !!}
                </div>
            </div>

            {{-- Confirm 2FA form (enter current code to confirm) --}}
            <form method="POST" action="/two-factor-recovery-codes" class="mb-4">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    Regenerate Recovery Codes
                </button>
            </form>

            {{-- Show recovery codes if in session --}}
            @if(session('two_factor_recovery_codes'))
                <div class="mb-4">
                    <p class="text-sm mb-2">Your recovery codes (store them safely):</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(json_decode(decrypt(session('two_factor_recovery_codes')), true) as $code)
                            <div class="border rounded p-2 text-xs bg-white">{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Disable 2FA --}}
            <form method="POST" action="/user/two-factor-authentication">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Disable Two-Factor Authentication</button>
            </form>
        </div>
    @endif
</div>
@endsection