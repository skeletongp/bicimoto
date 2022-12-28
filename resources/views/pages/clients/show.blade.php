<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients.show', $client) }}
    @endslot
    @slot('rightButton')
   <div class="flex space-x-4 items-center">
    @livewire('clients.update-or-create-relacionado', ['client' => $client], key(uniqid()))
    @livewire('clients.update-or-create-laboral', ['client' => $client], key(uniqid()))
    @livewire('clients.update-or-create-crediticio', ['client' => $client], key(uniqid()))
   </div>
    @endslot
    <div class="w-full mx-auto my-5 p-4">
        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
            <!-- Left Side -->
           <div class="w-full">
                {{-- Personal Data --}}

                @include('pages.clients.includes.personal-data')

                {{-- End of Personal Data --}}

                <div class="my-4"></div>

                <!-- Experience and education -->
                <div class="bg-white p-3 shadow-sm rounded-sm">

                    <div class=" py-2 mx-auto min-h-max h-full relative ">
                        @livewire('clients.client-invoice', ['client' => $client], key(uniqid()))
                    </div>
                    <!-- End of Experience and education grid -->
                </div>
                <!-- End of profile tab -->
            </div>
            <!-- Right Side -->
            <div class="w-full ">
                <!-- Profile Card -->
                <div class="bg-white border-t-4 border-gray-200">
                    @include('pages.clients.includes.relacionado-data')
                    @include('pages.clients.includes.laboral-data')
                    @include('pages.clients.includes.crediticio-data')

                </div>
                <div class=" py-2 mx-auto  relative space-y-4 ">
                    @livewire('invoices.cuotas-vencidas', ['client_id' => $client->id], key(uniqid()))
                    @livewire('anticipos.anticipo-list', ['client_code' => $client->code], key(uniqid()))
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
