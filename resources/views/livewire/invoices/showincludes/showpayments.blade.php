<div class="pt-12">
    @can('Cobrar Facturas')
        @if ($invoice->cuotas->count())
            <div class="">
                <livewire:invoices.invoice-cuota :invoice_id="$invoice->id" :key="$invoice->id" />
            </div>
        @else
        @include('livewire.invoices.showincludes.showAbono')
        @endif
    @endcan
</div>
