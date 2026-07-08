-- =====================================================
-- CHILE: Provincias y Comunas (distritos)
-- =====================================================
-- departamentos (regiones) ya existen con IDs 26-41
-- =====================================================

-- ==================== PROVINCIAS ====================

INSERT INTO provincias (departamento_id, nombre) VALUES
-- Arica y Parinacota (26)
(26, 'Arica'),
(26, 'Parinacota'),
-- Tarapacá (27)
(27, 'Iquique'),
(27, 'El Tamarugal'),
-- Antofagasta (28)
(28, 'Antofagasta'),
(28, 'El Loa'),
(28, 'Tocopilla'),
-- Atacama (29)
(29, 'Copiapó'),
(29, 'Huasco'),
(29, 'Chañaral'),
-- Coquimbo (30)
(30, 'Elqui'),
(30, 'Limari'),
(30, 'Choapa'),
-- Valparaíso (31)
(31, 'Valparaíso'),
(31, 'Isla de Pascua'),
(31, 'Los Andes'),
(31, 'Marga Marga'),
(31, 'Petorca'),
(31, 'Quillota'),
(31, 'San Antonio'),
(31, 'San Felipe'),
-- Metropolitana (32)
(32, 'Santiago'),
(32, 'Cordillera'),
(32, 'Chacabuco'),
(32, 'Maipo'),
(32, 'Melipilla'),
(32, 'Talagante'),
-- O''Higgins (33)
(33, 'Cachapoal'),
(33, 'Cardenal Caro'),
(33, 'Colchagua'),
-- Maule (34)
(34, 'Talca'),
(34, 'Cauquenes'),
(34, 'Curicó'),
(34, 'Linares'),
-- Ñuble (35)
(35, 'Diguillín'),
(35, 'Itata'),
(35, 'Punilla'),
-- Biobío (36)
(36, 'Concepción'),
(36, 'Arauco'),
(36, 'Biobío'),
-- La Araucanía (37)
(37, 'Cautín'),
(37, 'Malleco'),
-- Los Ríos (38)
(38, 'Valdivia'),
(38, 'Ranco'),
-- Los Lagos (39)
(39, 'Llanquihue'),
(39, 'Chiloé'),
(39, 'Osorno'),
(39, 'Palena'),
-- Aysén (40)
(40, 'Coyhaique'),
(40, 'Aysén'),
(40, 'Capitán Prat'),
(40, 'General Carrera'),
-- Magallanes (41)
(41, 'Magallanes'),
(41, 'Antártica Chilena'),
(41, 'Tierra del Fuego'),
(41, 'Última Esperanza');

-- ==================== DISTRITOS / COMUNAS ====================

-- NOTA: los IDs de provincias se asignan en orden de inserción.
-- Consultar IDs antes de insertar distritos.

