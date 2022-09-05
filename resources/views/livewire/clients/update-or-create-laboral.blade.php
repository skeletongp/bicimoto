<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Datos Laborales del Cliente</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-briefcase mr-2"></span>
            <span>Laborales</span>
        </x-slot>
        <div class="relative pt-8">
            <form wire:submit.prevent="createLaboral">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">

                        <div>
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="text" label="Actividad Laboral" id="lab_activity"
                                        wire:model.defer="form.activity" />
                                    <x-input-error for="form.activity" />
                                </div>

                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Profesión" id="lab_profesion" type="text"
                                    wire:model.defer="form.profesion" />
                                <x-input-error for="form.profesion" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Condición" wire:model.defer="form.condition" id="cyg_condition">
                                    <option value=""></option>
                                    <option>Dependiente</option>
                                    <option>Independiente</option>
                                </x-base-select>
                                <x-input-error for="form.condition" />
                            </div>
                        </div>
                        <div class=" flex space-x-3 items-start   pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="text" label="Lugar de trabajo" id="lab_company"
                                    wire:model.defer="form.company" />
                                <x-input-error for="form.company" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="lab_phone"
                                    wire:model.defer="form.phone" />
                                <x-input-error for="form.phone" />
                            </div>
                        </div>
                       
                    </div>
                    <div class="w-full overflow-hidden">
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="text" label="Dirección del trabajo" id="lab_address"
                                    wire:model.defer="form.address" />
                                <x-input-error for="form.address" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="date" label="Fecha de ingreso" id="lab_start_at"
                                    wire:model.defer="form.start_at" />
                                <x-input-error for="form.start_at" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="number" label="Ingresos mensuales" id="lab_salary"
                                    wire:model.defer="form.salary" />
                                <x-input-error for="form.salary" />
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
