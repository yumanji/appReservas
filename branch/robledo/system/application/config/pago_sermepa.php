<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Datos del pago por TPV
|--------------------------------------------------------------------------
|
| Definicion de variables para el pago
|
|
*/

$config['tpv_library_sufix']	= 'sermepa';


$config['tpv_nombre_comercio']	= 'EGALITEPADEL_PRUEBAS';
$config['tpv_codigo_comercio']	= '22695613';
$config['tpv_email_comercio']	= 'juanjo.nieto@gmail.com';
$config['tpv_palabra_secreta']	= 'qwertyasdf0123456789';
$config['tpv_usuario']	= '022695613-001';
$config['tpv_password']	= 'p022695613-001';
$config['tpv_payment_url']	= 'https://sis-t.sermepa.es:25443/sis/realizarPago';
$config['tpv_terminal']	= '1';
$config['tpv_moneda']	= '978';
$config['tpv_transaction_type']	= '0';
$config['tpv_url_ok']	= site_url('payment/confirm_tpv_ok_sermepa');
$config['tpv_url_ko']	= site_url('payment/confirm_tpv_ko_sermepa');
$config['tpv_url_return']	= base_url().'index2.php?c=payment&m=background_tpv_sermepa';
$config['tpv_email_notification']	= 'juanjo.nieto@gmail.com';

#############
## TEST
############

$config['tpv_nombre_comercio']	= 'EGALITEPADEL_PRUEBAS';
$config['tpv_codigo_comercio']	= '999008881';
$config['tpv_email_comercio']	= 'juanjo.nieto@gmail.com';
$config['tpv_palabra_secreta']	= 'qwertyasdf0123456789';
$config['tpv_usuario']	= '022695613-001';
$config['tpv_password']	= 'p022695613-001';
$config['tpv_payment_url']	= 'https://sis-i.sermepa.es:25443/sis/realizarPago';
$config['tpv_terminal']	= '1';
$config['tpv_moneda']	= '978';
$config['tpv_transaction_type']	= '0';
$config['tpv_url_ok']	= base_url().'index2.php?c=payment&m=confirm_tpv_ok_sermepa';
$config['tpv_url_ko']	= base_url().'index2.php?c=payment&m=confirm_tpv_ko_sermepa';
$config['tpv_url_return']	= base_url().'index2.php?c=payment&m=confirm_tpv_ko_sermepa';
$config['tpv_email_notification']	= 'juanjo.nieto@gmail.com';


/* End of file pagos.php */
/* Location: ./system/application/config/pagos.php */