<h2>Hi {{ $data['name'] }},</h2>

<p>Thank you for contacting SmartAgro. We have received your message:</p>

<p><strong>Subject:</strong> {{ $data['subject'] ?? 'No subject' }}</p>
<p><strong>Message:</strong><br>{{ $data['message'] }}</p>

<p>Our team will get back to you as soon as possible.</p>

<p>Best regards,<br>SmartAgro Team</p>