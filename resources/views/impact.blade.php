@extends('layouts.master')

@section('title', 'SmartAgro Impact - Bangladesh')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-green-700">🌱 SmartAgro Impact in Bangladesh</h1>
        <p class="text-gray-600 mt-2">Empowering farmers with AI-driven crop, pesticide, and fertilizer advice.</p>
    </div>

    <!-- Key Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 text-center">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-green-600">1,200+</h2>
            <p class="text-gray-700 mt-2">Farmers Assisted</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-green-600">3,500+</h2>
            <p class="text-gray-700 mt-2">Crops Monitored</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-green-600">2,400+</h2>
            <p class="text-gray-700 mt-2">Pesticide & Fertilizer Advices</p>
        </div>
    </div>

    <!-- Regional Impact Map (dummy) -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Regional Impact</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h3 class="font-semibold text-lg">Dhaka</h3>
                <p class="text-gray-600 mt-1">320 farmers</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h3 class="font-semibold text-lg">Chattogram</h3>
                <p class="text-gray-600 mt-1">280 farmers</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h3 class="font-semibold text-lg">Khulna</h3>
                <p class="text-gray-600 mt-1">210 farmers</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h3 class="font-semibold text-lg">Rajshahi</h3>
                <p class="text-gray-600 mt-1">150 farmers</p>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-green-700 mb-4">What Farmers Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-700">"SmartAgro helped me choose the right crops and increased my rice yield by 20%!"</p>
                <p class="mt-4 font-semibold">— Rahim, Tangail</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-700">"The pesticide recommendations are simple and effective. No more crop loss!"</p>
                <p class="mt-4 font-semibold">— Fatema, Bogura</p>
            </div>
        </div>
    </div>

    <!-- Dummy Crop Impact Table -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Crop Performance Overview</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
                <thead class="bg-green-100 text-left">
                    <tr>
                        <th class="py-2 px-4">Crop</th>
                        <th class="py-2 px-4">Area Covered (ha)</th>
                        <th class="py-2 px-4">Avg Yield (ton/ha)</th>
                        <th class="py-2 px-4">Farmers</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="py-2 px-4">Rice</td>
                        <td class="py-2 px-4">450</td>
                        <td class="py-2 px-4">4.5</td>
                        <td class="py-2 px-4">500</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="py-2 px-4">Wheat</td>
                        <td class="py-2 px-4">120</td>
                        <td class="py-2 px-4">3.2</td>
                        <td class="py-2 px-4">180</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4">Tomato</td>
                        <td class="py-2 px-4">60</td>
                        <td class="py-2 px-4">2.1</td>
                        <td class="py-2 px-4">75</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="py-2 px-4">Potato</td>
                        <td class="py-2 px-4">90</td>
                        <td class="py-2 px-4">3.8</td>
                        <td class="py-2 px-4">110</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center">
        <h2 class="text-2xl font-bold text-green-700 mb-4">Join SmartAgro Today!</h2>
        <p class="text-gray-600 mb-6">Get AI-driven advice for your farm and increase productivity.</p>
        <a href="{{ route('register') }}" class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700">Get Started</a>
    </div>
</div>
@endsection
