<div>
    <x-modal title="Ajustar anticipo" :fitV="true" :listenOpen="true" maxWidth="max-w-xs">
        <x-slot name="button">
            <span class="far fa-pen"></span>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="updateAnticipo">
                <div>
                    <x-base-input wire:model.defer="amount" id="$anticipo_id" label="Saldo actual"></x-base-input>
                    <x-input-error for="amount"> </x-input-error>
                </div>
            </form>
        </div>
    </x-modal>
</div>
