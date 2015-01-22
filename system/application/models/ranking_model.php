<?php
class Ranking_model extends Model {

		var $id   = NULL;
		var $create_user   = NULL;
		var $create_time   = NULL;
		var $create_ip   = NULL;
		var $modify_user   = NULL;
		var $modify_time   = NULL;
		var $modify_ip   = NULL;
		
    function Ranking_model()
    {
        // Call the Model constructor
        parent::Model();
    }

##############################################################################


  function getAssistantsStatus ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_ranking_assistants_status ORDER BY Description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }


  function getMatchsStatus ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_ranking_matchs_status ORDER BY Description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }


  function getPromotionTypes ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_ranking_promotion_types ORDER BY Description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }


  function getRoundDurations ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_ranking_round_duration ORDER BY Description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }



  function get_Prices ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM prices ORDER BY description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }


  
  function getSportsArray($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona deporte");
      $sql = "SELECT id, description FROM zz_sports WHERE active=1 "; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$this->lang->line($row->description);
			}	
				return $result;				
  }

  
  function getGendersArray($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array("0"=>"Indiferente");
      $sql = "SELECT id, description FROM zz_gender ORDER BY Description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }





##############################################################################
##  Crear un ranking
##############################################################################

  function createRanking($datos)
  {
  	if(!isset($datos['description']) || $datos['description']=='') return FALSE;
  	
  	$data = array(
             'description' => substr($datos['description'], 0, 75),
             'active' => $datos['active'],
             'gender' => $datos['gender'],
             'groups' => $datos['groups'],
             'teams' => $datos['teams'],
             'team_mates' => $datos['team_mates'],
             'start_date' => $datos['start_date'],
             'end_date' => $datos['end_date'],
             'rounds' => $datos['rounds'],
             'round_duration' => $datos['round_duration'],
             'score_parts' => $datos['score_parts'],
             'match_duration' => $datos['match_duration'],
             'promotion_type' => $datos['promotion_type'],
             'sport' => $datos['sport'],
             'price' => $datos['price'],
             'payment_freq' => $datos['payment_freq'],
 	           'signin' => $datos['signin'],
             'started' => 0,
             'current_round' => 0,
             'create_user' => $this->session->userdata('user_id'),
             'create_time' => date($this->config->item('log_date_format')),
             'create_ip' => $this->session->userdata('ip_address')
    	);

    $this->db->insert('ranking', $data);
    log_message('debug',$this->db->last_query());
    return $this->db->insert_id();
  }



##############################################################################
##  Actualizar un ranking
##############################################################################

	function updateRanking($datos, $key_field = NULL) {
		try {
	
	  	if(!isset($datos['description']) || $datos['description']=='') return FALSE;
	  	if(!isset($datos['id']) || $datos['id']=='') return FALSE;
	
	
	  	$data = array(
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
	    );
	    
	    if(isset($datos['description'])) $data['description'] = substr($datos['description'], 0, 75);
	    if(isset($datos['gender'])) $data['gender'] = $datos['gender'];
	    if(isset($datos['groups'])) $data['groups'] = $datos['groups'];
	    if(isset($datos['teams'])) $data['teams'] = $datos['teams'];
	    if(isset($datos['team_mates'])) $data['team_mates'] = $datos['team_mates'];
	    if(isset($datos['start_date'])) $data['start_date'] = $datos['start_date'];
	    if(isset($datos['end_date'])) $data['end_date'] = $datos['end_date'];
	    if(isset($datos['rounds'])) $data['rounds'] = $datos['rounds'];
	    if(isset($datos['round_duration'])) $data['round_duration'] = $datos['round_duration'];
	    if(isset($datos['score_parts'])) $data['score_parts'] = $datos['score_parts'];
	    if(isset($datos['match_duration'])) $data['match_duration'] = $datos['match_duration'];
	    if(isset($datos['promotion_type'])) $data['promotion_type'] = $datos['promotion_type'];
	    if(isset($datos['sport'])) $data['sport'] = $datos['sport'];
	    if(isset($datos['price'])) $data['price'] = $datos['price'];
	    if(isset($datos['signin'])) $data['signin'] = $datos['signin'];

	    if(isset($key_field)) $this->db->where($key_field, $datos[$key_field]);
	    else $this->db->where('id', $datos['id']);
	    
			$this->db->update('ranking', $data);
			log_message('debug',$this->db->last_query());
			
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en actualizacion de ranking: '.var_export($e));
	  }
	  return TRUE;
	}




##############################################################################
##  Iniciar un ranking
##############################################################################

	function startRanking($id, $key_field = NULL) {
		try {

	
	  	$data = array(
	             'started' => '1',
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
	    );

	    if(isset($key_field)) $this->db->where($key_field, $id);
	    else $this->db->where('id', $id);
	    
			$this->db->update('ranking', $data);
			log_message('debug',$this->db->last_query());
			//exit($this->db->last_query());
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en actualizacion de ranking: '.var_export($e));
	  }
	  return TRUE;
	}




##############################################################################
##  Iniciar una jornada
##############################################################################

	function startRound($id_round) {
		try {
	

			$sql = "SELECT round, id_ranking FROM ranking_rounds WHERE id =".$id_round;
			//echo $sql; 
			$query = $this->db->query($sql);
			$row = $query->row_array();

			$round = $row['round'];
			$id_ranking = $row['id_ranking'];

			# Me aseguro de cerrar las rondas anteriores
			$data = array(
					 'finished' => '1',
					 'modify_user' => $this->session->userdata('user_id'),
					 'modify_time' => date($this->config->item('log_date_format')),
					 'modify_ip' => $this->session->userdata('ip_address')
			);
			$this->db->where('round < '.$round);
			$this->db->update('ranking_rounds', $data);
			log_message('debug',$this->db->last_query());
			
			
			$data = array(
					 'started' => '1',
					 'finished' => '0',
					 'modify_user' => $this->session->userdata('user_id'),
					 'modify_time' => date($this->config->item('log_date_format')),
					 'modify_ip' => $this->session->userdata('ip_address')
			);
			$this->db->where('id', $id_round);
			$this->db->update('ranking_rounds', $data);
			log_message('debug',$this->db->last_query());
			
			$data = array(
					 'current_round' => $round,
					 'modify_user' => $this->session->userdata('user_id'),
					 'modify_time' => date($this->config->item('log_date_format')),
					 'modify_ip' => $this->session->userdata('ip_address')
			);
			$this->db->where('id', $id_ranking);
			$this->db->update('ranking', $data);
			log_message('debug',$this->db->last_query());
			//exit($this->db->last_query());
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en el inicio de ronda de ranking: '.var_export($e));
	  }
	  return TRUE;
	}



##############################################################################
##  Finalizo una jornada
##############################################################################

	function endRound($id_round, $next_round = NULL) {
		try {
	

	  	$data = array(
	             'finished' => '1',
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
	    );
	    $this->db->where('id', $id_round);
		$this->db->update('ranking_rounds', $data);
		log_message('debug',$this->db->last_query());
		
		if(isset($next_round)) {
			$sql = "SELECT round, id_ranking FROM ranking_rounds WHERE id =".$next_round;
			//echo $sql; 
			$query = $this->db->query($sql);
			$row = $query->row_array();

			$round = $row['round'];
			$id_ranking = $row['id_ranking'];

			$data = array(
					 'current_round' => $round,
					 'modify_user' => $this->session->userdata('user_id'),
					 'modify_time' => date($this->config->item('log_date_format')),
					 'modify_ip' => $this->session->userdata('ip_address')
			);
			$this->db->where('id', $id_ranking);
			$this->db->update('ranking', $data);
			log_message('debug',$this->db->last_query());	//exit($this->db->last_query());	
		}

			//exit($this->db->last_query());
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en la finalizacion de ronda de ranking: '.var_export($e));
	  }
	  return TRUE;
	}




##############################################################################
##  Definir rondas de ranking
##############################################################################

	function setRounds($id, $rounds) {
		try {
			
			$table = 'ranking_rounds';
			
			$this->db->delete($table, array('id_ranking' => $id)); 
			log_message('debug',$this->db->last_query());
			
			foreach($rounds as $round) {
		  	$data = array(
           'id_ranking' => $id,
           'round' => $round['ronda'],
           'started' => 0,
           'finished' => 0,
           'start_date' => $round['start_date'],
           'end_date' => $round['end_date'],
           'create_user' => $this->session->userdata('user_id'),
           'create_time' => date($this->config->item('log_date_format')),
           'create_ip' => $this->session->userdata('ip_address')
    		);

		    $this->db->insert($table, $data);
		    log_message('debug',$this->db->last_query());

			}

			//exit($this->db->last_query());
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en actualizacion de ranking: '.var_export($e));
	  }
	  return TRUE;
	}





##############################################################################
##  Definir resultados de ronda de ranking
##############################################################################

	function setRoundScoring($id, $ronda, $resultados, $empty = FALSE) {
		try {
			
			$table = 'ranking_rounds_scoring';
			
			$this->db->delete($table, array('id_ranking' => $id, 'round' => $ronda)); 
			log_message('debug',$this->db->last_query());
			
			foreach($resultados as $resultado) {
				$data = array(
				   'id_ranking' => $id,
				   'id_team' => $resultado['id'],
				   'round' => $ronda,
				   '`group`' => $resultado['group'],
				   '`order`' => $resultado['order'],
				   'puntos' => 0,
				   'PJ' => 0,
				   'PG' => 0,
				   'PE' => 0,
				   'PP' => 0,
				   'SG' => 0,
				   'SE' => 0,
				   'SP' => 0,
				   'JG' => 0,
				   'JE' => 0,
				   'JP' => 0,
				   'create_user' => $this->session->userdata('user_id'),
				   'create_time' => date($this->config->item('log_date_format')),
				   'create_ip' => $this->session->userdata('ip_address')
				);

				if(!$empty) {
					if(isset($resultado['puntos']) && $resultado['puntos']!='') $data['puntos'] = $resultado['puntos'];
					if(isset($resultado['PJ']) && $resultado['PJ']!='') $data['PJ'] = $resultado['PJ'];
					if(isset($resultado['PG']) && $resultado['PG']!='') $data['PG'] = $resultado['PG'];
					if(isset($resultado['PE']) && $resultado['PE']!='') $data['PE'] = $resultado['PE'];
					if(isset($resultado['PP']) && $resultado['PP']!='') $data['PP'] = $resultado['PP'];
					if(isset($resultado['SG']) && $resultado['SG']!='') $data['SG'] = $resultado['SG'];
					if(isset($resultado['SE']) && $resultado['SE']!='') $data['SE'] = $resultado['SE'];
					if(isset($resultado['SP']) && $resultado['SP']!='') $data['SP'] = $resultado['SP'];
					if(isset($resultado['JG']) && $resultado['JG']!='') $data['JG'] = $resultado['JG'];
					if(isset($resultado['JE']) && $resultado['JE']!='') $data['JE'] = $resultado['JE'];
					if(isset($resultado['JP']) && $resultado['JP']!='') $data['JP'] = $resultado['JP'];
				}


		    $this->db->insert($table, $data);
		    log_message('debug',$this->db->last_query());

			}

			//exit($this->db->last_query());
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en cerrado de ronda de ranking: '.var_export($e));
	  }
	  return TRUE;
	}




##############################################################################
##  Devuelve el primer hueco libre para un equipo en el rankin
##############################################################################

	function getNextSlot($id, $key_field = NULL) {
		try {
			
			if(isset($key_field)) $filtro = $key_field;
			else $filtro = 'id';
			$datos_tmp = $this->get_data(array('where' => 'ranking.'.$filtro.' = '.$id));
			$datos = $datos_tmp[0];
			//print("<pre>");print_r($datos);
			
			$table_name = 'ranking_teams';
			$this->db->select('ranking_teams.id, ranking_teams.group, ranking_teams.order', FALSE)->from($table_name);
			$this->db->where("ranking_teams.id_ranking = '".$id."'");
			$this->db->where("ranking_teams.status = '1'");
			$this->db->order_by('ranking_teams.group', 'desc');
			$this->db->order_by('ranking_teams.order', 'desc');
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			$resultado = $query->result_array();
			if(isset($resultado) && is_array($resultado) && count($resultado) > 0) {
				$siguiente = $resultado[0];
				//print("<pre>");print_r($siguiente);
				
				if($siguiente['order'] < $datos['teams']) $siguiente['order']++;
				else {
					if($siguiente['group'] == $datos['groups']) return NULL;
					else {
						$siguiente['order'] = 1;
						$siguiente['group']++;
					}
				}
			} else {
				$siguiente = array (
						'id' => 0,
						'group' => 1,
						'order' => 1
					);
			}
			//print("<pre>");print_r($siguiente);
			//exit($this->db->last_query());
			return $siguiente;
			
		}catch(Exception $e){
			return NULL;
			log_message('debug','Error en getNextSlot: '.var_export($e));
	  }
	  return NULL;
	}










################################################
# Recuperar datos de rankings
########################################


public function get_data($params = "" , $page = "all")
	{
		
		$table_name = 'ranking';
		
		//Build contents query
		$this->db->select('ranking.id, ranking.description, ranking.gender, ranking.groups, ranking.teams, ranking.team_mates, ranking.start_date, ranking.end_date, ranking.rounds, ranking.current_round, ranking.round_duration, ranking.score_parts, ranking.match_duration, ranking.promotion_type, ranking.sport, ranking.active, ranking.price, ranking.signin, ranking.started, prices.description as tarifa, zz_ranking_promotion_types.description as promotion_types_desc, zz_ranking_round_duration.description as round_duration_desc, zz_gender.description as gender_desc, zz_sports.description as sport_desc', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');
		$this->db->join('prices', 'ranking.price=prices.id', 'left outer');
		$this->db->join('zz_ranking_promotion_types', 'ranking.promotion_type=zz_ranking_promotion_types.id', 'left outer');
		$this->db->join('zz_ranking_round_duration', 'ranking.round_duration=zz_ranking_round_duration.id', 'left outer');
		$this->db->join('zz_gender', 'ranking.gender=zz_gender.id', 'left outer');
		$this->db->join('zz_sports', 'ranking.sport=zz_sports.id', 'left outer');


		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		else $params['orderbyway'] = 'desc';
		$this->db->order_by('ranking.start_date', $params['orderbyway']);
		
		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		$resultado = $query->result_array();
		for($i=0; $i<count($resultado); $i++) {
			
			$resultado[$i]['inicio'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['start_date']));
			$resultado[$i]['final'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['end_date']));
			
		}
		log_message('debug',$this->db->last_query());
		
		return $resultado;
	

}