SET @prov_start = (SELECT COALESCE(MAX(id), 0) + 1 FROM provincias);
SET @prov_arica = @prov_start;
SET @prov_parinacota = @prov_start + 1;
SET @prov_iquique = @prov_start + 2;
SET @prov_tamarugal = @prov_start + 3;
SET @prov_antofagasta = @prov_start + 4;
SET @prov_el_loa = @prov_start + 5;
SET @prov_tocopilla = @prov_start + 6;
SET @prov_copiapo = @prov_start + 7;
SET @prov_huasco = @prov_start + 8;
SET @prov_chanaral = @prov_start + 9;
SET @prov_elqui = @prov_start + 10;
SET @prov_limari = @prov_start + 11;
SET @prov_choapa = @prov_start + 12;
SET @prov_valparaiso = @prov_start + 13;
SET @prov_isla_pascua = @prov_start + 14;
SET @prov_los_andes = @prov_start + 15;
SET @prov_marga_marga = @prov_start + 16;
SET @prov_petorca = @prov_start + 17;
SET @prov_quillota = @prov_start + 18;
SET @prov_san_antonio = @prov_start + 19;
SET @prov_san_felipe = @prov_start + 20;
SET @prov_santiago = @prov_start + 21;
SET @prov_cordillera = @prov_start + 22;
SET @prov_chacabuco = @prov_start + 23;
SET @prov_maipo = @prov_start + 24;
SET @prov_melipilla = @prov_start + 25;
SET @prov_talagante = @prov_start + 26;
SET @prov_cachapoal = @prov_start + 27;
SET @prov_cardenal_caro = @prov_start + 28;
SET @prov_colchagua = @prov_start + 29;
SET @prov_talca = @prov_start + 30;
SET @prov_cauquenes = @prov_start + 31;
SET @prov_curico = @prov_start + 32;
SET @prov_linares = @prov_start + 33;
SET @prov_diguillin = @prov_start + 34;
SET @prov_itata = @prov_start + 35;
SET @prov_punilla = @prov_start + 36;
SET @prov_concepcion = @prov_start + 37;
SET @prov_arauco = @prov_start + 38;
SET @prov_bio_bio = @prov_start + 39;
SET @prov_cautin = @prov_start + 40;
SET @prov_malleco = @prov_start + 41;
SET @prov_valdivia = @prov_start + 42;
SET @prov_ranco = @prov_start + 43;
SET @prov_llanquihue = @prov_start + 44;
SET @prov_chiloe = @prov_start + 45;
SET @prov_osorno = @prov_start + 46;
SET @prov_palena = @prov_start + 47;
SET @prov_coyhaique = @prov_start + 48;
SET @prov_aysen = @prov_start + 49;
SET @prov_capitan_prat = @prov_start + 50;
SET @prov_general_carrera = @prov_start + 51;
SET @prov_magallanes = @prov_start + 52;
SET @prov_antartica = @prov_start + 53;
SET @prov_tierra_fuego = @prov_start + 54;
SET @prov_ultima_esperanza = @prov_start + 55;

INSERT INTO distritos (provincia_id, nombre) VALUES

-- Arica (1)
(@prov_arica, 'Arica'),
(@prov_arica, 'Camarones'),

-- Parinacota (2)
(@prov_parinacota, 'Putre'),
(@prov_parinacota, 'General Lagos'),

-- Iquique (3)
(@prov_iquique, 'Iquique'),
(@prov_iquique, 'Alto Hospicio'),

-- Tamarugal (4)
(@prov_tamarugal, 'Pozo Almonte'),
(@prov_tamarugal, 'Camiña'),
(@prov_tamarugal, 'Colchane'),
(@prov_tamarugal, 'Huara'),
(@prov_tamarugal, 'Pica'),

-- Antofagasta (5)
(@prov_antofagasta, 'Antofagasta'),
(@prov_antofagasta, 'Mejillones'),
(@prov_antofagasta, 'Sierra Gorda'),
(@prov_antofagasta, 'Taltal'),

-- El Loa (6)
(@prov_el_loa, 'Calama'),
(@prov_el_loa, 'Ollagüe'),
(@prov_el_loa, 'San Pedro de Atacama'),

-- Tocopilla (7)
(@prov_tocopilla, 'Tocopilla'),
(@prov_tocopilla, 'María Elena'),

-- Copiapó (8)
(@prov_copiapo, 'Copiapó'),
(@prov_copiapo, 'Caldera'),
(@prov_copiapo, 'Tierra Amarilla'),

-- Huasco (9)
(@prov_huasco, 'Vallenar'),
(@prov_huasco, 'Freirina'),
(@prov_huasco, 'Huasco'),
(@prov_huasco, 'Alto del Carmen'),

-- Chañaral (10)
(@prov_chanaral, 'Chañaral'),
(@prov_chanaral, 'Diego de Almagro'),

-- Elqui (11)
(@prov_elqui, 'La Serena'),
(@prov_elqui, 'Coquimbo'),
(@prov_elqui, 'La Higuera'),
(@prov_elqui, 'Vicuña'),
(@prov_elqui, 'Paihuano'),
(@prov_elqui, 'Andacollo'),

-- Limarí (12)
(@prov_limari, 'Ovalle'),
(@prov_limari, 'Río Hurtado'),
(@prov_limari, 'Monte Patria'),
(@prov_limari, 'Combarbalá'),
(@prov_limari, 'Punitaqui'),

