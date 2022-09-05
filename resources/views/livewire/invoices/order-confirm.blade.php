<div class="">


    <x-modal id="modalConfirmInvoice" maxWidth="max-w-3xl" :listenOpen="true" :clickAway="false" :open="$instant">
        <x-slot name="button">
            <span>
                Cobrar
            </span>
        </x-slot>
        <x-slot name="title">
            Cobrar Pedido
        </x-slot>
        <div class="flex justify-end">
            <x-toggle label="Crear Cuotas" id="form{{ $form['id'] }}.createCuota" value="1"
                wire:model="createCuota"></x-toggle>
        </div>
        <form wire:submit.prevent="tryPayInvoice"
            class="grid grid-cols-5 gap-4 p-3 max-w-3xl mx-auto text-left relative pt-16">
            {{-- Vendedor --}}
            {{-- <div class="absolute top-0 right-2">
                <x-base-input type="number" class="w-12 text-right" placeholder="Copias" label="" wire:model="copyCant"></x-base-input>
            </div> --}}
            <div class="col-span-2">
                <x-base-input class="text-xl font-bold" label="Vendedor" id="form{{ $form['id'] }}.seller" disabled
                    wire:model="form.seller.name">
                    </x-input>
            </div>
            <div class="col-span-2">
                <x-base-input class="text-xl font-bold" label="Cliente" id="form{{ $form['id'] }}.client" disabled
                    wire:model="form.name">
                    </x-input>
            </div>


            {{-- Montos --}}
            <div>
                <x-base-input class="text-xl font-bold" type="number" disabled wire:model.lazy="form.amount"
                    label="Subtotal" id="form{{ $form['id'] }}.amount">
                </x-base-input>
            </div>

            <div>
                <x-base-input class="text-xl font-bold" type="number" disabled wire:model.lazy="form.tax"
                    label="Impuestos" id="form{{ $form['id'] }}.tax"></x-base-input>
            </div>
            <div>
                <x-base-input class="text-xl font-bold" type="number" disabled wire:model.lazy="form.discount"
                    label="Descuento" id="form{{ $form['id'] }}.discount"></x-base-input>
                <x-input-error for="form.rest"></x-input-error>
            </div>
            <div>
                <x-base-input class="text-xl font-bold text-green-600" type="number" disabled
                    wire:model.lazy="form.total" label="Total" id="form{{ $form['id'] }}.total"></x-base-input>
                <x-input-error for="form.total"></x-input-error>
            </div>

            {{-- Campos de cobro --}}
                <div class="col-span-2">
                    <x-base-select class="text-xl" wire:model="payway" label="Forma de Pago"
                        id="{{ $form['id'] }}payway">
                        <option value="Efectivo">Efectivo</option>
                        @if ($banks->count())
                            <option value="Transferencia">Transferencia</option>
                        @endif
                        <option value="Tarjeta">Tarjeta</option>
                    </x-base-select>
                </div>
                @switch($payway)
                    @case('Efectivo')
                        <div class="col-span-2">
                            <x-base-input class="text-xl font-bold" type="number"
                                {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}} wire:model.debounce.300ms="form.efectivo"
                                label="Efectivo" id="form{{ $form['id'] }}.efectivo">
                            </x-base-input>
                            <x-input-error for="form.efectivo"></x-input-error>
                        </div>
                    @break

                    @case('Transferencia')
                        <div class="col-span-2">
                            <x-base-input class="text-xl font-bold" {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}}
                                type="number" wire:model.debounce.300ms="form.transferencia" label="Transferencia"
                                id="form{{ $form['id'] }}.transferencia"></x-base-input>
                            <x-input-error for="form.transferencia"></x-input-error>
                        </div>

                        <div class="col-span-2">
                            <x-base-select id="{{ $form['id'] }}bank_id" wire:model="bank_id" label="Banco" class="py-3">
                                <option value=""></option>
                                @foreach ($banks as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="bank">Seleccione un Banco</x-input-error>
                        </div>
                        <div>
                            <x-base-input class="text-sm py-3" type="text" wire:model.lazy="reference" label="No. Referencia"
                                id="f{{ $form['id'] }}.reference" placeholder="Nº. Ref."></x-base-input>
                            <x-input-error for="reference">Requerido</x-input-error>
                        </div>
                    @break

                    @case('Tarjeta')
                        <div class="col-span-2">
                            <x-base-input class="text-xl font-bold" type="number"
                                {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}} wire:model.debounce.300ms="form.tarjeta"
                                label="Tarjeta/Cheque" id="form{{ $form['id'] }}.tarjeta">
                            </x-base-input>
                            <x-input-error for="form.tarjeta"></x-input-error>
                        </div>
                    @break

                    @default
                @endswitch



            <div class="{{$createCuota?'col-span-2':'col-span-3'}} space-y-3">
                <x-base-input class="text-xl font-bold" type="text" wire:model.lazy="form.note" label="Nota"
                    id="form{{ $form['id'] }}.note" placeholder="Ingrese una nota a la factura"></x-base-input>
            </div>
            {{-- Texto Grande Pagado y cambio --}}
            <div class="flex flex-col space-y-4 col-span-5">
                @if (array_key_exists('payed', $form) && $form['payed'] > 0)
                    <div class="flex space-x-4 items-center">
                        <span class="text-2xl font-bold text-gray-900">PAGADO</span>
                        <span class="text-2xl font-bold text-gray-900">RD${{ formatNumber($form['payed']) }}</span>
                    </div>
                @endif
                @if (array_key_exists('rest', $form) && $form['rest'] > 0)
                    <div class="flex space-x-4 items-center">
                        <span class="text-2xl font-bold text-red-500">DEBE</span>
                        <span class="text-2xl font-bold text-red-500">RD${{ formatNumber($form['rest']) }}</span>
                    </div>
                @endif
                @if (array_key_exists('cambio', $form) && $form['cambio'] > 0)
                    <div class="flex space-x-4 items-center">
                        <span class="text-2xl font-bold text-green-500">CAMBIO</span>
                        <span class="text-2xl font-bold text-green-500">RD${{ formatNumber($form['cambio']) }}</span>
                    </div>
                @endif
            </div>

            {{-- Crear Cuotas --}}
            @if ($createCuota && $form['rest'] > 0)
                <div>
                    <x-base-input class="text-xl font-bold" type="number" wire:model="form.rest" readonly
                        label="Monto" id="form{{ $form['id'] }}.rest">
                    </x-base-input>
                    <x-input-error for="form.rest"></x-input-error>
                </div>
                <div>
                    <x-base-input class="text-xl font-bold" type="number"
                       {{--  status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}} wire:model.debounce.300ms="interes"
                        label="Interés (%)" id="form{{ $form['id'] }}.interes">
                    </x-base-input>
                    <x-input-error for="interes"></x-input-error>
                </div>
                <div>
                    <x-base-input class="text-xl font-bold" type="number"
                        {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}} wire:model.debounce.300ms="cuotas"
                        label="No. de Cuotas" id="form{{ $form['id'] }}.cuotas">
                    </x-base-input>
                    <x-input-error for="cuotas"></x-input-error>
                </div>
                <div class="col-span-2">
                    <x-base-select label="Períodos de pago" wire:model="periodo"
                        id="form{{ $form['id'] }}.periodo" class="text-xl">
                        <option value=""></option>
                        <option value="diario">Diario</option>
                        <option value="semanal">Semanal</option>
                        <option value="quincenal">Quincenal</option>
                        <option value="mensual">Mensual</option>
                    </x-base-select>
                    <x-input-error for="periodo"></x-input-error>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="text" wire:model.defer="tipo" 
                        label="Tipo" id="form{{ $form['id'] }}.tipo">
                    </x-base-input>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="text" wire:model.defer="marca" 
                        label="Marca" id="form{{ $form['id'] }}.marca">
                    </x-base-input>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="text" wire:model="modelo" 
                        label="Modelo" id="form{{ $form['id'] }}.modelo">
                    </x-base-input>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="text" wire:model.defer="color" 
                        label="Color" id="form{{ $form['id'] }}.color">
                    </x-base-input>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="text" wire:model.defer="chasis" 
                        label="Chasis" id="form{{ $form['id'] }}.chasis">
                    </x-base-input>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="number" wire:model.defer="year" 
                        label="Año" id="form{{ $form['id'] }}.year">
                    </x-base-input>
                </div>
                <div class="">
                    <x-base-input class="text-xl font-bold" type="text" wire:model.defer="placa" 
                        label="Placa" id="form{{ $form['id'] }}.placa">
                    </x-base-input>
                </div>
            @endif
            {{-- Fin de texto grande --}}
            <button
                class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
                wire:loading wire:target="tryPayInvoice">
                <x-loading></x-loading>
            </button>
            <div class="col-span-5 flex justify-end">
                <x-button wire:loading.attr="disabled">
                    Cobrar
                </x-button>
            </div>
        </form>
    </x-modal>

    @push('js')
        <script>
            var prevVal = 0;

            function clrInput(event) {
                input = event.target;
                prevVal = input.value;
                input.value = '';
            }

            function restoreInput(event) {
                input = event.target;
                input.value = prevVal;
            }
        </script>
    @endpush

</div>
