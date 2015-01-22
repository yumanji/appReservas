<?php
class Lessons_model extends Model {

/*
# CONTENIDO
#
# getTimetable()

*/
    var $id   = NULL;
    var $description   = NULL;
    var $active   = NULL;
    var $weekday   = NULL;
    var $start_time   = NULL;
    var $end_time   = NULL;
    var $start_date   = NULL;
    var $end_date   = NULL;
    var $id_sport   = NULL;
    var $id_instructor   = NULL;
    var $max_vacancies   = NULL;
    var $monthly_payment_day   = NULL;
    var $level   = NULL;
    var $gender   = NULL;
    var $L   = NULL;
    var $M   = NULL;
    var $X   = NULL;
    var $J   = NULL;
    var $V   = NULL;
    var $S   = NULL;
    var $D   = NULL;

    var $price   = NULL;
    var $montly   = NULL;
    var $signin   = NULL;


    function Lessons_model() {
        // Call the Model constructor
        parent::Model();
        $this->load->library('calendario');
        
    }
    


    function updateTimetable($fecha, $pista, $disponibilidad) {
        # Devuelve array con el horario para la pista dada
 
            //echo "<pre>";//print_r($disponibilidad);
            $this->CI =& get_instance();
        $this->CI->load->model('Pistas_model', 'pistas', TRUE);
        $reserve_interval = $this->CI->pistas->getCourtInterval($pista);
 
            $weekday=@date('N', strtotime($fecha));
            $condicion = '';
        switch($weekday) {
            case '1':
                $condicion = "`lessons`.L = '1'";
            break;
            case '2':
                $condicion = "`lessons`.M = '1'";
            break;
            case '3':
                $condicion = "`lessons`.X = '1'";
            break;
            case '4':
                $condicion = "`lessons`.J = '1'";
            break;
            case '5':
                $condicion = "`lessons`.V = '1'";
            break;
            case '6':
                $condicion = "`lessons`.S = '1'";
            break;
            case '7':
                $condicion = "`lessons`.D = '1'";
            break;
        }           
        if($condicion != '') $condicion = 'AND '.$condicion;
        else $condicion = 'AND 1=2';
            # Recupero los cursos aplicables para hoy
             
        $this->db->select('lessons.id, lessons.weekday, lessons.M, lessons.L, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.start_time, lessons.end_time, lessons.id_court, lessons.price');
        $this->db->from('lessons');
        $this->db->where("lessons.Active = '1' AND (lessons.start_date <= '". $fecha."' AND lessons.end_date >= '".$fecha."') AND lessons.id_court='".$pista."' ".$condicion." ");
        $handle = $this->db->get();
        //echo $this->db->last_query();
        $cursos = array();
        foreach ($handle->result() as $row) {
            $inicio = date('U', strtotime($fecha." ".$row->start_time));
            $hora = $inicio;
            $final = date('U', strtotime($fecha." ".$row->end_time));
			//echo '<pre>'; print_r($disponibilidad); exit();
			# Recorro la disponibilidad para marcar como 'reservado' aquellas celdas que se vean afectadas por el curso
            foreach($disponibilidad as $id => $valores) {
				$int_tmp = explode('-', $id);
				$intervalo = $int_tmp[1];	// Saco la segunda parte del Id, que es el timestamp de la celda a considerar
				$intervalo2 = $int_tmp[1] + ($reserve_interval * 60);	// Saco la segunda parte del Id, que es el timestamp de la celda a considerar
				#Si hay cruce de rangos, asumo que la celda está, de algún modo, ocupada por el curso (comparación extraida de aqui http://stackoverflow.com/questions/1396575/detecting-if-two-number-ranges-clash , quitando los = de los <= y >= porque si solo se tocan por un lado no quiero que lo marque como ocupado)
                if($valores[1]=="1" && !(($final <= $intervalo) || ($inicio >= $intervalo2))) {
					//if($int_tmp[0] == 12) echo '<br>'.$inicio.'--'.$intervalo2.' ==== '.$id;
					//if($int_tmp[0] == 12) echo '<br>'.$final.'--'.$intervalo;
                    $disponibilidad[$id][1]="0";
                    $disponibilidad[$id][2]=$row->id;
                    $disponibilidad[$id][3]="l";
                    $disponibilidad[$id][4]=date($this->config->item('reserve_hour_filter_format'), strtotime($fecha." ".$row->start_time)).' - '.date($this->config->item('reserve_hour_filter_format'), strtotime($fecha." ".$row->end_time));
                }
            }
			
            //$this->id."-".date('U', strtotime($date." ".$row->interval))
        }
             //if($int_tmp[0] == 12) { echo '<pre>'; print_r($disponibilidad); exit(); }
            
            //print_r($cursos);

            return $disponibilidad;
 
    }

##############################################################################

function updateLessons($id) {
		try {
    	$data = array(
               'description' => $this->description,
               'active' => $this->active,
	             'weekday' => $this->weekday,
               'start_time' => $this->start_time,
               'end_time' => $this->end_time,
               'start_date' => $this->start_date,
               'end_date' => $this->end_date,
               'id_sport' => $this->id_sport,
               'id_court' => $this->id_court,
               'id_instructor' => $this->id_instructor,
               'max_vacancies' => $this->max_vacancies,
               'monthly_payment_day' => $this->monthly_payment_day,
               'level' => $this->level,
               'signin' => $this->signin,
               'price' => $this->price,
               'gender' => $this->gender,
               'L' => $this->L,
               'M' => $this->M,
               'X' => $this->X,
               'J' => $this->J,
               'V' => $this->V,
               'S' => $this->S,
               'D' => $this->D,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      
      
      $this->db->where('id', $id);
			$this->db->update('lessons', $data);
			log_message('debug',$this->db->last_query());
			
      # Actualizo plazas vacantes
     	$update = "UPDATE lessons SET current_vacancies = max_vacancies - (SELECT count(*) FROM lessons_assistants WHERE id_lesson = ".$id." AND status < 3) WHERE Id = ".$id; 
			$this->db->query($update);
			log_message('debug',$this->db->last_query());
			
		}catch(Exception $e){
			return FALSE;
	  }
	  return TRUE;
}
    

##############################################################################

function updateLessonsPricesSimple($id) {
		try {
    	$data = array(
               'monthly' => $this->monthly,
               'signin' => $this->signin,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      
      
      $this->db->where('id', $id);
      $this->db->where('id_group', '0');
			$this->db->update('lessons_prices', $data);
			log_message('debug',$this->db->last_query());
			
		}catch(Exception $e){
			return FALSE;
	  }
	  return TRUE;
}
    


##############################################################################


    function getLevelsArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array(""=>"Selecciona nivel");
        $sql = "SELECT id, description FROM zz_lessons_levels ORDER BY view_order, Description"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$this->lang->line($row->description);
				}	
					return $result;				
    }



	

##############################################################################


    function getUnsubscriptionReasonsArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array(""=>"Selecciona motivo");
        $sql = "SELECT id, description FROM zz_lessons_unbubscription_reasons ORDER BY Description"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$row->description;
				}	
					return $result;				
    }




##############################################################################


	function get_AssitantsData($params = "" , $page = "all")
  {
  	$this->load->library('app_common');
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array();
    $this->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.status, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons_assistants.unsubscription_date, lessons_assistants.unsubscription_reason as unsubscription_reason_code, zz_lessons_unbubscription_reasons.description as unsubscription_reason, lessons_assistants.sign_date, lessons_assistants.last_payd_date, lessons_assistants.last_day_payed, concat( lessons_assistants.discount , lessons_assistants.discount_type ) as discount, lessons_assistants.discount as discount_value, lessons_assistants.discount_type as discount_type, meta.first_name, meta.last_name, meta.phone, meta.nif, meta.birth_date, meta.phone, lessons.description, lessons.weekday, lessons.L, lessons.M, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.max_vacancies, lessons.current_vacancies, lessons.id_sport as sport, lessons.price as price, zz_sports.description as sport_desc, users.group_id as group_id, groups.Description as group_desc');
    $this->db->from('lessons_assistants');
    $this->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    $this->db->join('users', 'lessons_assistants.id_user = users.id', 'left outer');
    $this->db->join('groups', 'groups.id = users.group_id', 'left outer');
    $this->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->db->join('zz_sports', 'lessons.id_sport = zz_sports.id', 'left outer');
    $this->db->join('zz_lessons_unbubscription_reasons', 'lessons_assistants.unsubscription_reason = zz_lessons_unbubscription_reasons.id', 'left outer');
    
		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		
		if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
    //echo $this->db->last_query();
    //log_message('debug',$this->db->last_query());
    //$sql = "SELECT * FROM lessons WHERE Active = '1' AND (start_date <= '". $end."' AND end_date >= '".$start."')";
    //$handle = $this->CI->db->query($sql);
    //echo $sql;
    //print("<pre>REGISTRO");print_r($query);print_r($query->result());print_r($query->result_array());
    $result = $query->result_array();
    foreach ($result as $code => $data) {
					if($data['id_user'] == 0) {
						$result[$code]['user_desc'] = $data['user_desc'];
						$result[$code]['user_phone'] = $data['user_phone'];
					} else {
						$result[$code]['user_desc'] = $data['first_name'];
						if($data['last_name'] != "") $result[$code]['user_desc'].=' '.$data['last_name'];
						$result[$code]['user_phone'] = $data['phone'];
					}
					
					if($data['sign_date'] != ''){
						$result[$code]['signed'] = '1';
						$result[$code]['signed_desc'] = 'Si';
					} else {
						$result[$code]['signed'] = '0';
						$result[$code]['signed_desc'] = 'No';
					}
					
					$result[$code]['fecha_nacimiento'] = date($this->config->item('reserve_date_filter_format'), strtotime($data['birth_date']));

					$result[$code]['fecha_inicio'] = date($this->config->item('reserve_date_filter_format'), strtotime($data['start_date']));
					$result[$code]['fecha_fin'] = date($this->config->item('reserve_date_filter_format'), strtotime($data['end_date']));
					$result[$code]['rango_fechas'] = $result[$code]['fecha_inicio'].' - '.$result[$code]['fecha_fin'];
					$result[$code]['hora_inicio'] = date($this->config->item('reserve_hour_filter_format'), strtotime($data['start_time']));
					$result[$code]['hora_fin'] = date($this->config->item('reserve_hour_filter_format'), strtotime($data['end_time']));
					$result[$code]['rango_horas'] = $result[$code]['hora_inicio'].' - '.$result[$code]['hora_fin'];

					$dias_semana = $this->config->item('weekdays_names');
					$dias_semana_alt = $this->config->item('weekdays_names_alt');
					
					$weekday_literal = '';
					if($data['L']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='L'; }
					if($data['M']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='M'; }
					if($data['X']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='X'; }
					if($data['J']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='J'; }
					if($data['V']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='V'; }
					if($data['S']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='S'; }
					if($data['D']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='D'; }
					if($weekday_literal == '') $weekday_literal = '-';
					
					//$result[$code]['dia_semana'] = $dias_semana[$data['weekday']];
					$result[$code]['dia_semana'] = $weekday_literal;
					
					$result[$code]['quota'] = $this->app_common->getPriceValue($data['price'], array('group' => $data['group_id'], 'date' => $data['start_date'] , 'time'=> $data['start_time']));
					$result[$code]['source_quota'] = $result[$code]['quota'];
					$pay_amount_tmp = $this->app_common->getPriceValue($data['price'], array('group' => $data['group_id'], 'date' => $data['start_date'] , 'time'=> $data['start_time']));
					if($data['discount_type'] == '%') $result[$code]['quota'] = $pay_amount_tmp - ($pay_amount_tmp * $data['discount_value'] / 100);
					else $result[$code]['quota'] = $pay_amount_tmp - $data['discount_value'];
					
					if($data['max_vacancies'] == '0') $result[$code]['plazas'] = '0/0';
					elseif($data['current_vacancies'] == '') $result[$code]['plazas'] = '-/'.$data['max_vacancies'];
					else $result[$code]['plazas'] = $data['current_vacancies'].'/'.$data['max_vacancies'];
				}	
					return $result;
					//print("<pre>");print_r($result);
					//return $query->result_array();
					
					

					
									
    }




