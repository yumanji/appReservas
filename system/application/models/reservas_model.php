<?php
class Reservas_model extends Model {

/*
# CONTENIDO
#
# getSportsArray()
# getReserveStatusArray()
# getPaymentWaysArray()
# getSpecialTimetable()
# getSpecialTimetableByCourt()
# getAvailabilityByCourt()
# checkExactAvailability()
# bookingInterval()
# getBookingInfoBySession()
# getBookingInfoById()
# eraseInterval()
# clearByUser()
# getPaymentMethodsByUser()
# setSelectionReserved()
# getPrice()
# get_data()
# get_data_count()
# get_listall()
# get_list_by_day()
# get_global_list()
# get_court_ocupation()
# get_complete_court_ocupation()
# validate_reserve()
# cleanBlockReserves()
# 

*/



    var $date   = NULL;
    var $intervalo   = NULL;
    var $status   = NULL;
    var $court   = NULL;
    var $id   = NULL;
    var $group   = NULL;
    var $id_user   = NULL;
    var $id_booking   = NULL;
    var $id_transaction   = NULL;
    var $booking_code   = NULL;
    var $user_nif   = NULL;
    var $sesion   = NULL;
    var $user_desc   = NULL;
    var $paymentway   = NULL;
    var $price   = NULL;
    var $price_court   = NULL;
    var $price_light   = NULL;
    var $price_supl1   = NULL;	// Suplemento por reserva anticipada
    var $price_supl2   = NULL;	// Suplemento por invitados
    var $price_supl3   = NULL;
    var $price_supl4   = NULL;
    var $price_supl5   = NULL;
    var $availability   = NULL;
    var $create_user   = NULL;
    var $create_time   = NULL;


##############################################################################



    function Reservas_model()
    {
        // Call the Model constructor
        parent::Model();
		$this->load->model('Pistas_model', 'pistas', TRUE);
    }


##############################################################################


    
    function getSportsArray($format = null)
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        if($format == 'json') $result=array();
		else $result=array(""=>"Selecciona deporte");
        $sql = "SELECT id, description FROM zz_sports WHERE active=1 "; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					if($format == 'json') array_push($result, $row->description.':'.$this->lang->line($row->description));
					else $result[$row->id]=$this->lang->line($row->description);
				}
					if($format == 'json') $result = implode(';', $result);
					return $result;				
    }



##############################################################################


    
    function getReserveStatusArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array(""=>"Selecciona opcion");
        $sql = "SELECT id, description FROM zz_booking_status "; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$this->lang->line($row->description);
				}	
					return $result;				
    }


##############################################################################


    
    function getPaymentWaysArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array(""=>"Selecciona opcion");
        $sql = "SELECT id, description FROM zz_paymentway WHERE active=1"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$this->lang->line($row->description);
				}	
					return $result;				
    }


##############################################################################







    function getSpecialTimetable() {
    	# Devuelve array con el horario especial común para la fecha definida
			$date=$this->date;
			$weekday=@date('N', strtotime($date));
			$court=$this->court;
			if($date!="" && $weekday!="") {
				# Compruebo si hay un horario especial activo para la fecha dada para todas las pistas
		    $sql = "SELECT time_table FROM time_tables_specials WHERE status = 1 and type = 2 and (id_court = 0 or id_court = ?) and date = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($court, $date));
				//echo '<br>sesion: '.$this->db->last_query();

				if ($query->num_rows() > 0) {	
					$row = $query->row();
					$timetable=$row->time_table;
				} else {
					# Compruebo si hay un horario especial activo para una fecha anual para todas las pistas
					$date_tmp=explode("-", $date);
					$date_anual='%'.$date_tmp[1]."-".$date_tmp[2];
					
			    $sql = "SELECT time_table FROM time_tables_specials WHERE status = 1 and type = 1 and id_court = 0 and date LIKE ? LIMIT 1"; 
					$query = $this->db->query($sql, array($date_anual));
					if ($query->num_rows() > 0) {	
						$row = $query->row();
						$timetable=$row->time_table;				
					} else return NULL;
					
				}
				
				//echo "<br>Horario: ".$timetable;
	
				# Recupero el detalle del timetable asociado al horario especial
		    $result=array();
	      $sql = "SELECT time_tables_detail.`interval`, time_tables_detail.status FROM time_tables_detail, time_tables WHERE time_tables_detail.id_time_table = time_tables.id AND time_tables_detail.id_time_table = ? AND (time_tables_detail.weekday = ? OR time_tables.everyday = 1) ORDER BY time_tables_detail.`interval`"; 
				$query = $this->db->query($sql, array($timetable, $weekday));
				//echo '<br>sesion: '.$this->db->last_query();
			//exit($date.' - '.$weekday);
				if ($query->num_rows() > 0) {	
					foreach ($query->result() as $row) {
						$result[$court."-".date('U', strtotime($date." ".$row->interval))]=array(date('H:i', strtotime($row->interval)),$row->status, '', '', '', '', '');
					}
					//print("horario especial periodico, encontrado.<br>");//print_r ($result);	        
					$this->availability=$result;	
					return NULL;			
	
				} else return NULL;
				
				# Aquí habría que meter una funcion que rellene los posibles espacios vacíos del array y lo ordene
			
			print_r ($result);
		} else return NULL;	        
    }



##############################################################################




    function getSpecialTimetableByCourt() {
    	# Devuelve array con el horario especial específico para la pista dada
			
			$court=$this->court;
			//echo "PIsta en marcha: ".$this->court."<br>";
			$date=$this->date;
			$weekday=@date('N', strtotime($date));
			if($court!="" && $date!="" && $weekday!="") {
				# Compruebo si hay un horario especial activo para la fecha dada para todas las pistas
		    $sql = "SELECT time_table FROM time_tables_specials WHERE status = 1 and type = 2 and id_court = ? and date = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($court, $date));
				if ($query->num_rows() > 0) {	
					$row = $query->row();
					$timetable=$row->time_table;
				} else {
					# Compruebo si hay un horario especial activo para una fecha anual para todas las pistas
					$date_tmp=explode("-", $date);
					$date_anual='%'.$date_tmp[1]."-".$date_tmp[2];
					
			    $sql = "SELECT time_table FROM time_tables_specials WHERE status = 1 and type = 1 and id_court = ? and date LIKE ? LIMIT 1"; 
					$query = $this->db->query($sql, array($court, $date_anual));
					if ($query->num_rows() > 0) {	
						$row = $query->row();
						$timetable=$row->time_table;				
					} else return NULL;
					
				}
				
				//echo "<br>Horario: ".$timetable;
	
				# Recupero el detalle del timetable asociado al horario especial
		    $result=array();
	      $sql = "SELECT time_tables_detail.`interval`, time_tables_detail.status FROM time_tables_detail, time_tables WHERE time_tables_detail.id_time_table = time_tables.id AND time_tables_detail.id_time_table = ? AND (time_tables_detail.weekday = ? OR time_tables.everyday = 1) ORDER BY time_tables_detail.`interval`"; 
				$query = $this->db->query($sql, array($timetable, $weekday));
				if ($query->num_rows() > 0) {	
					foreach ($query->result() as $row) {
						$result[$court."-".date('U', strtotime($date." ".$row->interval))]=array(date('H:i', strtotime($row->interval)),$row->status, '', '', '', '', '');
					}
					//print("horario especial periodico, encontrado.<br>");
					$this->availability=$result;	
					return NULL;			
	
				} else return NULL;
				
				# Aquí habría que meter una funcion que rellene los posibles espacios vacíos del array y lo ordene
			} else return NULL;
				        
    }




##############################################################################



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de preseleccion de pista
# -------------------------------------------------------------------

    function getAvailabilityByCourt($fecha="", $pista="", $exclusion = NULL, $options = NULL) {
    	# Devuelve array con las horas disponibles para ser reservadas de la pista y dia comunicados, del 'availability' almacenado

			# $exclusion es el id_transaction que ignoraremos en caso de venir (para cambios de horas de reserva en que se solapen parcialmente la reserva antigua y la nueva, por ejemplo...)

			# $options albergará opciones
			# $options['block_past'] definirá si  toda fecha y hora pasada se marca como bloqueada o no .. .para evitar reservar horas antiguas.. pero  con la opción de habilitarlo para la comprobación de si cierta pista estaba hábil en el pasado o para grabar clases que empiezan en fechas antiguas sin  que casque.

			if(!isset($options)) $options = array('block_past' => TRUE);

			if($fecha=="" || $pista=="") {
				foreach($this->availability as $intervalo => $valores) {
					$this->availability[$intervalo][1]="0";
				}
				return NULL;
			}
			
			# Si la fecha es pasada, todo bloqueado
			if($options['block_past'] && $fecha < date($this->config->item('date_db_format'))) {
				foreach($this->availability as $intervalo => $valores) {
					$this->availability[$intervalo][1]="0";
				}
				return NULL;
			}

			//echo $fecha;
			# Si la fecha es hoy, bloqueo horas pasadas (permitiendo cierto retraso en este bloqueo)
			if($options['block_past']){
				$delay = $this->config->item('booking_delay_seconds');
				$profile = $this->redux_auth->profile();
				if(count($delay)<= 0 || !is_object($profile) ||!isset($delay[$profile->group]) || !is_numeric($delay[$profile->group])) $retardo = 0;
				else $retardo = $delay[$profile->group];
				
				//print_r($profile);
				//echo '<br>'.$profile->group.' - '.$delay[$profile->group];
				if($fecha == date($this->config->item('date_db_format'))) {
					//echo $this->date.' Hoy -';
					foreach($this->availability as $intervalo => $valores) {
						if(strtotime($this->availability[$intervalo][0]) <= (strtotime(date($this->config->item('reserve_hour_filter_format'))) - $retardo )) {
							$this->availability[$intervalo][1]="0";
						}
					}
				}
			}

			$availability=$this->availability;
			//echo "<b>AAAAAAAA</b>";
	    $sql = "SELECT intervalo, id_transaction, status, shared, no_cost FROM booking WHERE status > 0 and date = ? and id_court = ? order by intervalo"; 
			$query = $this->db->query($sql, array($fecha, $pista));
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$id=$pista."-".date('U', strtotime($fecha." ".$row->intervalo));
					//echo "<b>".$id."</b><br>";
					if(isset($availability[$id]) && $availability[$id][1]=="1") {
						//echo "existe!<br>";
						$this->availability[$id][1]="0";
						
						# Si tengo marcada una reserva para excluirla y estamos justo en ese caso, pongo de nuevo la disponibilidad a 1
						if($row->id_transaction == $exclusion) $this->availability[$id][1]="1";
						
					}
					$this->availability[$id][2] = $row->id_transaction;
					$this->availability[$id][3] = $row->status;
					$info = $this->getBookingInfoById($row->id_transaction);
					//echo '<pre>';print_r($info);
					$this->availability[$id][4] = $info['inicio'].'-'.$info['fin'];
					$this->availability[$id][5] = $row->shared;
					$this->availability[$id][6] = $row->no_cost;
				}
			}
			
			return NULL;        
    }




##############################################################################




    function checkExactAvailability() {
    	# Devuelve si un intervalo está reservado o no
			
	    $sql = "SELECT id FROM booking WHERE status > 0 and date = ? and intervalo = ? and id_court = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($this->date, $this->intervalo, $this->court));
			
			if ($query->num_rows() > 0) return FALSE;
			else return TRUE;			
				        
    }





##############################################################################




    function bookingInterval() {
    	# Marca un intervalo como reservado
	
			$debug = FALSE;
			
	    $sql = "SELECT id FROM booking WHERE status > 0 and date = ? and intervalo = ? and id_court = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($this->date, $this->intervalo, $this->court));
			if($debug) 'SQL: '.$this->db->last_query();

			if ($query->num_rows() == 0) {
				
				$data = array(
	               'id_booking' => $this->court."-".date('U', strtotime($this->date." ".$this->intervalo)),
	               'id_transaction' => $this->session->userdata('idTransaction'),
	               'booking_code' => $this->app_common->reserve_encode($this->session->userdata('idTransaction')),
	               'id_user' => $this->id_user,
	               'id_court' => $this->court,
	               'date' => $this->date,
	               'session' => $this->sesion,
	               'intervalo' => $this->intervalo,
	               'price' => $this->price,
	               'price_court' => $this->price_court,
	               'price_light' => 0,
	               'price_supl1' => $this->price_supl1,
	               'price_supl2' => $this->price_supl2,
	               'price_supl3' => 0,
	               'price_supl4' => 0,
	               'price_supl5' => 0,
	               'status' => $this->status,
	               'create_user' => $this->create_user,
	               'create_time' => date($this->config->item('log_date_format'))
	            );
	
				if($this->status > 0){
					# Si el status es mayor que cero, marco la reserva... Si no, solo ejecuto la funcionalidad de marcar en sesion
					$this->db->insert('booking', $data);
					log_message('debug',$this->db->last_query());
				}
				
				$intervalos=$this->session->userdata('bookingInterval');
				
				if(!isset($intervalos) || !is_array($intervalos)) {
					$this->session->set_userdata('bookingInterval', array ($data['id_booking']));
				} else {
					array_push($intervalos, $data['id_booking']);
					$this->session->set_userdata('bookingInterval', $intervalos);
				}
				//$this->session->set_flashdata('bookingInterval', 'value');
				return TRUE;			
			} else return FALSE;			        
    }


