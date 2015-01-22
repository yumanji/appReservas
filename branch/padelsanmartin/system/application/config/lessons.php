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
$config['lessons_admincheck_permission']	= array(9 => FALSE, 7 => FALSE, 5 => FALSE, 3 => FALSE, 1 => TRUE);


/* End of file lessons.php */
/* Location: ./system/application/config/lessons.php */