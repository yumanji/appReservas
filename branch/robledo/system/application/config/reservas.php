<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


# Navegadores que necesitan que la reserva sea sin AJAX
$config['special_browser'] = array('Internet Explorer');


/*
|--------------------------------------------------------------------------
| Datos del Club
|--------------------------------------------------------------------------
|
| Diferentes datos del club en cuesti�n que pueden ser interesantes
|
|
*/

$config['app_name']	= 'IMJD Torrijos - Reserva Deportiva';
$config['app_provider']	= 'Reserva Deportiva';
$config['app_title']	= 'IMJD Torrijos - ReservaDeportiva.com - La forma de reservar donde jugar, sin moverte de casa';
$config['club_name']	= 'IMJD Torrijos';
$config['club_url']	= 'http://imjdtorrijos.reservadeportiva.com';
$config['club_address']	= 'Lope de Vega, 4';
$config['club_population']	= 'Torrijos';
$config['email_from_desc']	= $config['club_name'].' desde '.$config['app_provider'];
$config['email_from']	= 'noreply@reservadeportiva.com';
$config['email_replyto']	= 'noreply@reservadeportiva.com';
$config['email_reply_to']   = TRUE;
$config['email_reply_to_address']   = 'noreply@reservadeportiva.com';
$config['club_map']   = '';
$config['club_normativa']   = '';
$config['club_weather']   = '<div id="TT_RiT1EEE1E1UB18IUKfuzjjjDjWlULU12LtkdEcCoakj5GomoG"><h2><a href="http://www.tutiempo.net/Tiempo-Espana.html">El Tiempo</a></h2><a href="http://www.tutiempo.net/Tiempo-Torrijos-E45688.html">El tiempo en Torrijos</a></div>
			<script type="text/javascript" src="http://www.tutiempo.net/widget/eltiempo_RiT1EEE1E1UB18IUKfuzjjjDjWlULU12LtkdEcCoakj5GomoG"></script>';

$config['secret_word']	= 'info@reservadeportiva.com'; //Parabra secreta para usar de sufijo en encriptaciones con md5
$config['google_analytics_ID']	= 'UA-20049350-3';
$config['google_analytics_RD_ID']	= 'UA-20049350-2';



/*
|--------------------------------------------------------------------------
| Formas de pago
|--------------------------------------------------------------------------
|
| Habilita las diferentes formas de pago posibles
|
|
*/

$config['enable_reserve']	= TRUE;
$config['enable_cash']	= TRUE;
$config['enable_prepaid']	= TRUE;
$config['enable_creditcard']	= TRUE;
$config['enable_paypal']	= FALSE;
$config['enable_bank']	= TRUE;
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
$config['anonymous_enabled']	= FALSE;

$config['sport_required']	= FALSE;
$config['courtype_required']	= FALSE;
$config['court_required_9']	= TRUE;
$config['court_required_7']	= FALSE;
$config['court_required_3']	= FALSE;
$config['court_required_1']	= FALSE;
$config['court_required']	= array(9 => TRUE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 3 => FALSE, 1 => FALSE);
$config['date_required']	= array(9 => TRUE, 7 => TRUE, 6 => TRUE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
$config['max_search_days']	= array(9 => 2, 7 => 3, 5 => 3, 3 => 90, 1 => 365);
$config['booking_delay_seconds']	= array(9 => 0, 8 => 0, 7 => 0, 6 => 0, 5 => 0, 4 => 0, 3 => 0, 1 => 1200);	// segundos de retraso con los que se puede reservar una hora ya pasada

# Especifica los permisos de cada nivel de usuario para tener acceso a la opci�n de reservar sin coste
$config['no_cost_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 3 => FALSE, 1 => TRUE);

# Especifica si se dan jugadores en las pistas
$config['booking_record_players']	= FALSE;
$config['booking_record_players_number']	= 4;
$config['booking_record_players_range']	= array(1, 2,4);

#Especifica a partir de que nivel (yendo a niveles de numero menor y mayor nivel) ya SI pueden ver las pistas que esten como no visibles
$config['court_visible_by_group_higher_than']	= 5;



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
$config['cancelled_reserve_refund']	= array(9 => FALSE, 8 => FALSE, 7 => TRUE, 6 => TRUE, 5 => TRUE, 4 => TRUE, 3 => TRUE, 1 => TRUE); //Si es true se devuelve el dinero de la reserva a la cuenta prepago, si es false no se devuelve dinero
$config['cancelled_reserve_cancel_payment']	= array(9 => TRUE, 8 => TRUE, 7 => TRUE, 6 => TRUE, 5 => TRUE, 4 => TRUE, 3 => TRUE, 1 => TRUE); //Si es true se cancela el pago asociado a la reserva, si es false no se devuelve dinero

// Numero de segmentos de reserva m�nimos que el sistema permite reservar (al hacer click en un segmento, se seleccionan automaticamente  las adjuntas correspondientes, empezando desde el principio del dia)
$config['reserve_minimum_intervals']	= array(9 => 1, 8 => 1, 7 => 1, 6 => 1, 5 => 1, 4 => 1, 3 => 1, 2 => 1, 1 => 1); 

// Numero de segmentos m�ximos (por deporte y por nivel de usuario) que se puede reservar en un d�a
$config['reserve_maximum_intervals_check']	= TRUE;
$config['reserve_maximum_intervals']	= array(
		1 => array (
								1 => 999,
								2 => 999,
								3 => 999,
								4 => 15,
								5 => 15,
								6 => 15,
								7 => 15,
								8 => 15,
								9 => 3
								),
		2 => array (
								1 => 999,
								2 => 999,
								3 => 999,
								4 => 15,
								5 => 15,
								6 => 15,
								7 => 15,
								8 => 15,
								9 => 3
								)
		);
								

/*
|--------------------------------------------------------------------------
| configuracion de tarifas
|--------------------------------------------------------------------------
|
| Parametrizaci�n 
|
|
*/

$config['booking_light_price']	= 10;

$config['booking_extra_riogrande']	= FALSE;
$config['booking_extra_riogrande_quantity']	= 0.6;


/*
|--------------------------------------------------------------------------
| configuraciones varias
|--------------------------------------------------------------------------
|
| Parametrizaci�n 
|
|
*/

$config['public_register_user']	= FALSE;
$config['public_login_user']	= TRUE;


$config['reserve_send_mail']	= FALSE;
$config['reserve_send_sms']	= FALSE;

$config['reserve_admin_notification_cc']	= TRUE;
$config['reserve_admin_notification_mail']	= 'juanjo.nieto@gmail.com, jorge.egalite@gmail.com';


$config['weekdays_names']	= array(0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 7 => 'Domingo' );
$config['weekdays_names_alt']	= array('D' => 'Domingo', 'L' => 'Lunes', 'M' => 'Martes', 'X' => 'Miercoles', 'J' => 'Jueves', 'V' => 'Viernes', 'S' => 'Sabado', 'D' => 'Domingo' );


/* End of file reservas.php */
/* Location: ./system/application/config/reservas.php */