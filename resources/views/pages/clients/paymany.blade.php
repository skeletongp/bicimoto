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
                        conector.establecerJustificacion(ConectorPlugin.Constantes.AlineacionDerecha);
                        break;
                    case 'center':
                        conector.establecerJustificacion(ConectorPlugin.Constantes.AlineacionCentro);
                        break;
                    case 'left':
                        conector.establecerJustificacion(ConectorPlugin.Constantes.AlineacionIzquierda);
                        break;
                }
            }
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

                
            function printP(payment) {
                obj = payment;
                if (!obj.place.preference.printer) {
                    Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                    return false;
                }
                conector = new ConectorPlugin();
                conector.cortar();
                /* Encabezado Negocio */
                align(conector, 'center');
                conector.establecerEnfatizado(1);
                conector.establecerTamanioFuente(2, 3)
                conector.textoConAcentos(obj.payable.store.name.toUpperCase() + "\n");
                conector.establecerEnfatizado(0);
                conector.establecerTamanioFuente(1, 1)
                conector.texto('RNC: ')
                conector.texto(obj.payable.store.rnc + "\n");
                conector.texto(obj.payable.store.phone + "\n");
                conector.texto(obj.payable.store.address + "\n");
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin Encabezado */

                /* Sección Título */
                conector.establecerEnfatizado(1);
                conector.establecerTamanioFuente(2, 2);
                conector.texto('RECIBO DE PAGO');
                conector.establecerEnfatizado(0);
                conector.establecerTamanioFuente(1, 1);
                conector.feed(1);
                conector.texto(obj.cuotas + " / ");
                conector.texto(obj.cuotasTotal);
                conector.feed(2)
                /* Fin Sección */

                /* Detalle Factura */
                align(conector, 'left');
                conector.establecerEnfatizado(1);
                conector.texto("INICIAL: ");
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.payable.payment.total) + " | ")

                conector.establecerEnfatizado(1);
                conector.texto("ACTUAL: ");
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.payable.rest))
                conector.feed(1);



                conector.establecerEnfatizado(1);
                conector.texto('FECHA: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.day+" | ");

                conector.texto('FACT. NO.: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.payable.number);
                conector.feed(1);

                align(conector, 'center');
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin detalle */


                /* Datos del cliente */
                align(conector, 'left');
                conector.establecerEnfatizado(1);
                conector.texto('CLIENTE: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.payable.name ? obj.payable.name.toUpperCase() : obj.payer.contact.fullname.toUpperCase());
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('CÉDULA: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payer.contact.cedula ? obj.payer.contact.cedula : '0000000000')
                conector.texto(' / ');

                conector.establecerEnfatizado(1);
                conector.texto('TEL: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payer.contact.cellphone);
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('DIR: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payer.contact.address ? obj.payer.contact.address : 'N/D');
                conector.feed(1);
                align(conector, 'center');
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin Cliente */

                /* Encabezado de pago */
                conector.establecerEnfatizado(1);
                align(conector, 'center');
                conector.establecerTamanioFuente(1.3, 1.6);
                conector.texto('DETALLES DEL PAGO')
                conector.establecerTamanioFuente(1, 1);
                conector.feed(1)
                conector.establecerEnfatizado(0);
                /* Fin encabezados */

                /* Detalles del pago */
                align(conector, 'left');

                if (obj.efectivo > 0) {
                    conector.establecerEnfatizado(1);
                    conector.texto('EFECTIVO: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(formatter.format(obj.efectivo - obj.cambio));
                    conector.feed(1);
                }

                if (obj.transferencia > 0) {
                    conector.establecerEnfatizado(1);
                    conector.texto('TRANSFERENCIA: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(formatter.format(obj.transferencia - obj.cambio));
                    conector.feed(1);
                }

                if (obj.tarjeta > 0) {
                    conector.establecerEnfatizado(1);
                    conector.texto('OTROS: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(formatter.format(obj.tarjeta - obj.cambio));
                    conector.feed(1);
                }
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('SALDO ANTERIOR: ')
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.total));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('INTERÉS: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.cuota.interes) + " | ");
                conector.establecerEnfatizado(1);
                conector.texto('CAPITAL: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.cuota.capital));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('INTERÉS POR MORA: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.cuota.mora));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('PAGARÉ: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.cuota.debe));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('SALDO RESTANTE: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.rest));
                conector.feed(1);
                
                conector.establecerEnfatizado(1);
                conector.texto('PRÓXIMO PAGO: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.proxima.fecha);
                conector.feed(1);

                align(conector, 'center');
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin Detalles */
                /* Sección personas */

                conector.establecerEnfatizado(1);
                conector.texto('CAJERO: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.contable.fullname);
                conector.feed(2);
                /* Fin sección */

                /* Pie */
                conector.texto('-------- GRACIAS POR PREFERIRNOS --------\n');
                conector.feed(2);
                /* Fin pie */

                conector.feed(3);
                conector.cortar();
                conector.imprimirEn(obj.place.preference.printer)
                    .then(respuestaAlImprimir => {
                        if (respuestaAlImprimir === true) {
                            console.log("Impreso correctamente");
                        } else {
                            console.log("Error. La respuesta es: " + respuestaAlImprimir);
                        }
                    });

            }
        </script>
    @endpush
</x-app-layout>
