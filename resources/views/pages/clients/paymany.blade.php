<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients.paymany', $cuotas) }}
    @endslot

    <div class="w-full p-4 bg-gray-50  ">
        <div class="max-w-6xl mx-auto">
            <livewire:clients.pay-cuotas :cuotas="$cuotas" />
        </div>
    </div>
    @push('js')
        <script>
            Livewire.on('printPayment', function(payment) {
                console.log(payment);
                printP(payment);
            })

            function align(conector, dir) {
                switch (dir) {
                    case 'right':
                        conector.setAlign(dir);
                        break;
                    case 'center':
                        conector.setAlign(dir);
                        break;
                    case 'left':
                        conector.setAlign(dir);
                        break;
                }
            }
            removeAccent = function(string) {
            string = string.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            return string;
        };

        function texto(impresora, string) {
            impresora.write(removeAccent(string.toUpperCase()));
        }
        var formatter =
            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            });
            var toDecimal = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 2,
            });
            var sumField = (obj, field) => obj
                .map(items => items[field])
                .reduce((prev, curr) => parseFloat(prev) + parseFloat(curr), 0);

            retry=0;
            function printP(payment) {
                obj = payment;
                if (!obj.place.preference.printer) {
                    Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                    return false;
                }
                conector = new Impresora();
                conector.cut();
                /* Encabezado Negocio */
                align(conector, 'center');
                conector.setEmphasize(1);
                conector.setFontSize(2, 3)
                texto(conector, obj.payable.store.name.toUpperCase() + "\n");
                conector.setEmphasize(0);
                conector.setFontSize(1, 1)
               texto(conector, 'RNC: ')
               texto(conector, obj.payable.store.rnc + "\n");
               texto(conector, obj.payable.store.phone + "\n");
               texto(conector, obj.payable.store.address + "\n");
               texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Encabezado */

                /* Sección Título */
                conector.setEmphasize(1);
                conector.setFontSize(2, 2);
               texto(conector, 'RECIBO DE PAGO');
                conector.setEmphasize(0);
                conector.setFontSize(1, 1);
                conector.feed(1);
               texto(conector, obj.cuotas + " / ");
               texto(conector, obj.cuotasTotal);
                conector.feed(2)
                /* Fin Sección */

                /* Detalle Factura */
                align(conector, 'left');
                conector.setEmphasize(1);
               texto(conector, "INICIAL: ");
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.payable.payment.total) + " | ")

                conector.setEmphasize(1);
               texto(conector, "ACTUAL: ");
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.payable.rest))
                conector.feed(1);



                conector.setEmphasize(1);
               texto(conector, 'FECHA: ')
                conector.setEmphasize(0);
               texto(conector, obj.day + " | ");

               texto(conector, 'FACT. NO.: ')
                conector.setEmphasize(0);
               texto(conector, obj.payable.number);
                conector.feed(1);

                align(conector, 'center');
               texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin detalle */


                /* Datos del cliente */
                align(conector, 'left');
                conector.setEmphasize(1);
               texto(conector, 'CLIENTE: ')
                conector.setEmphasize(0);
               texto(conector, obj.payable.name ? obj.payable.name.toUpperCase() : obj.payer.contact.fullname.toUpperCase());
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'CÉDULA: ');
                conector.setEmphasize(0);
               texto(conector, obj.payer.contact.cedula ? obj.payer.contact.cedula : '0000000000')
               texto(conector, ' / ');

                conector.setEmphasize(1);
               texto(conector, 'TEL: ');
                conector.setEmphasize(0);
               texto(conector, obj.payer.contact.cellphone);
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'DIR: ');
                conector.setEmphasize(0);
               texto(conector, obj.payer.contact.address ? obj.payer.contact.address : 'N/D');
                conector.feed(1);
                align(conector, 'center');
               texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Cliente */

                /* Encabezado de pago */
                conector.setEmphasize(1);
                align(conector, 'center');
                conector.setFontSize(2, 1);
               texto(conector, 'DETALLES DEL PAGO')
                conector.setFontSize(1, 1);
                conector.feed(1)
                conector.setEmphasize(0);
                /* Fin encabezados */

                /* Detalles del pago */
                align(conector, 'left');

                if (obj.efectivo > 0) {
                    conector.setEmphasize(1);
                   texto(conector, 'EFECTIVO: ');
                    conector.setEmphasize(0);
                   texto(conector, formatter.format(obj.efectivo - obj.cambio));
                    conector.feed(1);
                }

                if (obj.transferencia > 0) {
                    conector.setEmphasize(1);
                   texto(conector, 'TRANSFERENCIA: ');
                    conector.setEmphasize(0);
                   texto(conector, formatter.format(obj.transferencia - obj.cambio));
                    conector.feed(1);
                }

                if (obj.tarjeta > 0) {
                    conector.setEmphasize(1);
                   texto(conector, 'OTROS: ');
                    conector.setEmphasize(0);
                   texto(conector, formatter.format(obj.tarjeta - obj.cambio));
                    conector.feed(1);
                }
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'SALDO ANTERIOR: ')
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.total));
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'INTERÉS: ');
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.cuota.interes) + " | ");
                conector.setEmphasize(1);
               texto(conector, 'CAPITAL: ');
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.cuota.capital));
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'INTERÉS POR MORA: ');
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.cuota.mora));
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'PAGARÉ: ');
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.cuota.debe));
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'SALDO RESTANTE: ');
                conector.setEmphasize(0);
               texto(conector, formatter.format(obj.rest));
                conector.feed(1);

                conector.setEmphasize(1);
               texto(conector, 'PRÓXIMO PAGO: ');
                conector.setEmphasize(0);
               texto(conector, obj.proxima.fecha);
                conector.feed(1);

                align(conector, 'center');
               texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Detalles */
                /* Sección personas */

                conector.setEmphasize(1);
               texto(conector, 'CAJERO: ');
                conector.setEmphasize(0);
               texto(conector, obj.contable.fullname);
                conector.feed(2);
                /* Fin sección */

                /* Pie */
               texto(conector, '-------- GRACIAS POR PREFERIRNOS --------\n');
                conector.feed(2);
                /* Fin pie */

                conector.feed(3);
                conector.cut();
                conector.imprimirEnImpresora(obj.place.preference.printer)
                    .then(respuestaAlImprimir => {
                        if (respuestaAlImprimir === true) {
                            console.log("Impreso correctamente");
                        } else {
                            Livewire.emit('printPayment', obj);
                            if(retry<3){
                            retry++;
                           }
                            console.log("Error. La respuesta es: " + respuestaAlImprimir);
                        }
                    });

            }
        </script>
    @endpush
</x-app-layout>
