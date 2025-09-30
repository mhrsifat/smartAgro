@extends('layouts.master')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Two-Factor Authentication</h2>

    @if(session('status'))
        <div class="mb-4 text-green-700">{{ session('status') }}</div>
    @endif

    {{-- If not enabled, show enable form --}}
    @if(! $user->two_factor_secret)
        <div class="mb-4">
            <p class="text-sm text-gray-700">Two-factor authentication is not enabled for your account.</p>

            <form method="POST" action="/user/two-factor-authentication" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Confirm your password to enable</label>
                    <input type="password" name="password" required class="w-full border rounded px-3 py-2" />
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enable 2FA</button>
            </form>
        </div>
    @else
        {{-- Already enabled: show QR and recovery codes --}}
        <div class="mb-4">
            <p class="text-sm text-gray-700 mb-3">Two-factor authentication is enabled for your account.</p>

            <div class="mb-4">
                <p class="text-sm mb-2">Scan this QR with your authenticator app:</p>
                <div class="border p-4 rounded bg-gray-50">
                    {!! $qr !!}
                </div>
            </div>

            <div class="mb-4">
                <p class="text-sm mb-2">If you lose your device, use one of these recovery codes:</p>
                @if($recoveryCodes)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($recoveryCodes as $code)
                            <div class="border rounded p-2 text-xs bg-white">{{ $code }}</div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-500">No recovery codes available. You can regenerate them below.</p>
                @endif
            </div>

            {{-- Regenerate recovery codes --}}
            <form method="POST" action="/user/two-factor-recovery-codes" class="inline-block mr-2">
                @csrf
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Regenerate codes</button>
            </form>

            {{-- Disable 2FA (Fortify requires password confirmation) --}}
            <form method="POST" action="/user/two-factor-authentication" class="inline-block">
                @csrf
                @method('DELETE')
                <div class="mt-3">
                    <label class="block text-sm font-medium mb-1">Confirm password to disable</label>
                    <input type="password" name="password" required class="w-full border rounded px-3 py-2" />
                </div>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded mt-2">Disable 2FA</button>
            </form>
        </div>
    @endif

    {{-- Confirm newly enabled 2FA (Fortify expects a POST to /user/confirmed-two-factor-authentication) --}}
    @if(session('status') == 'two-factor-authentication-enabled')
        <div class="mt-4 border-t pt-4">
            <p class="text-sm mb-2">Enter the code from your authenticator app to confirm setup:</p>
            <form method="POST" action="/user/confirmed-two-factor-authentication">
                @csrf
                <div class="mb-3">
                    <input type="text" name="code" required class="w-full border rounded px-3 py-2" placeholder="123456" />
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Confirm 2FA</button>
            </form>
        </div>
    @endif
</div>
@endsection