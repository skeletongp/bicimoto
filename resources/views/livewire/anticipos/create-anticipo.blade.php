<div>
    <x-modal :fitV="false" :listenOpen="true" maxWidth="max-w-xl" title="Registrar anticipo">
        <x-slot name="button">
            <div class="flex space-x-4 items-center">
                <span class="fas fa-plus"></span>
                <span>Anticipo</span>
            </div>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="createAnticipo">
                <div class="">
                    <div class="grid grid-cols-4 gap-6">
                        <div class="col-span-2">
                            <x-base-select class="text-xl" wire:model="payway" label="Forma de Pago" id="anticipopayway">
                                <option value="Efectivo">Efectivo</option>
                                @if (count($banks))
                                    <option value="Transferencia">Transferencia</option>
                                @endif
                                <option value="Tarjeta">Tarjeta</option>
                            </x-base-select>
                        </div>
                        @switch($payway)
                            @case('Efectivo')
                                <div class="col-span-2">
                                    <x-base-input class="text-xl py-2 font-bold" type="number" {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}}
                                        wire:model.lazy="anticipo.efectivo" label="Efectivo" id="formanticipo.efectivo">
                                    </x-base-input>
                                    <x-input-error for="anticipo.efectivo"></x-input-error>
                                </div>
                            @break

                            @case('Transferencia')
                                <div class="col-span-2">
                                    <x-base-input class="text-xl py-2 font-bold" {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}} type="number"
                                        wire:model.lazy="anticipo.transferencia" label="Transferencia"
                                        id="formanticipo.transferencia"></x-base-input>
                                    <x-input-error for="anticipo.transferencia"></x-input-error>
                                </div>

                                <div class="col-span-2">
                                    <x-base-select id="anticipobank_id" wire:model="bank_id" label="Banco" class="py-3">
                                        <option value=""></option>
                                        @foreach ($banks as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </x-base-select>
                                    <x-input-error for="bank">Seleccione un Banco</x-input-error>
                                </div>
                                <div class="col-span-2">
                                    <x-base-input class="text-sm py-3" type="text" wire:model.lazy="reference"
                                        label="No. Referencia" id="anticipo.reference" placeholder="Nº. Ref."></x-base-input>
                                    <x-input-error for="reference">Requerido</x-input-error>
                                </div>
                            @break

                            @case('Tarjeta')
                                <div class="col-span-2">
                                    <x-base-input class="text-xl py-2 font-bold" type="number" {{-- status="{{ $createCuota > 0 ? 'disabled' : '' }}" --}}
                                        wire:model.lazy="anticipo.tarjeta" label="Tarjeta/Cheque" id="anticipo.tarjeta">
                                    </x-base-input>
                                    <x-input-error for="anticipo.tarjeta"></x-input-error>
                                </div>
                            @break

                            @default
                        @endswitch
                        
                        <div class="col-span-2">
                            <x-datalist class="py-2" placeholder="Seleccione un cliente" listName="clientListInvoice" inputId="clientAncticipoID" model="clientId" >
                                @foreach ($clients as $index=> $clte)
                                    <option data-value="{{$index}}" value="{{$index.' - '.$clte}}" ></option>
                                @endforeach
                            </x-datalist>
                            <x-input-error for="client">Elige un cliente</x-input-error>
                        </div>
                    </div>
                    <div class="py-4 flex justify-end">
                        <x-button>
                            Guardar
                        </x-button>
                    </div>
                </div>

            </form>
        </div>
    </x-modal>
</div>
