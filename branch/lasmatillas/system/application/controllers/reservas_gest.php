<?php

class Reservas_gest extends Controller {

	function Reservas_gest()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
	}
	
	function index()
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);
	}




# -------------------------------------------------------------------
#  Listado general de las reservas usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all($param = NULL)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$panel_permission[$user_group]) {
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'date', width:12, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'user_desc', index:'user_desc', width:30, align:'center'},
						   		{name:'user_phone', index:'user_phone', width:12, align:'center'},
						   		{name:'status_desc', index:'status_desc', width:15, align:'center'},
						   		{name:'paymentway_desc', index:'paymentway_desc', width:15, align:'center'},
						   		{name:'price', index:'price', width:10, align:'center', sortable:false},
						   		{name:'light_desc', index:'price_light', width:10, align:'center'},
						   		{name:'no_cost',index:'no_cost', width:10, align:'center', sortable:false}";
		$colnames = "'Id','Fecha','Inicio','Final','Pista','Usuario', 'Telefono', 'Estado', 'Forma de pago', 'Precio', 'Luz', 'Sin coste'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "reservas_gest/jqgrid_list_all", 
				'title' => 'Listado de reservas', 
				'default_orderfield' => 'date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '820',
				'row_list_options' => '10,20,50',
		);
		
		# Si envío un parámetro adicional para filtrar, lo traspaso al que carga los datos
		if(isset($param)) $para_grid['data_url'] = "reservas_gest/jqgrid_list_all/".$param;
		if(isset($param)) {
		
			switch($param) {
				case 'no_cost':
					$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
									   		{name:'fecha',index:'fecha', width:12, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
									   		{name:'inicio', index:'inicio', width:10, align:'center', editable:true},
									   		{name:'final', index:'intervalo', width:10, align:'center', editable:true},
									   		{name:'court_name',index:'court_name', width:20, align:'center'},
									   		{name:'user_desc', index:'user_desc', width:30, align:'center'},
									   		{name:'user_phone', index:'user_phone', width:12, align:'center'},
									   		{name:'price', index:'price', width:10, align:'center', sortable:false},
									   		{name:'no_cost_desc', index:'no_cost_desc', width:50, align:'center'}";
					$colnames = "'Id','Fecha','Inicio','Final','Pista','Usuario', 'Telefono',  'Precio', 'Motivo'";
					$para_grid['title'] = "Listado de reservas sin coste";
					$para_grid['colmodel'] = $colmodel;
					$para_grid['colnames'] = $colnames;
				break;
					
				case 'canceled':
					$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
									   		{name:'fecha',index:'fecha', width:15, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
									   		{name:'inicio', index:'inicio', width:8, align:'center', editable:true},
									   		{name:'final', index:'intervalo', width:8, align:'center', editable:true},
									   		{name:'court_name',index:'court_name', width:15, align:'center'},
									   		{name:'user_desc', index:'user_desc', width:20, align:'center'},
									   		{name:'user_phone', index:'user_phone', width:15, align:'center'},
									   		{name:'price', index:'price', width:8, align:'center', sortable:false},
									   		{name:'cancelation_reason', index:'cancelation_reason', width:25, align:'center'},
									   		{name:'user_delete', index:'user_delete', width:25, align:'center'},
									   		{name:'time_delete', index:'time_delete', width:15, align:'center'}";
					$colnames = "'Id','Fecha','Inicio','Final','Pista','Usuario', 'Telefono',  'Precio', 'Motivo', 'Usuario Cancelacion', 'Fecha Canc.'";
					$para_grid['data_url'] = "reservas_gest/jqgrid_list_all_cancelled";
					$para_grid['title'] = "Listado de reservas canceladas";
					$para_grid['colmodel'] = $colmodel;
					$para_grid['colnames'] = $colnames;				
				break;
					
				case 'unpaid':
					$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'date', width:12, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'user_desc', index:'user_desc', width:30, align:'center'},
						   		{name:'user_phone', index:'user_phone', width:12, align:'center'},
						   		{name:'status_desc', index:'status_desc', width:15, align:'center'},
						   		{name:'price', index:'price', width:10, align:'center', sortable:false},
						   		{name:'light_desc', index:'price_light', width:10, align:'center', sortable:false},
						   		{name:'no_cost',index:'no_cost', width:10, align:'center', sortable:false}";
					$colnames = "'Id','Fecha','Inicio','Final','Pista','Usuario', 'Telefono', 'Estado', 'Precio', 'Luz', 'Sin coste'";
					$para_grid['title'] = "Listado de reservas no pagadas aun";
					$para_grid['colmodel'] = $colmodel;
					$para_grid['colnames'] = $colnames;				
				break;
			}
		}
	


		$grid_code = '<div style="position:relative; width: 820px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = $this->load->view('menu_lateral_gestion', '', true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('reservas_gest/list_all', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => $menu_lateral), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_flashdata('returnOkUrl', site_url('reservas_gest/list_all'));
			$this->session->set_flashdata('returnKoUrl', site_url('reservas_gest/list_all'));
			$this->session->set_userdata('returnOkUrl', site_url('reservas_gest/list_all'));
			$this->session->set_userdata('returnKoUrl', site_url('reservas_gest/list_all'));
			$this->session->set_userdata('returnUrl', site_url('reservas_gest/list_all'));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		$this->load->view('main', $data);
}







# -------------------------------------------------------------------
#  Listado general de las reservas usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all_canceled_antigua()
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$panel_permission[$user_group]) {
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'fecha', width:12, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'inicio', width:10, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'user_desc', index:'user_desc', width:30, align:'center'},
						   		{name:'user_phone', index:'user_phone', width:12, align:'center'},
						   		{name:'price', index:'price', width:10, align:'center'},
						   		{name:'cancelation_reason', index:'cancelation_reason', width:50, align:'center'}";
		$colnames = "'Id','Fecha','Inicio','Final','Pista','Usuario', 'Telefono',  'Precio', 'Motivo'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "reservas_gest/jqgrid_list_all_cancelled", 
				'title' => 'Listado de reservas canceladas', 
				'default_orderfield' => 'date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '820',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 820px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = $this->load->view('menu_lateral_gestion', '', true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('reservas_gest/list_all', array('grid_code' => $grid_code, 'enable_buttons' => FALSE, 'menu_lateral' => $menu_lateral), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_flashdata('returnOkUrl', site_url('reservas_gest/list_all_canceled'));
			$this->session->set_flashdata('returnKoUrl', site_url('reservas_gest/list_all_canceled'));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}



# -------------------------------------------------------------------
#  devuelve el listado de reservas para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all ($add_params = NULL)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			//if($add_params['exclude_user_restriction']) echo 'VERDAD'; else echo 'FALSE';
			if($user_group > '3' && (!isset($add_params['exclude_user_restriction']) || !$add_params['exclude_user_restriction']) ) $where = "booking.id_user = '".$profile->id."'";
		}	else {
			exit(0);
		}

		//$req_param = array ();
		$req_param = array (

				"orderby" => $this->input->post( "sidx", TRUE ),
				"orderbyway" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				/*
				"sort_by" => $this->input->post( "sidx", TRUE ),
				"sort_direction" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				"search_field_1" => "msg_to",
				"search_field_2" => "msg_in_inbox",
				"user_id" => $this->session->userdata('user_id')
				*/
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
				case "today":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(booking.date = '".date($this->config->item('date_db_format'))."')";
				break;
				case "month":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(MONTH(booking.date) = MONTH('".date($this->config->item('date_db_format'))."'))";
				break;
				case "week":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(WEEK(booking.date,3) = WEEK('".date($this->config->item('date_db_format'))."',3))";
				break;
				case "last_week":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(WEEK(booking.date,3) = (WEEK('".date($this->config->item('date_db_format'))."',3)-1))";
				break;
				case "last_month":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(MONTH(booking.date) = MONTH('".date($this->config->item('date_db_format'))."')-1)";
				break;
				case "unpaid":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(booking.status < 9)";
				break;
				case "no_cost":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(booking.no_cost = 1)";
				break;
			}
		}
		
		$req_param['where'] = $where;
		if(isset($add_params) && is_array($add_params) && isset($add_params['where']) && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		
		$data->page = $this->input->post( "page", TRUE );


		//print("<pre>");print_r($record_items);exit();
		$data->records = $this->reservas->get_data_count($req_param,"all");
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->reservas->get_data ($req_param, 'none');
		$data->rows = $records;
		//echo "<pre>"; print_r($data->rows);
		
		echo @json_encode ($data );
		exit( 0 );
	}
	
	


# -------------------------------------------------------------------
#  devuelve el listado de reservas por usuario para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	
public function jqgrid_list_by_user($user) {

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$profiles_visualization[$user_group] && !$panel_permission[$user_group] && $user != $profile->id) {
				redirect(site_url(), 'Location'); 
				exit();				
			}
			//echo $profile->id.'<br>'.$user.'<br>'; echo $panel_permission[$user_group];
			//print_r($panel_permission);//exit();

		}	else {
			exit(0);
		}
		
		$parametros = array('where' => "booking.id_user = '".$user."'");
		$this->jqgrid_list_all($parametros);
}





# -------------------------------------------------------------------
#  devuelve el listado de reservas por usuario para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	
public function listadopendientes() {


		$this->load->model('Reservas_model', 'reservas', TRUE);

		$where = '';



		//$req_param = array ();
		$req_param = array (

				"orderby" => $this->input->post( "sidx", TRUE ),
				"orderbyway" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				/*
				"sort_by" => $this->input->post( "sidx", TRUE ),
				"sort_direction" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				"search_field_1" => "msg_to",
				"search_field_2" => "msg_in_inbox",
				"user_id" => $this->session->userdata('user_id')
				*/
		);
		
		$req_param['where'] = '(booking.status = 7)';

		$data->page = $this->input->post( "page", TRUE );


		//print("<pre>");print_r($record_items);exit();
		$records = $this->reservas->get_data ($req_param, 'none');
		foreach($records as $reserva) {
			echo '<br> reserva '.$reserva['id_transaction'].' <a href="http://riogrande.reservadeportiva.com/index.php/reservas/pagoautomatico/'.$reserva['id_transaction'].'">Pagar</a>';
			
			}
		echo "<pre>"; print_r($records);
		exit();
		echo @json_encode ($data );
		exit( 0 );




}




# -------------------------------------------------------------------
#  devuelve el listado de reservas en los que el usuario aparece como jugador, por usuario para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	
public function jqgrid_list_by_user_shared($user) {

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$profiles_visualization[$user_group] && !$panel_permission[$user_group] && $user != $profile->id) {
				redirect(site_url(), 'Location'); 
				exit();				
			}
			//echo $profile->id.'<br>'.$user.'<br>'; echo $panel_permission[$user_group];
			//print_r($panel_permission);//exit();

		}	else {
			exit(0);
		}
		
		$parametros = array('where' => "booking.id_transaction IN (SELECT id_transaction FROM booking_players WHERE id_user = '".$user."')");
		$parametros['exclude_user_restriction'] = TRUE;
		$this->jqgrid_list_all($parametros);
}



# -------------------------------------------------------------------
#  devuelve el listado de reservas canceladas para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all_cancelled ()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			if($user_group > '3') $where = "booking.id_user = '".$profile->id."'";
		}	else {
			exit(0);
		}

		//$req_param = array ();
		$req_param = array (

				"orderby" => $this->input->post( "sidx", TRUE ),
				"orderbyway" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				/*
				"sort_by" => $this->input->post( "sidx", TRUE ),
				"sort_direction" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				"search_field_1" => "msg_to",
				"search_field_2" => "msg_in_inbox",
				"user_id" => $this->session->userdata('user_id')
				*/
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
		
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );


		//print("<pre>");print_r($record_items);exit();
		$data->records = $this->reservas->get_cancelled_count($req_param,"all");
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->reservas->get_cancelled ($req_param, 'none');
		$data->rows = $records;
		//echo "<pre>"; print_r($data->rows);
		
		echo json_encode ($data );
		exit( 0 );
	}

# -------------------------------------------------------------------
#  Listado antiguo de las reservas usando el Flexigrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all2()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_level=$profile->group;
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
				
		# Grid de datos
		$colModel['id_booking'] = array('ID',70,FALSE,'center',1, TRUE);
		$colModel['date'] = array('Fecha',50,FALSE,'center',2);
		$colModel['intervalo'] = array('Inicio',30,FALSE,'center',1, FALSE);
		$colModel['intervalo2'] = array('Fin',25,FALSE,'center',1, FALSE);
		$colModel['courts.name'] = array('Pista',60,FALSE,'center',0, FALSE);
		$colModel['price_light'] = array('Luz',24,FALSE,'center',0, FALSE);
		$colModel['zz_booking_status.description'] = array('Estado',30,FALSE,'center',0);
		$colModel['zz_paymentway.description'] = array('Forma Pago',60,FALSE,'center',0);
		$colModel['id_user'] = array('User ID',20, FALSE,'center',0, TRUE);
		$colModel['user_desc'] = array('Usuario',110, FALSE, 'center',0);
		$colModel['price'] = array('Precio',23,FALSE,'right',1);
		$colModel['no_cost'] = array('Sin Coste',40, FALSE, 'center',0);
		//$colModel['action_validate'] = array('Validar',35, FALSE, 'center',0, FALSE , 'validarReserva');
		$colModel['action_cancel'] = array('Cancelar',40, FALSE, 'center',0, FALSE , 'cancelarReserva');
		$colModel['action_change'] = array('Mod. Hora',45, FALSE, 'center',0);
		$colModel['action_payment'] = array('Cobrar',35, FALSE, 'center',0, FALSE , 'cobrarReserva');
		$colModel['action_light'] = array('Set Luz',35, FALSE, 'center',0, FALSE , 'setLight');
		
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 500,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Listado de reservas',
		'showTableToggleBtn' => false
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		
		//$buttons[] = array('Delete','delete','buttons');
		//$buttons[] = array('separator');
		$buttons[] = array($this->lang->line('new_reserve'),'add','buttons');
		$buttons[] = array('separator');
//		$buttons[] = array($this->lang->line('deselect_all'),'delete','test');
//		$buttons[] = array('separator');
//		$buttons[] = array('Anular','delete','validarReserva');
		
		
		# Filtros a pasar al grid
		$where_arr=array();
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_status=$this->input->post('status');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('user');
			$selected_no_cost=$this->input->post('no_cost');
			if($this->input->post('date1')!="") $selected_date1=date($this->config->item('date_db_format'), strtotime($this->input->post('date1')));
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('date_db_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			if($this->input->post('date2')!="") $selected_date2=date($this->config->item('date_db_format'), strtotime($this->input->post('date2')));
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('date_db_format'));

		if($selected_sport!="") array_push($where_arr, "courts.sport_type = '".$selected_sport."'");
		if($selected_court_type!="") array_push($where_arr, "courts.court_type = '".$selected_court_type."'");
		if($selected_court!="") array_push($where_arr, "id_court = '".$selected_court."'");
		if($selected_status!="") array_push($where_arr, "status = '".$selected_status."'");
		if($selected_paymentway!="") array_push($where_arr, "id_paymentway = '".$selected_paymentway."'");
		if($selected_user!="") array_push($where_arr, "id_user = '".$selected_user."'");
		if($selected_no_cost!="") array_push($where_arr, "no_cost = '".$selected_no_cost."'");
		if($selected_date1!="") array_push($where_arr, "date >= '".$selected_date1."'");
		if($selected_date2!="") array_push($where_arr, "date <= '".$selected_date2."'");
		
		$where=implode(' AND ', $where_arr);
		//echo $where;
		
		# Con esto guardo las condiciones extra en session.
		$this->session->set_flashdata('where', $where);

		#Guardo en sesion la URL de la página para volver a ella al terminar acciones como pagar, anular, etc..
		$this->session->set_userdata('return_url', $this->uri->uri_string());

		//print_r($buttons);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		
		$grid_js = build_grid_js('flex1',site_url("/reservas_gest/reserve_list_all"),$colModel,'date','desc',$gridParams,$buttons);
		
		
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
			'form' => 'frmGrid', 
			'enable_grid' => 1,
			'js_grid' => $grid_js,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		if($this->redux_auth->logged_in()) $data['page']='reservas_gest/list_all';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}


	
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas
# -------------------------------------------------------------------
	function reserve_list_all()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);

		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('id_booking','date','intervalo','courts.name','zz_paymentway.description','zz_booking_status.description','price','id_user','user_desc','no_cost');
		
		$this->flexigrid->validate_post('date','desc',$valid_fields);
		
		$add_where=$this->session->flashdata('where');

		$records = $this->reservas->get_global_list($add_where, $this->flexigrid->post_info['sortname'], $this->flexigrid->post_info['sortorder']);
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		 $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
		 //$record_items[] = null;
		 $record_items = array();
		foreach ($records['records']->result() as $row)
		{
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
			else $no_cost=img( array('src'=>'images/accept.png', "align"=>"absmiddle", "border"=>"0"));
			
			if($row->id_user) $usuario = $row->first_name.' '.$row->last_name.'('.$row->phone.')';
			else $usuario = $row->user_desc.'('.$row->user_phone.')';
			if(trim($usuario)=="") $usuario="No registrado";
			
			$time=$row->intervalo;
			$precio+=$row->price;
			$reserve_interval = $this->pistas->getCourtInterval($row->id_court);
			
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
		}
		$record_items[] = $registro;
		//log_message('debug', $record_items[0][0]);
		//Print please
		//print("<pre>");print_r($record_items);print("</pre>");
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	
	


# -------------------------------------------------------------------
#  Listado de las reservas de hoy
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function today()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		# Grid de datos
		$colModel['id_booking'] = array('ID',70,FALSE,'center',1, TRUE);
		$colModel['date'] = array('Fecha',50,FALSE,'center',2);
		$colModel['intervalo'] = array('Inicio',30,FALSE,'center',1, FALSE);
		$colModel['intervalo2'] = array('Fin',25,FALSE,'center',1, FALSE);
		$colModel['courts.name'] = array('Pista',60,FALSE,'center',0, FALSE);
		$colModel['price_light'] = array('Luz',24,FALSE,'center',0, FALSE);
		$colModel['zz_booking_status.description'] = array('Estado',30,FALSE,'center',0);
		$colModel['zz_paymentway.description'] = array('Forma Pago',60,FALSE,'center',0);
		$colModel['id_user'] = array('User ID',20, FALSE,'center',0, TRUE);
		$colModel['user_desc'] = array('Usuario',110, FALSE, 'center',0);
		$colModel['price'] = array('Precio',23,FALSE,'right',1);
		$colModel['no_cost'] = array('Sin Coste',40, FALSE, 'center',0);
		//$colModel['action_validate'] = array('Validar',35, FALSE, 'center',0, FALSE , 'validarReserva');
		$colModel['action_cancel'] = array('Cancelar',40, FALSE, 'center',0, FALSE , 'cancelarReserva');
		$colModel['action_change'] = array('Mod. Hora',45, FALSE, 'center',0);
		$colModel['action_payment'] = array('Cobrar',35, FALSE, 'center',0, FALSE , 'cobrarReserva');
		$colModel['action_light'] = array('Set Luz',35, FALSE, 'center',0, FALSE , 'setLight');
		
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 500,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Listado de reservas',
		'showTableToggleBtn' => false
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		
		//$buttons[] = array('Delete','delete','buttons');
		//$buttons[] = array('separator');
		$buttons[] = array($this->lang->line('new_reserve'),'add','buttons');
		$buttons[] = array('separator');
//		$buttons[] = array($this->lang->line('deselect_all'),'delete','test');
//		$buttons[] = array('separator');
//		$buttons[] = array('Anular','delete','validarReserva');
		
		
		# Filtros a pasar al grid
		$where_arr=array();
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_status=$this->input->post('status');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('user');
			$selected_no_cost=$this->input->post('no_cost');

		if($selected_sport!="") array_push($where_arr, "courts.sport_type = '".$selected_sport."'");
		if($selected_court_type!="") array_push($where_arr, "courts.court_type = '".$selected_court_type."'");
		if($selected_court!="") array_push($where_arr, "id_court = '".$selected_court."'");
		if($selected_status!="") array_push($where_arr, "status = '".$selected_status."'");
		if($selected_paymentway!="") array_push($where_arr, "id_paymentway = '".$selected_paymentway."'");
		if($selected_user!="") array_push($where_arr, "id_user = '".$selected_user."'");
		if($selected_no_cost!="") array_push($where_arr, "no_cost = '".$selected_no_cost."'");
		array_push($where_arr, "date = '".date($this->config->item('date_db_format'))."'");
		
		$where=implode(' AND ', $where_arr);
		//echo $where;
		
		# Con esto guardo las condiciones extra en session.
		$this->session->set_flashdata('where', $where);
		
		#Guardo en sesion la URL de la página para volver a ella al terminar acciones como pagar, anular, etc..
		$this->session->set_userdata('return_url', $this->uri->uri_string());


		//print_r($buttons);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		
		$grid_js = build_grid_js('flex1',site_url("/reservas_gest/reserve_list_all"),$colModel,'id_booking','asc',$gridParams,$buttons);
		
		
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields(array('sports'=>'1', 'court_type'=>'1', 'court'=>'1', 'status'=>'1', 'paymentway'=>'1', 'date'=>'0'))), true),
			'form' => 'frmGrid', 
			'enable_grid' => 1,
			'js_grid' => $grid_js,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		if($this->redux_auth->logged_in()) $data['page']='reservas_gest/list_all';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);

	
	}


	


# -------------------------------------------------------------------
#  Listado de las reservas de un usuario concreto
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all_by_user($id_user)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		//$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		# Control de acceso a usuarios. Solo pueden ver los datos de un usuario, el propio usuario o los gestores y admins
		if($id_user != $profile->id && $profile->group > 3 ) {
			echo "Acceso no permitido";
			exit();
		}
				
		# Grid de datos
		$colModel = array();
		$colModel['id_booking'] = array('ID',70,FALSE,'center',1, TRUE);
		$colModel['date'] = array('Fecha',60,FALSE,'center',2);
		$colModel['intervalo'] = array('Inicio',30,FALSE,'center',1, FALSE);
		$colModel['intervalo2'] = array('Fin',25,FALSE,'center',1, FALSE);
		$colModel['courts.name'] = array('Pista',70,FALSE,'center',0, FALSE);
		$colModel['price_light'] = array('Luz',24,FALSE,'center',0, FALSE);
		$colModel['zz_booking_status.description'] = array('Estado',30,FALSE,'center',0);
		$colModel['zz_paymentway.description'] = array('Forma Pago',60,FALSE,'center',0);
		$colModel['id_user'] = array('User ID',20, FALSE,'center',0, TRUE);
		$colModel['user_desc'] = array('Usuario',110, FALSE, 'center',0, TRUE);
		$colModel['price'] = array('Precio',30,FALSE,'right',1);
		$colModel['no_cost'] = array('Sin Coste',40, FALSE, 'center',0);
		//$colModel['action_validate'] = array('Validar',35, FALSE, 'center',0, FALSE , 'validarReserva');
		$colModel['action_cancel'] = array('Cancelar',40, FALSE, 'center',0, FALSE , 'cancelarReserva');
		$colModel['action_change'] = array('Mod. Hora',45, FALSE, 'center',0);
		$colModel['action_payment'] = array('Cobrar',35, FALSE, 'center',0, FALSE , 'cobrarReserva');
		$colModel['action_light'] = array('Set Luz',35, FALSE, 'center',0, FALSE , 'setLight');
		
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 500,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Listado de reservas de usuario',
		'showTableToggleBtn' => false
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		
		$buttons = array();
		//$buttons[] = array('Delete','delete','buttons');
		//$buttons[] = array('separator');
		//$buttons[] = array($this->lang->line('new_reserve'),'add','buttons');
		$buttons[] = array('separator');
