@extends('layouts.master')

@section('title', 'Crop Disease Scanner')

@section('content')
<div class="min-h-screen flex items-center justify-center py-10">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">🌱 Crop Disease Scanner</h2>

        <!-- Upload Form -->
        <form id="cropForm" enctype="multipart/form-data"
            class="flex flex-col md:flex-row items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
            @csrf
            <input type="file" name="images[]" multiple required
                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring focus:ring-green-300">
            <button type="submit" id="scanBtn"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow">
                🔍 Scan
            </button>
        </form>

        <!-- Progress -->
        <div id="progress-container" class="mt-6 hidden">
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div id="progress-bar" class="bg-green-500 h-4 rounded-full w-0"></div>
            </div>
            <p id="progress-text" class="text-gray-600 mt-2">0%</p>
        </div>

        <!-- Uploaded Images -->
        <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-2">📸 Uploaded Images:</h3>
        <div id="images-list" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>

        <!-- Combined AI Diagnosis -->
        <h3 class="text-xl font-bold text-green-700 mt-6 mb-4">🧾 AI Diagnosis</h3>
        <div id="global-diagnosis" class="prose bg-white p-4 rounded-lg border border-gray-200 mt-4">
            <div class="text-gray-500">Upload images to start analysis...</div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#cropForm').on('submit', function(e) {
        e.preventDefault();
        $('#scanBtn').prop('disabled', true);

        const formData = new FormData(this);
        $('#images-list').empty();
        $('#global-diagnosis').html('<div class="text-gray-500">⏳ Processing...</div>');
        $('#progress-container').removeClass('hidden');
        $('#progress-bar').css('width', '0%');
        $('#progress-text').text('0%');

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
                    const block = $(
                        `<div class="image-block p-2 bg-white rounded shadow-sm">
                            <img src="${fullUrl}" class="w-full h-48 object-contain rounded mb-2" />
                            <p class="status text-gray-500 mb-2">Uploaded</p>
                        </div>`
                    );
                    block.data('filename', filename);
                    $('#images-list').append(block);
                });

                $('#progress-text').text('Upload complete. Processing...');

                // Start polling for guest users only (if not authenticated)
                if (!window.currentUserId && response.userKey) {
                    pollDiagnosis(response.userKey);
                }
            },
            error: function(xhr, status, err) {
                $('#scanBtn').prop('disabled', false);
                let msg = xhr.responseJSON?.message || xhr.responseText || err;
                alert('Upload failed: ' + msg);
                $('#progress-text').text('Upload failed');
                console.error('Upload error:', xhr, status, err);
            }
        });
    });

    // Polling function for guests
    function pollDiagnosis(userKey) {
        const interval = setInterval(() => {
            $.getJSON(`/diagnosis/poll/${userKey}`, function(res) {
                if (!res) return;
                if (res.status === 'completed' || res.status === 'failed') {
                    // show html if available, otherwise show diagnosis or message
                    const html = res.html ?? res.diagnosis ?? ('<div class="text-gray-500">No result</div>');
                    $('#global-diagnosis').html(html);
                    $('#progress-text').text('Done');
                    clearInterval(interval);
                } else {
                    $('#progress-text').text('Analyzing...Please wait...');
                }
            }).fail(function(err) {
                console.error('Poll error', err);
            });
        }, 3000); // 3s interval
    }
});
</script>
@endpush

@endsection