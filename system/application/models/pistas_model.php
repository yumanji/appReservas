<?php
class Pistas_model extends Model {

    var $id   = NULL;
    var $name   = NULL;
    var $sport_type   = NULL;
    var $court_type   = NULL;
    var $id_price   = NULL;

    function Pistas_model() {
        // Call the Model constructor
        parent::Model();
    }
    
    function getTimeTablesArray() {
    	# Devuelve nombre de la pista
			
				$result=array();
				$sql = "SELECT id, description FROM time_tables WHERE active=1 "; 
				$query = $this->db->query($sql, array($court));
											//	echo $this->db->last_query();exit();
				foreach ($query->result() as $row)
				{
					
					$result[$row->id]=$row->description;
				}	
					return $result;				

    }
 
     function getTimeTableInterval($id_time) {
    	# Devuelve nombre de la pista
			
				$result=array();
				$sql = "SELECT `interval` FROM time_tables WHERE active=1 and id = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($court));
				//	echo $this->db->last_query();exit();
				foreach ($query->result() as $row)
				{					
					return $row->interval;
				}	
					return $this->config->item('reserve_interval');				

    }
 
  
     public function getCourtInterval($id_court) {
    	# Devuelve intervalo de tiempo de la pista en función de su time_table

		$sql = "SELECT time_table_default FROM courts WHERE active=1 and id = ? LIMIT 1"; 
		$query = $this->db->query($sql, array($id_court));
		//	echo $this->db->last_query();exit();
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$sql = "SELECT  `interval` FROM time_tables WHERE active=1 and id = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($row->time_table_default));
			//	echo $this->db->last_query();exit();
			foreach ($query->result() as $row)
			{					
				return $row->interval;
			}	
			
		} 
		
