<div class="grid grid-cols-2 gap-6">
    <div class="flex flex-col space-y-4">
        <div class=" overflow-x-auto shadow-md sm:rounded-lg">
            @livewire('productions.production-condiment', ['production' => $production], key(uniqid()))
        </div>
        <div class=" overflow-x-auto shadow-md sm:rounded-lg">
            <div class=" overflow-x-auto shadow-md sm:rounded-lg">
                @livewire('productions.production-recurso', ['production' => $production], key(uniqid()))
            </div>
        </div>
    </div>
    <div>
        <div class=" overflow-x-auto shadow-md sm:rounded-lg">
            <h1 class="text-center font-bold uppercase text-xl my-4">Productos obtenidos</h1>
            @can('Añadir Resultados')
                @if ($production->status !== 'Completado')
                    <div class="m-4">
                        @livewire('productions.get-product-from-production', ['production' => $production], key(uniqid()))
                    </div>
                @endif
            @endcan
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Atributo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cantidad
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Costo Unt.
                        </th>
                        @if ($production->status !== 'Completado')
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($production['products'] as $product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $product['productible']['name'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $product['unitable']['name'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ formatNumber($product['cant']) }}
                            </td>
                            <td class="px-6 py-4">
                                ${{ formatNumber($production['costUnit']) }}
                            </td>
                            @if ($production->status !== 'Completado')
                            <td class="px-6 py-4 text-right">
                                <div class="flex space-x-6">
                                    <span class="far fa-pen text-blue-300"></span>
                                    <span class="far fa-trash text-red-400  cursor-pointer"
                                        wire:click="confirm('¿Eliminar resultado?','deleteProduct', {{ $product['id'] }},'Crear Usuarios')"></span>
                                </div>
                            </td>
                        @endif
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