##############################################################################




    function getBookingInfoBySession($session) {
    	# Devuelve información de la reserva pendiente registrada para un numero de sesion
		$this->load->model('Pistas_model', 'pistas', TRUE);
	
	    $sql = "SELECT courts.id as id_court, courts.name as name, booking.id, booking.id_transaction, date , intervalo, price, price_light, price_court, booking_code, id_user, booking.user_desc, user_phone, no_cost, no_cost_desc, booking.status as status, meta.first_name as first_name, meta.last_name as last_name, meta.phone as phone, zz_booking_status.description as status_desc, id_paymentway FROM courts, zz_booking_status, booking LEFT OUTER JOIN meta ON booking.id_user = meta.user_id WHERE zz_booking_status.id = booking.status AND courts.id=booking.id_court and courts.active=1 and session = ? ORDER BY id_court asc, intervalo asc"; 
			$query = $this->db->query($sql, array($session));
			//echo 'sesion: '.$this->db->last_query();
			if ($query->num_rows() > 0) {
				$precio_total=0;
				$precio_pista=0;
				$intervalos=0;
				$min='';
				$max='';
				$light = false;
				$light_price = 0;
				$pista=array();
				$fecha=0;
				$usuario=0;
				$id_court=0;
				$status=0;
				$id = '';
				foreach ($query->result() as $row) {				
					if($id == '' || $id > $row->id) $id = $row->id;	// Me quedo con el id menor
					if($row->price_light != 0) $light = true;
					$light_price += $row->price_light;
					
					$id_court = $row->id_court;
					$reserve_interval = $this->pistas->getCourtInterval($row->id_court);

					$status = $row->status;
					
					if(!isset($pista[$row->name])) $pista[$row->name] = array(); 
					$inicio=date($this->config->item('hour_db_format'), strtotime($row->intervalo));
					$fin=date($this->config->item('hour_db_format'), strtotime($row->intervalo)+($reserve_interval * 60));
					array_push($pista[$row->name], array($inicio, $fin, $row->price));
					if($min > $inicio || $min=='') $min = $inicio;
					if($max < $fin || $max=='') $max = $fin;
					
					$usuario = $row->id_user;
					if($usuario==0) {
						$user_desc = $row->user_desc;
						$user_phone = $row->user_phone;
					} else {
						$user_desc = $row->first_name.' '.$row->last_name;
						$user_phone = $row->phone;
					}
					
					$intervalos++;
					$precio_total=$precio_total+$row->price;
					$precio_pista=$precio_pista+$row->price_court;
					$fecha=$row->date;
				}
				
				$result=array(
					'date' => $fecha,
					'price' => $precio_pista,
					'intervals' => $intervalos,
					'user' => $usuario,
					'user_desc' => $user_desc,
					'user_phone' => $user_phone,
					'inicio' => $min,
					'fin' => $max,
					'light' => $light,
					'light_price' => $light_price,
					'total_price' => $precio_total,
					'reserva' => $pista	,
					'id_court' => $id_court,
					'court' => $row->name,
					'no_cost' => $row->no_cost,
					'no_cost_desc' => $row->no_cost_desc,
					'booking_code' => $row->booking_code,				
					'id_transaction' => $row->id_transaction,				
					'id' => $id,				
					'status' => $status,				
					'status_desc' => $row->status_desc,
					'paymentway' => $row->id_paymentway,
					'operation_desc' => 'Reserva ('.$row->booking_code.') de '.$row->name.' el '.date($this->config->item('reserve_date_filter_format'), strtotime($fecha)).' de '.$min.' a '.$max
				);
				
				return $result;			
			} else return FALSE;			        
    }


##############################################################################




    function getBookingInfoById($id_transacion) {
    	# Devuelve información de la reserva pendiente registrada para un numero de sesion
		
	
	    $sql = "SELECT courts.id as id_court, courts.name as name, booking.id, booking.id_transaction, date , intervalo, price, price_light, price_court, id_user, booking.user_desc, booking_code, user_phone, no_cost, no_cost_desc, booking.status as status, meta.first_name as first_name, meta.last_name as last_name, meta.phone as phone, zz_booking_status.description as status_desc, courts.sport_type as id_sport, zz_sports.description as sport, shared, id_paymentway, booking.create_time, booking.price_supl1, booking.price_supl2 FROM courts, zz_booking_status, zz_sports, booking LEFT OUTER JOIN meta ON booking.id_user = meta.user_id WHERE zz_booking_status.id = booking.status AND zz_sports.id = courts.sport_type AND courts.id=booking.id_court and courts.active=1 and id_transaction = ? ORDER BY id_court asc, intervalo asc"; 
			$query = $this->db->query($sql, array($id_transacion));
				//echo $this->db->last_query();//exit();
			if ($query->num_rows() > 0) {
				$precio_total=0;
				$precio_pista=0;
				$precio_supl1=0;
				$precio_supl2=0;
				$intervalos=0;
				$min='';
				$max='';
				$light = false;
				$light_price = 0;
				$pista=array();
				$fecha=0;
				$usuario=0;
				$id_court=0;
				$status=0;
				$id = '';
				foreach ($query->result() as $row) {			
					if($id == '' || $id > $row->id) $id = $row->id;	// Me quedo con el id menor
					if($row->price_light != 0) $light = true;
					$light_price += $row->price_light;
					
					$id_court = $row->id_court;
					$reserve_interval = $this->pistas->getCourtInterval($id_court);
					$status = $row->status;

					if(!isset($pista[$row->name])) $pista[$row->name] = array(); 
					$inicio=date($this->config->item('hour_db_format'), strtotime($row->intervalo));
					$fin=date($this->config->item('hour_db_format'), strtotime($row->intervalo)+($reserve_interval * 60));
					array_push($pista[$row->name], array($inicio, $fin, $row->price));
					if($min > $inicio || $min=='') $min = $inicio;
					if($max < $fin || $max=='') $max = $fin;
					
					$usuario = $row->id_user;
					if($usuario==0) {
						$user_desc = $row->user_desc;
						$user_phone = $row->user_phone;
					} else {
						$user_desc = $row->first_name.' '.$row->last_name;
						$user_phone = $row->phone;
					}
					
					$intervalos++;
					//$precio_total=$precio_total+$row->price_court;
					$precio_supl1=$precio_supl1+$row->price_supl1;
					$precio_supl2=$precio_supl2+$row->price_supl2;
					$precio_total=$precio_total+$row->price;
					$precio_pista=$precio_pista+$row->price_court;
					$fecha=$row->date;
				}
				
				
				$result=array(
					'date' => $fecha,
					'fecha' => date($this->config->item('reserve_date_filter_format') ,strtotime($fecha)),
					'price' => $precio_pista,
					'intervals' => $intervalos,
					'user' => $usuario,
					'user_desc' => $user_desc,
					'user_phone' => $user_phone,
					'inicio' => $min,
					'fin' => $max,
					'light' => $light,
					'light_price' => $light_price,
					'total_price' => $precio_total,
					'precio_supl1' => $precio_supl1,
					'precio_supl2' => $precio_supl2,
					'reserva' => $pista	,
					'id_court' => $id_court,
					'court' => $row->name,
					'id_sport' => $row->id_sport,
					'sport' => $row->sport,
					'no_cost' => $row->no_cost,
					'no_cost_desc' => $row->no_cost_desc,
					'booking_code' => $row->booking_code,				
					'id_transaction' => $row->id_transaction,				
					'id' => $id,				
					'status' => $status,				
					'status_desc' => $row->status_desc,
					'paymentway' => $row->id_paymentway,
					'operation_desc' => 'Reserva ('.$row->booking_code.') de '.$row->name.' el '.date($this->config->item('reserve_date_filter_format'), strtotime($fecha)).' de '.$min.' a '.$max,
					'create_time' => $row->create_time,
				);
				
				# Información adicional para partidos de tipo 'reto'
				if($row->shared == '1') {
			    $sql = "SELECT * FROM booking_shared WHERE id_transaction = ?"; 
					$query = $this->db->query($sql, array($id_transacion));
						//echo $this->db->last_query();//exit();
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $row) {
							$result['players'] = $row->players;
							$result['price_by_player'] = $row->price_by_player;
							$result['gender'] = $row->gender;
							$result['low_player_level'] = $row->low_player_level;
							$result['high_player_level'] = $row->high_player_level;
							$result['limit_date'] = $row->limit_date;
							$result['visible'] = $row->visible;
							$result['last_notify'] = $row->last_notify;
							$result['winner_recorded'] = $row->winner_recorded;
						}
					} else {
						$result['players'] = '';
						$result['price_by_player'] = '';
						$result['gender'] = '';
						$result['low_player_level'] = '';
						$result['high_player_level'] = '';
						$result['limit_date'] = '';
						$result['visible'] = '';
						$result['last_notify'] = '';
						$result['winner_recorded'] = '0';
						
					}


			    $sql = "SELECT * FROM booking_players WHERE id_transaction = ?"; 
					$query = $this->db->query($sql, array($id_transacion));
						//echo $this->db->last_query();//exit();
					if ($query->num_rows() > 0) {
						$signed = 0;
						$signed_users = array();
						$waiting = 0;
						$waiting_users = array();
						foreach ($query->result() as $row) {
							if($row->status == 1 || $row->status == 5 || $row->status == 7) {
								$signed++;
								array_push($signed_users, array('id_user' => $row->id_user, 'status' => $row->status));
							}
							if($row->status == 2) {
								$waiting++;
								array_push($waiting_users, array('id_user' => $row->id_user, 'status' => $row->status));
							}
						}
						$result['signed'] = $signed;
						$result['signed_users'] = $signed_users;
						$result['waiting'] = $waiting;
						$result['waiting_users'] = $waiting_users;
					} else {
						$result['signed'] = 0;
						$result['signed_users'] = array();
						$result['waiting'] = 0;
						$result['waiting_users'] = array();
						
					}


				} else {



						$result['players'] = '';
						$result['price_by_player'] = '';
						$result['gender'] = '';
						$result['low_player_level'] = '';
						$result['high_player_level'] = '';
						$result['limit_date'] = '';
						$result['visible'] = '';
						$result['last_notify'] = '';
						
					


			    $sql = "SELECT * FROM booking_players WHERE id_transaction = ?"; 
					$query = $this->db->query($sql, array($id_transacion));
						//echo $this->db->last_query();//exit();
					if ($query->num_rows() > 0) {
						$signed = 0;
						$signed_users = array();
						foreach ($query->result() as $row) {
								$signed++;
								$row_user_desc = $row->user_desc;
								$row_user_phone = $row->user_phone;
								if($row->id_user != 0) {
									$this->CI =& get_instance();
									$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);
									$row_user_desc = $this->CI->usuario->getUserDesc($row->id_user);
									$row_user_phone = $this->CI->usuario->getUserPhone($row->id_user);									
								}
								array_push($signed_users, array('id' => $row->id, 'id_user' => $row->id_user, 'user_desc' => $row_user_desc, 'user_phone' => $row_user_phone, 'status' => $row->status));
						}
						$result['players'] = $signed;
						$result['playing_users'] = $signed_users;
					} else {
						$result['players'] = 0;
						$result['playing_users'] = array();
						
					}
				}
				
				return $result;			
			} else return FALSE;			        
    }

##############################################################################


    

    function getBookingInfoByRealid($id) {
    	# Devuelve información de la reserva pendiente registrada para un numero de sesion
		$this->load->model('Pistas_model', 'pistas', TRUE);
	
	    $sql = "SELECT courts.id as id_court, courts.name as name, booking.id, booking.id_transaction, booking.date , booking.intervalo, booking.price, booking.price_light, booking.price_court, booking.id_user, booking.user_desc, booking.booking_code, booking.user_phone, booking.no_cost, booking.no_cost_desc, booking.status as status, meta.first_name as first_name, meta.last_name as last_name, meta.phone as phone, zz_booking_status.description as status_desc, booking.id_paymentway FROM booking LEFT OUTER JOIN booking b2 ON booking.id_transaction = b2.id_transaction LEFT OUTER JOIN zz_booking_status ON zz_booking_status.id = booking.status LEFT OUTER JOIN courts ON courts.id=booking.id_court LEFT OUTER JOIN meta ON booking.id_user = meta.user_id WHERE courts.active=1 and b2.id = ? ORDER BY id_court asc, intervalo asc"; 
			$query = $this->db->query($sql, array($id));
				//echo $this->db->last_query();//exit();
			log_message('debug',$this->db->last_query());
			if ($query->num_rows() > 0) {
				$precio_total=0;
				$precio_pista=0;
				$intervalos=0;
				$min='';
				$max='';
				$light = false;
				$light_price = 0;
				$pista=array();
				$fecha=0;
				$usuario=0;
				$id_court=0;
				$status=0;
				$id = '';
				foreach ($query->result() as $row) {				
					if($id == '' || $id > $row->id) $id = $row->id;	// Me quedo con el id menor
					if($row->price_light != 0) $light = true;
					$light_price += $row->price_light;
					
					$id_court = $row->id_court;
					$reserve_interval = $this->pistas->getCourtInterval($row->id_court);
					$status = $row->status;
					
					if(!isset($pista[$row->name])) $pista[$row->name] = array(); 
					$inicio=date($this->config->item('hour_db_format'), strtotime($row->intervalo));
					$fin=date($this->config->item('hour_db_format'), strtotime($row->intervalo)+($reserve_interval * 60));
					array_push($pista[$row->name], array($inicio, $fin, $row->price));
					if($min > $inicio || $min=='') $min = $inicio;
					if($max < $fin || $max=='') $max = $fin;
					
					$usuario = $row->id_user;
					if($usuario==0) {
						$user_desc = $row->user_desc;
						$user_phone = $row->user_phone;
					} else {
						$user_desc = $row->first_name.' '.$row->last_name;
						$user_phone = $row->phone;
					}
					
					$intervalos++;
					$precio_total=$precio_total+$row->price;
					$precio_pista=$precio_pista+$row->price_court;
					$fecha=$row->date;
				}
				
				$result=array(
					'date' => $fecha,
					'price' => $precio_pista,
					'intervals' => $intervalos,
					'user' => $usuario,
					'user_desc' => $user_desc,
					'user_phone' => $user_phone,
					'inicio' => $min,
					'fin' => $max,
					'light' => $light,
					'light_price' => $light_price,
					'total_price' => $light_price + $precio_total,
					'reserva' => $pista	,
					'id_court' => $id_court,
					'court' => $row->name,
					'no_cost' => $row->no_cost,
					'no_cost_desc' => $row->no_cost_desc,
					'booking_code' => $row->booking_code,				
					'id_transaction' => $row->id_transaction,				
					'id' => $id,				
					'status' => $status,				
					'status_desc' => $row->status_desc,
					'paymentway' => $row->id_paymentway,
					'operation_desc' => 'Reserva ('.$row->booking_code.') de '.$row->name.' el '.date($this->config->item('reserve_date_filter_format'), strtotime($fecha)).' de '.$min.' a '.$max
				);
				
				return $result;			
			} else return FALSE;			        
    }


