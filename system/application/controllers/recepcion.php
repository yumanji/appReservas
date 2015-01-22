<?php

class Recepcion extends Controller {

	function Recepcion()
	{
		parent::Controller();	
		$this->config->load('recepcion');
	}
	
	function index($fecha = NULL)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Lessons_model', 'lessons', TRUE);
		
		
		if(!isset($fecha)) $fecha=date($this->config->item('date_db_format'));
		else $fecha=date($this->config->item('date_db_format'), strtotime($fecha));
		
		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		# Vacio el array de elementos seleccionados
		$this->session->set_userdata('bookingInterval', array());		
		
		#Cada vez que voy a buscar, actualizo el id de transaccion
		$this->session->set_userdata('idTransaction', $this->app_common->getIdTransaction());

		if (isset($fecha) && $fecha!="") {
			# Si no seleccionan pista, saco toda la info de todas las pistas
			
			$pistas=$this->pistas->getAvailableCourts();
			//print_r($pistas);
			$availability_array=array();
			foreach($pistas as $elemento) {
				$pista_elegida=$elemento;	// Valor de ejemplo .. Deberá ser el seleccionado
				$this->pistas->id=$pista_elegida;			
				$nombre_pista=$this->pistas->getDescription();			
				$dia_elegido=$fecha;
				$this->reservas->date=$dia_elegido;			
				$this->reservas->court=$pista_elegida;			
				$this->reservas->id_user=$user_id;		
				$this->reservas->availability=NULL;	
				//echo 'Pista '.$this->pistas->id.'<br>';
				
				if($this->redux_auth->logged_in()) $this->reservas->clearByUser($user_id);	// Si hay alguien logueado, borro las reservas pendientes de confirmar al hacer una nueva búsqueda
				
				/*
				$this->reservas->getSpecialTimetableByCourt();
				if(!$this->reservas->availability) $this->reservas->getSpecialTimetable();
				if(!$this->reservas->availability) $this->reservas->availability=$this->pistas->getTimetable($dia_elegido);
				$this->reservas->availability=$this->lessons->updateTimetable($fecha, $pista_elegida, $this->reservas->availability);
				$this->reservas->getAvailabilityByCourt($fecha,$pista_elegida);
				*/
				$this->app_common->get_court_availability($pista_elegida, $dia_elegido);
				$availability_array[$nombre_pista]=$this->reservas->availability;
				//array_push(, $this->reservas->availability);
			}
		}

		$add_where = "booking.date = '".$fecha."' AND booking.intervalo>='".date('H:i').":00' AND booking.intervalo<'".(date('H')+6).":".date('i').":00'";
		$records = $this->reservas->get_global_list($add_where, 'intervalo', 'asc');
		 $buttons=''; $registros=array(); 
		 
		foreach ($records['records']->result() as $row)
		{
			//print 'Id es:'.$row->id;
			//print '<br>';
			if(!isset($registros[$row->id_transaction])) {
				
				$registros[$row->id_transaction]=$this->reservas->getBookingInfoById($row->id_transaction);
				
			}
			
		}
				//print("<pre>");print_r($availability_array);
		
				//print("<pre>");print_r($registros);
				
		$filtro_fecha_arr=$this->config->item('max_search_days');
		if(isset($filtro_fecha_arr[$user_group]) && $filtro_fecha_arr[$user_group]!="") $filtro_fecha = $filtro_fecha_arr[$user_group];
				
