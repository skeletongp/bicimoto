<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products') }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            <object data="data:application/pdf;base64,{{cache()->get('chasis_'.env('STORE_ID'))}}" width="700" height="700" type="application/pdf"> </object>
        </div>
    </div>

</x-app-layout>
