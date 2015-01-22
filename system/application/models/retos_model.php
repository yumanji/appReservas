<?php
class Retos_model extends Model {

		var $id_transaction   = NULL;
		var $players   = NULL;
		var $price_by_player   = NULL;
		var $gender   = NULL;
		var $low_player_level   = NULL;
		var $high_player_level   = NULL;
		var $limit_date   = NULL;
		var $visible   = NULL;
		var $last_notify   = NULL;
		var $create_user   = NULL;
		var $create_time   = NULL;
		var $create_ip   = NULL;
		var $modify_user   = NULL;
		var $modify_time   = NULL;
		var $modify_ip   = NULL;
		
    function Retos_model()
    {
        // Call the Model constructor
        parent::Model();
    }
    
    function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function create($id, $data)
    {
        $this->id_transaction   = $id; // please read the below note
        $this->players = $data['players'];
        $this->price_by_player = $data['price_by_player'];
        $this->gender = $data['gender'];
        $this->limit_date = $data['limit_date'];
        $this->low_player_level = $data['low_player_level'];
        $this->high_player_level = $data['high_player_level'];
        $this->visible = $data['visible'];
        $this->last_notify = $data['last_notify'];
        $this->create_user = $this->session->userdata('user_id');
        $this->create_time = date(DATETIME_DB);
        $this->create_ip = $this->session->userdata('ip_address');

        $this->db->insert('booking_shared', $this);
        log_message('debug',$this->db->last_query());
    }


    function update($id, $datos)
    {
			$table = "booking_shared";

			$data = array(
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address')
			);
			if(isset($datos['players'])) $data['players'] = $datos['players'];
			if(isset($datos['price_by_player'])) $data['price_by_player'] = $datos['price_by_player'];
			if(isset($datos['gender'])) $data['gender'] = $datos['gender'];
			if(isset($datos['limit_date'])) $data['limit_date'] = $datos['limit_date'];
			if(isset($datos['low_player_level'])) $data['low_player_level'] = $datos['low_player_level'];
			if(isset($datos['high_player_level'])) $data['high_player_level'] = $datos['high_player_level'];
			if(isset($datos['visible'])) $data['visible'] = $datos['visible'];
			if(isset($datos['last_notify'])) $data['last_notify'] = $datos['last_notify'];

			$this->db->update($table, $data, array('id_transaction' => $id));
      log_message('debug',$this->db->last_query());
    }


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


########################
#
################


public function get_data($params = "" , $page = "all")
	{
		
		$table_name = 'booking_players';
		
		//Build contents query
		$this->db->select('booking_players.id, booking_players.id_transaction, booking_players.id_user, booking_players.status, zz_booking_player_status.description as status_desc, '.
						'meta.first_name as first_name, meta.last_name as last_name,  meta.first_name + \' \' + meta.last_name as complete_name, meta.phone as phone, meta.player_level, booking_players.win_game, booking_players.player_level_variation', FALSE)->from($table_name);

		//$this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'right outer');
		$this->db->join('zz_booking_player_status', 'booking_players.status=zz_booking_player_status.id', 'left outer');
		$this->db->join('meta', 'booking_players.id_user=meta.user_id', 'left outer');


		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		else $params['orderbyway'] = 'desc';
		$this->db->order_by('booking_players.id', $params['orderbyway']);
		
		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		//log_message('debug',$this->db->last_query());
		//exit();
		return $query;
	

}




