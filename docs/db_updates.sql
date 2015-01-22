-- r656
-- Creación de los descuentos para los asistentes a cursos
ALTER TABLE  `prices` ADD  `id_frequency` INT(11) NULL AFTER  `duration` ;

ALTER TABLE  `lessons_assistants` ADD  `discount` DECIMAL( 5, 2 ) NULL DEFAULT  '0' AFTER  `status` ,
ADD  `discount_type` CHAR( 1 ) NULL DEFAULT  '%' AFTER  `discount`


-- r660
-- Creación del tipo de pago asociado a los suplementos de luz
INSERT INTO  `zz_payment_type` (
`id` ,
`description` ,
`active` ,
`create_user` ,
`create_time` ,
`modify_user` ,
`modify_time`
)
VALUES (
'98',  'Suplemento luz',  '1', NULL , NULL , NULL , NULL
);

-- r665
-- Añado un nuevo tipo de tarifa
INSERT INTO  `zz_prices` (
`id` ,
`description` ,
`create_user` ,
`create_time` ,
`modify_user` ,
`modify_time`
)
VALUES (
'5',  'Clases',  '0', NULL , NULL , NULL
);

INSERT INTO  `prices` (
`pk` ,
`id` ,
`description` ,
`type` ,
`active` ,
`include_holiday` ,
`by_group` ,
`by_weekday` ,
`by_time` ,
`start_date` ,
`end_date` ,
`quantity` ,
`duration` ,
`id_frequency` ,
`create_user` ,
`create_time` ,
`modify_user` ,
`modify_time`
)
VALUES 
( NULL ,  '50',  'Particular',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '51',  'Particular Tecnif.',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '52',  'Clase Partido 3-1',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '53',  'Clase Partido 2-2',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '54',  'Adultos 4',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '55',  'Tecnificacion 4',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '56',  'Tecnificacion 3',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL ),
( NULL ,  '57',  'Infantil',  '5',  '1',  '1',  '1',  '1',  '1',  '2011-12-01',  '2012-12-31',  '0.00',  '1',  '5',  '0', NULL , NULL , NULL );