//		$buttons[] = array($this->lang->line('deselect_all'),'delete','test');
//		$buttons[] = array('separator');
//		$buttons[] = array('Anular','delete','validarReserva');
		
		
		# Filtros a pasar al grid
		$where_arr=array();
		
		/*
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_status=$this->input->post('status');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('user');
			$selected_no_cost=$this->input->post('no_cost');

		if($selected_sport!="") array_push($where_arr, "courts.sport_type = '".$selected_sport."'");
		if($selected_court_type!="") array_push($where_arr, "courts.court_type = '".$selected_court_type."'");
		if($selected_court!="") array_push($where_arr, "id_court = '".$selected_court."'");
		if($selected_status!="") array_push($where_arr, "status = '".$selected_status."'");
		if($selected_paymentway!="") array_push($where_arr, "id_paymentway = '".$selected_paymentway."'");
		if($selected_user!="") array_push($where_arr, "id_user = '".$selected_user."'");
		if($selected_no_cost!="") array_push($where_arr, "no_cost = '".$selected_no_cost."'");
		*/
		array_push($where_arr, "id_user = '".$id_user."'");
		
		$where=implode(' AND ', $where_arr);
		//echo $where;
		
		# Con esto guardo las condiciones extra en session.
		$this->session->set_flashdata('where', $where);
		
		#Guardo en sesion la URL de la página para volver a ella al terminar acciones como pagar, anular, etc..
		$this->session->set_userdata('return_url', $this->uri->uri_string());


		//print_r($buttons);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		
		$grid_js = build_grid_js('flex1',site_url("/reservas_gest/reserve_list_all"),$colModel,'id_booking','desc',$gridParams,$buttons);
		
		
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>TRUE), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			//'menu' => $this->load->view('menu', $menu, true),
			//'navigation' => $this->load->view('navigation', '', true),
			//'footer' => $this->load->view('footer', '', true),				
			//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields(array('sports'=>'1', 'court_type'=>'1', 'court'=>'1', 'status'=>'1', 'paymentway'=>'1', 'date'=>'0'))), true),
			'form' => 'frmGrid', 
			'enable_grid' => 1,
			'js_grid' => $grid_js,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/list_all';		
		
		# Carga de la vista principal
		$this->load->view('reservas_gest/reservas_gest_by_user', $data);

	
	}




# -------------------------------------------------------------------
#  Estado general de ocupación de las pistas para hoy u otras fechas
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function status()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', '', true),
						'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => htmlentities('Estado general de ocupación de las pistas para hoy u otras fechas'),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}




# -------------------------------------------------------------------
#  Listado de reservas canceladas
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function canceled()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', '', true),
						'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => htmlentities('Listado de reservas canceladas'),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}




# -------------------------------------------------------------------
#  Listado de reservas para un usuario concreto
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_owner()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', '', true),
						'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => htmlentities('Listado de reservas para un usuario concreto'),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}




# -------------------------------------------------------------------
#  Nueva reserva telefónica
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function new_phone()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', '', true),
						'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => htmlentities('Nueva reserva telefónica'),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}




# -------------------------------------------------------------------
#  Nueva reserva presencial (es decir, susceptible de ser cobrada)
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function new_present()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);



		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', '', true),
						'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => htmlentities('Nueva reserva presencial (es decir, susceptible de ser cobrada)'),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}











function simpleSearchFields($options=array())
	{
			$this->load->model('Reservas_model', 'reservas', TRUE);
			$this->load->model('Pistas_model', 'pistas', TRUE);

			if($this->redux_auth->logged_in()) {
				$profile=$this->redux_auth->profile();
				$user_group=$profile->group;
			}	else {
				redirect(site_url(), 'Location'); 
				exit();
			}

			#########################
			## CREACION DE FILTROS 
			######

			//$page_id=$this->app_common->get_page_id();
			//echo $page_id;
			
			/*
			$filtros=$this->session->userdata('filters');
			if(!isset($filtros)) $this->session->set_userdata('filters', array());	
			if(!isset($filtros[$page_id])) $filtros[$page_id]= array();
			*/
			$filter_array=array();
			
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_status=$this->input->post('status');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('user');
			$selected_no_cost=$this->input->post('no_cost');
			$selected_date1=$this->input->post('date1');
			
			//if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'));
			//echo date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')))). " -1 month";
			//echo date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));
			
			
			# Filtro de DEPORTE
			if(!isset($options['sports']) || $options['sports']=="1") {
				$optionss=$this->reservas->getSportsArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'sports', 'desc' => $this->lang->line('sport'), 'default' => $selected_sport, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'sports', 'type' => 'select', 'value' => $optionss);
					array_push($filter_array, $equipo);
				}
			}
			
			


			# Filtro de TIPO DE PISTA
			
			if(!isset($options['court_type']) || $options['court_type']=="1") {
				$optionss=$this->pistas->getAvailableCourtsTypesArray($selected_sport);
				if(isset($optionss) && count($optionss)!=1) {
					$tipopista=array('name' => 'court_type', 'desc' => $this->lang->line('court_type'), 'default' => $selected_court_type, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'court_type',  'type' => 'select', 'value' => array('' => $optionss));
					array_push($filter_array, $tipopista);
				}
			}
			
			


			# Filtro de PISTAS
			if(!isset($options['court']) || $options['court']=="1") {
				$optionss=$this->pistas->getAvailableCourtsArray($selected_sport,$selected_court_type);
				if(isset($optionss) && count($optionss)!=1) {
					$pista=array('name' => 'court', 'desc' => $this->lang->line('court'), 'default' => $selected_court, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'court', 'type' => 'select', 'value' => array('' => $optionss));
					array_push($filter_array, $pista);
				}
			}


			
			# Filtro de ESTADO DE RESERVA
			if(!isset($options['status']) || $options['status']=="1") {
				$optionss=$this->reservas->getReserveStatusArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'status', 'desc' => $this->lang->line('reserve_status'), 'default' => $selected_status, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'status', 'type' => 'select', 'value' => $optionss);
					array_push($filter_array, $equipo);
				}
			}
			

			
			# Filtro de FORMA DE PAGO
			if(!isset($options['paymentway']) || $options['paymentway']=="1") {
				$optionss=$this->reservas->getPaymentWaysArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'paymentway', 'desc' => $this->lang->line('payment_ways'), 'default' => $selected_paymentway, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'paymentway', 'type' => 'select', 'value' => $optionss);
					array_push($filter_array, $equipo);
				}
			}
			

//echo "AA".$options['date']."<br>";print_r($options);
			# Filtro de FECHA
			if(!isset($options['date']) || $options['date']=="1") {
				$fecha=array('name' => 'date1', 'desc' => $this->lang->line('date1'),  'default' => $selected_date1, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'date1', 'type' => 'date');
				array_push($filter_array, $fecha);
				# Filtro de FECHA
				$fecha=array('name' => 'date2', 'desc' => $this->lang->line('date2'),  'default' => $selected_date2, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'date2', 'type' => 'date');
				array_push($filter_array, $fecha);
			}


			
			# Completo la informacion de los filtros en sesion
			/*
			$this->session->set_userdata('filters', $filtros);	
			//print("<pre>"); print_r($filter_array);
			$filtros2=$this->session->userdata('filters');print("<pre>"); print_r($filtros2);
			*/
			
			return $filter_array;
		}

	
