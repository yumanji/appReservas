<?php

class Gestion extends Controller {

	function Gestion()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
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
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			$data['profile']=$profile;
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		if($this->redux_auth->logged_in()) $data['page']='gestion/index';

		
		
		$this->load->view('main', $data);
	}


	

# -------------------------------------------------------------------
#  listado de las fechas con calendarios de apertura especial
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function pistas()
	{
		
		$this->load->model('pistas_model', 'pistas', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('config_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'type',index:'type', width:10, align:'center'},
						   		{name:'fecha_', index:'fecha_', width:6, align:'center', editable:true},
						   		{name:'horario', index:'horario', width:6, align:'center', editable:true},
						   		{name:'pista',index:'pista', width:20, align:'center'},
								";
		$colnames = "'Id','Tipo','Fecha','Horario','Pista'";

		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "gestion/jqgrid_list_all", 
				'title' => 'Listado de fechas especiales', 
				'default_orderfield' => 'date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';

		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('gestion/list_specialdate_all', array('grid_code' => $grid_code,  'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}







# -------------------------------------------------------------------
#  devuelve el listado de fechas especiales de calendario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all ($add_params = NULL)
	{
		$this->load->model('pistas_model', 'pistas', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('config_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
		}	else {
        exit(0);
		}

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

		if(trim($where)!="") $where .= ' AND ';
		if(isset($add_params['where']) && $add_params['where']!='') $where .= $add_params['where'];

		$req_param['where'] = $where;
//print($where);
		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->pistas->get_specialdates_data($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->pistas->get_specialdates_data ($req_param, 'none');
		$data->rows = $records;
//print_r($records);
		echo json_encode ($data );
		exit( 0 );
	}






# -------------------------------------------------------------------
#  devuelve el listado de fechas especiales de calendario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function new_specialdate ($add_params = NULL)
	{
		$this->load->model('pistas_model', 'pistas', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


		###########
		# Función de creacion
		if($this->input->post('action') && $this->input->post('action')=="guardar") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$data['status'] = '1';
			$data['date'] = date($this->config->item('date_db_format'), strtotime($data['date']));
			unset($data['action']);
			//print("<pre>");print_r($data);exit();
			
			$id = $this->pistas->createSpecialTimeTables($data);
			
      $this->session->set_userdata('info_message', 'horario especial creado.');
			redirect(site_url('gestion/pistas'), 'Location'); 
			exit();
		}
		# Fin del creacion del registro
		##########
				
		$pistas = $this->pistas->getAvailableCourtsArray();
		$horarios = $this->pistas->getTimeTablesArray();

			# Carga de niveles para la vista

			# Carga de datos para la vista
			$data=array(
				'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
				'menu' => $this->load->view('menu', $menu, true),
				'navigation' => $this->load->view('navigation', '', true),
				'footer' => $this->load->view('footer', '', true),				
				//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
				'form' => 'formDetail',
				'array_courts' => $pistas,
				'array_times' => $horarios,
				'page' => 'gestion/specialdate_new',
				//'enable_grid' => 1,
				//'js_grid' => $grid_js,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

			$this->load->view('main', $data);
			/* pintar */		

		
}


# -------------------------------------------------------------------
#  devuelve el listado de fechas especiales de calendario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function del_specialdate ($id)
	{
		$this->load->model('pistas_model', 'pistas', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$this->session->set_userdata('info_message', 'Horario especial eliminado');
		$this->pistas->deleteSpecialTimeTable($id);
			redirect(site_url('gestion/pistas'), 'Location'); 
			exit();

			/* pintar */		

		
}


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */