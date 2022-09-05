<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices.show', $invoice) }}
    @endslot
    <div class=" w-full max-w-4xl mx-auto">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:invoices.invoice-cuota :invoice_id="$invoice->id" />
        </div>
    </div>
</x-app-layout>
