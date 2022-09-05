<div>
    <x-modal :fitV='false' maxWidth="max-w-xl">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Datos Crediticios del Cliente</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-hand-holding-usd mr-2"></span>
            <span>Crediticios</span>
        </x-slot>
        <div class="relative pt-8">
            <form wire:submit.prevent="createCrediticio">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">

                        <div>
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="number" label="Bienes Inmuebles" placeholder="Valor en RD$"
                                        id="credit_state" wire:model.defer="form.state" />
                                    <x-input-error for="form.state" />
                                </div>
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="number" label="Bienes Muebles" placeholder="Valor en RD$"
                                        id="credit_muebles" wire:model.defer="form.muebles" />
                                    <x-input-error for="form.muebles" />
                                </div>
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="number" label="Alquiler que paga" placeholder="Valor en RD$"
                                    id="credit_rent" wire:model.defer="form.rent" />
                                <x-input-error for="form.rent" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="number" label="Hipotecas que paga" placeholder="Valor en RD$"
                                    id="credit_hipoteca" wire:model.defer="form.hipoteca" />
                                <x-input-error for="form.hipoteca" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="number" label="Préstamos que paga" placeholder="Valor en RD$"
                                    id="credit_loans" wire:model.defer="form.loans" />
                                <x-input-error for="form.loans" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="number" label="Otros gastos mensuales" placeholder="Valor en RD$"
                                    id="credit_others" wire:model.defer="form.others" />
                                <x-input-error for="form.others" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-datalist label="Principal Banco que usa" wire:model="form.bank" inputId="credit_bank" listName="bankList">
                                 @foreach ($banks as $bank)
                                     <option value="{{$bank}}"></option>
                                 @endforeach
     
                                </x-datalist>
                                 <x-input-error for="form.bank" />
                             </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="number" label="Saldo en el banco" placeholder="Valor en RD$"
                                    id="credit_bank_value" wire:model.defer="form.bank_value" />
                                <x-input-error for="form.bank_value" />
                            </div>
                        </div>
                       

                    </div>


                </div>
                <div class="py-3 flex justify-end items-center">
                    <x-button>Guardar</x-button>
                </div>
            </form>
        </div>

    </x-modal>
</div>