##############################################################################


	function get_AssitanceData($params = "" , $page = "all")
  {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array();
    $this->db->select('lessons_assistance.id, lessons_assistance.id_lesson, lessons_assistance.date_lesson, lessons_assistance.id_instructor, lessons_assistance.done, lessons_assistance.observations, lessons_assistance.admin_check, lessons_assistance.admin_obs, lessons_assistance.recovered_date, lessons_assistance.recovered_obs, lessons_assistance.recovered, meta.first_name, meta.last_name, meta.phone, lessons.description, lessons.weekday, lessons.L, lessons.M, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.max_vacancies, lessons.current_vacancies, lessons.id_sport as sport, zz_sports.description as sport_desc, lessons.id_court as id_court, courts.name as court_desc');
    $this->db->from('lessons_assistance');
    $this->db->join('meta', 'lessons_assistance.id_instructor = meta.user_id', 'left outer');
    $this->db->join('lessons', 'lessons_assistance.id_lesson = lessons.id', 'left outer');
    $this->db->join('zz_sports', 'lessons.id_sport = zz_sports.id', 'left outer');
    $this->db->join('courts', 'lessons.id_court = courts.id', 'left outer');
    
		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		
		if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
    //echo $this->db->last_query();
    log_message('debug',$this->db->last_query());
    //$sql = "SELECT * FROM lessons WHERE Active = '1' AND (start_date <= '". $end."' AND end_date >= '".$start."')";
    //$handle = $this->CI->db->query($sql);
    //echo $sql;
    //print("<pre>");print_r($query->result_array());
    $result = $query->result_array();
    foreach ($result as $code => $data) {

			$result[$code]['instructor_desc'] = $data['first_name'];
			if($data['last_name'] != "") $result[$code]['instructor_desc'].=' '.$data['last_name'];
					
			if($data['done'] == '1') $result[$code]['done'] = 'Si';
			else $result[$code]['done'] = '-';
			if($data['admin_check'] == '1') $result[$code]['admin_check'] = 'Si';
			else $result[$code]['admin_check'] = '-';
			if(strlen($data['observations'])>25) $result[$code]['observations_short'] = substr($data['observations'],0,22).'..';
			else $result[$code]['observations_short'] = $data['observations'];
			if(strlen($data['admin_obs'])>25) $result[$code]['admin_obs_short'] = substr($data['admin_obs'],0,22).'..';
			else $result[$code]['admin_obs_short'] = $data['admin_obs'];

			$result[$code]['fecha_lesson'] = date($this->config->item('reserve_date_filter_format'), strtotime($data['date_lesson']));

			$result[$code]['fecha_inicio'] = date($this->config->item('reserve_date_filter_format'), strtotime($data['start_date']));
			$result[$code]['fecha_fin'] = date($this->config->item('reserve_date_filter_format'), strtotime($data['end_date']));
			$result[$code]['rango_fechas'] = $result[$code]['fecha_inicio'].' - '.$result[$code]['fecha_fin'];
			$result[$code]['hora_inicio'] = date($this->config->item('reserve_hour_filter_format'), strtotime($data['start_time']));
			$result[$code]['hora_fin'] = date($this->config->item('reserve_hour_filter_format'), strtotime($data['end_time']));
			$result[$code]['rango_horas'] = $result[$code]['hora_inicio'].' - '.$result[$code]['hora_fin'];

			$dias_semana = $this->config->item('weekdays_names');
			$weekday_literal = '';
			if($data['L']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='L'; }
			if($data['M']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='M'; }
			if($data['X']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='X'; }
			if($data['J']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='J'; }
			if($data['V']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='V'; }
			if($data['S']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='S'; }
			if($data['D']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='D'; }
			if($weekday_literal == '') $weekday_literal = '-';
			
			//$result[$code]['dia_semana'] = $dias_semana[$data['weekday']];
			$result[$code]['dia_semana'] = $weekday_literal;

			if($data['max_vacancies'] == '0') $result[$code]['plazas'] = '0/0';
			elseif($data['current_vacancies'] == '') $result[$code]['plazas'] = '-/'.$data['max_vacancies'];
			else $result[$code]['plazas'] = $data['current_vacancies'].'/'.$data['max_vacancies'];
		}	
			return $result;
					//return $query->result_array();
					
					

					
									
    }




