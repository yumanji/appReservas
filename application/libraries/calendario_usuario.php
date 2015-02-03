<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */
 
class Calendario_usuario
{


	/**
	 * __construct
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function Calendario_usuario()
    {
		log_message('debug', "Calendario Class Initialized");
		$this->CI =& get_instance();
		log_message('debug', "Calendario Class Initialized - 2");

	}
	
	function js2PhpTime($jsdate){
	  if(preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches)==1){
	    $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
	    //echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
	  }else if(preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches)==1){
	    $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
	    //echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
	  }
	  return $ret;
	}
	
	function php2JsTime($phpDate){
	    //echo $phpDate;
	    //return "/Date(" . $phpDate*1000 . ")/";
	    return date("m/d/Y H:i", $phpDate);
	}
	
	function php2MySqlTime($phpDate){
	    return date("Y-m-d H:i:s", $phpDate);
	}
	
	function mySql2PhpTime($sqlDate){
	    $arr = date_parse($sqlDate);
	    return mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);
	
	}


function addCalendar($st, $et, $sub, $ade){
  $ret = array();
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    
    

    $start_date = date($this->CI->config->item('date_db_format'), strtotime($st));
    $end_date = date($this->CI->config->item('date_db_format'), strtotime($et));
    $weekday = date('N', strtotime($st));
    $start_time = date($this->CI->config->item('hour_db_format'), strtotime($st));
    $end_time = date($this->CI->config->item('hour_db_format'), strtotime($et));
		$data = array(
		               'description' => $sub ,
		               'start_date' => $start_date ,
		               'end_date' => $end_date ,
		               'start_time' => $start_time ,
		               'end_time' => $end_time ,
		               'weekday' => $weekday ,
		               'active' => 1 ,
		               'monthly_payment_day' => 28 ,
		               'create_user' => $this->CI->session->userdata('user_id') ,
		               'create_time' => date($this->CI->config->item('log_date_format')) ,
		               'create_ip' => $this->CI->session->userdata('ip_address')
		            );
		# Dato del curso creado, para irse a él al terminar
		$ret['Data'] = $this->CI->db->insert_id();
		
		$this->CI->db->insert('lessons', $data);
    log_message('debug',$this->CI->db->last_query());
		$data = array(
		               'id_lesson' => $this->CI->db->insert_id(),
		               'id_group' => 0 ,
		               'create_user' => $this->CI->session->userdata('user_id') ,
		               'create_time' => date($this->CI->config->item('log_date_format')) ,
		               'create_ip' => $this->CI->session->userdata('ip_address')
		            );
		
		$this->CI->db->insert('lessons_prices', $data);
    log_message('debug',$this->CI->db->last_query());
    //echo $st.' - '.$et.' - '.$sub.' - '.$start_date.' - '.$end_date.' - '.$start_time.' - '.$end_time	;
    //exit();
    
    
    //echo $sql;

		if($this->CI->db->affected_rows()==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = $this->CI->db->insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}


function addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $tz){
  $ret = array();
  try{
    $db = new DBConnection();
    $db->getConnection();
    $sql = "insert into `jqcalendar` (`subject`, `starttime`, `endtime`, `isalldayevent`, `description`, `location`, `color`) values ('"
      .mysql_real_escape_string($sub)."', '"
      .$this->php2MySqlTime($this->js2PhpTime($st))."', '"
      .$this->php2MySqlTime($this->js2PhpTime($et))."', '"
      .mysql_real_escape_string($ade)."', '"
      .mysql_real_escape_string($dscr)."', '"
      .mysql_real_escape_string($loc)."', '"
      .mysql_real_escape_string($color)."' )";
    //echo($sql);
		if(mysql_query($sql)==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'add success';
      $ret['Data'] = mysql_insert_id();
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function listCalendarByRange($sd, $ed){
  $ret = array();
  $ret['events'] = array();
  $ret["issort"] =true;
  $ret["start"] = $this->php2JsTime($sd);
  $ret["end"] = $this->php2JsTime($ed);
  $ret['error'] = null;
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $start = date($this->CI->config->item('date_db_format'), strtotime($this->php2MySqlTime($sd)));
    $current_date = date($this->CI->config->item('date_db_format'), strtotime($this->php2MySqlTime($sd)));
    $end = date($this->CI->config->item('date_db_format'), strtotime($this->php2MySqlTime($ed)));
    $eventos = array();
    

		##############
		## Eventos
		##############
    $this->CI->db->select('id, fixed, resume, content, start_date, end_date');
    $this->CI->db->from('events');
    $this->CI->db->where("events.active = '1' AND ((events.start_date <= '". $end."' AND events.end_date >= '".$start."') OR events.fixed = '1')");
    $handle = $this->CI->db->get();
    //echo $this->CI->db->last_query();
    foreach ($handle->result() as $row) {
    	if($row->fixed=='1') {
    		$weekday = date('w', strtotime($row->start_date));
    		array_push($eventos, array('id'=> 'e'.$row->id, 'tipo'=>'evento', 'resume'=> $row->resume, 'content'=> $row->content, 'fixed'=> $row->fixed, 'weekday'=> $weekday, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'start_time' => '', 'end_time' => '','id_court'=> '', 'id_instructor'=> '', 'instructor'=> '', 'court'=> '', 'max_vacancies'=> '', 'current_vacancies'=> '', 'gender'=> '', 'level'=> '', 'waiting' => ''));
    	} else {
	    	$inicio = $row->start_date;
	    	$fin = $row->end_date;
	    	$actual = $inicio;
	    	//echo $actual;
	    	while($actual <= $fin) {
	    		$weekday = date('w', strtotime($actual));
					array_push($eventos, array('id'=> 'e'.$row->id, 'tipo'=>'evento', 'resume'=> $row->resume, 'content'=> $row->content, 'fixed'=> $row->fixed, 'weekday'=> $weekday, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'start_time' => '', 'end_time' => '','id_court'=> '', 'id_instructor'=> '', 'instructor'=> '', 'court'=> '', 'max_vacancies'=> '', 'current_vacancies'=> '', 'gender'=> '', 'level'=> '', 'waiting' => ''));
					$actual = date($this->CI->config->item('date_db_format'), strtotime($actual . " +1 day"));
				}
			}
		}


		//print("<pre>");print_r($eventos);exit();
    //echo $current_date;

		#####################
		# Reservas
		#####################

		$profile=$this->CI->redux_auth->profile();
		$usuario = $profile->id;
		
		/*
		$this->CI->db->select('booking.id as id, id_booking, id_transaction, id_user, session, id_court, `date` as fecha, '.
						'intervalo, `status`, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, '.
						'booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, '.
		 				'booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, '.
						'meta.last_name as last_name,  meta.phone as phone, zz_booking_status.description as status_desc, '.
						'zz_paymentway.description as paymentway_desc, booking.price_light as price_light, booking.price_court as price_court', FALSE)->from($table_name);
						*/
		$this->CI->db->distinct('id_transaction', FALSE)->from('booking');
		$this->CI->db->group_by('id_transaction');    
		$this->CI->db->where("booking.status > 5 AND id_user = ".$usuario." AND (booking.date <= '". $end."' AND booking.date >= '".$start."')");
    $handle = $this->CI->db->get();
    //echo $this->CI->db->last_query();
    foreach ($handle->result() as $row) {
  		$info = $this->CI->reservas->getBookingInfoById($row->id_transaction);
  		$weekday = date('w', strtotime($info['date']));
			array_push($eventos, array('id'=> 'b'.$info['id_transaction'], 'tipo'=>'reserva', 'resume'=> 'Reserva '.$info['booking_code'].' en '.$info['court'], 'content'=> $info['operation_desc'], 'fixed'=> '', 'weekday'=> $weekday, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($info['date'])), 'start_date_original'=> $info['date'], 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($info['date'])), 'end_date_original'=> $info['date'], 'start_time' => $info['inicio'], 'end_time' => $info['fin'], 'id_court'=> $info['id_court'], 'id_instructor'=> '', 'instructor'=> '', 'court'=> $info['court'], 'max_vacancies'=> '', 'current_vacancies'=> '', 'gender'=> '', 'level'=> '', 'waiting' => ''));
		}

