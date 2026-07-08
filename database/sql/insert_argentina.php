<?php
require 'C:\xampp\htdocs\mvc-lito\vendor\autoload.php';
$app = require_once 'C:\xampp\htdocs\mvc-lito\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pais_id = 2;

// ============ DEPARTAMENTOS (provincias argentinas + CABA) ============
$departamentos = [
    'Buenos Aires',
    'Catamarca',
    'Chaco',
    'Chubut',
    'Córdoba',
    'Corrientes',
    'Entre Ríos',
    'Formosa',
    'Jujuy',
    'La Pampa',
    'La Rioja',
    'Mendoza',
    'Misiones',
    'Neuquén',
    'Río Negro',
    'Salta',
    'San Juan',
    'San Luis',
    'Santa Cruz',
    'Santa Fe',
    'Santiago del Estero',
    'Tierra del Fuego, Antártida e Islas del Atlántico Sur',
    'Tucumán',
    'Ciudad Autónoma de Buenos Aires',
];

DB::statement('SET FOREIGN_KEY_CHECKS=0');
$depto_ids = [];
foreach ($departamentos as $d) {
    DB::table('departamentos')->insert(['pais_id' => $pais_id, 'nombre' => $d]);
    $depto_ids[] = DB::getPdo()->lastInsertId();
}
$depto_map = array_combine($departamentos, $depto_ids);