##############################################################################


    function getBookingCancelledInfoById($id_transacion) {
    	# Devuelve información de la reserva pendiente registrada para un numero de sesion
    
    
    	$sql = "SELECT courts.id as id_court, courts.name as name, booking_cancelled.id, booking_cancelled.id_transaction, date , intervalo, price, id_user, booking_cancelled.user_desc, booking_code, user_phone, no_cost, no_cost_desc, booking_cancelled.status as status, meta.first_name as first_name, meta.last_name as last_name, meta.phone as phone, zz_booking_status.description as status_desc, courts.sport_type as id_sport, zz_sports.description as sport, id_paymentway, booking_cancelled.create_time FROM courts, zz_booking_status, zz_sports, booking_cancelled LEFT OUTER JOIN meta ON booking_cancelled.id_user = meta.user_id WHERE zz_booking_status.id = booking_cancelled.status AND zz_sports.id = courts.sport_type AND courts.id=booking_cancelled.id_court and courts.active=1 and id_transaction = ? ORDER BY id_court asc, intervalo asc";
    	$query = $this->db->query($sql, array($id_transacion));
    	//echo $this->db->last_query();//exit();
    	if ($query->num_rows() > 0) {
    		$precio_total=0;
    		$precio_pista=0;
    		$precio_supl1=0;
    		$precio_supl2=0;
    		$intervalos=0;
    		$min='';
    		$max='';
    		$light = false;
    		$light_price = 0;
    		$pista=array();
    		$fecha=0;
    		$usuario=0;
    		$id_court=0;
    		$status=0;
    		$id = '';
    		foreach ($query->result() as $row) {
    			if($id == '' || $id > $row->id) $id = $row->id;	// Me quedo con el id menor
    				
    			$id_court = $row->id_court;
    			$reserve_interval = $this->pistas->getCourtInterval($id_court);
    			$status = $row->status;
    
    			if(!isset($pista[$row->name])) $pista[$row->name] = array();
    			$inicio=date($this->config->item('hour_db_format'), strtotime($row->intervalo));
    			$fin=date($this->config->item('hour_db_format'), strtotime($row->intervalo)+($reserve_interval * 60));
    			array_push($pista[$row->name], array($inicio, $fin, $row->price));
    			if($min > $inicio || $min=='') $min = $inicio;
    			if($max < $fin || $max=='') $max = $fin;
    				
    			$usuario = $row->id_user;
    			if($usuario==0) {
    				$user_desc = $row->user_desc;
    				$user_phone = $row->user_phone;
    			} else {
    				$user_desc = $row->first_name.' '.$row->last_name;
    				$user_phone = $row->phone;
    			}
    				
    			$intervalos++;
    			$fecha=$row->date;
    		}
    
    
    		$result=array(
    				'date' => $fecha,
    				'fecha' => date($this->config->item('reserve_date_filter_format') ,strtotime($fecha)),
    				'price' => 0,
    				'intervals' => $intervalos,
    				'user' => $usuario,
    				'user_desc' => $user_desc,
    				'user_phone' => $user_phone,
    				'inicio' => $min,
    				'fin' => $max,
    				'reserva' => $pista	,
    				'id_court' => $id_court,
    				'court' => $row->name,
    				'id_sport' => $row->id_sport,
    				'sport' => $row->sport,
    				'no_cost' => $row->no_cost,
    				'no_cost_desc' => $row->no_cost_desc,
    				'booking_code' => $row->booking_code,
    				'id_transaction' => $row->id_transaction,
    				'id' => $id,
    				'status' => $status,
    				'status_desc' => $row->status_desc,
    				'paymentway' => $row->id_paymentway,
    				'operation_desc' => 'Reserva ('.$row->booking_code.') de '.$row->name.' el '.date($this->config->item('reserve_date_filter_format'), strtotime($fecha)).' de '.$min.' a '.$max,
    				'create_time' => $row->create_time,
    		);
    
    
    					return $result;
    					} else return FALSE;
    }
    
        ##############################################################################
    
    
    

    function eraseInterval() {
    	# Devuelve array con las horas disponibles para ser reservadas de la pista y dia comunicados, del 'availability' almacenado
			
	    $sql = "SELECT id FROM booking WHERE status > 0 and date = ? and intervalo = ? and id_court = ? and id_user = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($this->date, $this->intervalo, $this->court, $this->id_user));
			if ($query->num_rows() > 0) {
				
				/*
				$data = array(
	               'id_booking' => $this->court."-".date('U', strtotime($this->date." ".$this->intervalo)),
	               'id_user' => $this->id_user,
	               'id_court' => $this->court,
	               'date' => $this->date,
	               'intervalo' => $this->intervalo
	            );
	            */
				$data = array(
	               'id_transaction' => $this->session->userdata('idTransaction'),
	               'id_user' => $this->id_user,
	               'id_court' => $this->court,
	               'date' => $this->date,
	               'intervalo' => $this->intervalo
	            );
	
				$this->db->delete('booking',$data);
				log_message('debug',$this->db->last_query());
				$intervalos=$this->session->userdata('bookingInterval');
				
				if(isset($intervalos) && is_array($intervalos)) {
					$intervalos2=array($data['id_transaction']);
					$intervalos = array_diff($intervalos, $intervalos2);
					$this->session->set_userdata('bookingInterval', $intervalos);
				}
				$this->session->set_flashdata('bookingInterval', 'value');

				return TRUE;			
			} else return FALSE;			        
    }



##############################################################################



    function clearByUser($usuario) {
    	# Si llamo a esta función borro todas las reservas pendientes de pagar (estado '5') de el usuario activo, por sesion
			if($usuario!=="") {	
				$data = array(
	               'session' => $this->session->userdata('session_id'),
	               'status' => 5,
	            );
	
				$this->db->delete('booking',$data);
				//log_message('debug',$this->db->last_query());
				
				$data = array(
	               'id_user' => $usuario,
	               'status' => 5,
	            );
	
				$this->db->delete('booking',$data);
				//log_message('debug',$this->db->last_query());
			}
				return NULL;			
		        
    }




##############################################################################



    function clearBySession($session) {
    	# Si llamo a esta función borro todas las reservas pendientes de pagar (estado '5') de el usuario activo, por sesion
			if($session!=="") {	
				$data = array(
	               'session' => $session,
	               'status' => 5,
	            );
	
				$this->db->delete('booking',$data);
				//log_message('debug',$this->db->last_query());

			} else {
				$data = array(
	               'session' => $this->session->userdata('session_id'),
	               'status' => 5,
	            );
	
				$this->db->delete('booking',$data);
				//log_message('debug',$this->db->last_query());

				
			}
				return NULL;			
		        
    }






##############################################################################



    function getPaymentMethodsByUser($user_level) {
    	# Devuelve array de los diferentes métodos de pago disponibles
    	exit('función reservas->getPaymentMethodsByUser() deshabilitada');
    	return null;
    	$payment = array ('reserve' => FALSE, 'cash' => FALSE, 'paypal' => FALSE, 'prepaid' => FALSE, 'creditcard' => FALSE, 'tpv' => FALSE, 'bank' => FALSE);
    	foreach($payment as $type => $value) {
    		$payment[$type] = $this->app_common->PaymentMethodStatus($type);
    	}
    	
    	# Aqui debería comprobar los permisos del usuario
			if($user_level >= 9) {
				$payment['reserve'] = FALSE;
				$payment['prepaid'] = FALSE;
				$payment['cash'] = FALSE;
				$payment['paypal'] = FALSE;
				$payment['bank'] = FALSE;
			}
			if($user_level == 7) {
				$payment['reserve'] = FALSE;
			}
			if($user_level == 6) {
				$payment['reserve'] = FALSE;
			}

			if($user_level >= 5) {
				$payment['cash'] = FALSE;
				$payment['bank'] = FALSE;
				$payment['creditcard'] = FALSE;
			}

				return $payment;			
		        
    }



##############################################################################



    function setSelectionReserved($id_transaction, $status, $payment = "", $user, $userdesc='', $phone='', $no_cost, $no_cost_desc = NULL) {
    	# Marca una reserva con un estado de reserva determinado
    	
    	$data = array(
               'status' => $status,
               'id_paymentway' => $payment,
	             'id_user' => $user,
               'user_desc' => $userdesc,
               'user_phone' => $phone,
               'no_cost' => $no_cost,
               'no_cost_desc' => $no_cost_desc,
	             'modify_user' => $user,
	             'modify_time' => date($this->config->item('log_date_format'))
      );
      $this->db->where('id_transaction', $id_transaction);
			$this->db->update('booking', $data);
			log_message('debug',$this->db->last_query());
			if($this->db->affected_rows()) return TRUE;
			//echo $this->db->last_query();
			//exit();
			return FALSE;			
		        
    }



##############################################################################



    function setSelectionShared($id_transaction) {
    	# Marca una reserva con un estado de reserva determinado

    	
    	$data = array(
               'shared' => 1,
	             'modify_time' => date(DATETIME_DB), 
							'modify_user' => $this->session->userdata('user_id'),
							'modify_ip' => $this->session->userdata('ip_address')
      			);
      $this->db->where('id_transaction', $id_transaction);
			$this->db->update('booking', $data);
			log_message('debug',$this->db->last_query());
			if($this->db->affected_rows()) return TRUE;
			//echo $this->db->last_query();
			//exit();
			return FALSE;			
		        
    }


##############################################################################


/*
    function setBookingExtraInfo($id_transaction, $status, $user, $userdesc='', $phone='', $no_cost, $no_cost_desc = NULL) {
    	# Marca una reserva con un estado de reserva determinado
    	
    	$data = array(
               'status' => $status,
	             'id_user' => $user,
               'user_desc' => $userdesc,
               'user_phone' => $phone,
               'no_cost' => $no_cost,
               'no_cost_desc' => $no_cost_desc,
	             'modify_user' => $user,
	             'modify_time' => date($this->config->item('log_date_format'))
      );
      $this->db->where('id_transaction', $id_transaction);
			$this->db->update('booking', $data);
			//echo $this->db->last_query();
			//log_message('debug',$this->db->last_query());
			//exit();
			return NULL;			
		        
    }

*/

##############################################################################



    function setLight($id_transaction) {
    	# Marca una reserva con un estado de reserva determinado
    	//print_r( $this->session);
    	$luz = 0; $precio = 0;
    	
      $sql = "SELECT * FROM booking where Id_Transaction = ? "; 
			$query = $this->db->query($sql, array($id_transaction));
			//$count = $this->db->count_all_results();
			//$precio_luz = $quantity / $count;
			foreach ($query->result() as $row)
			{
				$this->date = $row->date;
				$this->intervalo = $row->intervalo;
				$this->court = $row->id_court;
				$this->getLightPrice();
				$luz = $this->price_light;
				$precio = $row->price + $luz;
				$id_booking = $row->id_booking;
				
	    	$data = array(
	               'price' => $precio,
	               'price_light' => $luz,
		             'modify_user' => $this->session->userdata('user_id'),
		             'modify_time' => date($this->config->item('log_date_format'))
	      );
	      $this->db->where('id_booking', $id_booking);
				$this->db->update('booking', $data);
				log_message('debug',$this->db->last_query(). ' - '.$row->price);
				
			}	

			//echo $this->db->last_query();
			//log_message('debug',$this->db->last_query());
			//exit();
			return NULL;			
		        
    }




