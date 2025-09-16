@component('mail::message')
# Security notice: email change requested

Hi {{ $user->name }},

We received a request to change your account email to **{{ $newEmail }}**.

If you requested this, no action is required here — confirm on the new email inbox.

If you did **not** request this change, click below to cancel the request immediately:

@component('mail::button', ['url' => $cancelUrl])
Cancel email change
@endcomponent

If you need help, reply to this email or contact support.

Regards,<br>
{{ config('app.name') }}
@endcomponent