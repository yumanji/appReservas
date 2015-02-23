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


# Configuración sobre
$config['lessons_enable_lesson_idcard']	= TRUE;
$config['lessons_idcard_template_default']	= 'carnetIMDJ_ocio.jpg';
$config['lessons_idcard_lessons_enabled']	= array(399, 400);
$config['lessons_idcard_lessons_custom_template']	= array('400' => 'carnetIMDJ_ocio_abono.jpg');

/* End of file lessons.php */
/* Location: ./system/application/config/lessons.php */