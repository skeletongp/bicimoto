<div class="bg-white p-3 shadow-sm rounded-sm">
    <div class="flex items-center space-x-2 font-bold uppercase  leading-:8">
       
        <span class="tracking-wide">Datos laborales</span>
    </div>
    @if ($laboral)
        <div class="">
            <div class="grid grid-cols-2 text-sm">
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Actividad:</div>
                    <div class=" py-2 col-span-2">{{ $laboral->activity }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Profesión:</div>
                    <div class=" py-2 col-span-2">{{ $laboral->profesion }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Condición:</div>
                    <div class=" py-2 col-span-2">{{ $laboral->condition }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase ">Empresa:</div>
                    <div class=" py-2 col-span-2" title="{{$laboral->company}}">{{ ellipsis($laboral->company,20) }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Ingresos:</div>
                    <div class=" py-2 col-span-2">${{ formatNumber($laboral->salary) }}</div>
                </div>

                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Teléfono:</div>
                    <div class=" py-2 col-span-2">{{ $laboral->phone }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase ">Dirección:</div>
                    <div class=" py-2 col-span-2" title="{{$laboral->address}}">{{ ellipsis($laboral->address,20) }}</div>
                </div>

                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase ">Desde:</div>
                    <div class=" py-2 col-span-2">
                        {{ $laboral->start_at ?: 'Sin datos' }}
                    </div>
                </div>

            </div>
        </div>
    @else
        <h1 class="">Sin datos registrados</h1>
    @endif
</div>