################################################
# Recuperar datos de partidos de rankings
########################################


public function get_matchs_data($params = "" , $page = "all")
	{
		
		$table_name = 'ranking_matchs';
		//print_r($params);
		//Build contents query
		$this->db->select($table_name.'.id, '.$table_name.'.id_ranking, '.$table_name.'.group, '.$table_name.'.round, '.$table_name.'.team1, '.$table_name.'.team2, '.$table_name.'.status, '.$table_name.'.estimated_date, '.$table_name.'.played_date, '.$table_name.'.winner, ranking_teams.description as equipo1, ranking_teams2.description as equipo2, ranking_winner.description as ganador, zz_ranking_matchs_status.description as estado, usuario1.phone as phone1, usuario2.phone as phone2', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');
		$this->db->join('zz_ranking_matchs_status', $table_name.'.status=zz_ranking_matchs_status.id', 'left outer');
		$this->db->join('ranking_teams', $table_name.'.team1=ranking_teams.id', 'left outer');
		$this->db->join('ranking_teams as ranking_teams2', $table_name.'.team2=ranking_teams2.id', 'left outer');
		$this->db->join('ranking_teams as ranking_winner', $table_name.'.winner=ranking_winner.id', 'left outer');
		$this->db->join('meta as usuario1', 'ranking_teams.main_user=usuario1.user_id', 'left outer');
		$this->db->join('meta as usuario2', 'ranking_teams2.main_user=usuario2.user_id', 'left outer');


		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['order_by'])) $this->db->order_by($params['order_by']);
		else {
			$this->db->order_by($table_name.'.group', 'asc');
			$this->db->order_by($table_name.'.played_date', 'asc');
			$this->db->order_by($table_name.'.estimated_date', 'asc');
		}
		/*
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		else $params['orderbyway'] = 'desc';
		*/
		if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		//echo $this->db->last_query().'<br>';
		
		$resultado = $query->result_array();
		log_message('debug',$this->db->last_query());

		for($i=0; $i<count($resultado); $i++) {
			
			$resultado[$i]['fecha_estimada'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['estimated_date']));
			$resultado[$i]['fecha_jugado'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['played_date']));
			$resultado[$i]['resultado'] = $this->get_matchs_result_data($resultado[$i]['id'], 'text');
			$resultado[$i]['resultado2'] = $this->get_matchs_result_data($resultado[$i]['id'], '');
			
		}		
		return $resultado;
	

}






