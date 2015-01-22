-- phpMyAdmin SQL Dump
-- version 4.3.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 22, 2015 at 01:12 PM
-- Server version: 5.6.21-log
-- PHP Version: 5.6.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `torrijos`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL,
  `id_sport` int(11) NOT NULL COMMENT 'Deporte',
  `id_court` int(11) NOT NULL COMMENT 'Pista en la que se desarrolla el curso.',
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `id_manager` int(11) NOT NULL COMMENT 'ID del usuario que lo gestiona, que normalmente será el profesor',
  `capacity` int(11) NOT NULL DEFAULT '0',
  `used_vacancies` int(11) NOT NULL DEFAULT '0',
  `weekday` char(1) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `start_time` time DEFAULT NULL COMMENT 'Hora de inicio\n',
  `end_time` time DEFAULT NULL,
  `employee_discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `member_discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `user_discount` decimal(4,2) NOT NULL DEFAULT '0.00',
  `seasson_cost` decimal(5,2) DEFAULT NULL COMMENT 'Coste por curso completo',
  `month_cost` decimal(5,2) DEFAULT NULL COMMENT 'Coste mensual',
  `individual_cost` decimal(5,2) DEFAULT NULL COMMENT 'Coste de una única clase',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Definicion de tarifas';

-- --------------------------------------------------------

--
-- Table structure for table `activities_schedule_exceptions`
--

