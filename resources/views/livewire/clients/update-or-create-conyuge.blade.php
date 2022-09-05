<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Añadir / Actualizar Cónyuge</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-user-plus mr-2"></span>
            <span> Cónyuge</span>
        </x-slot>
        <div class="relative pt-8">
            <form wire:submit.prevent="createConyuge">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">

                        <div>
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="text" label="Nombre" id="cyg_name" wire:model.defer="form.name" />
                                    <x-input-error for="form.name" />
                                </div>
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="text" label="Apellidos" id="cyg_lastname" wire:model.defer="form.lastname" />
                                    <x-input-error for="form.lastname" />
                                </div>
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="cyg_email" type="email"
                                    wire:model.defer="form.email" />
                                <x-input-error for="form.email" />
                            </div>
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="text" label="Dirección" id="cyg_address" wire:model.defer="form.address" />
                                <x-input-error for="form.address" />
                            </div>
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="text" label="Nacionalidad" id="cyg_nacionality" wire:model.defer="form.nacionality" />
                                <x-input-error for="form.nacionality" />
                            </div>
                        </div>
                    </div>
                    <div class="w-full overflow-hidden">
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-select class="{{ $cltDocType ? 'text-black' : 'text-gray-300' }}"
                                    label="Tipo de documento" id="cygDocType" wire:model="cltDocType">
                                    <option value="" class="text-gray-300">Elija RNC o Cédula</option>
                                    <option class="text-black">RNC</option>
                                    <option class="text-black">Cédula</option>
                                </x-base-select>
                                <x-input-error for="cltDocType">Indique el tipo de documento</x-input-error>
                            </div>
                            <div class="w-full overflow-hidden {{ $cltDocType != 'RNC' ? 'hidden' : '' }}">
                                <x-base-input label="No. Documento" placeholder="Ingrese el Nº. de RNC" id="cyg_RNC"
                                    type="text" wire:model.defer="form.cedula"
                                    />
                                <x-input-error for="form.cedula" />

                            </div>
                            <div class="w-full overflow-hidden {{ $cltDocType != 'Cédula' ? 'hidden' : '' }}">
                                <x-base-input label="No. Documento" placeholder="Ingrese el Nº. de Cédula"
                                    id="cyg_Cedula" type="text" wire:model.defer="form.cedula"
                                    />
                                <x-input-error for="form.cedula" />

                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="cyg_phone"
                                    wire:model.defer="form.phone" />
                                <x-input-error for="form.phone" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="Nº. Celular" id="cyg_cellphone"
                                    wire:model.defer="form.cellphone" />
                                <x-input-error for="form.cellphone" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Estado Civil" wire:model.defer="form.civil_status" id="cyg_civil_status">
                                    <option value=""></option>
                                    <option>Casado</option>
                                    <option>Soltero</option>
                                    <option>Unión libre</option>
                                    <option>Viudo</option>
                                </x-base-select>
                                <x-input-error for="form.civil_status" />
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
                        

                    </div>
                    
                </div>
                <div class="py-3 flex justify-end items-center">
                    <x-button>Guardar</x-button>
                </div>
            </form>
        </div>

    </x-modal>
</div>
@push('js')
    <script>
        $('#cltDocType').on('change', function () {
            $('#cyg_RNC').val(' ');
        });
        $('#cyg_RNC').formatPhoneNumber({
            format: '###-#####-#'
        })
        $('#cyg_Cedula').formatPhoneNumber({
            format: '###-#######-#'
        })
    </script>
@endpush
