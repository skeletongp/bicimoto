<div>
    <h1 class="uppercase text-xl text-center font-bold">Crear rol</h1>
    <div class="my-4 px-4">
        <form action="" class="mt-8" wire:submit.prevent=" authorize('¿Permitir crear rol?', 'validateAuthorization','createRole',null,'Crear Roles')">
            <div class="flex justify-between items-end space-x-4">
                <div class="w-full max-w-xs">
                    <x-input label="Nombre del Rol" wire:model.defer="form.name" id="form.role.name"></x-input>
                    <x-input-error for="form.name"></x-input-error>
                </div>
                <div class="">
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled'>
                        <div class="animate-spin mr-2" wire:loading wire:target="createRole">
                            <span class="fa fa-spinner ">
                            </span>
                        </div>
                        <span>Guardar</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
