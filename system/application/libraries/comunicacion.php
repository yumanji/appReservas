<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class comunicacion
{
	public function comunicacion()
	{
		$this->CI =& get_instance();
		log_message('debug', "comunicacion Class Initialized");
		$this->CI->load->model('Notifications_model', 'mails', TRUE);
	}


	public function send_general_notification($data, $type) {
		
		$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);
		
		switch ($type) {
			case '2':
				# Mail a todos los alumnos de clases
				$usuarios = $this->CI->usuario->get_data(array('where' => "users.email <>'' and users.email is not null and users.active = '1' and allow_mail_notification = '1' and users.id IN (SELECT DISTINCT lessons_assistants.id_user FROM `lessons_assistants`, lessons WHERE id_lesson=lessons.id and lessons.active = 1 and lessons_assistants.status <>9 and lessons.end_date >= '".date(DATETIME_DB)."')"))->result_array();			
			break;
			
			case '3':
				# Mail a todos los participantes de ranking
				$usuarios = $this->CI->usuario->get_data(array('where' => "users.email <>'' and users.email is not null and users.active = '1' and allow_mail_notification = '1'  and users.id IN (SELECT DISTINCT ranking_teams_members.id_user FROM `ranking_teams_members`, ranking_teams,  ranking where ranking.active = 1 and ranking_teams.id = ranking_teams_members.id_team and ranking.id = ranking_teams.id_ranking and ranking_teams.status <> 9 and ranking_teams_members.status <> 9 and ranking.end_date >= '".date(DATETIME_DB)."')"))->result_array();			
			break;
			
			case '4':
				# Mail a todos los usuarios abonados
				$usuarios = $this->CI->usuario->get_data(array('where' => "users.email <>'' and users.email is not null and users.active = '1' and allow_mail_notification = '1' and users.group_id <= 6"))->result_array();			
			break;
			
			case '5':
				# Mail a todos los usuarios NO abonados
				$usuarios = $this->CI->usuario->get_data(array('where' => "users.email <>'' and users.email is not null and users.active = '1' and allow_mail_notification = '1' and users.group_id = 7"))->result_array();			
			break;
			
			case '1':
			default:
				# Mail a todos los activos
				$usuarios = $this->CI->usuario->get_data(array('where' => "users.email <>'' and users.email is not null and users.active = '1' and allow_mail_notification = '1'"))->result_array();			
			break;
		}
		
		$emails = array();
		foreach($usuarios as $usuario) {
			if(!in_array($usuario['email'], $emails)) array_push($emails, $usuario['email']);
		}
		//print("<pre>");print_r($emails);print_r($data);exit();
		
		$resultado = $this->CI->mails->createMultiMessage($data, $emails);
		return $resultado;
	}
}