//print("<pre>");print_r($info);exit();


		#####################
		# Retos
		#####################

		/*
		$this->CI->db->select('booking.id as id, id_booking, id_transaction, id_user, session, id_court, `date` as fecha, '.
						'intervalo, `status`, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, '.
						'booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, '.
		 				'booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, '.
						'meta.last_name as last_name,  meta.phone as phone, zz_booking_status.description as status_desc, '.
						'zz_paymentway.description as paymentway_desc, booking.price_light as price_light, booking.price_court as price_court', FALSE)->from($table_name);
						*/
		
		$param = array('where' => 'booking_players.id_user = \''.$usuario.'\' AND booking_players.status IN (\'1\', \'2\', \'5\', \'7\')');
		$resultado = $this->CI->retos->get_data($param, 'all');
    //echo $this->CI->db->last_query();
    foreach ($resultado->result() as $row) {
  		$info = $this->CI->reservas->getBookingInfoById($row->id_transaction);
  		$weekday = date('w', strtotime($info['date']));
  		
			array_push($eventos, array('id'=> 'r'.$info['id_transaction'], 'tipo'=>'reto', 'resume'=> 'Reto en '.$info['court'], 'content'=> 'Reto en '.$info['court'].' de '.$info['inicio'].' a '.$info['fin'].'.'."\r\n".'Ocupaci&oacute;n: '.$info['signed'].'/'.$info['players']."\r\n".'Lista de espera: '.$info['waiting'], 'fixed'=> '', 'weekday'=> $weekday, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($info['date'])), 'start_date_original'=> $info['date'], 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($info['date'])), 'end_date_original'=> $info['date'], 'start_time' => $info['inicio'], 'end_time' => $info['fin'], 'id_court'=> $info['id_court'], 'id_instructor'=> '', 'instructor'=> '', 'court'=> $info['court'], 'max_vacancies'=> $info['players'], 'current_vacancies'=> '', 'gender'=> $info['gender'], 'level'=> '', 'waiting' => ''));
		}