################################################
# Recuperar datos de partidos de rankings
########################################


public function get_matchs_result_data($id_match, $option = NULL)
	{
		
		$table_name = 'ranking_matchs_result';
		//print_r($params);
		//Build contents query
		$this->db->select($table_name.'.id, '.$table_name.'.id_match, '.$table_name.'.score_part, '.$table_name.'.team1_score, '.$table_name.'.team2_score', FALSE)->from($table_name);



		$this->db->where($table_name.'.id_match = '.$id_match);
	
		$this->db->order_by($table_name.'.score_part', 'asc');

		//Get contents
		$query = $this->db->get();
		log_message('debug',$this->db->last_query());
		//echo $this->db->last_query().'<br>';
		
		$resultado = array();
		if(isset($option) && $option == 'text') $resultado = '';
		foreach ($query->result_array() as $row)
		{
			if(isset($option) && $option == 'text') {
				$resultado = $resultado . ' '.$row['team1_score'].'-'.$row['team2_score'];
			} else {
				$resultado[$row['score_part']] = array (
													'team1' => $row['team1_score'],
													'team2' => $row['team2_score']
											);
			}
		}

		
		return $resultado;
	

}







################################################
# Recuperar tanteos de los equipos de rankings
########################################


public function getRoundScoring($id, $round, $group = '')
	{
		
		$table_name = 'ranking_teams';
		
		//Build contents query
		$this->db->select('ranking_teams.id, ranking_rounds_scoring.group, ranking_rounds_scoring.order, ranking_teams.main_user, ranking_teams.description, ranking_teams.status, ranking_teams.sign_date, ranking_teams.unsubscription_date, ranking_teams.last_payd_date, ranking_teams.last_day_payed, meta.first_name, meta.last_name, meta.phone, ranking_rounds_scoring.puntos, ranking_rounds_scoring.PJ, ranking_rounds_scoring.PG, ranking_rounds_scoring.PE, ranking_rounds_scoring.PP, ranking_rounds_scoring.SG, ranking_rounds_scoring.SE, ranking_rounds_scoring.SP, ranking_rounds_scoring.JG, ranking_rounds_scoring.JE, ranking_rounds_scoring.JP ', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');
		$this->db->join('meta', $table_name.'.main_user=meta.user_id', 'left outer');
		$this->db->join('ranking_rounds_scoring', $table_name.'.id=ranking_rounds_scoring.id_team and '.$table_name.'.id_ranking=ranking_rounds_scoring.id_ranking', 'inner');

		$this->db->where($table_name.'.id_ranking', $id);
		$this->db->where($table_name.'.status', '1');
		$this->db->where('ranking_rounds_scoring.round', $round);
	
		$this->db->order_by('ranking_rounds_scoring.group', 'asc');
		$this->db->order_by('ranking_rounds_scoring.order', 'asc');
		$this->db->order_by('ranking_rounds_scoring.puntos', 'desc');
		$this->db->order_by('ranking_rounds_scoring.PG', 'desc');
		$this->db->order_by('ranking_rounds_scoring.PP', 'asc');
		$this->db->order_by('ranking_rounds_scoring.SG', 'desc');
		$this->db->order_by('ranking_rounds_scoring.SP', 'asc');
		$this->db->order_by('ranking_rounds_scoring.JG', 'desc');
		$this->db->order_by('ranking_rounds_scoring.JP', 'asc');
		/*
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		else $params['orderbyway'] = 'desc';
		*/
		
		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		//log_message('debug',$this->db->last_query());
		//echo $this->db->last_query();
		$resultado = $query->result_array();
		for($i=0; $i<count($resultado); $i++) {
			$resultado[$i]['puntos'] = intval($resultado[$i]['puntos']);
			
			$resultado[$i]['main_user_description'] = $resultado[$i]['first_name'];
			if($resultado[$i]['last_name']) $resultado[$i]['main_user_description'] .= ' ' . $resultado[$i]['last_name'];
			if(trim($resultado[$i]['main_user_description'])=='') $resultado[$i]['main_user_description'] = 'Desconocido';
			
		}
		
		return $resultado;



}





################################################
# Recuperar datos de equipos del ranking
########################################


public function get_ActiveTeams($id, $group = null)
	{
		
		$where = "ranking_teams.id_ranking = '".$id."' AND ranking_teams.status = '1'";
		if(isset($group) && trim($group)!='' && trim($group)!=' ' && trim($group)!='0') $where .= " AND ranking_teams.group = '".$group."' ";
		return $this->get_teams(array('where' => $where));
	}
	
public function get_teams($params = "" , $page = "all")
	{
		
		$table_name = 'ranking_teams';
		
		//Build contents query
		$this->db->select('ranking_teams.id, ranking_teams.id_ranking, ranking_teams.group, ranking_teams.order, ranking_teams.main_user, ranking_teams.description, ranking_teams.status, ranking_teams.sign_date, ranking_teams.unsubscription_date, ranking_teams.last_payd_date, ranking_teams.last_day_payed, meta.first_name, meta.last_name, meta.phone', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');
		$this->db->join('meta', 'ranking_teams.main_user=meta.user_id', 'left outer');

		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		$this->db->order_by('ranking_teams.group', 'asc');
		$this->db->order_by('ranking_teams.order', 'asc');
		/*
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		else $params['orderbyway'] = 'desc';
		*/
		
		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		log_message('debug',$this->db->last_query());
		$resultado = $query->result_array();
		for($i=0; $i<count($resultado); $i++) {
			
			if($resultado[$i]['sign_date']!='') $resultado[$i]['fecha_alta'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['sign_date']));
			else $resultado[$i]['fecha_alta'] = '';
			if($resultado[$i]['unsubscription_date']!='') $resultado[$i]['fecha_baja'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['unsubscription_date']));
			else $resultado[$i]['fecha_baja'] = '';
			if($resultado[$i]['last_payd_date']!='') $resultado[$i]['pagado_hasta'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['last_payd_date']));
			else $resultado[$i]['pagado_hasta'] = '';
			if($resultado[$i]['last_day_payed']!='') $resultado[$i]['fecha_pago'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['last_day_payed']));
			else $resultado[$i]['fecha_pago'] = '';
			
			$resultado[$i]['main_user_description'] = $resultado[$i]['first_name'];
			if($resultado[$i]['last_name']) $resultado[$i]['main_user_description'] .= ' ' . $resultado[$i]['last_name'];
			if(trim($resultado[$i]['main_user_description'])=='') $resultado[$i]['main_user_description'] = 'Desconocido';
			
		}
		
		return $resultado;
	

}
	
