<div class="w-full">
    <div class=" py-2 flex-col space-y-4 max-w-4xl min-h-max h-full relative ">
        <div>
            <div class="flex space-x-4 items-start mt-8">
                <div class="w-full max-w-[10rem">
                    <x-base-select status="{{$confirmed?'disabled':''}}" label="" id="payWay" wire:model="payway">
                       <option value="">Forma de pago</option>
                        <option>Efectivo</option>
                        <option>Transferencia</option>
                        <option>Otra</option>
                    </x-base-select>
                    <x-input-error for="payway">Campo requerido</x-input-error>
                </div>
                <div class="w-full">
                    <x-base-input status="{{$confirmed?'disabled':''}}" type="number" id="amount" label="" placeholder="Monto total pagado"
                        wire:model.lazy="amount"></x-base-input>
                    <x-input-error for="amount"></x-input-error>
                </div>
               
                    <div class="w-full">
                        <x-base-select status="{{$payway == 'Transferencia'?'':'disabled'}}" label="" wire:model="bank_id" id="outBankId">
                            <option value="">Banco</option>
                            @foreach ($banks as $ide => $bank)
                                <option value="{{ $ide }}">{{ $bank }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="bank_id">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input status="{{$payway == 'Transferencia'?'':'disabled'}}" type="text" id="outBankRef" label=""
                            placeholder="Nro. de Referencia" wire:model.defer="ref">
                        </x-base-input>
                        <x-input-error for="ref">Campo requerido</x-input-error>
                    </div>
                
                <div class="pt-2">
                    <x-button wire:click="$toggle('confirmed')">
                        {{$confirmed?'Reset':'Confirmar'}}
                    </x-button>
                </div>

            </div>
        </div>
        <div class="flex flex-col space-y-4">
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-left text-gray-800">
                    <thead class=" uppercase bg-gray-200 font-bold ">
                        <tr class="px-4">
                           
                            <th scope="col" class="py-1 px-2">
                                Fecha
                            </th>
                            <th scope="col" class="py-1 px-2">
                                Interés
                            </th>
                            <th scope="col" class="py-1 px-2">
                                Capital
                            </th>
                            <th scope="col" class="py-1 px-2">
                                Mora
                            </th>
                            <th scope="col" class="py-1 px-2">
                                Cuota
                            </th>
                            <th scope="col" class="py-1 px-2">
                                <span class="sr-only">Acción</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cuotas as $cuota)
                            <tr class="bg-white border-b even:bg-gray-100 ">
                                <th scope="row" class="py-2 px-2  whitespace-nowrap e">
                                    {{ \Carbon\Carbon::parse($cuota->fecha)->format('d/m/Y') }}
                                </th>
                               
                                <td class="py-2 px-2">
                                    ${{ formatNumber($cuota->interes) }}
                                </td>
                                <td class="py-2 px-2">
                                    ${{ formatNumber($cuota->capital) }}
                                </td>
                                <td class="py-2 px-2">
                                    ${{ formatNumber($cuota->mora) }}
                                </td>
                                <td class="py-2 px-2">
                                    ${{ formatNumber($cuota->debe) }}
                                </td>
                                <td class="py-2 px-2">
                                    @if ($cuota->status =='pendiente')
                                    <span wire:click.prevent="payCuota({{ $cuota->id }})" 
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline cursor-pointer">
                                        Cobrar
                                    </span>
                                        
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr class="bg-white border-b even:bg-gray-100 ">
                                    <td colspan="6" class="py-2 px-2">
                                        No hay cuotas para cobrar
                                    </td>
                                </tr>
                        @endforelse
                        @if (count($cuotas))
                        <tr class="bg-white border-b even:bg-gray-100 ">
                            <th scope="row" class="py-2 px-2  whitespace-nowrap e">
                                
                            </th>
                            <td scope="row" class="py-2 px-2 ">
                            </td>
                            <td class="py-2 px-2">
                            </td>
                            <td class="py-2 px-2">
                            </td>
                            <td class="py-2 px-2 font-bold">
                                ${{ formatNumber($total) }}
                            </td>
                            <td class="py-2 px-2">
                               
                            </td>
                        </tr>
                            
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
            <div wire:loading wire:target="payCuota">
                <x-loading></x-loading>
            </div>
    </div>
 
</div>
