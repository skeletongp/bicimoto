<div>
    <x-modal title="Editar Contrato" :fitV='false' maxWidth="max-w-xl" :listenOpen="true">
        <x-slot name="button">
            Editar
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="editContrato" class="flex flex-col space-y-4">
                <div class="grid grid-cols-3 gap-6 ">
                    <div class="w-full overflow-hidden">
                        <x-base-input type="text" label="Tipo" id="contrato.tipo" wire:model.defer="contrato.tipo" />
                        <x-input-error for="contrato.tipo" />
                    </div>
                    <div class="w-full overflow-hidden">
                        <x-base-input type="text" label="Marca" id="contrato.marca"
                            wire:model.defer="contrato.marca" />
                        <x-input-error for="contrato.marca" />
                    </div>
                    <div class="w-full overflow-hidden">
                        <x-base-input type="text" label="Modelo" id="contrato.modelo"
                            wire:model.defer="contrato.modelo" />
                        <x-input-error for="contrato.modelo" />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div class="w-full overflow-hidden">
                        <x-base-input type="text" label="Color" id="contrato.color"
                            wire:model.defer="contrato.color" />
                        <x-input-error for="contrato.color" />
                    </div>
                    <div class="w-full overflow-hidden col-span-2">
                        <x-base-input type="text" label="Chasis" id="contrato.chasis"
                            wire:model.defer="contrato.chasis" />
                        <x-input-error for="contrato.chasis" />
                    </div>

                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div class="w-full overflow-hidden">
                        <x-base-input type="text" label="Placa" id="contrato.year"
                            wire:model.defer="contrato.year" />
                        <x-input-error for="contrato.year" />
                    </div>
                    <div class="w-full overflow-hidden col-span-2">
                        <x-base-input type="text" label="Placa" id="contrato.placa"
                            wire:model.defer="contrato.placa" />
                        <x-input-error for="contrato.placa" />
                    </div>

                </div>
                <div class="flex justify-end">
                    <x-button>Actualizar</x-button>
                </div>
            </form>


        </div>
    </x-modal>
</div>
