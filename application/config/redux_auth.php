<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */

	/**
	 * Tables.
	 **/
	$config['tables']['groups'] = 'groups';
	$config['tables']['users'] = 'users';
	$config['tables']['meta'] = 'meta';
	
	/**
	 * Default group, use name
	 */
	$config['default_group'] = 'user';
	 
	/**
	 * Meta table column you want to join WITH.
	 * Joins from users.id
	 **/
	$config['join'] = 'user_id';
	
	/**
	 * Columns in your meta table,
	 * id not required.
	 **/
	$config['columns'] = array('first_name', 'last_name', 'address', 'population', 'code_province', 'code_country', 'cp', 'gender', 'nif', 'birth_date', 'phone', 'mobile_phone', 'prepaid_cash', 'bank', 'bank_office', 'bank_dc', 'bank_account', 'bank_titular', 'player_level', 'allow_mail_notification', 'allow_phone_notification', 'reto_notifica', 'code_price', 'alt_code', 'last_payd_date', 'numero_socio', 'avatar');
	
	/**
	 * A database column which is used to
	 * login with.
	 **/
	$config['identity'] = 'id';

	/**
	 * Email Activation for registration
	 **/
	$config['email_activation'] = true;
	$config['email_activation_admin'] = true;	// Enviar un email cuando se da de alta un usuario desde dentro de la aplicacion
	if(!$config['email_activation']) $config['email_activation_admin'] = false;	// Si el mail esta desactivado.. se desactivan todos
	
	/**
	 * Folder where email templates are stored.
     * Default : redux_auth/
	 **/
	$config['email_templates'] = 'redux_auth/';

	/**
	 * Salt Length
	 **/
	$config['salt_length'] = 10;

	/**
	 * Crear automaticamente el password al crear usuario por administracion
	 **/
	$config['auto_password'] = TRUE;
	
	
	# Grupos de usuarios que pueden cambiar los passwords de otros usuarios
	$config['users_password_admin_change']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => FALSE, 1 => TRUE);




/*
|--------------------------------------------------------------------------
| configuracion de tarifas
|--------------------------------------------------------------------------
|
| Tarifa de coutas de usuario
|
|
*/

$config['users_qouta_price']	= 20;

# Grupos de usuarios a los que se les cobrará una cuota
$config['users_quota_group']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => TRUE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => FALSE, 1 => FALSE);


# Fecha de cobro de la proxima cuota mensual 
$config['users_monthly_quota_next_date'] = date('d-m-Y');	

# Fecha de cobro de la proxima cuota anual
if(date('n') < 10) $config['users_yearly_quota_next_date'] = '01-10-'.(intval(date('Y'))-1);
else  $config['users_yearly_quota_next_date'] = '01-10-'.(intval(date('Y')));
# En este caso está previsto que se cobre en el mes 10 (Octubre)


# Dias de anterioridad con los cuales avisaremos de que hay usuarios a los que se les acaba la cuota pagada
# y fecha en la que se generarán los datos de los pagos por banco.
$config['users_qouta_caducity_days']	= 7;

# Forma de pago por defecto para la emision de pagos de cuotas de socio
$config['users_qouta_default_paymentway']	= 4;
$config['users_qouta_default_payment_status']	= 2;

$config['users_qouta_admin_notification']	= 'juanjo.nieto@gmail.com';



/*
|--------------------------------------------------------------------------
| configuraciones varias de los usuarios
|--------------------------------------------------------------------------
|
|
*/

# Muestro el numero 'especial' de usuario (para numeros de abonado, numeros de carnet, etc..)
$config['users_member_number_visibility']	= TRUE;
# Niveles de usuario en los que este número se verá
$config['users_member_number_visibility_by_group']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => TRUE, 5 => TRUE, 4 => TRUE, 3 => TRUE, 2 => TRUE, 1 => TRUE);
# Numero de usuario generado automáticamente (no editable)
$config['users_member_number_auto']	= FALSE;
# Formato del numero de usuario generado automáticamente
$config['users_member_number_auto_format']	= '%06s';

# Campos extra a mostrar en el resultado de la busqueda rapida de usuarios
$config['users_search_extra_info']	= array('users.id'=> 'id');
# Campos extra por los que filtrar en la busqueda rapida de usuarios
$config['users_search_extra_filters'] = array('meta.first_name', 'meta.last_name', 'meta.nif', 'meta.phone', 'meta.mobile_phone', 'meta.numero_socio', 'users.id', 'users.code');

# Configuración sobre
$config['users_carnet_enabled']	= TRUE;
$config['users_carnet_template_by_group']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => 'carnetIMDJ_noemp.jpg', 5 => 'carnetIMDJ_emp.jpg', 4 => 'carnetIMDJ_emp.jpg', 3 => 'carnetIMDJ_emp.jpg', 2 => 'carnetIMDJ_emp.jpg', 1 => 'carnetIMDJ_emp.jpg');


# Mostrar aviso legal al registrar usuarios
$config['users_register_legal_advice']	= TRUE;


?>