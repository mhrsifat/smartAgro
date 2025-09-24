@extends('layouts.master')

@section('title', 'Fertilizer Suggestor - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-green-700 mb-6">💧 সার পরামর্শ নিন</h1>

    <form id="fertilizerForm" class="space-y-4 bg-white shadow-md rounded-lg p-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">ফসলের নাম *</label>
            <input type="text" name="crop" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">মাটির ধরন *</label>
            <input type="text" name="soil_type" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">জমির পরিমাণ (একর/হেক্টর) *</label>
            <input type="number" step="0.01" name="area" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">অবস্থান</label>
            <input type="text" name="location" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">অতিরিক্ত নোট</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center">
            <span id="btnText">পরামর্শ নিন</span>
            <svg id="spinner" class="animate-spin h-5 w-5 ml-2 hidden text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        </button>
    </form>

    <div id="result" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
        <h2 class="text-xl font-semibold text-green-700 mb-2">📌 পরামর্শ</h2>
        <div id="suggestionText" class="text-gray-800 whitespace-pre-line"></div>
    </div>
</div>

<script>
const fertilizerForm = document.getElementById('fertilizerForm');
const spinner = document.getElementById('spinner');
const btnText = document.getElementById('btnText');
let warnOnLeave = false;

fertilizerForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    spinner.classList.remove('hidden');
    btnText.textContent = 'পরামর্শ তৈরি হচ্ছে...';
    warnOnLeave = true;

    axios.post("{{ route('recommend.fertilizer') }}", formData)
        .then(res => {
            document.getElementById('result').classList.remove('hidden');
            document.getElementById('suggestionText').textContent = res.data.suggestions;
        })
        .catch(err => {
            document.getElementById('result').classList.remove('hidden');
            document.getElementById('suggestionText').textContent = '❌ কোনো সমস্যা হয়েছে। আবার চেষ্টা করুন।';
            console.error(err);
        })
        .finally(() => {
            spinner.classList.add('hidden');
            btnText.textContent = 'পরামর্শ নিন';
            warnOnLeave = false;
        });
});

window.addEventListener('beforeunload', function(e) {
    if (warnOnLeave) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endsection
