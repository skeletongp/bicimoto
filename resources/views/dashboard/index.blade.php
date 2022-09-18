<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('home') }}
    @endslot
    @slot('rightButton')
        <div class="flex items-center space-x-4">
            <livewire:anticipos.create-anticipo />
            <livewire:general.amortizar />
        </div>
    @endslot
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 px-4 ">
        <livewire:dashboard.stat-card />
        <livewire:dashboard.stat-week />
        <div class="lg:col-span-2">
            <livewire:dashboard.last-sales />
        </div>
        <div class="lg:col-span-1">
            <livewire:dashboard.last-products />
        </div>
    </div>

</x-app-layout>
