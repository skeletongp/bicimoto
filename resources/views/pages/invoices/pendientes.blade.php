<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices.pendientes') }}
    @endslot

    <div class=" w-full max-w-6xl mx-auto  flex flex-col space-y-8 ">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
           <livewire:invoices.cuotas-activas />
        </div>
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:invoices.cuotas-vencidas />
         </div>
    </div>

</x-app-layout>
