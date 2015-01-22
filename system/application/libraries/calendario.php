<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */
 
class Calendario
{


	/**
	 * __construct
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function Calendario()
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
    $L = 0; $M = 0; $X = 0; $J = 0; $V = 0; $S = 0; $D = 0; 
    switch($weekday) {
    	case '1':
    		$L = 1;
    	break;
    	case '2':
    		$M = 1;
    	break;
    	case '3':
    		$X = 1;
    	break;
    	case '4':
    		$J = 1;
    	break;
    	case '5':
    		$V = 1;
    	break;
    	case '6':
    		$S = 1;
    	break;
    	case '7':
    		$D = 1;
    	break;
    }
    $start_time = date($this->CI->config->item('hour_db_format'), strtotime($st));
    $end_time = date($this->CI->config->item('hour_db_format'), strtotime($et));
		$data = array(
		               'description' => $sub ,
		               'start_date' => $start_date ,
		               'end_date' => $end_date ,
		               'start_time' => $start_time ,
		               'end_time' => $end_time ,
		               'weekday' => $weekday ,
		               'L' => $L ,
		               'M' => $L ,
		               'X' => $L ,
		               'J' => $L ,
		               'V' => $L ,
		               'S' => $L ,
		               'D' => $L ,
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
    /*
		$data = array(
		               'id_lesson' => $this->CI->db->insert_id(),
		               'id_group' => 0 ,
		               'create_user' => $this->CI->session->userdata('user_id') ,
		               'create_time' => date($this->CI->config->item('log_date_format')) ,
		               'create_ip' => $this->CI->session->userdata('ip_address')
		            );
		
		$this->CI->db->insert('lessons_prices', $data);
    log_message('debug',$this->CI->db->last_query());
    */
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
	//exit(date('U', strtotime(date($this->CI->config->item('date_db_format')))).' ++++ '.date('U', strtotime(date($this->CI->config->item('date_db_format')).' 23:59:59')).' ++++ '.$sd.' ----- '.$ed);
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
    $cursos = array();
    
    $curso_seleccionado = $this->CI->session->userdata('selected_lesson');
    
    $this->CI->db->select('lessons.id, lessons.description, lessons.weekday, lessons.M, lessons.L, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.gender, zz_gender.description as gender_desc, lessons.level, zz_lessons_levels.description as level_desc,  lessons.max_vacancies, lessons.current_vacancies, lessons.id_instructor, lessons.monthly_payment_day, lessons.id_sport, lessons.id_court, lessons.price, lessons.signin, courts.name as court_desc, meta.first_name, meta.last_name');
    $this->CI->db->from('lessons');
    $this->CI->db->join('courts', 'lessons.id_court = courts.id', 'left outer');
    $this->CI->db->join('meta', 'lessons.id_instructor = meta.user_id', 'left outer');
    $this->CI->db->join('zz_gender', 'lessons.gender = zz_gender.id', 'left outer');
    $this->CI->db->join('zz_lessons_levels', 'lessons.level = zz_lessons_levels.id', 'left outer');
    $this->CI->db->where("lessons.Active = '1' AND (lessons.start_date <= '". $end."' AND lessons.end_date >= '".$start."')");
    if($curso_seleccionado!='') {
    	$this->CI->db->where("lessons.id = '".$curso_seleccionado."'");
    	$this->CI->session->unset_userdata('selected_lesson');
    }
    
    $handle = $this->CI->db->get();
    
    //$sql = "SELECT * FROM lessons WHERE Active = '1' AND (start_date <= '". $end."' AND end_date >= '".$start."')";
    //$handle = $this->CI->db->query($sql);
    //echo $this->CI->db->last_query();
    foreach ($handle->result() as $row) {
    	
	    $this->CI->db->select('count(*) as cantidad');
	    $this->CI->db->from('lessons_assistants');
	    $this->CI->db->where("lessons_assistants.id_lesson = '".$row->id."' AND status='7'");
	    $handle2 = $this->CI->db->get();
	    $resultado = $handle2->row();
			
			//array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> $row->weekday, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->L == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 1, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->M == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 2, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->X == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 3, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->J == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 4, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->V == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 5, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->S == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 6, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
  		if($row->D == '1') array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> 7, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
			
		}
		//print("<pre>");print_r($cursos);exit();
    //echo $current_date;




