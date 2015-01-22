<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Retos
|--------------------------------------------------------------------------
|
| Parametrización de diferentes permisos de acceso de la aplicacion
|
|
*/

# Especifica la hora a partir de la cual se considera que el reto es por la tarde (a efectos de limitacion en las notificaciones)
$config['retos_afternoon_edge']	= '15:30';

# Especifica si los partidos de retos deben registar los ganadores
$config['retos_save_results']	= TRUE;
# Especifica si los resultados de los partidos de retos deben modificar puntuación de los jugadores (esta opción inhabilita el slider del player_level en la vista del perfil de cada uno, no así en la de detalle de usuario que ven los gestores)
$config['retos_player_level_modification']	= TRUE;
# parametro de la función que gestionará lo que se debe hacer con los jugadores en cada club
$config['retos_player_level_function']	= 'suma01';


/* End of file retos.php */
/* Location: ./system/application/config/retos.php */