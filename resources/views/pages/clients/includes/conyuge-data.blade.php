<div class="bg-white p-3 shadow-sm rounded-sm">
    <div class="flex items-center space-x-2 font-bold uppercase  leading-8">
       <span class="tracking-wide">Persona de Contacto</span>
    </div>
    @if (optional($conyuge)->contact)
        <div class="">
            <div class="grid grid-cols-2 text-sm">
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Nombre:</div>
                    <div class=" py-2 col-span-2">{{ $conyuge->contact->name }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Apellidos:</div>
                    <div class=" py-2 col-span-2">{{ $conyuge->contact->lastname }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Cédula:</div>
                    <div class=" py-2 col-span-2">{{ $conyuge->contact->cedula }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Tel:</div>
                    <div class=" py-2 col-span-2">{{ $conyuge->contact->cellphone }}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Dirección:</div>
                    <div class=" py-2 col-span-2" title="{{$conyuge->contact->address}}">{{ ellipsis($conyuge->contact->address,25) }}</div>
                </div>

                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Correo:</div>
                    <div class=" py-2 col-span-2 text-blue-600" title="{{$conyuge->contact->email}}">{{ ellipsis($conyuge->contact->email,25) }}
                    </div>
                </div>
                
            </div>
        </div>
    @else
    <h1 class="">Sin datos registrados</h1>
    @endif
</div>
