<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('home') }}
    @endslot
    <div class="px-4 my-4 ">
        <livewire:general.toggle-place />
    </div>
    <div class="grid grid-cols-3 gap-3 px-4">
        <livewire:dashboard.stat-card />
        <livewire:dashboard.stat-week />
        <div class="col-span-3">
            <livewire:dashboard.last-sales />
        </div>

    </div>

</x-app-layout>
