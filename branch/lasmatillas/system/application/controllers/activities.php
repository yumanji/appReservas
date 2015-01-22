<?php

class Activities extends Controller {

	function Activities()
	{
		parent::Controller();	
	}
	

	function index()
	{
		$this->load->model('Redux_auth_model', 'usuario', TRUE);


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		


		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);
		
		$calendario = $this->load->view('activities/calendar', array(), true);
		
		//$extra_meta = link_tag(base_url().'css/dailog.css').link_tag(base_url().'css/calendar.css').link_tag(base_url().'css/dp.css').link_tag(base_url().'css/alert.css').link_tag(base_url().'css/main.css').'<script src="'.base_url().'js/calendar/Common.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/datepicker_lang_US.js" type="text/javascript"></script>    <script src="'.base_url().'js/calendar/jquery.datepicker.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.alert.js" type="text/javascript"></script>  <script src="'.base_url().'js/calendar/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script> <script src="'.base_url().'js/calendar/wdCalendar_lang_US.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.calendar.js" type="text/javascript"></script>';
		$data=array(
			'meta' => $this->load->view('meta', array('lib_calendar' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $calendario,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);
    //print("<pre>");print_r($this->session);
	}



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function datafeed($method, $id = NULL)
	{
		$this->load->library('calendario');


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		switch ($method) {
		    case "add":
		        $ret = $this->calendario->addCalendar($this->input->post("CalendarStartTime"), $this->input->post("CalendarEndTime"), $this->input->post("CalendarTitle"), $this->input->post("IsAllDayEvent"));
		        break;
		    case "list":
		        $ret = $this->calendario->listCalendar($this->input->post('showdate'), $this->input->post('viewtype'));
		        //$ret = $this->calendario->listCalendar('11/3/2010', 'week');
		        break;
		    case "update":
		        $ret = $this->calendario->updateCalendar($this->input->post("calendarId"), $this->input->post("CalendarStartTime"), $this->input->post("CalendarEndTime"));
		        break; 
		    case "remove":
		        $ret = $this->calendario->removeCalendar( $this->input->post("calendarId"));
		        break;
		    case "adddetails":
		        $st = $this->input->post("stpartdate") . " " . $this->input->post("stparttime");
		        $et = $this->input->post("etpartdate") . " " . $this->input->post("etparttime");
		        if(isset($id)){
		        	$all_day = $this->input->post("IsAllDayEvent");
		            $ret = $this->calendario->updateDetailedCalendar($id, $st, $et, 
		                $this->input->post("Subject"), isset($all_day)?1:0, $this->input->post("Description"), 
		                $this->input->post("Location"), $this->input->post("colorvalue"), $this->input->post("timezone"));
		        }else{
		        	$all_day = $this->input->post("IsAllDayEvent");
		            $ret = $this->calendario->addDetailedCalendar($st, $et,                    
		                $this->input->post("Subject"), isset($all_day)?1:0, $this->input->post("Description"), 
		                $this->input->post("Location"), $this->input->post("colorvalue"), $this->input->post("timezone"));
		        }        
		        break; 
		
		
		}
		$this->output->set_header($this->config->item('json_header'));
		echo json_encode($ret); 
		}




}

/* End of file activities.php */
/* Location: ./system/application/controllers/activities.php */