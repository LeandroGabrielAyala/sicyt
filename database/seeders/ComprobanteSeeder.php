<?php

namespace Database\Seeders;

use App\Models\Comprobante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comprobante::create([
            'nombre' => 'Certificación Contable',
        ]);
        Comprobante::create([
            'nombre' => 'Factura A',
        ]);
        Comprobante::create([
            'nombre' => 'Factura B',
        ]);
        Comprobante::create([
            'nombre' => 'Factura C',
        ]);
        Comprobante::create([
            'nombre' => 'Factura E',
        ]);
        Comprobante::create([
            'nombre' => 'Factura M',
        ]);
        Comprobante::create([
            'nombre' => 'Factura T',
        ]);
        Comprobante::create([
            'nombre' => 'Invoice',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Crédito Op. Ext.',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Crédito A',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Crédito B',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Crédito C',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Crédito M',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Crédito T',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Débito A',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Débito B',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Débito C',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Débito M',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Débito T',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Débito Op. Ext.',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Venta Contado A',
        ]);
        Comprobante::create([
            'nombre' => 'Nota de Venta Contado b',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Factura A',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Factura B',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket C',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Fiscal',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Nota de Crédito A',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Nota de Crédito B',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Nota de Crédito C',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Nota de Débito A',
        ]);
        Comprobante::create([
            'nombre' => 'Ticket Nota de Débito B',
        ]);
        Comprobante::create([
            'nombre' => 'Otros Comprobantes s/R.G. 1415',
        ]);
        Comprobante::create([
            'nombre' => 'Otros Comprobantes A s/R.G. 1415',
        ]);
        Comprobante::create([
            'nombre' => 'Otros Comprobantes B s/R.G. 1415',
        ]);
    }
}
