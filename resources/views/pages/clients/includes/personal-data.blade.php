<div class="bg-white p-3 shadow-sm rounded-sm border-t-4 border-gray-200">
    <div class="flex items-center space-x-2 font-bold uppercase  leading-8:">
        <span clas="text-green-500">
            
        </span>
        <span class="tracking-wide">Contacto y Detalles</span>
    </div>
    <div class="">
        <div class="grid grid-cols-2 text-sm">
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase">Nombre:</div>
                <div class="px-4 py-2 col-span-2">{{$client->contact->name}}</div>
            </div>
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase">Apellidos:</div>
                <div class="px-4 py-2 col-span-2">{{$client->contact->lastname}}</div>
            </div>
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase">Cédula:</div>
                <div class="px-4 py-2 col-span-2">{{$client->contact->cedula}}</div>
            </div>
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase">Tel.:</div>
                <div class="px-4 py-2 col-span-2">{{$client->contact->cellphone}}</div>
            </div>
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase col-span:-1">Dirección</div>
                <div class="px-4 py-2 col-span-2" title="{{$client->contact->address}}">{{ellipsis($client->contact->address,25)}}</div>
            </div>
            
            <div class="grid grid-cols-3 border-b-2 border-gray-100 ">
                <div class="px-4 py-2 font-bold uppercase col-span:-1">Correo</div>
                <div class="px-4 py-2 col-span-2 text-blue-600">
                    {{$client->contact->email}}
                </div>
            </div>
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase">Puntaje:</div>
                <div class="px-4 py-2 col-span-2 flex items-center space-x-4 {{$client->puntaje>35?'text-green-500':'text-red-500'}}">
                   <span> {{formatNumber($client->puntaje)}}%</span>
                   <span class="fas  {{$client->puntaje>35?'fa-arrow-up':'fa-arrow-down'}}">
                    
                   </span>

                </div>
            </div>
            <div class="grid grid-cols-3 border-b-2 border-gray-100">
                <div class="px-4 py-2 font-bold uppercase">Deuda:</div>
                <div class="px-4 py-2 col-span-2">${{formatNumber($client->debt)}}</div>
            </div>
        </div>
    </div>
  
</div>