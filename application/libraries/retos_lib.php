<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class retos_lib
{
	public function retos_lib()
	{
		$this->CI =& get_instance();
		log_message('debug', "retos_lib Class Initialized");
		$this->CI->load->model('Retos_model', 'retos', TRUE);
	}


 ##############
 #
 # Devuelve booleano indicando si el reto está aún abierto a modificaciones o ya se jugó
 #
 ####################
	public function reto_is_open($id_transaction) {
		
		$info=$this->CI->reservas->getBookingInfoById($id_transaction);
		//print_r($info);
		//echo '<br>'.strtotime($info['date'].' '.$info['inicio'].':00');
		//echo '<br>'.time();
		if(strtotime($info['date'].' '.$info['inicio'].':00') < time()) return FALSE;
		else return TRUE;
	}




 ##############
 #
 # Graba los resultados de un reto (incluso actualizando los niveles de los usuarios llegado el caso)
 #
 ####################
	public function save_result($id_transaction, $jugadores) {
		
		if($id_transaction == '') return FALSE;
		if(!is_array($jugadores)) return FALSE;
		
		$info=$this->CI->reservas->getBookingInfoById($id_transaction);
		if(!is_array($info) && count($info)>0) return FALSE;
		
		//echo '<pre>';
		//print_r($info);
		
		$jugadores = $this->calculate_level_modification($jugadores);
		
		foreach($jugadores as $jugador) {
			echo $jugador['id_user'];
			if($jugador['win_game'] != 0 || $jugador['player_level_variation'] != 0){
				$this->CI->retos->setPlayerLevelModification($jugador['id_user'], $jugador['player_level_variation']);
			}
			$this->CI->retos->setPlayerSharedGameResult($jugador['id_user'], $id_transaction, array('win_game' => $jugador['win_game'], 'player_level_variation' => $jugador['player_level_variation']));
		}
		
		$this->CI->retos->setSharedGameResultSaved($id_transaction);
		//print_r($jugadores);
		
		return TRUE;
	}





 ##############
 #
 # Borra los resultados de un reto (incluso actualizando los niveles de los usuarios llegado el caso)
 #
 ####################
	public function delete_result($id_transaction, $jugadores) {
		
		if($id_transaction == '') return FALSE;
		if(!is_array($jugadores)) return FALSE;
		
		$info=$this->CI->reservas->getBookingInfoById($id_transaction);
		if(!is_array($info) && count($info)>0) return FALSE;
		
		//echo '<pre>';
		//print_r($info);
		
		$jugadores = $this->calculate_level_modification($jugadores);
		
		foreach($jugadores as $jugador) {
			//echo $jugador['id_user'];

			$this->CI->retos->setPlayerLevelModification($jugador['id_user'], ((-1) * $jugador['player_level_variation']));
			$this->CI->retos->setPlayerSharedGameResult($jugador['id_user'], $id_transaction, array('win_game' => 0, 'player_level_variation' => 0));
		}
		
		$this->CI->retos->setSharedGameResultSaved($id_transaction, 0);
		//print_r($jugadores);
		
		return TRUE;
	}



 ##############
 #
 # Graba los resultados de un reto (incluso actualizando los niveles de los usuarios llegado el caso)
 #
 ####################
	public function calculate_level_modification($jugadores) {
	
		if($this->CI->config->item('retos_player_level_modification')) {
			switch($this->CI->config->item('retos_player_level_function')) {
				case 'suma01':
					foreach($jugadores as $id=>$jugador) {
						if($jugador['win_game']=='1') $jugadores[$id]['player_level_variation'] = '0.10';
						else $jugadores[$id]['player_level_variation'] = '-0.10';
					}
				break;
			}
		}
		return $jugadores;
	
	}

}