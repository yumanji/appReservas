<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Permisos
|--------------------------------------------------------------------------
|
| Parametrización de diferentes permisos de acceso de la aplicacion
|
|
*/

# Especifica los permisos de cada nivel de usuario para tener acceso a la opción de panel de control
$config['control_panel_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 3 => TRUE, 1 => TRUE);
# Especifica los permisos de cada nivel de usuario para visualizar el cierre de caja
$config['cierre_caja_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 3 => TRUE, 1 => TRUE);
# Especifica los permisos de cada nivel de usuario para visualizar la caja de busqueda
$config['main_search_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# Especifica los permisos de cada nivel de usuario para visualizar los perfiles de usuarios
$config['profiles_visualization_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para visualizar listado de reservas
$config['bookings_visualization_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para visualizar ranking
$config['ranking_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para visualizar calendarios especiales
$config['config_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 3 => FALSE, 1 => TRUE);
# permisos de cada nivel de usuario para visualizar listado de reservas
$config['mails_visualization_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede crear partidos compartidos
$config['shared_bookings_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede ver detalles de cursos
$config['lessons_admin_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede ver cuestiones del modulo de pagos
$config['payment_managment_permission']	= array(9 => FALSE, 7 => FALSE, 6 => FALSE, 5 => TRUE, 3 => TRUE, 1 => TRUE);

# permisos de cada nivel de usuario para determinar si puede ver informes
$config['reports_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede ver la opcion del menu de calendario
$config['calendar_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede ver la opcion del menu de actividades
$config['activities_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);
# permisos de cada nivel de usuario para determinar si puede ver la opcion del menu de tienda
$config['shop_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);




# permisos de cada nivel de usuario para determinar si puede ver el chat de soporte
$config['chat_permission']	= FALSE;
# permisos de cada nivel de usuario para determinar si puede ver el chat de soporte
$config['chat_group_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);


/* End of file permissions.php */
/* Location: ./system/application/config/permissions.php */