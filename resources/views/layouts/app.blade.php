<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>
        @if (isset($titlte))
            {{ $title }}
        @else
            {{ env('APP_NAME') }}
        @endif
    </title>


    {{-- Fonts --}}


    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link href="https://unpkg.com/mobius1-selectr@latest/dist/selectr.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Scripts --}}

    <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>

    @livewireStyles
    @laravelPWA

</head>

<body class=" antialised ">
    <div class="hidden xl:flex relative h-full w-screen ">
        <div class="sticky left-0 top-0 z-50 h-screen bg-gray-50 p-2" style="z-index: 80">
            @include('includes.sidebar')
        </div>
        <div class="w-full ">
            {{-- Navbar --}}
            <header class="sticky top-0 z-50 w-full mx-auto py-2 bg-white">
                @include('includes.header')
                <div class=" w-full bg-gray-50 py-1 px-4">
                    @if (isset($bread))
                        {{ $bread }}
                    @endif
                </div>
            </header>

            {{-- Sidebar --}}


            {{-- Content --}}
            <main class="pl-0  flex">
                <div class="hidden" id="generalLoad">
                    <x-loading></x-loading>
                </div>
                <section class=" w-full bg-white ">

                    {{ $slot }}
                </section>
            </main>

            {{-- Foot --}}
            <footer>

            </footer>
        </div>
    </div>

    <div class="flex justify-center items-center xl:hidden w-screen h-screen">
        <h1 class=" font-bold text-3xl uppercase text-center max-w-lg leading-12">Este tamaño de pantalla no es
            compatible. Utilice un monitor más
            grande o
            aplique zoom out al sistema</h1>
    </div>
    @livewireScripts
    
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/mobius1-selectr@latest/dist/selectr.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('js')

    <script>
        colors = {
            "success": {
                "text": 'text-green-700',
                "bg": 'bg-green-100'
            },
            'error': {
                "text": 'text-red-700',
                "bg": 'bg-red-100'
            },
            'warning': {
                "text": 'text-yellow-700',
                "bg": 'bg-yellow-100'
            },
            'info': {
                text: 'text-blue-700',
                "bg": 'bg-blue-100'
            }
        };



        Livewire.on('showAlert', (alert, type) => {
            icons = ['success', 'error', 'info', 'warning'];

            if (!icons.includes(type)) {
                type = 'info';
            }
            Swal.fire({
                title: `<div class="p-4 mb-4 text-lg uppercase ${colors[type]['text']} ${colors[type]['bg']} 
                rounded-lg font-bold role="alert"> ${alert} </div>`,
                icon: type,
                showConfirmButton: false,
                timer: 2000,
                position: 'top-end',
            });
        });

        Livewire.onError(statusCode => {
            switch (statusCode) {
                case 403:
                    msg = 'No tienes permiso para realizar esta acción';
                    break;
                case 419:
                    msg = 'Su sesión ha expirado';
                    break;
                default:
                    msg = 'Ha ocurrido un error con tu solicitud '
                    break;
            }
            Swal.fire({
                title: `<div class="p-4 mb-4 text-lg uppercase text-red-700 bg-red-100 
                rounded-lg font-bold role="alert"> ${msg} </div>`,
                icon: 'error',
                showConfirmButton: true,
                timer: 2000,
                position: 'top-end',
            });
            if (statusCode !== 500) {
                return false;
            }
        })
        $('.load').on('click', function() {
            $('#generalLoad').removeClass('hidden');
        })
    </script>

</body>

</html>