// ============ PROVINCIAS (departamentos/partidos argentinos) ============
$provincias = [
    'Buenos Aires' => [
        'Adolfo Alsina', 'Adolfo Gonzales Chaves', 'Alberti', 'Almirante Brown',
        'Arrecifes', 'Avellaneda', 'Ayacucho', 'Azul', 'Bahía Blanca', 'Balcarce',
        'Baradero', 'Benito Juárez', 'Berazategui', 'Berisso', 'Bolívar',
        'Bragado', 'Brandsen', 'Campana', 'Cañuelas', 'Capitán Sarmiento',
        'Carlos Casares', 'Carlos Tejedor', 'Carmen de Areco', 'Castelli',
        'Chacabuco', 'Chascomús', 'Chivilcoy', 'Colón', 'Coronel Dorrego',
        'Coronel Pringles', 'Coronel Rosales', 'Coronel Suárez', 'Daireaux',
        'Dolores', 'Ensenada', 'Escobar', 'Esteban Echeverría', 'Exaltación de la Cruz',
        'Ezeiza', 'Florencio Varela', 'Florentino Ameghino', 'General Alvarado',
        'General Alvear', 'General Arenales', 'General Belgrano', 'General Guido',
        'General Juan Madariaga', 'General La Madrid', 'General Las Heras',
        'General Lavalle', 'General Paz', 'General Pinto', 'General Pueyrredón',
        'General Rodríguez', 'General San Martín', 'General Viamonte',
        'General Villegas', 'Guaminí', 'Hipólito Yrigoyen', 'Hurlingham',
        'Ituzaingó', 'José C. Paz', 'Junín', 'La Costa', 'La Matanza', 'La Plata',
        'Lanus', 'Laprida', 'Las Flores', 'Leandro N. Alem', 'Lezama', 'Lincoln',
        'Lobería', 'Lobos', 'Lomas de Zamora', 'Luján', 'Magdalena', 'Maipú',
        'Malvinas Argentinas', 'Mar Chiquita', 'Marcos Paz', 'Mercedes',
        'Merlo', 'Monte', 'Monte Hermoso', 'Moreno', 'Morón', 'Navarro',
        'Necochea', 'Nueve de Julio', 'Olavarría', 'Patagones', 'Pehuajó',
        'Pellegrini', 'Pergamino', 'Pila', 'Pilar', 'Pinamar', 'Presidente Perón',
        'Puán', 'Punta Indio', 'Quilmes', 'Ramallo', 'Rauch', 'Rivadavia',
        'Rojas', 'Roque Pérez', 'Saavedra', 'Saladillo', 'Salliqueló',
        'Salto', 'San Andrés de Giles', 'San Antonio de Areco', 'San Cayetano',
        'San Fernando', 'San Isidro', 'San Miguel', 'San Miguel del Monte',
        'San Nicolás', 'San Pedro', 'San Vicente', 'Suipacha', 'Tandil',
        'Tapalqué', 'Tigre', 'Tordillo', 'Tornquist', 'Trenque Lauquen',
        'Tres Arroyos', 'Tres de Febrero', 'Tres Lomas', 'Veinticinco de Mayo',
        'Vicente López', 'Villa Gesell', 'Villarino', 'Zárate',
    ],
    'Catamarca' => [
        'Ambato', 'Ancasti', 'Andalgalá', 'Antofagasta de la Sierra', 'Belén',
        'Capayán', 'Capital', 'El Alto', 'Fray Mamerto Esquiú', 'La Paz',
        'Paclín', 'Pomán', 'Santa María', 'Santa Rosa', 'Tinogasta', 'Valle Viejo',
    ],
    'Chaco' => [
        'Almirante Brown', 'Bermejo', 'Chacabuco', 'Comandante Fernández',
        'Doce de Octubre', 'Dos de Abril', 'Fray Justo Santa María de Oro',
        'General Belgrano', 'General Donovan', 'General Güemes', 'Independencia',
        'Libertad', 'Libertador General San Martín', 'Maipú', 'Mayor Luis Fontana',
        'Nueve de Julio', 'O\'Higgins', 'Presidencia de la Plaza',
        'Primero de Mayo', 'Quitilipi', 'San Fernando', 'San Lorenzo',
        'Sargento Cabral', 'Tapenagá', 'Veinticinco de Mayo',
    ],
    'Chubut' => [
        'Biedma', 'Cushamen', 'Escalante', 'Florentino Ameghino', 'Futaleufú',
        'Gaiman', 'Gastre', 'Languiñeo', 'Mártires', 'Paso de Indios',
        'Rawson', 'Río Senguer', 'Sarmiento', 'Tehuelches', 'Telsen',
    ],
    'Córdoba' => [
        'Calamuchita', 'Capital', 'Colón', 'Cruz del Eje', 'General Roca',
        'General San Martín', 'Ischilín', 'Juárez Celman', 'Marcos Juárez',
        'Minas', 'Pocho', 'Presidente Roque Sáenz Peña', 'Punilla',
        'Río Cuarto', 'Río Primero', 'Río Seco', 'Río Segundo', 'San Alberto',
        'San Javier', 'San Justo', 'Santa María', 'Sobremonte', 'Tercero Arriba',
        'Totoral', 'Tulumba', 'Unión',
    ],
    'Corrientes' => [
        'Alvear', 'Bella Vista', 'Berón de Astrada', 'Capital', 'Concepción',
        'Curuzú Cuatiá', 'Empedrado', 'Esquina', 'General Alvear', 'General Paz',
        'Goya', 'Itatí', 'Ituzaingó', 'Lavalle', 'Mburucuyá', 'Mercedes',
        'Monte Caseros', 'Paso de los Libres', 'Saladas', 'San Cosme',
        'San Luis del Palmar', 'San Martín', 'San Miguel', 'San Roque',
        'Santo Tomé', 'Sauce',
    ],
    'Entre Ríos' => [
        'Colón', 'Concordia', 'Diamante', 'Federación', 'Federal',
        'Gualeguay', 'Gualeguaychú', 'Islas del Ibicuy', 'La Paz', 'Nogoyá',
        'Paraná', 'San José de Feliciano', 'San Salvador', 'Tala',
        'Uruguay', 'Victoria', 'Villaguay',
    ],
    'Formosa' => [
        'Bermejo', 'Formosa', 'Laishí', 'Matacos', 'Patiño', 'Pilagás',
        'Pilcomayo', 'Pirané', 'Ramón Lista',
    ],
    'Jujuy' => [
        'Cochinoca', 'Dr. Manuel Belgrano', 'El Carmen', 'Humahuaca', 'Ledesma',
        'Palpalá', 'Rinconada', 'San Antonio', 'San Pedro', 'Santa Bárbara',
        'Santa Catalina', 'Susques', 'Tilcara', 'Tumbaya', 'Valle Grande',
        'Yavi',
    ],
    'La Pampa' => [
        'Atreucó', 'Caleu Caleu', 'Capital', 'Catriló', 'Chalileo', 'Chapaleufú',
        'Chical Co', 'Conhelo', 'Curacó', 'Guatraché', 'Hucal', 'Lihuel Calel',
        'Limay Mahuida', 'Loventué', 'Maracó', 'Puelén', 'Quemú Quemú',
        'Rancul', 'Realicó', 'Toay', 'Trenel', 'Utracán',
    ],
    'La Rioja' => [
        'Arauco', 'Capital', 'Castro Barros', 'Chamical', 'Chilecito',
        'Coronel Felipe Varela', 'Famatina', 'General Ángel V. Peñaloza',
        'General Belgrano', 'General Lamadrid', 'General Ocampo',
        'General San Martín', 'Independencia', 'Rosario Vera Peñaloza',
        'San Blas de los Sauces', 'Sanagasta', 'Vinchina',
    ],
    'Mendoza' => [
        'Capital', 'General Alvear', 'Godoy Cruz', 'Guaymallén', 'Junín',
        'La Paz', 'Las Heras', 'Lavalle', 'Luján de Cuyo', 'Maipú',
        'Malargüe', 'Rivadavia', 'San Carlos', 'San Martín', 'San Rafael',
        'Santa Rosa', 'Tunuyán', 'Tupungato',
    ],
    'Misiones' => [
        'Apóstoles', 'Cainguás', 'Candelaria', 'Capital', 'Concepción',
        'El Dorado', 'General Manuel Belgrano', 'Guaraní', 'Iguazú',
        'Leandro N. Alem', 'Libertador General San Martín', 'Montecarlo',
        'Oberá', 'San Ignacio', 'San Javier', 'San Pedro', 'Veinticinco de Mayo',
    ],
    'Neuquén' => [
        'Aluminé', 'Añelo', 'Catán Lil', 'Chos Malal', 'Collón Curá',
        'Confluencia', 'Huiliches', 'Lácar', 'Loncopué', 'Los Lagos',
        'Minas', 'Ñorquín', 'Pehuenches', 'Picún Leufú', 'Picunches',
        'Zapala',
    ],
    'Río Negro' => [
        'Adolfo Alsina', 'Avellaneda', 'Bariloche', 'Conesa', 'El Cuy',
        'General Roca', 'Ñorquincó', 'Pichi Mahuida', 'Pilcaniyeu',
        'San Antonio', 'Valcheta', 'Veinticinco de Mayo', 'Nueve de Julio',
    ],
    'Salta' => [
        'Anta', 'Cachi', 'Cafayate', 'Capital', 'Cerrillos', 'Chicoana',
        'General Güemes', 'General José de San Martín', 'Guachipas',
        'Iruya', 'La Candelaria', 'La Caldera', 'La Poma', 'La Viña',
        'Los Andes', 'Metán', 'Molinos', 'Orán', 'Rivadavia', 'Rosario de la Frontera',
        'Rosario de Lerma', 'San Carlos', 'Santa Victoria',
    ],
    'San Juan' => [
        'Albardón', 'Angaco', 'Calingasta', 'Capital', 'Caucete', 'Chimbas',
        'Iglesia', 'Jáchal', 'Nueve de Julio', 'Pocito', 'Rawson',
        'Rivadavia', 'San Martín', 'Santa Lucía', 'Sarmiento', 'Ullum',
        'Valle Fértil', 'Veinticinco de Mayo', 'Zonda',
    ],
    'San Luis' => [
        'Ayacucho', 'Belgrano', 'Capital', 'Coronel Pringles', 'General Pedernera',
        'Gobernador Dupuy', 'Junín', 'Libertador General San Martín',
    ],
    'Santa Cruz' => [
        'Corpen Aike', 'Deseado', 'Güer Aike', 'Lago Argentino', 'Lago Buenos Aires',
        'Magallanes', 'Río Chico',
    ],
    'Santa Fe' => [
        'Belgrano', 'Caseros', 'Castellanos', 'Constitución', 'Garay',
        'General López', 'General Obligado', 'Iriondo', 'La Capital',
        'Las Colonias', 'Nueve de Julio', 'Rosario', 'San Cristóbal',
        'San Javier', 'San Jerónimo', 'San Justo', 'San Lorenzo',
        'San Martín', 'Vera',
    ],
    'Santiago del Estero' => [
        'Aguirre', 'Alberdi', 'Atamisqui', 'Avellaneda', 'Banda', 'Belgrano',
        'Capital', 'Choya', 'Copo', 'Figueroa', 'General Taboada', 'Guasayán',
        'Jiménez', 'Juan F. Ibarra', 'Loreto', 'Los Telares',
        'Mitre', 'Moreno', 'Ojo de Agua', 'Pellegrini', 'Quebrachos',
        'Río Hondo', 'Rivadavia', 'Robles', 'Salavina', 'San Martín',
        'Sarmiento', 'Silípica',
    ],
    'Tierra del Fuego, Antártida e Islas del Atlántico Sur' => [
        'Antártida Argentina', 'Islas del Atlántico Sur', 'Río Grande', 'Ushuaia',
    ],
    'Tucumán' => [
        'Burruyacú', 'Capital', 'Chicligasta', 'Cruz Alta', 'Famaillá',
        'Graneros', 'Juan Bautista Alberdi', 'La Cocha', 'Leales', 'Lules',
        'Monteros', 'Río Chico', 'Simoca', 'Tafí del Valle', 'Tafí Viejo',
        'Trancas', 'Yerba Buena',
    ],
    'Ciudad Autónoma de Buenos Aires' => [
        'Comuna 1', 'Comuna 2', 'Comuna 3', 'Comuna 4', 'Comuna 5',
        'Comuna 6', 'Comuna 7', 'Comuna 8', 'Comuna 9', 'Comuna 10',
        'Comuna 11', 'Comuna 12', 'Comuna 13', 'Comuna 14', 'Comuna 15',
    ],
];