public function get_teams_members($params = "" , $page = "all")
	{
		
		$table_name = 'ranking_teams_members';
		
		//Build contents query
		$this->db->select($table_name.'.id, '.$table_name.'.id_team, '.$table_name.'.id_user, '.$table_name.'.user_name, '.$table_name.'.user_phone, '.$table_name.'.main, '.$table_name.'.status, '.$table_name.'.sign_date, '.$table_name.'.unsubscription_date, '.$table_name.'.last_payd_date, '.$table_name.'.last_day_payed, meta.first_name, meta.last_name, meta.phone, ranking.id as id_ranking', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');
		$this->db->join('meta', $table_name.'.id_user=meta.user_id', 'left outer');
		$this->db->join('ranking_teams', $table_name.'.id_team=ranking_teams.id', 'left outer');
		$this->db->join('ranking', 'ranking_teams.id_ranking=ranking.id', 'left outer');

		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		$this->db->order_by('ranking_teams.group', 'asc');
		$this->db->order_by('ranking_teams.order', 'asc');
		/*
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		else $params['orderbyway'] = 'desc';
		*/
		
		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		log_message('debug',$this->db->last_query());
		//echo $this->db->last_query();
		$resultado = $query->result_array();
		for($i=0; $i<count($resultado); $i++) {
			
			if($resultado[$i]['sign_date']!='') $resultado[$i]['fecha_alta'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['sign_date']));
			else $resultado[$i]['fecha_alta'] = '';
			if($resultado[$i]['unsubscription_date']!='') $resultado[$i]['fecha_baja'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['unsubscription_date']));
			else $resultado[$i]['fecha_baja'] = '';
			if($resultado[$i]['last_payd_date']!='') $resultado[$i]['pagado_hasta'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['last_payd_date']));
			else $resultado[$i]['pagado_hasta'] = '';
			if($resultado[$i]['last_day_payed']!='') $resultado[$i]['fecha_pago'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['last_day_payed']));
			else $resultado[$i]['fecha_pago'] = '';
			
			if($resultado[$i]['id_user']!='0' && $resultado[$i]['id_user']!='') {
				$resultado[$i]['main_user_description'] = $resultado[$i]['first_name'];
				if($resultado[$i]['last_name']) $resultado[$i]['main_user_description'] .= ' ' . $resultado[$i]['last_name'];
			} else {
				$resultado[$i]['main_user_description'] = $resultado[$i]['user_name'];
				$resultado[$i]['phone'] = $resultado[$i]['user_phone'];
			}
			if(!isset($resultado[$i]['main_user_description']) || trim($resultado[$i]['main_user_description'])=='') $resultado[$i]['main_user_description'] = 'Desconocido';
			
		}
		
		return $resultado;
	

}


