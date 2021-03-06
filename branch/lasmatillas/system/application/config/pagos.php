<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Parametros generales de los pagos
|--------------------------------------------------------------------------
|
*/
$config['payment_general_iva']	= 0.21;
$config['payment_autoticket']	= TRUE;


/*
|--------------------------------------------------------------------------
| Parametros del numero de ticket
|--------------------------------------------------------------------------
|
*/
$config['payment_ticket_format_number_length']	= 3;
$config['payment_ticket_format_number']	= '%0'.$config['payment_ticket_format_number_length'].'d';
$config['payment_ticket_format_prefix']	= 'ymd';


/*
|--------------------------------------------------------------------------
| Datos del pago por TPV
|--------------------------------------------------------------------------
|
| Definicion de variables para el pago
|
*/
$config['tpv_library_prefix']	= 'sermepa';

$config['tpv_popup']	= TRUE;

$config['tpv_moneda']	= 'EUR';
$config['tpv_url_ok']	= 'EUR';
$config['tpv_url_ko']	= 'EUR';
$config['tpv_url_post']	= 'EUR';
$config['tpv_email_notification']	= 'juanjo.nieto@gmail.com';


/*
|--------------------------------------------------------------------------
| Datos depermisos sobre las diferentes opciones de pago
|--------------------------------------------------------------------------
|
*/

$config['payment_admin_available']	= array('reserve' => TRUE, 'cash' => TRUE, 'prepaid' => TRUE, 'creditcard' => TRUE, 'paypal' => FALSE, 'bank' => TRUE, 'tpv' => FALSE );
$config['payment_operator_available']	= array('reserve' => TRUE, 'cash' => TRUE, 'prepaid' => TRUE, 'creditcard' => TRUE, 'paypal' => FALSE, 'bank' => TRUE, 'tpv' => FALSE );
$config['payment_profesor_available']	= array('reserve' => TRUE, 'cash' => FALSE, 'prepaid' => TRUE, 'creditcard' => FALSE, 'paypal' => FALSE, 'bank' => FALSE, 'tpv' => FALSE );
$config['payment_advanced_user_available']	= array('reserve' => TRUE, 'cash' => FALSE, 'prepaid' => TRUE, 'creditcard' => FALSE, 'paypal' => FALSE, 'bank' => FALSE, 'tpv' => TRUE );
$config['payment_user_available']	= array('reserve' => FALSE, 'cash' => FALSE, 'prepaid' => TRUE, 'creditcard' => FALSE, 'paypal' => FALSE, 'bank' => FALSE, 'tpv' => TRUE );
$config['payment_anonimo_available']	= array('reserve' => FALSE, 'cash' => FALSE, 'prepaid' => FALSE, 'creditcard' => FALSE, 'paypal' => TRUE, 'bank' => FALSE, 'tpv' => TRUE );



/*
|--------------------------------------------------------------------------
| Datos degeneracion de remesas bancarias
|--------------------------------------------------------------------------
|
*/

//N�mero de cuenta para el ordenante y el presentador
//$config['aeb19_bank_account'] = array('0128', '0225', '66', '0100011185');	// Move to the bit
$config['aeb19_bank_account'] = array('0081', '4311', '56', '0001045809');	// EMUVISA
//$config['aeb19_bank_account'] = array('0081', '0295', '07', '0001250631');	// egalite

//CIF para el ordenante y el presentador
$config['aeb19_business_cif'] = 'A82634353';	// Move to the bit
//$config['aeb19_business_cif'] = 'B85390482';	// Egalite

//Nombre del presentador y del ordenante
$config['aeb19_business_name'] = 'EMUVISA, S.A.U.';
//$config['aeb19_business_name'] = 'Egalite S.L.';

//Direccion fiscal
$config['aeb19_business_address'] = 'Calle Iglesia n&ordm; 12, Arroyomolinos (28939), Madrid';



/* End of file pagos.php */
/* Location: ./system/application/config/pagos.php */