##############################################################################


	function get_AssitantsReport($leccion, $date)
  {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
    $result=array();
    $this->db->select('lessons_reports.id, lessons_assistants.id_lesson, lessons_reports.date_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_reports.user_phone, lessons_assistants.status, lessons_reports.asistance, lessons_reports.observations');
    //$this->db->select('lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone');
    $this->db->from('lessons_assistants');
    $this->db->join('lessons_reports', "lessons_assistants.id_lesson = lessons_reports.id_lesson AND ((lessons_reports.id_user <> '0' AND lessons_reports.id_user = lessons_assistants.id_user) OR (lessons_reports.id_user = '0' AND lessons_reports.user_desc = lessons_assistants.user_desc))", 'left outer');
    $this->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    
		$this->db->where("lessons_assistants.status < '7'");
		$this->db->where('lessons_assistants.id_lesson', $leccion);
		$this->db->where("(lessons_reports.id_lesson = '".$leccion."' OR lessons_reports.id_lesson is null)");
		$this->db->where("(lessons_reports.date_lesson = '".$date."' OR lessons_reports.date_lesson is null)");
	
		//Get contents
		$query = $this->db->get();
    //echo $this->db->last_query();
    log_message('debug',$this->db->last_query());
    //$sql = "SELECT * FROM lessons WHERE Active = '1' AND (start_date <= '". $end."' AND end_date >= '".$start."')";
    //$handle = $this->CI->db->query($sql);
    //echo $sql;
    //print("<pre>");print_r($query->result_array());
    $result = $query->result_array();
		return $result;
									
    }