CREATE TABLE IF NOT EXISTS `activities_schedule_exceptions` (
  `id` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1: periodicidad anual ; 2: fecha concreta',
  `date` date NOT NULL,
  `status` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Dias especiales del calendario';

-- --------------------------------------------------------

--
-- Table structure for table `activities_subscriptions`
--

CREATE TABLE IF NOT EXISTS `activities_subscriptions` (
  `id` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `user_desc` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT 'ID del usuario que lo gestiona, que normalmente será el profesor',
  `user_phone` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `subscription_type` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '3' COMMENT 'Tipo de suscripcion:\n1 - anual\n2 - mensual\n3 - único día\n',
  `initial_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `last_payd_date` date DEFAULT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL,
  `id_booking` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `id_transaction` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `booking_code` char(6) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `session` varchar(35) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_court` int(11) NOT NULL,
  `date` date NOT NULL,
  `intervalo` time NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0:Libre ; 5:prereservado; 7: pendiente pago; 9:pagado',
  `id_paymentway` int(11) NOT NULL DEFAULT '0' COMMENT '1:Contado, 2:tarjeta, 3:paypal, 4:prepago, 5:banco..',
  `price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `price_court` decimal(5,2) DEFAULT NULL,
  `price_light` decimal(5,2) DEFAULT NULL,
  `price_supl1` decimal(5,2) DEFAULT NULL,
  `price_supl2` decimal(5,2) DEFAULT NULL,
  `price_supl3` decimal(5,3) DEFAULT NULL,
  `price_supl4` decimal(5,3) DEFAULT NULL,
  `price_supl5` decimal(5,3) DEFAULT NULL,
  `no_cost` int(11) NOT NULL DEFAULT '0',
  `no_cost_desc` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_nif` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_desc` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_phone` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `shared` int(11) DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_ip` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=163 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de almacenaje de reservas';

-- --------------------------------------------------------

--
-- Table structure for table `booking_cancelled`
--

CREATE TABLE IF NOT EXISTS `booking_cancelled` (
  `id` int(11) NOT NULL,
  `id_booking` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `id_transaction` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `booking_code` char(6) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `session` varchar(35) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_court` int(11) NOT NULL,
  `date` date NOT NULL,
  `intervalo` time NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0:Libre ; 5:prereservado; 7: pendiente pago; 9:pagado',
  `cancelation_reason` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_paymentway` int(11) NOT NULL DEFAULT '0' COMMENT '1:Contado, 2:tarjeta, 3:paypal, 4:prepago, 5:banco..',
  `price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `no_cost` int(11) DEFAULT '0',
  `no_cost_desc` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_nif` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_desc` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_phone` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=21402 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_players`
--

CREATE TABLE IF NOT EXISTS `booking_players` (
  `id` int(11) NOT NULL,
  `id_transaction` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `user_desc` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_phone` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `win_game` tinyint(1) NOT NULL DEFAULT '0',
  `player_level_variation` decimal(3,2) NOT NULL DEFAULT '0.00',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Jugadores para un partido (quizá compartido)';

-- --------------------------------------------------------

--
-- Table structure for table `booking_shared`
--

CREATE TABLE IF NOT EXISTS `booking_shared` (
  `id` int(11) NOT NULL,
  `id_transaction` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `players` int(11) DEFAULT '0',
  `price_by_player` decimal(5,2) DEFAULT '0.00',
  `gender` int(11) DEFAULT NULL,
  `low_player_level` decimal(2,1) DEFAULT '1.0',
  `high_player_level` decimal(2,1) DEFAULT '6.0',
  `limit_date` date DEFAULT NULL,
  `visible` int(11) DEFAULT NULL,
  `last_notify` int(11) DEFAULT '1' COMMENT 'Dias de antelación con los que se envía el ''ultimo aviso'' de disponibilidad.',
  `notified` int(11) DEFAULT '0',
  `winner_recorded` tinyint(1) NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2493 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Informacion de los retos';

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext COLLATE utf8_spanish2_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE IF NOT EXISTS `courts` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `court_type` int(11) NOT NULL,
  `id_price` int(11) DEFAULT NULL COMMENT 'Tarifa para la pista',
  `light_price` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `time_table_default` int(11) DEFAULT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `view_order` int(11) NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de registro de las pistas dadas de alta en el sistema';

-- --------------------------------------------------------

--
-- Table structure for table `courts_types`
--

CREATE TABLE IF NOT EXISTS `courts_types` (
  `id` int(11) NOT NULL,
  `id_sport` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tipos de pista';

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL,
  `fixed` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `resume` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `content` text COLLATE utf8_spanish2_ci,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de eventos y noticias';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` tinyint(3) unsigned NOT NULL,
  `name` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jqcalendar`
--

CREATE TABLE IF NOT EXISTS `jqcalendar` (
  `Id` int(11) NOT NULL,
  `Subject` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `Location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `IsAllDayEvent` smallint(6) NOT NULL,
  `Color` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `RecurringRule` varchar(500) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int(11) NOT NULL,
  `description` varchar(75) COLLATE utf8_spanish2_ci NOT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `weekday` int(11) NOT NULL,
  `start_time` time NOT NULL COMMENT 'Hora de inicio de las clases',
  `end_time` time NOT NULL COMMENT 'Hora en que acaban las clases',
  `start_date` date DEFAULT NULL COMMENT 'Fecha de inicio del curso',
  `end_date` date DEFAULT NULL COMMENT 'Fecha de fin de curso',
  `id_sport` int(11) DEFAULT NULL,
  `id_instructor` int(11) DEFAULT NULL COMMENT 'Profesor predeterminado del curso',
  `id_court` int(11) DEFAULT NULL,
  `max_vacancies` int(11) NOT NULL DEFAULT '0',
  `current_vacancies` int(11) DEFAULT NULL,
  `monthly_payment_day` int(11) DEFAULT '1' COMMENT 'Dia en que se generan los pagos del periodo siguiente',
  `level` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `L` int(11) DEFAULT '0',
  `M` int(11) DEFAULT '0',
  `X` int(11) DEFAULT '0',
  `J` int(11) DEFAULT '0',
  `V` int(11) DEFAULT '0',
  `S` int(11) DEFAULT '0',
  `D` int(11) DEFAULT '0',
  `signin` decimal(5,2) DEFAULT '0.00',
  `price` int(11) DEFAULT NULL,
  `create_user` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=415 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Cabecera de cursos creados';

-- --------------------------------------------------------

--
-- Table structure for table `lessons_assistance`
--

CREATE TABLE IF NOT EXISTS `lessons_assistance` (
  `id` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `date_lesson` date NOT NULL,
  `id_instructor` int(11) NOT NULL,
  `done` int(11) NOT NULL DEFAULT '0',
  `observations` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `admin_check` int(11) NOT NULL DEFAULT '0',
  `admin_obs` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `recovered` int(11) DEFAULT '0',
  `recovered_date` date DEFAULT NULL,
  `recovered_obs` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3020 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Informe diario de las clases impartidas';

-- --------------------------------------------------------

--
-- Table structure for table `lessons_assistants`
--

CREATE TABLE IF NOT EXISTS `lessons_assistants` (
  `id` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `user_desc` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_phone` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1.- anual\n2.- suscrito\n3.- puntual\n7.- lista espera\n9.- borrado',
  `discount` decimal(5,2) DEFAULT '0.00',
  `discount_type` char(1) COLLATE utf8_spanish2_ci DEFAULT '%',
  `sign_date` date DEFAULT NULL,
  `unsubscription_date` date DEFAULT NULL,
  `unsubscription_reason` int(2) NOT NULL DEFAULT '0',
  `last_payd_date` date DEFAULT NULL,
  `last_day_payed` date DEFAULT NULL,
  `custom_discount` decimal(5,2) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5005 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Alumnos apuntados a cursos';

-- --------------------------------------------------------

--
-- Table structure for table `lessons_prices`
--

CREATE TABLE IF NOT EXISTS `lessons_prices` (
  `id` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `id_group` int(11) NOT NULL COMMENT '''0'' si es precio único para todos los niveles',
  `entire` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT 'Precio por servicio completo',
  `monthly` decimal(5,2) DEFAULT '0.00' COMMENT 'Precio de cuota mensual',
  `unique` decimal(5,2) DEFAULT '0.00' COMMENT 'Precio de un día concreto',
  `signin` decimal(5,2) DEFAULT '0.00' COMMENT 'Cuota de alta',
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Precios de las clases, según niveles';

-- --------------------------------------------------------

--
-- Table structure for table `lessons_reports`
--

CREATE TABLE IF NOT EXISTS `lessons_reports` (
  `id` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `date_lesson` date DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `user_desc` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_phone` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `asistance` int(11) NOT NULL DEFAULT '0',
  `observations` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Informe de asistencia de los alumnos';

-- --------------------------------------------------------

--
-- Table structure for table `meta`
--

CREATE TABLE IF NOT EXISTS `meta` (
  `id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `first_name` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `player_level` decimal(2,1) DEFAULT '1.0',
  `address` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `population` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT '0',
  `code_population` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `code_province` int(11) DEFAULT NULL,
  `code_country` int(11) DEFAULT NULL,
  `cp` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nif` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `mobile_phone` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `prepaid_cash` decimal(7,2) DEFAULT '0.00',
  `bank_bic` varchar(11) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank_iban` varchar(34) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank` char(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank_office` char(4) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank_dc` char(2) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank_account` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank_titular` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `bank_charge` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `language` int(11) NOT NULL DEFAULT '0',
  `validation` varchar(32) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT 'Columna en que guardaremos el MD5 de time() de creacion para las comprobaciones de los mails de validacion',
  `allow_mail_notification` char(1) COLLATE utf8_spanish2_ci DEFAULT '1',
  `allow_phone_notification` char(1) COLLATE utf8_spanish2_ci DEFAULT '1',
  `reto_lunes` int(11) DEFAULT '1',
  `reto_martes` int(11) DEFAULT '1',
  `reto_miercoles` int(11) DEFAULT '1',
  `reto_jueves` int(11) DEFAULT '1',
  `reto_viernes` int(11) DEFAULT '1',
  `reto_sabado` int(11) DEFAULT '1',
  `reto_domingo` int(11) DEFAULT '1',
  `reto_manana` int(11) DEFAULT '1',
  `reto_tarde` int(11) DEFAULT '1',
  `reto_finde` int(11) DEFAULT '1',
  `reto_notifica` int(11) DEFAULT '1',
  `code_price` int(11) DEFAULT NULL,
  `alt_code` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `last_payd_date` date DEFAULT NULL,
  `numero_socio` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `avatar` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `notas` varchar(1024) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `create_time` datetime NOT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5925 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(25) COLLATE utf8_spanish2_ci NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `order` int(11) DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `from` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `type` int(11) NOT NULL,
  `destination_type` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL COMMENT 'Tarifa para la pista',
  `destination` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `content` text COLLATE utf8_spanish2_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `status_desc` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `start_process` datetime DEFAULT NULL,
  `end_process` datetime DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7155 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de registro de notificaciones del sistema';

-- --------------------------------------------------------

--
-- Table structure for table `notification_mails`
--

CREATE TABLE IF NOT EXISTS `notification_mails` (
  `id` int(11) NOT NULL,
  `id_notification` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `from` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `type` int(11) NOT NULL,
  `destination_type` int(11) NOT NULL,
  `destination_text` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `destination_id` int(11) DEFAULT '0',
  `content` text COLLATE utf8_spanish2_ci,
  `active` int(11) NOT NULL DEFAULT '0',
  `send` int(11) NOT NULL DEFAULT '0',
  `start_process` datetime DEFAULT NULL,
  `end_process` datetime DEFAULT NULL,
  `error_count` int(11) DEFAULT '0',
  `error_desc` text COLLATE utf8_spanish2_ci,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(128) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=957118 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de detalle de los mails individuales';

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL,
  `option` varchar(25) COLLATE utf8_spanish2_ci NOT NULL,
  `value` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de opciones de la aplicacion';

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL,
  `id_type` int(11) NOT NULL COMMENT '1: reserva pista',
  `id_element` varchar(35) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT 'Id del elemento del que estamos pagando, en funcion del id_type',
  `id_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT '0',
  `id_transaction` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `desc_user` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0: pendiente; 9: realizado',
  `quantity` decimal(6,2) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `fecha_valor` date DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_paymentway` int(11) DEFAULT NULL COMMENT 'mirar en picklist zz_paymentway',
  `remesa` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ticket_number` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=47540 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Registro de los pagos realizados';

-- --------------------------------------------------------

--
-- Table structure for table `payments_scheduled`
--

CREATE TABLE IF NOT EXISTS `payments_scheduled` (
  `id` int(11) NOT NULL,
  `id_type` int(11) NOT NULL COMMENT '1: reserva pista',
  `id_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `quantity` decimal(6,2) DEFAULT NULL,
  `firstdate` datetime NOT NULL COMMENT 'Fecha de inicio de pago',
  `periodicity_type` int(11) DEFAULT NULL COMMENT '1.- por intervalo de tiempo; 2.- por día del mes; 3.- por dia de la semana\n',
  `periodicity` datetime NOT NULL COMMENT 'Cada cuantos dias se pasa el pago',
  `monthday` int(11) DEFAULT NULL COMMENT 'Dia del mes de pago',
  `weekday` int(11) DEFAULT NULL,
  `lastdate` datetime NOT NULL COMMENT 'Ultimo día en que se generó el pago',
  `description` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_paymentway` int(11) DEFAULT NULL COMMENT 'mirar en picklist zz_paymentway',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Registro de los pagos periódicos programados';

-- --------------------------------------------------------

--
-- Table structure for table `payments_tpv_extra`
--

CREATE TABLE IF NOT EXISTS `payments_tpv_extra` (
  `id_payment` int(11) NOT NULL,
  `transaction_num` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `payment_datetime` datetime DEFAULT NULL,
  `secure_payment` char(1) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  `commerce_id` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `terminal` int(11) DEFAULT NULL,
  `control` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `response` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `authorisation_code` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Registro de datos extra de pagos por internet';

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `pk` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `type` int(11) DEFAULT NULL COMMENT 'Tipo de tarifa (pista, bono, cuota, etc)',
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `include_holiday` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `by_group` int(11) DEFAULT '0',
  `by_weekday` int(11) DEFAULT '0',
  `by_time` int(11) DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quantity` decimal(5,2) NOT NULL DEFAULT '0.00',
  `duration` int(1) DEFAULT NULL,
  `id_frequency` int(11) DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Definicion de tarifas';

-- --------------------------------------------------------

--
-- Table structure for table `prices_by_group`
--

CREATE TABLE IF NOT EXISTS `prices_by_group` (
  `id` int(11) NOT NULL,
  `id_price` int(11) NOT NULL,
  `id_group` int(11) NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `quantity` decimal(5,2) NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=290 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Detalle de las tarifas cuando solo dependen del usuario';

-- --------------------------------------------------------

--
-- Table structure for table `prices_by_time`
--

CREATE TABLE IF NOT EXISTS `prices_by_time` (
  `id` int(11) NOT NULL,
  `id_price` int(11) NOT NULL,
  `id_group` int(11) NOT NULL DEFAULT '0',
  `start_date` date NOT NULL DEFAULT '2009-01-01',
  `end_date` date NOT NULL DEFAULT '2099-01-01',
  `weekday` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `time` time NOT NULL,
  `quantity` decimal(5,2) NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=30734 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Detalle de las tarifas por dia y hora';

-- --------------------------------------------------------

--
-- Table structure for table `prices_specials`
--

CREATE TABLE IF NOT EXISTS `prices_specials` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1: periodicidad anual ; 2: fecha concreta',
  `date` date NOT NULL,
  `status` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `id_price` int(11) NOT NULL,
  `id_court` int(11) NOT NULL DEFAULT '0' COMMENT 'Permite especificar a qué pista afecta. ''0'' para ''todas''',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Dias especiales del calendario';

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` varchar(32) NOT NULL,
  `iva` double NOT NULL,
  `image` varchar(128) NOT NULL,
  `stock` int(11) NOT NULL,
  `create_user` varchar(80) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(75) DEFAULT NULL,
  `modify_user` varchar(80) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(75) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE IF NOT EXISTS `ranking` (
  `id` int(11) NOT NULL,
  `description` varchar(75) COLLATE utf8_spanish2_ci NOT NULL,
  `active` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL COMMENT 'Genero',
  `groups` int(11) DEFAULT NULL COMMENT 'Grupos en que se divide el ranking',
  `teams` int(11) DEFAULT NULL COMMENT 'Equipos por grupo',
  `team_mates` int(11) DEFAULT NULL COMMENT 'Personas por equipo',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `rounds` int(11) DEFAULT NULL COMMENT '(opcional con round_duration) numero de jornadas',
  `current_round` int(11) DEFAULT NULL,
  `round_duration` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT '(opcional con rounds) Duracion de cada jornada',
  `score_parts` int(11) DEFAULT '1' COMMENT 'Numero de sets, tiempos, etc..',
  `match_duration` int(11) DEFAULT NULL COMMENT 'Duracion (en intervalos) del partido, para reservas',
  `promotion_type` int(11) DEFAULT NULL COMMENT 'Tipo de promocion de equipos al acabar jornada',
  `sport` int(11) DEFAULT NULL COMMENT 'Deporte del ranking',
  `price` int(11) DEFAULT NULL COMMENT 'Tarifa a aplicar',
  `payment_freq` int(11) DEFAULT NULL,
  `signin` decimal(7,2) DEFAULT '0.00',
  `started` int(11) DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de almacenaje de rankings';

-- --------------------------------------------------------

--
-- Table structure for table `ranking_matchs`
--

CREATE TABLE IF NOT EXISTS `ranking_matchs` (
  `id` int(11) NOT NULL,
  `id_ranking` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `team1` int(11) NOT NULL COMMENT 'Usuario principal del equipo',
  `team2` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1.- suscrito\n7.- lista espera\n9.- borrado',
  `estimated_date` date DEFAULT NULL,
  `played_date` date DEFAULT NULL,
  `winner` int(11) DEFAULT NULL,
  `last_day_payed` date DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ranking_matchs_result`
--

CREATE TABLE IF NOT EXISTS `ranking_matchs_result` (
  `id` int(11) NOT NULL,
  `id_match` int(11) DEFAULT NULL,
  `score_part` int(11) DEFAULT NULL,
  `team1_score` int(11) NOT NULL,
  `team2_score` int(11) DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Resultados de partidos de un ranking';

-- --------------------------------------------------------

--
-- Table structure for table `ranking_rounds`
--

CREATE TABLE IF NOT EXISTS `ranking_rounds` (
  `id` int(11) NOT NULL,
  `id_ranking` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `started` int(11) DEFAULT '0',
  `finished` int(11) DEFAULT '0',
  `status` int(11) DEFAULT NULL COMMENT '1.- suscrito\n7.- lista espera\n9.- borrado',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=257 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Jornadas de un ranking';

-- --------------------------------------------------------

--
-- Table structure for table `ranking_rounds_scoring`
--

CREATE TABLE IF NOT EXISTS `ranking_rounds_scoring` (
  `id` int(11) NOT NULL,
  `id_team` int(11) DEFAULT NULL,
  `id_ranking` int(11) NOT NULL,
  `round` int(11) DEFAULT NULL,
  `group` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `PJ` int(11) DEFAULT NULL,
  `PG` int(11) DEFAULT NULL,
  `PE` int(11) DEFAULT NULL,
  `PP` int(11) DEFAULT NULL,
  `puntos` decimal(5,2) DEFAULT NULL,
  `SG` int(11) DEFAULT NULL,
  `SP` int(11) DEFAULT NULL,
  `SE` int(11) DEFAULT NULL,
  `JG` int(11) DEFAULT NULL,
  `JE` int(11) DEFAULT NULL,
  `JP` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Resultados de un ranking de jornadas cerradas';

-- --------------------------------------------------------

--
-- Table structure for table `ranking_teams`
--

CREATE TABLE IF NOT EXISTS `ranking_teams` (
  `id` int(11) NOT NULL,
  `id_ranking` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `main_user` int(11) NOT NULL COMMENT 'Usuario principal del equipo',
  `description` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1.- suscrito\n7.- lista espera\n9.- borrado',
  `sign_date` date DEFAULT NULL,
  `unsubscription_date` date DEFAULT NULL,
  `last_payd_date` date DEFAULT NULL,
  `last_day_payed` date DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Equipos apuntados a un ranking';

-- --------------------------------------------------------

--
-- Table structure for table `ranking_teams_members`
--

CREATE TABLE IF NOT EXISTS `ranking_teams_members` (
  `id` int(11) NOT NULL,
  `id_team` int(11) NOT NULL,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(75) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `user_phone` varchar(35) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `main` int(11) DEFAULT '0',
  `status` int(11) DEFAULT NULL COMMENT '1.- suscrito\n7.- lista espera\n9.- borrado',
  `sign_date` date DEFAULT NULL,
  `unsubscription_date` date DEFAULT NULL,
  `last_payd_date` date DEFAULT NULL,
  `last_day_payed` date DEFAULT NULL,
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_ip` varchar(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Miembros de un equipo';

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Datos de los informes';

-- --------------------------------------------------------

--
-- Table structure for table `report_gropus`
--

CREATE TABLE IF NOT EXISTS `report_gropus` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Agrupaciones (a nivel conceptual y visual) de los informes';

-- --------------------------------------------------------

--
-- Table structure for table `time_tables`
--

CREATE TABLE IF NOT EXISTS `time_tables` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `everyday` int(11) NOT NULL DEFAULT '0' COMMENT 'Indica si todos los dias son iguales',
  `interval` int(11) NOT NULL DEFAULT '30',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes horarios que podemos usar en la aplicación';

-- --------------------------------------------------------

--
-- Table structure for table `time_tables_detail`
--

CREATE TABLE IF NOT EXISTS `time_tables_detail` (
  `id` int(11) NOT NULL,
  `id_time_table` int(11) NOT NULL,
  `weekday` char(1) COLLATE utf8_spanish2_ci NOT NULL,
  `interval` time NOT NULL,
  `status` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=753 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Detalles de cada calendario';

-- --------------------------------------------------------

--
-- Table structure for table `time_tables_specials`
--

CREATE TABLE IF NOT EXISTS `time_tables_specials` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1: periodicidad anual ; 2: fecha concreta',
  `date` date NOT NULL,
  `status` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `time_table` int(11) NOT NULL,
  `id_court` int(11) NOT NULL DEFAULT '0' COMMENT 'Permite especificar a qué pista afecta. ''0'' para ''todas''',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Dias especiales del calendario';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned DEFAULT NULL,
  `email` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Tabla de usuarios',
  `active` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `deleted` datetime DEFAULT NULL,
  `code` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `create_time` datetime NOT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `ip_address` char(16) COLLATE utf8_spanish2_ci NOT NULL,
  `activation_code` varchar(40) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `forgotten_password_code` varchar(40) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=5906 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de usuarios';

-- --------------------------------------------------------

--
-- Table structure for table `users_modules`
--

CREATE TABLE IF NOT EXISTS `users_modules` (
  `id` int(11) NOT NULL,
  `id_user` mediumint(8) NOT NULL,
  `id_module` int(11) NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Opciones de cada usuario sobre cada modulo';

-- --------------------------------------------------------

--
-- Table structure for table `users_options`
--

CREATE TABLE IF NOT EXISTS `users_options` (
  `id_user` mediumint(8) NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `option` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `value` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de opciones básicas de cada usuario';

-- --------------------------------------------------------

--
-- Table structure for table `users_prepaid_movements`
--

CREATE TABLE IF NOT EXISTS `users_prepaid_movements` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `id_paymentway` int(11) NOT NULL,
  `id_transaction` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `amount` decimal(6,2) NOT NULL DEFAULT '0.00',
  `create_user` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_user` int(11) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='movimientos hechos en el saldo del monedero ';

-- --------------------------------------------------------

--
-- Table structure for table `users_reports`
--

CREATE TABLE IF NOT EXISTS `users_reports` (
  `id` int(11) NOT NULL,
  `id_user` mediumint(8) NOT NULL,
  `id_report` int(11) NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Visibilidad y permisos de informes para usuarios';

-- --------------------------------------------------------

--
-- Table structure for table `zzz_version`
--

CREATE TABLE IF NOT EXISTS `zzz_version` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Revisión de la base de datos';

-- --------------------------------------------------------

--
-- Table structure for table `zz_binary`
--

CREATE TABLE IF NOT EXISTS `zz_binary` (
  `id` int(11) NOT NULL,
  `Description` varchar(15) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tabla de si/no';

-- --------------------------------------------------------

--
-- Table structure for table `zz_booking_player_status`
--

CREATE TABLE IF NOT EXISTS `zz_booking_player_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Estados de la union de un jugador a partido compartido';

-- --------------------------------------------------------

--
-- Table structure for table `zz_booking_status`
--

CREATE TABLE IF NOT EXISTS `zz_booking_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes estados de pago de la reserva';

-- --------------------------------------------------------

--
-- Table structure for table `zz_country`
--

CREATE TABLE IF NOT EXISTS `zz_country` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `iso_2` char(2) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `iso_3` char(3) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `address_format` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Paises';

-- --------------------------------------------------------

--
-- Table structure for table `zz_gender`
--

CREATE TABLE IF NOT EXISTS `zz_gender` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Genero';

-- --------------------------------------------------------

--
-- Table structure for table `zz_language`
--

CREATE TABLE IF NOT EXISTS `zz_language` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `code` varchar(8) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Idiomas';

-- --------------------------------------------------------

--
-- Table structure for table `zz_lessons_assistants_status`
--

CREATE TABLE IF NOT EXISTS `zz_lessons_assistants_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes estados de suscripcion a un curso';

-- --------------------------------------------------------

--
-- Table structure for table `zz_lessons_levels`
--

CREATE TABLE IF NOT EXISTS `zz_lessons_levels` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `view_order` int(11) NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes estados de pago';

-- --------------------------------------------------------

--
-- Table structure for table `zz_lessons_unbubscription_reasons`
--

CREATE TABLE IF NOT EXISTS `zz_lessons_unbubscription_reasons` (
  `id` int(11) NOT NULL,
  `description` varchar(30) NOT NULL DEFAULT '',
  `create_user` varchar(80) NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Motivos de baja de alumno';

-- --------------------------------------------------------

--
-- Table structure for table `zz_notification_dest_type`
--

CREATE TABLE IF NOT EXISTS `zz_notification_dest_type` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes tipos de destinatarios de notificaciones	';

-- --------------------------------------------------------

--
-- Table structure for table `zz_notification_status`
--

CREATE TABLE IF NOT EXISTS `zz_notification_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes estados de los mails a enviar';

-- --------------------------------------------------------

--
-- Table structure for table `zz_notification_type`
--

CREATE TABLE IF NOT EXISTS `zz_notification_type` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes tipos de notificaciones';

-- --------------------------------------------------------

--
-- Table structure for table `zz_paymentway`
--

CREATE TABLE IF NOT EXISTS `zz_paymentway` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Formas de pago';

-- --------------------------------------------------------

--
-- Table structure for table `zz_payment_frequency`
--

CREATE TABLE IF NOT EXISTS `zz_payment_frequency` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `php_interval_name` varchar(35) COLLATE utf8_spanish2_ci NOT NULL,
  `active` int(11) DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zz_payment_status`
--

CREATE TABLE IF NOT EXISTS `zz_payment_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes estados de pago de la reserva';

-- --------------------------------------------------------

--
-- Table structure for table `zz_payment_type`
--

CREATE TABLE IF NOT EXISTS `zz_payment_type` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `active` char(1) COLLATE utf8_spanish2_ci DEFAULT '0',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zz_prices`
--

CREATE TABLE IF NOT EXISTS `zz_prices` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Deportes';

-- --------------------------------------------------------

--
-- Table structure for table `zz_province`
--

CREATE TABLE IF NOT EXISTS `zz_province` (
  `id` int(11) NOT NULL,
  `id_country` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `abreviatura` char(3) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Provincias';

-- --------------------------------------------------------

--
-- Table structure for table `zz_ranking_assistants_status`
--

CREATE TABLE IF NOT EXISTS `zz_ranking_assistants_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes estados de suscripcion a un ranking';

-- --------------------------------------------------------

--
-- Table structure for table `zz_ranking_matchs_status`
--

CREATE TABLE IF NOT EXISTS `zz_ranking_matchs_status` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zz_ranking_promotion_types`
--

CREATE TABLE IF NOT EXISTS `zz_ranking_promotion_types` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Diferentes tipos de promociones entre grupos';

-- --------------------------------------------------------

--
-- Table structure for table `zz_ranking_round_duration`
--

CREATE TABLE IF NOT EXISTS `zz_ranking_round_duration` (
  `id` varchar(25) COLLATE utf8_spanish2_ci NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Tipos de duraciones de cada jornada de ranking';

-- --------------------------------------------------------

--
-- Table structure for table `zz_sports`
--

CREATE TABLE IF NOT EXISTS `zz_sports` (
  `id` int(11) NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `create_user` varchar(80) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `modify_user` varchar(80) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='Deportes';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_schedule_exceptions`
--
ALTER TABLE `activities_schedule_exceptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_subscriptions`
--
ALTER TABLE `activities_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`), ADD KEY `usuario` (`id_user`), ADD KEY `pista` (`id_court`), ADD KEY `formapago` (`id_paymentway`), ADD KEY `estado` (`status`), ADD KEY `id_transaction` (`id_transaction`), ADD KEY `date` (`date`);

--
-- Indexes for table `booking_cancelled`
--
ALTER TABLE `booking_cancelled`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_players`
--
ALTER TABLE `booking_players`
  ADD PRIMARY KEY (`id`), ADD KEY `id_transaction` (`id_transaction`), ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `booking_shared`
--
ALTER TABLE `booking_shared`
  ADD PRIMARY KEY (`id`), ADD KEY `id_transaction` (`id_transaction`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`id`), ADD KEY `deporte` (`sport_type`), ADD KEY `tipo_pista` (`court_type`), ADD KEY `tarifa` (`id_price`);

--
-- Indexes for table `courts_types`
--
ALTER TABLE `courts_types`
  ADD PRIMARY KEY (`id`), ADD KEY `deporte` (`id_sport`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jqcalendar`
--
ALTER TABLE `jqcalendar`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons_assistance`
--
ALTER TABLE `lessons_assistance`
  ADD PRIMARY KEY (`id`), ADD KEY `id_lesson` (`id_lesson`);

--
-- Indexes for table `lessons_assistants`
--
ALTER TABLE `lessons_assistants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons_prices`
--
ALTER TABLE `lessons_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons_reports`
--
ALTER TABLE `lessons_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta`
--
ALTER TABLE `meta`
  ADD PRIMARY KEY (`id`), ADD KEY `usuario` (`user_id`), ADD KEY `genero` (`gender`), ADD KEY `idioma` (`language`), ADD KEY `pais` (`code_country`), ADD KEY `provincia` (`code_province`), ADD KEY `nombre` (`first_name`,`last_name`), ADD KEY `numero_socio` (`numero_socio`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_mails`
--
ALTER TABLE `notification_mails`
  ADD PRIMARY KEY (`id`), ADD KEY `id_notification` (`id_notification`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `option` (`option`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`), ADD KEY `id_transaction` (`id_transaction`), ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `payments_scheduled`
--
ALTER TABLE `payments_scheduled`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments_tpv_extra`
--
ALTER TABLE `payments_tpv_extra`
  ADD PRIMARY KEY (`id_payment`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`pk`);

--
-- Indexes for table `prices_by_group`
--
ALTER TABLE `prices_by_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prices_by_time`
--
ALTER TABLE `prices_by_time`
  ADD PRIMARY KEY (`id`), ADD KEY `id_price` (`id_price`), ADD KEY `id_price_2` (`id_price`,`id_group`), ADD KEY `weekday` (`weekday`);

--
-- Indexes for table `prices_specials`
--
ALTER TABLE `prices_specials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_matchs`
--
ALTER TABLE `ranking_matchs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_matchs_result`
--
ALTER TABLE `ranking_matchs_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_rounds`
--
ALTER TABLE `ranking_rounds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_rounds_scoring`
--
ALTER TABLE `ranking_rounds_scoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_teams`
--
ALTER TABLE `ranking_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking_teams_members`
--
ALTER TABLE `ranking_teams_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`), ADD KEY `grupo` (`id_group`);

--
-- Indexes for table `report_gropus`
--
ALTER TABLE `report_gropus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_tables`
--
ALTER TABLE `time_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_tables_detail`
--
ALTER TABLE `time_tables_detail`
  ADD PRIMARY KEY (`id`), ADD KEY `time_table` (`id_time_table`);

--
-- Indexes for table `time_tables_specials`
--
ALTER TABLE `time_tables_specials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_modules`
--
ALTER TABLE `users_modules`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `MULTIPLE` (`id_user`,`id_module`), ADD KEY `code_USER` (`id_user`), ADD KEY `USER` (`id_user`), ADD KEY `MODULE` (`id_module`);

--
-- Indexes for table `users_options`
--
ALTER TABLE `users_options`
  ADD PRIMARY KEY (`id_user`), ADD KEY `USER` (`id_user`);

--
-- Indexes for table `users_prepaid_movements`
--
ALTER TABLE `users_prepaid_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_reports`
--
ALTER TABLE `users_reports`
  ADD PRIMARY KEY (`id`), ADD KEY `usuario` (`id_user`), ADD KEY `informe` (`id_report`);

--
-- Indexes for table `zzz_version`
--
ALTER TABLE `zzz_version`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_binary`
--
ALTER TABLE `zz_binary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_booking_player_status`
--
ALTER TABLE `zz_booking_player_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_booking_status`
--
ALTER TABLE `zz_booking_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_country`
--
ALTER TABLE `zz_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_gender`
--
ALTER TABLE `zz_gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_language`
--
ALTER TABLE `zz_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_lessons_assistants_status`
--
ALTER TABLE `zz_lessons_assistants_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_lessons_levels`
--
ALTER TABLE `zz_lessons_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_lessons_unbubscription_reasons`
--
ALTER TABLE `zz_lessons_unbubscription_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_notification_dest_type`
--
ALTER TABLE `zz_notification_dest_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_notification_status`
--
ALTER TABLE `zz_notification_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_notification_type`
--
ALTER TABLE `zz_notification_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_paymentway`
--
ALTER TABLE `zz_paymentway`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_payment_frequency`
--
ALTER TABLE `zz_payment_frequency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_payment_status`
--
ALTER TABLE `zz_payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_payment_type`
--
ALTER TABLE `zz_payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_prices`
--
ALTER TABLE `zz_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_province`
--
ALTER TABLE `zz_province`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_ranking_assistants_status`
--
ALTER TABLE `zz_ranking_assistants_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_ranking_matchs_status`
--
ALTER TABLE `zz_ranking_matchs_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_ranking_promotion_types`
--
ALTER TABLE `zz_ranking_promotion_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_ranking_round_duration`
--
ALTER TABLE `zz_ranking_round_duration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zz_sports`
--
ALTER TABLE `zz_sports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activities_schedule_exceptions`
--
ALTER TABLE `activities_schedule_exceptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activities_subscriptions`
--
ALTER TABLE `activities_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT for table `booking_cancelled`
--
ALTER TABLE `booking_cancelled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21402;
--
-- AUTO_INCREMENT for table `booking_players`
--
ALTER TABLE `booking_players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT for table `booking_shared`
--
ALTER TABLE `booking_shared`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2493;
--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `jqcalendar`
--
ALTER TABLE `jqcalendar`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=415;
--
-- AUTO_INCREMENT for table `lessons_assistance`
--
ALTER TABLE `lessons_assistance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3020;
--
-- AUTO_INCREMENT for table `lessons_assistants`
--
ALTER TABLE `lessons_assistants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5005;
--
-- AUTO_INCREMENT for table `lessons_prices`
--
ALTER TABLE `lessons_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lessons_reports`
--
ALTER TABLE `lessons_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `meta`
--
ALTER TABLE `meta`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5925;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7155;
--
-- AUTO_INCREMENT for table `notification_mails`
--
ALTER TABLE `notification_mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=957118;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47540;
--
-- AUTO_INCREMENT for table `payments_scheduled`
--
ALTER TABLE `payments_scheduled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `pk` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `prices_by_group`
--
ALTER TABLE `prices_by_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=290;
--
-- AUTO_INCREMENT for table `prices_by_time`
--
ALTER TABLE `prices_by_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30734;
--
-- AUTO_INCREMENT for table `prices_specials`
--
ALTER TABLE `prices_specials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ranking_matchs`
--
ALTER TABLE `ranking_matchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ranking_matchs_result`
--
ALTER TABLE `ranking_matchs_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ranking_rounds`
--
ALTER TABLE `ranking_rounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=257;
--
-- AUTO_INCREMENT for table `ranking_rounds_scoring`
--
ALTER TABLE `ranking_rounds_scoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ranking_teams`
--
ALTER TABLE `ranking_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ranking_teams_members`
--
ALTER TABLE `ranking_teams_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `time_tables`
--
ALTER TABLE `time_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `time_tables_detail`
--
ALTER TABLE `time_tables_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=753;
--
-- AUTO_INCREMENT for table `time_tables_specials`
--
ALTER TABLE `time_tables_specials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5906;
--
-- AUTO_INCREMENT for table `users_modules`
--
ALTER TABLE `users_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users_prepaid_movements`
--
ALTER TABLE `users_prepaid_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `users_reports`
--
ALTER TABLE `users_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `zzz_version`
--
ALTER TABLE `zzz_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
