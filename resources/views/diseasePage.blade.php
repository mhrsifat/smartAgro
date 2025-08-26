<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>🌱 Crop Disease Scanner</title>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- Tailwind (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* small additional styling for the 'prose' container */
    .diagnosis-content { max-width: 100%; white-space: normal; }
  </style>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center py-10">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">🌱 Crop Disease Scanner</h2>

    <form id="cropForm" enctype="multipart/form-data"
      class="flex flex-col md:flex-row items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
      @csrf
      <input type="file" name="images[]" multiple required
             class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring focus:ring-green-300">
      <button type="submit" id="scanBtn" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow">
        🔍 Scan
      </button>
    </form>

    <!-- Global progress (upload + processing) -->
    <div id="progress-container" class="mt-6 hidden">
      <div class="w-full bg-gray-200 rounded-full h-4">
        <div id="progress-bar" class="bg-green-500 h-4 rounded-full w-0"></div>
      </div>
      <p id="progress-text" class="text-gray-600 mt-2">0%</p>
    </div>

    <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-2">📸 Uploaded Images:</h3>
    <div id="images-list" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>

    <h3 class="text-xl font-bold text-green-700 mt-6 mb-4">🧾 AI Diagnosis</h3>
    <div id="diagnosis-list"></div>

    <!-- GLOBAL combined diagnosis container (IMPORTANT) -->
    <div id="global-diagnosis" class="prose bg-white p-4 rounded-lg border border-gray-200 mt-4">
      <!-- Combined AI result will appear here -->
    </div>

  </div>
</div>

<script>
$(function() {
  // Ensure CSRF header for all AJAX calls
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Keep a reference to the poll to avoid duplicates
  let globalPoll = null;

  $('#cropForm').on('submit', function(e) {
    e.preventDefault();

    // prevent double submit
    $('#scanBtn').prop('disabled', true);

    const form = this;
    let formData = new FormData(form);
    $('#images-list').empty();
    $('#diagnosis-list').empty();
    $('#global-diagnosis').empty();
    $('#progress-container').removeClass('hidden');
    $('#progress-bar').css('width', '0%');
    $('#progress-text').text('0%');

    // Abort any previous poll
    if (globalPoll) {
      clearInterval(globalPoll);
      globalPoll = null;
    }

    $.ajax({
      url: "{{ route('disease.analyze') }}",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      xhr: function() {
        const xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(evt) {
          if (evt.lengthComputable) {
            const percent = Math.round((evt.loaded / evt.total) * 100);
            $('#progress-bar').css('width', percent + '%');
            $('#progress-text').text('Uploading: ' + percent + '%');
          }
        }, false);
        return xhr;
      },
      success: function(response) {
        $('#scanBtn').prop('disabled', false);

        if (!response.uploadedImages || response.uploadedImages.length === 0) {
          alert('Server did not return uploaded images.');
          $('#progress-text').text('Upload failed');
          return;
        }

        // Show thumbnails
        response.uploadedImages.forEach(function(fullUrl) {
          const filename = fullUrl.split('/').pop();
          const block = $(`
            <div class="image-block p-2 bg-white rounded shadow-sm">
              <img src="${fullUrl}" class="w-full h-48 object-contain rounded mb-2" />
              <p class="status text-gray-500 mb-2">Uploaded</p>
            </div>
          `);
          block.data('filename', filename);
          $('#images-list').append(block);
        });

        // Start polling for the combined result (single key)
        $('#global-diagnosis').text('⏳ Processing...');
        $('#progress-text').text('Processing...');

        globalPoll = setInterval(function() {
          $.ajax({
            url: "{{ url('/diagnosis-status') }}",
            type: "GET",
            dataType: "json",
            success: function(data) {
              // data: { status: 'processing'|'completed', diagnosis: null|string }
              if (data && data.status === 'completed' && data.diagnosis && data.diagnosis !== 'processing') {
                // Insert returned HTML
                $('#global-diagnosis').html(data.diagnosis);
                $('#progress-bar').css('width', '100%');
                $('#progress-text').text('All done');
                clearInterval(globalPoll);
                globalPoll = null;
              } else if (data && data.status === 'processing') {
                // still processing - keep user informed
                $('#global-diagnosis').text('⏳ Processing...');
              } else if (data && data.diagnosis && data.diagnosis.startsWith('Error:')) {
                $('#global-diagnosis').text('❌ ' + data.diagnosis);
                $('#progress-text').text('Failed');
                clearInterval(globalPoll);
                globalPoll = null;
              } else {
                // unexpected; stop poll to avoid infinite loop
                $('#global-diagnosis').text('❌ No result yet. Check server logs.');
                $('#progress-text').text('Failed');
                clearInterval(globalPoll);
                globalPoll = null;
              }
            },
            error: function() {
              $('#global-diagnosis').text('❌ Network error while checking status.');
              $('#progress-text').text('Failed');
              clearInterval(globalPoll);
              globalPoll = null;
            }
          });
        }, 3000); // poll every 3s
      },
      error: function(xhr, status, err) {
        $('#scanBtn').prop('disabled', false);
        let msg = err || 'Request failed';
        try {
          msg = xhr.responseJSON?.message || xhr.responseText || err;
        } catch(e) {}
        alert('Upload failed: ' + msg);
        $('#progress-text').text('Upload failed');
      }
    });
  });
});
</script>
</body>
</html>