##############################################################################



    function getLight($id_transaction) {
    	# Marca una reserva con un estado de reserva determinado
    	//print_r( $this->session);
    	$luz = 0; $precio = 0;
    	
      $sql = "SELECT * FROM booking where Id_Transaction = ? "; 
			$query = $this->db->query($sql, array($id_transaction));
			//$count = $this->db->count_all_results();
			//$precio_luz = $quantity / $count;
			foreach ($query->result() as $row)
			{
				$this->date = $row->date;
				$this->intervalo = $row->intervalo;
				$this->court = $row->id_court;
				$this->getLightPrice();
				$luz = $this->price_light;
				$precio = $precio + $luz;
				
			}	

			//echo $this->db->last_query();
			//log_message('debug',$this->db->last_query());
			//exit();
			return $precio;			
		        
    }





    function setPrice($id_transaction) {
    	
    	$this->load->library('booking');
    	$debug = FALSE;
    	# Marca una reserva con un estado de reserva determinado
    	//print_r( $this->session);
    	$luz = 0; $precio = 0;
    	
      $sql = "SELECT * FROM booking where Id_Transaction = ? "; 
			$query = $this->db->query($sql, array($id_transaction));
			//$count = $this->db->count_all_results();
			//$precio_luz = $quantity / $count;
			//echo 'transaccion: '.$id_transaction.'<br>';
			foreach ($query->result() as $row)
			{
				$this->date = $row->date;
				$this->intervalo = $row->intervalo;
				$this->court = $row->id_court;
				$this->id_user = $row->id_user;
				if($this->id_user != 0) {
					$this->CI =& get_instance();
					$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);
					$this->group = $this->CI->usuario->getUserGroup($row->id_user);
					if(!isset($this->group)) $this->group = 9;					
				}	else $this->group = 9;
				
				/*
				echo 'fecha: '.$this->date.'<br>';
				echo 'hora: '.$this->intervalo.'<br>';
				echo 'pista: '.$this->court.'<br>';
				echo 'usuario: '.$this->id_user.'<br>';
				echo 'grupo: '.$this->group.'<br>';
				*/
				$this->getPrice();
				
				
				
				########################
				
				$extras = $this->booking->getExtra($id_transaction, $this->court.'-'.strtotime($this->date.' '.$this->intervalo));
				if($debug) echo "\r\n".'<br>el extra es '.$extras.' ';
				$this->price_supl1 = $extras; 				
				
				##########################
				
				
				
				# Si tiene precio de luz, es que lo ha seleccionado. Recalculo el precio y defino la variable
				if($row->price_light != 0) {
					$this->getLightPrice();
					$luz = $this->price_light;
				} else $luz = 0;
				
				$precio = $this->price_court + $luz + $this->price_supl1 + $row->price_supl2;

				#Compruebo los extras
				//$extras = $this->booking->getExtra($this->session->userdata('idTransaction'), $this->intervalo);
				//if($debug) echo "\r\n".'<br>el extra es '.$extras.' ';
				//$precio +=  $extras; 
				//$this->reservas->price = $this->reservas->price + $extras;

				
				/*
				echo 'luz: '.$this->price_light.'<br>';
				echo 'pista: '.$this->price_court.'<br>';
				echo 'precio: '.$this->price.'<br>';
				*/
	    	$data = array(
	               'price' => $precio,
	               'price_court' => $this->price_court,
	               'price_light' => $luz,
	               'price_supl1' => $this->price_supl1,
		             'modify_user' => $this->session->userdata('user_id'),
		             'modify_time' => date($this->config->item('log_date_format')),
		             'modify_ip' => $this->session->userdata('ip_address')
	      );
	      $this->db->where('id_transaction', $id_transaction);
	      $this->db->where('intervalo', $this->intervalo);
				$this->db->update('booking', $data);
				//echo $this->db->last_query();
				
			}	

			//log_message('debug',$this->db->last_query());
			//exit();
			return NULL;			
		        
    }





    function setPriceExtra($id_transaction, $field, $quantity, $interval = 'all') {

    	//print_r( $this->session);
    	$luz = 0; $precio = 0;
    	
      $sql = "SELECT * FROM booking where Id_Transaction = ? order by intervalo"; 
			$query = $this->db->query($sql, array($id_transaction));
			//$count = $this->db->count_all_results();
			//$precio_luz = $quantity / $count;
			//echo 'transaccion: '.$id_transaction.'<br>';
			$i = 0; $precio = 0;
			foreach ($query->result() as $row)
			{
				$actualiza = FALSE;
				if($interval == 'all') $actualiza = TRUE;
				if($interval == 'first' && $i == 0) $actualiza = TRUE;
				$this->intervalo = $row->intervalo;
				
				$precio = 0;
				if($field != 'price_court' ) $precio += $row->price_court;
				//log_message('debug','Sumo precio pista ('.$row->price_court.') y queda '.$precio);
				if($field != 'price_light' ) $precio += $row->price_light;
				//log_message('debug','Sumo precio luz ('.$row->price_light.') y queda '.$precio);
				if($field != 'price_supl1' ) $precio += $row->price_supl1;
				//log_message('debug','Sumo precio supl1 ('.$row->price_supl1.') y queda '.$precio);
				if($field != 'price_supl2' ) $precio += $row->price_supl2;
				//log_message('debug','Sumo precio price_supl2 ('.$row->price_supl2.') y queda '.$precio);
				if($field != 'price_supl3' ) $precio += $row->price_supl3;
				//log_message('debug','Sumo precio price_supl3 ('.$row->price_supl3.') y queda '.$precio);
				if($field != 'price_supl4' ) $precio += $row->price_supl4;
				//log_message('debug','Sumo precio price_supl4 ('.$row->price_supl4.') y queda '.$precio);
				$precio +=  $quantity;
				//log_message('debug','Precio final: '.$precio);

				$data = array(
	               'price' => $precio,
	               $field => $quantity,
		             'modify_user' => $this->session->userdata('user_id'),
		             'modify_time' => date($this->config->item('log_date_format')),
		             'modify_ip' => $this->session->userdata('ip_address')
				);
				if($actualiza){
					$this->db->where('id_transaction', $id_transaction);
					$this->db->where('intervalo', $this->intervalo);
					 $this->db->update('booking', $data);
					 log_message('debug',$this->db->last_query());
					 if($interval == 'first') break;
					}
				//echo $this->db->last_query();
				
				$i++;
			}	

			log_message('debug',$this->db->last_query());
			//exit();
			return NULL;			
		        
    }



##############################################################################

    function getPrice( $options = array()) {
    	# Devuelve precio del intervalo del día para la pista solicitada
    	
    	$debug = FALSE;
//echo "A";    	
    	if(!$this->date || !$this->intervalo) return NULL;
//echo "B";    	
    	$id = $this->court;
    	$date = $this->date;
    	$time = $this->intervalo;
    	$group = $this->group;
    	if(!isset($group)) $group = 9;
    	if($debug) print('<br><pre>');
			$weekday=@date('N', strtotime($date));
			$interval=@date($this->config->item('hour_db_format'), strtotime($time));


				# Compruebo si hay un horario especial activo para la fecha dada para todas las pistas
		    $sql = "SELECT id_price FROM prices_specials WHERE status = 1 and type = 2 and (id_court = 0 or id_court = ?) and date = ? ORDER BY id_court DESC  LIMIT 1"; 
				$query = $this->db->query($sql, array($id, $date));
				if($debug) echo '<br>sesion: '.$this->db->last_query();

				if ($query->num_rows() > 0) {	
					$row = $query->row();
					$id_price=$row->id_price;
					if($debug) 'tarifa encontrada: '.$id_price;
				} else {
					# Compruebo si hay un horario especial activo para una fecha anual para todas las pistas
					$date_tmp=explode("-", $date);
					$date_anual='%'.$date_tmp[1]."-".$date_tmp[2];
					
			    $sql = "SELECT id_price FROM prices_specials WHERE status = 1 and type = 1 and (id_court = 0 or id_court = ?) and date LIKE ? ORDER BY id_court DESC LIMIT 1"; 
					$query = $this->db->query($sql, array($id, $date_anual));
					if($debug) echo '<br>sesion: '.$this->db->last_query();
					if ($query->num_rows() > 0) {	
						$row = $query->row();
						$id_price=$row->id_price;				
						if($debug) 'tarifa encontrada: '.$id_price;
					} 					
				}			
			# Si no se ha definido el precio con las tablas de tarifas para dias especiales, busco la tarifa de la pista
			if(!isset($id_price) || $id_price=="") {			
				# Recupero tarifa
		    $sql = "SELECT id_price FROM courts WHERE id = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($id));
	//echo $this->db->last_query();
			//log_message('debug', 'SQL: '.$this->db->last_query());
				if ($query->num_rows() > 0) {	
	//echo "C";    	
					$row = $query->row();
					$id_price=$row->id_price;
				} else return NULL;
			}


			//if($debug) exit('--------------------tarifa: '.$id_price);
				if($debug) print("\r\n".'--------------------tarifa: '.$id_price);
			
				if($id_price!="") {
if($debug) echo "D";    	
					# Debo comprobar si el día está definido como festivo en algún calendario para saber qué consulta hacer
					$datos = array(
						'date' => $date,
						'time' => $time,
						'group' => $group
					);
				if($debug) print_r($datos);
					
					$precio = $this->app_common->getPriceValue($id_price, $datos);
					if($debug) echo "\r\n<br>PRECIO--".$precio;    
					
					if(!$this->getLightPrice()) $this->price_light = 0;
					$this->price = $precio;
					$this->price_court = $precio;
					//exit();	
					return TRUE;

/*					
					# Si no es festivo
			    $sql = "SELECT prices.quantity, prices.by_group, prices.by_weekday, prices.by_time FROM prices WHERE prices.id = ? and prices.active = '1' and prices.start_date <= ? and prices.end_date >= ?  LIMIT 1"; 
					$query = $this->db->query($sql, array($id_price, $date, $date));
					if($debug) 		print_r($query);

					//log_message('debug', 'SQL: '.$this->db->last_query());
					if($debug) echo $this->db->last_query();
					if ($query->num_rows() > 0) {	
if($debug) echo "E";    	
						$row = $query->row();
						$quantity = $row->quantity;
						$by_group = $row->by_group;
						$by_weekday = $row->by_weekday;
						$by_time = $row->by_time;
						
						if ($by_weekday == '1' && $by_time == '0') {
							
							# Si la tarifa es solo de weekday y no de time, busco los registros con weekday='0'
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and weekday = '0' and start_date <= ? and end_date >= ? and time = ?  LIMIT 1"; 
							$query2 = $this->db->query($sql2, array($id_price, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							if($debug) echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;
							
						} elseif ($by_weekday == '1' && $by_time == '1' && $by_group == '1') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and id_group = ? and weekday = ? and start_date <= ? and end_date >= ? and time = ?  order by quantity desc LIMIT 1"; 
							$query2 = $this->db->query($sql2, array($id_price, $group, $weekday, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							if($debug) echo $this->db->last_query();
								$row2 = $query2->row();
								if($debug) print_r($row2);
								if($debug) print_r($query2->result_array());
								$quantity = $row2->quantity;		
								if($debug) echo 'Precio obtenido: '.$quantity;
																			
						} elseif ($by_weekday == '0' && $by_time == '1' && $by_group == '1') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and id_group = ? and weekday = 0 and start_date <= ? and end_date >= ? and time = ?  order by quantity desc LIMIT 1"; 
							$query2 = $this->db->query($sql2, array($id_price, $group, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							if($debug) echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
												
						}	 elseif ($by_weekday == '1' && $by_time == '1' && $by_group == '0') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and weekday = ? and start_date <= ? and end_date >= ? and time = ? order by quantity desc  LIMIT 1"; 
							$query2 = $this->db->query($sql2, array($id_price, $weekday, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
												
						} elseif($by_group == '1') {
							
							# Si la tarifa es de grupos, busco los registros en la tabla adecuada
					    $sql2 = "SELECT quantity FROM prices_by_group WHERE id_price = ? and id_group = ? and start_date <= ? and end_date >= ?"; 
							$query2 = $this->db->query($sql2, array($id_price, $group, $date, $date));
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;							
							
						}
						
						
						if(!$this->getLightPrice()) $this->price_light = 0;
						$this->price = $quantity;
						$this->price_court = $quantity;
						//echo "<br>--".$quantity;    exit();	
						return TRUE;
					} else return NULL;
					//date('H:i', strtotime($row->interval))
					
					*/
					# Si es festivo
					# ...
					
				} else return NULL;
    }





