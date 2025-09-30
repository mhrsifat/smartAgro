<x-app-layout title="Admin Settings">
    <x-headings.page-title title="Admin Settings" />
    
<div class="p-6">

    <!-- Tabs -->
    <div class="flex space-x-4 border-b mb-4">
        <button class="tab-btn py-2 border-b-2 border-transparent" data-tab="profile">Profile</button>
        <button class="tab-btn py-2 border-b-2 border-transparent" data-tab="header">Header</button>
        <button class="tab-btn py-2 border-b-2 border-transparent" data-tab="footer">Footer</button>
        <button class="tab-btn py-2 border-b-2 border-transparent" data-tab="hero">Hero</button>
    </div>

    <!-- Profile -->
    <div class="tab-content" data-content="profile">
        <!-- Your profile form here -->
        <div>Comming...</div>
    </div>

    <!-- Header -->
    <div class="tab-content hidden" data-content="header">
        <!-- Your header form here -->
        <div>Comming...</div>
    </div>

    <!-- Footer -->
    <div class="tab-content hidden" data-content="footer">
        <!-- Your footer form here -->
        <div>Comming...</div>
    </div>

    <!-- Hero -->
    <div class="tab-content hidden" data-content="hero">
       <div>Comming...</div>
    </div>

</div>
    
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;

            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('border-blue-500'));
            tabs.forEach(t => t.classList.add('border-transparent'));

            // Hide all contents
            contents.forEach(c => c.classList.add('hidden'));

            // Activate clicked tab
            tab.classList.remove('border-transparent');
            tab.classList.add('border-blue-500');

            // Show corresponding content
            const activeContent = document.querySelector(`.tab-content[data-content="${target}"]`);
            activeContent.classList.remove('hidden');
        });
    });

    // Optional: activate first tab by default
    tabs[0].click();
});
</script>