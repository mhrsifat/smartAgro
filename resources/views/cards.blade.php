<x-app-layout>
<div class="container mx-auto grid">
    <x-headings.page-title title="Cards" />

    <x-ui.cta url="/cta" icon="icons.star" text="Do this now" cta="Start" />

    <x-headings.section-title title="Big section cards" />
    <x-ui.text-card>
    Large, full width sections goes here Large, full width sections goes here Large, full width sections goes here
    </x-ui.text-card>

    <x-headings.section-title title="Responsive cards" />
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card icon="icons.users" text="Clients" value="44" />
        <x-ui.metric-card icon="icons.money" bg-color="bg-green-100" text-color="text-green-500" text="Balance" value="£55" />
        <x-ui.metric-card icon="icons.cart" bg-color="bg-blue-100" text-color="text-blue-500" text="Sales" value="66" />
        <x-ui.metric-card icon="icons.chat" bg-color="bg-teal-100" text-color="text-teal-500" text="Contacts" value="77" />
    </div>

    <x-headings.section-title title="Cards with title" />
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <x-ui.title-card title="Card title">
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Fuga, cum commodi a omnis numquam quod? Totam exercitationem quos hic ipsam at qui cum numquam, sed amet ratione! Ratione, nihil dolorum.
        </x-ui.title-card>
        <x-ui.title-card title="Purple title" text-color="text-white" bg-color="bg-purple-600">
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Fuga, cum commodi a omnis numquam quod? Totam exercitationem quos hic ipsam at qui cum numquam, sed amet ratione! Ratione, nihil dolorum.
        </x-ui.title-card>
    </div>
</div>
</x-app-layout>
