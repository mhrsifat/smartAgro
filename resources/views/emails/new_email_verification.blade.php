@component('mail::message')
# Confirm your new email

Hi {{ $user->name }},

You requested to change your account email to **{{ $user->new_email }}**.

Click the button below to confirm this new email address (link expires in 24 hours):

@component('mail::button', ['url' => $verifyUrl])
Confirm new email
@endcomponent

If you did not request this change, ignore this email or contact support.

Thanks,<br>
{{ config('app.name') }}
@endcomponent