		$data=array(
			'meta' => $this->load->view('recepcion/meta', '', true),
			'footer' => $this->load->view('recepcion/footer', '', true),				
			'header' => $this->load->view('recepcion/header', array('refresh' => true, 'selected_date' => date($this->config->item('reserve_date_filter_format'), strtotime($fecha)), 'filtro_fecha' => $filtro_fecha), true),
			'live' => '',
			//'sidebar' => $this->load->view('recepcion/sidebar', array('registros' => $registros), true),
			'grid' => $this->load->view('recepcion/grid', array('availability' => $availability_array, 'user_id' => $user_id, 'date' => date($this->config->item('reserve_date_filter_format'), strtotime($fecha)), 'filtro_fecha' => $filtro_fecha), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		
		$sidebar_activo=$this->config->item('recepcion_show_near_bookings');
		if($sidebar_activo) $data['sidebar'] = $this->load->view('recepcion/sidebar', array('registros' => $registros), true);
		
		
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
    $this->load->view('recepcion', $data);
		
								
      //$this->load->view('main', $data);
      
	}


	function selection($id_transaction, $fecha = NULL)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		
		if(!isset($fecha)) $fecha=date($this->config->item('date_db_format'));
		else $fecha=date($this->config->item('date_db_format'), strtotime($fecha));
		//echo $fecha;
		
		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		# Vacio el array de elementos seleccionados
		$this->session->set_userdata('bookingInterval', array());		
		

		if (isset($fecha) && $fecha!="") {
			# Si no seleccionan pista, saco toda la info de todas las pistas
			
			$pistas=$this->pistas->getAvailableCourts();
			//print_r($pistas);
			$availability_array=array();
			foreach($pistas as $elemento) {
				$pista_elegida=$elemento;	// Valor de ejemplo .. Deberá ser el seleccionado
				$this->pistas->id=$pista_elegida;			
				$nombre_pista=$this->pistas->getDescription();			
				$dia_elegido=$fecha;
				//$this->reservas->date=$dia_elegido;			
				//$this->reservas->court=$pista_elegida;			
				$this->reservas->id_user=$user_id;		
				$this->reservas->availability=NULL;	
				//echo 'Pista '.$this->pistas->id.'<br>';
				
				if($this->redux_auth->logged_in()) $this->reservas->clearByUser($user_id);	// Si hay alguien logueado, borro las reservas pendientes de confirmar al hacer una nueva búsqueda
				
				/*
				$this->reservas->getSpecialTimetableByCourt();
				if(!$this->reservas->availability) $this->reservas->getSpecialTimetable();
				if(!$this->reservas->availability) $this->reservas->availability=$this->pistas->getTimetable($dia_elegido);
				$this->reservas->getAvailabilityByCourt($fecha,$pista_elegida);
				*/
				$this->app_common->get_court_availability($pista_elegida, $dia_elegido, $id_transaction);
				$availability_array[$nombre_pista]=$this->reservas->availability;
				//array_push(, $this->reservas->availability);
			}
		}

		

		$data=array(
			'meta' => $this->load->view('recepcion/meta', '', true),
			'footer' => $this->load->view('recepcion/footer', '', true),				
			//'header' => $this->load->view('recepcion/header', array('fecha' => $fecha, 'title' => 'Seleccione nueva ubicaci&oacute;n de reserva'), true),
			'live' => '',
			'grid' => $this->load->view('recepcion/grid_selector', array('availability' => $availability_array, 'id_transaction' => $id_transaction, 'user_id' => $user_id, 'selected_date' => date($this->config->item('reserve_date_filter_format'), strtotime($fecha)), 'date' => date($this->config->item('reserve_date_filter_format'), strtotime($fecha))), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
    $this->load->view('recepcion', $data);
		
								
      //$this->load->view('main', $data);
      
	}



	function grid($fecha = NULL)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		
		if(!isset($fecha)) $fecha=date($this->config->item('date_db_format'));
		else $fecha=date($this->config->item('date_db_format'), strtotime($fecha));

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		if (isset($fecha) && $fecha!="") {
			# Si no seleccionan pista, saco toda la info de todas las pistas
			
			$pistas=$this->pistas->getAvailableCourts();
			//print_r($pistas);
			$availability_array=array();
			foreach($pistas as $elemento) {
				$pista_elegida=$elemento;	// Valor de ejemplo .. Deberá ser el seleccionado
				$this->pistas->id=$pista_elegida;			
				$nombre_pista=$this->pistas->getDescription();			
				$dia_elegido=$fecha;
				//$this->reservas->date=$dia_elegido;			
				//$this->reservas->court=$pista_elegida;			
				$this->reservas->id_user=$user_id;		
				$this->reservas->availability=NULL;	
				//echo 'Pista '.$this->pistas->id.'<br>';
				
				if($this->redux_auth->logged_in()) $this->reservas->clearByUser($user_id);	// Si hay alguien logueado, borro las reservas pendientes de confirmar al hacer una nueva búsqueda
				
				/*
				$this->reservas->getSpecialTimetableByCourt();
				if(!$this->reservas->availability) $this->reservas->getSpecialTimetable();
				if(!$this->reservas->availability) $this->reservas->availability=$this->pistas->getTimetable($dia_elegido);
				$this->reservas->getAvailabilityByCourt($fecha,$pista_elegida);
				*/
				$this->app_common->get_court_availability($pista_elegida, $dia_elegido);
				$availability_array[$nombre_pista]=$this->reservas->availability;
				//array_push(, $this->reservas->availability);
			}
		}

		
		$this->load->view('recepcion/grid', array('availability' => $availability_array, 'user_id' => $user_id, 'date' => date($this->config->item('reserve_date_filter_format'), strtotime($fecha))));
		
								
      //$this->load->view('main', $data);
      
	}





}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */