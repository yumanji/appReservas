<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Configuraciones de clases y cursos
|--------------------------------------------------------------------------
|
| Diferentes datos del club en cuestión que pueden ser interesantes
|
|
*/

$config['lessons_prices_range']	= '5';


$config['payment_advice_prev_days']	= '7';


# permisos de cada nivel de usuario para hacer checkin final de una clase realizada
$config['lessons_admincheck_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => TRUE, 1 => TRUE);

$config['lessons_profesor_group']	= array(4, 3, 2, 1);


/* End of file lessons.php */
/* Location: ./system/application/config/lessons.php */