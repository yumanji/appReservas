<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 

class rank_lib
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;


	/**
	 * __construct
	 *
	 * @return void
	 * @author Mathew
	 **/
	/*
	public function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "rank_Lib Class Initialized");
	}
	*/
	
	public function rank_lib()
	{
		$this->CI =& get_instance();
		log_message('debug', "rank_Lib Class Initialized");
	}
	
	/**
	 * Calcula partidos de una jornada (con opci�n de ida y vuelta, pendiente de progamar)
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function calculaJornadas($equipos, $i_v = false)
	{
		if(!is_array($equipos) || count($equipos) == 0) return NULL;
		
		//shuffle ($equipos);	// Mezclamos los equipos para asegurar imparcialidad
		
		if((count($equipos)%2) == 1) array_push($equipos, 'D');	// Si son impares, a�ado el equipo 'D', que es el de jornada de descanso
		
		$jornadas = count($equipos) - 1;
		
		$partidos = array();
		
		# Recorremos las 
		for($i = 1; $i <= $jornadas; $i++) {
			$jornada = array();
			if($i == 1) {
				# Primera jornada
				
				for($j = 1; $j <= (count($equipos) / 2); $j++) {
					$k = $j - 1;
					//echo $j.'-'.$k.'-'.(count($equipos)/2).'<br>';
					if((count($equipos)/2) == $j) $partido = array($equipos[count($equipos)-1], $equipos[($k)]);	// El �ltimo partido de la jornada es especial..
					else $partido = array($equipos[$k], $equipos[count($equipos)-($j+1)]);
					array_push($jornada, $partido);
				}
				# Fin de calculo de la primera jornada

			}	elseif($i > 0 && $i%2 == 0) {
				# Calculo de las jornadas pares
				
				$punto_partida = $partidos[$i-2];	// Cojo la jornada anterior (que es impar)
				//print("<pre>");print_r($punto_partida);exit();
				
				for($j=0; $j < count($punto_partida); $j++) {
					$partido = array();
					if($j==0) $partido = array($punto_partida[$j][1], $punto_partida[count($punto_partida)-1][0]);
					else $partido = array($punto_partida[$j][1], $punto_partida[$j-1][0]);
					array_push($jornada, $partido);
				}				
				
				# Fin de calculo de las jornadas pares				
			}	else {
				# Calculo de las jornadas impares
				
				$punto_partida = $partidos[$i-3];	// Cojo la anterior jornada impar (dos jornadas antes)
				//print("<pre>");print_r($punto_partida);exit();
				
				for($j=0; $j < count($punto_partida); $j++) {
					$partido = array();
					if($j==0) $partido = array($punto_partida[$j][1], $punto_partida[$j+1][1]);
					elseif(($j+1) == count($punto_partida)) $partido = array($punto_partida[$j][0], $punto_partida[$j-1][0]);
					else $partido = array($punto_partida[$j-1][0], $punto_partida[$j+1][1]);
					array_push($jornada, $partido);
				}
				
				# Fin de calculo de las jornadas impares				
			}
			
			array_push($partidos, $jornada);
		}
		
		return($partidos);
		
	}



	/**
	 * Recupera rondas de un ranking
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function getRounds($id)
	{

		$resultado = $this->CI->rank->getRounds($id);

		if(!isset($resultado) || count($resultado) == 0) return NULL;
		
		
		for($i=0; $i<count($resultado); $i++) {
			
			$resultado[$i]['fecha_inicio'] = date($this->CI->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['start_date']));
			$resultado[$i]['fecha_fin'] = date($this->CI->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['end_date']));

			if($resultado[$i]['started']=='') $resultado[$i]['started'] = '0';
			if($resultado[$i]['finished']=='') $resultado[$i]['finished'] = '0';

			if($resultado[$i]['started'] == '1' && $resultado[$i]['finished'] == '0') $resultado[$i]['current'] = '1';
			else $resultado[$i]['current'] = '0';
		}
				
		return $resultado;
	}





	/**
	 * Recupera EQUIPOS de un ranking con su tanteo en la ronda actual
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function getTeams($id, $round = '', $group = '', $no_order = FALSE)
	{
		
		$info = $this->CI->rank->getRanking($id);
		$rondas = $this->getRounds($id);
		foreach($rondas as $ronda_) {
			if($ronda_['id'] == $round) $ronda_activa = $ronda_;
		}
		//print_r($ronda_activa);
		//echo '--'.$info['current_round_id'].'--'.$round;
		if($round == '') {
			
			# Si acabo de crear el ranking y a�n no hay ronda iniciada
			$equipos = $this->CI->rank->get_ActiveTeams($id, $group);
			if(!isset($equipos) || count($equipos) == 0) return NULL;
			for($i=0; $i < count($equipos); $i++) {
				$equipos[$i]['puntos'] = 0;
				$equipos[$i]['PJ'] = 0;
				$equipos[$i]['PG'] = 0;
				$equipos[$i]['PP'] = 0;
				$equipos[$i]['PE'] = 0;
				$equipos[$i]['SG'] = 0;
				$equipos[$i]['SP'] = 0;
				$equipos[$i]['SE'] = 0;
				$equipos[$i]['JG'] = 0;
				$equipos[$i]['JP'] = 0;
				$equipos[$i]['JE'] = 0;
			}
		
		} elseif($info['current_round_id'] == $round && $ronda_activa['started'] == '1' && $ronda_activa['finished'] == '0') {
			//echo 'aa';
			# Si estoy consultando los datos de la jornada actual
			$equipos = $this->getTeamsCalculated($id, $round, $group, $no_order);		
		} else {
			//echo 'bb';
			# Si estoy consultando jornadas anteriores
			$equipos = $this->CI->rank->getRoundScoring($id, $round, $group, $no_order);	
//print('<pre>');print_r($equipos);			
		}
		
		return $equipos;
	
	}
	




	/**
	 * Baja un equipo de posicion en el ranking
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function moveTeamDown($id, $id_team)
	{
		
		$info = $this->CI->rank->getRanking($id);
		$equipos = $this->getTeams($id, $info['current_round_id']);
		//print_r($equipos);
		$equipo = $this->getTeam($id_team);
		
		$equipo1 = array('id_ranking' => $id, 'id' => $id_team, 'group' => '', 'order' => '');
		$equipo2 = array('id_ranking' => $id, 'id' => '', 'group' => '', 'order' => '');
		
		# Recorro los equipos para sacar el orden y grupo del equipo actual
		for($i=0; $i<count($equipos); $i++) {
			if($equipos[$i]['id'] == $id_team) {
				$orden_actual = $equipos[$i]['order'];
				$ronda_actual = $equipos[$i]['group'];
				//$equipo2['id'] = $equipos[$i-1]['id'];
			}
		}
		//echo $ronda_actual.' - '.$orden_actual.'<br>';
		# Recorro los grupos y ordenes para asignarle la posicion anterior y la posterior al 2�
		for($i=1; $i <= $info['groups']; $i++) {
			for($j=1; $j <= $info['teams']; $j++) {
				if($ronda_actual == $i && $orden_actual == $j) {
					$equipo2['group'] = $i;
					$equipo2['order'] = $j;
					continue;
				}
					//echo $i.' - aaaa - '.$j.'<br>';
				
				if($equipo2['group']!='' && $equipo2['order']!='' && $equipo1['order']=='') {
					$equipo1['group'] = $i;
					$equipo1['order'] = $j;
					
				}
			}
		}
		
		
		for($i=0; $i<count($equipos); $i++) {
			if($equipos[$i]['group'] == $equipo1['group'] && $equipos[$i]['order'] == $equipo1['order']) {
				$equipo2['id'] = $equipos[$i]['id'];
			}
		}
		
		//print_r($equipo1);print_r($equipo2);
		//exit();		
		$this->CI->rank->setTeamPosition($id, $id_team, $equipo1['group'], $equipo1['order']);
		if($equipo2['id']!='') $this->CI->rank->setTeamPosition($id, $equipo2['id'], $equipo2['group'], $equipo2['order']);
		
		return true;

	
	}
	

	/**
	 * Sube un equipo de posicion en el ranking
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function moveTeamUp($id, $id_team, $only_up = FALSE)
	{
		$info = $this->CI->rank->getRanking($id);
		//print($id_team."<pre>");print_r($info);//print_r($equipos);
		$equipos = $this->CI->rank->getRoundScoring($id, $info['current_round_id']);
		//exit();
		//$equipos = $this->CI->rank->get_teams(array('where' => "ranking_teams.id_ranking = ".$id." AND ranking_teams.status = 1"));
		//$equipos = $this->getTeams($id, $info['current_round_id'], '', FALSE);
		$equipo = $this->getTeam($id_team);
		
		$equipo1 = array('id_ranking' => $id, 'id' => $id_team);
		$equipo2 = array('id_ranking' => $id, 'id' => '');
		
		# Recorro los equipos para sacar el orden y grupo del equipo actual
		for($i=0; $i<count($equipos); $i++) {
			if($equipos[$i]['id'] == $id_team) {
				$orden_actual = $equipos[$i]['order'];
				$ronda_actual = $equipos[$i]['group'];
				//$equipo2['id'] = $equipos[$i-1]['id'];
			}
		}
		
		# Recorro los grupos y ordenes para asignarle la posicion anterior y la posterior al 2�
		for($i=1; $i <= $info['groups']; $i++) {
			for($j=1; $j <= $info['teams']; $j++) {
				if($ronda_actual == $i && $orden_actual == $j) {
					$equipo1['group'] = $ronda;
					$equipo1['order'] = $orden;
					$equipo2['group'] = $i;
					$equipo2['order'] = $j;
				}
				$orden = $j;
				$ronda = $i;
			}
		}
		
		
		for($i=0; $i<count($equipos); $i++) {
			if($equipos[$i]['group'] == $equipo1['group'] && $equipos[$i]['order'] == $equipo1['order']) {
				$equipo2['id'] = $equipos[$i]['id'];
			}
		}
		
		//print_r($equipo1);print_r($equipo2);
		//exit();
		$this->CI->rank->setTeamPosition($id, $id_team, $equipo1['group'], $equipo1['order'], $info['current_round_id']);
		if(!$only_up && $equipo2['id']!='') $this->CI->rank->setTeamPosition($id, $equipo2['id'], $equipo2['group'], $equipo2['order'], $info['current_round_id']);
		
		return true;

	
	}
	


	# Calculo para la jornada actual	
	public function getTeamsCalculated($id, $round = '0', $group = '', $no_order = FALSE)
	{

		//if($no_order) echo 'no ordenar';
		$equipos = $this->CI->rank->get_ActiveTeams($id, $group);
		if(!isset($equipos) || count($equipos) == 0) return NULL;
		for($i=0; $i < count($equipos); $i++) {
			$equipos[$i]['puntos'] = 0;
			$equipos[$i]['desempate'] = 0;
			$equipos[$i]['PJ'] = 0;
			$equipos[$i]['PG'] = 0;
			$equipos[$i]['PP'] = 0;
			$equipos[$i]['PE'] = 0;
			$equipos[$i]['PD'] = 0;	//Diferencia entre ganados y perdidos
			$equipos[$i]['SG'] = 0;
			$equipos[$i]['SP'] = 0;
			$equipos[$i]['SE'] = 0;
			$equipos[$i]['SD'] = 0;
			$equipos[$i]['JG'] = 0;
			$equipos[$i]['JP'] = 0;
			$equipos[$i]['JE'] = 0;
			$equipos[$i]['JD'] = 0;
		}
		//print("<b>EQUIPOS</b><pre>");print_r($equipos);print("</pre>");
		
		# Estados de los partidos que se considerar�n a efectos de resultados
		$estados = $this->CI->config->item('ranking_completed_matchs_status');
		
		$condiciones = "ranking_matchs.id_ranking = ".$id." AND ranking_matchs.round = ".$round." AND ranking_matchs.status IN (".implode(', ', $estados).")";
		$partidos = $this->CI->rank->get_matchs_data(array('where' => $condiciones), 'all');
		//print("<b>PARTIDOS</b><pre>");print_r($partidos);print("</pre>");
		
		# Puntuaciones de partidos
		$puntuaciones = array(
			'ganado' => $this->CI->config->item('ranking_win_match'),
			'empatado' => $this->CI->config->item('ranking_drawn_match'),
			'perdido' => $this->CI->config->item('ranking_lost_match'),
			'ganadoWO' => $this->CI->config->item('ranking_win_WO_match'),
			'perdidoWO' => $this->CI->config->item('ranking_lost_WO_match')
		);
		
		
		foreach($partidos as $partido) {
			switch($partido['status']) {
				case '5':
					$ganador = $partido['winner'];
					if($partido['team1'] != $ganador) $perdedor = $partido['team1'];
					else $perdedor = $partido['team2'];
					
					for($i=0; $i<count($equipos); $i++) {
						# Datos del equipo ganador
						if($equipos[$i]['id'] == $ganador) {
							$equipos[$i]['PJ']++;
							$equipos[$i]['PG']++;
							$equipos[$i]['puntos']+=$puntuaciones['ganado'];
						}
						
						# Datos del equipo perdedor
						if($equipos[$i]['id'] == $perdedor) {
							$equipos[$i]['PJ']++;
							$equipos[$i]['PP']++;
							$equipos[$i]['puntos']+=$puntuaciones['perdido'];
							
						}
					}
				break;
				case '6':
					$ganador = $partido['winner'];
					if($partido['team1'] != $ganador) $lesionado = $partido['team1'];
					else $lesionado = $partido['team2'];
					
					for($i=0; $i<count($equipos); $i++) {
						# Datos del equipo ganador
						if($equipos[$i]['id'] == $ganador) {
							$equipos[$i]['PJ']++;
							$equipos[$i]['PG']++;
							$equipos[$i]['puntos']+=$puntuaciones['ganadoWO'];
						}
						
						# Datos del equipo perdedor
						if($equipos[$i]['id'] == $lesionado) {
							$equipos[$i]['PJ']++;
							$equipos[$i]['PP']++;
							$equipos[$i]['puntos']+=$puntuaciones['perdidoWO'];
							
						}
					}
				
				break;
				case '8':
					$ganador = $partido['winner'];
					if($partido['team1'] != $ganador) $no_presentado = $partido['team1'];
					else $no_presentado = $partido['team2'];
					
					for($i=0; $i<count($equipos); $i++) {
						# Datos del equipo ganador
						if($equipos[$i]['id'] == $ganador) {
							$equipos[$i]['PJ']++;
							$equipos[$i]['PG']++;
							$equipos[$i]['puntos']+=$puntuaciones['ganadoWO'];
						}
						
						# Datos del equipo perdedor
						if((($equipos[$i]['id'] == $partido['team1'] || $equipos[$i]['id'] == $partido['team2']) && $ganador == '0' ) || $equipos[$i]['id'] == $no_presentado  ) {
							$equipos[$i]['PJ']++;
							$equipos[$i]['PP']++;
							$equipos[$i]['puntos']+=$puntuaciones['perdidoWO'];
							
						}
					}
				
				break;
			}
			
			foreach($partido['resultado2'] as $set => $tantos) {
				for($i=0; $i<count($equipos); $i++) {
					# Datos del equipo ganador
					if($equipos[$i]['id'] == $partido['team1']) {
						$equipos[$i]['JG'] = $equipos[$i]['JG'] + $tantos['team1'];
						$equipos[$i]['JP'] = $equipos[$i]['JP'] + $tantos['team2'];
						if($tantos['team1'] > $tantos['team2']) $equipos[$i]['SG']++;
						elseif($tantos['team1'] < $tantos['team2']) $equipos[$i]['SP']++;
						elseif($tantos['team1']!=0)  $equipos[$i]['SE']++;
					}
					if($equipos[$i]['id'] == $partido['team2']) {
						$equipos[$i]['JG'] = $equipos[$i]['JG'] + $tantos['team2'];
						$equipos[$i]['JP'] = $equipos[$i]['JP'] + $tantos['team1'];
						if($tantos['team1'] > $tantos['team2']) $equipos[$i]['SP']++;
						elseif($tantos['team1'] < $tantos['team2']) $equipos[$i]['SG']++;
						elseif($tantos['team1']!=0) $equipos[$i]['SE']++;
					}
				}
				
			}
			
		}
		
		for($i=0; $i<count($equipos); $i++) {
			$equipos[$i]['PD'] = $equipos[$i]['PG'] - $equipos[$i]['PP'];
			$equipos[$i]['SD'] = $equipos[$i]['SG'] - $equipos[$i]['SP'];
			$equipos[$i]['JD'] = $equipos[$i]['JG'] - $equipos[$i]['JP'];
		}
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		
		if($no_order) { 
			$equipos = $this->CI->app_common->ordenar_array($equipos, 'group', SORT_ASC, 'order', SORT_ASC);
			//echo 'aa';
		} else {
			$equipos = $this->CI->app_common->ordenar_array($equipos, 'group', SORT_ASC, 'puntos', SORT_DESC, 'PG', SORT_DESC, 'SD', SORT_DESC, 'SG', SORT_DESC, 'JD', SORT_DESC, 'JG', SORT_DESC);
			$grupos = $this->CI->app_common->array_column($equipos, 'group', 'group');
			//print_r($grupos);
			//echo 'bb';
			# Voy a ir recorriendo grupo por grupo buscando si hay empates a dos en el primer o ultimo lugar para rellenar un campo 'desempate' que permita saber quien va primero y quien segundo de esas parejas emparejadas a dos (se hace por el ganador del cruce directo)
			foreach($grupos as $grupo) {
				$implicados = array(); $puntuacion_mayor = 0; $puntuacion_menor=0; $primeros = array(); $ultimos = array(); $id_anterior = 0; $puntos_anterior = 0; $agrupacion_puntos = array();
				foreach($equipos as $equipo) {
					if($equipo['group'] == $grupo) {
						array_push($implicados, $equipo);	// Relleno un array con los datos de equipos de este grupo
						
						#Relleno el array que agrupa a los equipos con la misma puntuación, para los desempates
						if(!isset($agrupacion_puntos[$equipo['puntos']])) $agrupacion_puntos[$equipo['puntos']] = array();
						array_push($agrupacion_puntos[$equipo['puntos']], $equipo['id']);
						
						// Si es el equipo con m�s puntos, grabo cuantos puntos son y grabo que es el equipo m�s alto. Si tiene los mismos puntos que el mayor, lo a�ado al array de $primeros
						if($equipo['puntos'] > $puntuacion_mayor) { $puntuacion_mayor = $equipo['puntos']; $primeros = array($equipo['id']); }
						elseif($equipo['puntos'] == $puntuacion_mayor) array_push($primeros, $equipo['id']);
						
						// Si es el equipo con menos puntos, grabo cuantos puntos son y grabo que es el equipo m�s bajo. Si tiene los mismos puntos que el menor, lo a�ado al array de $ultimos
						if($equipo['puntos'] < $puntuacion_menor) { $puntuacion_menor = $equipo['puntos']; $ultimos = array($equipo['id']); }
						elseif($equipo['puntos'] == $puntuacion_menor) array_push($ultimos, $equipo['id']);
					
						#Actualizo las variables para comparar
						$id_anterior = $equipo['id']; $puntos_anterior = $equipo['puntos'];
					}
				}
				
				//echo '<pre>'; print_r($equipos); exit();
				
				# Desempates de dos equipos!
				foreach($agrupacion_puntos as $puntuacion => $equipos_puntuados) {
					# Recorro el array de puntuaciones y cuando detecto qyehay un empate a dos, gestiono el desempate
					if(count($equipos_puntuados) == 2) { 
						//echo '<br>Equipos empatados:'; print_r($equipos_puntuados); 
						
						# Recorro los equipos para saber la posición actual del equipo que va delante y del que va detrás
						$pos_ganador = 0; $pos_perdedor = 0;
						for($i=0; $i<count($equipos); $i++) {
							if(in_array($equipos[$i]['id'], $equipos_puntuados)) {
								if($pos_ganador == 0) $pos_ganador = $equipos[$i]['order'];
								else  $pos_perdedor = $equipos[$i]['order'];
							}
						}
						
						foreach($partidos as $partido) {
							# Busco el enfrentamiento directo
							if(in_array($partido['team1'], $equipos_puntuados) && in_array($partido['team2'], $equipos_puntuados)) {
								for($i=0; $i<count($equipos); $i++) {
									if($equipos[$i]['id'] == $partido['winner']) { /*echo '<br>El equipo '.$equipos[$i]['description'].' sube posicion a '.$pos_ganador;*/ $equipos[$i]['order'] = $pos_ganador;  $equipos[$i]['desempate'] = 1; }
									elseif(in_array($equipos[$i]['id'], $equipos_puntuados) && $partido['winner']!='' && $equipos[$i]['id'] != $partido['winner']) { /*echo '<br>El equipo '.$equipos[$i]['description'].' baja posicion a '.$pos_perdedor;*/ $equipos[$i]['order'] = $pos_perdedor;  $equipos[$i]['desempate'] = 0; }
								}
								//print_r($equipos[$i]); exit();
							}
						}
					}
				}
				
				/*
				if(count($primeros) == 2) { 
					//echo 'Hay dos empatados para el primer puesto...<br>'; echo '<pre>'; print_r($primeros); 
					foreach($partidos as $partido) {
						# Busco el enfrentamiento directo
						if(in_array($partido['team1'], $primeros) && in_array($partido['team2'], $primeros)) {
							for($i=0; $i<count($equipos); $i++) {
								if($equipos[$i]['id'] == $partido['winner']) {  $equipos[$i]['order'] = 1;  $equipos[$i]['desempate'] = 1; }
								if(in_array($equipos[$i]['id'], $primeros) && $partido['winner']!='' && $equipos[$i]['id'] != $partido['winner']) {   $equipos[$i]['order'] = 2; }
							}
							//print_r($equipos[$i]); exit();
						}
					}
				}
				if(count($ultimos) == 2) { 
					//echo 'Hay dos empatados para el ultimo puesto...<br>'; echo '<pre>'; print_r($ultimos); 
					foreach($partidos as $partido) {
						# Busco el enfrentamiento directo
						if(in_array($partido['team1'], $ultimos) && in_array($partido['team2'], $ultimos)) {
							for($i=0; $i<count($equipos); $i++) {
								if($equipos[$i]['id'] == $partido['winner']) {  $equipos[$i]['order'] = (count($implicados) -1); $equipos[$i]['desempate'] = 1; }
								if(in_array($equipos[$i]['id'], $ultimos) && $partido['winner']!='' && $equipos[$i]['id'] != $partido['winner']) {  $equipos[$i]['order'] = count($implicados); }
							}
							//print_r($equipos[$i]); exit();
						}
					}
				
				}
			*/
			
				/*		
					if($grupo == 2) {
					$implicados = array();
								foreach($equipos as $equipo) {
						if($equipo['group'] == $grupo) {
							array_push($implicados, $equipo);	// Relleno un array con los datos de equipos de este grupo
						}
						}
						print("<pre>");print_r($implicados); exit(); 
					}
				*/
				
				

			}
