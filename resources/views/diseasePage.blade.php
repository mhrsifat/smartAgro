@extends('layouts.master')

@section('title', '🌱 Crop Disease Scanner')

@section('content')

<div class="min-h-screen flex items-center justify-center py-10">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8"><h2 class="text-2xl font-bold text-gray-800 mb-6">🌱 Crop Disease Scanner</h2>

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
        <!-- AI result appears here -->
        <livewire:diagnosis-listener />
    </div>

</div>

</div><!-- Scripts -->@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script><script>
$(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // current user id from server (null if guest)
    const currentUserId = @json(auth()->id());

    // helper: robustly update UI from a diagnosis payload
    function applyDiagnosisPayload(payload) {
        const status = payload?.status || '';
        const html = payload?.html || '';
        const file = payload?.file || '';

        const target = document.querySelector('#global-diagnosis');
        if (!target) return;

        if (status === 'processing') {
            target.textContent = '⏳ Processing...';
        } else if (status === 'failed') {
            target.textContent = '❌ ' + (html || 'Failed');
        } else if (status === 'completed') {
            // html may contain markup
            target.innerHTML = html || '<div>Diagnosis ready</div>';
            const pb = document.querySelector('#progress-bar');
            const pt = document.querySelector('#progress-text');
            if (pb) pb.style.width = '100%';
            if (pt) pt.textContent = 'All done';

            // dispatch a window event so Livewire / other code can react
            window.dispatchEvent(new CustomEvent('diagnosis-updated', { detail: { status, html, file } }));
        } else {
            // unknown state
            target.textContent = html || 'Waiting for result...';
        }
    }

    // helper: normalize incoming Echo/notification payloads
    function normalizeIncoming(data) {
        // possible shapes:
        // 1) a plain BroadcastMessage: { status, html, file }
        // 2) a Notification object via .notification: { id, type, data: { status, html, file } }
        // 3) a custom event envelope: { notification: { data: { ... } } }

        if (!data) return {};

        if (data.status || data.html || data.file) return data;
        if (data.data && (data.data.status || data.data.html || data.data.file)) return data.data;
        if (data.notification && data.notification.data) return data.notification.data;
        if (data.notification && (data.notification.status || data.notification.html)) return data.notification;

        return {};
    }

    // Subscribe to Echo channels (if Echo is available)
    function setupEchoListeners(userId) {
        if (!userId) {
            console.warn('User not authenticated — Echo private channels will not be joined.');
            return;
        }

        if (!window.Echo) {
            console.warn('Laravel Echo not found on window.Echo — real-time updates will not work.');
            return;
        }

        // Listen to a dedicated diagnosis.* channel if you're broadcasting there
        try {
            window.Echo.private(`diagnosis.${userId}`)
                .listen('DiagnosisUpdated', (e) => {
                    const payload = normalizeIncoming(e);
                    applyDiagnosisPayload(payload);
                    // also show a toast
                    if (typeof showToast === 'function') showToast('Diagnosis updated', 'info');
                })
                .error((err) => console.error('Echo diagnosis channel error', err));
        } catch (err) {
            console.warn('Could not subscribe to diagnosis.{id} channel', err);
        }

        // Also listen to the default user notification channel (Laravel notifications)
        try {
            window.Echo.private(`App.Models.User.${userId}`)
                .notification((notification) => {
                    const payload = normalizeIncoming(notification);
                    applyDiagnosisPayload(payload);
                    // emit a generic notification event for other parts of app
                    window.dispatchEvent(new CustomEvent('notification-received', { detail: { notification } }));
                });
        } catch (err) {
            console.warn('Could not subscribe to App.Models.User.{id} notification channel', err);
        }
    }

    // Initialize echo listeners once (if user authenticated)
    setupEchoListeners(currentUserId);

    // FORM SUBMIT: upload and rely on sockets for result (no polling)
    $('#cropForm').on('submit', function(e) {
        e.preventDefault();
        $('#scanBtn').prop('disabled', true);

        const formData = new FormData(this);
        $('#images-list').empty();
        $('#global-diagnosis').empty();
        $('#progress-container').removeClass('hidden');
        $('#progress-bar').css('width', '0%');
        $('#progress-text').text('0%');

        // show processing until socket replies
        $('#global-diagnosis').text('⏳ Processing...');
        $('#progress-text').text('Processing...');

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

                // IMPORTANT: we DO NOT start polling. The server must broadcast the result
                // when analysis completes. Make sure your backend triggers a Notification
                // or Event that is broadcasted to `diagnosis.{id}` or the user channel.

            },
            error: function(xhr, status, err) {
                $('#scanBtn').prop('disabled', false);
                let msg = xhr.responseJSON?.message || xhr.responseText || err;
                alert('Upload failed: ' + msg);
                $('#progress-text').text('Upload failed');
            }
        });
    });
});
</script>@endpush @endsection

