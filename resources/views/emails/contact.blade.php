<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Contact Message</title>
  </head>
  <body>
    <h2>New contact message</h2>
    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Subject:</strong> {{ $data['subject'] ?? '—' }}</p>
    <hr>
    <p>{!! nl2br(e($data['message'])) !!}</p>
  </body>
</html>