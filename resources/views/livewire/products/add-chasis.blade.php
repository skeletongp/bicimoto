<div>
    <x-modal title="" :fitV="false" maxWidth="max-w-2xl" >
        <x-slot name="button">
            <div>
                <span class="far fa-plus"></span>
                <span>Registrar Chasis</span>
            </div>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="addChasis">
                <div class="grid grid-cols-3 gap-4">
                    
                    
                    <div class="">
                        <x-base-input class="text-base font-bold" type="text" wire:model.defer="chasis.tipo" label="Tipo"
                            id="chasis.tipo">
                        </x-base-input>
                        <x-input-error for="chasis.tipo"></x-input-error>
                    </div>
                    <div class="">
                        <x-base-input class="text-base font-bold" type="text" wire:model.defer="chasis.marca" label="Marca"
                            id="chasis.marca">
                        </x-base-input>
                        <x-input-error for="chasis.marca"></x-input-error>
                    </div>
                    <div class="">
                        <x-base-input class="text-base font-bold" type="text" wire:model.defer="chasis.modelo" label="Modelo"
                            id="chasis.modelo">
                        </x-base-input>
                        <x-input-error for="chasis.modelo"></x-input-error>
                    </div>
                    <div class="">
                        <x-base-input class="text-base font-bold" type="text" wire:model.defer="chasis.color" label="Color"
                            id="chasis.color">
                        </x-base-input>
                        <x-input-error for="chasis.color"></x-input-error>
                    </div>
                    <div class="">
                        <x-base-input class="text-base font-bold" type="text" wire:model.defer="chasis.chasis" label="Chasis"
                            id="chasis.chasis">
                        </x-base-input>
                        <x-input-error for="chasis.chasis"></x-input-error>
                    </div>
                    <div class="">
                        <x-base-input class="text-base font-bold" type="number" wire:model.defer="chasis.year" label="Año"
                            id="chasis.year">
                        </x-base-input>
                        <x-input-error for="chasis.year"></x-input-error>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-4">
                    <x-button type="submit" class="bg-cyan-700 hover:bg-cyan-800 flex space-x-4 items-center">
                        <span class="far fa-save"></span>
                        <span>Guardar</span>
                    </x-button>
                    <x-button  type="button" wire:click.prevent="createChasis"  class="bg-blue-700 hover:bg-blue-800 flex space-x-4 items-center">
                        <span class="far fa-check-circle"></span>
                        <span>Finalizar</span>
                    </x-button>
                </div>
            </form>
            <div class="py-4">
                {{-- Create table for chasisGroup --}}
                
<div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-2">
                    Chasis
                </th>
                <th scope="col" class="py-3 px-2">
                    Marca
                </th>
                <th scope="col" class="py-3 px-2">
                    Modelo
                </th>
                <th scope="col" class="py-3 px-2">
                    Color
                </th>
                <th scope="col" class="py-3 px-2">
                    Año
                </th>
               
            </tr>
        </thead>
        <tbody>
            @forelse ($chasisGroup as $chasisItem)
            <tr class="bg-white border-b  hover:bg-gray-50 text-gray-900">
                <th scope="row" class="py-3 px-2 font-medium  whitespace-nowrap ">
                    {{ $chasisItem['chasis'] }}
                </th>
                <td class="py-3 px-2">
                    {{ $chasisItem['marca'] }}
                </td>
                <td class="py-3 px-2">
                    {{ $chasisItem['modelo'] }}
                </td>
                <td class="py-3 px-2">
                    {{ $chasisItem['color'] }}
                </td>
                <td class="py-3 px-2">
                    {{ $chasisItem['year'] }}
                </td>
               
                
            </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <h1 class="text-center my-2 font-bold uppercase">No ha añadido ningún chasis.</h1>
                    </td>
                </tr>
            @endforelse
            
        </tbody>
    </table>
</div>

            </div>
        </div>
    </x-modal>
</div>