public function getRanking($id) {

	$datos = $this->get_data(array('where' => 'ranking.id = '.$id));
	$ficha = $datos[0];

	$rondas = $this->getRounds($id);

	if(count($rondas)>0) { 
		foreach($rondas as $ronda) { 
			if($ficha['current_round'] == $ronda['round']) {
				$current_round_id = $ronda['id']; break;
			} else $current_round_id = '';
		} 
	}
	else $current_round_id = '';
	$ficha['current_round_id'] = $current_round_id;
	
	# Recupero precio
		if($ficha['price']!="") {
			
			$ficha['precio_por_nivel'] = '0';
			$ficha['precio'] = 0;
			$date = date($this->config->item('log_date_format'));
			
	    $sql = "SELECT prices.quantity, prices.by_group FROM prices WHERE prices.id = ? and prices.active = '1' and prices.start_date <= ? and prices.end_date >= ?  LIMIT 1"; 
			$query = $this->db->query($sql, array($ficha['price'], $date, $date));
			//log_message('debug', 'SQL: '.$this->db->last_query());
			//echo $this->db->last_query();
			if ($query->num_rows() > 0) {	
				$row = $query->row();
				$quantity = $row->quantity;
				$by_group = $row->by_group;
				
				$ficha['precio_por_nivel'] = $by_group;
				
				/*
				if($by_group == '1') {
					
					# Si la tarifa es de grupos, busco los registros en la tabla adecuada
			    $sql2 = "SELECT quantity FROM prices_by_group WHERE id_price = ? and id_group = ? and start_date <= ? and end_date >= ?"; 
					$query2 = $this->db->query($sql2, array($id_price, $group, $date, $date));
					//echo $this->db->last_query();
					$row2 = $query2->row();
					$quantity = $row2->quantity;							
					
				}
				*/
				
				$ficha['precio'] = $quantity;
				//echo "<br>--".$quantity;    exit();	
			} 
		} 
	# Fin de recuperacion de precio	
	
	$ficha['max_vacancies'] = $ficha['groups'] * $ficha['teams'];

	if($ficha['current_round_id'] == '') $ficha['current_round_id'] = 0;


	$ficha['current_vacancies'] = $ficha['max_vacancies'];
	$ficha['used_vacancies'] = 0;
  $sql = "SELECT count(*) as cantidad FROM ranking_teams WHERE id_ranking = ? and status = '1'"; 
	$query = $this->db->query($sql, array($ficha['id']));
	//log_message('debug', 'SQL: '.$this->db->last_query());
	//echo $this->db->last_query();
	if ($query->num_rows() > 0) {
		$row = $query->row();
		$ficha['used_vacancies'] = $row->cantidad;
		$ficha['current_vacancies'] = $ficha['max_vacancies'] - $ficha['used_vacancies'];
	}
	//print("<pre>");print_r($ficha);exit();
	
	return $ficha;

}