		return $this->config->item('reserve_interval');;
				

    }
 
    function getCourtPrice($court) {
    	# Devuelve nombre de la pista
			
		$sql = "SELECT id_price FROM courts WHERE active=1 and id = ? LIMIT 1"; 
		$query = $this->db->query($sql, array($court));
									//	echo $this->db->last_query();exit();
		if ($query->num_rows() > 0) {	
			$row = $query->row();
			return $row->id_price;
		} else return NULL;
    }
 
 
 
 
    function deleteSpecialTimeTable($id) {
    	# Devuelve nombre de la pista
			
				$this->db->delete('time_tables_specials', array('id' => $id)); 

				return NULL;
    }
 
      
    function getCourtLightPrice($court) {
    	# Devuelve nombre de la pista
			
				$sql = "SELECT light_price FROM courts WHERE active=1 and id = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($court));
											//	echo $this->db->last_query();exit();
				if ($query->num_rows() > 0) {	
					$row = $query->row();
					$luz = $row->light_price;
					if(!isset($luz) || $luz == '' || $luz == 0) $luz = $this->config->item('booking_light_price');
					
					return $luz;
				} else return NULL;
    }

   
    function getCourtSport($court) {
    	# Devuelve nombre de la pista
			
				$sql = "SELECT sport_type FROM courts WHERE active=1 and id = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($court));
											//	echo $this->db->last_query();exit();
				if ($query->num_rows() > 0) {	
					$row = $query->row();
					return $row->sport_type;
				} else return NULL;
    }

    function getDescription() {
    	# Devuelve nombre de la pista
			
			if($this->id !="") {
				$sql = "SELECT name FROM courts WHERE active=1 and id = ? LIMIT 1"; 
				$query = $this->db->query($sql, array($this->id));
												
				if ($query->num_rows() > 0) {	
					$row = $query->row();
					return $row->name;
				} else return NULL;
			} else return NULL;		
    }

   
    function getAvailableCourts($sport="", $type="", $group = 9) {
    	# Devuelve lista de los ID de las pistas disponibles en general o para un deporte dado


		$sql_extra = '';
		$profile=$this->redux_auth->profile();
		$user_group=$profile->group;
		if(isset($user_group) && $user_group!='' && $group==9) $group = $user_group;
		//echo $group.' - '.$this->config->item('court_visible_by_group_higher_than');
		if($this->config->item('court_visible_by_group_higher_than') <= $group) {
			$sql_extra = ' AND (visible = 1) ';
		}


        $result=array();
      	if($type=="") {
	        if($sport=="") {
		        $sql = "SELECT id FROM courts WHERE active=1 ".$sql_extra." order by view_order, name"; 
				$query = $this->db->query($sql);
			} else {
	        $sql = "SELECT id FROM courts WHERE active=1 and sport_type = ? ".$sql_extra." order by view_order, name"; 
					$query = $this->db->query($sql, array($sport));
			}
		} else {
		    $sql = "SELECT id FROM courts WHERE active=1 and court_type = ? ".$sql_extra." order by view_order, name"; 
			$query = $this->db->query($sql, array($type));						
		}
				
		foreach ($query->result() as $row)
		{
			array_push($result, $row->id);
			//$result[$row->id]=$row->name;
		}	
		return $result;				
    }

    function getAvailableCourtsArray($sport="", $type="", $group = 9) {
    	# Devuelve un array con el Id y el nombre de las pistas disponibles en general o para un deporte dado
				
				$sql_extra = '';
				$profile=$this->redux_auth->profile();
				$user_group = 9;
				if(isset($profile) && is_object($profile)) $user_group=$profile->group;
				if(isset($user_group) && $user_group!='' && $group==9) $group = $user_group;
				//echo $group.' - '.$this->config->item('court_visible_by_group_higher_than');
				if($this->config->item('court_visible_by_group_higher_than') <= $group) {
					$sql_extra = ' AND (visible = 1) ';
				}
        
        $result=array(""=>"Seleccione Pista");
        if($sport=="") {
	        if($type=="") {
		        $sql = "SELECT id, name FROM courts WHERE active=1 ".$sql_extra." order by view_order, name"; 
				$query = $this->db->query($sql);
			} else {
		        $sql = "SELECT id, name FROM courts WHERE active=1 and court_type = ? ".$sql_extra." order by view_order, name"; 
				$query = $this->db->query($sql, array($type));						
			}
		} else {
	        if($type=="") {
		        $sql = "SELECT id, name FROM courts WHERE active=1 and sport_type = ? ".$sql_extra." order by view_order, name"; 
				$query = $this->db->query($sql, array($sport));
			} else {
		        $sql = "SELECT id, name FROM courts WHERE active=1 and court_type = ? ".$sql_extra." order by view_order, name"; 
				$query = $this->db->query($sql, array($type));						
			}
		}
				
				//echo '<br>'.$this->db->last_query();
				
		foreach ($query->result() as $row)
		{
			$result[$row->id]=$row->name;
		}	
		return $result;				
    }
    
    function getAvailableCourtsTypes($sport="") {
    	# Devuelve lista de los ID de las pistas disponibles en general o para un deporte dado

        $result=array();
        if($sport=="") {
	        $sql = "SELECT id FROM courts_types WHERE active=1 "; 
					$query = $this->db->query($sql);
				} else {
	        $sql = "SELECT id FROM courts_types WHERE active=1 and id_sport = ?"; 
					$query = $this->db->query($sql, array($sport));
				}
				
				foreach ($query->result() as $row)
				{
					array_push($result, $row->id);
					//$result[$row->id]=$row->name;
				}	
					return $result;				
    }

    function getAvailableCourtsTypesArray($sport="") {
    	# Devuelve un array con el Id y el nombre de las pistas disponibles en general o para un deporte dado

        $result=array(""=>"Seleccione Tipo");
        if($sport=="" || !isset($sport)) {
	        $sql = "SELECT id, description FROM courts_types WHERE active=1  ORDER BY description"; 
					$query = $this->db->query($sql);
				} else {
	        $sql = "SELECT id, description FROM courts_types WHERE active=1 and id_sport = ? ORDER BY description"; 
					$query = $this->db->query($sql, array($sport));
				}
				
				foreach ($query->result() as $row)
				{
					//echo $row->description."-".$this->lang->line($row->description)."<br>";
					$result[$row->id]=$this->lang->line($row->description);
				}	
					return $result;				
    }

    function getTimetable($date) {
    	# Devuelve array con el horario para la pista dada
			$debug = FALSE;
			$weekday=@date('N', strtotime($date));
			# Recupero el timetable por defecto
	    $sql = "SELECT time_table_default FROM courts WHERE active=1 and id = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($this->id));
			if($debug) echo '<br>'.$this->db->last_query();
			if ($query->num_rows() > 0) {	
				if($debug) echo "A";
				$row = $query->row();
				$timetable=$row->time_table_default;			
				
				
				if($timetable!="" && $weekday!="") {
					if($debug) echo "B";

			    $condicion_extra = '';
			    $sql = "SELECT everyday FROM time_tables WHERE id = ? LIMIT 1"; 
					$query = $this->db->query($sql, array($timetable));
					if($debug) echo '<br>'.$this->db->last_query();
					if ($query->num_rows() > 0) {	
						$row = $query->row();
						$everyday=$row->everyday;			
						if($debug) echo '<br>everyday: '.$everyday;
						#Si el horario es igual para todos los días,  añado esta condicion
						if($everyday=='1') $condicion_extra = ' OR weekday = 0 ';
					
					}
					
					
					
					# Recupero el detalle del timetable
			    $result=array();
		      $sql = "SELECT `interval`, status FROM time_tables_detail WHERE id_time_table = ? AND ( weekday = ? ".$condicion_extra.") ORDER BY `interval`"; 
					$query = $this->db->query($sql, array($timetable, $weekday));
					if($debug) echo '<br>'.$this->db->last_query();
					foreach ($query->result() as $row) {
						$result[$this->id."-".date('U', strtotime($date." ".$row->interval))]=array(date('H:i', strtotime($row->interval)),$row->status, '', '', '', '', '');
					}
					
					# Aquí habría que meter una funcion que rellene los posibles espacios vacíos del array y lo ordene
					
					//print('<pre>');print_r ($result);print('</pre>');
						        
					return $result;	
				} else return NULL;			
			} else return NULL;
    }

    function getCourtAvailability($date="") {
    	# Devuelve array con los tiempos disponibles para la pista dada
    	
    	##############
    	##############
    	##############
    	###################### HAY QUE COMPLETAR LA FUNCION
    	
			# Si no me pasan fecha, paso la
	    $result=$this->getCourtTimetable();

			# Recupero time_table especial
	    $sql = "SELECT time_table FROM time_tables_specials WHERE status = 1 and type = 2 and date = ? LIMIT 1"; 
			$query = $this->db->query($sql, array($date));
			if ($query->num_rows() > 0) {	
				$row = $query->row();
				$timetable=$row->time_table;
			}
			
			//print_r ($result);	        
			return $result;				
    }