-- Choapa (13)
(@prov_choapa, 'Illapel'),
(@prov_choapa, 'Salamanca'),
(@prov_choapa, 'Los Vilos'),
(@prov_choapa, 'Canela'),

-- Valparaíso (14)
(@prov_valparaiso, 'Valparaíso'),
(@prov_valparaiso, 'Viña del Mar'),
(@prov_valparaiso, 'Concón'),
(@prov_valparaiso, 'Quintero'),
(@prov_valparaiso, 'Puchuncaví'),
(@prov_valparaiso, 'Casablanca'),
(@prov_valparaiso, 'Juan Fernández'),

-- Isla de Pascua (15)
(@prov_isla_pascua, 'Isla de Pascua'),

-- Los Andes (16)
(@prov_los_andes, 'Los Andes'),
(@prov_los_andes, 'San Esteban'),
(@prov_los_andes, 'Calle Larga'),
(@prov_los_andes, 'Rinconada'),

-- Marga Marga (17)
(@prov_marga_marga, 'Quilpué'),
(@prov_marga_marga, 'Villa Alemana'),
(@prov_marga_marga, 'Limache'),
(@prov_marga_marga, 'Olmué'),

-- Petorca (18)
(@prov_petorca, 'Petorca'),
(@prov_petorca, 'La Ligua'),
(@prov_petorca, 'Cabildo'),
(@prov_petorca, 'Zapallar'),
(@prov_petorca, 'Papudo'),

-- Quillota (19)
(@prov_quillota, 'Quillota'),
(@prov_quillota, 'La Calera'),
(@prov_quillota, 'Nogales'),
(@prov_quillota, 'Hijuelas'),

-- San Antonio (20)
(@prov_san_antonio, 'San Antonio'),
(@prov_san_antonio, 'Cartagena'),
(@prov_san_antonio, 'El Tabo'),
(@prov_san_antonio, 'El Quisco'),
(@prov_san_antonio, 'Algarrobo'),
(@prov_san_antonio, 'Santo Domingo'),

-- San Felipe (21)
(@prov_san_felipe, 'San Felipe'),
(@prov_san_felipe, 'Llay Llay'),
(@prov_san_felipe, 'Putaendo'),
(@prov_san_felipe, 'Santa María'),
(@prov_san_felipe, 'Catemu'),
(@prov_san_felipe, 'Panquehue'),

-- Santiago (22)
(@prov_santiago, 'Santiago'),
(@prov_santiago, 'Cerrillos'),
(@prov_santiago, 'Cerro Navia'),
(@prov_santiago, 'Conchalí'),
(@prov_santiago, 'El Bosque'),
(@prov_santiago, 'Estación Central'),
(@prov_santiago, 'Huechuraba'),
(@prov_santiago, 'Independencia'),
(@prov_santiago, 'La Cisterna'),
(@prov_santiago, 'La Florida'),
(@prov_santiago, 'La Granja'),
(@prov_santiago, 'La Pintana'),
(@prov_santiago, 'La Reina'),
(@prov_santiago, 'Las Condes'),
(@prov_santiago, 'Lo Barnechea'),
(@prov_santiago, 'Lo Espejo'),
(@prov_santiago, 'Lo Prado'),
(@prov_santiago, 'Macul'),
(@prov_santiago, 'Maipú'),
(@prov_santiago, 'Ñuñoa'),
(@prov_santiago, 'Pedro Aguirre Cerda'),
(@prov_santiago, 'Peñalolén'),
(@prov_santiago, 'Providencia'),
(@prov_santiago, 'Pudahuel'),
(@prov_santiago, 'Quilicura'),
(@prov_santiago, 'Quinta Normal'),
(@prov_santiago, 'Recoleta'),
(@prov_santiago, 'Renca'),
(@prov_santiago, 'San Joaquín'),
(@prov_santiago, 'San Miguel'),
(@prov_santiago, 'San Ramón'),
(@prov_santiago, 'Vitacura'),

-- Cordillera (23)
(@prov_cordillera, 'Puente Alto'),
(@prov_cordillera, 'Pirque'),
(@prov_cordillera, 'San José de Maipo'),