//print("<pre>");print_r($eventos);







	#########################
	# PROCESADO

    $i=0;
    while($current_date <= $end && $i<32) {
    	$i++;
    	$weekday = date('N', strtotime($current_date));
    	//echo '<b>'.$current_date.'('.$weekday.')</b><br>';
    	

	    foreach ($eventos as $evento) {
				//echo $evento['description'].': current: '.$current_date.'   start:'.$evento['start_date_original'].'   end: '.$evento['end_date_original'].'  weekday: '.$evento['weekday'].'<br>';
	      if($evento['fixed']=='1' || ($weekday == $evento['weekday'] && ($current_date >= $evento['start_date_original'] && $current_date <= $evento['end_date_original']))) {
	      	//echo '<b>Valido</b><br>';
	      	if($evento['tipo']=='evento' && $evento['fixed']=='0') $color = '13';
	      	elseif($evento['tipo']=='evento') $color = '0';
	      	elseif($evento['tipo']=='reserva') $color = '10';
	      	elseif($evento['tipo']=='reto') $color = '6';
	      	else $color = '0';
	      	
	      	if($evento['start_time'] != '') {
	      		$st_date=$this->php2JsTime($this->mySql2PhpTime($current_date.' '.$evento['start_time']));
	      		$en_date=$this->php2JsTime($this->mySql2PhpTime($current_date.' '.$evento['end_time']));
	      		$allday=0;
	      	} else {
	      		$st_date=$this->php2JsTime($this->mySql2PhpTime($current_date.' 01:00:00'));
	      		$en_date=$this->php2JsTime($this->mySql2PhpTime($current_date.' 01:00:00'));;
	      		$allday=1;
	      		
	      	}
	      	
		      $ret['events'][] = array(
		        $evento['id'], //0
		        htmlspecialchars($evento['resume']),
		        $st_date,
		        $en_date, //3
		        $allday, //AllDayEvent
		        0, // AllDayEvent con la hora del evento
		        //$row->InstanceType,
		        0,//Recurring event,
		        $color, //Color
		        1,//editable
		        $evento['court'], //$row->Location, -> 9
		        '',//$attends
		        $evento['start_date'],
		        $evento['end_date'],
		        $evento['instructor'], //13
		        $evento['court'],
		        $evento['gender'],
		        $evento['level'],
		        $evento['max_vacancies'], //17
		        $evento['current_vacancies'],
		        $evento['waiting'],
		        $evento['content']
		      );
		    }
		    
	    }
	    
	    $current_date = date($this->CI->config->item('date_db_format'), strtotime($current_date.' +1 day'));
	    //echo $current_date;
	    
	  }
	}catch(Exception $e){
     $ret['error'] = $e->getMessage();
  }
  return $ret;
}


