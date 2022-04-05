<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('home') }}
    @endslot
    <h1 class="text-red-400">Hola mundo</h1>
    <div class="grid grid-cols-4 gap-8">
        @foreach (auth()->user()->store->users as $user)
            <div
                class="max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img class="rounded-t-lg w-48 mx-auto p-4" src="{{ $user->avatar }}" alt="">
                </a>
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white w-full overflow-hidden overflow-ellipsis whitespace-nowrap">
                            {{ $user->fullname }}</h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 ">{{ $user->email }}</p>
                    <a href="#"
                        class="inline-flex items-center space-x-4 py-2 px-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <span> Read more</span>
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
        @endforeach

    </div>

</x-app-layout>