-- Aumento el tamaño de la descripción de un pago
ALTER TABLE  `payments` CHANGE  `description`  `description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NULL DEFAULT NULL


-- Creo genero mixto
INSERT INTO  `zz_gender` (
`id` ,
`description` ,
`create_user` ,
`create_time` ,
`modify_user` ,
`modify_time`
)
VALUES (
'3',  'Mixto',  '0', NULL , NULL , NULL
);


-- Creo tarifa de coste cero
INSERT INTO  `prices` (
`pk` ,
`id` ,
`description` ,
`type` ,
`active` ,
`include_holiday` ,
`by_group` ,
`by_weekday` ,
`by_time` ,
`start_date` ,
`end_date` ,
`quantity` ,
`duration` ,
`id_frequency` ,
`create_user` ,
`create_time` ,
`modify_user` ,
`modify_time`
)
VALUES (
NULL ,  '58',  'Precio único',  '5',  '1',  '1',  '0',  '0',  '0',  '2011-01-01',  '2099-12-31',  '0.00',  '1',  '1',  '0', NULL , NULL , NULL
);




-- r764
-- Creación del tipo de pago asociado a ranking
INSERT INTO  `zz_payment_type` (
`id` ,
`description` ,
`active` ,
`create_user` ,
`create_time` ,
`modify_user` ,
`modify_time`
)
VALUES (
'7',  'Ranking',  '1', NULL , NULL , NULL , NULL
);



--r798
-- Creación de la tabla de dias de precios especiales para pistas
CREATE  TABLE IF NOT EXISTS `prices_specials` (
  `id` INT NOT NULL ,
  `type` INT NOT NULL COMMENT '1: periodicidad anual ; 2: fecha concreta' ,
  `date` DATE NOT NULL ,
  `status` CHAR(1) NOT NULL DEFAULT '0' ,
  `id_price` INT NOT NULL ,
  `id_court` INT NOT NULL DEFAULT '0' COMMENT 'Permite especificar a qué pista afecta. \'0\' para \'todas\'' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Dias especiales del calendario'

ALTER TABLE  `prices_specials` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT



---

ALTER TABLE  `booking_players` ADD  `user_desc` VARCHAR( 75 ) NULL AFTER  `id_user` ,
ADD  `user_phone` VARCHAR( 45 ) NULL AFTER  `user_desc`

----

-- Creacion de campo para definir la tarifa de luz que se usará en cada pista
ALTER TABLE  `courts` ADD  `light_price` INT NOT NULL DEFAULT  '0' AFTER  `id_price`
--


-- Creacion de campos en la tabla de jugadores asociados al almacenaje de resultados de partidos comunes y retos y la variación de puntuación personal de cada uno por este evento.
ALTER TABLE  `booking_players` ADD  `win_game` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `status` ,
ADD  `player_level_variation` DECIMAL( 3, 2 ) NOT NULL DEFAULT  '0' AFTER  `win_game`
--
ALTER TABLE  `booking_shared` ADD  `winner_recorded` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `notified`
--


-- pongo el campo que permite determinar que pistas son visibles para el publio normal y cuales no.. 
ALTER TABLE  `courts` ADD  `visible` INT( 1 ) NOT NULL DEFAULT  '1' AFTER  `time_table_default` ;

-- Creacion de campo para visualizar las pistas de forma ordenada
ALTER TABLE  `courts` ADD  `view_order` INT NOT NULL DEFAULT  '0' AFTER  `time_table_default`

-- Cambio en tabla de tiempos para que en cada uno se especifique la duración del intervalo
ALTER TABLE  `time_tables` ADD  `interval` INT NOT NULL DEFAULT  '30' AFTER  `everyday`

-- Cambio en tabla de usuarios para añadir notas del usuario
ALTER TABLE  `meta` ADD  `notas` VARCHAR( 1024 ) NULL AFTER  `avatar`

-- Campo nuevo para especificar el orden de visualizacion de niveles de clases
ALTER TABLE  `zz_lessons_levels` ADD  `view_order` INT( 11 ) NOT NULL DEFAULT  '0' AFTER  `description` ;

-- Cambio en tabla de frecuencias de pago para añadir el campo que indica de qué periodo de tiempo hablamos en terminos de PHP
ALTER TABLE  `zz_payment_frequency` ADD  `php_interval_name` VARCHAR( 35 ) NOT NULL AFTER  `description`
UPDATE  `zz_payment_frequency` SET  `php_interval_name` =  'month' WHERE  `zz_payment_frequency`.`id` =5;
UPDATE  `zz_payment_frequency` SET  `php_interval_name` =  'year' WHERE  `zz_payment_frequency`.`id` =9;

-- meto los campos necesarios para el IBAN y BIC
ALTER TABLE  `meta` ADD  `bank_bic` VARCHAR( 11 ) NULL AFTER  `prepaid_cash` ,
ADD  `bank_iban` VARCHAR( 4 ) NULL AFTER  `bank_bic`
ALTER TABLE  `meta` CHANGE  `bank_iban`  `bank_iban` VARCHAR( 34 ) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NULL DEFAULT NULL


-- Campo nuevo para especificar el motivo de baja de un alumno en las clases
ALTER TABLE  `lessons_assistants` ADD  `unsubscription_reason` INT( 2 ) NOT NULL DEFAULT  '0' AFTER  `unsubscription_date` ;

-- Creación de la tabla de motivos de baja
CREATE  TABLE IF NOT EXISTS `zz_lessons_unbubscription_reasons` (
  `id` INT NOT NULL ,
  `description` VARCHAR(30) NOT NULL DEFAULT '' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Motivos de baja de alumno';

ALTER TABLE  `zz_lessons_unbubscription_reasons` CHANGE  `id`  `id` INT( 11 ) NOT NULL ;

INSERT INTO  `zz_lessons_unbubscription_reasons` (
`id` , `description` , `create_user` , `create_time` , `modify_user` , `modify_time`)
VALUES ('0',  'Desconocido',   0 , NULL , NULL , NULL),
('1',  'Precio',   0 , NULL , NULL , NULL),
('2',  'Traslado',   0 , NULL , NULL , NULL),
('3',  'Error en alta',   0 , NULL , NULL , NULL)
;