function listCalendar($day, $type){
  $phpTime = $this->js2PhpTime($day);
  //echo $phpTime . "+" . $type;
  switch($type){
    case "month":
      $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
      //exit ($st.'  -  '.$et);
      break;
    case "week":
      //suppose first day of a week is monday 
      $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
      //echo date('N', $phpTime);
      $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
      $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
      break;
    case "day":
      $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
      break;
  }
  //echo $st . "--" . $et;
  return $this->listCalendarByRange($st, $et);
}

function updateCalendar($id, $st, $et){
  $ret = array();
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $sql = "update `jqcalendar` set"
      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "' "
      . "where `id`=" . $id;
    $this->CI->db->query($sql);
    //echo $sql;
		if($this->CI->db->affected_rows()==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz){
  $ret = array();
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $sql = "update `jqcalendar` set"
      . " `starttime`='" . php2MySqlTime(js2PhpTime($st)) . "', "
      . " `endtime`='" . php2MySqlTime(js2PhpTime($et)) . "', "
      . " `subject`='" . mysql_real_escape_string($sub) . "', "
      . " `isalldayevent`='" . mysql_real_escape_string($ade) . "', "
      . " `description`='" . mysql_real_escape_string($dscr) . "', "
      . " `location`='" . mysql_real_escape_string($loc) . "', "
      . " `color`='" . mysql_real_escape_string($color) . "' "
      . "where `id`=" . $id;
    //echo $sql;
    $this->CI->db->query($sql);
    //echo $sql;
    //foreach ($handle->result() as $row) {
		if($this->CI->db->affected_rows()==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function removeCalendar($id){
  $ret = array();
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $sql = "delete from `jqcalendar` where `id`=" . $id;
    $this->CI->db->query($sql);
    //echo $sql;
		if($this->CI->db->affected_rows()==false){
      $ret['IsSuccess'] = false;
      $ret['Msg'] = mysql_error();
    }else{
      $ret['IsSuccess'] = true;
      $ret['Msg'] = 'Succefully';
    }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}

function getCalendarByRange($id){
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $this->CI->db->select('lessons.id, lessons.description, lessons.weekday, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.gender, zz_gender.description as gender_desc, lessons.level, zz_lessons_levels.description as level_desc,  lessons.max_vacancies, lessons.current_vacancies, lessons.id_instructor, lessons.monthly_payment_day, lessons.id_sport, lessons.id_court, courts.name as court_desc, meta.first_name, meta.last_name, lessons_prices.signin, lessons_prices.monthly');
    $this->CI->db->from('lessons');
    $this->CI->db->join('courts', 'lessons.id_court = courts.id', 'left outer');
    $this->CI->db->join('meta', 'lessons.id_instructor = meta.user_id', 'left outer');
    $this->CI->db->join('zz_gender', 'lessons.gender = zz_gender.id', 'left outer');
    $this->CI->db->join('zz_lessons_levels', 'lessons.level = zz_lessons_levels.id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    $this->CI->db->join('lessons_prices', 'lessons.id = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons.id = '". $id."'");
    
    $handle = $this->CI->db->get();
    //echo $this->CI->db->last_query();
    //echo $sql;
    //echo $sql;
    //$resultado = array();
    $row = $handle->row();
	}catch(Exception $e){
  }
  return $row;
}



function getAssistantInfo($id){
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $this->CI->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons.description, lessons_assistants.status, lessons_assistants.sign_date, lessons_assistants.unsubscription_date, lessons_assistants.last_payd_date, lessons_assistants.last_day_payed, lessons.monthly_payment_day, meta.first_name, meta.last_name, zz_lessons_assistants_status.description as status_desc');
    $this->CI->db->from('lessons_assistants');
    $this->CI->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->CI->db->join('zz_lessons_assistants_status', 'lessons_assistants.status = zz_lessons_assistants_status.id', 'left outer');
    $this->CI->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    $this->CI->db->join('lessons_prices', 'lessons_assistants.id_lesson = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons_assistants.id = '". $id."'");
    
    $handle = $this->CI->db->get();
    log_message('debug',$this->CI->db->last_query());
    //echo $this->CI->db->last_query();
    //echo $sql;
    //echo $sql;
    //$resultado = array();
    $resultado = $handle->result_array();
    
    //print("<pre>");print_r($resultado);exit();
    $row = $handle->row();
	}catch(Exception $e){
  }
  return $row;
}



function checkAssistant($lesson, $id_user = NULL, $user_desc = NULL){
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $this->CI->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons.description, lessons_assistants.status, lessons_assistants.sign_date, lessons_assistants.unsubscription_date, lessons_assistants.last_payd_date, lessons.monthly_payment_day, meta.first_name, meta.last_name, zz_lessons_assistants_status.description as status_desc');
    $this->CI->db->from('lessons_assistants');
    $this->CI->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->CI->db->join('zz_lessons_assistants_status', 'lessons_assistants.status = zz_lessons_assistants_status.id', 'left outer');
    $this->CI->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    $this->CI->db->join('lessons_prices', 'lessons_assistants.id_lesson = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons_assistants.id_lesson = '". $lesson."'");
    if(isset($id_user) && $id_user!="") $this->CI->db->where("lessons_assistants.id_user = '". $id_user."'");
    elseif(isset($user_desc) && $user_desc!="") $this->CI->db->where("lessons_assistants.user_desc = '". $user_desc."'");
    else return FALSE;
    
    $handle = $this->CI->db->get();
    log_message('debug',$this->CI->db->last_query());
    //echo $this->CI->db->last_query();
    //echo $sql;
    //echo $sql;
    //$resultado = array();
    if($handle->num_rows() >0) return TRUE;
    
    //print("<pre>");print_r($resultado);exit();
	}catch(Exception $e){
  }
  return FALSE;
}



function getAssistantPaymentInfo($id){
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $this->CI->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons.description, lessons_assistants.status, lessons_assistants.sign_date, lessons_assistants.unsubscription_date, lessons_assistants.last_payd_date, lessons.monthly_payment_day, meta.first_name, meta.last_name, zz_lessons_assistants_status.description as status_desc');
    $this->CI->db->from('lessons_assistants');
    $this->CI->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->CI->db->join('zz_lessons_assistants_status', 'lessons_assistants.status = zz_lessons_assistants_status.id', 'left outer');
    $this->CI->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    $this->CI->db->join('lessons_prices', 'lessons_assistants.id_lesson = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons_assistants.id = '". $id."'");
    
    $handle = $this->CI->db->get();
    //echo $this->CI->db->last_query();
    //echo $sql;
    //echo $sql;
    //$resultado = array();
    $resultado[] = $handle->result_array();
    
    //print("<pre>");print_r($resultado);exit();
	}catch(Exception $e){
  }
  return $row;
}

	
}