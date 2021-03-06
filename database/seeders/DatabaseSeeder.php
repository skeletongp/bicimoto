<?php

namespace Database\Seeders;

use App\Http\Livewire\Store\CreateStore;
use App\Jobs\CreateCountForPlaceJob;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* $store = Store::create([
            'name' => 'AtrionTech Soluciones Digitales',
            'address' => 'Calle Respaldo A, No. 8E, D. N.',
            'lema' => '¡Soluciones simples a problemas complejos!',
            'email' => 'info@atriontechsd.com',
            'phone' => '(809) 508-6221',
            'rnc' => '132487524',
            'expires_at' => Carbon::now()->addMonths(3)
        ]);
        */

        $store=Store::find(env('STORE_ID'));
        $store->image()->create([
            'path' => 'https://res.cloudinary.com/atriontechsd/image/upload/v1657775865/bicimoto/logo/bicimoto_o0gqq8.jpg',
        ]);
        $unit = $store->units()->create([
            'name' => 'Unidad',
            'symbol' => 'UND.'
        ]);
        
        $tax = $store->taxes()->create([
            'name' => 'ITBIS',
            'rate' => 0.18
        ]);

        $data = [
            'name' => $store->name . ' | Oficina',
            'phone' => $store->phone,

        ];
        $place = $store->places()->create($data);
        $place->preference()->create([
            'comprobante_type' => 'B00',
            'unit_id' => $unit->id,
            'tax_id' => $tax->id,
        ]);
        $provider = $store->providers()->create([
            'name' => 'Suplidor',
            'lastname' => 'Genérico ',
            'email' => $store->email,
            'address' => 'Sin Dirección',
            'phone' => '(000) 000-0000',
            'rnc' => '000-00000-0',
            'limit' => 0,
        ]);
       
       /*  $user = $store->users()->create([
            'name' => 'Ismael',
            'lastname' => 'Contreras ',
            'email' => 'contrerasismael0@gmail.com',
            'username' => 'mastergo',
            'password' => 'mastergo',
            'phone' => '(829) 804-1907)',
            'loggeable' => 'yes',
            'place_id' => $store->places()->first()->id,
            'store_id' => $store->id,
        ]); */
        $user=User::find(1);
        $store->users()->attach($user);
        $client = $store->clients()->create([
            'name' => 'Clte. Genérico',
            'email' => 'ventas@bicimotoelclavo.com',
            'address' => 'Sin Dirección',
            'phone' => '(000) 000-0000',
            'RNC' => '000-00000-0',
            'limit' => 50000,
        ]);

        $user->image()->create([
            'path' => 'https://definicion.de/wp-content/uploads/2016/02/avatar.jpg',
        ]);
        $this->call(
            [
                RoleSeeder::class,
                ScopeSeeder::class,
                CountMainSeeder::class,
              
            ]
        );
        
        setContable($client, '101', 'debit', $client->fullname, $place->id);
        setContable($tax, '203', 'credit', 'ITBIS por Pagar', $place->id);
        setContable($tax, '103', 'debit', $tax->name . ' por Cobrar', $place->id);
        $user->assignRole('Super Admin');
        $user->assignRole('Administrador');
        $store->roles()->save(Role::find(1));
        $store->roles()->save(Role::find(2));
        $store->roles()->save(Role::find(3));
        $this->setCounts($place, $provider);
       
      
    }
    public function setCounts($place, $provider)
    {
        setContable($place, '100', 'debit', 'Efectivo en Caja General', $place->id);
        setContable($place, '100', 'debit', 'Efectivo en Caja Chica', $place->id);
        setContable($place, '100', 'debit', 'Efectivo en Cheques', $place->id);
        setContable($place, '100', 'debit', 'Otros Efectivos', $place->id);
        setContable($place, '104', 'debit', 'Inventario general',  $place->id,);
        setContable($place, '400', 'credit', 'Ingresos por Ventas de Productos', $place->id);
        setContable($place, '400', 'credit', 'Ingresos por Ventas de Servicios', $place->id);
        setContable($place, '401', 'debit', 'Devoluciones en Ventas', $place->id);
        setContable($place, '401', 'debit', 'Otras notas de crédito', $place->id);
        setContable($place, '401', 'debit', 'Descuentos en Ventas', $place->id);
        setContable($place, '402', 'credit', 'Otros Ingresos', $place->id);
        setContable($place, '500', 'debit', 'Compra de mercancías', $place->id);
        setContable($place, '500', 'debit', 'Generación de servicios', $place->id);
        setContable($place, '501', 'credit', 'Devoluciones en compras', $place->id);
        setContable($place, '501', 'credit', 'Descuentos en compras', $place->id);
        setContable($place, '300', 'credit', 'Capital Sucrito y Pagado', $place->id);
        setContable($provider, '200', 'credit', $provider->fullname, $place->id);
        setContable($place, '600', 'debit', 'Sueldos y Salarios', $place->id);
        setContable($place, '600', 'debit', 'Atención al Personal', $place->id);
        setContable($place, '600', 'debit', 'Combustible', $place->id);
        setContable($place, '600', 'debit', 'Materiales de Oficina', $place->id);
        setContable($place, '600', 'debit', 'Materiales de Limpieza', $place->id);
        setContable($place, '600', 'debit', 'Publicidad', $place->id);
        setContable($place, '600', 'debit', 'Teléfono y Comunicación', $place->id);
        setContable($place, '600', 'debit', 'Reparaciones', $place->id);
        setContable($place, '600', 'debit', 'Mantenimiento', $place->id);
        setContable($place, '600', 'debit', 'Depreciación Edificio', $place->id);
        setContable($place, '600', 'debit', 'Depreciación Mobiliario', $place->id);
        setContable($place, '600', 'debit', 'Depreciación Equipo Transporte', $place->id);
        setContable($place, '600', 'debit', 'Depreciación Maquinaria', $place->id);
        setContable($place, '600', 'debit', 'Infraestructura Tecnológica', $place->id);
        setContable($place, '600', 'debit', 'Gastos Incobrables', $place->id);
        setContable($place, '601', 'debit', 'Sueldos y Salarios Ventas', $place->id);
        setContable($place, '601', 'debit', 'Comisiones Ventas', $place->id);
        setContable($place, '601', 'debit', 'Atención al Personal Ventas', $place->id);
        setContable($place, '601', 'debit', 'Transporte Ventas', $place->id);
        setContable($place, '601', 'debit', 'Asignación de Vehículo Ventas', $place->id);
        setContable($place, '602', 'debit', 'Intereses Préstamos', $place->id);
        setContable($place, '602', 'debit', 'Comisiones Bancarias', $place->id);
        setContable($place, '602', 'debit', 'Gastos de Seguros', $place->id);
        setContable($place, '602', 'debit', 'Hipotecas', $place->id);
    }
}