    $i=0;
    while($current_date <= $end && $i<32) {
    	$i++;
    	$weekday = date('N', strtotime($current_date));
    	//echo '<b>'.$current_date.'('.$weekday.')</b><br>';
    	

	    foreach ($cursos as $curso) {
				//echo $curso['description'].': current: '.$current_date.'   start:'.$curso['start_date_original'].'   end: '.$curso['end_date_original'].'  weekday: '.$curso['weekday'].'<br>';
	      if($weekday == $curso['weekday'] && ($current_date >= $curso['start_date_original'] && $current_date <= $curso['end_date_original'])) {
	      	//echo '<b>Valido</b><br>';
	      	if($curso['max_vacancies']==0) $color = '0';
	      	elseif($curso['current_vacancies']==0) $color = '1';
	      	elseif($curso['current_vacancies']<($curso['max_vacancies']/2)) $color = '13';
	      	//elseif($curso['current_vacancies']==$curso['max_vacancies']) $color = '10';
	      	else $color = '10';
	      	
		      $ret['events'][] = array(
		        $curso['id'], //0
		        htmlspecialchars($curso['description']),
		        $this->php2JsTime($this->mySql2PhpTime($current_date.' '.$curso['start_time'])),
		        $this->php2JsTime($this->mySql2PhpTime($current_date.' '.$curso['end_time'])), //3
		        0, // AllDayEvent
		        0, //more than one day event
		        //$row->InstanceType,
		        0,//Recurring event,
		        $color, //Color
		        1,//editable
		        $curso['court'], //$row->Location, -> 9
		        '',//$attends
		        $curso['start_date'],
		        $curso['end_date'],
		        $curso['instructor'], //13
		        $curso['court'],
		        $curso['gender'],
		        $curso['level'],
		        $curso['max_vacancies'], //17
		        $curso['current_vacancies'],
		        $curso['waiting']
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




function listCalendarByRangeByLesson($sd, $ed, $id){
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
    $cursos = array();
    
    
    $this->CI->db->select('lessons.id, lessons.description, lessons.weekday, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.gender, zz_gender.description as gender_desc, lessons.level, zz_lessons_levels.description as level_desc,  lessons.max_vacancies, lessons.current_vacancies, lessons.id_instructor, lessons.monthly_payment_day, lessons.id_sport, lessons.id_court, lessons.price, lessons.signin, courts.name as court_desc, meta.first_name, meta.last_name');
    $this->CI->db->from('lessons');
    $this->CI->db->join('courts', 'lessons.id_court = courts.id', 'left outer');
    $this->CI->db->join('meta', 'lessons.id_instructor = meta.user_id', 'left outer');
    $this->CI->db->join('zz_gender', 'lessons.gender = zz_gender.id', 'left outer');
    $this->CI->db->join('zz_lessons_levels', 'lessons.level = zz_lessons_levels.id', 'left outer');
    $this->CI->db->where("lessons.Active = '1' AND lessons.id = '".$id."' AND (lessons.start_date <= '". $end."' AND lessons.end_date >= '".$start."')");
    
    $handle = $this->CI->db->get();
    
    //$sql = "SELECT * FROM lessons WHERE Active = '1' AND (start_date <= '". $end."' AND end_date >= '".$start."')";
    //$handle = $this->CI->db->query($sql);
    //echo $this->CI->db->last_query();
    foreach ($handle->result() as $row) {
    	
	    $this->CI->db->select('count(*) as cantidad');
	    $this->CI->db->from('lessons_assistants');
	    $this->CI->db->where("lessons_assistants.id_lesson = '".$row->id."' AND status='7'");
	    $handle2 = $this->CI->db->get();
	    $resultado = $handle2->row();
			
			array_push($cursos, array('id'=> $row->id, 'description'=> $row->description, 'weekday'=> $row->weekday, 'start_time'=> $row->start_time, 'end_time'=> $row->end_time, 'start_date'=> date($this->CI->config->item('reserve_date_filter_format'),strtotime($row->start_date)), 'start_date_original'=> $row->start_date, 'end_date'=> date($this->CI->config->item('reserve_date_filter_format'), strtotime($row->end_date)), 'end_date_original'=> $row->end_date, 'id_court'=> $row->id_court, 'id_instructor'=> $row->id_instructor, 'instructor'=> $row->first_name.' '.$row->last_name, 'court'=> $row->court_desc, 'max_vacancies'=> $row->max_vacancies, 'current_vacancies'=> $row->current_vacancies, 'gender'=> $row->gender_desc, 'level'=> $row->level_desc, 'waiting' => $resultado->cantidad));
			
		}
		//print("<pre>");print_r($cursos);exit();
    //echo $current_date;




    $i=0;
    while($current_date <= $end && $i<32) {
    	$i++;
    	$weekday = date('N', strtotime($current_date));
    	//echo '<b>'.$current_date.'('.$weekday.')</b><br>';
    	

	    foreach ($cursos as $curso) {
				//echo $curso['description'].': current: '.$current_date.'   start:'.$curso['start_date_original'].'   end: '.$curso['end_date_original'].'  weekday: '.$curso['weekday'].'<br>';
	      if($weekday == $curso['weekday'] && ($current_date >= $curso['start_date_original'] && $current_date <= $curso['end_date_original'])) {
	      	//echo '<b>Valido</b><br>';
	      	if($curso['max_vacancies']==0) $color = '0';
	      	elseif($curso['current_vacancies']==0) $color = '1';
	      	elseif($curso['current_vacancies']<($curso['max_vacancies']/2)) $color = '13';
	      	//elseif($curso['current_vacancies']==$curso['max_vacancies']) $color = '10';
	      	else $color = '10';
	      	
		      $ret['events'][] = array(
		        $curso['id'], //0
		        htmlspecialchars($curso['description']),
		        $this->php2JsTime($this->mySql2PhpTime($current_date.' '.$curso['start_time'])),
		        $this->php2JsTime($this->mySql2PhpTime($current_date.' '.$curso['end_time'])), //3
		        0, // AllDayEvent
		        0, //more than one day event
		        //$row->InstanceType,
		        0,//Recurring event,
		        $color, //Color
		        1,//editable
		        $curso['court'], //$row->Location, -> 9
		        '',//$attends
		        $curso['start_date'],
		        $curso['end_date'],
		        $curso['instructor'], //13
		        $curso['court'],
		        $curso['gender'],
		        $curso['level'],
		        $curso['max_vacancies'], //17
		        $curso['current_vacancies'],
		        $curso['waiting']
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


function listCalendar($day, $type, $id = NULL){
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
  if(!isset($id)) return $this->listCalendarByRange($st, $et);
  else  return $this->listCalendarByRangeByLesson($st, $et, $id);
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

function getCalendarByRange($id, $format = NULL){
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $this->CI->db->select('lessons.id, lessons.description, lessons.active, lessons.weekday, lessons.start_time, lessons.end_time, lessons.start_date, lessons.end_date, lessons.gender, zz_gender.description as gender_desc, lessons.level, zz_lessons_levels.description as level_desc,  lessons.max_vacancies, lessons.current_vacancies, lessons.id_instructor, lessons.monthly_payment_day, lessons.id_sport, lessons.id_court, lessons.L, lessons.M, lessons.X, lessons.J, lessons.V, lessons.S, lessons.D, lessons.price, lessons.signin, courts.name as court_desc, meta.first_name, meta.last_name, prices.duration as price_duration, prices.id_frequency as frequency');
    $this->CI->db->from('lessons');
    $this->CI->db->join('courts', 'lessons.id_court = courts.id', 'left outer');
    $this->CI->db->join('meta', 'lessons.id_instructor = meta.user_id', 'left outer');
    $this->CI->db->join('zz_gender', 'lessons.gender = zz_gender.id', 'left outer');
    $this->CI->db->join('zz_lessons_levels', 'lessons.level = zz_lessons_levels.id', 'left outer');
    $this->CI->db->join('prices', 'lessons.price = prices.id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    //$this->CI->db->join('lessons_prices', 'lessons.id = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons.id = '". $id."'");
    
    $handle = $this->CI->db->get();
    //echo $this->CI->db->last_query();
    //echo $sql;
    //echo $sql;
    //$resultado = array();
    if(isset($format) && $format == 'array') $row = $handle->row_array();
    else $row = $handle->row();
	}catch(Exception $e){
  }
  return $row;
}



function getAssistantInfo($id){
    //$db = new DBConnection();
    //$db->getConnection();
    /*
    $this->CI->db->flush_cache();
    $this->CI->db->stop_cache();
    $this->CI->db->cache_delete_all();
    $this->CI->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons.description, lessons_assistants.status, lessons_assistants.sign_date, lessons_assistants.unsubscription_date, lessons_assistants.last_payd_date, lessons_assistants.last_day_payed, lessons.monthly_payment_day, meta.first_name, meta.last_name, zz_lessons_assistants_status.description as status_desc');
    $this->CI->db->from('lessons_assistants');
    $this->CI->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->CI->db->join('zz_lessons_assistants_status', 'lessons_assistants.status = zz_lessons_assistants_status.id', 'left outer');
    $this->CI->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    $this->CI->db->join('lessons_prices', 'lessons_assistants.id_lesson = lessons_prices.id_lesson AND (lessons_prices.id_group = 0 OR lessons_prices.id_group IS NULL)', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons_assistants.id = '". $id."'");
    */
    $sql = "SELECT `lessons_assistants`.`id`, `lessons_assistants`.`id_lesson`, `lessons_assistants`.`id_user`, `lessons_assistants`.`user_desc`, `lessons_assistants`.`user_phone`, `lessons`.`description`, `lessons_assistants`.`status`, `lessons_assistants`.`sign_date`, `lessons_assistants`.`unsubscription_date`, `lessons_assistants`.`last_payd_date`, `lessons_assistants`.`last_day_payed`, `lessons_assistants`.`discount`, `lessons_assistants`.`discount_type`, `lessons`.`monthly_payment_day`, lessons.price, lessons.signin, `meta`.`first_name`, `meta`.`last_name`, `zz_lessons_assistants_status`.`description` as status_desc FROM (`lessons_assistants`) LEFT OUTER JOIN `lessons` ON `lessons_assistants`.`id_lesson` = `lessons`.`id` LEFT OUTER JOIN `zz_lessons_assistants_status` ON `lessons_assistants`.`status` = `zz_lessons_assistants_status`.`id` LEFT OUTER JOIN `meta` ON `lessons_assistants`.`id_user` = `meta`.`user_id`  WHERE `lessons_assistants`.`id` = ?";
    $handle = $this->CI->db->query($sql, array($id));
    //$handle = $this->CI->db->get();
    //log_message('debug',$this->CI->db->last_query());
    //echo $this->CI->db->last_query();
    //echo $sql;
    //echo $sql;
    //$resultado = array();
    $resultado = $handle->result_array();
    
    //print("<pre>");print_r($resultado);exit();
    $row = $handle->row();
  return $row;
}



function checkAssistant($lesson, $id_user = NULL, $user_desc = NULL, $id = NULL){
  try{
    //$db = new DBConnection();
    //$db->getConnection();
    $this->CI->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons.description, lessons_assistants.status, lessons_assistants.sign_date, lessons_assistants.unsubscription_date, lessons_assistants.last_payd_date, lessons.monthly_payment_day, lessons.price, lessons.signin, meta.first_name, meta.last_name, zz_lessons_assistants_status.description as status_desc');
    $this->CI->db->from('lessons_assistants');
    $this->CI->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->CI->db->join('zz_lessons_assistants_status', 'lessons_assistants.status = zz_lessons_assistants_status.id', 'left outer');
    $this->CI->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    //$this->CI->db->join('lessons_prices', 'lessons_assistants.id_lesson = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
    //$this->CI->db->where("lessons.Active = '1' AND lessons.id = '". $id."'");
    $this->CI->db->where("lessons_assistants.id_lesson = '". $lesson."'");
    $this->CI->db->where("lessons_assistants.status IN (1,2,3,7)");
    if(isset($id_user) && $id_user!="") $this->CI->db->where("lessons_assistants.id_user = '". $id_user."'");
    elseif(isset($user_desc) && $user_desc!="") $this->CI->db->where("lessons_assistants.user_desc = '". $user_desc."'");
    elseif(isset($id) && $id!="") $this->CI->db->where("lessons_assistants.id = '". $id."'");
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
    $this->CI->db->select('lessons_assistants.id, lessons_assistants.id_lesson, lessons_assistants.id_user, lessons_assistants.user_desc, lessons_assistants.user_phone, lessons.description, lessons_assistants.status, lessons_assistants.sign_date, lessons_assistants.unsubscription_date, lessons_assistants.last_payd_date, lessons.monthly_payment_day, lessons.price, lessons.signin, meta.first_name, meta.last_name, zz_lessons_assistants_status.description as status_desc');
    $this->CI->db->from('lessons_assistants');
    $this->CI->db->join('lessons', 'lessons_assistants.id_lesson = lessons.id', 'left outer');
    $this->CI->db->join('zz_lessons_assistants_status', 'lessons_assistants.status = zz_lessons_assistants_status.id', 'left outer');
    $this->CI->db->join('meta', 'lessons_assistants.id_user = meta.user_id', 'left outer');
    
    // Con esto saco, temporalmente, los precios genéricos del curso (sin atender a niveles de usuarios)
    //$this->CI->db->join('lessons_prices', 'lessons_assistants.id_lesson = lessons_prices.id_lesson AND lessons_prices.id_group = 0', 'left outer');
    
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





 ##############
 #
 # Registra el pago de la cuota de usuario
 #
 ####################
	public function pay_user_quota($asistente, $options = NULL) {
		
		if(isset($options['object']) && is_array($options['object'])) $assistant=$options['object'];
		else $assistant=get_object_vars ($this->CI->calendario->getAssistantInfo($asistente));
		//print('asistente<pre>');print_r($assistant);
		$assistant_info = $this->CI->users->get_user($assistant['id_user']);
		//print('usuario<pre>');print_r($assistant_info);
		$info = $this->CI->calendario->getCalendarByRange($assistant['id_lesson']);
		//print('curso<pre>');print_r($info);
		$this->CI->load->model('Payment_model', 'pagos', TRUE);
//exit();

		if(!isset($quantity) || $quantity == '') $quantity = 1;	// Mensualidades por defecto a pagar
		
		# Si se ha marcado como dado de alta el usuario, seguimos..
		if($assistant['signed'] == '1') {

			if(!isset($assistant['last_day_payed']) || $assistant['last_day_payed']=="") {
				$ultima_fecha = $assistant['sign_date'];
				$dia = date('d', strtotime($ultima_fecha));
				$dia_de_pago = '01';
				if($dia < $dia_de_pago) {
					$trozos = split('-', $ultima_fecha);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
				} elseif($dia > $dia_de_pago) {
					$fecha_siguiente = date($this->CI->config->item('log_date_format'), strtotime($assistant['sign_date'].' +'.$info->price_duration.' '.$this->CI->pagos->getFrequencyCommand($info->frequency)));
					$trozos = split('-', $fecha_siguiente);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
				} else {
					$last_payd_date = date($this->CI->config->item('log_date_format'), strtotime($assistant['sign_date'].' +'.$info->price_duration.' '.$this->CI->pagos->getFrequencyCommand($info->frequency)));
				}
				
				//echo "1<br>";
			} else {
				$last_payd_date = date($this->CI->config->item('log_date_format'), strtotime($assistant['last_day_payed'].' +'.$info->price_duration.' '.$this->CI->pagos->getFrequencyCommand($info->frequency)));
				//echo "2<br>";
			}		
			//echo '<br>... las_payd_date: '.$last_payd_date; exit();
			
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
					if($this->CI->lessons->setMonthlyPayment($asistente, $last_payd_date)) $this->CI->session->set_userdata('info_message', 'Pago hasta el '.date($this->CI->config->item('reserve_date_filter_format'), strtotime($last_payd_date)).' realizado');
					else {
						return FALSE;
					}
	
					if($assistant['id_user'] != '' && $assistant['id_user'] != 0) $user_desc = $assistant['first_name'].' '.$assistant['last_name'];
					else $user_desc = $assistant['user_desc'];
				
					$estado = 9;
					if($paymentway == 4) $estado = 2;
					if($pay_amount == 0) $estado = 9;	
					
					$this->CI->pagos->id_type=2; //Clases y cursos
					$this->CI->pagos->id_element=$this->CI->session->userdata('session_id');
					$this->CI->pagos->id_transaction='l-'.$assistant['id_lesson'].'-'.$assistant['id_user'].'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
					$this->CI->pagos->id_user=$assistant['id_user'];
					$this->CI->pagos->desc_user=$user_desc;
					$this->CI->pagos->id_paymentway = $paymentway;
					$this->CI->pagos->status=$estado;
					$this->CI->pagos->quantity = $pay_amount;
					$this->CI->pagos->datetime=date($this->CI->config->item('log_date_format'));
					$this->CI->pagos->description="Cuota mensual del curso '".$assistant['description']."', hasta el ".$last_payd_date;
					$this->CI->pagos->create_user=$this->CI->session->userdata('user_id');
					$this->CI->pagos->create_time=date($this->CI->config->item('log_date_format'));
					
					$this->CI->pagos->setPayment();
				
				
					$pago = $this->CI->pagos->getPaymentByTransaction($this->CI->pagos->id_transaction);
				
				
					return TRUE;
				} else return FALSE;

		
		} else return FALSE; // no está marcado como dado de alta



return NULL;

	}






 ##############
 #
 # Registra el pago de la cuota de usuario
 #
 ####################
	public function pay_user_quota_torrijos($asistente, $options = NULL) {
		
		if(isset($options['object']) && is_array($options['object'])) $assistant=$options['object'];
		else $assistant=get_object_vars ($this->CI->calendario->getAssistantInfo($asistente));
		//print('asistente<pre>');print_r($assistant);
		$assistant_info = $this->CI->users->get_user($assistant['id_user']);
		//print('usuario<pre>');print_r($assistant_info);
		$info = $this->CI->calendario->getCalendarByRange($assistant['id_lesson']);
		//print('curso<pre>');print_r($info);exit();
		$this->CI->load->model('Payment_model', 'pagos', TRUE);
//exit();

		if(!isset($quantity) || $quantity == '') $quantity = 1;	// Mensualidades por defecto a pagar
		
		# Si se ha marcado como dado de alta el usuario, seguimos..
		if($assistant['signed'] == '1') {

			if(!isset($assistant['last_day_payed']) || $assistant['last_day_payed']=="") {
				$ultima_fecha = $assistant['sign_date'];
				$dia = date('d', strtotime($ultima_fecha));
				$dia_de_pago = '01';
				if($dia < $dia_de_pago) {
					$trozos = split('-', $ultima_fecha);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
				} elseif($dia > $dia_de_pago) {
					$fecha_siguiente = date($this->CI->config->item('log_date_format'), strtotime($assistant['sign_date'].' +'.$info->price_duration.' '.$this->CI->pagos->getFrequencyCommand($info->frequency)));
					$trozos = split('-', $fecha_siguiente);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
				} else {
					$last_payd_date = date($this->CI->config->item('log_date_format'), strtotime($assistant['sign_date'].' +'.$info->price_duration.' '.$this->CI->pagos->getFrequencyCommand($info->frequency)));
				}
				
				//echo "1<br>";
			} else {
				$last_payd_date = date($this->CI->config->item('log_date_format'), strtotime($assistant['last_day_payed'].' +'.$info->price_duration.' '.$this->CI->pagos->getFrequencyCommand($info->frequency)));
				//echo "2<br>";
			}		
			//echo '<br>... las_payd_date: '.$last_payd_date; exit();
			
			$paymentway = 1;	//Forma pago temporal.. por banco

			if(isset($quantity) && $quantity!=0 && $quantity!="" && isset($paymentway) && $paymentway!=0 && $paymentway!="") {
					/*
					$cuota = $assistant['quota'];
					$pay_amount_tmp = $cuota * $quantity;
					if($assistant->discount_type == '%') $pay_amount = $pay_amount_tmp - ($pay_amount_tmp * $assistant->discount / 100);
					else $pay_amount = $pay_amount_tmp - $assistant->discount;
					*/
					$pay_amount = $assistant['quota'];
					//exit('cuota:'.$pay_amount);
					if($this->CI->lessons->setMonthlyPayment($asistente, $last_payd_date)) $this->CI->session->set_userdata('info_message', 'Pago hasta el '.date($this->CI->config->item('reserve_date_filter_format'), strtotime($last_payd_date)).' realizado');
					else {
						return FALSE;
					}
	
					if($assistant['id_user'] != '' && $assistant['id_user'] != 0) $user_desc = $assistant['first_name'].' '.$assistant['last_name'];
					else $user_desc = $assistant['user_desc'];
				
					$estado = 9;
					if($paymentway == 4) $estado = 2;
					if($pay_amount == 0) $estado = 9;	
					
					$this->CI->pagos->id_type=2; //Clases y cursos
					$this->CI->pagos->id_element=$this->CI->session->userdata('session_id');
					$this->CI->pagos->id_transaction='l-'.$assistant['id_lesson'].'-'.$assistant['id_user'].'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
					$this->CI->pagos->id_user=$assistant['id_user'];
					$this->CI->pagos->desc_user=$user_desc;
					$this->CI->pagos->id_paymentway = $paymentway;
					$this->CI->pagos->status=$estado;
					$this->CI->pagos->quantity = $pay_amount;
					$this->CI->pagos->datetime=date($this->CI->config->item('log_date_format'));
					$this->CI->pagos->description="Cuota mensual del curso '".$assistant['description']."', hasta el ".$last_payd_date;
					$this->CI->pagos->create_user=$this->CI->session->userdata('user_id');
					$this->CI->pagos->create_time=date($this->CI->config->item('log_date_format'));
					
					$this->CI->pagos->setPayment();
				
				
					$pago = $this->CI->pagos->getPaymentByTransaction($this->CI->pagos->id_transaction);
					//print_r($pago); exit();
				


					##########################################3
					##########################################
					## PINTAR
					###############################
					$quota = number_format($pay_amount, 2);
					//print_r($array_result);exit();
					$imgPath = $this->CI->config->item('root_path').'images/templates/plantilla.jpg';
					//$imgStampPath = $this->CI->config->item('root_path').'images/users/'.$array_result['avatar'];
					$font = $this->CI->config->item('root_path').'system/fonts/FreeSansBold.ttf';
					$doc_title = 'Cuota de curso';
					//print_r($array_result);
					//$imgPath = $this->config->item('root_path').'images/templates/'.$carnet_permission[$array_result['group_id']];
					//$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
					if(!file_exists($imgPath) || !file_exists($font)) exit ('Fallo en la carga de las plantillas necesarias');
					
					# Abro plantilla de carnet
					$size=getimagesize($imgPath);
					switch($size["mime"]){
						case "image/jpeg":
							$image = imagecreatefromjpeg($imgPath); //jpeg file
						break;
						case "image/gif":
							$image = imagecreatefromgif($imgPath); //gif file
					  break;
					  case "image/png":
						  $image = imagecreatefrompng($imgPath); //png file
					  break;
					  default: 
						$image=false;
					  break;
					}
					if(!$image) exit ('Fallo en la carga de las plantillas necesarias');


					


					// Set the margins for the stamp and get the height/width of the stamp image
					$marge_right = 200;
					$marge_bottom = 1900;
					//imagecopy($image, $fotocarnet_thumb, imagesx($image) - $ancho_fotocarnet_thumb - $marge_right, imagesy($image) - $alto_fotocarnet_thumb - $marge_bottom, 0, 0, $ancho_fotocarnet_thumb, $alto_fotocarnet_thumb);

					
					# Escribo los datos del usuario
					$white = imagecolorallocate($image, 255, 255, 255);
					$grey = imagecolorallocate($image, 128, 128, 128);
					$black = imagecolorallocate($image, 0, 0, 0);
					$fontSize = 46;	// Tamaño de texto normal
					$text_xpos = 270;	// Margen izquierdo

					imagettftext($image, 72, 0, 1200, 300, $black, $font, $doc_title);	// Titulo
					
					//$array_result= array('user_lastname' => 'Nieto Castellano', 'user_name' => 'Juan José', 'cif' => '50107654S', 'birthdate' => '20/08/1977', 'address' => 'Calle de Constancia, 17, 3º C', 'population' => 'Torrijos de arribarrigota', 'cp' => '28058', 'phone' => '915092162', 'phone2' => '656424453', 'email' => 'juanjitojuanjitoo0000o.nieto@gmail.com');

					imagettftext($image, $fontSize, 0, $text_xpos, 665, $black, $font, $assistant_info['user_lastname'].', '.$assistant_info['user_name']);	// Nombre
					imagettftext($image, $fontSize, 0, $text_xpos+1600, 665, $black, $font, $assistant_info['nif']);	// DNI
					imagettftext($image, $fontSize, 0, $text_xpos, 850, $black, $font, date($this->CI->config->item('reserve_date_filter_format'),strtotime($assistant_info['birth_date'])));	// fecha nacimiento
					imagettftext($image, $fontSize-5, 0, $text_xpos+400, 850, $black, $font, $assistant_info['address']);	// direccion
					if(strlen($assistant_info['population'])<= 15 ) imagettftext($image, $fontSize, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);	// telefono movil
					elseif(strlen($assistant_info['population'])<= 20 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);
					elseif(strlen($assistant_info['population'])<= 23 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);
					else imagettftext($image, $fontSize-20, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);
					imagettftext($image, $fontSize, 0, $text_xpos, 1030, $black, $font, $assistant_info['cp']);	// codigo postal
					//imagettftext($image, $fontSize, 0, $text_xpos+450, 1030, $black, $font, $assistant_info['user_phone']);	// telefono fijo
					imagettftext($image, $fontSize, 0, $text_xpos+400, 1030, $black, $font, $assistant_info['user_phone']);	// telefono movil
					imagettftext($image, $fontSize, 0, $text_xpos+900, 1030, $black, $font, $assistant_info['user_id']);	// telefono movil
					if(strlen($assistant_info['email'])<= 22 ) imagettftext($image, $fontSize, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);	// telefono movil
					elseif(strlen($assistant_info['email'])<= 30 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);
					elseif(strlen($assistant_info['email'])<= 34 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);
					else imagettftext($image, $fontSize-20, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);

					imagettftext($image, $fontSize-5, 0, $text_xpos, 1400, $black, $font, 'Como '.$assistant_info['group_description'].' para el curso "'.$info->description.'"  ');	// Nombre
					imagettftext($image, $fontSize-5, 0, $text_xpos, 1500, $black, $font, 'la cuota a abonar es de '.$quota.' euros. Quedará pagado hasta el '.date($this->CI->config->item('reserve_date_filter_format'),strtotime($last_payd_date)));	// Nombre
					imagettftext($image, $fontSize-5, 0, $text_xpos, 1600, $black, $font, 'En el concepto de pago deberá poner \''.$pago->ticket_number.'\'');	// Nombre
					imagettftext($image, $fontSize-5, 0, $text_xpos, 1800, $black, $font, 'El ingreso deberá realizarse en alguno de los siguientes números de cuenta:');	// Nombre
					imagettftext($image, $fontSize, 0, $text_xpos+50, 1900, $black, $font, '2105 0039 34 1290022090 (Caja Castilla-La Mancha)');	// Nombre
					imagettftext($image, $fontSize, 0, $text_xpos+50, 2000, $black, $font, '3081 0181 03 2563768528 (Caja Rural)');	// Nombre
					imagettftext($image, $fontSize-10, 0, $text_xpos, 2150, $black, $font, 'Deberá acompañarse la presente solicitud con el justificante del ingreso');	// Nombre

					
					
					//imagettftext($image, $fontSize, 0, $text_xpos, 140, $black, $font, 'ID: '.$assistant_info['user_id']);


					//$anchoo = imagesx($image);
					//$altoo = imagesy($image);
					//$proporcion = 1.4143;
					//$imagen_final = imagecreatetruecolor($anchoo, $altoo);
					//$image = imagerotate($image, 90, 0);
					//imagecopyresized ($imagen_final, $image, 0, 0, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);
					//imagecopyresized ($imagen_final, $image, 0, $altoo / 2, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);


					
					
					header("Content-type: image/jpeg");
					header("Content-type: " . $mime);
					//header("Content-Length: " . $size);
					// NOTE: Possible header injection via $basename
					//header("Content-Disposition: attachment; filename=cuota_" . $code_user .'.jpg');
					//header('Content-Transfer-Encoding: binary');
					//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					
					# Rota la imagen 90 grados
					//$image = imagerotate($image, 90, 0);
					$recibo_path  = $this->CI->config->item('root_path').'data/recibos/'.$pago->ticket_number.'.jpg';
					imagejpeg($image,$recibo_path, 100);
					// Liberar memoria
					imagedestroy($image);

					require($this->CI->config->item('root_path').'system/libraries/fpdf/fpdf.php');

					$pdf = new FPDF();
					$pdf->AddPage();
					$pdf->SetFont('Arial','B',16);
					//$pdf->Cell(40,10,'¡Hola, Mundo!');
					$pdf->Image($recibo_path, 0, 0, 210);
					$pdf->Output();
					exit();
					
					//imagedestroy($imagen_final);
					exit();					










				
					return TRUE;
				} else return FALSE;

		
		} else return FALSE; // no está marcado como dado de alta



return NULL;

	}





	/**
	 * Generar el array de datos a exportar
	 *
	 * @return boolean
	 * @author 
	 **/
	public function export_data($opciones = NULL)
	{
			$this->CI->load->model('lessons_model', 'lessons', TRUE);

		$datos = $this->CI->lessons->get_AssitanceData();
		//echo '<pre>'; print_r($datos);
		//exit();
		//return $datos;
		
		$resultado = array();
		foreach($datos as $usuario) {
			array_push($resultado, array(
				'id_booking' => 'l-'.$usuario['id_lesson'].'-'.$usuario['id_instructor'].'-'.$usuario['fecha_lesson'],
				'Fecha' => $usuario['fecha_lesson'],
				'HoraInicio' => $usuario['hora_inicio'],
				'HoraFin' => $usuario['hora_fin'],
				'NombrePista' => $usuario['court_desc'],
				'Deporte' => $usuario['sport_desc'],
				'user_id' => $usuario['id_instructor'],
				'Usuario' => $usuario['instructor_desc'],
				'Telefono' => $usuario['phone'],
				'id_CreadorReserva' => $usuario['id_instructor'],
				'CreadorReserva' => $usuario['instructor_desc'],
				'SinCoste' => 'Si',
				'DescripcionSinCoste' => 'Clase concertada',
				'Luz' => '0',
				'TipoReserva' => 'Clases',
				'FechaReserva' => $usuario['fecha_lesson']

				
			
			));
		}
			
			/*
			$nombres_semana = $this->CI->config->item('weekdays_names');
			//print('<pre>');print_r($records->result_array());exit();
			for($i = 0; $i < count($resultado); $i++) {
				$fecha_part = explode('-', $resultado[$i]['date']);
				$resultado[$i]['año'] = $fecha_part[2];
				$resultado[$i]['mes'] = $fecha_part[1];
				$resultado[$i]['dia'] = $fecha_part[0];
				$resultado[$i]['dia_semana'] = date('w', strtotime($resultado[$i]['date']));
				$resultado[$i]['dia_semana_nombre'] = $nombres_semana[$resultado[$i]['dia_semana']];
				
			}
			*/

			return $resultado;

	}



	
}