################################################
# Recuperar datos de rondas
########################################


public function getRounds($id, $option = 'all' )
	{
		
		$table_name = 'ranking_rounds';
		
		//Build contents query
		$this->db->select($table_name.'.id, '.$table_name.'.round, '.$table_name.'.started, '.$table_name.'.finished, '.$table_name.'.start_date, '.$table_name.'.end_date', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');

		switch($option) {
			case "future":
			
			break;
			
			case "past":
			
			break;
			
			case "current":
				$this->db->where($table_name.'.round IN (SELECT current_round FROM ranking WHERE id = '.$id.')');
			break;
			
			case "unopened":
				$this->db->where($table_name.'.started = 0');			
			break;
			
			case "opened":
				$this->db->where($table_name.'.started = 1');			
			break;
			
			case "closed":
				$this->db->where($table_name.'.closed = 1');			
			break;
			
		}

		$this->db->where($table_name.'.id_ranking = '.$id);
		
		$this->db->order_by($table_name.'.round', 'ASC');

		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		log_message('debug',$this->db->last_query());
		
		$resultado = $query->result_array();
		
		# Si pido la ronda actual, no devuelvo array de arrays.. si no solo el primer elemento
		if(count($resultado) > 0 && $option=='current') $resultado = $resultado[0];
		
		return $resultado;
	

}


##############################################################################
##  Crear un ranking
##############################################################################

  function createMatch($id, $group, $round, $team1, $team2, $date)
  {
  	
  	//$this->db->protect_identifiers('ranking_matchs');
  	$data = array(
             'id_ranking' => $id,
             '`group`' => $group,
             'round' => $round,
             'status' => 1,
             'team1' => $team1,
             'team2' => $team2,
             'estimated_date' => $date,
             'create_user' => $this->session->userdata('user_id'),
             'create_time' => date($this->config->item('log_date_format')),
             'create_ip' => $this->session->userdata('ip_address')
    	);

    $this->db->insert('ranking_matchs', $data);
    log_message('debug',$this->db->last_query());
    return TRUE;
  }





##############################################################################
##  Grabar resultado de un partido
##############################################################################

	function setMatchResult($id_match, $datos) {
		try {
			$table = 'ranking_matchs_result';
			
			$this->db->delete($table, array('id_match' => $id_match)); 
			log_message('debug',$this->db->last_query());
			
			$i = 1;
			foreach($datos['tanteo'] as $tanteo) {
				$data = array(
				   'id_match' => $id_match,
				   'score_part' => $i,
				   'team1_score' => $tanteo['1'],
				   'team2_score' => $tanteo['2'],
				   'create_user' => $this->session->userdata('user_id'),
				   'create_time' => date($this->config->item('log_date_format')),
				   'create_ip' => $this->session->userdata('ip_address')
				);

				$this->db->insert($table, $data);
				log_message('debug',$this->db->last_query());
				$i++;
			}


			$table = "ranking_matchs";
			$data = array(
						'played_date' => $datos['fecha'], 
						'winner' => $datos['vencedor'], 
						'status' => $datos['estado'], 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update($table, $data, array('id' => $id_match));
		  log_message('debug',$this->db->last_query());

			//exit($this->db->last_query());
			return TRUE;
			
		}catch(Exception $e){
			return FALSE;
			log_message('debug','Error en actualizacion de ranking: '.var_export($e));
	  }
	  return TRUE;
	}





##############################################################################
##  Limpiar los partidos de una jornada, para un grupo concreto.. para crearlos de nuevo
##############################################################################

  function cleanMatchs ($id, $group, $round)
  {
  	
  	$this->db->delete('ranking_matchs',array('id_ranking' => $id, 'group' => $group, 'round' => $round));
    log_message('debug',$this->db->last_query());
    return TRUE;
  }




################################################
# Establece nueva posición para un equipo
########################################
function createTeam($ranking, $datos)
{
	$table = "ranking_teams";
	$data = array(
				'id_ranking' => $ranking, 
				'`group`' => $datos['group'], 
				'`order`' => $datos['order'], 
				'main_user' => $datos['main_user'], 
				'description' => $datos['description'], 
				'status' => 1, 
				'sign_date' => date(DATETIME_DB), 
				'modify_time' => date(DATETIME_DB), 
				'modify_user' => $this->session->userdata('user_id'),
				'modify_ip' => $this->session->userdata('ip_address'));
				//print("<pre>");print_r($data);
	$this->db->insert($table, $data);
  log_message('debug',$this->db->last_query());

	$id_team = $this->db->insert_id();
	
	$table = "ranking_teams_members";
	$data = array(
				'id_team' => $id_team, 
				'id_user' => $datos['main_user'], 
				'main' => 1, 
				'status' => 1, 
				'sign_date' => date(DATETIME_DB), 
				'modify_time' => date(DATETIME_DB), 
				'modify_user' => $this->session->userdata('user_id'),
				'modify_ip' => $this->session->userdata('ip_address'));
				//print("<pre>");print_r($data);
	$this->db->insert($table, $data);
  log_message('debug',$this->db->last_query());

	//print("<pre>");print_r($datos);exit();
	if(count($datos['players'])>0) {
		foreach($datos['players'] as $jugador) {
			if($jugador['id_user']!=$datos['main_user']) {
				if($jugador['id_user']!='0' && $jugador['id_user']!='') {
					$id_user = $jugador['id_user'];
					$user_desc = '';
					$user_phone = '';
				} else {
					$id_user = 0;
					$user_desc = $jugador['user_name'];
					$user_phone = $jugador['user_phone'];
				}
				
				$table = "ranking_teams_members";
				$data = array(
							'id_team' => $id_team, 
							'id_user' => $id_user, 
							'user_name' => $user_desc, 
							'user_phone' => $user_phone, 
							'status' => 1, 
							'sign_date' => date(DATETIME_DB), 
							'modify_time' => date(DATETIME_DB), 
							'modify_user' => $this->session->userdata('user_id'),
							'modify_ip' => $this->session->userdata('ip_address'));
				$this->db->insert($table, $data);
				//print("<pre>");print_r($data);
			  log_message('debug',$this->db->last_query());
				
				
			}
		}
	}
	//exit();
	return TRUE;
}






##############################################################################


    function setMonthlyPayment($id_assistant, $last_payd_date, $options = NULL)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'last_payd_date' => date($this->config->item('log_date_format')),
           'last_day_payed' => $last_payd_date,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      
	      $this->db->where('id', $id_assistant);
				$this->db->update('ranking_teams_members', $data);
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }






################################################
# Dar de baja equipo
########################################
function unsubscribeTeam($id_team)
{
	$table = "ranking_teams";
	$data = array(
				'status' => 9, 
				'modify_time' => date(DATETIME_DB), 
				'modify_user' => $this->session->userdata('user_id'),
				'modify_ip' => $this->session->userdata('ip_address'));
	$this->db->update($table, $data, array('id' => $id_team));
  log_message('debug',$this->db->last_query());

	
	$table = "ranking_teams_members";
	$data = array(
				'status' => 9, 
				'modify_time' => date(DATETIME_DB), 
				'modify_user' => $this->session->userdata('user_id'),
				'modify_ip' => $this->session->userdata('ip_address'));
	$this->db->update($table, $data, array('id_team' => $id_team));
  log_message('debug',$this->db->last_query());

	//exit();
	return TRUE;
}





################################################
# Establece nueva posición para un equipo
########################################
function setTeamPosition($ranking, $team, $group, $position, $ronda = null)
{
	$table = "ranking_teams";
	$data = array(
				'`group`' => $group, 
				'`order`' => $position, 
				'modify_time' => date(DATETIME_DB), 
				'modify_user' => $this->session->userdata('user_id'),
				'modify_ip' => $this->session->userdata('ip_address'));
	$this->db->update($table, $data, array('id' => $team, 'id_ranking' => $ranking));
  log_message('debug',$this->db->last_query());
  
  if(isset($ronda)) {
		$table = "ranking_rounds_scoring";
		$data = array(
					'`group`' => $group, 
					'`order`' => $position, 
					'modify_time' => date(DATETIME_DB), 
					'modify_user' => $this->session->userdata('user_id'),
					'modify_ip' => $this->session->userdata('ip_address'));
		$this->db->update($table, $data, array('id_team' => $team, 'id_ranking' => $ranking, 'round' => $ronda));
	  log_message('debug',$this->db->last_query());
	 }
  //echo '<br>'.$this->db->last_query();
}



















###########################################################################################################################

    function add_player($id, $data)
    {
    		$this->CI =& get_instance();
    		$check = 1;
    		//$this->load->model('Reservas_model', 'reserva', TRUE);
				$info=$this->CI->reservas->getBookingInfoById($id);
				//print("<pre>");print_r($data);print_r($info);exit();
				
				foreach($info['signed_users'] as $usuario) {
					if($usuario['id_user'] == $data['id_user']) $check = 0;
				}
				foreach($info['waiting_users'] as $usuario) {
					if($usuario['id_user'] == $data['id_user']) $check = 0;
				}
				
				if($check) {
	        $datos['id_transaction'] = $id;
	        $datos['id_user'] = $data['id_user'];
	        $datos['status'] = $data['status'];
	        $datos['create_user'] = $this->session->userdata('user_id');
	        $datos['create_time'] = date(DATETIME_DB);
	        $datos['create_ip'] = $this->session->userdata('ip_address');
	
	        $this->db->insert('booking_players', $datos);
	        log_message('debug',$this->db->last_query());
	        return NULL;
	      } else {
	      	log_message('debug','Usuario '.$data['id_user'].' ya registrado en el reto '.$id.'. No se le da de alta de nuevo');
	      	return ('Usuario ya registrado previamente en este reto.');
	      }
    }



    function validate_player($id, $user)
    {
			$table = "booking_players";
			$data = array(
						'status' => 1, 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update($table, $data, array('id_transaction' => $id, 'id_user' => $user));
      log_message('debug',$this->db->last_query());
    }



    function pay_player($id, $user)
    {
			$table = "booking_players";
			$data = array(
						'status' => 5, 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update($table, $data, array('id_transaction' => $id, 'id_user' => $user));
      log_message('debug',$this->db->last_query());
    }




    function remove_player($id, $user)
    {
			$table = "booking_players";
			$data = array(
						'status' => 3, 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update($table, $data, array('id_transaction' => $id, 'id_user' => $user));
      log_message('debug',$this->db->last_query());
    }




		//**************************************
    // Registro el reto como notificado satisfactoriamente
    
    function setRetoNotified($id) {
    	# Devuelve nombre de la pista
			
			$table = "booking_shared";
			$data = array(
						'notified' => 1, 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update($table, $data, array('id_transaction' => $id));
			log_message('debug', 'SQL: '.$this->db->last_query());
			//echo $this->db->last_query();

					
    }


##############################################################################

function cancel_reto($id_transaction)
{		
	
	$this->db->delete('booking_shared',array('id_transaction' => $id_transaction));
	log_message('debug', 'SQL: '.$this->db->last_query());
	return ($this->db->affected_rows() >= 1) ? true : false;
	
}







	public function get_global_data($params = "" , $page = "all")
		{
			
		$this->load->model('Pistas_model', 'pistas', TRUE);
			$table_name = 'booking';
			
			//Build contents query
			$this->db->select('booking.id as id, id_booking, booking.id_transaction, id_user, session, id_court, DATE_FORMAT(DATE(booking.date), \'%d-%m-%Y\') as fecha, '.
							'intervalo, `status`, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, '.
							'booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, '.
			 				'booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, '.
							'meta.last_name as last_name,  meta.first_name + \' \' + meta.last_name as complete_name, meta.phone as phone, zz_booking_status.description as status_desc, '.
							'zz_paymentway.description as paymentway_desc, booking.price_light as price_light, booking.price_court as price_court, '.
							'booking_shared.players, booking_shared.price_by_player, booking_shared.gender, booking_shared.low_player_level, booking_shared.high_player_level, DATE_FORMAT(DATE(booking_shared.limit_date), \'%d-%m-%Y\') as limit_date, booking_shared.visible, booking_shared.last_notify, booking_shared.notified', FALSE)->from($table_name);

			$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
			$this->db->join('meta', 'booking.id_user=meta.user_id', 'left outer');
			$this->db->join('booking_shared', 'booking.id_transaction=booking_shared.id_transaction', 'left outer');
			$this->db->join('zz_booking_status', 'booking.status=zz_booking_status.id', 'left outer');
			$this->db->join('zz_paymentway', 'booking.id_paymentway=zz_paymentway.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			$this->db->order_by('id_transaction', $params['orderbyway']);
			
			//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			$record_items = array(); $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
			$contador=0; $first_row = $params ["num_rows"] *  ($params ["page"] - 1); $last_row = ($params ["num_rows"] *  $params ["page"]);
			
			foreach ($query->result() as $row)
			{
				//if($contador <= $first_row) { $contador++; continue; }
				$reserve_interval = $this->pistas->getCourtInterval($row->id_court);
				if($contador > $last_row) { $contador++; break; }
				if($transaccion=="") $transaccion = $row->id_transaction;
				
				//echo $row->id_transaction.' # ' .$transaccion.'<br>';
				if($transaccion != $row->id_transaction && $transaccion!="") {
					#Sólo si se ha cambiado de Id de transacción
					$contador++;
					if($contador > $first_row) {
						$record_items[] = $registro;
					}
					//print("<pre>"); print_r($registro);
					$registro=array();
					$min_time=""; $max_time=""; $precio=0;
					$transaccion = $row->id_transaction;
				}
				// ojo, las imágenes tienen que ser png
				//modificar mas adelante añadiendo un campo en BBDD
				$paint_status = '';
				if ($row->status_desc == '') $paint_status='';
				else  $paint_status = img(array('src'=>'images/'.$row->status_desc.'.png', "align"=>"absmiddle", "border"=>"0", "title"=>$this->lang->line($row->status_desc)));
				
				if($row->no_cost==0) $no_cost='';
				else $no_cost='S&iacute;';
				
				if($row->id_user) $usuario = $row->first_name.' '.$row->last_name.'('.$row->phone.')';
				else $usuario = $row->user_desc.'('.$row->user_phone.')';
				if(trim($usuario)=="") $usuario="No registrado";
				
				$time=$row->intervalo;
				$precio+=$row->price;
				if($min_time=="" || $min_time > $row->intervalo) $min_time = date('H:i', strtotime($time));
				if($max_time=="" || $max_time < $row->intervalo) $max_time = date('H:i', strtotime($time)+($reserve_interval * 60));
				
				# Definicion de los botones
				/*$butt_array=array();
				if($row->status != 9) array_push($butt_array, '<a href=\'#\' onClick="javascript: alert(\'Marcar la reserva como iniciada\');"><img id="activar" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/accept.png\'></a>');
				if($row->status != 9) {
					array_push($butt_array, '<a href=\'#\'  onClick="javascript: alert(\'Modificar la reserva\');" ><img value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/refresh.png\'></a>');
				}
				if($row->status != 9) array_push($butt_array, '<a href=\'#\' onClick="javascript: alert(\'Eliminar la reserva\');"><img border=\'0\' src=\''.$this->config->item('base_url').'images/close.png\'></a>');
				//$buttons=implode(' ', $butt_array);*/
				
				//NUEVOS BOTONES
				//if ($row->status == 9) $button_validate= '<img id="validar" "title"="Validar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/accept.png\'>';
				//else $button_validate = "-";
				if ($row->price_light > 0) 
				{
					$light_desc= 'Si';
					$light_cost = $row->price_light;
				}
				else
				{
					$light_desc= '';
					$light_cost = 0;
				}
				
				$notified = 'No';
				if($row->notified == 1) $notified = 'S&iacute;';
		
				$registro = array(
					'id_transaction' => $row->id_transaction,
					'id_booking' => $row->id_booking,
					'fecha' => date($this->config->item('reserve_date_filter_format') ,strtotime($row->fecha)),
					'inicio' => $min_time,
					'final' => $max_time,
					'status_desc' => $this->lang->line($row->status_desc),
					'court_name' => $row->court_name,
					'paymentway_desc' => $this->lang->line($row->paymentway_desc)!="" ? $this->lang->line($row->paymentway_desc) : '-',
					'user_desc' => $usuario,
					'user_phone' => $usuario,
					'price' => $precio,
					'no_cost' => $no_cost,
					'light_desc' => $light_desc,
					'light_cost' => $light_cost,
					'players' => $row->players,
					'price_by_player' => $row->price_by_player,
					'gender' => $row->gender,
					'low_player_level' => $row->low_player_level,
					'high_player_level' => $row->high_player_level,
					'limit_date' => $row->limit_date,
					'visible' => $row->visible,
					'last_notify' => $row->last_notify,
					'notified' => $notified,
				);	
				//print("<pre>");print_r($row);print("</pre>");
			}
			$record_items[] = $registro;

			return $record_items;
		

		}


  

}
?>