#######################################
#######################################

#FUNCION PARA RECUPERAR LOS INTERVALOS DE LA SEMANA
#DEVUELVE UN ARRAY DE ARRAYS DE LOS DIAS DE LA SEMANA, EL 0 ES EL DOMINGO

    function getAllTimetables() {
    	# Devuelve array con el horario para la pista dada
			# Recupero el timetable por defecto
	    $sql = "SELECT id, id_time_table, if(weekday='7','0',weekday) as week_day_date, `interval` as intervalo ".
				"FROM time_tables_detail ".
				"where status = 1 ".
				"and id_time_table = 1 ".
				"order by 3,4"; 
		$query = $this->db->query($sql);
		$registro = 0;
		$result_all = array();
		foreach ($query->result() as $row) 
		{
			if (($registro == $row->week_day_date))
			{
				$result[$row->id]=array('id'  => $row->id,
									'id_time_table'  => $row->id_time_table,
									'week_day_date'  => $row->week_day_date,
									'intervalo'  => $row->intervalo,);
			}
			else
			{
				$result_all[$registro] = $result;
				$result = null;	
				$result[$row->id]=array('id'  => $row->id,
									'id_time_table'  => $row->id_time_table,
									'week_day_date'  => $row->week_day_date,
									'intervalo'  => $row->intervalo,);
			}
			$registro = $row->week_day_date;
		}
		$result_all[$registro] = $result;
					
					# Aquí habría que meter una funcion que rellene los posibles espacios vacíos del array y lo ordene
					
					//print('<pre>');print_r ($result);print('</pre>');
						        
		return $result_all;	
    }








##############################################################################


	
public function get_specialdates_data($params = "" , $page = "all")
	{
		
		$this->CI =& get_instance();

		//Select table name
		$table_name = "time_tables_specials";
		
		//Build contents query
		$this->db->select('time_tables_specials.id as id, time_tables_specials.type as type, time_tables_specials.date as fecha, DATE_FORMAT(DATE(time_tables_specials.date), \'%d-%m-%Y\') as fecha_, time_tables.description as horario, courts.name as pista', FALSE)->from($table_name);
		$this->db->join('courts', 'courts.id=time_tables_specials.id_court', 'left outer');
		//$this->db->join('booking', 'payments.id_transaction=booking.id_transaction', 'left outer');
		$this->db->join('time_tables', 'time_tables.id=time_tables_specials.time_table', 'left outer');

		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		
		if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		$query2 = $query->result_array();
		//echo $this->db->last_query();
		//log_message('debug',$this->db->last_query());
		
		for($i=0; $i<count($query2); $i++) {
			if(trim($query2[$i]['pista']) == '') $query2[$i]['pista'] = 'Todas';
		}
		return $query2;
	

	}




##############################################################################

	function createSpecialTimeTables($data) {
		
			$data['create_user'] = $this->session->userdata('user_id');
			$data['create_time'] = date($this->config->item('log_date_format'));
			//$data['create_ip'] = $this->session->userdata('ip_address');


			$this->db->insert('time_tables_specials', $data);	  
			log_message('debug',$this->db->last_query());
			
			return TRUE;
	}



}
?>