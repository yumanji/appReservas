<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Parametros generales del modulo facturacion
|--------------------------------------------------------------------------
|
*/

# permisos de cada nivel de usuario para determinar si puede crear un pago nuevo
$config['payment_add_custom_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede crear una devolucion
$config['payment_add_custom_devolution_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);

# permisos de cada nivel de usuario para determinar si puede cambiar estados de los pagos
$config['payment_change_status_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => FALSE, 1 => TRUE);

# permisos de cada nivel de usuario para determinar si puede cambiar estados de los pagos a pendiente
$config['payment_pendiente_change_status_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => FALSE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede volver a pagar pagos pendientes
$config['payment_repay_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede cambiar estados de los pagos a devuelto
$config['payment_devuelto_change_status_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => FALSE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede cambiar estados de los pagos a cancelado
$config['payment_cancel_change_status_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => FALSE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede ver recibos
$config['payment_view_receipt_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 4 => TRUE, 3 => TRUE, 2 => TRUE, 1 => TRUE);

# estados de pago que pueden revertirse a pendiente
$config['payment_change_status_pendiente_option']	= array(6, 9);
# estados de pago que pueden ser repagados
$config['payment_repay_option']	= array(2);
# estados de pago que pueden revertirse a devuelto
$config['payment_change_status_devuelto_option']	= array(9);
# estados de pago que pueden cancelarse
$config['payment_change_status_cancelar_option']	= array(1, 2, 3, 4, 5, 6);
# estados de pago que pueden generar un recibo
$config['payment_generate_receipt_option']	= array(6, 9);
# estados de pago que deben considerarse como entradas en facturacion
$config['payment_considered_to_report']	= array( 6,  9);
# estados de pago que pueden ser aún cobrados
$config['booking_status_can_be_payd_option']	= array(1, 2, 3, 4, 5, 6, 7, 8);

# Formas de pago que, al crearse un pago manual, serán puestas como estado 'pendiente' (normalmente, transferencia bancaria)
$config['payment_creado_pendiente']	= array( 4);


# Tipo de pago  asociado al suplemento de luz
$config['booking_extra_light_payment_type']	= 98;


# PArametros que determinan la ejecucion de rutinas alternativas de facturacion 
# (saltando la funcion estandar de tarifas)
$config['prices_alternative_funtion']	= FALSE;

$config['prices_alternative_values']	= array(1);	# Codigo de las tarifas que se considerarán como diferentes y deberán ser calculadas de otro modo

/* End of file pagos.php */
/* Location: ./system/application/config/pagos.php */