##############################################################################


	function get_LessonDates($leccion, $prev, $next)
  {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
    $this->db->select('lessons.id, lessons.weekday, lessons.L, lessons.M, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.start_date, lessons.end_date');
    $this->db->from('lessons');
    $this->db->where("lessons.id = '".$leccion."'");
    
    $fechas = array();
    $handle = $this->db->get();
    //echo $this->db->last_query();
    $cursos = array();
    $dias = array();
  	foreach ($handle->result() as $row) {
  		$start_date = $row->start_date;
  		$end_date = $row->end_date;
  		$weekday = $row->weekday;
  		if($row->L) array_push($dias, 1);
  		if($row->M) array_push($dias, 2);
  		if($row->X) array_push($dias, 3);
  		if($row->J) array_push($dias, 4);
  		if($row->V) array_push($dias, 5);
  		if($row->S) array_push($dias, 6);
  		if($row->D) array_push($dias, 7);
		}

		# Obtención de la primera fecha válidad el curso
		$actual = date($this->config->item('date_db_format'));
		$fecha = $actual;
		//zecho $fecha."<br>";
		$first  = ''; $chk = 0;
		while(!$chk && $fecha <= $end_date) {
			if(in_array(date('w', strtotime($fecha)), $dias)) $chk=1; 
			else $fecha = date($this->config->item('date_db_format'), strtotime($fecha.' +1day'));
		}

		# Si subiendo dias no hemos encontrado el primer día válido de curso, hago el repaso descendente
		if(!in_array(date('w', strtotime($fecha)), $dias)) {
			$actual = date($this->config->item('date_db_format'));
			$fecha = date($this->config->item('date_db_format'), strtotime($actual.' -1day'));
			//echo $fecha."<br>";
			$first  = ''; $chk = 0;
			while(!$chk && $fecha >= $start_date) {
				if(in_array(date('w', strtotime($fecha)), $dias)) {$chk=1;  $fechas[$fecha] = date($this->config->item('reserve_date_filter_format'), strtotime($fecha)).' *';}
				else $fecha = date($this->config->item('date_db_format'), strtotime($fecha.' -1day'));
			}
			
		} else {
			$fechas[$fecha] = date($this->config->item('reserve_date_filter_format'), strtotime($fecha)).' *';
		}
		
		## Si no he encontrado fecha válida.. devuelvo nulo
		if(count($fechas)== 0) return NULL;	
		foreach($fechas as $key => $value) $first = $key;
		
		# Recupero las fechas próximas
		$actual = date($this->config->item('date_db_format'), strtotime($first));
		$cont = 0;
		while($cont <= $next && $actual <= $end_date) {
			if(in_array(date('w', strtotime($actual)), $dias) && !isset($fechas[$actual])) {$cont++; $fechas[$actual] = date($this->config->item('reserve_date_filter_format'), strtotime($actual));}
			$actual = date($this->config->item('date_db_format'), strtotime($actual.' +1day'));			
		}
		
		# Recupero las fechas anteriores
		$actual = date($this->config->item('date_db_format'), strtotime($first));
		$cont = 0;
		while($cont <= $prev && $actual >= $start_date) {
			if(in_array(date('w', strtotime($actual)), $dias) && !isset($fechas[$actual])) {$cont++; $fechas[$actual] = date($this->config->item('reserve_date_filter_format'), strtotime($actual));}
			$actual = date($this->config->item('date_db_format'), strtotime($actual.' -1day'));			
		}

		
		ksort($fechas);
		$partes = $this->get_AssitanceData(array('where' => "lessons_assistance.id_lesson = '".$leccion."'"));
		//print("<pre>");
		//print_r($fechas);
		//print_r($partes);
		foreach($partes as $key => $datos) {
			unset($fechas[$datos['date_lesson']]);
		}
		//print_r($fechas);
		return $fechas;
									
    }




