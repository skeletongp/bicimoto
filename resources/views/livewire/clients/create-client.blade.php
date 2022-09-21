<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl" :listenOpen="true">
       
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Nuevo Cliente</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-user-plus mr-2"></span>
            <span> Cliente</span>
        </x-slot>
        <div class="relative pt-8">
            <div class="absolute right-2 top-0 font-bold text-lg">
                <span>Cód.: {{ $code }}</span>
            </div>
            <form wire:submit.prevent="createClient">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">

                        <div>
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="text" label="Nombre" id="client.name"
                                        wire:model.defer="form.name" />
                                    <x-input-error for="form.name" />
                                </div>
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="text" label="Apellidos" id="client.lastname"
                                        wire:model.defer="form.lastname" />
                                    <x-input-error for="form.lastname" />
                                </div>
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="client.email" type="email"
                                    wire:model.defer="form.email" />
                                <x-input-error for="form.email" />
                            </div>
                            
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="text" label="Dirección" id="client.address"
                                    wire:model.defer="form.address" />
                                <x-input-error for="form.address" />
                            </div>
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Nacionalidad" wire:model.defer="form.nacionality" id="client.nacionality">
                                    <option value=""></option>
                                    <option>Dominicano</option>
                                    <option>Haitiano</option>
                                    <option>Otro</option>
                                </x-base-select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="w-full overflow-hidden">

                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden ">
                                <x-base-input label="No. Documento" class="cedula" placeholder="Ingrese el Nº. de Cédula"
                                    id="client_Cedula" type="text" wire:model.defer="form.cedula" />
                                <x-input-error for="form.cedula" />

                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Sexo" wire:model.defer="form.genre" id="grenreclt">
                                    <option value=""></option>
                                    <option>Masculino</option>
                                    <option>Femenino</option>
                                </x-base-select>
                                <x-input-error for="form.genre" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="client.phone"
                                    wire:model.defer="form.phone" />
                                <x-input-error for="form.phone" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="Nº. Celular" id="client.cellphone"
                                    wire:model.defer="form.cellphone" />
                                <x-input-error for="form.cellphone" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-1/2 overflow-hidden">
                                <x-base-select label="Estado Civil" wire:model.defer="form.civil_status" id="civilSt">
                                    <option value=""></option>
                                    <option>Casado</option>
                                    <option>Soltero</option>
                                    <option>Unión libre</option>
                                    <option>Viudo</option>
                                </x-base-select>
                                <x-input-error for="form.civil_status" />
                            </div>
                            <div class=" flex items-end space-x-3 ">
                                <div class="w-full overflow-hidden ">
                                    <label for="client_avatar" class="flex items-center space-x-4 pt-6 cursor-pointer">
                                        <span class="fas fa-image text-xl"></span>
                                        <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Logo/Avatar</span>
                                        @if ($avatar)
                                            <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                                {{ formatNumber($avatar->getSize() / 1024) }} KB</span>
                                        @endif
                                        <input wire:model="avatar" type="file" class="hidden" name="avatar"
                                            id="client_avatar" accept="image/png, image/gif, image/jpeg">
                                    </label>
                                    <x-input-error for="avatar" />
                                </div>
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
