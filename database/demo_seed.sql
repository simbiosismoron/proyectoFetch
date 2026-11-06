-- Datos de demostración Pampa. Ejecutar únicamente en una base NUEVA.
-- No contiene usuarios ni ventas: crear cuentas desde el registro de la aplicación.
-- Compatible con el esquema de ppampa_backup.sql.
START TRANSACTION;
INSERT INTO categorias (idCategoria, nombre, idCategoriaPadre) VALUES
(1,'Tripas',NULL),(2,'Condimentos',NULL),(3,'Integrales',NULL),(4,'Máquinas',NULL),(5,'Repuestos',NULL);

-- Proveedores ficticios; CUIT ilustrativos, no válidos para facturación.
INSERT INTO proveedores (idProveedor,nombre,cuit,razonSocial) VALUES
(1,'Insumos del Centro (demo)','00000000001','Insumos del Centro Demo'),
(2,'Equipamiento Pampeano (demo)','00000000002','Equipamiento Pampeano Demo'),
(3,'Suministros Regionales (demo)','00000000003','Suministros Regionales Demo');

INSERT INTO productos (idProducto,nombre,codigo,idCategoria,descripcion,costo,precio,foto,idProveedor,stock,unidadMedida,incremento,activo) VALUES
(1,'Tripa porcina calibre 32/34','TRI-POR-3234',1,'Presentación por paquete para elaboración de embutidos.',18500,26000,NULL,1,35,'paquete',1,1),
(2,'Tripa porcina calibre 34/36','TRI-POR-3436',1,'Presentación por paquete; calibre 34/36.',20500,29000,NULL,1,28,'paquete',1,1),
(3,'Tripa porcina calibre 36/38','TRI-POR-3638',1,'Presentación por paquete; calibre 36/38.',22500,31500,NULL,1,22,'paquete',1,1),
(4,'Tripa vacuna para embutidos','TRI-VAC-001',1,'Presentación por paquete para embutidos de mayor calibre.',27000,38000,NULL,3,15,'paquete',1,1),
(5,'Tripa colágena calibre 21','TRI-COL-21',1,'Tripa de colágeno para producción de embutidos.',16500,23500,NULL,3,18,'paquete',1,1),
(6,'Ají molido - Caja 5 kg','CON-AJI-5',2,'Caja de ají molido de 5 kg.',19500,27500,NULL,1,24,'caja',1,1),
(7,'Pimentón dulce - Caja 5 kg','CON-PIM-5',2,'Caja de pimentón dulce de 5 kg.',22000,31000,NULL,1,20,'caja',1,1),
(8,'Orégano seco - Caja 5 kg','CON-ORE-5',2,'Caja de orégano seco de 5 kg.',25000,35000,NULL,1,17,'caja',1,1),
(9,'Pimienta negra molida - Caja 5 kg','CON-PIN-5',2,'Caja de pimienta negra molida de 5 kg.',62000,85000,NULL,3,9,'caja',1,1),
(10,'Ajo deshidratado - Caja 5 kg','CON-AJO-5',2,'Caja de ajo deshidratado de 5 kg.',34500,48000,NULL,3,14,'caja',1,1),
(11,'Integral para chorizo fresco - Bolsa 5 kg','INT-CHO-5',3,'Mezcla integral para elaboración de chorizo fresco.',29500,42000,NULL,1,30,'bolsa',1,1),
(12,'Integral para salame - Bolsa 5 kg','INT-SAL-5',3,'Mezcla integral para elaboración de salame.',33500,47500,NULL,1,16,'bolsa',1,1),
(13,'Integral para hamburguesas - Bolsa 5 kg','INT-HAM-5',3,'Mezcla integral para preparación de hamburguesas.',26500,37500,NULL,3,21,'bolsa',1,1),
(14,'Integral para morcilla - Bolsa 5 kg','INT-MOR-5',3,'Mezcla integral para elaboración de morcilla.',28500,40000,NULL,3,12,'bolsa',1,1),
(15,'Integral para longaniza - Bolsa 5 kg','INT-LON-5',3,'Mezcla integral para elaboración de longaniza.',32500,46000,NULL,1,13,'bolsa',1,1),
(16,'Embutidora manual 5 litros','MAQ-EMB-05',4,'Embutidora manual de acero inoxidable de 5 litros.',145000,199000,NULL,2,4,'unidad',1,1),
(17,'Embutidora manual 10 litros','MAQ-EMB-10',4,'Embutidora manual de acero inoxidable de 10 litros.',215000,299000,NULL,2,2,'unidad',1,1),
(18,'Picadora de carne N° 22','MAQ-PIC-22',4,'Picadora de carne para producción comercial.',340000,465000,NULL,2,3,'unidad',1,1),
(19,'Mezcladora de carne 20 litros','MAQ-MEZ-20',4,'Mezcladora de carne de 20 litros.',490000,665000,NULL,2,1,'unidad',1,1),
(20,'Selladora de bolsas de mesa','MAQ-SEL-01',4,'Selladora térmica de mesa para bolsas.',74000,105000,NULL,2,0,'unidad',1,1),
(21,'Cuchilla para picadora N° 22','REP-CUC-22',5,'Cuchilla de repuesto compatible con picadora N° 22.',11500,16500,NULL,2,16,'unidad',1,1),
(22,'Disco para picadora N° 22 - 6 mm','REP-DIS-2206',5,'Disco perforado de 6 mm para picadora N° 22.',13500,19500,NULL,2,12,'unidad',1,1),
(23,'Disco para picadora N° 22 - 8 mm','REP-DIS-2208',5,'Disco perforado de 8 mm para picadora N° 22.',13500,19500,NULL,2,11,'unidad',1,1),
(24,'Embudo para embutidora 20 mm','REP-EMB-20',5,'Boquilla de repuesto de 20 mm.',8500,12500,NULL,2,23,'unidad',1,1),
(25,'Junta para embutidora 5 litros','REP-JUN-05',5,'Junta de repuesto para embutidora manual de 5 litros.',5500,8500,NULL,2,19,'unidad',1,1);
COMMIT;
-- Precios, costos y existencias son ilustrativos, no cotizaciones reales.
