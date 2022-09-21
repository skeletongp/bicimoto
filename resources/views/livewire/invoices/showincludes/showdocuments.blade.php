<div class="flex flex-col items-center justify-center space-y-4">
    @if (count($invoice->cuotas))
        <div class="flex w-full space-x-4 justify-end">
            <a id="cartaSeguro" href="{{ route('invoices.carta', [$invoice]) }}"
                class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                Carta de Seguro
            </a>
            <a id="cartaDigesset" href="{{ route('invoices.carta', [$invoice, 'Digesset']) }}"
                class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                Carta de DIGESSET
            </a>
            @can('Editar Contratos')
                <livewire:contratos.edit-contrato :contrato_id="$invoice->contrato->id" />
            @endcan
        </div>
    @endif
    @if ($document)
        <h1 class="font-bold uppercase text-xl ">Contrato del cliente</h1>
        <object data="data:application/pdf;base64,{{ $document}}" width="700" height="700" type="application/pdf">
        </object>
    @else
        <img class="w-1/3" src="{{ env('NO_IMAGE') }}" alt="">
    @endif
</div>
