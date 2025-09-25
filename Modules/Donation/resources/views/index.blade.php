@extends('layouts.master')

@section('title', 'Support Our Project')

@section('content')
<div class="container mx-auto px-4 py-12">

    <!-- Hero / Intro Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-green-700">🌱 Support SmartAgro</h1>
        <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
            Help us empower farmers, increase crop yields, and make agriculture sustainable in Bangladesh.
        </p>
        <a href="{{ route('donation.create') }}" class="mt-6 inline-block bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
            Donate Now
        </a>
    </div>

    <!-- Why Donate Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Why Donate?</h2>
        <p class="text-gray-700">
            Your donation supports technology-driven solutions for farmers, provides training, and improves livelihoods.
            Every contribution helps us reach more communities and make a real impact.
        </p>
    </div>

    <!-- Project Goal Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Our Goals</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Increase crop productivity using smart farming techniques</li>
            <li>Provide affordable tools and resources to farmers</li>
            <li>Promote sustainable and eco-friendly farming</li>
            <li>Support farmer education and training programs</li>
        </ul>
    </div>

    <!-- Short Description Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">About SmartAgro</h2>
        <p class="text-gray-700">
            SmartAgro is a technology platform dedicated to modernizing agriculture in Bangladesh. 
            Through data-driven insights, IoT sensors, and community support, we aim to empower farmers
            and create a sustainable future for agriculture.
        </p>
    </div>

    <!-- Call to Action -->
    <div class="text-center">
        <a href="{{ route('donation.create') }}" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
            Donate Now
        </a>
    </div>

</div>
@endsection