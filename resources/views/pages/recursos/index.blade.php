<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recursos') }}
    @endslot
    @slot('rightButton')
        <div class="flex justify-end items-center space-x-4 pb-2">
            @can('Sumar Productos')
                <a id="addresource" type="button" href="{{ route('recursos.sum') }}"
                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    Sumar
                </a>
            @endcan
            @can('Crear Recursos')
                <livewire:recursos.create-recurso />
            @endcan
            @can('Crear Cuentas')
            @livewire('contables.create-count', ['model' => 'App\Models\Recurso', 'codes' => ['104', '500']], key(uniqid()))
            @endcan
        </div>
    @endslot

    <div class=" mx-auto max-w-6l w-full">

            <div class="flex space-x-4 px-4 items-start">
                <div class="w-full">
                    <livewire:recursos.table-recurso />
                </div>
                <div class="w-full">
                    <livewire:condiments.table-condiment />
                </div>
            </div>
    </div>

</x-app-layout>
