@extends('layouts.master')

@section('content')
<div class="bg-[#F5F5F5] py-12">
  <div class="max-w-5xl mx-auto px-6">

    <!-- Hero -->
    <div class="text-center mb-12">
      <h1 class="text-3xl font-bold text-[#2E7D32]">We’d Love to Hear From You</h1>
      <p class="mt-3 text-gray-600">At SmartAgro, we value your questions, ideas, and feedback. Whether you need crop advice, want to report an issue, or simply want to connect — we’re here to help.</p>
    </div>

    <!-- Contact Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center mb-12">
      <div>
        <h3 class="font-semibold text-[#1976D2]">Address</h3>
        <p class="mt-2 text-gray-600">House #12, Road #5<br>Green Agro Park, Dhaka, Bangladesh</p>
      </div>
      <div>
        <h3 class="font-semibold text-[#1976D2]">Phone</h3>
        <p class="mt-2 text-gray-600">+880 1234 567890</p>
      </div>
      <div>
        <h3 class="font-semibold text-[#1976D2]">Email</h3>
        <p class="mt-2 text-gray-600">support@smartagro.com</p>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white shadow-md rounded-2xl p-8">

      {{-- Success message --}}
      @if(session('success'))
        <div class="mb-6 p-4 rounded-md bg-green-50 text-green-800">
          {{ session('success') }}
        </div>
      @endif

      {{-- Validation errors --}}
      @if($errors->any())
        <div class="mb-6 p-4 rounded-md bg-red-50 text-red-800">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6" novalidate>
        @csrf

        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input
            type="text"
            name="name"
            required
            value="{{ old('name') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32] @error('name') border-red-400 ring-red-100 @enderror"
            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
            aria-describedby="name-error"
          >
          @error('name')
            <p id="name-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            name="email"
            required
            value="{{ old('email') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32] @error('email') border-red-400 ring-red-100 @enderror"
            aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
            aria-describedby="email-error"
          >
          @error('email')
            <p id="email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Subject</label>
          <input
            type="text"
            name="subject"
            value="{{ old('subject') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32] @error('subject') border-red-400 ring-red-100 @enderror"
            aria-describedby="subject-error"
          >
          @error('subject')
            <p id="subject-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Message</label>
          <textarea
            name="message"
            rows="4"
            required
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32] @error('message') border-red-400 ring-red-100 @enderror"
            aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}"
            aria-describedby="message-error"
          >{{ old('message') }}</textarea>
          @error('message')
            <p id="message-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <button
          type="submit"
          class="w-full py-3 px-6 rounded-md font-medium shadow-sm"
          style="background-color: #1976D2; color: #FFFFFF;"
        >
          Send Message
        </button>
      </form>
    </div>

    <!-- CTA -->
    <div class="text-center mt-12">
      <h3 class="text-xl font-semibold text-[#2E7D32]">Need Quick Support?</h3>
      <p class="mt-2 text-gray-600">Our support team usually replies within 24 hours.</p>
      <a href="tel:+8801234567890" class="inline-block mt-4 px-6 py-3 rounded-md font-medium shadow-md"
         style="background-color: #FBC02D; color: #212121;">
        Call Us Now
      </a>
    </div>
  </div>
</div>
@endsection