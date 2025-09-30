@extends('layouts.master')

@section('title', 'Diagnosis Result - SmartAgro')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Diagnosis Result</h1>
            <p class="text-gray-600 mt-2">View your crop analysis results</p>
        </div>

        @if($diagnosis)
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Diagnosis Information</h2>
                    <div class="space-y-3">
                        
                        
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600">Created:</span>
                            <span class="text-gray-900">{{ $diagnosis->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        
                    </div>
                </div>
            </div>
        @endif

        @if($html)
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detailed Analysis</h2>
                    <div class="diagnosis-content prose max-w-none">
                        {!! $html !!}
                    </div>
                </div>
            </div>
        @endif

        @endsection