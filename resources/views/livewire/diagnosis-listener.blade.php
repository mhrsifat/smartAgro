<div>
    {{-- This component reflects the current AI diagnosis (keeps server-side state) --}}
    @if($status === 'processing')
        <div class="text-gray-600">⏳ Processing...</div>
    @elseif($status === 'completed')
        <div class="prose bg-white p-4 rounded-lg border border-gray-200">
            {!! $html !!}
        </div>

        @if($file)
            <div class="mt-2 text-sm">
                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-green-600 underline">Download result file</a>
            </div>
        @endif
    @elseif($status === 'failed')
        <div class="text-red-600">❌ {{ $html }}</div>
    @endif
</div>

<script>
document.addEventListener('livewire:load', function () {
    // Update your existing DOM nodes (#global-diagnosis, progress bar) when Livewire receives a broadcast
    window.addEventListener('diagnosis-updated', function (e) {
        const status = e.detail.status || '';
        const html = e.detail.html || '';

        if (html) {
            const target = document.querySelector('#global-diagnosis');
            if (target) target.innerHTML = html;
        } else {
            const target = document.querySelector('#global-diagnosis');
            if (target) {
                if (status === 'processing') target.textContent = '⏳ Processing...';
                else if (status === 'failed') target.textContent = '❌ Failed';
            }
        }

        if (status === 'completed') {
            const pb = document.querySelector('#progress-bar');
            const pt = document.querySelector('#progress-text');
            if (pb) pb.style.width = '100%';
            if (pt) pt.textContent = 'All done';
            if (typeof showToast === 'function') showToast('Diagnosis ready', 'success');
        }
    });

    window.addEventListener('notification-received', function (e) {
        const n = e.detail.notification;
        if (typeof showToast === 'function') {
            // notification payload shape varies; attempt to display a sensible message
            const msg = (n && n.data && (n.data.message || n.data.status)) || 'New notification';
            showToast(msg, 'info');
        }
    });
});
</script>