##############################################################################

    function getLightPrice() {
    	# Devuelve precio de la luz para el intervalo del día para la pista solicitada
//echo "A";    	
    	if(!$this->date || !$this->intervalo) return NULL;
    	$this->CI =& get_instance();
    	$this->CI->load->model('Pistas_model', 'pistas', TRUE);
//echo "B";    	
    	$id = $this->court;
    	$date = $this->date;
    	$time = $this->intervalo;
    	$group = $this->group;
    	if(!isset($group)) $group = 9;
    	
			$weekday=@date('N', strtotime($date));
			$interval=@date($this->config->item('hour_db_format'), strtotime($time));
			
			# Recupero tarifa
			$id_price=$this->CI->pistas->getCourtLightPrice($id);
			
				if($id_price!="") {
//echo "D";    	
					# Debo comprobar si el día está definido como festivo en algún calendario para saber qué consulta hacer
					$datos = array(
						'date' => $date,
						'time' => $time,
						'group' => $group
					);
					
					$precio = $this->app_common->getPriceValue($id_price, $datos);
					$this->price_light = $precio;
//echo "<br>--".$quantity;    	
					return TRUE;
					/*
					# Si no es festivo
			    $sql = "SELECT prices.quantity, prices.by_group, prices.by_weekday, prices.by_time FROM prices WHERE prices.id = ? and prices.active = '1' and prices.start_date <= ? and prices.end_date >= ?  LIMIT 1"; 
					$query = $this->db->query($sql, array($id_price, $date, $date));
					//log_message('debug', 'SQL: '.$this->db->last_query());
					//echo $this->db->last_query();
					if ($query->num_rows() > 0) {	
//echo "E";    	
						$row = $query->row();
						$quantity = $row->quantity;
						$by_group = $row->by_group;
						$by_weekday = $row->by_weekday;
						$by_time = $row->by_time;
						
						if ($by_weekday == '1' && $by_time == '0') {
							
							# Si la tarifa es solo de weekday y no de time, busco los registros con weekday='0'
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and weekday = '0' and start_date <= ? and end_date >= ? and time = ?"; 
							$query2 = $this->db->query($sql2, array($id_price, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;
							
						} elseif ($by_weekday == '1' && $by_time == '1' && $by_group == '1') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and id_group = ? and weekday = ? and start_date <= ? and end_date >= ? and time = ?"; 
							$query2 = $this->db->query($sql2, array($id_price, $group, $weekday, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
												
						}	 elseif ($by_weekday == '1' && $by_time == '1' && $by_group == '0') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
					    $sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and weekday = ? and start_date <= ? and end_date >= ? and time = ?"; 
							$query2 = $this->db->query($sql2, array($id_price, $weekday, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
												
						} elseif($by_group == '1') {
							
							# Si la tarifa es de grupos, busco los registros en la tabla adecuada
					    $sql2 = "SELECT quantity FROM prices_by_group WHERE id_price = ? and id_group = ? and start_date <= ? and end_date >= ?"; 
							$query2 = $this->db->query($sql2, array($id_price, $group, $date, $date));
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;							
							
						}
						
						
						$this->price_light = $quantity;
//echo "<br>--".$quantity;    	
						return TRUE;
					} else return NULL;
					//date('H:i', strtotime($row->interval))
					
					
					# Si es festivo
					# ...
					*/
				} else return NULL;
					
    }



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas UNIFICADAS, en formato array, 
# -------------------------------------------------------------------
	public function get_last_bookings($filters = NULL, $order = NULL, $orderway = NULL , $limit = NULL) 
	{
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$records = $this->get_global_list($filters, $order, $orderway, $limit );
		
		$min_time=""; $max_time=""; $precio=0; $registro = array();
		foreach ($records['records']->result() as $row)
		{
			if(!isset($transaccion) || $transaccion=="") $transaccion = $row->id_transaction;
			
			//echo $row->id_transaction.' # ' .$transaccion.'<br>';
			if($transaccion != $row->id_transaction && $transaccion!="") {
				#Sólo si se ha cambiado de Id de transacción
				$record_items[] = $registro;
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
			else $no_cost=img( array('src'=>'images/accept.png', "align"=>"absmiddle", "border"=>"0"));
			
			if($row->id_user) $usuario = $row->first_name.' '.$row->last_name.'('.$row->phone.')';
			else $usuario = $row->user_desc.'('.$row->user_phone.')';
			if(trim($usuario)=="") $usuario="No registrado";
			$reserve_interval = $this->pistas->getCourtInterval($row->id_court);
			
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
				$img_light= '<img id="luz" "title"="Luz contratada" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/luz.png\'>';
				$button_light = '-';
			}
			else
			{
				$img_light= '<img id="no_luz" "title"="Luz no contratada" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/luz_no.png\'>';
				$button_light = '<img id="luz" "title"="Luz contratada" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/luz.png\'>';
			}
			$button_cancel= '<img id="cancelar" "title"="Cancelar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/close.png\'>';
			$button_change= '<a href="javascript:modificarReserva(\''.$row->id_transaction.'\',\''.$min_time.'\',\''.$max_time.'\');"><img id="modificar" "title"="Modificar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/refresh.png\'></a>';
			//$button_change=
			if ($row->status < 9) $button_payment= '<img id="payment" "title"="Cobrar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/coins.png\'>'; 
			else $button_payment = '-'; 
			
	

			$registro = array(
				$row->id_transaction,
				$row->id_booking,
				date($this->config->item('reserve_date_filter_format') ,strtotime($row->fecha)),
				$min_time,
				$max_time,
				$row->court_name,
				$img_light,
				$paint_status,
				$this->lang->line($row->paymentway_desc)!="" ? $this->lang->line($row->paymentway_desc) : '-',
				$row->id_user,
				$usuario,
				$precio,
				$no_cost,
				//$button_validate,
				$button_cancel,
				$button_change,
				$button_payment,
				$button_light
			);	
			//print("<pre>");print_r($row);print("</pre>");
			//$record_items[] = $registro;
		}
		$record_items[] = $registro;
		//print("<pre>");print_r($record_items);print("</pre>");
		return $record_items;
	}
	




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas
# -------------------------------------------------------------------
	public function get_listall() 
	{
		$add_where=$this->session->flashdata('where');
		
		return $this->get_global_list($add_where);
	}
	


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas del día actual
# -------------------------------------------------------------------
	public function get_list_by_day($date = NULL) 
	{
		$this->CI =& get_instance();

		//Select table name
		$table_name = "booking";
		
		//Build contents query
		$this->db->select('id_booking, date, intervalo, courts.name, no_cost, status, price, id_user, user_desc')->from($table_name);
		$this->CI->flexigrid->build_query();
		$this->db->join('courts', 'courts.id=booking.id_court');

		if (isset($date) && trim($date)!="") $this->db->where('date', $date);

		$add_where=$this->session->flashdata('where');
		log_message('debug', 'WHERE--: '.$add_where);
		if (isset($add_where) && trim($add_where)!="") $this->db->where($add_where);
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo "AAAAAA".$this->db->last_query()."CCCCCCCCCCC";
		log_message('debug', 'SQL: '.$this->db->last_query());
		//Build count query
		$this->db->select('count(id_booking) as record_count')->from($table_name);
		$this->CI->flexigrid->build_query(FALSE);
		$this->db->join('courts', 'courts.id=booking.id_court');
		if (isset($date) && trim($date)!="") $this->db->where('date', $date);

		//log_message('debug', 'WHERE: '.$add_where);
		if (isset($add_where) && trim($add_where)!="") $this->db->where($add_where);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
	//print("<pre>");print_r($return);
		//Return all
		return $return;
	}



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas
# -------------------------------------------------------------------
	public function get_global_list($filters="", $orderby="", $orderbyway="", $limit="", $flexigrid=FALSE) 
	{
		$this->CI =& get_instance();

		//Select table name
		$table_name = "booking";
		
		//Build contents query
		$this->db->select('booking.id as id, id_booking, id_transaction, id_user, session, id_court, `date` as fecha, '.
						'intervalo, `status`, id_paymentway, price, no_cost, no_cost_desc, booking.user_desc, user_phone, '.
						'booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, '.
		 				'booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, '.
						'meta.last_name as last_name,  meta.phone as phone, zz_booking_status.description as status_desc, '.
						'zz_paymentway.description as paymentway_desc, booking.price_light as price_light, booking.price_court as price_court', FALSE)->from($table_name);
		if($flexigrid) $this->CI->flexigrid->build_query();
		//$this->CI->flexigrid->build_query();
		$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		$this->db->join('meta', 'booking.id_user=meta.user_id', 'left outer');
		$this->db->join('zz_booking_status', 'booking.status=zz_booking_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'booking.id_paymentway=zz_paymentway.id', 'left outer');


		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
	
		if (isset($orderby) && trim($orderby)!="" && isset($orderbyway) && trim($orderbyway)!="") $this->db->order_by($orderby, $orderbyway);
		
		if (isset($limit) && trim($limit)!="") $this->db->limit($limit);
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo "A<br>A<br>A<br>A<br>A<br>A".$this->db->last_query()."CCCCCCCCCCC";
		log_message('debug', 'SQL: '.$this->db->last_query());
		//Build count query
		
		
		# Para devolver el numero de registros
		$this->db->select('count(id_booking) as record_count')->from($table_name);
		$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
		if (isset($orderby) && trim($orderby)!="" && isset($orderbyway) && trim($orderbyway)!="") $this->db->order_by($orderby, $orderbyway);
		if (isset($limit) && trim($limit)!="") $this->db->limit($limit);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		//Return all
		return $return;
	}
	


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado agrupado por pistas, para ocupacion
# -------------------------------------------------------------------
	public function get_court_ocupation($filters="") 
	{

		//Select table name
		$table_name = "courts";
		
		//Build contents query
		$this->db->select('id_court, courts.name as court, `date` as date, sum(price-(no_cost*price)) as cobrado, sum(price) as facturable, count(courts.id) as intervalos', FALSE)->from($table_name);
		$this->db->join('booking', 'courts.id=booking.id_court', 'left outer');
		
		$this->db->where('(booking.status > 5 OR booking.status is null)');
		$this->db->where('courts.active > 0');

		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
	
		$this->db->group_by(array("id_court", "date", "courts.name"));
		$this->db->order_by('id_court, date', 'ASC');
		//Get contents
		$return['records'] = $this->db->get();
		//echo "A<br>A<br>A<br>A<br>A<br>A".$this->db->last_query()."CCCCCCCCCCC";
		//log_message('debug', 'SQL: '.$this->db->last_query());
		//Build count query
		
		
		//Return all
		return $return;
	}
	
	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve la mayor fecha de reserva hecha
# -------------------------------------------------------------------
	public function get_max_booking_date($filters="") 
	{

		//Select table name
		$table_name = "booking";
		
		//Build contents query
		$this->db->select('max(`date`) as date', FALSE)->from($table_name);

		//Get contents
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$fecha = $row->date;
		}
		//echo "A<br>A<br>A<br>A<br>A<br>A".$this->db->last_query()."CCCCCCCCCCC";
		//log_message('debug', 'SQL: '.$this->db->last_query());
		//Build count query
		
		
		//Return all
		return $fecha;
	}
	


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado agrupado por pistas, para ocupacion
# -------------------------------------------------------------------
	public function get_complete_court_ocupation($fecha1=NULL, $fecha2=NULL, $hora1=NULL, $hora2=NULL) 
	{
		
		$this->CI =& get_instance();
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$pistas=$this->pistas->getAvailableCourtsArray('','');
		//print_r($pistas);
		if(!isset($fecha1) || !isset($fecha2)) return NULL;
		
		$filters="(`date` >= '".$fecha1."' OR `date` IS NULL) AND (`date` <= '".$fecha2."' OR `date` IS NULL)";
		if(isset($hora1) && $hora1!="") $filters .= "AND (intervalo >= '".$hora1."' OR intervalo IS NULL)";
		else $filters .= "AND (intervalo >= '00:00:00' OR intervalo IS NULL)";
		if(isset($hora2) && $hora2!="") $filters .= "AND (intervalo <= '".$hora2."' OR intervalo IS NULL)";
		else $filters .= "AND (intervalo <= '23:59:59' OR intervalo IS NULL)";
		$this->CI =& get_instance();
		
		$ocupacion = $this->get_court_ocupation($filters);
			//print("aaaaaaaaaa<pre>");print_r($ocupacion['records']->result_array());print('BBBBB<br>');
		$resultado=array(); $pista="";
		foreach($pistas as $code => $pista) {
			if($code!='') $resultado[$code]=array('id' => $code, 'name' => $pista, 'total_horas' => 0, 'total_facturado' => 0, 'total_facturable' => 0, 'maximo_horas' => 0);
		}
		foreach ($ocupacion['records']->result() as $row) {
			if($pista=="" || $pista != $row->id_court) {
				$pista = $row->id_court;
				$resultado[$row->id_court]=array('id' => $row->id_court, 'name' => $row->court, 'total_horas' => 0, 'total_facturado' => 0, 'total_facturable' => 0, 'maximo_horas' => 0);
			}
			$resultado[$row->id_court]['total_horas'] += number_format(($row->intervalos / 2), 2);
			$resultado[$row->id_court]['total_facturado'] += ($row->cobrado);
			$resultado[$row->id_court]['total_facturable'] += ($row->facturable);
			
			# Cargo valores de las propiedades del modelo
			$this->date=$row->date;			
			$this->court=$row->id_court;			
			$this->CI->pistas->id=$row->id_court;			

		}
		//print("aa<pre>");print_r($resultado);//exit();
		foreach($resultado as $pista) {
			#Recorro todo el intervalo de dias del rango para calcular las horas totales que se podían haber alquilado
			$fecha=$fecha1;
			while($fecha<=$fecha2) {
				
				# Calculo de horas disponibles por pista y dia
				$this->getSpecialTimetableByCourt();
				//print("a");print_r($this->reservas->availability);
				if(!$this->availability) $this->getSpecialTimetable();
				//print("b");print_r($this->reservas->availability);
				if(!$this->availability) $this->availability=$this->CI->pistas->getTimetable($fecha);
				$total_horas=0;
				//print("c - ".$hora1.' - '.$hora2);print_r($this->availability);
				#Sumo 1 (osea, la disponibilidad, si está disponible.. osea $valie[1], si la hora está dentro del rango
				foreach($this->availability as $inte => $value) if($value[0] >= $hora1 && $value[0] < $hora2) {$total_horas+=$value[1]; }
				//print("<pre>");print_r($this->availability);
				//echo "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA".$total_horas;
				$resultado[$pista['id']]['maximo_horas'] +=($total_horas/2);
				
				$fecha=date($this->config->item('date_db_format'), strtotime($fecha . " +1 day"));
				//echo $fecha;
			}

			
			
			//print_r($row);
		}
		
		//print("<pre>");print_r($resultado);
		return $resultado;

	}
	
# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado agrupado por pistas, para ocupacion
# -------------------------------------------------------------------
	public function change_reserve($id_transaction_old,
									$hora_inicio = NULL,
									$minuto_inicio = NULL,
									$hora_fin = NULL,
									$minuto_fin = NULL) 
	{

		log_message('debug', 'Datos recibidos-> id_transaction: '.$id_transaction_old.'; intervalo: '.$intervalo.'; hora inicio: '.$hora_inicio.'; minuto inicio: '.$minuto_inicio.'; Fecha: '.$fecha.'; Id_court: '.$id_court);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);

		//CALCULO DIFERENCIA ENTRE FECHAS PARA CONOCER INTERVALOS		
		$result_message = '';
		$result = false;
		if(!isset($hora_inicio) || !isset($hora_fin)) return NULL;
		$hora_total_ini = ($hora_inicio * 60) + $minuto_inicio;
		$hora_total_fin = ($hora_fin * 60) + $minuto_fin;
		$minutos_dif = $hora_total_fin - $hora_total_ini;
		$id_court = null;
		$fecha_reserva = null;
		$id_user = null;
		$status = null;
		$id_paymentway = null;
		$info=$this->reservas->getBookingInfoById($id_transaction_old);
		$reserve_interval = $this->pistas->getCourtInterval($info['id_court']);
		//$reserve_interval = $this->config->item('reserve_interval');
		//COMPRUEBO QUE LAS HORAS SON CORRECTAS
		if ($hora_total_fin > $hora_total_ini)
		{
			if (($minutos_dif % $reserve_interval) == 0)
			{
				//CALCULO LOS INTERVALOS (REGISTROS BOOKING)
				$intervalos = ($minutos_dif / $reserve_interval);
				//CALCULO INTERVALOS ORIGINALES
				$sql_select_interv = "SELECT * FROM booking ".
									 "WHERE ID_TRANSACTION = ?";					
				$query = $this->db->query($sql_select_interv, array($id_transaction_old));
				$intervalos_old = $query->num_rows();
				//SI EL CAMBIO NO ES DEL MISMO TIEMPO SE CANCELA				
				if ($intervalos_old == $intervalos)
				{	
					if ($intervalos_old > 0) 
					{
						foreach ($query->result() as $row)
						{
							/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */						
							$array_all[$row->id] = array(
				                   'id'  => $row->id,
				                   'id_booking'  => $row->id_booking,
				                   'id_user'  => $row->id_user,
				                   'id_court'  => $row->id_court,
				                   'date'  => $row->date,
				                   'intervalo'  => $row->intervalo,
				                   'status'  => $row->status,
				                   'id_paymentway'  => $row->id_paymentway,
				                   'price'  => $row->price,
				                   'no_cost'  => $row->no_cost,
				                   'no_cost_desc'  => $row->no_cost_desc,
				                   'user_nif'  => $row->user_nif,
				                   'user_desc'  => $row->user_desc,
				                   'user_phone'  => $row->user_phone,
				                   'id_transaction'  => $row->id_transaction,
				                   'booking_code'  => $row->booking_code);
							$fecha_reserva = $row->date;
							$id_court = $row->id_court;
							$id_user = $row->id_user;
							$status = $row->status;
							$price_light = $row->price_light;
							log_message('debug', 'Precio de la luz de segmento: '.$price_light);
							$id_paymentway = $row->id_paymentway;
							
						}
							log_message('debug', 'Precio de la luz general: '.$price_light);
						//CHEQUEO DISPONIBILIDAD DE LA NUEVA RESERVA
						//RELLENO CAMPOS NECESARIOS
						$this->court = $id_court; 
						$this->id_transaction = $id_transaction_old;					
						$this->date = $fecha_reserva;
						//debug.log_message('debug','Fecha Reserva: '.$fecha_reserva);
						$result_availability = true;
						for ($i = 0 ; $i < $intervalos ; $i ++) 
						{						
							$suma_minutos = ($i * $reserve_interval) + $minuto_inicio;
							//relleno los campos necesarios para chequear disponibilidad
							$this->intervalo = date('H:i',mktime($hora_inicio,$suma_minutos,0,1,1,1998));
							//$this->intervalo = $intervalo_reserva;
							if (!$this->checkExactAvailabilityChange())
							{
								log_message('debug', 'No hay disponibilidad para el intervalo '.$this->intervalo);
								$result_message = 'No hay disponibilidad para el intervalo '.$this->intervalo;
								$result_availability = false;
								return $result_message;
							}
						}
						//log_message('debug', 'ID_PAYMENT='.$id_paymentway);
						//SI HAY DISPONIBILIDAD INSERTO VALORES
						$sesion_insert = str_replace('{{slash}}', '\\',$this->session->userdata('session_id'));
						$id_transaction_new = $sesion_insert.$this->court."-".date('U', strtotime($this->date." ".$this->intervalo));
						//&calculate_prices = false;
						for ($i = 0 ; $i < $intervalos ; $i ++) 
						{					
							$suma_minutos = ($i * $reserve_interval) + $minuto_inicio;
							//relleno los campos necesarios para chequear disponibilidad
							$this->intervalo = date('H:i',mktime($hora_inicio,$suma_minutos,0,1,1,1998));
							
							//debug.log_message('debug','Intervalo: '.$this->intervalo);
							//INSERTO VALORES
							if  ($this->getPrice())
							{
								if($price_light != 0) $price_light = $this->price_light;
								else $price_light = 0;
								log_message('debug', 'Precio de la luz antes de grabar: '.$price_light);

								$data = array(
				               'id_booking' => $this->court."-".date('U', strtotime($this->date." ".$this->intervalo)),
				               //PREGUNTAR SI EL ID ES NUEVO O NO
				               'id_transaction' => $id_transaction_new,
				               'booking_code' => $this->app_common->reserve_encode($this->session->userdata('idTransaction')),
				               'id_user' => $id_user,
				               'id_court' => $this->court,
				               'date' => $this->date,
				               'session' => $sesion_insert,
				               'intervalo' => $this->intervalo,
				               //codigo comentado, bbdd desactualizada
				               'price' => $this->price,
				               'price_light' => $price_light,
				               'price_court' => $this->price_court,
				               'id_paymentway' => $id_paymentway,
				               /*'price_supl1' => 0,
				               'price_supl2' => 0,
				               'price_supl3' => 0,
				               'price_supl4' => 0,
				               'price_supl5' => 0,*/
				               'status' => $status,
				               'create_user' => $this->session->userdata('user_id'),
				               'create_time' => date(DATETIME_DB));
								# Si el status es mayor que cero, marco la reserva... Si no, solo ejecuto la funcionalidad de marcar en sesion
								$this->db->insert('booking', $data);
								log_message('debug', 'SQL: '.$this->db->last_query());
							}
							else 
							{
								log_message('debug', 'No se han encontrado precios para el intervalo:'.$this->intervalo);
								$result_message = "No se han encontrado precios para el intervalo: $this->intervalo";
								return $result_message;	
							}
							
						}
						
						//UNA VEZ INSERTADOS LOS REGISTROS NUEVOS, BORRO LOS ANTERIORES
						if ($this->cancel_reserve($id_transaction_old, 'Cancelacion por cambio de horas'))
						{
							log_message('debug', 'Ok..');
							$result_message = "ok|".$id_transaction_new;	
						}
						else
						{
							log_message('debug', 'Importante: La reserva anterior no se ha borrado');
							$result_message = "Importante: La reserva anterior no se ha borrado";
							return $result_message; 
						}
					}
					else
					{
						log_message('debug', 'La reserva no tenía pistas seleccionadas');
						$result_message = 'La reserva no tenía pistas seleccionadas';	
					}
				}
				else
				{
					log_message('debug', 'No coincide el mismo tiempo de reserva');
					$result_message = 'No coincide el mismo tiempo de reserva';
					return $result_message;	
				}					
			}
			else
			{
				log_message('debug', 'Las horas introducidas no corresponden con ningún horario');
				$result_message = 'Las horas introducidas no corresponden con ningún horario';
				return $result_message;	
			}
		}
		else
		{
			//HORA FIN MENOR HORA INICIO
			log_message('debug', 'La hora de inicio debe ser mayor que la hora de fin');
			$result_message = 'La hora de inicio debe ser mayor que la hora de fin';
			return $result_message;	
		}
		return $result_message;

	}
	
	##############################################################################
	
	
# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado agrupado por pistas, para ocupacion
# -------------------------------------------------------------------
	public function change_reserve_get($id_transaction_old,
									$intervalo,
									$hora_inicio = NULL,
									$minuto_inicio = NULL,
									$fecha = NULL,
									$id_court = NULL) 
	{
		
		log_message('debug', 'Datos recibidos-> id_transaction: '.$id_transaction_old.'; intervalo: '.$intervalo.'; hora inicio: '.$hora_inicio.'; minuto inicio: '.$minuto_inicio.'; Fecha: '.$fecha.'; Id_court: '.$id_court);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//CALCULO DIFERENCIA ENTRE FECHAS PARA CONOCER INTERVALOS		
		$result_message = '';
		$result = false;
		$hora_total_ini = ($hora_inicio * 60) + $minuto_inicio;
		$fecha_reserva = null;
		$id_user = null;
		$status = null;
		$id_paymentway = null;
		//$reserve_interval = $this->config->item('reserve_interval');
		$info=$this->reservas->getBookingInfoById($id_transaction_old);
		$reserve_interval = $this->pistas->getCourtInterval($info['id_court']);
			if (($minuto_inicio % $reserve_interval) == 0)
			{
				//CALCULO DATOS ORIGINALES
				$this->db->select('*, MIN(INTERVALO) AS intervalo_ini, MAX(INTERVALO) AS intervalo_fin, count(*) num_intervalos', FALSE)->from('booking');
		    	$this->db->where('id_transaction',$id_transaction_old); 
		    	$this->db->group_by('id_transaction', 'asc');
				$record = $this->db->get();
				if ($record->num_rows() > 0) 
				{
					//log_message('debug', 'SQL: '.$this->db->last_query());
					$row = $record->row();					
					//Almaceno los datos que necesito
					//SI EL CAMBIO NO ES DEL MISMO TIEMPO SE CANCELA
						/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */						
					$array_booking = Array(
		                   'id'  => $row->id,
		                   'id_booking'  => $row->id_booking,
		                   'id_user'  => $row->id_user,
		                   'id_court'  => $row->id_court,
		                   'date'  => $row->date,
		                   'intervalo'  => $row->intervalo_ini,
		                   'status'  => $row->status,
		                   'id_paymentway'  => $row->id_paymentway,
		                   'price'  => $row->price,
		                   'no_cost'  => $row->no_cost,
		                   'no_cost_desc'  => $row->no_cost_desc,
		                   'user_nif'  => $row->user_nif,
		                   'user_desc'  => $row->user_desc,
		                   'user_phone'  => $row->user_phone,
		                   'id_transaction'  => $row->id_transaction,
		                   'booking_code'  => $row->booking_code,
		                   'intervalo_fin'  => $row->intervalo_fin,
		                   'num_intervalos'  => $row->num_intervalos);
					$fecha_reserva_old = $row->date;
					$id_court_old = $row->id_court;
					$id_user = $row->id_user;
					$status = $row->status;
					$id_paymentway = $row->id_paymentway;
					$new_day = false;
					//CHEQUEO DISPONIBILIDAD DE LA NUEVA RESERVA
					//RELLENO CAMPOS NECESARIOS
					
					//$array_booking = $array_booking_ini[0];
					$suma_minutos_final = ($array_booking['num_intervalos'] * $reserve_interval) + $minuto_inicio;	
					$intervalo_final = date('H:i',mktime($hora_inicio,$suma_minutos_final,0,1,1,1998));
					//Compruebo si la nueva reserva cambia de día y de pista o no coinciden los horarios
					if ($id_court_old != $id_court)
					{
						$new_day = true;
					}
					else if ($fecha != $fecha_reserva_old)
					{
						$new_day = true;
					}
					else if (($intervalo > $array_booking['intervalo_fin']) && ($intervalo_final < $array_booking['intervalo_ini']))
					{
						$new_day = true;	
					}
					//chequeo normal				
					$this->court = $id_court; 
					$this->id_transaction = $id_transaction_old;					
					$this->date = $fecha;
					$this->intervalo = $intervalo;
					
					//primero chequeo que esté disponible la nueva fecha
					for ($i = 0 ; $i < $array_booking['num_intervalos']; $i ++) 
					{						
						$suma_minutos = ($i * $reserve_interval) + $minuto_inicio;
						//relleno los campos necesarios para chequear disponibilidad
						$this->intervalo = date('H:i',mktime($hora_inicio,$suma_minutos,0,1,1,1998));
						//$this->intervalo = $intervalo_reserva;
						//chequeo si coincide el intervalo con la reserva anterior
						if (!$new_day)
						{
							//
							if ((date('H:i',strtotime($this->intervalo)) >= date('H:i',strtotime($array_booking['intervalo']))) &&
								(date('H:i',strtotime($this->intervalo)) <= date('H:i',strtotime($array_booking['intervalo_fin']))))
							{	
								//Coincide, no compruebo nada							
								//log_message('debug', 'Coincide..');
							}
							else
							{				
								if (!$this->checkExactAvailability())
								{
									log_message('debug', 'No hay disponibilidad para el intervalo'.$this->intervalo);
									$result_message = 'No hay disponibilidad para el intervalo '.$this->intervalo;
									$result_availability = false;
									return $result_message;
								}								
							}
						}
						else 
						{						
							if (!$this->checkExactAvailability())
							{
								log_message('debug', 'No hay disponibilidad para el intervalo '.$this->intervalo);
								$result_message = 'No hay disponibilidad para el intervalo '.$this->intervalo;
								$result_availability = false;
								return $result_message;
							}
						}
					}
					//inserto en BBDD, he pasado la comprobacion
				
					//log_message('debug', 'ID_PAYMENT='.$id_paymentway);
					$sesion_insert = str_replace('{{slash}}', '\\',$this->session->userdata('session_id'));
					$id_transaction_new = $sesion_insert.$this->court."-".date('U', strtotime($this->date." ".$this->intervalo));
					//&calculate_prices = false;
					for ($i = 0 ; $i < $array_booking['num_intervalos']; $i ++) 
					{					
						$suma_minutos = ($i * $this->config->item('reserve_interval')) + $minuto_inicio;
						//relleno los campos necesarios para chequear disponibilidad
						$this->intervalo = date('H:i',mktime($hora_inicio,$suma_minutos,0,1,1,1998));
						
						//debug.log_message('debug','Intervalo: '.$this->intervalo);
						//INSERTO VALORES
						if  ($this->getPrice())
						{
							$data = array(
			               'id_booking' => $this->court."-".date('U', strtotime($this->date." ".$this->intervalo)),
			               //PREGUNTAR SI EL ID ES NUEVO O NO
			               'id_transaction' => $id_transaction_new,
						   'booking_code' => $this->app_common->reserve_encode($this->session->userdata('idTransaction')),
			               'id_user' => $id_user,
			               'id_court' => $this->court,
			               'date' => $this->date,
			               'session' => $sesion_insert,
			               'intervalo' => $this->intervalo,
			               //codigo comentado, bbdd desactualizada
			               'price' => $this->price,
			               'price_light' => $this->price_light,
			               'price_court' => $this->price_court,
			               'id_paymentway' => $id_paymentway,
			               /*'price_supl1' => 0,
			               'price_supl2' => 0,
			               'price_supl3' => 0,
			               'price_supl4' => 0,
			               'price_supl5' => 0,*/
			               'status' => $status,
			               'create_user' => $this->session->userdata('user_id'),
			               'create_time' => date(DATETIME_DB));
							# Si el status es mayor que cero, marco la reserva... Si no, solo ejecuto la funcionalidad de marcar en sesion
							$this->db->insert('booking', $data);
							log_message('debug', 'SQL: '.$this->db->last_query());
						}
						else 
						{
							log_message('debug', 'No se han encontrado precios para el intervalo: '.$this->intervalo);
							$result_message = "No se han encontrado precios para el intervalo: $this->intervalo";
							return $result_message;	
						}
						
					}
				
					//UNA VEZ INSERTADOS LOS REGISTROS NUEVOS, BORRO LOS ANTERIORES
					if ($this->cancel_reserve($id_transaction_old, 'Cancelacion por cambio de horas'))
					{
						$result_message = "ok|".$id_transaction_new;
					}
					else
					{
						log_message('debug', 'La reserva anterior no se ha borrado');
						$result_message = "Importante: La reserva anterior no se ha borrado";
						return $result_message; 
					}
				}
				else
				{
					log_message('debug', 'La reserva que se quiere modificar, no existe.');
					$result_message = "Importante: La reserva que se quiere modificar no existe";
					return $result_message; 
				}	
				
				
							
			}
			else
			{
				$result_message = 'Las horas introducidas no corresponden con ningún horario';
				log_message('debug', 'Las horas introducidas no corresponden con ningun horario');
				return $result_message;	
			}
		return $result_message;
	}
	
	##############################################################################
	



    function checkExactAvailabilityChange() {
    	//Modifcar la anterior para que funcione con este cambio
    	# Devuelve si un intervalo está reservado o no
			
	    $sql = "SELECT id FROM booking WHERE status > 0 and date = ? and intervalo = ? and id_court = ? and id_transaction != ? LIMIT 1"; 
			$query = $this->db->query($sql, array($this->date, $this->intervalo, $this->court, $this->id_transaction));
			if ($query->num_rows() > 0) return FALSE;
			else return TRUE;			
				        
    }



	##############################################################################
	




    function cleanBlockReserves() {
    	//Modifcar la anterior para que funcione con este cambio
    	# Devuelve si un intervalo está reservado o no
			
			$this->db->where("TIMESTAMP(create_time,'00:04:00') < now() and status <= '5'");
			$this->db->delete('booking');
			log_message('debug',$this->db->last_query());
			return TRUE;			
				        
    }





	##############################################################################
	
	

    /* FUNCTION PARA VALIDAR UNA RESERVA*/
    function complete_reserve($id_transaction)
    {		
    	log_message('debug', 'entro en la funcion complete_reserve con transaccion '.$id_transaction);
			$data = array('status' => 9, 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update('booking',$data, array('id_transaction' => $id_transaction));
			log_message('debug',$this->db->last_query());
			return ($this->db->affected_rows() >= 1) ? true : false;
    }
	

	##############################################################################
	
	

    /* FUNCTION PARA VALIDAR UNA RESERVA*/
    function validate_reserve($id_transaction)
    {		
		$data = array('status' => 11, 
					'modify_time' => date(date(DATETIME_DB)), 
					'modify_user' => $this->session->userdata('user_id'),
					'modify_ip' => $this->session->userdata('ip_address'));
		$this->db->update('booking',$data, array('id_transaction' => $id_transaction));
		return ($this->db->affected_rows() >= 1) ? true : false;
    }
	

	##############################################################################

    /* FUNCTION PARA CANCELAR UNA RESERVA*/
    function cancel_reserve($id_transaction, $text_cancel)
    {		
    	//chequeo la reserva por si está pagada para devolver el dinero
    	//primero chequeo que la opción está activada
    	/*
    	if ($this->config->item('cancelled_reserve_refund'))
    	{
    		$sql_query = "select status, sum(price) as price_total, max(id_user) as id_user ". 
    					"from booking where id_transaction = ? ".
						"group by status";
    		$data_query = array($id_transaction);
    		$query = $this->db->query($sql_query,$data_query);
    		if ($query->num_rows() > 0) 
			{
				$row = $query->row();
				//si el estado es pagado(9) devolvemos el dinero
				if ($row->status == 9)
				{
					$sql_update = "update meta set prepaid_cash = (prepaid_cash + ?) ".
									"where user_id = ?";
					$data_update = array($row->price_total,
										$row->id_user);
					$this->db->query($sql_update,$data_update);	
				}
			}
    	}
    	*/
    	//copia de seguridad en booking_cancelled
    	$sql_insert = "insert into booking_cancelled ".
					"(id_booking, id_transaction, id_user, booking_code, session, id_court, date, intervalo, status,".
					"cancelation_reason, id_paymentway, price, no_cost, no_cost_desc, user_nif, user_desc, user_phone,".
					"create_user, create_time, create_ip) ".
					"(select id_booking, id_transaction, id_user, booking_code, session, id_court, date, intervalo, status,".
					"?, id_paymentway, price, no_cost, no_cost_desc, user_nif, user_desc, user_phone,".
					"?, ?, ? from booking where id_transaction = ?)";
    	$data = array($text_cancel,  
					$this->session->userdata('user_id'), 
					date(DATETIME_DB),
					$this->session->userdata('ip_address'),
					$id_transaction);
    	$this->db->query($sql_insert,$data);
    	log_message('debug', 'SQL: '.$this->db->last_query());
    	
		$this->db->delete('booking',array('id_transaction' => $id_transaction));
		log_message('debug', 'SQL: '.$this->db->last_query());
		
		return ($this->db->affected_rows() >= 1) ? true : false;
    }




	public function get_data($params = "" , $page = "all")
		{
			$this->load->model('Pistas_model', 'pistas', TRUE);	
			error_reporting(E_ALL);
			# Consulta para extraer la lista de reservas que deberán mostrarse
			$table_name = 'booking';
			
			$max_registros = 999999999;
			if ($page != "all") $max_registros =  (($params ["num_rows"] *  ($params ["page"] - 1)) + $params ["num_rows"])  * 6;
			//exit ('aa'.$max_registros);
			//Build contents query
			$this->db->select('distinct id_transaction', FALSE);
			$this->db->from('(SELECT `date`, intervalo, id_transaction, id_court, id_user, `status`, `id_paymentway` FROM ('.$table_name.') ORDER BY `date` desc LIMIT '.$max_registros.') a');

			$this->db->join('courts', 'courts.id=a.id_court', 'left outer');
			$this->db->join('meta', 'a.id_user=meta.user_id', 'left outer');
			$this->db->join('zz_booking_status', 'a.status=zz_booking_status.id', 'left outer');
			$this->db->join('zz_paymentway', 'a.id_paymentway=zz_paymentway.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where(str_replace('booking.', 'a.', $params['where']));
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			if (empty ($params['orderbyway']) || $params['orderbyway']=='') $params['orderbyway'] = 'ASC';
			//$this->db->order_by('id_transaction', $params['orderbyway']);
						
			if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			$lista_de_reservas = array();
			//Get contents
			$query = $this->db->get(); //echo $this->db->last_query(); exit();
			//log_message('debug',$this->db->last_query());
			if ($page != "all") {
				$lista_de_reservas = array();
				foreach ($query->result() as $row)
				{
					array_push($lista_de_reservas, $row->id_transaction);
				}
			}
			
			
			
			
			# Consulta para extraer la información de esas reservas
			$table_name = 'booking';
			
			//Build contents query
			$this->db->select('booking.id as id, id_booking, id_transaction, id_user, session, id_court, DATE_FORMAT(DATE(booking.date), \'%d-%m-%Y\') as fecha, '.
							'intervalo, `status`, id_paymentway, price, no_cost, no_cost_desc, booking.user_desc, user_phone, '.
							'booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, '.
			 				'booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, '.
							'meta.last_name as last_name,  meta2.first_name as create_first, meta2.last_name as create_last, meta.first_name + \' \' + meta.last_name as complete_name, meta.phone as phone, zz_booking_status.description as status_desc, '.
							'zz_paymentway.description as paymentway_desc, booking.price_light as price_light, booking.price_court as price_court, zz_sports.id as sport, zz_sports.description as sport_desc, shared, DATE_FORMAT(DATE(booking.create_time), \'%d-%m-%Y\') as fecha_creacion, price_supl1, price_supl2, price_supl3, booking.booking_code', FALSE)->from($table_name);

			$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
			$this->db->join('zz_sports', 'zz_sports.id=courts.sport_type', 'left outer');
			$this->db->join('meta', 'booking.id_user=meta.user_id', 'left outer');
			$this->db->join('meta as meta2', 'booking.create_user=meta2.user_id', 'left outer');
			$this->db->join('zz_booking_status', 'booking.status=zz_booking_status.id', 'left outer');
			$this->db->join('zz_paymentway', 'booking.id_paymentway=zz_paymentway.id', 'left outer');
	
	
			if ($page != "all" && count($lista_de_reservas) > 0) {
				if (!empty ($params['where'])) $params['where'].= ' AND ';
				$params['where'].= "booking.id_transaction IN ('".implode("', '", $lista_de_reservas)."')";
			}
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			if (empty ($params['orderbyway']) || $params['orderbyway']=='') $params['orderbyway'] = 'ASC';
			$this->db->order_by('id_transaction', $params['orderbyway']);
			$this->db->order_by('intervalo', $params['orderbyway']);
			
			//if ($page != "all") $this->db->limit ((6 * $params ["num_rows"]) + 6 * ($params ["num_rows"] *  ($params ["page"] - 1)), 0 );
			
			//Get contents
			$query = $this->db->get();
			//log_message('debug',$this->db->last_query());
			//echo $this->db->last_query().'<br>';
			//exit( $this->db->last_query());//.'<br>';
			
			$record_items = array(); $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0; $light_cost=0; $light_desc= '';

			
			foreach ($query->result() as $row)
			{
				if($transaccion=="") $transaccion = $row->id_transaction;
				
				//echo $row->id_transaction.' # ' .$transaccion.'<br>';
				if($transaccion != $row->id_transaction && $transaccion!="") {
					#Sólo si se ha cambiado de Id de transacción

					$record_items[] = $registro;
					//print("<pre>"); print_r($registro);
					$registro=array();
					$min_time=""; $max_time=""; $precio=0; $light_cost=0; $light_desc= '';
					$transaccion = $row->id_transaction;
				}
				// ojo, las imágenes tienen que ser png
				//modificar mas adelante añadiendo un campo en BBDD
				$paint_status = '';
				if ($row->status_desc == '') $paint_status='';
				else  $paint_status = img(array('src'=>'images/'.$row->status_desc.'.png', "align"=>"absmiddle", "border"=>"0", "title"=>$this->lang->line($row->status_desc)));
				
				if($row->no_cost==0) $no_cost='';
				else $no_cost='Si';
				
				/*
				if($row->id_user) $usuario = $row->first_name.' '.$row->last_name.'('.$row->phone.')';
				else $usuario = $row->user_desc.'('.$row->user_phone.')';
				*/
				
				/*
				if($row->id_user) {
					$usuario = $row->first_name.' '.$row->last_name;
					$phone = $row->phone;
				}
				else {
					$usuario = $row->user_desc;
					$phone = $row->user_phone;
				}
				*/
				$usuario = $row->user_desc;
				$phone = $row->user_phone;
				if(trim($usuario)=="") $usuario="No registrado";
				
				$create_user = $row->create_first.' '.$row->create_last;
				
				$reserve_interval = $this->pistas->getCourtInterval($row->id_court);
				
				$time=$row->intervalo;
				$precio+=$row->price;
				//$precio += $this->price_court + $row->price_light + $row->price_supl1 + $row->price_supl2;
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
					$light_cost += $row->price_light;
				}
				/*
				else
				{
					$light_desc= '';
					$light_cost = 0;
				}
				*/
				$button_cancel= '<img id="cancelar" "title"="Cancelar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/close.png\'>';
				$button_change= '<a href="javascript:modificarReserva(\''.$row->id_transaction.'\',\''.$min_time.'\',\''.$max_time.'\');"><img id="modificar" "title"="Modificar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/refresh.png\'></a>';
				//$button_change=
				if ($row->status < 9) $button_payment= '<img id="payment" "title"="Cobrar Reserva" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/coins.png\'>'; 
				else $button_payment = '-'; 
				
		
				$registro = array(
					'id_transaction' => $row->id_transaction,
					'id_booking' => $row->id_booking,
					'fecha' => date($this->config->item('reserve_date_filter_format') ,strtotime($row->fecha)),
					'inicio' => $min_time,
					'final' => $max_time,
					'status_desc' => $this->lang->line($row->status_desc),
					'court_name' => $row->court_name,
					'sport_name' => $row->sport_desc,
					'paymentway_desc' => $this->lang->line($row->paymentway_desc)!="" ? $this->lang->line($row->paymentway_desc) : '-',
					'user_id' => $row->id_user,
					'user_desc' => $usuario,
					'user_phone' => $phone,
					'create_user_id' => $row->create_user,
					'create_user_desc' => $create_user,
					'price' => number_format($precio, 2, ',', ''),
					'no_cost' => $no_cost,
					'no_cost_desc' => $row->no_cost_desc,
					'shared' => $row->shared,
					'light_desc' => $light_desc,
					'light_cost' => number_format($light_cost, 2, ',', ''),
					'fecha_creacion' => $row->fecha_creacion,
					'coste_antelacion' => number_format($row->price_supl1, 2, ',', ''),
					'coste_invitado' => number_format($row->price_supl2, 2, ',', ''),
					'booking_code' => $row->booking_code,
				);	
				//print("<pre>");print_r($row);print("</pre>");
				
			}
			$record_items[] = $registro;

			return $record_items;
		

		}


	public function get_data_count($params = "" , $page = "all")
		{
			
			$table_name = 'booking';
			
			//Build contents query
			//$this->db->select('distinct id_transaction', FALSE)->from($table_name);
			$this->db->count_all_results($table_name); $this->db->from($table_name);
			$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
			$this->db->join('meta', 'booking.id_user=meta.user_id', 'left outer');
			$this->db->join('zz_booking_status', 'booking.status=zz_booking_status.id', 'left outer');
			$this->db->join('zz_paymentway', 'booking.id_paymentway=zz_paymentway.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
			//$this->db->group_by("booking.id_transaction");
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			//$query = $this->db->get();
			//echo $this->db->last_query().'<br>';
			//log_message('debug',$this->db->last_query());
			
			return $this->db->count_all_results();
		

		}





	public function get_cancelled($params = "" , $page = "all")
		{

		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->db->flush_cache();
		
			$lista_de_reservas = array();
			$table_name = 'booking_cancelled';
			
			//Build contents query
			if ($page != "all") {
				$this->db->select('distinct id_transaction', FALSE)->from($table_name);
	
				$this->db->join('courts', 'courts.id='.$table_name.'.id_court', 'left outer');
				$this->db->join('meta', $table_name.'.id_user=meta.user_id', 'left outer');
				$this->db->join('zz_booking_status', $table_name.'.status=zz_booking_status.id', 'left outer');
				$this->db->join('zz_paymentway', $table_name.'.id_paymentway=zz_paymentway.id', 'left outer');
		
		
				if (!empty ($params['where'])) $this->db->where($params['where']);
			
				if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
				
				if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
				
				//Get contents
				//$query = $this->db->get();
				
				//Get contents
				$query = $this->db->get();
				$lista_de_reservas = array();
				foreach ($query->result() as $row)
				{
					array_push($lista_de_reservas, $row->id_transaction);
				}
			}


			$this->db->flush_cache();
			$table_name = 'booking_cancelled';
			
			//Build contents query
			$this->db->select($table_name.'.id as id, id_booking, id_transaction, id_user, session, id_court, DATE_FORMAT(DATE('.$table_name.'.date), \'%d-%m-%Y\') as fecha, DATE_FORMAT(DATE('.$table_name.'.create_time), \'%d-%m-%Y\') as date_delete, DATE_FORMAT('.$table_name.'.create_time, \'%d-%m-%Y %H:%i\') as time_delete, '.
							'intervalo, `status`, id_paymentway, price, no_cost, no_cost_desc, '.$table_name.'.user_desc, user_phone, '.
							''.$table_name.'.create_user as create_user, '.$table_name.'.create_time as create_time, '.$table_name.'.modify_user as modify_user, '.
			 				''.$table_name.'.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, meta_2.first_name as first_name_2, '.
							'meta.last_name as last_name, meta_2.last_name as last_name_2,  meta.first_name + \' \' + meta.last_name as complete_name, meta.phone as phone, zz_booking_status.description as status_desc, '.
							'zz_paymentway.description as paymentway_desc, '.$table_name.'.cancelation_reason as cancelation_reason', FALSE)->from($table_name);

			$this->db->join('courts', 'courts.id='.$table_name.'.id_court', 'left outer');
			$this->db->join('meta', $table_name.'.id_user=meta.user_id', 'left outer');
			$this->db->join('meta as meta_2', $table_name.'.create_user=meta_2.user_id', 'left outer');
			$this->db->join('zz_booking_status', $table_name.'.status=zz_booking_status.id', 'left outer');
			$this->db->join('zz_paymentway', $table_name.'.id_paymentway=zz_paymentway.id', 'left outer');
	
	
			if ($page != "all" && count($lista_de_reservas) > 0) {
				if (!empty ($params['where'])) $params['where'].= ' AND ';
				$params['where'].= "booking_cancelled.id_transaction IN ('".implode("', '", $lista_de_reservas)."')";
			}
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			$this->db->order_by('id_transaction', $params['orderbyway']);
			
			//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			//echo $this->db->last_query();
			
			$record_items = array(); $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
			
			foreach ($query->result() as $row)
			{
				//if($contador <= $first_row) { $contador++; continue; }
				if($transaccion=="") $transaccion = $row->id_transaction;
				
				//echo $row->id_transaction.' # ' .$transaccion.'<br>';
				if($transaccion != $row->id_transaction && $transaccion!="") {
					#Sólo si se ha cambiado de Id de transacción
					$record_items[] = $registro;
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
				
				if($row->id_user) $usuario = $row->first_name.' '.$row->last_name;
				else $usuario = $row->user_desc;
				if(trim($usuario)=="") $usuario="No registrado";
				
				if($row->id_user) $usuario2 = $row->first_name_2.' '.$row->last_name_2;
				if(trim($usuario2)=="") $usuario2 = "No registrado";
				$reserve_interval = $this->pistas->getCourtInterval($row->id_court);
				
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
				//$button_change=
				
		
				$registro = array(
					'id_transaction' => $row->id_transaction,
					'id_booking' => $row->id_booking,
					'fecha' => date($this->config->item('reserve_date_filter_format') ,strtotime($row->fecha)),
					'inicio' => $min_time,
					'final' => $max_time,
					'court_name' => $row->court_name,
					'user_desc' => $usuario,
					'user_phone' => $row->phone,
					'user_delete' => $usuario2,
					'date_delete' => $row->date_delete,
					'time_delete' => $row->time_delete,
					'price' => $precio,
					'no_cost' => $no_cost,
					'cancelation_reason' => $row->cancelation_reason,
				);	
				//print("<pre>");print_r($row);print("</pre>");
				
			}
			$record_items[] = $registro;

			return $record_items;
		

		}


	public function get_cancelled_count($params = "" , $page = "all")
		{
			
			$table_name = 'booking_cancelled';
			
			//Build contents query
			$this->db->select('distinct id_transaction', FALSE)->from($table_name);

			$this->db->join('courts', 'courts.id='.$table_name.'.id_court', 'left outer');
			$this->db->join('meta', $table_name.'.id_user=meta.user_id', 'left outer');
			$this->db->join('zz_booking_status', $table_name.'.status=zz_booking_status.id', 'left outer');
			$this->db->join('zz_paymentway', $table_name.'.id_paymentway=zz_paymentway.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			return count($query->result_array());
		

		}




    function add_player($id, $data)
    {
    		$this->CI =& get_instance();
    		$check = 1;
    		//$this->load->model('Reservas_model', 'reserva', TRUE);
				$info=$this->CI->reservas->getBookingInfoById($id);
				//print("<pre>");print_r($data);print_r($info);exit();
				
				if(!isset($info) || !is_array($info) || count($info)<=0) return ('Reserva no encontrada');
				
				if(isset($info['playing_users']) && is_array($info['playing_users'])) {
					foreach($info['playing_users'] as $usuario) {
						if(($data['id_user']!= 0 && $usuario['id_user'] == $data['id_user']) || ($data['id_user']== 0 && $usuario['user_desc'] == $data['user_desc'])) $check = 0;
					}
				}
				
				# Control de integridad de datos
				if($data['id_user']=='') $data['id_user'] = 0;
				
				if($check) {
	        $datos['id_transaction'] = $id;
	        $datos['id_user'] = $data['id_user'];
	        $datos['user_desc'] = $data['user_desc'];
	        $datos['user_phone'] = $data['user_phone'];
	        $datos['status'] = 9;
	        $datos['create_user'] = $this->session->userdata('user_id');
	        $datos['create_time'] = date(DATETIME_DB);
	        $datos['create_ip'] = $this->session->userdata('ip_address');
	
	        $this->db->insert('booking_players', $datos);
	        log_message('debug',$this->db->last_query());
	        return NULL;
	      } else {
	      	log_message('debug','Usuario '.$data['id_user'].' o '.$data['user_desc'].' ya registrado en la reserva '.$id.'. No se le da de alta de nuevo');
	      	return ('Usuario ya registrado previamente en esta reserva.');
	      }
    }




    function remove_player($id_transaction, $id_user)
    {
				$data = array(
	               'id_transaction' => $id_transaction,
	               'id' => $id_user,
	            );
	
				$this->db->delete('booking_players',$data);
        log_message('debug',$this->db->last_query());
				return TRUE;
    }



	
	
	/**
	 * getBookingCountDayUser
	 *
	 * @return number
	 **/
	public function getBookingCountDayUser($sport, $date, $user, $force = 0) 
	{
		//echo "AA";
		if($this->config->item('booking_record_players') && $force == 0)
			$sql_select = "select count(*) as total from booking_players, booking, courts where booking_players.id_transaction = booking.id_transaction and booking.id_court = courts.id and courts.sport_type = ? and booking.date = ? and booking_players.id_user = ?";
		else
			$sql_select = "select count(*) as total from booking, courts where booking.id_court = courts.id and courts.sport_type = ? and date = ? and id_user = ?";
			
		$query = $this->db->query($sql_select, array($sport, $date, $user));
        //log_message('debug',$this->db->last_query());
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			$total = $row->total;
			if(!isset($total) || $total == '') $total = 0;
			return $total;

		}	else return 0;	
	}	





##############################################################################



    function change_players($id_old, $id_new) {
    	# Cambia el id_transaction asociado a un conjunto de jugadores de una reserva, para no perderlos al hacer un cambio de reserva
    	
    	$data = array(
               'id_transaction' => $id_new,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_ip' => $this->session->userdata('ip_address'),
	             'modify_time' => date($this->config->item('log_date_format'))
      );
      $this->db->where('id_transaction', $id_old);
			$this->db->update('booking_players', $data);
			log_message('debug',$this->db->last_query());
			//if($this->db->affected_rows()) return TRUE;
			//echo $this->db->last_query();
			//exit();
			return TRUE;			
		        
    }



##############################################################################



    function change_shared_players($id_old, $id_new) {
    	# Cambia el id_transaction asociado a un conjunto de jugadores de una reserva, para no perderlos al hacer un cambio de reserva
    	
    	$data = array(
               'id_transaction' => $id_new,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_ip' => $this->session->userdata('ip_address'),
	             'modify_time' => date($this->config->item('log_date_format'))
      );
      $this->db->where('id_transaction', $id_old);
			$this->db->update('booking_shared', $data);
			log_message('debug',$this->db->last_query());
			//if($this->db->affected_rows()) return TRUE;
			//echo $this->db->last_query();
			//exit();
			return TRUE;			
		        
    }





	public function get_data_players_to_export($params = "" , $page = "all")
	{
			
		$sql_select = "select booking_players.id_user as id_user, booking.id_transaction as id_booking from booking_players, booking, meta where booking_players.id_transaction = booking.id_transaction and (booking_players.id_user = meta.user_id or meta.user_id is null) and booking_players.status = 9";
			
		$query = $this->db->query($sql_select, array());
    //log_message('debug',$this->db->last_query());
		//echo $this->db->last_query();
		$array_result = array(); $registro = array();
		foreach ($query->result() as $row) {		

			$registro = array( 
				'id_booking' => $row->id_booking,
				'id_user' => $row->id_user
			);
			$array_result[] = $registro;
			$registro = array();
		}	
		
		return $array_result;	
	}

	public function get_data_to_export($params = "" , $page = "all")
	{
		$datos = $this->get_data($params, $page);
		//exit();
		$resultado = array();
		foreach($datos as $reserva) {
			
			$booking_type = 'Reserva';
			if($reserva['shared'] == 1) $booking_type = 'Reto';
			
			array_push($resultado, array(
				'id_booking' => $reserva['id_transaction'],
				'fecha' => $reserva['fecha'],
				'HoraInicio' => $reserva['inicio'],
				'HoraFin' => $reserva['final'],
				'NombrePista' => $reserva['court_name'],
				'Deporte' => $reserva['sport_name'],
				'user_id' => $reserva['user_id'],
				'Usuario' => $reserva['user_desc'],
				'Telefono' => $reserva['user_phone'],
				'id_CreadorReserva' => $reserva['create_user_id'],
				'CreadorReserva' => $reserva['create_user_desc'],
				'SinCoste' => $reserva['no_cost'],
				'DescripcionSinCoste' => $reserva['no_cost_desc'],
				'Luz' => $reserva['light_desc'],
				'TipoReserva' => $booking_type,
				'FechaReserva' => $reserva['fecha_creacion'],
				'CosteReserva' => $reserva['price'],
				'CosteLuz' => $reserva['light_cost'],
				'CosteReservaAnticipada' => $reserva['coste_antelacion'],
				'CosteInvitado' => $reserva['coste_invitado'],
				'CodigoReserva' => $reserva['booking_code'],
				'Estado' => $reserva['status_desc']
				));
		}
		
		return $resultado;
	}


}
?>