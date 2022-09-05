<div class="bg-white p-3 shadow-sm rounded-sm">
    <div class="flex items-center space-x-2 font-bold uppercase  leading-:8">
       
        <span class="tracking-wide">Datos crediticios</span>
    </div>
    @if ($crediticio)
        <div class="">
            <div class="grid grid-cols-2 text-sm">
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Inmuebles:</div>
                    <div class=" py-2 col-span-2">${{formatNumber($crediticio->state)}}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Muebles:</div>
                    <div class=" py-2 col-span-2">${{formatNumber($crediticio->muebles)}}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Alquileres:</div>
                    <div class=" py-2 col-span-2">${{formatNumber($crediticio->rent)}}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase ">Hipoteca:</div>
                    <div class=" py-2 col-span-2" >${{formatNumber($crediticio->hipoteca)}}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Préstamos:</div>
                    <div class=" py-2 col-span-2">${{ formatNumber($crediticio->loans) }}</div>
                </div>

                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase">Otros:</div>
                    <div class=" py-2 col-span-2">${{formatNumber($crediticio->others)}}</div>
                </div>
                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase ">Banco:</div>
                    <div class=" py-2 col-span-2">{{ ellipsis($crediticio->bank,25) }}</div>
                </div>

                <div class="grid grid-cols-3 border-b-2 border-gray-100">
                    <div class=" py-2 font-bold uppercase ">Saldo:</div>
                    <div class=" py-2 col-span-2">
                        ${{formatNumber($crediticio->bank_value)}}
                    </div>
                </div>

            </div>
        </div>
    @else
        <h1 class="">Sin datos registrados</h1>
    @endif
</div>