-- Chacabuco (24)
(@prov_chacabuco, 'Colina'),
(@prov_chacabuco, 'Lampa'),
(@prov_chacabuco, 'Tiltil'),

-- Maipo (25)
(@prov_maipo, 'San Bernardo'),
(@prov_maipo, 'Buin'),
(@prov_maipo, 'Paine'),
(@prov_maipo, 'Calera de Tango'),

-- Melipilla (26)
(@prov_melipilla, 'Melipilla'),
(@prov_melipilla, 'María Pinto'),
(@prov_melipilla, 'Curacaví'),
(@prov_melipilla, 'Alhué'),
(@prov_melipilla, 'San Pedro'),

-- Talagante (27)
(@prov_talagante, 'Talagante'),
(@prov_talagante, 'Peñaflor'),
(@prov_talagante, 'El Monte'),
(@prov_talagante, 'Isla de Maipo'),
(@prov_talagante, 'Padre Hurtado'),

-- Cachapoal (28)
(@prov_cachapoal, 'Rancagua'),
(@prov_cachapoal, 'Codegua'),
(@prov_cachapoal, 'Coinco'),
(@prov_cachapoal, 'Coltauco'),
(@prov_cachapoal, 'Doñihue'),
(@prov_cachapoal, 'Graneros'),
(@prov_cachapoal, 'Las Cabras'),
(@prov_cachapoal, 'Machalí'),
(@prov_cachapoal, 'Malloa'),
(@prov_cachapoal, 'Mostazal'),
(@prov_cachapoal, 'Olivar'),
(@prov_cachapoal, 'Peumo'),
(@prov_cachapoal, 'Pichidegua'),
(@prov_cachapoal, 'Quinta de Tilcoco'),
(@prov_cachapoal, 'Rengo'),
(@prov_cachapoal, 'Requínoa'),
(@prov_cachapoal, 'San Vicente de Tagua Tagua'),

-- Cardenal Caro (29)
(@prov_cardenal_caro, 'Pichilemu'),
(@prov_cardenal_caro, 'La Estrella'),
(@prov_cardenal_caro, 'Litueche'),
(@prov_cardenal_caro, 'Marchigüe'),
(@prov_cardenal_caro, 'Navidad'),
(@prov_cardenal_caro, 'Paredones'),

-- Colchagua (30)
(@prov_colchagua, 'San Fernando'),
(@prov_colchagua, 'Chépica'),
(@prov_colchagua, 'Chimbarongo'),
(@prov_colchagua, 'Lolol'),
(@prov_colchagua, 'Nancagua'),
(@prov_colchagua, 'Palmilla'),
(@prov_colchagua, 'Peralillo'),
(@prov_colchagua, 'Placilla'),
(@prov_colchagua, 'Pumanque'),
(@prov_colchagua, 'Santa Cruz'),

-- Talca (31)
(@prov_talca, 'Talca'),
(@prov_talca, 'Constitución'),
(@prov_talca, 'Curepto'),
(@prov_talca, 'Empedrado'),
(@prov_talca, 'Maule'),
(@prov_talca, 'Pelarco'),
(@prov_talca, 'Pencahue'),
(@prov_talca, 'Río Claro'),
(@prov_talca, 'San Clemente'),
(@prov_talca, 'San Rafael'),

-- Cauquenes (32)
(@prov_cauquenes, 'Cauquenes'),
(@prov_cauquenes, 'Chanco'),
(@prov_cauquenes, 'Pelluhue'),

-- Curicó (33)
(@prov_curico, 'Curicó'),
(@prov_curico, 'Hualañé'),
(@prov_curico, 'Licantén'),
(@prov_curico, 'Molina'),
(@prov_curico, 'Rauco'),
(@prov_curico, 'Romeral'),
(@prov_curico, 'Sagrada Familia'),
(@prov_curico, 'Teno'),
(@prov_curico, 'Vichuquén'),

-- Linares (34)
(@prov_linares, 'Linares'),
(@prov_linares, 'Colbún'),
(@prov_linares, 'Longaví'),
(@prov_linares, 'Parral'),
(@prov_linares, 'Retiro'),
(@prov_linares, 'San Javier'),
(@prov_linares, 'Villa Alegre'),
(@prov_linares, 'Yerbas Buenas'),

