<aside class=" h-full w-64 hidden lg:block" aria-label="Sidebar">
    <div class="h-full overflow-y-auto py-4 px-3 pl-4 bg-gray-50 rounded dark:bg-gray-800">
        <ul class=" h-full space-y-0">
            <li>
                <x-dropdown>
                    <x-slot name="trigger">
                        <div
                            class="uppercase flex items-center p-2 text-base font-bold cursor-pointer  rounded-lg  hover:bg-gray-200  ">
                            <div class="h-8 w-8 rounded-full shadow-sm bg-cover bg-center "
                                style="background-image: url({{ auth()->user()->avatar }}); min-width:2rem; min-height:2rem">
                            </div>
                            <span
                                class="ml-3 px-4 w-full overflow-hidden overflow-ellipsis whitespace-nowrap">{{ auth()->user()->fullname }}</span>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <x-button class="flex space-x-3 items-center">
                                <span class="far fa-sign-out-alt"></span>
                                <span> Cerrar sesión</span>
                            </x-button>
                        </form>

                    </x-slot>
                </x-dropdown>
            </li>
            <x-side-link routeName='home' icon='fas w-10 text-center fa-chart-pie' text='Dashboard'
                activeRoute="home" />
            <x-side-link routeName='settings.index' icon='fas w-10 text-center fa-cogs' text='Ajustes'
                activeRoute="settings.*" />
            <div class="w-full pt-4"></div>

            <x-dropitem text="Contactos" icon="far fa-users" :routes="['users.*', 'clients.*','providers.*']">
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-user-tie fa-lg' text='Usuarios'
                    activeRoute="users.*" scope="Usuarios"/>
                <x-side-link routeName='clients.index' icon='far w-10 text-center fa-users fa-lg' text='Clientes'
                    activeRoute="clients.*" scope="Clientes"/>
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-user-tag fa-lg' text='Proveedores'
                    activeRoute="home.*" scope="Proveedores"/>
            </x-dropitem>

            <x-dropitem text="Facturación" icon="far fa-copy">
                <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-file-invoice-dollar fa-lg'
                    text='Facturas' activeRoute="invoices.*" scope="Facturas" />
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-file-exclamation fa-lg'
                    text='Cotizaciones' activeRoute="home.*" scope="Cotizaciones"/>
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-copy fa-lg' text='Pedidos'
                    activeRoute="home.*" scope="Pedidos"/>
            </x-dropitem>

            <x-dropitem text="Inventario" icon=" far fa-cabinet-filing" :routes="['products.*','recursos.*','Procesos.*']">
                <x-side-link routeName='products.index' icon='far w-10 text-center fa-layer-group fa-lg'
                    text='Productos' activeRoute="products.*" scope="Productos" />
                <x-side-link routeName='recursos.index' icon='far w-10 text-center fa-warehouse-alt fa-lg' text='Recursos'
                    activeRoute="recursos.*" scope="Recursos"/>
                <x-side-link routeName='procesos.index' icon='far w-10 text-center fa-copy fa-lg' text='Procesos'
                    activeRoute="procesos.*" scope="Procesos" />
            </x-dropitem>

            <x-dropitem text="Informes" icon="far fa-file-alt">
              
                <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-file-chart-pie fa-lg'
                    text='Estadísticas' activeRoute="invoices.*" scope="Estadísticas"/>
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-file-chart-line fa-lg'
                    text='Reportes' activeRoute="home.*" scope="Reportes"/>
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-download fa-lg' text='Exportaciones'
                    activeRoute="home.*" scope="Exportaciones"/>
            </x-dropitem>

            <x-dropitem text="Finanzas" icon="far fa-wallet">
              
                <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-chart-line fa-lg' text='Ingresos'
                    activeRoute="invoices.*" scope="Ingresos"/>
                <x-side-link routeName='users.index' icon='far w-10 text-center fa-chart-line-down fa-lg' text='Gastos'
                    activeRoute="home.*" scope="Gastos"/>
            </x-dropitem>
        </ul>
    </div>
</aside>