##############################################################################


    function addAssistant($lesson, $status, $user = NULL, $user_desc = NULL, $user_phone = NULL, $discount = 0, $sign_date = null)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        
        //echo "aaa";
        $this->db->delete('lessons_assistants', array('id_lesson' => $lesson, 'id_user' => $user, 'user_desc' => $user_desc)); 
        
				$data = array(
				               'id_lesson' => $lesson ,
				               'id_user' => $user ,
				               'user_desc' => $user_desc ,
				               'user_phone' => $user_phone ,
				               'status' => $status ,
				               'discount' => $discount ,
							   //'sign_date' => $sign_date,
							   //'last_payd_date' => date($this->config->item('log_date_format')),
				               //'sign_date' => date($this->config->item('date_db_format')) ,
				               'create_user' => $this->session->userdata('user_id') ,
				               'create_time' => date($this->config->item('log_date_format')) ,
				               'create_ip' => $this->session->userdata('ip_address')
				            );
				if(isset($sign_date)) {
					$data['sign_date'] = date($this->config->item('log_date_format'), strtotime($sign_date));
					$data['last_day_payed'] = $data['sign_date'];
				}
				
				$this->db->insert('lessons_assistants', $data);
				//echo $this->db->last_query();
				log_message('debug',$this->db->last_query());
				# Actualizo plazas libres si el status es de añadir (descartando las bajas y las listas de espera)
        if($status < 3) {
        	$update = "UPDATE lessons SET current_vacancies = max_vacancies - (SELECT count(*) FROM lessons_assistants WHERE id_lesson = ".$lesson." AND status < 3) WHERE Id = ".$lesson; 
					$this->db->query($update);
					//echo $this->db->last_query();
				}
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }




##############################################################################
## Añadir datos de la clase impartida en una fecha dada

    function addAssistance($data)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        
        
				$data = array(
				               'id_lesson' => $data['id_lesson'] ,
				               'date_lesson' => $data['date_lesson'] ,
				               'id_instructor' => $data['id_instructor'] ,
				               'done' => $data['done'] ,
				               'observations' => $data['observations'] ,
				               'create_user' => $this->session->userdata('user_id') ,
				               'create_time' => date($this->config->item('log_date_format')) ,
				               'create_ip' => $this->session->userdata('ip_address')
				            );
				
				$this->db->insert('lessons_assistance', $data);
				//echo $this->db->last_query();
				log_message('debug',$this->db->last_query());

			}catch(Exception $e){
				log_message('error',$e);
				return FALSE;
		  }        
				return TRUE;				
    }




##############################################################################
## Actualiza datos de la clase impartida en una fecha dada

    function updateAssistance($id, $data)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        
        
				$datos = array(
				               'id_instructor' => $data['id_instructor'] ,
				               'done' => $data['done'] ,
				               'observations' => $data['observations'] ,
				               'recovered' => $data['recovered'] ,
				               'recovered_date' => $data['recovered_date'] ,
				               'recovered_obs' => $data['recovered_obs'] ,
				               'modify_user' => $this->session->userdata('user_id') ,
				               'modify_time' => date($this->config->item('log_date_format')) ,
				               'modify_ip' => $this->session->userdata('ip_address')
				            );
				
	      $this->db->where('id', $id);
	      $this->db->where('id_lesson', $data['id_lesson']);
	      $this->db->where('date_lesson', $data['date_lesson']);
				$this->db->update('lessons_assistance', $datos);
				//echo $this->db->last_query();
				log_message('debug',$this->db->last_query());
//exit();
			}catch(Exception $e){
				log_message('error',$e);
				return FALSE;
		  }        
				return TRUE;				
    }




##############################################################################
## Actualiza datos de la clase impartida en una fecha dada

    function deleteAssistanceReport($leccion, $fecha)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        
        
        $this->db->delete('lessons_reports', array('id_lesson' => $leccion, 'date_lesson' => $fecha)); 
				//echo $this->db->last_query();
				log_message('debug',$this->db->last_query());
