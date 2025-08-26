<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AI Crop Recommendation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-6">🌾 AI Crop Recommendation</h1>

    <form id="suggestForm" class="space-y-4 bg-white shadow-md rounded-xl p-6">
      <!-- Soil type -->
      <div>
        <label for="soil_type" class="block font-medium mb-1">Soil type</label>
        <select id="soil_type" name="soil_type" required
          class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500">
          <option value="">-- select soil type --</option>
          <option value="sandy">Sandy</option>
          <option value="loamy">Loamy</option>
          <option value="clay">Clay</option>
          <option value="silty">Silty</option>
          <option value="peaty">Peaty</option>
        </select>
      </div>

      <!-- Area input with dynamic unit -->
      <div>
        <label class="block font-medium mb-1">Area</label>
        <div class="flex gap-2">
          <input id="area" name="area" type="number" step="0.01" required
            class="flex-1 border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500"
            placeholder="e.g. 0.50">
          <select id="area_unit" name="area_unit"
            class="w-32 border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500">
            <option value="decimal">Decimal</option>
            <option value="bigha">Bigha</option>
            <option value="hectare">Hectare</option>
            <option value="acre">Acre</option>
            <option value="katha">Katha</option>
          </select>
        </div>
      </div>

      <!-- Location selector (Bangladesh districts) -->
      <div>
        <label for="location" class="block font-medium mb-1">Location (Bangladesh)</label>
        <select id="location" name="location"
          class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500">
          <option value="">-- select district --</option>
        </select>
      </div>

      <!-- Previous crop -->
      <div>
        <label for="previous_crop" class="block font-medium mb-1">Previous crop (if any)</label>
        <input id="previous_crop" name="previous_crop" type="text"
          class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500"
          placeholder="e.g. rice, maize">
      </div>

      <!-- Notes -->
      <div>
        <label for="notes" class="block font-medium mb-1">Additional notes</label>
        <textarea id="notes" name="notes" rows="4"
          class="w-full border-gray-300 rounded-lg p-2 focus:ring-green-500 focus:border-green-500"
          placeholder="Pests, irrigation, market preference..."></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex gap-2 pt-2">
        <button id="submitBtn" type="submit"
          class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50">
          Get Recommendation
        </button>
        <button id="clearBtn" type="button"
          class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">Clear</button>
      </div>
    </form>

    <!-- Output -->
    <div id="output" class="hidden mt-6 p-4 bg-green-50 border border-green-200 rounded-lg"></div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('suggestForm');
  const output = document.getElementById('output');
  const submitBtn = document.getElementById('submitBtn');
  const clearBtn = document.getElementById('clearBtn');
  const locationSelect = document.getElementById('location');

  // Preload Bangladesh districts
  const districts = [
    "Dhaka","Chattogram","Rajshahi","Khulna","Barishal","Sylhet","Rangpur","Mymensingh",
    "Cumilla","Noakhali","Gazipur","Narayanganj","Bogura","Pabna","Faridpur","Jashore",
    "Kushtia","Dinajpur","Tangail","Kishoreganj","Cox's Bazar","Feni","Lakshmipur","Jhenaidah"
    // add more if needed
  ];
  districts.forEach(d => {
    const opt = document.createElement('option');
    opt.value = d;
    opt.textContent = d;
    locationSelect.appendChild(opt);
  });

  function setLoading(on) {
    submitBtn.disabled = on;
    submitBtn.textContent = on ? 'Thinking...' : 'Get Recommendation';
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    output.classList.add('hidden');
    output.textContent = '';

    const data = {
      soil_type: document.getElementById('soil_type').value,
      area: document.getElementById('area').value,
      area_unit: document.getElementById('area_unit').value,
      location: document.getElementById('location').value,
      previous_crop: document.getElementById('previous_crop').value,
      notes: document.getElementById('notes').value
    };

    setLoading(true);

    try {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      const res = await fetch('/api/recommend-crop', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });

      if (!res.ok) {
        const txt = await res.text();
        throw new Error('Server error: ' + res.status + ' — ' + txt);
      }

      const json = await res.json();
      output.classList.remove('hidden');
      output.textContent = json.suggestions || JSON.stringify(json, null, 2);

    } catch (err) {
      output.classList.remove('hidden');
      output.textContent = 'Request failed: ' + err.message;
    } finally {
      setLoading(false);
    }
  });

  clearBtn.addEventListener('click', function () {
    form.reset();
    output.classList.add('hidden');
    output.textContent = '';
  });
});
</script>
</body>
</html>
