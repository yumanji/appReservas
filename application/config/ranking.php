<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Permisos del ranking
|--------------------------------------------------------------------------
|
| Parametrización de diferentes permisos de acceso de la aplicacion
|
|
*/

# permisos de cada nivel de usuario para editar ranking
$config['ranking_create_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);

# permisos de cada nivel de usuario para editar ranking
$config['ranking_edit_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => TRUE, 2 => TRUE, 1 => TRUE);

# permisos de cada nivel de usuario para borrar ranking
$config['ranking_delete_permission']	= array(9 => FALSE, 8 => FALSE, 7 => FALSE, 6 => FALSE, 5 => FALSE, 4 => FALSE, 3 => FALSE, 2 => TRUE, 1 => TRUE);




/*
|--------------------------------------------------------------------------
| Tanteos de los partidos
|--------------------------------------------------------------------------
|
| Parametrización de los tanteos de los partidos del ranking
|
|
*/

$config['ranking_drawn_match']	= 0;
$config['ranking_win_match']	= 1;
$config['ranking_lost_match']	= 0;
$config['ranking_win_WO_match']	= 1;
$config['ranking_lost_WO_match']	= 0;

$config['ranking_lost_lesion_ganador_tanteo']	= 6;
$config['ranking_lost_lesion_perdedor_tanteo']	= 3;
$config['ranking_lost_ausente_ganador_tanteo']	= 6;
$config['ranking_lost_ausente_perdedor_tanteo']	= 3;







$config['ranking_completed_matchs_status']	= array(5, 6, 8);



/* End of file ranking.php */
/* Location: ./system/application/config/ranking.php */