//exit();
			
			# Reordeno de nuevo metiendo el campo 'desempate' en el ordenamiento, para que dentro de los que est�n empatados a puntos y hayan entrado en el calculo anterior (es decir.. los que solo tenian dos empatados) se tenga en cuenta primero el desempate antes que los partidos ganados.. 
			if(!$no_order) $equipos = $this->CI->app_common->ordenar_array($equipos, 'group', SORT_ASC, 'puntos', SORT_DESC, 'desempate', SORT_DESC, 'PG', SORT_DESC, 'SD', SORT_DESC, 'SG', SORT_DESC, 'JD', SORT_DESC, 'JG', SORT_DESC);
				
				
				
			$grupo_ = 1; $pos_ = 1; $id_ = 0;
			foreach($equipos as $equipo) {
				if($grupo_ != $equipo['group']) $pos_ = 1;
				$grupo_ = $equipo['group'];
					//echo '<br>poniendo equipo '.$equipo['description'].' en posicion '.$pos_;
				$this->CI->rank->setTeamPosition($id, $equipo['id'], $equipo['group'], $pos_);
				$equipos[$id_]['order'] =  $pos_;
				$pos_++; $id_++;
			}
		
		
		}
		//print("<b>EQUIPOS CON RESULTADOS</b><pre>");print_r($equipos);print("</pre>");exit();
			//exit('aaaa');	
			
		return $equipos;
	}




	/**
	 * Recupera rondas de un ranking
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function createRounds($info)
	{
		if(!isset($info) || count($info)==0) return NULL;

		$fecha_inicio = strtotime($info['start_date']);
		//echo 'inicio: '.$info['start_date'].' - '.$fecha_inicio.'<br>';
		$fecha_fin = strtotime($info['end_date']);
		//echo 'fin: '.$info['end_date'].' - '.$fecha_fin.'<br>';
		
		$duracion_total = $fecha_fin - $fecha_inicio;
		$duracion_total_dias = intval($duracion_total/ (60*60*24));
		
		//echo 'Duracion: '.$duracion_total.' / dias: '.strval($duracion_total_dias).'<br>';
		$rondas = intval($info['rounds']);
		//echo 'rondas: '.$info['rounds'].'<br>';
		$duracion_ronda = intval($info['round_duration']);
		//echo 'Duracion/ronda: '.$info['round_duration'].'<br>';
		
		$modo = '';
		if($rondas != '0') $modo = 'rounds';
		if($duracion_ronda != '0') $modo = 'duration';
		if($modo=='') return NULL;
		
		
		switch($modo) {
			case 'rounds':
				$time_jornada = $duracion_total / $rondas;
				$duracion_ronda = intval($time_jornada/ (60*60*24));
				//echo 'Duracion ronda: '.$time_jornada.' / en dias: '.strval($duracion_ronda).'<br>'	;		
			break;
			
			case 'duration':
				$rondas = intval($duracion_total_dias / $duracion_ronda);
			
				//echo 'Rondas: '.$rondas.' <br>';			
			break;			
		}
		
		$jornadas = array();
		$fecha_actual = $fecha_inicio;
		for($i=1; $i <= $rondas; $i++) {
			//echo 'Ronda numero '.$i.'<br>';
			$ronda_trabajo = array();
			$ronda_trabajo['ronda'] = $i;
			$ronda_trabajo['initial'] = $fecha_actual;
			$ronda_trabajo['start_date'] = date($this->CI->config->item('date_db_format'), $fecha_actual);
			$ronda_trabajo['fecha_inicio'] = date($this->CI->config->item('reserve_date_filter_format'), $fecha_actual);
			$ronda_trabajo['end'] = $fecha_actual + (($duracion_ronda-1)*60*60*24);
			if($i == $rondas) $ronda_trabajo['end'] = $fecha_fin;
			$ronda_trabajo['end_date'] = date($this->CI->config->item('date_db_format'), $ronda_trabajo['end']);
			$ronda_trabajo['fecha_fin'] = date($this->CI->config->item('reserve_date_filter_format'), $ronda_trabajo['end']);
			//echo $ronda_trabajo['end'];
			array_push($jornadas, $ronda_trabajo );
			$fecha_actual = $ronda_trabajo['end'] + (60*60*24);
		}
		//$inicio
		//print("<pre>");print_r($jornadas);print("</pre>");exit();
		return $jornadas;
	}
	


	/**
	 * Crea array con los partidos asociados a un grupo, con las fechas estimadas de juego
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function scheduleMatchs($info, $group, $jornada, $matchs)
	{
		if(!isset($info) || count($info)==0) return NULL;
		if(!isset($matchs) || count($matchs)==0) return NULL;
		//print("<pre>");print_r($info);print('Grupo: '.$group.'<br>Jornada: '.$jornada.'<br>');print_r($matchs);print("</pre>");
		
		foreach($info['rondas'] as $ronda) {
			if($ronda['id'] == $jornada) {
				$fecha_inicio = strtotime($ronda['start_date']);
				//echo 'inicio: '.$ronda['start_date'].' - '.$fecha_inicio.'<br>';
				$fecha_fin = strtotime($ronda['end_date']);
				//echo 'fin: '.$ronda['end_date'].' - '.$fecha_fin.'<br>';
				
				if(!isset($fecha_inicio) || !isset($fecha_fin)) return null;
				
				$duracion_total = $fecha_fin - $fecha_inicio;
				$duracion_total_dias = intval($duracion_total/ (60*60*24));
				
				//echo 'Duracion: '.$duracion_total.' / dias: '.strval($duracion_total_dias).'<br>';
				$encuentros = count($matchs);
				//echo 'encuentros: '.$encuentros.'<br>';
				$duracion_ronda = intval($info['round_duration']);
				//echo 'Duracion/ronda: '.$info['round_duration'].'<br>';
				
				$time_jornada = $duracion_total / $encuentros;
				$duracion_ronda = intval($time_jornada/ (60*60*24));
				//echo 'Duracion ronda: '.$time_jornada.' / en dias: '.strval($duracion_ronda).'<br>'	;		
		
				
				$jornadas = array();
				$fecha_actual = $fecha_inicio;
				for($i=1; $i <= $encuentros; $i++) {
					//echo 'Ronda numero '.$i.'<br>';
					$ronda_trabajo = array();
					$ronda_trabajo['id_ranking'] = $info['id'];
					$ronda_trabajo['group'] = $group;
					$ronda_trabajo['round'] = $jornada;
					$ronda_trabajo['estimated_date'] = date($this->CI->config->item('date_db_format'), $fecha_actual);
					$ronda_trabajo['end'] = $fecha_actual + (($duracion_ronda-1)*60*60*24);
					if($i == $encuentros) $ronda_trabajo['end'] = $fecha_fin;
					//echo $ronda_trabajo['end'];
					foreach($matchs[$i-1] as $jornadita) {
						//print_r($jornadita);
						$ronda_trabajo['team1'] = $jornadita[0];
						$ronda_trabajo['team2'] = $jornadita[1];
						
						# Descarto los partidos de descanso
						if($ronda_trabajo['team1']!='D' && $ronda_trabajo['team2']!='D') array_push($jornadas, $ronda_trabajo );
				}
					
					$fecha_actual = $ronda_trabajo['end'] + (60*60*24);
				}			
				
				
				
				
				
			}
		}

		//$inicio
		//print("JORNADAS!!!!!<pre>");print_r($jornadas);print("</pre>");exit();
		return $jornadas;
	}
	

	
	/**
	 * Recupera datos de partidos para una jornada
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function getMatchs($ranking, $round, $add_params)
	{
			$this->CI->load->model('Ranking_model', 'rank', TRUE);

		//exit($ranking.'aa');
		$where = '';

		//$req_param = array ();
		$req_param = array (

				"orderby" => $this->CI->input->post( "sidx", TRUE ),
				"orderbyway" => $this->CI->input->post( "sord", TRUE ),
				"page" => $this->CI->input->post( "page", TRUE ),
				"num_rows" => $this->CI->input->post( "rows", TRUE ),
				"search" => $this->CI->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->CI->input->post( "searchField", TRUE ),
				"search_operator" => $this->CI->input->post( "searchOper", TRUE ),
				"search_str" => $this->CI->input->post( "searchString", TRUE ),
		);

		if($req_param['search']=='true' && $req_param['search_field']!='' && $req_param['search_operator']!='' && $req_param['search_str']!='') {
			if(trim($where)!="") $where .= ' AND ';
			
			$where .= $req_param['search_field'];
			switch($req_param['search_operator']) {
				case 'cn':
					$where .=' LIKE \'%'.$req_param['search_str'].'%\' '; 
				break;
			}	
		}
		
		if(isset($add_params)) {
			switch($add_params) {
				case "future":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(booking.date > '".date($this->config->item('date_db_format'))."' OR (booking.date = '".date($this->config->item('date_db_format'))."' AND booking.intervalo >= '".date($this->config->item('hour_db_format'))."'))";
				break;
				default:
				#asumo que es para usuarios..
					if(trim($where)!="") $where .= ' AND ';
					$where .= "ranking_matchs.id_ranking IN (select id_ranking from ranking_teams, ranking_teams_members WHERE ranking_teams.id_ranking = ".$ranking." AND ranking_teams.id = ranking_teams_members.id_team AND ranking_teams_members.id_user = ".$add_params.") AND ranking_matchs.group IN (select ranking_teams.group from ranking_teams, ranking_teams_members WHERE ranking_teams.id_ranking = ".$ranking." AND ranking_teams.id = ranking_teams_members.id_team AND ranking_teams_members.id_user = ".$add_params.")";
				break;
			}
		}
		
		
		if(trim($where)!="") $where .= ' AND ';
		$where .= 'ranking_matchs.id_ranking = '.$ranking;
		
		if(trim($where)!="") $where .= ' AND ';
		$where .= 'ranking_matchs.round = '.$round;
		
		$req_param['where'] = $where;
		if(isset($add_params) && is_array($add_params) && isset($add_params['where']) && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		
		$data->page = $this->CI->input->post( "page", TRUE );


		//print("<pre>");print_r($record_items);exit();
		$data->records = count ($this->CI->rank->get_matchs_data($req_param,"all"));
		if(isset($data->records) && $data->records!=0) $data->total = ceil ($data->records / $req_param['num_rows'] );
		else $data->total = 0;
		$records = $this->CI->rank->get_matchs_data ($req_param, 'none');

		for($i=0; $i<count($records); $i++) {
			
			if($records[$i]['played_date']) $records[$i]['fecha'] = date($this->CI->config->item('reserve_date_filter_format'), strtotime($records[$i]['played_date']));
			elseif($records[$i]['estimated_date'])  $records[$i]['fecha'] = date($this->CI->config->item('reserve_date_filter_format'), strtotime($records[$i]['estimated_date']));
			else $records[$i]['fecha'] = '';
			
			if($records[$i]['winner']!='' && $records[$i]['ganador']=='') $records[$i]['ganador'] = 'No identificado';
			elseif($records[$i]['winner']=='') $records[$i]['ganador'] = ' - ';
			//$resultado[$i]['final'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado[$i]['end_date']));
			
			if(trim($records[$i]['phone1'])!='') $records[$i]['equipo1'] .= ' ('.$records[$i]['phone1'].')';
			if(trim($records[$i]['phone2'])!='') $records[$i]['equipo2'] .= ' ('.$records[$i]['phone2'].')';
		}



		$data->rows = $records;	
	
		return $data;

	}



	
	/**
	 * Recupera datos de partidos para una jornada
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function getMatchInfo($id_match)
	{

		//$req_param = array ();
		$req_param = array (

				"orderby" => $this->CI->input->post( "sidx", TRUE ),
				"orderbyway" => $this->CI->input->post( "sord", TRUE ),
				"page" => $this->CI->input->post( "page", TRUE ),
				"num_rows" => $this->CI->input->post( "rows", TRUE ),
				"search" => $this->CI->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->CI->input->post( "searchField", TRUE ),
				"search_operator" => $this->CI->input->post( "searchOper", TRUE ),
				"search_str" => $this->CI->input->post( "searchString", TRUE ),
		);

		$where = 'ranking_matchs.id = '.$id_match;
		
		$req_param['where'] = $where;

		//print("<pre>");print_r($record_items);exit();
		$resultado = $this->CI->rank->get_matchs_data($req_param,"all");
		$partido = array();
		if(count($resultado)>0) $partido = $resultado[0];
	
		$tanteo = $this->CI->rank->get_matchs_result_data($id_match);
		$partido['tanteo'] = $tanteo;
		
		return $partido;

	}	
	
	

	
	/**
	 * Dar de alta un equipo nuevo en el ranking
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function newTeam($id, $datos)
	{
		//print("<pre>");print_r($datos);exit();
		$jugadores = $this->CI->rank->get_teams_members(array('where' => "ranking.id = '".$id."' and ranking_teams.status not in (9)"));
		$test = 1;
		foreach($jugadores as $jugador) {
			if($datos['main_user'] == $jugador['id_user']) $test = 0;
		}
		//print($test.' - '.$datos."<pre>");print_r($jugadores);exit();

		if($test==1) $this->CI->rank->createTeam($id, $datos);
		else return FALSE;
		
		return TRUE;

	}	

	
	/**
	 * recuperar info de un equipo
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function getTeam($id)
	{
		//print("<pre>");print_r($datos);exit();
		$equipos = $this->CI->rank->get_teams(array('where' => "ranking_teams.id = '".$id."'"));
		$equipo = $equipos[0];
		$jugadores = $this->CI->rank->get_teams_members(array('where' => "ranking_teams.id = '".$id."'"));
		$equipo['players'] = $jugadores;
		return $equipo;
		$test = 1;
		foreach($jugadores as $jugador) {
			if($datos['main_user'] == $jugador['id_user']) $test = 0;
		}

		if($test==1) $this->CI->rank->createTeam($id, $datos);
		
		return TRUE;

	}	
	



	/**
	 * aplica las promociones de subida y bajada de equipos en sus grupos
	 *
	 * @return boolean
	 * @author Mathew
	 **/
	public function runPromotion($ranking, $listado, $ronda)
	{
		$debug = FALSE;
		$resultado = array();
		
		# Recorro los grupos
		foreach($listado as $id => $equipos) {
			# comprobacion de que hay que bajar equipos... 
			$bajar = TRUE;
			if($debug) echo '<br> TRabajando con el equipo '.$id;
			if($listado[$id][1]['id'] == 0 || !isset($listado[$id+1]) || $listado[$id+1][1]['id'] == 0) {
				$bajar = FALSE;
			}
			if($bajar) {
				if($debug) echo '<br> el grupo '.$id.' puede bajar';
				foreach($equipos as $idt => $equipo) {
					
					/*
					# Codigo para bajar al ultimo a dos grupos m�s abajo
					if($idt == count($equipos)) {
						$pos_destino = 2;
						if($id != (count($listado)-2) || $id != (count($listado)-1)) $grupo_destino = $equipo['group'] + 2;
						else {
							$grupo_destino = $equipo['group'] + 1;
							//$pos_destino = 2;
						}
												
						if($debug) echo '<br> bajar el equipo '.$equipo['id'].' ('.$equipo['description'].')  dos grupos.. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino);
					}
					*/
					# codigo para bajar al ultimo un grupo m�s abajo
					if($idt == (count($equipos))) {
						$pos_destino = 1;
						$grupo_destino = $equipo['group'] + 1;
						if($debug) echo '<br> bajar el equipo '.$equipo['id'].' ('.$equipo['description'].')  un grupo.. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino, $ronda);
					}
				}
			}
			
			# comprobacion de que hay que subir equipos
			$subir = TRUE;
			if($id == 1 || $listado[$id][1]['id'] == 0) $subir = FALSE;

			if($subir) {
				if($debug) echo '<br> el grupo '.$id.' puede subir'; 
				foreach($equipos as $idt => $equipo) {
					/*
					# Codigo para subir al primer dos grupos m�s arriba
					if($idt == 1) {
						$pos_destino = 3;
						if($id > 3) $grupo_destino = $equipo['group'] - 2;
						else  {
							$grupo_destino = $equipo['group'] - 1;
							//$pos_destino = 3;
						}
						
						if($grupo_destino <= 0) $grupo_destino = 1;
						if($debug) echo '<br> subir el equipo '.$equipo['id'].' ('.$equipo['description'].')  dos grupos. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino);
					}*/
					
					# Codigo para subir al primer un grupo m�s arriba
					if($idt == 1) {
						$grupo_destino = $equipo['group'] - 1;
						if($grupo_destino <= 0) $grupo_destino = 1;
						$pos_destino = 4;
						
						if($debug) echo '<br> subir el equipo '.$equipo['id'].' ('.$equipo['description'].') un grupo. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino, $ronda);
					}
				}
			}
			
		}		
		
		//exit();
		return TRUE;

	}	
	





	/**
	 * aplica las promociones de subida y bajada de equipos en sus grupos
	 *
	 * @return boolean
	 * @author Mathew
	 **/
	public function runPromotion_vieja($ranking, $listado, $ronda)
	{
		$debug = TRUE;
		$resultado = array();
		
		# Recorro los grupos
		foreach($listado as $id => $equipos) {
			# comprobacion de que hay que bajar equipos... 
			$bajar = TRUE;
			if($debug) echo '<br> TRabajando con el equipo '.$id;
			if($listado[$id][1]['id'] == 0 || !isset($listado[$id+1]) || $listado[$id+1][1]['id'] == 0) {
				$bajar = FALSE;
			}
			if($bajar) {
				if($debug) echo '<br> el grupo '.$id.' puede bajar';
				foreach($equipos as $idt => $equipo) {
					if($idt == count($equipos)) {
						$pos_destino = 2;
						if($id != (count($listado)-2) || $id != (count($listado)-1)) $grupo_destino = $equipo['group'] + 2;
						else {
							$grupo_destino = $equipo['group'] + 1;
							//$pos_destino = 2;
						}
												
						if($debug) echo '<br> bajar el equipo '.$equipo['id'].' ('.$equipo['description'].')  dos grupos.. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino);
					}
					if($idt == (count($equipos)-1)) {
						$pos_destino = 1;
						$grupo_destino = $equipo['group'] + 1;
						if($debug) echo '<br> bajar el equipo '.$equipo['id'].' ('.$equipo['description'].')  un grupo.. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino);
					}
				}
			}
			
			# comprobacion de que hay que subir equipos
			$subir = TRUE;
			if($id == 1 || $listado[$id][1]['id'] == 0) $subir = FALSE;

			if($subir) {
				if($debug) echo '<br> el grupo '.$id.' puede subir'; 
				foreach($equipos as $idt => $equipo) {
					if($idt == 1) {
						$pos_destino = 3;
						if($id > 3) $grupo_destino = $equipo['group'] - 2;
						else  {
							$grupo_destino = $equipo['group'] - 1;
							//$pos_destino = 3;
						}
						
						if($grupo_destino <= 0) $grupo_destino = 1;
						if($debug) echo '<br> subir el equipo '.$equipo['id'].' ('.$equipo['description'].')  dos grupos. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino);
					}
					if($idt == 2) {
						$grupo_destino = $equipo['group'] - 1;
						if($grupo_destino <= 0) $grupo_destino = 1;
						$pos_destino = 4;
						
						if($debug) echo '<br> subir el equipo '.$equipo['id'].' ('.$equipo['description'].') un grupo. del '.$equipo['group'].' al '.$grupo_destino.' a posicion '.$pos_destino;
						$this->CI->rank->setTeamPosition($ranking, $equipo['id'], $grupo_destino, $pos_destino);
					}
				}
			}
			
		}		
		
		exit();
		return TRUE;

	}	
	





 ##############
 #
 # Registra el pago de la cuota de usuario
 #
 ####################
	public function pay_user_quota($asistente, $options = NULL) {
		
		/*
		if(isset($options['object']) && is_array($options['object'])) $assistant=$options['object'];
		else $assistant=get_object_vars ($this->CI->calendario->getAssistantInfo($asistente));
		*/
		$assistant = $this->CI->rank->get_teams_members(array('where'=>"ranking.started = 1 AND ranking_teams_members.id = ".$asistente." "));
		$assistant = $assistant[0];
		$ranking = $this->CI->rank->get_data(array('where'=>"ranking.id = ".$assistant['id_ranking']));
		$ranking = $ranking[0];

		$equipo = $this->CI->rank->get_teams(array('where' => 'ranking_teams.id = '.$assistant['id_team']));		
		$equipo = $equipo[0];
		//print('equipo<pre>');print_r($equipo);

		$assistant['grupo'] = $this->CI->users->getUserGroup($assistant['id_user']);
		if(!isset($assistant['grupo']) || $assistant['grupo']=='') $assistant['grupo'] = 9;
		$assistant['quota'] = $this->CI->app_common->getPriceValue($ranking['price'], array('group' => $assistant['grupo']));

		
		//$assistant_info = $this->CI->users->get_user($assistant['id_user']);
		//print('usuario<pre>');print_r($assistant_info);
		//$info = $this->CI->calendario->getCalendarByRange($assistant['id_lesson']);
		//print('curso<pre>');print_r($info);
//exit();

		if(!isset($quantity) || $quantity == '') $quantity = 1;	// Mensualidades por defecto a pagar
		
		# Si se ha marcado como dado de alta el usuario, seguimos..
		if($assistant['sign_date'] != '') {

			if(!isset($assistant['last_day_payed']) || $assistant['last_day_payed']=="") {
				$ultima_fecha = $assistant['sign_date'];
				$dia = date('d', strtotime($ultima_fecha));
				$dia_de_pago = '01';
				if($dia < $dia_de_pago) {
					$trozos = split('-', $ultima_fecha);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
				} elseif($dia > $dia_de_pago) {
					$fecha_siguiente = date($this->CI->config->item('log_date_format'), strtotime($assistant['sign_date'].' +1 month'));
					$trozos = split('-', $fecha_siguiente);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
				} else {
					$last_payd_date = date($this->CI->config->item('log_date_format'), strtotime($assistant['sign_date'].' +1 month'));
				}
				
				//echo "1<br>";
			} else {
				$last_payd_date = date($this->CI->config->item('log_date_format'), strtotime($assistant['last_day_payed'].' +1 month'));
				//echo "2<br>";
			}		
			//echo '<br>... las_payd_date: '.$last_payd_date;
			//exit();
			$paymentway = 4;	//Forma pago temporal.. por banco

			if(isset($quantity) && $quantity!=0 && $quantity!="" && isset($paymentway) && $paymentway!=0 && $paymentway!="") {
					/*
					$cuota = $assistant['quota'];
					$pay_amount_tmp = $cuota * $quantity;
					if($assistant->discount_type == '%') $pay_amount = $pay_amount_tmp - ($pay_amount_tmp * $assistant->discount / 100);
					else $pay_amount = $pay_amount_tmp - $assistant->discount;
					*/
					$pay_amount = $assistant['quota'];
					//exit('cuota:'.$pay_amount);
					if($this->CI->rank->setMonthlyPayment($asistente, $last_payd_date)) $this->CI->session->set_userdata('info_message', 'Pago hasta el '.date($this->CI->config->item('reserve_date_filter_format'), strtotime($last_payd_date)).' realizado');
					else {
						return FALSE;
					}
	//exit();
					if($assistant['id_user'] != '' && $assistant['id_user'] != 0) {
						$user_desc = $assistant['first_name'].' '.$assistant['last_name'];
						$description = "Cuota mensual del ranking '".$ranking['description']."', hasta el ".$last_payd_date;
					} else {
						# En caso de que el usuario insscrito como jugador sea an�nimo
						$user_desc = $equipo['main_user_description'];
						$assistant['id_user'] = $equipo['main_user'];
						$description = "Cuota mensual (en nombre de ".$assistant['main_user_description'].") del ranking '".$ranking['description']."', hasta el ".$last_payd_date;
					}
				
					$estado = 9;
					if($paymentway == 4) $estado = 2;
					if($pay_amount == 0) $estado = 9;	// Si la cuota resultante es '0', se pone siempre como pagado para evitar pagos raros pendientes en remesas
	
					$this->CI->load->model('Payment_model', 'pagos', TRUE);
					$this->CI->pagos->id_type=7; //Ranking
					$this->CI->pagos->id_element=$this->CI->session->userdata('session_id');
					$this->CI->pagos->id_transaction='r-'.$ranking['id'].'-'.$assistant['id_team'].'-'.$assistant['id'].'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
					$this->CI->pagos->id_user=$assistant['id_user'];
					$this->CI->pagos->desc_user=$user_desc;
					$this->CI->pagos->id_paymentway = $paymentway;
					$this->CI->pagos->status=$estado;
					$this->CI->pagos->quantity = $pay_amount;
					$this->CI->pagos->datetime=date($this->CI->config->item('log_date_format'));
					$this->CI->pagos->description = $description;
					$this->CI->pagos->create_user=$this->CI->session->userdata('user_id');
					$this->CI->pagos->create_time=date($this->CI->config->item('log_date_format'));
					
					$this->CI->pagos->setPayment();
				
				//exit('AAAAAAAAAAAAAAA');
					return TRUE;
				} else return FALSE;

		
		} else return FALSE; // no est� marcado como dado de alta



return NULL;

	}






	
}