-- Diguillín (35)
(@prov_diguillin, 'Chillán'),
(@prov_diguillin, 'Chillán Viejo'),
(@prov_diguillin, 'Bulnes'),
(@prov_diguillin, 'Quillón'),
(@prov_diguillin, 'Pemuco'),
(@prov_diguillin, 'Yungay'),
(@prov_diguillin, 'El Carmen'),
(@prov_diguillin, 'Pinto'),
(@prov_diguillin, 'San Ignacio'),

-- Itata (36)
(@prov_itata, 'Quirihue'),
(@prov_itata, 'Cobquecura'),
(@prov_itata, 'Treguaco'),
(@prov_itata, 'Coelemu'),
(@prov_itata, 'Ninhue'),
(@prov_itata, 'Portezuelo'),
(@prov_itata, 'Ránquil'),

-- Punilla (37)
(@prov_punilla, 'San Carlos'),
(@prov_punilla, 'Coihueco'),
(@prov_punilla, 'San Nicolás'),
(@prov_punilla, 'Ñiquén'),

-- Concepción (38)
(@prov_concepcion, 'Concepción'),
(@prov_concepcion, 'Coronel'),
(@prov_concepcion, 'Chiguayante'),
(@prov_concepcion, 'Florida'),
(@prov_concepcion, 'Hualqui'),
(@prov_concepcion, 'Lota'),
(@prov_concepcion, 'Penco'),
(@prov_concepcion, 'San Pedro de la Paz'),
(@prov_concepcion, 'Santa Juana'),
(@prov_concepcion, 'Talcahuano'),
(@prov_concepcion, 'Tomé'),
(@prov_concepcion, 'Hualpén'),

-- Arauco (39)
(@prov_arauco, 'Lebu'),
(@prov_arauco, 'Arauco'),
(@prov_arauco, 'Cañete'),
(@prov_arauco, 'Contulmo'),
(@prov_arauco, 'Curanilahue'),
(@prov_arauco, 'Los Álamos'),
(@prov_arauco, 'Tirúa'),

-- Biobío (40)
(@prov_bio_bio, 'Los Ángeles'),
(@prov_bio_bio, 'Antuco'),
(@prov_bio_bio, 'Cabrero'),
(@prov_bio_bio, 'Laja'),
(@prov_bio_bio, 'Mulchén'),
(@prov_bio_bio, 'Nacimiento'),
(@prov_bio_bio, 'Negrete'),
(@prov_bio_bio, 'Quilaco'),
(@prov_bio_bio, 'Quilleco'),
(@prov_bio_bio, 'San Rosendo'),
(@prov_bio_bio, 'Santa Bárbara'),
(@prov_bio_bio, 'Tucapel'),
(@prov_bio_bio, 'Yumbel'),
(@prov_bio_bio, 'Alto Biobío'),

-- Cautín (41)
(@prov_cautin, 'Temuco'),
(@prov_cautin, 'Carahue'),
(@prov_cautin, 'Cholchol'),
(@prov_cautin, 'Cunco'),
(@prov_cautin, 'Curarrehue'),
(@prov_cautin, 'Freire'),
(@prov_cautin, 'Galvarino'),
(@prov_cautin, 'Gorbea'),
(@prov_cautin, 'Lautaro'),
(@prov_cautin, 'Loncoche'),
(@prov_cautin, 'Melipeuco'),
(@prov_cautin, 'Nueva Imperial'),
(@prov_cautin, 'Padre Las Casas'),
(@prov_cautin, 'Perquenco'),
(@prov_cautin, 'Pitrufquén'),
(@prov_cautin, 'Pucón'),
(@prov_cautin, 'Saavedra'),
(@prov_cautin, 'Teodoro Schmidt'),
(@prov_cautin, 'Toltén'),
(@prov_cautin, 'Vilcún'),
(@prov_cautin, 'Villarrica'),