$prov_ids = [];
foreach ($provincias as $depto_nombre => $prov_list) {
    $depto_id = $depto_map[$depto_nombre];
    foreach ($prov_list as $prov_nombre) {
        DB::table('provincias')->insert(['departamento_id' => $depto_id, 'nombre' => $prov_nombre]);
        $prov_ids[$depto_nombre][] = DB::getPdo()->lastInsertId();
    }
}

echo "Inserted " . count($departamentos) . " departamentos (provinces) for Argentina.\n";
$total_prov = 0;
foreach ($provincias as $d => $ps) { $total_prov += count($ps); }
echo "Inserted $total_prov provincias (departments/partidos) for Argentina.\n";

// ============ DISTRITOS (cabeceras de cada departamento/partido) ============
$cabeceras = [
    'Buenos Aires' => [
        'Adolfo Alsina' => 'Carhué', 'Adolfo Gonzales Chaves' => 'Adolfo Gonzales Chaves',
        'Alberti' => 'Alberti', 'Almirante Brown' => 'Adrogué',
        'Arrecifes' => 'Arrecifes', 'Avellaneda' => 'Avellaneda',
        'Ayacucho' => 'Ayacucho', 'Azul' => 'Azul', 'Bahía Blanca' => 'Bahía Blanca',
        'Balcarce' => 'Balcarce', 'Baradero' => 'Baradero', 'Benito Juárez' => 'Benito Juárez',
        'Berazategui' => 'Berazategui', 'Berisso' => 'Berisso', 'Bolívar' => 'Bolívar',
        'Bragado' => 'Bragado', 'Brandsen' => 'Brandsen', 'Campana' => 'Campana',
        'Cañuelas' => 'Cañuelas', 'Capitán Sarmiento' => 'Capitán Sarmiento',
        'Carlos Casares' => 'Carlos Casares', 'Carlos Tejedor' => 'Carlos Tejedor',
        'Carmen de Areco' => 'Carmen de Areco', 'Castelli' => 'Castelli',
        'Chacabuco' => 'Chacabuco', 'Chascomús' => 'Chascomús', 'Chivilcoy' => 'Chivilcoy',
        'Colón' => 'Colón', 'Coronel Dorrego' => 'Coronel Dorrego',
        'Coronel Pringles' => 'Coronel Pringles', 'Coronel Rosales' => 'Punta Alta',
        'Coronel Suárez' => 'Coronel Suárez', 'Daireaux' => 'Daireaux',
        'Dolores' => 'Dolores', 'Ensenada' => 'Ensenada', 'Escobar' => 'Belén de Escobar',
        'Esteban Echeverría' => 'Monte Grande', 'Exaltación de la Cruz' => 'Capilla del Señor',
        'Ezeiza' => 'Ezeiza', 'Florencio Varela' => 'Florencio Varela',
        'Florentino Ameghino' => 'Florentino Ameghino', 'General Alvarado' => 'Miramar',
        'General Alvear' => 'General Alvear', 'General Arenales' => 'General Arenales',
        'General Belgrano' => 'General Belgrano', 'General Guido' => 'General Guido',
        'General Juan Madariaga' => 'General Juan Madariaga',
        'General La Madrid' => 'General La Madrid', 'General Las Heras' => 'General Las Heras',
        'General Lavalle' => 'General Lavalle', 'General Paz' => 'Ranchos',
        'General Pinto' => 'General Pinto', 'General Pueyrredón' => 'Mar del Plata',
        'General Rodríguez' => 'General Rodríguez', 'General San Martín' => 'San Martín',
        'General Viamonte' => 'General Viamonte', 'General Villegas' => 'General Villegas',
        'Guaminí' => 'Guaminí', 'Hipólito Yrigoyen' => 'Henderson',
        'Hurlingham' => 'Hurlingham', 'Ituzaingó' => 'Ituzaingó',
        'José C. Paz' => 'José C. Paz', 'Junín' => 'Junín', 'La Costa' => 'Mar del Tuyú',
        'La Matanza' => 'San Justo', 'La Plata' => 'La Plata', 'Lanus' => 'Lanús',
        'Laprida' => 'Laprida', 'Las Flores' => 'Las Flores', 'Leandro N. Alem' => 'Leandro N. Alem',
        'Lezama' => 'Lezama', 'Lincoln' => 'Lincoln', 'Lobería' => 'Lobería',
        'Lobos' => 'Lobos', 'Lomas de Zamora' => 'Lomas de Zamora', 'Luján' => 'Luján',
        'Magdalena' => 'Magdalena', 'Maipú' => 'Maipú',
        'Malvinas Argentinas' => 'Los Polvorines', 'Mar Chiquita' => 'Coronel Vidal',
        'Marcos Paz' => 'Marcos Paz', 'Mercedes' => 'Mercedes', 'Merlo' => 'Merlo',
        'Monte' => 'Monte', 'Monte Hermoso' => 'Monte Hermoso', 'Moreno' => 'Moreno',
        'Morón' => 'Morón', 'Navarro' => 'Navarro', 'Necochea' => 'Necochea',
        'Nueve de Julio' => 'Nueve de Julio', 'Olavarría' => 'Olavarría',
        'Patagones' => 'Carmen de Patagones', 'Pehuajó' => 'Pehuajó',
        'Pellegrini' => 'Pellegrini', 'Pergamino' => 'Pergamino', 'Pila' => 'Pila',
        'Pilar' => 'Pilar', 'Pinamar' => 'Pinamar', 'Presidente Perón' => 'Presidente Perón',
        'Puán' => 'Puán', 'Punta Indio' => 'Punta Indio', 'Quilmes' => 'Quilmes',
        'Ramallo' => 'Ramallo', 'Rauch' => 'Rauch', 'Rivadavia' => 'Rivadavia',
        'Rojas' => 'Rojas', 'Roque Pérez' => 'Roque Pérez', 'Saavedra' => 'Saavedra',
        'Saladillo' => 'Saladillo', 'Salliqueló' => 'Salliqueló', 'Salto' => 'Salto',
        'San Andrés de Giles' => 'San Andrés de Giles', 'San Antonio de Areco' => 'San Antonio de Areco',
        'San Cayetano' => 'San Cayetano', 'San Fernando' => 'San Fernando',
        'San Isidro' => 'San Isidro', 'San Miguel' => 'San Miguel',
        'San Miguel del Monte' => 'San Miguel del Monte',
        'San Nicolás' => 'San Nicolás de los Arroyos', 'San Pedro' => 'San Pedro',
        'San Vicente' => 'San Vicente', 'Suipacha' => 'Suipacha', 'Tandil' => 'Tandil',
        'Tapalqué' => 'Tapalqué', 'Tigre' => 'Tigre', 'Tordillo' => 'Tordillo',
        'Tornquist' => 'Tornquist', 'Trenque Lauquen' => 'Trenque Lauquen',
        'Tres Arroyos' => 'Tres Arroyos', 'Tres de Febrero' => 'Caseros',
        'Tres Lomas' => 'Tres Lomas', 'Veinticinco de Mayo' => 'Veinticinco de Mayo',
        'Vicente López' => 'Olivos', 'Villa Gesell' => 'Villa Gesell',
        'Villarino' => 'Médanos', 'Zárate' => 'Zárate',
    ],
    'Catamarca' => [
        'Ambato' => 'La Puerta', 'Ancasti' => 'Ancasti', 'Andalgalá' => 'Andalgalá',
        'Antofagasta de la Sierra' => 'Antofagasta de la Sierra', 'Belén' => 'Belén',
        'Capayán' => 'Huillapima', 'Capital' => 'San Fernando del Valle de Catamarca',
        'El Alto' => 'El Alto', 'Fray Mamerto Esquiú' => 'San José',
        'La Paz' => 'Recreo', 'Paclín' => 'La Merced', 'Pomán' => 'Pomán',
        'Santa María' => 'Santa María', 'Santa Rosa' => 'Santa Rosa',
        'Tinogasta' => 'Tinogasta', 'Valle Viejo' => 'San Isidro',
    ],
    'Chaco' => [
        'Almirante Brown' => 'Concepción del Bermejo', 'Bermejo' => 'La Leonesa',
        'Chacabuco' => 'Charata', 'Comandante Fernández' => 'Presidencia Roque Sáenz Peña',
        'Doce de Octubre' => 'General Pinedo', 'Dos de Abril' => 'Hermoso Campo',
        'Fray Justo Santa María de Oro' => 'Santa Sylvina', 'General Belgrano' => 'Corzuela',
        'General Donovan' => 'Makallé', 'General Güemes' => 'Juan José Castelli',
        'Independencia' => 'Campo Largo', 'Libertad' => 'Puerto Tirol',
        'Libertador General San Martín' => 'General José de San Martín',
        'Maipú' => 'Tres Isletas', 'Mayor Luis Fontana' => 'Villa Ángela',
        'Nueve de Julio' => 'Las Breñas', 'O\'Higgins' => 'La Clotilde',
        'Presidencia de la Plaza' => 'Presidencia de la Plaza', 'Primero de Mayo' => 'Margarita Belén',
        'Quitilipi' => 'Quitilipi', 'San Fernando' => 'Resistencia',
        'San Lorenzo' => 'Villa Berthet', 'Sargento Cabral' => 'Colonia Elisa',
        'Tapenagá' => 'Charadai', 'Veinticinco de Mayo' => 'Machagai',
    ],
    'Chubut' => [
        'Biedma' => 'Puerto Madryn', 'Cushamen' => 'Cushamen', 'Escalante' => 'Comodoro Rivadavia',
        'Florentino Ameghino' => 'Florentino Ameghino', 'Futaleufú' => 'Esquel',
        'Gaiman' => 'Gaiman', 'Gastre' => 'Gastre', 'Languiñeo' => 'Tecka',
        'Mártires' => 'Las Plumas', 'Paso de Indios' => 'Paso de Indios',
        'Rawson' => 'Rawson', 'Río Senguer' => 'Río Mayo', 'Sarmiento' => 'Sarmiento',
        'Tehuelches' => 'José de San Martín', 'Telsen' => 'Telsen',
    ],
    'Córdoba' => [
        'Calamuchita' => 'San Agustín', 'Capital' => 'Córdoba', 'Colón' => 'Jesús María',
        'Cruz del Eje' => 'Cruz del Eje', 'General Roca' => 'Villa Huidobro',
        'General San Martín' => 'Villa María', 'Ischilín' => 'Deán Funes',
        'Juárez Celman' => 'La Carlota', 'Marcos Juárez' => 'Marcos Juárez',
        'Minas' => 'San Carlos Minas', 'Pocho' => 'Salsacate',
        'Presidente Roque Sáenz Peña' => 'Laboulaye', 'Punilla' => 'Cosquín',
        'Río Cuarto' => 'Río Cuarto', 'Río Primero' => 'Santa Rosa de Río Primero',
        'Río Seco' => 'Villa de María', 'Río Segundo' => 'Villa del Rosario',
        'San Alberto' => 'Villa Cura Brochero', 'San Javier' => 'Villa Dolores',
        'San Justo' => 'San Francisco', 'Santa María' => 'Alta Gracia',
        'Sobremonte' => 'San Francisco del Chañar', 'Tercero Arriba' => 'Oliva',
        'Totoral' => 'Villa del Totoral', 'Tulumba' => 'Villa Tulumba',
        'Unión' => 'Bell Ville',
    ],
    'Corrientes' => [
        'Alvear' => 'Alvear', 'Bella Vista' => 'Bella Vista', 'Berón de Astrada' => 'Berón de Astrada',
        'Capital' => 'Corrientes', 'Concepción' => 'Concepción',
        'Curuzú Cuatiá' => 'Curuzú Cuatiá', 'Empedrado' => 'Empedrado',
        'Esquina' => 'Esquina', 'General Alvear' => 'General Alvear',
        'General Paz' => 'Nuestra Señora del Rosario de Caá Catí', 'Goya' => 'Goya',
        'Itatí' => 'Itatí', 'Ituzaingó' => 'Ituzaingó', 'Lavalle' => 'Lavalle',
        'Mburucuyá' => 'Mburucuyá', 'Mercedes' => 'Mercedes',
        'Monte Caseros' => 'Monte Caseros', 'Paso de los Libres' => 'Paso de los Libres',
        'Saladas' => 'Saladas', 'San Cosme' => 'San Cosme',
        'San Luis del Palmar' => 'San Luis del Palmar', 'San Martín' => 'La Cruz',
        'San Miguel' => 'San Miguel', 'San Roque' => 'San Roque',
        'Santo Tomé' => 'Santo Tomé', 'Sauce' => 'Sauce',
    ],
    'Entre Ríos' => [
        'Colón' => 'Colón', 'Concordia' => 'Concordia', 'Diamante' => 'Diamante',
        'Federación' => 'Federación', 'Federal' => 'Federal',
        'Gualeguay' => 'Gualeguay', 'Gualeguaychú' => 'Gualeguaychú',
        'Islas del Ibicuy' => 'Villa Paranacito', 'La Paz' => 'La Paz',
        'Nogoyá' => 'Nogoyá', 'Paraná' => 'Paraná',
        'San José de Feliciano' => 'San José de Feliciano', 'San Salvador' => 'San Salvador',
        'Tala' => 'Rosario del Tala', 'Uruguay' => 'Concepción del Uruguay',
        'Victoria' => 'Victoria', 'Villaguay' => 'Villaguay',
    ],
    'Formosa' => [
        'Bermejo' => 'Laguna Yema', 'Formosa' => 'Formosa', 'Laishí' => 'San Francisco de Laishí',
        'Matacos' => 'Ingeniero Juárez', 'Patiño' => 'Comandante Fontana',
        'Pilagás' => 'El Espinillo', 'Pilcomayo' => 'Clorinda', 'Pirané' => 'Pirané',
        'Ramón Lista' => 'General Mosconi',
    ],
    'Jujuy' => [
        'Cochinoca' => 'Abra Pampa', 'Dr. Manuel Belgrano' => 'San Salvador de Jujuy',
        'El Carmen' => 'El Carmen', 'Humahuaca' => 'Humahuaca', 'Ledesma' => 'Libertador General San Martín',
        'Palpalá' => 'Palpalá', 'Rinconada' => 'Rinconada', 'San Antonio' => 'San Antonio',
        'San Pedro' => 'San Pedro de Jujuy', 'Santa Bárbara' => 'Santa Clara',
        'Santa Catalina' => 'Santa Catalina', 'Susques' => 'Susques',
        'Tilcara' => 'Tilcara', 'Tumbaya' => 'Tumbaya', 'Valle Grande' => 'Valle Grande',
        'Yavi' => 'La Quiaca',
    ],
    'La Pampa' => [
        'Atreucó' => 'Macachín', 'Caleu Caleu' => 'La Adela', 'Capital' => 'Santa Rosa',
        'Catriló' => 'Catriló', 'Chalileo' => 'Santa Isabel', 'Chapaleufú' => 'Intendente Alvear',
        'Chical Co' => 'Algarrobo del Águila', 'Conhelo' => 'Eduardo Castex',
        'Curacó' => 'Puelches', 'Guatraché' => 'Guatraché', 'Hucal' => 'Bernasconi',
        'Lihuel Calel' => 'Cuchillo Co', 'Limay Mahuida' => 'Limay Mahuida',
        'Loventué' => 'Telén', 'Maracó' => 'General Pico', 'Puelén' => 'Veinticinco de Mayo',
        'Quemú Quemú' => 'Quemú Quemú', 'Rancul' => 'Rancul', 'Realicó' => 'Realicó',
        'Toay' => 'Toay', 'Trenel' => 'Trenel', 'Utracán' => 'General Acha',
    ],
    'La Rioja' => [
        'Arauco' => 'Aimogasta', 'Capital' => 'La Rioja', 'Castro Barros' => 'Aminga',
        'Chamical' => 'Chamical', 'Chilecito' => 'Chilecito',
        'Coronel Felipe Varela' => 'Villa Unión', 'Famatina' => 'Famatina',
        'General Ángel V. Peñaloza' => 'Tama', 'General Belgrano' => 'Olta',
        'General Lamadrid' => 'Villa Castelli', 'General Ocampo' => 'Milagro',
        'General San Martín' => 'Villa San Martín', 'Independencia' => 'Patquía',
        'Rosario Vera Peñaloza' => 'Chepes', 'San Blas de los Sauces' => 'San Blas',
        'Sanagasta' => 'Sanagasta', 'Vinchina' => 'Vinchina',
    ],
    'Mendoza' => [
        'Capital' => 'Mendoza', 'General Alvear' => 'General Alvear',
        'Godoy Cruz' => 'Godoy Cruz', 'Guaymallén' => 'Villa Nueva', 'Junín' => 'Junín',
        'La Paz' => 'La Paz', 'Las Heras' => 'Las Heras', 'Lavalle' => 'Lavalle',
        'Luján de Cuyo' => 'Luján de Cuyo', 'Maipú' => 'Maipú',
        'Malargüe' => 'Malargüe', 'Rivadavia' => 'Rivadavia', 'San Carlos' => 'San Carlos',
        'San Martín' => 'San Martín', 'San Rafael' => 'San Rafael',
        'Santa Rosa' => 'Santa Rosa', 'Tunuyán' => 'Tunuyán', 'Tupungato' => 'Tupungato',
    ],
    'Misiones' => [
        'Apóstoles' => 'Apóstoles', 'Cainguás' => 'Campo Grande',
        'Candelaria' => 'Candelaria', 'Capital' => 'Posadas',
        'Concepción' => 'Concepción de la Sierra', 'El Dorado' => 'El Dorado',
        'General Manuel Belgrano' => 'Bernardo de Irigoyen', 'Guaraní' => 'El Soberbio',
        'Iguazú' => 'Puerto Iguazú', 'Leandro N. Alem' => 'Leandro N. Alem',
        'Libertador General San Martín' => 'San Martín',
        'Montecarlo' => 'Montecarlo', 'Oberá' => 'Oberá',
        'San Ignacio' => 'San Ignacio', 'San Javier' => 'San Javier',
        'San Pedro' => 'San Pedro', 'Veinticinco de Mayo' => 'Alba Posse',
    ],
    'Neuquén' => [
        'Aluminé' => 'Aluminé', 'Añelo' => 'Añelo', 'Catán Lil' => 'Las Lajas',
        'Chos Malal' => 'Chos Malal', 'Collón Curá' => 'Piedra del Águila',
        'Confluencia' => 'Neuquén', 'Huiliches' => 'Junín de los Andes',
        'Lácar' => 'San Martín de los Andes', 'Loncopué' => 'Loncopué',
        'Los Lagos' => 'Villa La Angostura', 'Minas' => 'Andacollo',
        'Ñorquín' => 'El Huecú', 'Pehuenches' => 'Rincón de los Sauces',
        'Picún Leufú' => 'Picún Leufú', 'Picunches' => 'Las Lajitas',
        'Zapala' => 'Zapala',
    ],
    'Río Negro' => [
        'Adolfo Alsina' => 'Viedma', 'Avellaneda' => 'Choele Choel',
        'Bariloche' => 'San Carlos de Bariloche', 'Conesa' => 'General Conesa',
        'El Cuy' => 'El Cuy', 'General Roca' => 'General Roca',
        'Ñorquincó' => 'Ñorquincó', 'Pichi Mahuida' => 'Río Colorado',
        'Pilcaniyeu' => 'Pilcaniyeu', 'San Antonio' => 'San Antonio Oeste',
        'Valcheta' => 'Valcheta', 'Veinticinco de Mayo' => 'Maquinchao',
        'Nueve de Julio' => 'Sierra Colorada',
    ],
    'Salta' => [
        'Anta' => 'Joaquín V. González', 'Cachi' => 'Cachi', 'Cafayate' => 'Cafayate',
        'Capital' => 'Salta', 'Cerrillos' => 'Cerrillos', 'Chicoana' => 'Chicoana',
        'General Güemes' => 'General Güemes', 'General José de San Martín' => 'Tartagal',
        'Guachipas' => 'Guachipas', 'Iruya' => 'Iruya',
        'La Candelaria' => 'La Candelaria', 'La Caldera' => 'La Caldera',
        'La Poma' => 'La Poma', 'La Viña' => 'La Viña', 'Los Andes' => 'San Antonio de los Cobres',
        'Metán' => 'Metán', 'Molinos' => 'Molinos', 'Orán' => 'San Ramón de la Nueva Orán',
        'Rivadavia' => 'Rivadavia', 'Rosario de la Frontera' => 'Rosario de la Frontera',
        'Rosario de Lerma' => 'Rosario de Lerma', 'San Carlos' => 'San Carlos',
        'Santa Victoria' => 'Santa Victoria Oeste',
    ],
    'San Juan' => [
        'Albardón' => 'Albardón', 'Angaco' => 'Villa del Salvador',
        'Calingasta' => 'Calingasta', 'Capital' => 'San Juan',
        'Caucete' => 'Caucete', 'Chimbas' => 'Chimbas', 'Iglesia' => 'Rodeo',
        'Jáchal' => 'San José de Jáchal', 'Nueve de Julio' => 'Nueve de Julio',
        'Pocito' => 'Pocito', 'Rawson' => 'Rawson', 'Rivadavia' => 'Rivadavia',
        'San Martín' => 'San Martín', 'Santa Lucía' => 'Santa Lucía',
        'Sarmiento' => 'Media Agua', 'Ullum' => 'Ullum',
        'Valle Fértil' => 'Villa San Agustín', 'Veinticinco de Mayo' => 'Santa Rosa',
        'Zonda' => 'Zonda',
    ],
    'San Luis' => [
        'Ayacucho' => 'San Francisco del Monte de Oro', 'Belgrano' => 'Villa de la Quebrada',
        'Capital' => 'San Luis', 'Coronel Pringles' => 'La Toma',
        'General Pedernera' => 'Mercedes', 'Gobernador Dupuy' => 'Buena Esperanza',
        'Junín' => 'Santa Rosa de Conlara', 'Libertador General San Martín' => 'San Martín',
    ],
    'Santa Cruz' => [
        'Corpen Aike' => 'Puerto Santa Cruz', 'Deseado' => 'Puerto Deseado',
        'Güer Aike' => 'Río Gallegos', 'Lago Argentino' => 'El Calafate',
        'Lago Buenos Aires' => 'Perito Moreno', 'Magallanes' => 'San Julián',
        'Río Chico' => 'Gobernador Gregores',
    ],
    'Santa Fe' => [
        'Belgrano' => 'Armstrong', 'Caseros' => 'Casilda', 'Castellanos' => 'Rafaela',
        'Constitución' => 'Villa Constitución', 'Garay' => 'Helvecia',
        'General López' => 'Melincué', 'General Obligado' => 'Reconquista',
        'Iriondo' => 'Cañada de Gómez', 'La Capital' => 'Santa Fe',
        'Las Colonias' => 'Esperanza', 'Nueve de Julio' => 'Tostado',
        'Rosario' => 'Rosario', 'San Cristóbal' => 'San Cristóbal',
        'San Javier' => 'San Javier', 'San Jerónimo' => 'Coronda',
        'San Justo' => 'San Justo', 'San Lorenzo' => 'San Lorenzo',
        'San Martín' => 'Sastre', 'Vera' => 'Vera',
    ],
    'Santiago del Estero' => [
        'Aguirre' => 'Valle de Mayo', 'Alberdi' => 'Campo Gallo',
        'Atamisqui' => 'Villa Atamisqui', 'Avellaneda' => 'Herrera',
        'Banda' => 'La Banda', 'Belgrano' => 'Clodomira',
        'Capital' => 'Santiago del Estero', 'Choya' => 'Frías',
        'Copo' => 'Monte Quemado', 'Figueroa' => 'Figueroa',
        'General Taboada' => 'Añatuya', 'Guasayán' => 'San Pedro de Guasayán',
        'Jiménez' => 'Pozo Hondo', 'Juan F. Ibarra' => 'Suncho Corral',
        'Loreto' => 'Loreto', 'Los Telares' => 'Los Telares',
        'Mitre' => 'Villa Unión', 'Moreno' => 'Tintina',
        'Ojo de Agua' => 'Villa Ojo de Agua', 'Pellegrini' => 'Beltrán',
        'Quebrachos' => 'Sumampa', 'Río Hondo' => 'Termas de Río Hondo',
        'Rivadavia' => 'Selva', 'Robles' => 'Fernández',
        'Salavina' => 'Los Telares', 'San Martín' => 'Estación Atamisqui',
        'Sarmiento' => 'Garza', 'Silípica' => 'Árraga',
    ],
    'Tierra del Fuego, Antártida e Islas del Atlántico Sur' => [
        'Antártida Argentina' => 'Base Esperanza',
        'Islas del Atlántico Sur' => 'Puerto Argentino',
        'Río Grande' => 'Río Grande', 'Ushuaia' => 'Ushuaia',
    ],
    'Tucumán' => [
        'Burruyacú' => 'Burruyacú', 'Capital' => 'San Miguel de Tucumán',
        'Chicligasta' => 'Concepción', 'Cruz Alta' => 'Banda del Río Salí',
        'Famaillá' => 'Famaillá', 'Graneros' => 'Graneros',
        'Juan Bautista Alberdi' => 'Alberdi', 'La Cocha' => 'La Cocha',
        'Leales' => 'Bella Vista', 'Lules' => 'Lules', 'Monteros' => 'Monteros',
        'Río Chico' => 'Aguilares', 'Simoca' => 'Simoca',
        'Tafí del Valle' => 'Tafí del Valle', 'Tafí Viejo' => 'Tafí Viejo',
        'Trancas' => 'Trancas', 'Yerba Buena' => 'Yerba Buena',
    ],
    'Ciudad Autónoma de Buenos Aires' => [
        'Comuna 1' => 'Retiro', 'Comuna 2' => 'Recoleta', 'Comuna 3' => 'Balvanera',
        'Comuna 4' => 'Barracas', 'Comuna 5' => 'Almagro', 'Comuna 6' => 'Caballito',
        'Comuna 7' => 'Flores', 'Comuna 8' => 'Villa Soldati', 'Comuna 9' => 'Liniers',
        'Comuna 10' => 'Floresta', 'Comuna 11' => 'Villa Devoto', 'Comuna 12' => 'Villa Urquiza',
        'Comuna 13' => 'Belgrano', 'Comuna 14' => 'Palermo', 'Comuna 15' => 'Villa Ortúzar',
    ],
];

$total_dist = 0;
$dist_data = [];

// Build a map: depto_nombre -> [prov_nombre -> prov_id]
$prov_map = [];
foreach ($prov_ids as $depto_nombre => $ids) {
    foreach ($ids as $i => $id) {
        $prov_map[$depto_nombre][$provincias[$depto_nombre][$i]] = $id;
    }
}

foreach ($cabeceras as $depto_nombre => $prov_list) {
    foreach ($prov_list as $prov_nombre => $cabecera) {
        $pid = $prov_map[$depto_nombre][$prov_nombre];
        $dist_data[] = ['provincia_id' => $pid, 'nombre' => $cabecera];
        $total_dist++;
    }
}

foreach (array_chunk($dist_data, 100) as $chunk) {
    DB::table('distritos')->insert($chunk);
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');
echo "Inserted $total_dist distritos for Argentina.\n";