# -------------------------------------------------------------------
# Funcion que valida una reserva
# -------------------------------------------------------------------
	function validate_reserve($id_transaction)
	{
		$this->load->model('reservas_model', 'reservas', TRUE);
		if($this->redux_auth->logged_in()) 
		{			

			$result = $this->reservas->validate_reserve($id_transaction);
			if ($result)
			{
				/* introducir mensaje */
			}
			else 
			{
				/* introducir mensaje */
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$this->list_all();
	}

	
# -------------------------------------------------------------------
# Funcion que cancela una reserva
# -------------------------------------------------------------------
	function cancel_reserve()
	{
		$this->load->model('reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');
		
		$id_transaction = $this->input->post('id_transaction');
		//echo $id_transaction."<br>";
		$text_cancel = $this->input->post('text_cancel'); 
		//echo $text_cancel."<br>";
		if($this->redux_auth->logged_in()) 
		{	
			$profile=$this->redux_auth->profile();
			$info=$this->reservas->getBookingInfoById($id_transaction);
			//print("<pre>");print_r($info);

			$result = $this->booking->cancel_reserve($info, $text_cancel, array('mail' => TRUE));
			//exit();
			if ($result)
			{
				//print("<pre>");print_r($info); exit();
				$this->session->set_userdata('info_message','La accion se ha realizado correctamente');
				//if($this->config->item('cancelled_reserve_refund') && isset($info) && $info['user'] && $info['status'] == '9') $this->usuario->addPrepaidMovement($info['user'], $info['total_price'], 1, 1, $id_transaction);
			}	else {
				$this->session->set_userdata('error_message','Error en la cancelaci&oacute;n de reserva');
			}
			
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}

			
			$returnUrl = $this->session->userdata('returnUrl');
			$this->session->unset_userdata('returnUrl');
			if(isset($returnUrl) && $returnUrl != "" ) redirect($returnUrl, 'Location'); 
			else redirect(site_url('reservas_gest/list_all/'.time()), 'Location'); 
			exit();
	}

	
# -------------------------------------------------------------------
# Funcion que cancela una reserva por GET
# -------------------------------------------------------------------
	function cancel_reserve_get($id_transaction, $text_cancel, $dummy)
	{
		$this->load->model('reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');

		if (isset($id_transaction)) 
		{
			$text_cancel = urldecode($text_cancel);
			if($this->redux_auth->logged_in()) 
			{			
				$profile=$this->redux_auth->profile();
				$info=$this->reservas->getBookingInfoById($id_transaction);
				//print("<pre>");print_r($info);
	
				$result = $this->booking->cancel_reserve($info, $text_cancel, array('mail' => TRUE));

				$result = $this->reservas->cancel_reserve($id_transaction, $text_cancel);
				if ($result)
				{
					$this->session->set_userdata('info_message','La accion se ha realizado correctamente');
					//if($this->config->item('cancelled_reserve_refund') && isset($info) && $info['user'] && $info['status'] == '9') $this->usuario->addPrepaidMovement($info['user'], $info['total_price'], 1, 1, $id_transaction);
					return 1;
				}
				else 
				{
					echo '0';
					return 0;
				}		
			}	
			else {
				# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
				echo '0';
				return 0;
			}
		}
		else
		{
			echo '0';
			return 0;
		}
	}

	
# -------------------------------------------------------------------
# Funcion que cancela una reserva para Ajax
# -------------------------------------------------------------------
	function change_reserve_get($id_transaction, $fecha, $intervalo, $id_court, $dummy)
	{
			//exit("AA");
		$this->load->model('reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');
		if($this->redux_auth->logged_in()) 
		{	
			$array_ini = explode(':', date('H:i',strtotime($intervalo)));
			$hora_inicio = $array_ini[0];
			$minuto_inicio = $array_ini[1];
			$fecha = date('Y-m-d',strtotime($fecha));
			$info_antigua = $this->reservas->getBookingInfoById($id_transaction);
			$result = $this->booking->change_reserve($id_transaction, $info_antigua, $intervalo, $fecha, $id_court);
			/*
			$result = $this->reservas->change_reserve_get($id_transaction,
													$intervalo,
													$hora_inicio,
													$minuto_inicio,
													$fecha,
													$id_court);
			*/
												
			if (stristr($result,'ok'))			
			{
				log_message('debug', 'Reserva cambiada: '.$id_transaction);
				

				#Envio de mail de confirmacion de modificacion de reserva
				if($this->config->item('reserve_send_mail')) {
					log_message('debug', '1');
					$email='';
					$nueva_id_tmp = explode('|', $result);
					$nueva_id = $nueva_id_tmp[1];
					$info = $this->reservas->getBookingInfoById($nueva_id);
					log_message('debug', 'usuario: '.$info['user']);

					$this->booking->notify_booking($info, array('action' => 'change', 'old_booking' => $info_antigua));


				}	else log_message('debug', 'No enviar notificacion');
				
				echo '1';
				return TRUE;
			}
			else 
			{
								log_message('debug', 'Reserva NO cambiada');

				echo '0';
				return TRUE;
			}	
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			echo '0';
			return TRUE;
		}
	}	

	
# -------------------------------------------------------------------
# Funcion para añadir y cobrar la luz a una reserva
# -------------------------------------------------------------------
	function light_reserve_get($id_transaction, $num_intervalos=NULL, $id_payment=NULL, $dummy)
	{
		$this->load->model('reservas_model', 'reservas', TRUE);
		$booking = Array();
		
		//el redirect se debe introducir en la sesion, si no está seleccionado sólo pintamos por pantalla
		$redirect = $this->session->userdata('redirect');
		
		if ($redirect == null)
		{
			$redirect = 'NO_REDIRECT';
		} 
		if($this->redux_auth->logged_in()) 
		{
			//capturo la info de la reserva
			$booking = $this->reservas->getBookingInfoById($id_transaction);
			//chequeos de parametros
			//si no hay intervalos seleccionados será toda la reserva
			
			if (!isset($num_intervalos))
			{
				//calcular el numero de intervalos
				$num_intervalos = $booking['intervals'];
			}
			else if ($num_intervalos == 'null')
			{
				$num_intervalos = $booking['intervals'];
			}
			//si el metodo de pago no está seleccinado será en efectivo
			if (!isset($id_payment))
			{
				$id_payment = 1;
			}
			else if ($id_payment == 'null')
			{
				$id_payment = 1;
			}
		
		
			//actualizo la reserva para marcar la hora
			//FALTA AÑADIR EL PRECIO DE LA HORA
			$this->reservas->court = $booking['id_court'];
	    	$this->reservas->date = $booking['date'];
	    	$this->reservas->intervalo = $booking['inicio'];
			if ($this->reservas->getPrice())
			{
				$price_light = $this->reservas->price_light; 
			}
			$result = $this->reservas->setLight($id_transaction, $num_intervalos);
			
			//inserto una entrada en payments para corroborar el pago
			$this->load->model('payment_model', 'payment_model', TRUE);
			//relleno las variable necesarias
			$this->payment_model->id_type = '1';
			$this->payment_model->id_element = $booking['id_court'];
			$this->payment_model->id_user = $booking['user'];
			$this->payment_model->status = $booking['status'];
			$this->payment_model->quantity = ($price_light * $num_intervalos);
			$this->payment_model->datetime = date(DATETIME_DB);
			$this->payment_model->description = 'Pago Suplemento Luz';
			$this->payment_model->id_transaction = $id_transaction;
			$this->payment_model->create_user = $this->session->userdata('user_id');
			$this->payment_model->create_time = date(DATETIME_DB);
			//inserto el registro
			$result = $this->payment_model->setPayment(); 

			//FALTA AÑADIR REDIRECT
			if ($result)
			{
				if ($redirect=='NO_REDIRECT')
				{
					echo '1';
					return 1;					
				}
				else
				{
					redirect(site_url().$redirect, 'Location');
				}
			}
			else 
			{
				echo '0';
				return 0;
			}	
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			echo '0';
			return 0;
		}
	}	
# -------------------------------------------------------------------
# Funcion que cancela una reserva
# -------------------------------------------------------------------
	function change_reserve()
	{
		$this->load->model('reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');
		
		if($this->redux_auth->logged_in()) 
		{			
			$id_transaction = $this->input->post('id_transaction');
			$intervalo_inicio = $this->input->post('hora_inicio');
			$intervalo_fin = $this->input->post('hora_fin');
			$array_ini = explode(':', $intervalo_inicio);
			$array_fin = explode(':', $intervalo_fin);
			$hora_inicio = $array_ini[0];
			$minuto_inicio = $array_ini[1];
			$hora_fin = $array_fin[0];
			$minuto_fin = $array_fin[1];
			
			$returnOkUrl = $this->session->flashdata('returnOkUrl');
			$returnKoUrl = $this->session->flashdata('returnKoUrl');
			
			/*print 'Hora Inicio:'.$hora_fin; 
			print '<br>';
			print 'Hora Inicio:'.$minuto_fin;*/
			
			
			$info_antigua=$this->reservas->getBookingInfoById($id_transaction);
			$result = $this->reservas->change_reserve($id_transaction,
													$hora_inicio,
													$minuto_inicio,
													$hora_fin,
													$minuto_fin);
			
			//print $result;									
			if (stristr($result,'ok'))
			{
				$this->session->set_userdata('info_message','La acci&oacute;n se ha realizado correctamente');
				
				
				#Envio de mail de confirmacion de modificacion de reserva
				if($this->config->item('reserve_send_mail')) {
					$email='';
					$nueva_id_tmp = explode('|', $result);
					$nueva_id = $nueva_id_tmp[1];
					$info=$this->reservas->getBookingInfoById($nueva_id);

					$this->booking->notify_booking($info, array('action' => 'change', 'old_booking' => $info_antigua));

				}				
				
				if($returnOkUrl) redirect($returnOkUrl, 'Location'); else redirect(site_url('reservas_gest/list_all'), 'Location');
			}
			else 
			{
				$this->session->set_userdata('error_message',$result);
				if($returnKoUrl) redirect($returnKoUrl, 'Location'); else redirect(site_url('reservas_gest/list_all'), 'Location');
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		redirect(site_url(), 'Location');
	}



	function payment_request($id_transaction, $options = NULL)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_level=$profile->group;
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$info=$this->reservas->getBookingInfoById($id_transaction);
		$payment_type = 1;
		//print_r($info);exit();
		
		# Cargo los estados de reservas que pueden ser cobradas aún
		$this->load->config('facturacion');
		$opciones = $this->config->item('booking_status_can_be_payd_option');
		//echo $status.'--'.strval($info[0]->status); print_r($opciones);exit();
		if(!in_array($info['status'], $opciones) &&  $options!='light_only') {
			# Si no está en el array.. o si solo quiero cobrar luz, no se puede cobrar ya.
			
			if($returnoKoUrl == '' or !isset($returnoKoUrl)) $returnoKoUrl = site_url('reservas_gest/list_all');
			
			$this->session->set_userdata('error_message', 'No es posible pagar esta reserva');
			redirect($returnoKoUrl); exit();
		} 
							
		# Formas de pago
		$this->load->model('Payment_model', 'pagos', TRUE);
		$paymentMethods=$this->pagos->getPaymentMethodsByUser($user_level);
		
		# Especificaciones de métodos de pago de esta pantalla
		//$paymentMethods['creditcard'] = TRUE;
		# Modificaciones de formas de pago dado que el pago es en recepción y desde el listado de reservas ya hechas
		$paymentMethods['tpv'] = FALSE;
		$paymentMethods['reserve'] = FALSE;
		if($user_level==7) {
			$paymentMethods['cash'] = FALSE;
			$paymentMethods['creditcard'] = FALSE;
		}	
		$this->session->set_flashdata('paymentMethods', $paymentMethods);
		$returnOkUrl = $this->session->flashdata('returnOkUrl');
		$returnKoUrl = $this->session->flashdata('returnKoUrl');
		
		$conceptos = array();
		if(isset($options) && $options == 'light_only') {
			#Conceptos de pago en caso de hablar de cobrar solo la luz
			# Si quieero cobrar la luz
			if($info['light']=='1') {
				# Luz ya pagada
				if($returnoKoUrl == '' or !isset($returnoKoUrl)) $returnoKoUrl = site_url('reservas_gest/list_all');
				
				$this->session->set_userdata('error_message', 'Luz ya abonada en esta reserva');
				redirect($returnoKoUrl); exit();
				
			}
			$payment_type = $this->config->item('booking_extra_light_payment_type');
			
			$num_intervalos = $info['intervals'];
		
			//calculo de precio de luz
			$this->reservas->court = $info['id_court'];
	    $this->reservas->date = $info['date'];
	    $this->reservas->intervalo = $info['inicio'];
	    $this->reservas->group = $this->usuario->getUserGroup($info['user']);
			if ($this->reservas->getLightPrice())
			{
				$price_light = $this->reservas->price_light; 
			}			
			$cargo_luz = $price_light * $num_intervalos;
			//print($price_light.'<pre>');print_r($info);
			//exit();
			# Si la reserva solo está reservada, pero no pagada, directamente cambio el estado de la luz y regreso al listado.. sin generar pago. 
			# Si la reserva ya está pagada.. continuo ejecutando, para generar el pago adicional
			//print_r($opciones);
			//print_r($info);
			//exit();
			if(in_array($info['status'], $opciones)) {
				$result = $this->reservas->setLight($id_transaction, $num_intervalos);
				if($returnoKoUrl == '' or !isset($returnoKoUrl)) $returnoKoUrl = site_url('reservas_gest/list_all');
				$this->session->set_userdata('info_message', 'Luz activada en la reserva.');
				redirect($returnoKoUrl); 
				exit();
			}
			
			array_push($conceptos, array('text' => "Suplemento luz reserva ".$info['booking_code']." (".$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).")", 'value' => $cargo_luz));
			//print_r($conceptos);exit();
			
		}	else {
			# Conceptos en caso de hablar de reserva completa
			array_push($conceptos, array('text' => "Reserva ".$info['court']." (".$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).")", 'value' => $info['price']));
			if(isset($info['precio_supl1']) && $info['precio_supl1'] != 0) array_push($conceptos, array('text' => "Suplemento reserva anticipada", 'value' => $info['precio_supl1']));
			if(isset($info['playing_users']) && count($info['playing_users']) > 1) {
				$externos = 0;
				if($debug) print_r($info['players']);
				foreach($info['playing_users'] as $usuario) {
					if(isset($usuario['id_user']) && $usuario['id_user'] == 0) $externos++;
				}
				
				if($externos > 0 ) $precio = ($externos * 7 );
				$this->reservas->setPriceExtra($id_transaction, 'price_supl2', $precio, 'first');
				//echo $externos;
				array_push($conceptos, array('text' => "Invitados (".$externos.")", 'value' => $precio));
			}			
		}
		if($info['light']) array_push($conceptos, array('text' => "Suplemento de luz", 'value' => $info['light_price']));



		$this->session->set_flashdata('paymentLines', $conceptos);
		$this->session->set_flashdata('returnOkUrl', site_url('reservas_gest/list_all/'.time()));
		$this->session->set_flashdata('returnKoUrl', site_url('reservas_gest/list_all/'.time()));
	
		redirect(site_url('payment/payment_request/'.$payment_type.'/'.$id_transaction.'/1/'.$options), 'Location'); 
		exit();


	}
	
	
	


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Visualizacion del detalle de una reserva
# -------------------------------------------------------------------

	function detail($id_transaction, $function = NULL, $usuario = NULL)
	{

		if(!isset($id_transaction)) {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			$user_name=$profile->username;

				# Variable que habilita o no el registrar la reserva como 'partido compartido'
				$permiso=$this->config->item('bookings_visualization_permission');
				if(!$permiso[$profile->group]) {
	        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
	        redirect(site_url(), 'Location'); 
	        exit();
				}
			}	else {
			$user_id=0;
			$user_group=9;
			$user_name=$this->lang->line('anonymous_user');
		}
		
		
		if(isset($function)) {
			# Si estamos llamando a alguna funcionalidad
			if($function == 'delete' && isset($usuario)) {
				#Si quiero borrar un usuario y vienen todos los datos
				$this->reservas->remove_player($id_transaction, $usuario);
        $this->session->set_userdata('info_message', 'Jugador eliminado. <br>Coste de la reserva no alterado! <br>Deber&aacute; gestionar pagos adicionales con pagos manuales');
        redirect(site_url('reservas_gest/detail/'.$id_transaction), 'Location'); exit();	
			}	//FIn de control de eliminacion de jugadores
			
			if($function == 'add') {
				#Si quiero añadir un usuario y vienen todos los datos
				$id_user = $this->input->post( "id_user");
				if(!isset($id_user) || $id_user == '') $id_user = '0';
				$user_desc = $this->input->post( "user_desc");
				$user_phone = $this->input->post( "user_phone");
				if($id_user != '' || ($user_desc!='' && $user_phone!='')) {
					$resultado = $this->reservas->add_player($id_transaction, array('id_user' => $id_user, 'user_desc' => $user_desc, 'user_phone' => $user_phone));
				}
				
				#Comprobacion de resultado y escribir mensaje en sesion
				if(!isset($resultado)) {
	        $this->session->set_userdata('info_message', 'Jugador A&ntilde;adido. <br>Coste de la reserva no alterado! <br>Deber&aacute; gestionar pagos adicionales con pagos manuales');
				} else {
	        $this->session->set_userdata('error_message', 'Error al a&ntilde;adir jugador. <br>Revise los datos aportados');
				}
        redirect(site_url('reservas_gest/detail/'.$id_transaction), 'Location'); exit();	
        
			}	// Fin de control de la adicion de jugadores
			
		}	// Fin de control de las funciones

		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);print("</pre>");exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_userdata('error_message', 'Informacion no encontrada');
            redirect(site_url(), 'Location'); exit();	
		}
				
		$data=array(
			'menu' => $this->load->view('menu', '', true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		

		$extra = '';//link_tag(base_url().'css/reservas_info.css')."\r\n";
		$data['info']=$info;

		$data['meta']=$this->load->view('meta', array('extra' => $extra), true);
		$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
		$data['footer']=$this->load->view('footer', '', true);

		
		$data['page']='reservas_gest/view';

    $this->load->view('main', $data);
    //print("<pre>");print_r($this->session);
	}


	
	
/* End of file reservas_gest.php */
/* Location: ./system/application/controllers/reservas_gest.php */
}