-- Malleco (42)
(@prov_malleco, 'Angol'),
(@prov_malleco, 'Collipulli'),
(@prov_malleco, 'Curacautín'),
(@prov_malleco, 'Ercilla'),
(@prov_malleco, 'Lonquimay'),
(@prov_malleco, 'Los Sauces'),
(@prov_malleco, 'Lumaco'),
(@prov_malleco, 'Purén'),
(@prov_malleco, 'Renaico'),
(@prov_malleco, 'Traiguén'),
(@prov_malleco, 'Victoria'),

-- Valdivia (43)
(@prov_valdivia, 'Valdivia'),
(@prov_valdivia, 'Corral'),
(@prov_valdivia, 'Lanco'),
(@prov_valdivia, 'Los Lagos'),
(@prov_valdivia, 'Máfil'),
(@prov_valdivia, 'Mariquina'),
(@prov_valdivia, 'Paillaco'),
(@prov_valdivia, 'Panguipulli'),

-- Ranco (44)
(@prov_ranco, 'La Unión'),
(@prov_ranco, 'Futrono'),
(@prov_ranco, 'Lago Ranco'),
(@prov_ranco, 'Río Bueno'),

-- Llanquihue (45)
(@prov_llanquihue, 'Puerto Montt'),
(@prov_llanquihue, 'Calbuco'),
(@prov_llanquihue, 'Cochamó'),
(@prov_llanquihue, 'Fresia'),
(@prov_llanquihue, 'Frutillar'),
(@prov_llanquihue, 'Llanquihue'),
(@prov_llanquihue, 'Los Muermos'),
(@prov_llanquihue, 'Maullín'),
(@prov_llanquihue, 'Puerto Varas'),

-- Chiloé (46)
(@prov_chiloe, 'Castro'),
(@prov_chiloe, 'Ancud'),
(@prov_chiloe, 'Chonchi'),
(@prov_chiloe, 'Curaco de Vélez'),
(@prov_chiloe, 'Dalcahue'),
(@prov_chiloe, 'Puqueldón'),
(@prov_chiloe, 'Queilén'),
(@prov_chiloe, 'Quellón'),
(@prov_chiloe, 'Quemchi'),
(@prov_chiloe, 'Quinchao'),

-- Osorno (47)
(@prov_osorno, 'Osorno'),
(@prov_osorno, 'Puerto Octay'),
(@prov_osorno, 'Purranque'),
(@prov_osorno, 'Puyehue'),
(@prov_osorno, 'Río Negro'),
(@prov_osorno, 'San Juan de la Costa'),
(@prov_osorno, 'San Pablo'),

-- Palena (48)
(@prov_palena, 'Chaitén'),
(@prov_palena, 'Futaleufú'),
(@prov_palena, 'Hualaihué'),
(@prov_palena, 'Palena'),

-- Coyhaique (49)
(@prov_coyhaique, 'Coyhaique'),
(@prov_coyhaique, 'Lago Verde'),

-- Aysén (50)
(@prov_aysen, 'Puerto Aysén'),
(@prov_aysen, 'Cisnes'),
(@prov_aysen, 'Guaitecas'),

-- Capitán Prat (51)
(@prov_capitan_prat, 'Cochrane'),
(@prov_capitan_prat, 'O''Higgins'),
(@prov_capitan_prat, 'Tortel'),

-- General Carrera (52)
(@prov_general_carrera, 'Chile Chico'),
(@prov_general_carrera, 'Río Ibáñez'),

-- Magallanes (53)
(@prov_magallanes, 'Punta Arenas'),
(@prov_magallanes, 'Laguna Blanca'),
(@prov_magallanes, 'Río Verde'),
(@prov_magallanes, 'San Gregorio'),

-- Antártica Chilena (54)
(@prov_antartica, 'Cabo de Hornos'),
(@prov_antartica, 'Antártica'),

-- Tierra del Fuego (55)
(@prov_tierra_fuego, 'Porvenir'),
(@prov_tierra_fuego, 'Primavera'),
(@prov_tierra_fuego, 'Timaukel'),

-- Última Esperanza (56)
(@prov_ultima_esperanza, 'Natales'),
(@prov_ultima_esperanza, 'Torres del Paine');
