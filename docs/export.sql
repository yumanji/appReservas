SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `reservadeportiva` ;
CREATE SCHEMA IF NOT EXISTS `reservadeportiva` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci ;
USE `reservadeportiva`;

-- -----------------------------------------------------
-- Table `reservadeportiva`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`users` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`users` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `group_id` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL ,
  `email` VARCHAR(80) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `password` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL COMMENT 'Tabla de usuarios' ,
  `active` CHAR(1) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL DEFAULT '0' ,
  `deleted` DATETIME NULL DEFAULT NULL ,
  `code` VARCHAR(12) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `username` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL ,
  `create_user` VARCHAR(80) NOT NULL ,
  `create_time` DATETIME NOT NULL ,
  `modify_user` VARCHAR(80) NULL DEFAULT NULL ,
  `modify_time` DATETIME NULL DEFAULT NULL ,
  `ip_address` CHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `activation_code` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL DEFAULT '0' ,
  `forgotten_password_code` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci
COMMENT = 'Tabla de usuarios';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`modules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`modules` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`modules` (
  `id` INT NOT NULL ,
  `name` VARCHAR(25) NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '0' ,
  `order` INT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`users_options`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`users_options` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`users_options` (
  `id_user` MEDIUMINT(8) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `option` VARCHAR(45) NULL ,
  `value` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_user`) )
ENGINE = InnoDB
COMMENT = 'Tabla de opciones básicas de cada usuario';

CREATE INDEX `USER` ON `reservadeportiva`.`users_options` (`id_user` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`users_modules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`users_modules` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`users_modules` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_user` MEDIUMINT(8) NOT NULL ,
  `id_module` INT NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Opciones de cada usuario sobre cada modulo';

CREATE UNIQUE INDEX `MULTIPLE` ON `reservadeportiva`.`users_modules` (`id_user` ASC, `id_module` ASC) ;

CREATE INDEX `code_USER` ON `reservadeportiva`.`users_modules` (`id_user` ASC) ;

CREATE INDEX `USER` ON `reservadeportiva`.`users_modules` (`id_user` ASC) ;

CREATE INDEX `MODULE` ON `reservadeportiva`.`users_modules` (`id_module` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`options`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`options` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`options` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `option` VARCHAR(25) NOT NULL ,
  `value` VARCHAR(45) NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de opciones de la aplicacion';

CREATE UNIQUE INDEX `option` ON `reservadeportiva`.`options` (`option` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_sports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_sports` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_sports` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `image` VARCHAR(255) NULL ,
  `active` INT NOT NULL DEFAULT 1 ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Deportes';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`courts_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`courts_types` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`courts_types` (
  `id` INT NOT NULL ,
  `id_sport` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `active` INT NOT NULL DEFAULT 0 ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tipos de pista';

CREATE INDEX `deporte` ON `reservadeportiva`.`courts_types` (`id_sport` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`prices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`prices` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`prices` (
  `pk` INT NOT NULL AUTO_INCREMENT ,
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '0' ,
  `employee_discount` DECIMAL(4,2) NOT NULL DEFAULT 0 ,
  `member_discount` DECIMAL(4,2) NOT NULL DEFAULT 0 ,
  `user_discount` DECIMAL(4,2) NOT NULL DEFAULT 0 ,
  `include_holiday` CHAR(1) NOT NULL DEFAULT '0' ,
  `date_initial` DATETIME NULL ,
  `date_end` DATETIME NULL ,
  `light_extra` DECIMAL(4,2) NULL DEFAULT 0 ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`pk`) )
ENGINE = InnoDB
COMMENT = 'Definicion de tarifas';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`courts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`courts` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`courts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `sport_type` INT NOT NULL ,
  `court_type` INT NOT NULL ,
  `id_price` INT NULL COMMENT 'Tarifa para la pista' ,
  `created` DATETIME NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '0' ,
  `time_table_default` INT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de registro de las pistas dadas de alta en el sistema';

CREATE INDEX `deporte` ON `reservadeportiva`.`courts` (`sport_type` ASC) ;

CREATE INDEX `tipo_pista` ON `reservadeportiva`.`courts` (`court_type` ASC) ;

CREATE INDEX `tarifa` ON `reservadeportiva`.`courts` (`id_price` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`time_tables`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`time_tables` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`time_tables` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(45) NOT NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '0' ,
  `everyday` INT NOT NULL DEFAULT 0 COMMENT 'Indica si todos los dias son iguales' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes horarios que podemos usar en la aplicación';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`time_tables_specials`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`time_tables_specials` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`time_tables_specials` (
  `id` INT NOT NULL ,
  `type` INT NOT NULL COMMENT '1: periodicidad anual ; 2: fecha concreta' ,
  `date` DATE NOT NULL ,
  `status` CHAR(1) NOT NULL DEFAULT '0' ,
  `time_table` INT NOT NULL ,
  `id_court` INT NOT NULL DEFAULT '0' COMMENT 'Permite especificar a qué pista afecta. \'0\' para \'todas\'' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Dias especiales del calendario';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`time_tables_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`time_tables_detail` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`time_tables_detail` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_time_table` INT NOT NULL ,
  `weekday` CHAR(1) NOT NULL ,
  `interval` TIME NOT NULL ,
  `status` CHAR(1) NOT NULL DEFAULT '0' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Detalles de cada calendario';

CREATE INDEX `time_table` ON `reservadeportiva`.`time_tables_detail` (`id_time_table` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_gender`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_gender` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_gender` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Genero';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_binary`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_binary` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_binary` (
  `id` INT NOT NULL ,
  `Description` VARCHAR(15) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de si/no';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_language`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_language` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_language` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `code` VARCHAR(8) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Idiomas';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_country` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_country` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `iso_2` CHAR(2) NULL ,
  `iso_3` CHAR(3) NULL ,
  `address_format` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Paises';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_province`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_province` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_province` (
  `id` INT NOT NULL ,
  `id_country` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `abreviatura` CHAR(3) NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Provincias';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`prices_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`prices_detail` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`prices_detail` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_price` INT NOT NULL ,
  `weekday` CHAR(1) NOT NULL ,
  `time` TIME NOT NULL ,
  `quantity` DECIMAL(5,2) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Detalle de las diferentes tarifas';

CREATE INDEX `price` ON `reservadeportiva`.`prices_detail` (`id_price` ASC) ;

CREATE INDEX `prices` ON `reservadeportiva`.`prices_detail` (`id_price` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`report_gropus`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`report_gropus` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`report_gropus` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Agrupaciones (a nivel conceptual y visual) de los informes';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`reports` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`reports` (
  `id` INT NOT NULL ,
  `id_group` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` INT NOT NULL ,
  `create_time` DATETIME NOT NULL ,
  `modify_user` INT NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Datos de los informes';

CREATE INDEX `grupo` ON `reservadeportiva`.`reports` (`id_group` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`users_reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`users_reports` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`users_reports` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_user` MEDIUMINT(8) NOT NULL ,
  `id_report` INT NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Visibilidad y permisos de informes para usuarios';

CREATE INDEX `usuario` ON `reservadeportiva`.`users_reports` (`id_user` ASC) ;

CREATE INDEX `informe` ON `reservadeportiva`.`users_reports` (`id_report` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_paymentway`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_paymentway` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_paymentway` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `active` INT NOT NULL DEFAULT '0' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Formas de pago';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_booking_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_booking_status` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_booking_status` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes estados de pago de la reserva';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`booking`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`booking` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`booking` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_booking` VARCHAR(15) NOT NULL ,
  `id_transaction` VARCHAR(50) NULL ,
  `id_user` INT NOT NULL ,
  `booking_code` CHAR(6) NULL ,
  `session` VARCHAR(35) NULL ,
  `id_court` INT NOT NULL ,
  `date` DATE NOT NULL ,
  `intervalo` TIME NOT NULL ,
  `status` INT NOT NULL DEFAULT 0 COMMENT '0:Libre ; 5:prereservado; 7: pendiente pago; 9:pagado' ,
  `id_paymentway` INT NOT NULL DEFAULT 0 COMMENT '1:Contado, 2:tarjeta, 3:paypal, 4:prepago, 5:banco..' ,
  `price` DECIMAL(5,2) NOT NULL DEFAULT 0 ,
  `price_court` DECIMAL(5,2) NULL ,
  `price_light` DECIMAL(5,2) NULL ,
  `price_supl1` DECIMAL(5,2) NULL ,
  `price_supl2` DECIMAL(5,2) NULL ,
  `price_supl3` DECIMAL(5,3) NULL ,
  `price_supl4` DECIMAL(5,3) NULL ,
  `price_supl5` DECIMAL(5,3) NULL ,
  `no_cost` INT NOT NULL DEFAULT 0 ,
  `no_cost_desc` VARCHAR(255) NULL ,
  `user_nif` VARCHAR(25) NULL ,
  `user_desc` VARCHAR(75) NULL ,
  `user_phone` VARCHAR(45) NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(15) NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de almacenaje de reservas';

CREATE INDEX `usuario` ON `reservadeportiva`.`booking` (`id_user` ASC) ;

CREATE INDEX `pista` ON `reservadeportiva`.`booking` (`id_court` ASC) ;

CREATE INDEX `formapago` ON `reservadeportiva`.`booking` (`id_paymentway` ASC) ;

CREATE INDEX `estado` ON `reservadeportiva`.`booking` (`status` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`payments` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`payments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_type` INT NOT NULL COMMENT '1: reserva pista' ,
  `id_element` VARCHAR(35) NULL COMMENT 'Id del elemento del que estamos pagando, en funcion del id_type' ,
  `id_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `id_transaction` VARCHAR(50) NULL ,
  `desc_user` VARCHAR(75) NULL ,
  `status` INT NOT NULL DEFAULT 0 COMMENT '0: pendiente; 9: realizado' ,
  `quantity` DECIMAL(6,2) NULL ,
  `datetime` DATETIME NOT NULL ,
  `description` VARCHAR(75) NULL ,
  `id_paymentway` INT NULL COMMENT 'mirar en picklist zz_paymentway' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(15) NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Registro de los pagos realizados';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`meta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`meta` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`meta` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` MEDIUMINT(8) UNSIGNED NOT NULL ,
  `first_name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `last_name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL ,
  `player_level` DECIMAL(2,1) NULL DEFAULT 1 ,
  `address` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `population` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `province` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `gender` INT(11) NOT NULL DEFAULT '0' ,
  `code_population` VARCHAR(12) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `code_province` INT(11) NULL DEFAULT NULL ,
  `code_country` INT(11) NULL DEFAULT NULL ,
  `cp` VARCHAR(12) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `nif` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `birth_date` DATE NULL DEFAULT NULL ,
  `phone` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `mobile_phone` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `prepaid_cash` DECIMAL(7,2) NULL DEFAULT 0 ,
  `bank` CHAR(4) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `bank_office` CHAR(4) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `bank_dc` CHAR(2) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `bank_account` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `bank_titular` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `bank_charge` CHAR(1) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL DEFAULT '0' ,
  `language` INT(11) NOT NULL DEFAULT '0' ,
  `validation` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL COMMENT 'Columna en que guardaremos el MD5 de time() de creacion para las comprobaciones de los mails de validacion' ,
  `allow_mail_notification` CHAR(1) NULL DEFAULT '1' ,
  `allow_phone_notification` CHAR(1) NULL DEFAULT '1' ,
  `create_user` VARCHAR(80) NOT NULL ,
  `create_time` DATETIME NOT NULL ,
  `modify_user` VARCHAR(80) NULL DEFAULT NULL ,
  `modify_time` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE INDEX `usuario` ON `reservadeportiva`.`meta` (`user_id` ASC) ;

CREATE INDEX `genero` ON `reservadeportiva`.`meta` (`gender` ASC) ;

CREATE INDEX `idioma` ON `reservadeportiva`.`meta` (`language` ASC) ;

CREATE INDEX `pais` ON `reservadeportiva`.`meta` (`code_country` ASC) ;

CREATE INDEX `provincia` ON `reservadeportiva`.`meta` (`code_province` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`ci_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`ci_sessions` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(20) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(255) NOT NULL ,
  `last_activity` INT(12) UNSIGNED NOT NULL DEFAULT '0' ,
  `user_data` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`session_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_payment_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_payment_type` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_payment_type` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NULL ,
  `active` CHAR(1) NULL DEFAULT '0' ,
  `create_user` VARCHAR(80) NULL ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`booking_cancelled`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`booking_cancelled` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`booking_cancelled` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_booking` VARCHAR(15) NOT NULL ,
  `id_transaction` VARCHAR(50) NULL ,
  `id_user` INT NOT NULL ,
  `booking_code` CHAR(6) NULL ,
  `session` VARCHAR(35) NULL ,
  `id_court` INT NOT NULL ,
  `date` DATE NOT NULL ,
  `intervalo` TIME NOT NULL ,
  `status` INT NOT NULL DEFAULT 0 COMMENT '0:Libre ; 5:prereservado; 7: pendiente pago; 9:pagado' ,
  `cancelation_reason` VARCHAR(255) NULL ,
  `id_paymentway` INT NOT NULL DEFAULT 0 COMMENT '1:Contado, 2:tarjeta, 3:paypal, 4:prepago, 5:banco..' ,
  `price` DECIMAL(5,2) NOT NULL DEFAULT 0 ,
  `no_cost` INT NULL DEFAULT 0 ,
  `no_cost_desc` VARCHAR(255) NULL ,
  `user_nif` VARCHAR(25) NULL ,
  `user_desc` VARCHAR(75) NULL ,
  `user_phone` VARCHAR(45) NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(25) NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(25) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de almacenaje de reservas';

CREATE INDEX `usuario` ON `reservadeportiva`.`booking_cancelled` (`id_user` ASC) ;

CREATE INDEX `pista` ON `reservadeportiva`.`booking_cancelled` (`id_court` ASC) ;

CREATE INDEX `formapago` ON `reservadeportiva`.`booking_cancelled` (`id_paymentway` ASC) ;

CREATE INDEX `estado` ON `reservadeportiva`.`booking_cancelled` (`status` ASC) ;


-- -----------------------------------------------------
-- Table `reservadeportiva`.`payments_scheduled`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`payments_scheduled` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`payments_scheduled` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `id_type` INT(11) NOT NULL COMMENT '1: reserva pista' ,
  `id_user` VARCHAR(80) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL DEFAULT '0' ,
  `quantity` DECIMAL(6,2) NULL DEFAULT NULL ,
  `firstdate` DATETIME NOT NULL COMMENT 'Fecha de inicio de pago' ,
  `periodicity_type` INT NULL COMMENT '1.- por intervalo de tiempo; 2.- por día del mes; 3.- por dia de la semana\n' ,
  `periodicity` DATETIME NOT NULL COMMENT 'Cada cuantos dias se pasa el pago' ,
  `monthday` INT NULL COMMENT 'Dia del mes de pago' ,
  `weekday` INT NULL ,
  `lastdate` DATETIME NOT NULL COMMENT 'Ultimo día en que se generó el pago' ,
  `description` VARCHAR(75) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `id_paymentway` INT(11) NULL DEFAULT NULL COMMENT 'mirar en picklist zz_paymentway' ,
  `create_user` VARCHAR(80) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL DEFAULT NULL ,
  `create_ip` VARCHAR(25) NULL ,
  `modify_user` VARCHAR(80) CHARACTER SET 'utf8' COLLATE 'utf8_spanish2_ci' NULL DEFAULT NULL ,
  `modify_time` DATETIME NULL DEFAULT NULL ,
  `modify_ip` VARCHAR(25) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci
COMMENT = 'Registro de los pagos periódicos programados';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`activities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`activities` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`activities` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_sport` INT NOT NULL COMMENT 'Deporte' ,
  `id_court` INT NOT NULL COMMENT 'Pista en la que se desarrolla el curso.' ,
  `description` VARCHAR(45) NOT NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '0' ,
  `id_manager` INT NOT NULL COMMENT 'ID del usuario que lo gestiona, que normalmente será el profesor' ,
  `capacity` INT NOT NULL DEFAULT 0 ,
  `used_vacancies` INT NOT NULL DEFAULT 0 ,
  `weekday` CHAR(1) NULL ,
  `start_time` TIME NULL COMMENT 'Hora de inicio\n' ,
  `end_time` TIME NULL ,
  `employee_discount` DECIMAL(4,2) NOT NULL DEFAULT 0 ,
  `member_discount` DECIMAL(4,2) NOT NULL DEFAULT 0 ,
  `user_discount` DECIMAL(4,2) NOT NULL DEFAULT 0 ,
  `seasson_cost` DECIMAL(5,2) NULL COMMENT 'Coste por curso completo' ,
  `month_cost` DECIMAL(5,2) NULL COMMENT 'Coste mensual' ,
  `individual_cost` DECIMAL(5,2) NULL COMMENT 'Coste de una única clase' ,
  `create_user` INT NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` INT NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Definicion de tarifas';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`activities_schedule_exceptions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`activities_schedule_exceptions` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`activities_schedule_exceptions` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_activity` INT NOT NULL ,
  `type` INT NOT NULL COMMENT '1: periodicidad anual ; 2: fecha concreta' ,
  `date` DATE NOT NULL ,
  `status` CHAR(1) NOT NULL DEFAULT '0' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Dias especiales del calendario';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`activities_subscriptions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`activities_subscriptions` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`activities_subscriptions` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_activity` INT NOT NULL ,
  `id_user` INT NOT NULL DEFAULT 0 ,
  `user_desc` VARCHAR(255) NULL COMMENT 'ID del usuario que lo gestiona, que normalmente será el profesor' ,
  `user_phone` VARCHAR(255) NULL ,
  `subscription_type` CHAR(1) NOT NULL DEFAULT '3' COMMENT 'Tipo de suscripcion:\n1 - anual\n2 - mensual\n3 - único día\n' ,
  `initial_date` DATE NOT NULL ,
  `end_date` DATE NULL ,
  `last_payd_date` DATE NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '1' ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Definicion de tarifas';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`groups` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  `description` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Definiciones de los grupos a los que hacer mailings';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_payment_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_payment_status` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_payment_status` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes estados de pago';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`users_prepaid_movements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`users_prepaid_movements` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`users_prepaid_movements` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_user` INT NOT NULL ,
  `payment_type` INT NOT NULL ,
  `id_paymentway` INT NOT NULL ,
  `id_transaction` VARCHAR(45) NULL ,
  `amount` DECIMAL(6,2) NOT NULL DEFAULT 0 ,
  `create_user` INT NULL ,
  `create_time` DATETIME NULL ,
  `modify_user` INT NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'movimientos hechos en el saldo del monedero ';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`lessons`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`lessons` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`lessons` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(75) NOT NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '1' ,
  `weekday` INT NOT NULL ,
  `start_time` TIME NOT NULL COMMENT 'Hora de inicio de las clases' ,
  `end_time` TIME NOT NULL COMMENT 'Hora en que acaban las clases' ,
  `start_date` DATE NULL COMMENT 'Fecha de inicio del curso' ,
  `end_date` DATE NULL COMMENT 'Fecha de fin de curso' ,
  `id_sport` INT NULL ,
  `id_instructor` INT NULL COMMENT 'Profesor predeterminado del curso' ,
  `id_court` INT NULL ,
  `max_vacancies` INT NOT NULL DEFAULT 0 ,
  `current_vacancies` INT NULL ,
  `monthly_payment_day` INT NULL DEFAULT 28 COMMENT 'Dia en que se generan los pagos del periodo siguiente' ,
  `level` INT NULL ,
  `gender` INT NULL ,
  `create_user` INT NOT NULL ,
  `create_time` DATETIME NOT NULL ,
  `create_ip` VARCHAR(15) NOT NULL ,
  `modify_user` INT NULL ,
  `modify_time` VARCHAR(45) NULL ,
  `modify_ip` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Cabecera de cursos creados';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`lessons_assistants`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`lessons_assistants` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`lessons_assistants` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_lesson` INT NOT NULL ,
  `id_user` INT NOT NULL ,
  `user_desc` VARCHAR(75) NULL ,
  `user_phone` VARCHAR(15) NULL ,
  `status` INT NULL COMMENT '1.- anual\n2.- suscrito\n3.- puntual\n7.- lista espera\n9.- borrado' ,
  `sign_date` DATE NULL ,
  `unsubscription_date` DATE NULL ,
  `last_payd_date` DATE NULL ,
  `last_day_payed` DATE NULL ,
  `create_user` INT NULL ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(15) NULL ,
  `modify_user` INT NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Alumnos apuntados a cursos';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`lessons_prices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`lessons_prices` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`lessons_prices` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_lesson` INT NOT NULL ,
  `id_group` INT NOT NULL COMMENT '\'0\' si es precio único para todos los niveles' ,
  `entire` DECIMAL(6,2) NOT NULL DEFAULT 0 COMMENT 'Precio por servicio completo' ,
  `monthly` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Precio de cuota mensual' ,
  `unique` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Precio de un día concreto' ,
  `signin` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Cuota de alta' ,
  `create_user` INT NULL ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(15) NULL ,
  `modify_user` INT NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Precios de las clases, según niveles';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_lessons_levels`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_lessons_levels` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_lessons_levels` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes estados de pago';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_lessons_assistants_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_lessons_assistants_status` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_lessons_assistants_status` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes estados de suscripcion a un curso';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`payments_tpv_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`payments_tpv_extra` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`payments_tpv_extra` (
  `id_payment` INT NOT NULL ,
  `transaction_num` VARCHAR(45) NULL ,
  `payment_datetime` DATETIME NULL ,
  `secure_payment` CHAR(1) NULL ,
  `amount` DECIMAL(11,2) NULL ,
  `commerce_id` VARCHAR(25) NULL ,
  `terminal` INT NULL ,
  `control` VARCHAR(75) NULL ,
  `response` VARCHAR(15) NULL ,
  `authorisation_code` VARCHAR(45) NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(25) NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(25) NULL ,
  PRIMARY KEY (`id_payment`) )
ENGINE = InnoDB
COMMENT = 'Registro de datos extra de pagos por internet';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`notification`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`notification` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`notification` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `id_notification` INT NOT NULL ,
  `subject` VARCHAR(255) NOT NULL ,
  `from` VARCHAR(255) NOT NULL ,
  `destination_type` INT NOT NULL ,
  `destination_id` INT NOT NULL COMMENT 'Tarifa para la pista' ,
  `destination` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL ,
  `status` INT NOT NULL DEFAULT '0' ,
  `status_desc` VARCHAR(255) NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(128) NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(128) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de registro de notificaciones del sistema';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`notification_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`notification_detail` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`notification_detail` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(255) NOT NULL ,
  `from` VARCHAR(255) NOT NULL ,
  `destination_type` INT NOT NULL ,
  `destination_id` INT NOT NULL COMMENT 'Tarifa para la pista' ,
  `content` TEXT NULL ,
  `active` CHAR(1) NOT NULL DEFAULT '0' ,
  `send` CHAR(1) NOT NULL DEFAULT '0' ,
  `start_process` DATETIME NULL ,
  `end_process` DATETIME NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `create_ip` VARCHAR(128) NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  `modify_ip` VARCHAR(128) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Tabla de detalle de los mails individuales';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_notification_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_notification_status` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_notification_status` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes estados de los mails a enviar';


-- -----------------------------------------------------
-- Table `reservadeportiva`.`zz_notification_dest_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservadeportiva`.`zz_notification_dest_type` ;

CREATE  TABLE IF NOT EXISTS `reservadeportiva`.`zz_notification_dest_type` (
  `id` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `create_user` VARCHAR(80) NOT NULL DEFAULT '0' ,
  `create_time` DATETIME NULL ,
  `modify_user` VARCHAR(80) NULL ,
  `modify_time` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Diferentes tipos de destinatarios de notificaciones	';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`modules`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `modules` (`id`, `name`, `description`, `active`, `order`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'reservas', 'Reservas', '1', 1, '0', 0, '', 0);
INSERT INTO `modules` (`id`, `name`, `description`, `active`, `order`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'usuarios', 'Usuarios', '1', 2, '0', 0, '', 0);
INSERT INTO `modules` (`id`, `name`, `description`, `active`, `order`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'tarifas', 'Tarifas', '1', 3, '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_sports`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_sports` (`id`, `description`, `image`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'padel', NULL, 1, 0, 0, 0, 0);
INSERT INTO `zz_sports` (`id`, `description`, `image`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'tenis', NULL, 1, 0, 0, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`courts_types`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `courts_types` (`id`, `id_sport`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 1, 'padelMuro', 1, 0, 0, 0, 0);
INSERT INTO `courts_types` (`id`, `id_sport`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 1, 'padelCristal', 1, 0, 0, 0, 0);
INSERT INTO `courts_types` (`id`, `id_sport`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 2, 'tenisCemento', 1, 0, 0, 0, 0);
INSERT INTO `courts_types` (`id`, `id_sport`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (4, 2, 'tenisTierra', 0, 0, 0, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`prices`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `prices` (`pk`, `id`, `description`, `active`, `employee_discount`, `member_discount`, `user_discount`, `include_holiday`, `date_initial`, `date_end`, `light_extra`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (0, 1, 'Tarifa1', '1', 0, 0, 0, '1', 2010-01-01, 0, 0.5, '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`courts`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Pista Muro 1', 1, 1, 1, 0, '1', 1, 0, 0, 0, 0);
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'Pista Muro 2', 1, 1, 1, 0, '1', 1, 0, 0, 0, 0);
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Pista Cristal 1', 1, 2, 1, 0, '1', 1, 0, 0, 0, 0);
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (4, 'Pista Tenis Central', 2, 3, 1, 0, '1', 1, 0, 0, 0, 0);
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (5, 'Pista Muro 3', 1, 1, 1, 0, '1', 1, '0', 0, '', 0);
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (6, 'Pista Muro 4', 1, 1, 1, 0, '1', 1, '0', 0, '', 0);
INSERT INTO `courts` (`id`, `name`, `sport_type`, `court_type`, `id_price`, `created`, `active`, `time_table_default`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (0, 'Pista Cristal 2', 1, 2, 1, 0, '1', 1, '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`time_tables`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `time_tables` (`id`, `description`, `active`, `everyday`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Horario Principal', '1', 0, 0, 0, 0, 0);
INSERT INTO `time_tables` (`id`, `description`, `active`, `everyday`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'Horario Festivos', '1', 1, 0, 0, 0, 0);
INSERT INTO `time_tables` (`id`, `description`, `active`, `everyday`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Horario inactivo', '0', 1, 0, 0, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`time_tables_specials`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `time_tables_specials` (`id`, `type`, `date`, `status`, `time_table`, `id_court`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 1, '2010/07/06', '1', 2, 0, 1, 0, 1, 0);
INSERT INTO `time_tables_specials` (`id`, `type`, `date`, `status`, `time_table`, `id_court`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 2, '2010/07/06', '1', 2, 0, 1, 0, 1, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_gender`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_gender` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Hombre', 1, 2010-01-01, 0, 0);
INSERT INTO `zz_gender` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'Mujer', 1, 2010-01-01, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_binary`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_binary` (`id`, `Description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (0, 'No', 1, 2010-01-01, 0, 0);
INSERT INTO `zz_binary` (`id`, `Description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Sí', 1, 2010-01-01, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_language`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_language` (`id`, `description`, `code`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Español', '1', 1, 2010-01-01, 0, 0);
INSERT INTO `zz_language` (`id`, `description`, `code`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'Ingles', '1', 1, 2010-01-01, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_paymentway`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_paymentway` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Contado', 1, 0, 0, 0, 0);
INSERT INTO `zz_paymentway` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'Tarjeta', 1, 0, 0, 0, 0);
INSERT INTO `zz_paymentway` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Paypal', 1, 0, 0, 0, 0);
INSERT INTO `zz_paymentway` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (4, 'Transferencia', 1, 0, 0, 0, 0);
INSERT INTO `zz_paymentway` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (5, 'Prepago', 1, 0, 0, 0, 0);
INSERT INTO `zz_paymentway` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (6, 'TPV', 1, '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_booking_status`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_booking_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (5, 'prerreserva', 0, 0, 0, 0);
INSERT INTO `zz_booking_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (7, 'pendiente_pago', 0, 0, 0, 0);
INSERT INTO `zz_booking_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (9, 'pagado', 0, 0, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_payment_type`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_payment_type` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Reserva Pista', '1', '', 0, '', 0);
INSERT INTO `zz_payment_type` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'Clases y Cursos', '1', '', 0, '', 0);
INSERT INTO `zz_payment_type` (`id`, `description`, `active`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Recarga Bono', '1', '', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_payment_status`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_payment_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'planificado', 0, 0, 0, 0);
INSERT INTO `zz_payment_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (5, 'procesando', 0, 0, 0, 0);
INSERT INTO `zz_payment_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (7, 'cancelado', 0, 0, 0, 0);
INSERT INTO `zz_payment_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (9, 'pagado', 0, 0, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_lessons_levels`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_lessons_levels` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Iniciacion', 0, 0, 0, 0);
INSERT INTO `zz_lessons_levels` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Intermedio', 0, 0, 0, 0);
INSERT INTO `zz_lessons_levels` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (5, 'Avanzado', 0, 0, 0, 0);
INSERT INTO `zz_lessons_levels` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (9, 'Indiferente', 0, 0, 0, 0);
INSERT INTO `zz_lessons_levels` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (8, 'Profesional', '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_lessons_assistants_status`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_lessons_assistants_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'anual', 0, 0, 0, 0);
INSERT INTO `zz_lessons_assistants_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (2, 'mensual', 0, 0, 0, 0);
INSERT INTO `zz_lessons_assistants_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'puntual', 0, 0, 0, 0);
INSERT INTO `zz_lessons_assistants_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (7, 'espera', 0, 0, 0, 0);
INSERT INTO `zz_lessons_assistants_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (9, 'borrado', '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`notification`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (1, NULL, 'Pista Muro 1', '1', 1, 1, NULL, 0, '1', NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (2, NULL, 'Pista Muro 2', '1', 1, 1, NULL, 0, '1', NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (3, NULL, 'Pista Cristal 1', '1', 2, 1, NULL, 0, '1', NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (4, NULL, 'Pista Tenis Central', '2', 3, 1, NULL, 0, '1', NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (5, NULL, 'Pista Muro 3', '1', 1, 1, NULL, 0, '1', NULL, '0', 0, NULL, '', 0, NULL);
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (6, NULL, 'Pista Muro 4', '1', 1, 1, NULL, 0, '1', NULL, '0', 0, NULL, '', 0, NULL);
INSERT INTO `notification` (`id`, `id_notification`, `subject`, `from`, `destination_type`, `destination_id`, `destination`, `content`, `status`, `status_desc`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (0, NULL, 'Pista Cristal 2', '1', 2, 1, NULL, 0, '1', NULL, '0', 0, NULL, '', 0, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`notification_detail`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (1, 'Pista Muro 1', '1', 1, 1, 0, '1', '1', NULL, NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (2, 'Pista Muro 2', '1', 1, 1, 0, '1', '1', NULL, NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (3, 'Pista Cristal 1', '1', 2, 1, 0, '1', '1', NULL, NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (4, 'Pista Tenis Central', '2', 3, 1, 0, '1', '1', NULL, NULL, 0, 0, NULL, 0, 0, NULL);
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (5, 'Pista Muro 3', '1', 1, 1, 0, '1', '1', NULL, NULL, '0', 0, NULL, '', 0, NULL);
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (6, 'Pista Muro 4', '1', 1, 1, 0, '1', '1', NULL, NULL, '0', 0, NULL, '', 0, NULL);
INSERT INTO `notification_detail` (`id`, `subject`, `from`, `destination_type`, `destination_id`, `content`, `active`, `send`, `start_process`, `end_process`, `create_user`, `create_time`, `create_ip`, `modify_user`, `modify_time`, `modify_ip`) VALUES (0, 'Pista Cristal 2', '1', 2, 1, 0, '1', '1', NULL, NULL, '0', 0, NULL, '', 0, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_notification_status`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_notification_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (0, 'Creado', 0, 0, 0, 0);
INSERT INTO `zz_notification_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Pendiente', 0, 0, 0, 0);
INSERT INTO `zz_notification_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (5, 'En proceso', 0, 0, 0, 0);
INSERT INTO `zz_notification_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (9, 'Enviado', 0, 0, 0, 0);
INSERT INTO `zz_notification_status` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (8, 'Error', '0', 0, '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `reservadeportiva`.`zz_notification_dest_type`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `reservadeportiva`;
INSERT INTO `zz_notification_dest_type` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (1, 'Todos', 0, 0, 0, 0);
INSERT INTO `zz_notification_dest_type` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (3, 'Grupos', 0, 0, 0, 0);
INSERT INTO `zz_notification_dest_type` (`id`, `description`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES (7, 'Individual', 0, 0, 0, 0);

COMMIT;
