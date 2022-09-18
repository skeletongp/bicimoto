<div>
    <x-modal :fitV="false" maxWidth="max-w-xl" title="Amortizar financiamiento">
        <x-slot name="button">
            <div class="flex space-x-4 items-center">
                <span class="fas fa-dollar-sign"></span>
                <span>Amortizar</span>
            </div>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="$set('amortizar',true)">
                <div class="">
                    <div class="grid grid-cols-5 gap-6">
                        <div class="col-span-2">
                            <x-base-select label="Períodos de pago" wire:model="periodo" id="periodo" class="text-xl">
                                <option value=""></option>
                                <option value="diario">Diario</option>
                                <option value="semanal">Semanal</option>
                                <option value="quincenal">Quincenal</option>
                                <option value="mensual">Mensual</option>
                            </x-base-select>
                            <x-input-error for="periodo"></x-input-error>
                        </div>
                        <div>
                            <x-base-input type="number" wire:model.defer="monto" label="Monto" id="amortMonto">
                            </x-base-input>
                            <x-input-error for="monto"></x-input-error>
                        </div>
                        <div>
                            <x-base-input type="number" wire:model.defer="interes" label="Interés" id="amortInteres">
                            </x-base-input>
                            <x-input-error for="interes"></x-input-error>
                        </div>
                        <div>
                            <x-base-input type="number" wire:model.defer="cuotas" label="Cuotas" id="amortCuotas">
                            </x-base-input>
                            <x-input-error for="cuotas"></x-input-error>
                        </div>


                    </div>
                    <div class="py-2 flex justify-end space-x-4">
                        <x-button class="bg-slate-400">
                            Calcular
                        </x-button>
                       {{--  <x-button class="bg-cyan-600" role="button" wire:click="limpiar">
                            Limpiar
                        </x-button> --}}
                    </div>
                </div>
                <div class="p-2">
                    @if ($pagares)
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 py-2">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">
                                            Fecha
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            Interés
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            Capital
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            Cuota
                                        </th>
                                        <th scope="col" class="py-3 px-6">
                                            Saldo 
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagares as $pago)
                                        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                            <td class="py-2 px-2">
                                                {{ formatDate($pago->fecha,'d-m-Y') }}
                                            </td>
                                            <td class="py-2 px-2">
                                                ${{ formatNumber($pago->interes) }}
                                            </td>
                                            <td class="py-2 px-2">
                                                ${{ formatNumber($pago->capital) }}
                                            </td>
                                            <td class="py-2 px-2">
                                                ${{ formatNumber($pago->debe) }}
                                            </td>
                                            <td class="py-2 px-2">
                                                ${{ formatNumber($pago->restatotal) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-4">
                            {{ $pagares->links() }}
                        </div>
                    @endif
                </div>

            </form>
        </div>
    </x-modal>
</div>
