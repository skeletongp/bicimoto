<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products') }}
    @endslot
  

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">

            <livewire:products.allchasis :all="true" />
        </div>
    </div>

</x-app-layout>
