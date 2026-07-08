<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EximInitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Países para clientes internacionales
        if (DB::table('paises')->count() === 0) {
            DB::table('paises')->insert([
                ['nombre' => 'Perú'],
                ['nombre' => 'Estados Unidos'],
                ['nombre' => 'Canadá'],
                ['nombre' => 'México'],
                ['nombre' => 'Brasil'],
                ['nombre' => 'Colombia'],
                ['nombre' => 'Chile'],
                ['nombre' => 'Argentina'],
                ['nombre' => 'Ecuador'],
                ['nombre' => 'Bolivia'],
                ['nombre' => 'Alemania'],
                ['nombre' => 'Francia'],
                ['nombre' => 'España'],
                ['nombre' => 'Italia'],
                ['nombre' => 'Reino Unido'],
                ['nombre' => 'Países Bajos'],
                ['nombre' => 'Bélgica'],
                ['nombre' => 'Suiza'],
                ['nombre' => 'Japón'],
                ['nombre' => 'Corea del Sur'],
                ['nombre' => 'China'],
                ['nombre' => 'Australia'],
                ['nombre' => 'Emiratos Árabes Unidos'],
                ['nombre' => 'Arabia Saudita'],
                ['nombre' => 'Rusia'],
            ]);
        }

        // Monedas
        DB::table('exim_monedas')->insert([
            ['codigo' => 'USD', 'nombre' => 'Dólar Americano', 'simbolo' => '$', 'tipo_cambio' => 1],
            ['codigo' => 'EUR', 'nombre' => 'Euro', 'simbolo' => '€', 'tipo_cambio' => 1.08],
            ['codigo' => 'PEN', 'nombre' => 'Sol Peruano', 'simbolo' => 'S/', 'tipo_cambio' => 0.27],
        ]);

        // Incoterms
        DB::table('exim_incoterms')->insert([
            ['codigo' => 'EXW', 'nombre' => 'Ex Works', 'descripcion' => 'El vendedor entrega la mercancía en sus instalaciones. El comprador asume todos los costos y riesgos desde la recogida.', 'incluye_transporte_interno' => false, 'incluye_flete_maritimo' => false, 'incluye_seguro' => false, 'incluye_aduanas_origen' => false, 'incluye_aduanas_destino' => false, 'incluye_transporte_destino' => false],
            ['codigo' => 'FCA', 'nombre' => 'Free Carrier', 'descripcion' => 'El vendedor entrega la mercancía al transportista designado por el comprador en el lugar acordado.', 'incluye_transporte_interno' => true, 'incluye_flete_maritimo' => false, 'incluye_seguro' => false, 'incluye_aduanas_origen' => true, 'incluye_aduanas_destino' => false, 'incluye_transporte_destino' => false],
            ['codigo' => 'FOB', 'nombre' => 'Free On Board', 'descripcion' => 'El vendedor entrega la mercancía a bordo del buque designado por el comprador en el puerto de origen.', 'incluye_transporte_interno' => true, 'incluye_flete_maritimo' => false, 'incluye_seguro' => false, 'incluye_aduanas_origen' => true, 'incluye_aduanas_destino' => false, 'incluye_transporte_destino' => false],
            ['codigo' => 'CFR', 'nombre' => 'Cost and Freight', 'descripcion' => 'El vendedor cubre el costo y el flete hasta el puerto de destino. El riesgo se transfiere al comprador cuando la mercancía está a bordo.', 'incluye_transporte_interno' => true, 'incluye_flete_maritimo' => true, 'incluye_seguro' => false, 'incluye_aduanas_origen' => true, 'incluye_aduanas_destino' => false, 'incluye_transporte_destino' => false],
            ['codigo' => 'CIF', 'nombre' => 'Cost, Insurance and Freight', 'descripcion' => 'El vendedor cubre costo, flete y seguro hasta el puerto de destino. El riesgo se transfiere al comprador cuando está a bordo.', 'incluye_transporte_interno' => true, 'incluye_flete_maritimo' => true, 'incluye_seguro' => true, 'incluye_aduanas_origen' => true, 'incluye_aduanas_destino' => false, 'incluye_transporte_destino' => false],
            ['codigo' => 'DAP', 'nombre' => 'Delivered at Place', 'descripcion' => 'El vendedor entrega la mercancía en el lugar acordado del país de destino. El comprador asume los costos de importación.', 'incluye_transporte_interno' => true, 'incluye_flete_maritimo' => true, 'incluye_seguro' => true, 'incluye_aduanas_origen' => true, 'incluye_aduanas_destino' => false, 'incluye_transporte_destino' => true],
            ['codigo' => 'DDP', 'nombre' => 'Delivered Duty Paid', 'descripcion' => 'El vendedor asume todos los costos y riesgos hasta entregar la mercancía en el destino final, incluyendo aranceles de importación.', 'incluye_transporte_interno' => true, 'incluye_flete_maritimo' => true, 'incluye_seguro' => true, 'incluye_aduanas_origen' => true, 'incluye_aduanas_destino' => true, 'incluye_transporte_destino' => true],
        ]);

        // Transportes
        DB::table('exim_transportes')->insert([
            ['tipo' => 'maritimo', 'nombre' => 'Transporte Marítimo', 'descripcion' => 'Transporte de carga en contenedores vía marítima', 'costo_base' => 1500, 'moneda_id' => 1, 'activo' => true],
            ['tipo' => 'aereo', 'nombre' => 'Transporte Aéreo', 'descripcion' => 'Transporte de carga vía aérea', 'costo_base' => 3500, 'moneda_id' => 1, 'activo' => true],
            ['tipo' => 'terrestre', 'nombre' => 'Transporte Terrestre', 'descripcion' => 'Transporte de carga vía terrestre', 'costo_base' => 500, 'moneda_id' => 1, 'activo' => true],
            ['tipo' => 'courier', 'nombre' => 'Courier (DHL/FedEx/UPS)', 'descripcion' => 'Envío de muestras y documentos vía courier internacional', 'costo_base' => 80, 'moneda_id' => 1, 'activo' => true],
        ]);

        // Seguros
        DB::table('exim_seguros')->insert([
            ['nombre' => 'Seguro Estándar Marítimo', 'porcentaje' => 1.5, 'costo_base' => 50, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Seguro Premium', 'porcentaje' => 3.0, 'costo_base' => 100, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Seguro Básico', 'porcentaje' => 0.5, 'costo_base' => 20, 'moneda_id' => 1, 'activo' => true],
        ]);

        // Pallets
        DB::table('exim_pallets')->insert([
            ['tipo' => 'estandar', 'material' => 'madera', 'largo_cm' => 120, 'ancho_cm' => 100, 'alto_cm' => 15, 'peso_kg' => 25, 'capacidad_kg' => 1500, 'costo_unitario' => 15],
            ['tipo' => 'euro', 'material' => 'madera', 'largo_cm' => 120, 'ancho_cm' => 80, 'alto_cm' => 15, 'peso_kg' => 20, 'capacidad_kg' => 1200, 'costo_unitario' => 12],
            ['tipo' => 'industrial', 'material' => 'plastico', 'largo_cm' => 120, 'ancho_cm' => 100, 'alto_cm' => 15, 'peso_kg' => 15, 'capacidad_kg' => 2000, 'costo_unitario' => 25],
            ['tipo' => 'personalizado', 'material' => 'madera', 'largo_cm' => 110, 'ancho_cm' => 110, 'alto_cm' => 15, 'peso_kg' => 22, 'capacidad_kg' => 1300, 'costo_unitario' => 18],
        ]);

        // Contenedores
        DB::table('exim_contenedores')->insert([
            ['tipo' => '20ft', 'largo_cm' => 589, 'ancho_cm' => 235, 'alto_cm' => 239, 'capacidad_max_kg' => 28180, 'pallets_max' => 10, 'sacos_max' => 260, 'flete_maritimo' => 2500, 'seguro' => 200, 'gastos_portuarios' => 350, 'documentacion' => 100],
            ['tipo' => '40ft', 'largo_cm' => 1203, 'ancho_cm' => 235, 'alto_cm' => 239, 'capacidad_max_kg' => 28750, 'pallets_max' => 20, 'sacos_max' => 520, 'flete_maritimo' => 4000, 'seguro' => 350, 'gastos_portuarios' => 500, 'documentacion' => 150],
            ['tipo' => '40hq', 'largo_cm' => 1203, 'ancho_cm' => 235, 'alto_cm' => 269, 'capacidad_max_kg' => 28500, 'pallets_max' => 22, 'sacos_max' => 560, 'flete_maritimo' => 4500, 'seguro' => 400, 'gastos_portuarios' => 550, 'documentacion' => 150],
        ]);

        // Gastos Operativos
        DB::table('exim_gastos_operativos')->insert([
            ['nombre' => 'Compra de Café', 'descripcion' => 'Costo de adquisición del café verde', 'costo' => 8.50, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Beneficiado', 'descripcion' => 'Proceso de beneficiado húmedo', 'costo' => 0.50, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Clasificación', 'descripcion' => 'Clasificación y selección de granos', 'costo' => 0.30, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Trilla', 'descripcion' => 'Proceso de trillado', 'costo' => 0.20, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Tostado', 'descripcion' => 'Proceso de tostado', 'costo' => 0.80, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Molienda', 'descripcion' => 'Proceso de molienda', 'costo' => 0.40, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Empaque', 'descripcion' => 'Empaque al vacío o en bolsas', 'costo' => 0.60, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Etiquetado', 'descripcion' => 'Diseño e impresión de etiquetas', 'costo' => 0.15, 'tipo_calculo' => 'por_saco', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Transporte Interno', 'descripcion' => 'Transporte de la finca al almacén', 'costo' => 0.25, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Mano de Obra', 'descripcion' => 'Costo de mano de obra directa', 'costo' => 0.35, 'tipo_calculo' => 'por_kg', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Gastos Administrativos', 'descripcion' => 'Gastos de administración general', 'costo' => 200, 'tipo_calculo' => 'fijo', 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Costos Financieros', 'descripcion' => 'Costos de financiamiento bancario', 'costo' => 1.5, 'tipo_calculo' => 'fijo', 'moneda_id' => 1, 'activo' => true],
        ]);

        // Gastos Logísticos
        DB::table('exim_gastos_logisticos')->insert([
            ['nombre' => 'Transporte a Puerto', 'descripcion' => 'Flete interno desde almacén hasta puerto de embarque', 'costo' => 300, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Agente de Carga', 'descripcion' => 'Honorarios del agente de carga internacional', 'costo' => 150, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Aduanas - Origen', 'descripcion' => 'Gastos de despacho aduanero de exportación', 'costo' => 200, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Certificado de Origen', 'descripcion' => 'Emisión del certificado de origen', 'costo' => 30, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Certificado Fitosanitario', 'descripcion' => 'Certificado fitosanitario SENASA', 'costo' => 50, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'SENASA', 'descripcion' => 'Inspección fitosanitaria de exportación', 'costo' => 40, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Inspecciones', 'descripcion' => 'Inspecciones de calidad y cantidad', 'costo' => 80, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Documentación', 'descripcion' => 'Elaboración de documentos de exportación', 'costo' => 60, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'THC', 'descripcion' => 'Terminal Handling Charges', 'costo' => 120, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Manipuleo', 'descripcion' => 'Carguío y descarguío de contenedor', 'costo' => 90, 'moneda_id' => 1, 'activo' => true],
            ['nombre' => 'Forwarder', 'descripcion' => 'Honorarios del forwarder', 'costo' => 100, 'moneda_id' => 1, 'activo' => true],
        ]);
    }
}
