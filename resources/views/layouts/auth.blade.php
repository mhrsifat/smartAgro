<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">@yield('heading')</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    @yield('content')
  </div>

</body>
</html>