//exit();
			}catch(Exception $e){
				log_message('error',$e);
				return FALSE;
		  }        
				return TRUE;				
    }



##############################################################################
## Añadir datos del usuario y de su asistencia a la clase impartida en una fecha dada

    function addAssistanceReport($data)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        
        
				$data = array(
				               'id_lesson' => $data['id_lesson'] ,
				               'date_lesson' => $data['date_lesson'] ,
				               'id_user' => $data['id_user'] ,
				               'user_desc' => $data['user_desc'] ,
				               'user_phone' => $data['user_phone'] ,
				               'asistance' => $data['asistance'] ,
				               'observations' => $data['observations'] ,
				               'create_user' => $this->session->userdata('user_id') ,
				               'create_time' => date($this->config->item('log_date_format')) ,
				               'create_ip' => $this->session->userdata('ip_address')
				            );
				
				$this->db->insert('lessons_reports', $data);
				//echo $this->db->last_query();
				log_message('debug',$this->db->last_query());

			}catch(Exception $e){
				log_message('error',$e);
				return FALSE;
		  }        
				return TRUE;				
    }


##############################################################################
## Actualiza datos del usuario y de su asistencia a la clase impartida en una fecha dada

    function updateAssistanceReport($data)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        
        
				$datos = array(
				               'id_lesson' => $data['id_lesson'] ,
				               'date_lesson' => $data['date_lesson'] ,
				               'id_user' => $data['id_user'] ,
				               'user_desc' => $data['user_desc'] ,
				               'user_phone' => $data['user_phone'] ,
				               'asistance' => $data['asistance'] ,
				               'observations' => $data['observations'] ,
				               'modify_user' => $this->session->userdata('user_id') ,
				               'modify_time' => date($this->config->item('log_date_format')) ,
				               'modify_ip' => $this->session->userdata('ip_address')
				            );
				
	      $this->db->where('id_lesson', $data['id_lesson']);
	      $this->db->where('date_lesson', $data['date_lesson']);
	      $this->db->where('id_user', $data['id_user']);
	      $this->db->where('user_desc', $data['user_desc']);
				$this->db->update('lessons_reports', $datos);
				//echo $this->db->last_query();
				log_message('debug',$this->db->last_query());

			}catch(Exception $e){
				log_message('error',$e);
				return FALSE;
		  }        
				return TRUE;				
    }


##############################################################################


    function subscribeAssitant($lesson, $id)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'status' => 2,
           //'sign_date' => date($this->config->item('date_db_format')),
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      $this->db->where('id', $id);
	      $this->db->where('id_lesson', $lesson);
				$this->db->update('lessons_assistants', $data);
				log_message('debug',$this->db->last_query());

				# Actualizo plazas libres         
      	$update = "UPDATE lessons SET current_vacancies = max_vacancies - (SELECT count(*) FROM lessons_assistants WHERE id_lesson = ".$lesson." AND status < 3) WHERE Id = ".$lesson; 
				$this->db->query($update);
				log_message('debug',$this->db->last_query());

			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }


##############################################################################


    function signAssitant($lesson, $id = NULL, $id_user = NULL, $user_desc = NULL , $sign_date)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
				if(!isset($id) && $id==0 && !isset($id_user) && $id_user==0 && !isset($user_desc) && $user_desc==0) return FALSE;
				# ...
	    	$data = array(
           //'status' => 2,
           'sign_date' => $sign_date,
           'last_payd_date' => $sign_date,
           //	'last_day_payed' => $sign_date,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      
	      if(isset($id) && $id!=0) $this->db->where('id', $id);
	      elseif(isset($id_user) && $id_user!=0) $this->db->where('id_user', $id_user);
	      else $this->db->where('user_desc', $user_desc);
	      
	      $this->db->where('id_lesson', $lesson);
				$this->db->update('lessons_assistants', $data);
				log_message('debug',$this->db->last_query());


			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }


##############################################################################


    function unsubscribeAssitant($lesson, $id, $unsubscription_reason = null)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'status' => 9,
           'unsubscription_date' => date($this->config->item('date_db_format')),
		   'unsubscription_reason' => $unsubscription_reason,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      $this->db->where('id', $id);
				$this->db->update('lessons_assistants', $data);
				log_message('debug',$this->db->last_query());

				# Actualizo plazas libres         
      	$update = "UPDATE lessons SET current_vacancies = current_vacancies + 1 WHERE Id = ".$lesson; 
				$this->db->query($update);
				log_message('debug',$this->db->last_query());

			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }



##############################################################################


    function setMonthlyPayment($id_assistant, $last_payd_date)
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
				$this->db->update('lessons_assistants', $data);
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }



##############################################################################


    function updateAdminCheck($leccion, $id, $obs)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'admin_obs' => $obs,
           'admin_check' => 1,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      
	      $this->db->where('id', $id);
	      $this->db->where('id_lesson', $leccion);
				$this->db->update('lessons_assistance', $data);
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
			
			return TRUE;				
    }



##############################################################################


    
    function getGendersArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array("0"=>"Indiferente");
        $sql = "SELECT id, description FROM zz_gender ORDER BY Description"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$row->description;
				}	
					return $result;				
    }





	public function get_data($params = "" , $page = "all")
		{
			
    $this->db->select('lessons.id, lessons.description, lessons.weekday, lessons.L, lessons.M, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.gender, zz_gender.description as gender_desc, lessons.level, zz_lessons_levels.description as level_desc,  lessons.max_vacancies, lessons.current_vacancies, lessons.id_instructor, lessons.monthly_payment_day, lessons.id_sport, lessons.id_court, lessons.price, lessons.signin, courts.name as court_desc, meta.first_name, meta.last_name, zz_sports.description as sport_desc');
    $this->db->from('lessons');
    $this->db->join('courts', 'lessons.id_court = courts.id', 'left outer');
    $this->db->join('meta', 'lessons.id_instructor = meta.user_id', 'left outer');
    $this->db->join('zz_gender', 'lessons.gender = zz_gender.id', 'left outer');
    $this->db->join('zz_lessons_levels', 'lessons.level = zz_lessons_levels.id', 'left outer');
	$this->db->join('zz_sports', 'zz_sports.id=lessons.id_sport', 'left outer');
   $this->db->where("lessons.Active = '1'");
    
			if (!empty ($params['where'])) $this->db->where($params['where']);
			$this->db->order_by('lessons.active', 'DESC');
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			//log_message('debug',$this->db->last_query());
			//echo '<br/>'.$this->db->last_query();
			

			$result = $query->result_array();
			foreach($result as $key => $resultado) {
				$result[$key]['sport_desc'] = $this->lang->line($resultado['sport_desc']);
				$result[$key]['profesor'] = $resultado['first_name'].' '.$resultado['last_name'];
				$result[$key]['fecha_inicio'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado['start_date']));
				$result[$key]['fecha_fin'] = date($this->config->item('reserve_date_filter_format'), strtotime($resultado['end_date']));
				$result[$key]['rango_fechas'] = $result[$key]['fecha_inicio'].' - '.$result[$key]['fecha_fin'];
				$result[$key]['hora_inicio'] = date($this->config->item('reserve_hour_filter_format'), strtotime($resultado['start_time']));
				$result[$key]['hora_fin'] = date($this->config->item('reserve_hour_filter_format'), strtotime($resultado['end_time']));
				$result[$key]['rango_horas'] = $result[$key]['hora_inicio'].' - '.$result[$key]['hora_fin'];
				setlocale(LC_TIME, "sp_SP");
				$dias_semana = $this->config->item('weekdays_names');
				
				$weekday_literal = '';
				if($resultado['L']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='L'; }
				if($resultado['M']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='M'; }
				if($resultado['X']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='X'; }
				if($resultado['J']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='J'; }
				if($resultado['V']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='V'; }
				if($resultado['S']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='S'; }
				if($resultado['D']=='1') { if($weekday_literal!='') $weekday_literal.='-'; $weekday_literal.='D'; }
				if($weekday_literal == '') $weekday_literal = '-';
				
				//$result[$code]['dia_semana'] = $dias_semana[$data['weekday']];
				$result[$key]['dia_semana'] = $weekday_literal;

				//$result[$key]['dia_semana'] = $dias_semana[$resultado['weekday']];
				if($resultado['max_vacancies'] == '0') $result[$key]['plazas'] = '0/0';
				elseif($resultado['current_vacancies'] == '') $result[$key]['plazas'] = '-/'.$resultado['max_vacancies'];
				else $result[$key]['plazas'] = $resultado['current_vacancies'].'/'.$resultado['max_vacancies'];
			}
			
			return $result;

		}

}
?>