	public function get_global_data($params = "" , $page = "all")
		{
			
		$this->CI =& get_instance();
		$this->CI->load->model('Pistas_model', 'pistas', TRUE);

		$table_name = 'booking';
			
			//Build contents query
		$add_select = '';
		if(isset($params['usuario']) && $params['usuario']!='') $add_select = ', booking_players.status as apuntado';
		
		
		if($page=='count') { $this->db->count_all_results($table_name); $this->db->from($table_name); }
		else $this->db->select('booking.id as id, id_booking, booking.id_transaction, booking.id_user, session, id_court, DATE_FORMAT(DATE(booking.date), \'%d-%m-%Y\') as fecha, '.
							'intervalo, booking.`status`, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, '.
							'booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, '.
			 				'booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, '.
							'meta.last_name as last_name,  meta.first_name + \' \' + meta.last_name as complete_name, meta.phone as phone, zz_booking_status.description as status_desc, '.
							'zz_paymentway.description as paymentway_desc, booking.price_light as price_light, booking.price_court as price_court, '.
							'booking_shared.players, booking_shared.price_by_player, booking_shared.gender, booking_shared.low_player_level, booking_shared.high_player_level, DATE_FORMAT(DATE(booking_shared.limit_date), \'%d-%m-%Y\') as limit_date, booking_shared.visible, booking_shared.last_notify, booking_shared.notified'.$add_select, FALSE)->from($table_name);

		$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		$this->db->join('meta', 'booking.id_user=meta.user_id', 'left outer');
		$this->db->join('booking_shared', 'booking.id_transaction=booking_shared.id_transaction', 'left outer');
		$this->db->join('zz_booking_status', 'booking.status=zz_booking_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'booking.id_paymentway=zz_paymentway.id', 'left outer');
		if(isset($params['usuario']) && $params['usuario']!='') $this->db->join('booking_players', 'booking_players.id_transaction=booking.id_transaction', 'left outer');
			
		if(isset($params['usuario']) && $params['usuario']!='') $params['where'].=' AND (booking_players.id_user='.$params['usuario'].' OR booking_players.id_user is null)';
		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		if (empty ($params['orderbyway'])) $params['orderbyway'] = 'DESC';
		
		if($page=='count') return $this->db->count_all_results();	// Si estoy pidiendo el COUNT no necesito ni ordenar ni nada de lo que viene a continuación, así que doy el return desde ya.
		
		
		$this->db->order_by('id_transaction', $params['orderbyway']);
		
		//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		//log_message('debug',$this->db->last_query());
		//echo $this->db->last_query();exit();
		
		if (empty ($params['page'])) $params['page'] = '1';
		if (empty ($params['num_rows'])) $params['num_rows'] = '999999';
		
		$record_items = array(); $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
		$contador=0; $first_row = $params ["num_rows"] *  ($params ["page"] - 1); $last_row = ($params ["num_rows"] *  $params ["page"]);
			
			foreach ($query->result() as $row)
			{
				//if($contador <= $first_row) { $contador++; continue; }
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
				$reserve_interval = $this->CI->pistas->getCourtInterval($row->id_court);
				
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
					'notified' => $notified
				);
				if(isset($params['usuario']) && $params['usuario']!='') {
					$estados = array(1, 2, 5);
					$registro['apuntado'] = $row->apuntado;
					if(in_array($registro['apuntado'], $estados)!='') $registro['apuntado'] = 'Si';
					else $registro['apuntado'] = '';
				} else $registro['apuntado'] = '';
				
				//print("<pre>");print_r($row);print("</pre>");
			}
			if(count($registro)>0) $record_items[] = $registro;

			return $record_items;
		

		}


##############################################################################


    public function setPlayerLevelModification ($usuario, $incremento)
    {
		//echo $usuario;
        //$query = $this->db->get('entries', 10);
        //return $query->result();

	      //exit('aaaa');
			$this->db->query("UPDATE meta SET player_level = player_level + (".$incremento.") WHERE user_id = ".$usuario);
			log_message('debug',$this->db->last_query());
		        
		return TRUE;				
    }
  
##############################################################################


    public function setPlayerSharedGameResult ($usuario, $id_transaction, $datos)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
				'modify_user' => $this->session->userdata('user_id'),
				'modify_time' => date($this->config->item('log_date_format')),
				'modify_ip' => $this->session->userdata('ip_address')
			);
			if(isset($datos['win_game'])) $data['win_game'] = $datos['win_game'];
			if(isset($datos['player_level_variation'])) $data['player_level_variation'] = $datos['player_level_variation'];

			$this->db->where('id_user', $usuario);
			$this->db->where('id_transaction', $id_transaction);
			$this->db->update('booking_players', $data);
			log_message('debug',$this->db->last_query());
		}catch(Exception $e){
			return FALSE;
		}        
		
		return TRUE;				
    }
  
##############################################################################


    public function setSharedGameResultSaved ($id_transaction, $status = '1')
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
				'winner_recorded' => $status,
				'modify_user' => $this->session->userdata('user_id'),
				'modify_time' => date($this->config->item('log_date_format')),
				'modify_ip' => $this->session->userdata('ip_address')
			);

			$this->db->where('id_transaction', $id_transaction);
			$this->db->update('booking_shared', $data);
			log_message('debug',$this->db->last_query());
		}catch(Exception $e){
			return FALSE;
		}        
		
		return TRUE;				
    }
  

}
?>