<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Datos del Club
|--------------------------------------------------------------------------
|
| Diferentes datos del club en cuesti�n que pueden ser interesantes
|
|
*/

$config['app_name']	= 'Reserva Deportiva';
$config['app_title']	= 'ReservaDeportiva.com - La forma de reservar donde jugar, sin moverte de casa';
$config['club_name']	= 'Club de padel Inventado';
$config['club_url']	= 'http://demo.reservasdeportivas.com';
$config['club_address']	= 'Direcci�n del club, 7';
$config['club_population']	= 'Navalzarcillos';




/*
|--------------------------------------------------------------------------
| Formas de pago
|--------------------------------------------------------------------------
|
| Habilita las diferentes formas de pago posibles
|
|
*/

$config['enable_reserve']	= FALSE;
$config['enable_cash']	= TRUE;
$config['enable_prepaid']	= TRUE;
$config['enable_creditcard']	= TRUE;
$config['enable_paypal']	= FALSE;
$config['enable_bank']	= FALSE;
$config['enable_tpv']	= TRUE;


/*
|--------------------------------------------------------------------------
| Busquedas de pista
|--------------------------------------------------------------------------
|
| Parametrizaci�n de diferentes aspectos de la b�squeda de pistas libres
|
|
*/

# Habilita el acceso al sistema al usuario an�nimo (para b�squedas, reservas, etc..)
$config['anonymous_enabled']	= TRUE;

$config['sport_required']	= FALSE;
$config['courtype_required']	= FALSE;
$config['court_required_9']	= TRUE;
$config['court_required_7']	= FALSE;
$config['court_required_3']	= FALSE;
$config['court_required_1']	= FALSE;
$config['court_required']	= array(9 => TRUE, 7 => FALSE, 5 => FALSE, 3 => FALSE, 1 => FALSE);
$config['date_required']	= array(9 => TRUE, 7 => TRUE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
$config['max_search_days']	= array(9 => 2, 7 => 3, 5 => 3, 3 => 90, 1 => 365);

# Especifica los permisos de cada nivel de usuario para tener acceso a la opci�n de reservar sin coste
$config['no_cost_permission']	= array(9 => FALSE, 7 => FALSE, 5 => FALSE, 3 => FALSE, 1 => TRUE);

/*
|--------------------------------------------------------------------------
| Formatos tipo de la aplicaci�n
|--------------------------------------------------------------------------
|
| Parametrizaci�n de diferentes formatos muy usados en la aplicaci�n
|
|
*/
$config['reserve_date_filter_format']	= 'd-m-Y';	// Formato de fecha para los filtros y POST
$config['reserve_hour_filter_format']	= 'H:i';	// Formato de hora para los filtros y POST
$config['date_db_format']	= 'Y-m-d';	// Formato de fecha para la base de datos
$config['hour_db_format']	= 'H:i';	// Formato de hora para la base de datos

$config['half_hour_simbol']	= '&nbsp;&nbsp;-&nbsp;&nbsp;';
$config['reserve_interval']	= 30; //minutos de cada intervalo de tiempo para las reservas
$config['cancelled_reserve_refund']	= true; //Si es true se devuelve el dinero de la reserva a la cuenta prepago, si es false no se devuelve dinero




/*
|--------------------------------------------------------------------------
| configuraciones varias
|--------------------------------------------------------------------------
|
| Parametrizaci�n 
|
|
*/

$config['public_register_user']	= TRUE;
$config['public_login_user']	= TRUE;


$config['reserve_send_mail']	= TRUE;
$config['reserve_send_sms']	= FALSE;

$config['reserve_admin_notification_cc']	= TRUE;
$config['reserve_admin_notification_mail']	= 'juanjo.nieto@gmail.com';

/* End of file reservas.php */
/* Location: ./system/application/config/reservas.php */