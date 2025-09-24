@extends('layouts.master')

@section('title', 'SmartAgro Achievements - Bangladesh')

@section('content')
<div class="container mx-auto px-4 py-12">

    <!-- Header / Hero -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-green-700">🌱 SmartAgro Achievements in Bangladesh</h1>
        <p class="text-gray-600 mt-2">Improving farmer livelihoods, crop yield, and sustainable farming across the country.</p>
    </div>

    <!-- Key Stats / Impact Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 text-center">
        <div class="bg-green-50 shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-green-600">1,200+</h2>
            <p class="text-gray-700 mt-2">Farmers Reached</p>
        </div>
        <div class="bg-green-50 shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-green-600">4,000+</h2>
            <p class="text-gray-700 mt-2">Acres Monitored</p>
        </div>
        <div class="bg-green-50 shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-green-600">3,500+</h2>
            <p class="text-gray-700 mt-2">Pesticide & Fertilizer Recommendations</p>
        </div>
    </div>

    <!-- Regional Achievements -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">📍 Our Work Across Bangladesh</h2>

        <div class="space-y-12">

            <!-- Dhaka -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row items-center gap-6">
                <img src="https://via.placeholder.com/300x200" alt="Dhaka farm" class="rounded-lg md:w-1/2">
                <div class="md:w-1/2">
                    <h3 class="text-2xl font-bold text-green-700">Dhaka Division</h3>
                    <p class="text-gray-700 mt-2">We helped over 320 farmers increase rice yield by 20% in Dhaka through AI crop suggestions and precision fertilizer guidance.</p>
                    <ul class="list-disc list-inside mt-2 text-gray-600">
                        <li>450 hectares of rice monitored</li>
                        <li>Free workshops for 120 farmers</li>
                        <li>Integrated pest management campaigns</li>
                    </ul>
                </div>
            </div>

            <!-- Chattogram -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row-reverse items-center gap-6">
                <img src="https://via.placeholder.com/300x200" alt="Chattogram farm" class="rounded-lg md:w-1/2">
                <div class="md:w-1/2">
                    <h3 class="text-2xl font-bold text-green-700">Chattogram Division</h3>
                    <p class="text-gray-700 mt-2">Chattogram farmers reported healthier tomato crops thanks to SmartAgro's disease detection and timely pesticide advice.</p>
                    <ul class="list-disc list-inside mt-2 text-gray-600">
                        <li>80% of farmers reported improved crop health</li>
                        <li>150 hectares under AI-based monitoring</li>
                        <li>Educational seminars in 5 sub-districts</li>
                    </ul>
                </div>
            </div>

            <!-- Rajshahi -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row items-center gap-6">
                <img src="https://via.placeholder.com/300x200" alt="Rajshahi farm" class="rounded-lg md:w-1/2">
                <div class="md:w-1/2">
                    <h3 class="text-2xl font-bold text-green-700">Rajshahi Division</h3>
                    <p class="text-gray-700 mt-2">Our initiatives helped potato farmers adopt better fertilization schedules, resulting in higher yields and reduced cost.</p>
                    <ul class="list-disc list-inside mt-2 text-gray-600">
                        <li>100 hectares monitored</li>
                        <li>Advised on integrated nutrient management</li>
                        <li>50+ farmers trained on smart irrigation</li>
                    </ul>
                </div>
            </div>

            <!-- Khulna -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row-reverse items-center gap-6">
                <img src="https://via.placeholder.com/300x200" alt="Khulna farm" class="rounded-lg md:w-1/2">
                <div class="md:w-1/2">
                    <h3 class="text-2xl font-bold text-green-700">Khulna Division</h3>
                    <p class="text-gray-700 mt-2">Khulna rice farmers saw increased yield and fewer pest issues due to our combined crop-pest-fertilizer advice system.</p>
                    <ul class="list-disc list-inside mt-2 text-gray-600">
                        <li>90% farmers satisfied with recommendations</li>
                        <li>Integrated pest management workshops</li>
                        <li>Monitoring of 80 hectares of farmland</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Farmer Success Stories -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">👩‍🌾 Farmer Success Stories</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-700">"Thanks to SmartAgro, I could identify the right fertilizer for my wheat crop. My harvest increased by 25%!"</p>
                <p class="mt-4 font-semibold">— Karim, Rajshahi</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-700">"The integrated pest management advice saved my tomato crop from disease. Highly recommended!"</p>
                <p class="mt-4 font-semibold">— Salma, Chattogram</p>
            </div>
        </div>
    </div>

    <!-- Call-to-Action -->
    <div class="text-center">
        <h2 class="text-3xl font-bold text-green-700 mb-4">Join Our Mission!</h2>
        <p class="text-gray-600 mb-6">Be part of SmartAgro and help transform agriculture in Bangladesh.</p>
        <a href="{{ route('register') }}" class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700">Get Started</a>
    </div>
</div>
@endsection
