<div>
    <x-modal maxWidth="max-w-2xl" title="Cobrar Cuenta" :fitV="true" :listenOpen="true">
        <x-slot name="button">
            Cobrar
        </x-slot>

        <div>
            <form wire:submit.prevent="createTransaction">
                @php
                    asort($countMains);
                @endphp
                <h1 class="text-lg font-bold uppercase my-2">Débito</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-datalist disabled inputId="cMainDebit_id{{$cDetailCredit_id}}" listName="cMainDebit_idList{{$cDetailCredit_id}}" model="cMainDebit_id"
                            label="Cuenta Control" wire:model="cMainDebitName">
                            @foreach ($cobrables as $id => $cMainDebit)
                                <option data-value="{{ $id }}" value="{{ $cMainDebit }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cMainDebit_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist inputId="cDetailDebit_id{{$cDetailCredit_id}}" model="cDetailDebit_id" listName="cDetailDebit_idList{{$cDetailCredit_id}}"
                            label="Cuenta Detalle">
                            @foreach ($countsDebit as $idDebit => $cDebit)
                                {{ $cDebit }}
                                <option data-value="{{ $idDebit }}" value="{{ $cDebit }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cDetailDebit_id"></x-input-error>
                    </div>
                </div>
                <h1 class="text-lg font-bold uppercase my-2 mt-4">Crédito</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-datalist disabled inputId="cMainCredit_id{{$cDetailCredit_id}}" wire:model="cMainCreditName"
                            listName="cMainCredit_idList{{$cDetailCredit_id}}" model="cMainCredit_id" label="Cuenta Control">
                            @foreach ($countMains as $id => $cMain)
                                <option data-value="{{ $id }}" value="{{ $cMain }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cMainCredit_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist disabled wire:model="cDetailCreditName" inputId="cDetailCredit_id{{$cDetailCredit_id}}"
                            listName="cDetailCredit_idList{{$cDetailCredit_id}}" model="cDetailCredit_id" label="Cuenta Detalle">
                            @foreach ($countsCredit as $idCredit => $cCredit)
                                <option data-value="{{ $idCredit }}" value="{{ $cCredit }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cDetailCredit_id"></x-input-error>
                    </div>
                </div>
                <div class="my-4">
                    <x-base-input disabled id="trConcept{{$cDetailCredit_id}}" label="Concepto" wire:model.defer="concept" />
                    <x-input-error for="concept"></x-input-error>
                </div>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-input id="trRef{{$cDetailCredit_id}}" label="Referencia" wire:model.defer="ref" />
                        <x-input-error for="ref"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input id="trAmount{{$cDetailCredit_id}}" label="Monto" wire:model.defer="amount" type="number" />
                        <x-input-error for="amount"></x-input-error>
                    </div>
                </div>
                <div class="flex justify-end my-2">
                    <x-button>Registrar</x-button>
                </div>

            </form>
        </div>
    </x-modal>
</div>