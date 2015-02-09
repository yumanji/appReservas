<?php

class Lessons extends Controller {
 /*
 # CONTENIDO
 #
 # index()
 
 */
	function Lessons()
	{
		parent::Controller();	
		$this->lang->load('lessons');
		$this->config->load('lessons');

		
	}
	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de búsqueda de pista
# -------------------------------------------------------------------

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
		
		$calendario = $this->load->view('lessons/calendar', array(), true);
		
		//$extra_meta = link_tag(base_url().'css/dailog.css').link_tag(base_url().'css/calendar.css').link_tag(base_url().'css/dp.css').link_tag(base_url().'css/alert.css').link_tag(base_url().'css/main.css').'<script src="'.base_url().'js/calendar/Common.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/datepicker_lang_US.js" type="text/javascript"></script>    <script src="'.base_url().'js/calendar/jquery.datepicker.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.alert.js" type="text/javascript"></script>  <script src="'.base_url().'js/calendar/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script> <script src="'.base_url().'js/calendar/wdCalendar_lang_US.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.calendar.js" type="text/javascript"></script>';
		$data=array(
			'meta' => $this->load->view('meta', array('lib_calendar' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion_ppal', array(), true)), true),
			'menu' => $this->load->view('menu', '', true),
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
#  Listado general de las reservas usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function lista()
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
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$deportes = ':All;'.$this->reservas->getSportsArray('json');

		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'description',index:'lessons.description', width:25, align:'center', datefmt:'dd/mm/Y', date:true},
						   		{name:'sport_desc', index:'zz_sports.description', width:10, align:'center',stype:'select', searchoptions:{value:'".$deportes."'}},
						   		{name:'court_desc', index:'court_desc', width:10, align:'center'},
						   		{name:'dia_semana', index:'weekday', width:10, align:'center', editable:true},
						   		{name:'rango_fechas', index:'start_date', width:20, align:'center', editable:true},
						   		{name:'rango_horas',index:'start_time', width:12, align:'center'},
						   		{name:'plazas', index:'max_vacancies', width:8, align:'center'},
						   		{name:'profesor', index:'meta.first_name', width:15, align:'center'}";
		$colnames = "'Id','Nombre', 'Deporte', 'Pista','Dia','Fechas','Horario', 'Plazas', 'Profesor'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "lessons/jqgrid_list_all", 
				'title' => 'Listado de cursos', 
				'default_orderfield' => 'sport_desc', 
				'default_orderway' => 'asc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '820',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 820px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = $this->load->view('lessons/menu_lateral', '', true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion_ppal', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('lessons/list_all', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => $menu_lateral), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_flashdata('returnOkUrl', site_url('reservas_gest/list_all'));
			$this->session->set_flashdata('returnKoUrl', site_url('reservas_gest/list_all'));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}



# -------------------------------------------------------------------
#  devuelve el listado de reservas para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all ($add_params = NULL, $id_user = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			} else {
				//if($user_group > '3') $where = "booking.id_user = '".$profile->id."'";
			}
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

		$where = "lessons.active = '1'";
		
		if($req_param['search']=='true' && $req_param['search_field']!='' && $req_param['search_operator']!='' && $req_param['search_str']!='') {
			if(trim($where)!="") $where .= ' AND ';
			
			$where .= $req_param['search_field'];
			switch($req_param['search_operator']) {
				case 'cn':
					$where .=' LIKE \'%'.$req_param['search_str'].'%\' '; 
				break;
			}	
		}

		#Procesado de filtros automaticos
$filters = $_POST['filters'];
$search = $_POST['_search'];

    //$where = "";

if(($search==true) &&($filters != "")) {


        $filters = json_decode($filters);
        //$where = " where ";
        $whereArray = array();
        $rules = $filters->rules;
        $groupOperation = $filters->groupOp;
        foreach($rules as $rule) {

            $fieldName = $rule->field;
			$search1 = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
			$replace1 = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

			$fieldData = str_replace($search1, $replace1, $rule->data);

            switch ($rule->op) {
           case "eq":
                $fieldOperation = " = '".$fieldData."'";
                break;
           case "ne":
                $fieldOperation = " != '".$fieldData."'";
                break;
           case "lt":
                $fieldOperation = " < '".$fieldData."'";
                break;
           case "gt":
                $fieldOperation = " > '".$fieldData."'";
                break;
           case "le":
                $fieldOperation = " <= '".$fieldData."'";
                break;
           case "ge":
                $fieldOperation = " >= '".$fieldData."'";
                break;
           case "nu":
                $fieldOperation = " = ''";
                break;
           case "nn":
                $fieldOperation = " != ''";
                break;
           case "in":
                $fieldOperation = " IN (".$fieldData.")";
                break;
           case "ni":
                $fieldOperation = " NOT IN '".$fieldData."'";
                break;
           case "bw":
                $fieldOperation = " LIKE '".$fieldData."%'";
                break;
           case "bn":
                $fieldOperation = " NOT LIKE '".$fieldData."%'";
                break;
           case "ew":
                $fieldOperation = " LIKE '%".$fieldData."'";
                break;
           case "en":
                $fieldOperation = " NOT LIKE '%".$fieldData."'";
                break;
           case "cn":
                $fieldOperation = " LIKE '%".$fieldData."%'";
                break;
           case "nc":
                $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                break;
            default:
                $fieldOperation = "";
                break;
                }
            if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
        }
        if (count($whereArray)>0) {
			if(trim($where)!="") $where .= ' AND ';
            $where .= join(" ".$groupOperation." ", $whereArray);
        } 
    }

	
		if(isset($id_user) && $id_user != '') {
			if(trim($where)!="") $where .= ' AND ';
			$where .= ' lessons.id IN (SELECT id_lesson from lessons_assistants WHERE id_user = '.$id_user.' and status IN (1,2,3)) ';
		}
		
		$req_param['where'] = $where;
		if(isset($add_params) && $add_params != 'none' && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		$data->page = $this->input->post( "page", TRUE );

		//print("<pre>");print_r($req_param);exit();
		$data->records = count($this->lessons->get_data ($req_param,"all"));
		$data->total = $data->records;
		if($req_param['num_rows']!=0) $data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->lessons->get_data ($req_param, 'none');
		$data->rows = $records;
		//print("<pre>");print_r($data);exit();
		//echo "<pre>"; print_r($data->rows);
		
		echo json_encode ($data );
		exit( 0 );
	}
	
	

# -------------------------------------------------------------------
#  devuelve el listado de reservas para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all_generation ($add_params = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
			$this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
			redirect(site_url(), 'Location'); 
			exit();
			} else {
				//if($user_group > '3') $where = "booking.id_user = '".$profile->id."'";
			}
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

		$where = "lessons.active = '1'";
		
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
		if(isset($add_params) && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		$data->page = $this->input->post( "page", TRUE );


		$data->records = count($this->lessons->get_data (array('where' => 'lessons.id_sport in (1, 9, 10, 11)', 'orderby' => 'id_sport'),"all"));
		$data->total = $data->records;
		if($req_param['num_rows']!=0) $data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->lessons->get_data (array('where' => 'lessons.id_sport in (1) and lessons.price in (54, 57)', 'orderby' => 'id_sport', 'orderbyway' => 'ASC'), 'all');
		$data->rows = $records;
		//print("<pre>");print_r($data);exit();
		//echo "a<pre>"; print_r($records);exit();
		foreach($records as $curso) {
			echo '<br><a href="'.site_url('control/lessons_quotas_torrijos/'.$curso['id']).'" target="_blank">Generar</a> Curso: '.$curso['description'].' - '.$curso['sport_desc'];
		}
		exit();
		echo json_encode ($data );
		exit( 0 );
	}
	
		

# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_assistants ($id = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);

		$where = '';
		if(!$id) exit(0);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
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
		if($where!='') $where.=' AND ';
		$where.= 'lessons_assistants.id_lesson = \''.$id.'\' AND lessons_assistants.status IN (1,2,3)';
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->lessons->get_AssitantsData($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->lessons->get_AssitantsData ($req_param, 'none');
		//print("<pre>");print_r($records);
		
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}

		

# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_waiting ($id = NULL, $global = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);

		$where = '';
		if(!$id && !$global) exit(0);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
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
		if(!isset($global)) {
			if($where!='') $where.=' AND ';
			$where.= 'lessons_assistants.id_lesson = \''.$id.'\'';
		}
		if($where!='') $where.=' AND ';
		$where.= 'lessons_assistants.status IN (7)';
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->lessons->get_AssitantsData($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->lessons->get_AssitantsData ($req_param, 'none');
		//print("<pre>");print_r($records);
		
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}

	
	
	
# -------------------------------------------------------------------
#  devuelve el listado de usuarios dados de baja de un curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_erased ($id = NULL, $global = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);

		$where = '';
		if(!$id && !$global) exit(0);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
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
		if(!isset($global)) {
			if($where!='') $where.=' AND ';
			$where.= 'lessons_assistants.id_lesson = \''.$id.'\'';
		}
		if($where!='') $where.=' AND ';
		$where.= 'lessons_assistants.status IN (9)';
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->lessons->get_AssitantsData($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->lessons->get_AssitantsData ($req_param, 'none');
		//print("<pre>");print_r($records);
		
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}

	
# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_assistance ($id = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);

		$where = '';
		if(!$id) exit(0);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
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
		if($where!='') $where.=' AND ';
		$where.= 'lessons_assistance.id_lesson = \''.$id.'\'';
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->lessons->get_AssitanceData($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->lessons->get_AssitanceData ($req_param, 'none');
		//print("<pre>");print_r($records);
		
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );	}

	
	
# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de búsqueda de pista
# -------------------------------------------------------------------

	function calendar($id)
	{
		$this->load->model('Redux_auth_model', 'usuario', TRUE);


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
      }
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		


		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);
		
		$calendario = $this->load->view('lessons/calendar_detail', array('lesson' => $id), true);
		
		//$extra_meta = link_tag(base_url().'css/dailog.css').link_tag(base_url().'css/calendar.css').link_tag(base_url().'css/dp.css').link_tag(base_url().'css/alert.css').link_tag(base_url().'css/main.css').'<script src="'.base_url().'js/calendar/Common.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/datepicker_lang_US.js" type="text/javascript"></script>    <script src="'.base_url().'js/calendar/jquery.datepicker.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.alert.js" type="text/javascript"></script>  <script src="'.base_url().'js/calendar/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script> <script src="'.base_url().'js/calendar/wdCalendar_lang_US.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.calendar.js" type="text/javascript"></script>';
		$data=array(
			'meta' => $this->load->view('meta', array('lib_calendar' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', '', true),
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

	function edit($id = NULL, $start = NULL, $end = NULL, $isallday = NULL, $title = NULL, $dummy = NULL)
	{
 	
		$this->load->library('calendario');


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
      }
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		if(isset($id) && !strstr($id, 'time')) $event = $this->calendario->getCalendarByRange($id);
		
		//$edicion = $this->load->view('lessons/edit', array('event' => $event), true);
		
		$extra_meta = link_tag(base_url().'css/dropdown.css').link_tag(base_url().'css/colorselect.css').link_tag(base_url().'css/dp.css').link_tag(base_url().'css/main.css').'<script src="'.base_url().'js/jquery-1.4.2.js" type="text/javascript"></script><script src="'.base_url().'js/calendar/Common.js" type="text/javascript"></script><script src="'.base_url().'js/calendar/jquery.form.js" type="text/javascript"></script><script src="'.base_url().'js/calendar/jquery.validate.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/datepicker_lang_US.js" type="text/javascript"></script>    <script src="'.base_url().'js/calendar/jquery.datepicker.js" type="text/javascript"></script> <script src="'.base_url().'js/calendar/jquery.dropdown.js" type="text/javascript"></script>  <script src="'.base_url().'js/calendar/jquery.colorselect.js" defer="defer" type="text/javascript"></script>';
		
		$data=array(
			//'meta' => $this->load->view('meta', array('extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			//'footer' => $this->load->view('footer', '', true),	
			'meta' => $extra_meta,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		if(isset($event)) $data['event'] = $event;

    $this->load->view('lessons/edit', $data);

	}





# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function assistants ($id)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->model('lessons_model', 'lessons', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
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

		$this->session->set_userdata('returnUrl', site_url('lessons/assistants/'.$id));	
		
		$colmodel = "	{name:'id',index:'lessons_assistants.id', width:1, align:'center',hidden:true},
						   		{name:'user_desc',index:'user_desc', width:13, align:'center'},
						   		{name:'group_desc',index:'users.group_id', width:7, align:'center'},
						   		{name:'user_phone',index:'user_phone', width:5, align:'center'},
						   		{name:'fecha_nacimiento',index:'fecha_nacimiento', width:5, align:'center'},
						   		{name:'signed_desc',index:'sign_date', width:2, align:'center'},
						   		{name:'last_day_payed', index:'lessons_assistants.last_day_payed', width:5, align:'center'},
						   		{name:'quota', index:'lessons_assistants.id', width:3, align:'right'},
						   		{name:'discount', index:'lessons_assistants.discount', width:3, align:'right'}";
		$colnames = "'Id', 'Nombre', 'Nivel', 'Telefono', 'Nacimiento', 'Alta', 'Pagado hasta', 'Cuota', 'Desc'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel,
				'colnames' => $colnames, 
				'data_url' => "lessons/jqgrid_list_assistants/".$id, 
				'title' => 'Listado de alumnos', 
				'default_orderfield' => 'lessons_assistants.id', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';

		# Si hay cuota de alta, pinto el boton de pagar el alta
		$info = $this->calendario->getCalendarByRange($id);
		if($info->signin == 0) $alta = FALSE;
		else $alta = TRUE;

		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$motivos_baja = $this->lessons->getUnsubscriptionReasonsArray();
		# Gestión de permiso sobre carnet de socio
		$carnet=$this->config->item('lessons_enable_lesson_idcard');	// Habilita la emisión de carnet de curso
		$curso_permission = $this->config->item('lessons_idcard_lessons_enabled');
		$carnet_enabled = TRUE;
		if(!isset($carnet) || !$carnet || !isset($curso_permission) || !in_array($id, $curso_permission)) {
			$carnet_enabled = FALSE;
		}		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $id), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('lessons/list_players', array('grid_code' => $grid_code, 'enable_buttons' => TRUE,  'alta' => $alta, 'menu_lateral' => NULL, 'id_transaction' => $id, 'motivos_baja'=>$motivos_baja, 'carnet' => $carnet_enabled), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}





# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function waiting ($id)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
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
		
		$colmodel = "	{name:'id',index:'lessons_assistants.id', width:1, align:'center',hidden:true},
						   		{name:'user_desc',index:'user_desc', width:30, align:'center'},
						   		{name:'user_phone',index:'user_phone', width:10, align:'center'}";
		$colnames = "'Id', 'Nombre', 'Telefono'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "lessons/jqgrid_list_waiting/".$id, 
				'title' => 'Listado de alumnos en lista de espera', 
				'default_orderfield' => 'lessons_assistants.id', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		# Si hay cuota de alta, pinto el boton de pagar el alta
		$info = $this->calendario->getCalendarByRange($id);
		if($info->signin == 0) $alta = FALSE;
		else $alta = TRUE;

		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $id), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('lessons/list_waiting', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'alta' => $alta, 'menu_lateral' => NULL, 'id_transaction' => $id), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}





# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function erased ($id)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->model('lessons_model', 'lessons', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
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
		
		$colmodel = "	{name:'id',index:'lessons_assistants.id', width:1, align:'center',hidden:true},
						   		{name:'user_desc',index:'user_desc', width:30, align:'center'},
						   		{name:'user_phone',index:'user_phone', width:10, align:'center'},
						   		{name:'unsubscription_date',index:'unsubscription_date', width:10, align:'center'},
						   		{name:'unsubscription_reason',index:'unsubscription_reason', width:30, align:'center'}";
		$colnames = "'Id', 'Nombre', 'Telefono', 'Fecha Baja', 'Motivo Baja'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "lessons/jqgrid_list_erased/".$id, 
				'title' => 'Listado de alumnos dados de baja', 
				'default_orderfield' => 'lessons_assistants.id', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $id), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('lessons/list_erased', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL, 'id_transaction' => $id), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}





# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function waiting_all ()
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
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
		
		$colmodel = "	{name:'id',index:'lessons_assistants.id', width:1, align:'center',hidden:true},
						   		{name:'user_desc',index:'user_desc', width:20, align:'center'},
						   		{name:'user_phone',index:'user_phone', width:10, align:'center'},
						   		{name:'description',index:'lessons.description', width:20, align:'center'},
						   		{name:'sport_desc',index:'zz_sports.description', width:8, align:'center'},
						   		{name:'dia_semana',index:'lessons.weekday', width:8, align:'center'},
						   		{name:'rango_fechas',index:'lessons.start_date', width:20, align:'center'},
						   		{name:'rango_horas',index:'lessons.start_time', width:12, align:'center'},
						   		{name:'plazas',index:'lessons.current_vacancies', width:6, align:'center'}";
		$colnames = "'Id', 'Nombre', 'Telefono', 'Curso', 'Deporte', 'Dia', 'Fechas', 'Horario', 'Plazas'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "lessons/jqgrid_list_waiting/0/1", 
				'title' => 'Listado de alumnos en lista de espera', 
				'default_orderfield' => 'lessons_assistants.id', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		

		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion_ppal', '', true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('lessons/list_waiting_all', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}







# -------------------------------------------------------------------
#  devuelve el listado de reportes diarios de un curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function assistance ($id)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
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
		
		$colmodel = "	{name:'id',index:'lessons_reports.id', width:1, align:'center',hidden:true},
						   		{name:'fecha_lesson',index:'lessons_assistance.date_lesson', width:10, align:'center'},
						   		{name:'instructor_desc',index:'meta.first_name', width:20, align:'center'},
						   		{name:'done',index:'lessons_assistance.done', width:4, align:'center'},
						   		{name:'observations',index:'lessons_assistance.observations', width:20, align:'center'},
						   		{name:'admin_check',index:'lessons_assistance.admin_check', width:4, align:'center'},
						   		{name:'admin_obs',index:'lessons_assistance.admin_obs', width:20, align:'center'}";
		$colnames = "'Id', 'Fecha', 'Profesor', 'Hecho', 'Observaciones', 'Check', 'Obs. Admin'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "lessons/jqgrid_list_assistance/".$id, 
				'title' => 'Listado de partes de asistencia', 
				'default_orderfield' => 'lessons_assistance.date_lesson', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';

		# Comprobaciones para activar o desactivar botones
		$lessons_admincheck_permission = $this->config->item('lessons_admincheck_permission');
		if($lessons_admincheck_permission[$profile->group]) $admincheck = TRUE;
		else $admincheck = FALSE;
		
		
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $id), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('lessons/list_assistance', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL, 'admincheck' => $admincheck, 'id_transaction' => $id), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}






# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function detail($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		//print_r($this->session);
		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);


		if($this->input->post('action') && $this->input->post('action')=="save") {
			$debug = FALSE;
			if($debug) { print("<pre>");print_r($_POST);}
			//exit();
				$this->lessons->id = $id;
				$this->lessons->description = $this->input->post('description');
				$this->lessons->active = ($this->input->post('active'))? 1 : 0;
				$this->lessons->weekday = $this->input->post('weekday');
				$this->lessons->start_time = $this->input->post('start_time');
				$this->lessons->end_time = $this->input->post('end_time');
				$this->lessons->start_date = date($this->config->item('date_db_format'), strtotime($this->input->post('start_date')));
				$this->lessons->end_date = date($this->config->item('date_db_format'), strtotime($this->input->post('end_date')));
				$this->lessons->id_sport = $this->input->post('id_sport');
				$this->lessons->id_court = $this->input->post('id_court');
				$this->lessons->signin = $this->input->post('signin');
				$this->lessons->price = $this->input->post('price');
				$this->lessons->id_instructor = $this->input->post('id_instructor');
				$this->lessons->max_vacancies = $this->input->post('max_vacancies');
		 		$this->lessons->monthly_payment_day = $this->input->post('monthly_payment_day');
		 		$this->lessons->level = $this->input->post('level');
		 		$this->lessons->gender = $this->input->post('gender');
		 		$this->lessons->L = $this->input->post('L');
		 		$this->lessons->M = $this->input->post('M');
		 		$this->lessons->X = $this->input->post('X');
		 		$this->lessons->J = $this->input->post('J');
		 		$this->lessons->V = $this->input->post('V');
		 		$this->lessons->S = $this->input->post('S');
		 		$this->lessons->D = $this->input->post('D');

				$dias_semana_activos = array();
				if($this->lessons->L==1) array_push($dias_semana_activos, 1);
				if($this->lessons->M==1) array_push($dias_semana_activos, 2);
				if($this->lessons->X==1) array_push($dias_semana_activos, 3);
				if($this->lessons->J==1) array_push($dias_semana_activos, 4);
				if($this->lessons->V==1) array_push($dias_semana_activos, 5);
				if($this->lessons->S==1) array_push($dias_semana_activos, 6);
				if($this->lessons->D==1) array_push($dias_semana_activos, 0);
				if($debug) print_r($dias_semana_activos);
				
				/*
				$maxima_fecha = $this->reservas->get_max_booking_date();
				$fecha_actual = $this->lessons->start_date;
				if($debug) echo 'weekday: '.$this->lessons->weekday."<br>";
				if($debug) echo 'fecha actual: '.$fecha_actual."<br>";
				if($debug) echo 'dia semana actual: '.date( 'w', strtotime($fecha_actual))."<br>";
				while( !in_array(date( 'w', strtotime($fecha_actual)), $dias_semana_activos)) {
					$fecha_actual = date( $this->config->item('date_db_format'), strtotime($fecha_actual.' +1days'));
					if($debug) echo 'primera fecha cuyo día de la semana coincide con lo elegido: '.$fecha_actual."<br>";
				}
				if($debug) echo $fecha_actual.'---------------------->';
				$fecha_final = $this->lessons->end_date;
		
				$hora_inicio = $this->lessons->start_time;
				$hora_fin = $this->lessons->end_time;
		
				$pista = $this->input->post('id_court');
		
				if($debug) echo 'maxima fecha: '.$maxima_fecha.'<br>';
				if($debug) print($hora_inicio.$hora_fin.$this->lessons->weekday."<pre>");
				$valido = 1; $fecha_problematica = '';
				while($fecha_actual <= $maxima_fecha && $fecha_actual <= $fecha_final && $valido == 1) {
					if($debug) echo 'fecha procesada: '.$fecha_actual."<br>";
					$hora_actual = date( $this->config->item('hour_db_format'), strtotime($hora_inicio));
					
					$this->app_common->get_court_availability($pista, $fecha_actual, $id, array('block_past'=>FALSE));
					while($hora_actual <= $hora_fin && $valido == 1) {
						foreach($this->reservas->availability as $disp) {
							if($debug) echo $disp[0].' - '.$hora_actual.' - '.$disp[1]."<br>";
							if($disp[0] == $hora_actual && ($disp[1] == '0' && $disp[3] != 'l' &&  $disp[2] != $id)) {
								$valido = 0; 
								$fecha_problematica = $fecha_actual;
								if($debug) echo '<b>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br></b>';
							}
						}
						
						$hora_actual = date( $this->config->item('hour_db_format'), strtotime($hora_actual.' +30minutes'));
					}
					if($debug) print_r($this->reservas->availability);
					$fecha_actual = date( $this->config->item('date_db_format'), strtotime($fecha_actual.' +7days'));
				}
				*/
				if($debug) exit('Resultado:'.$valido);
				$valido = true;
				if($valido) {
					if($this->lessons->updateLessons($id)) $this->session->set_userdata('info_message', 'Informaci&oacute;n del curso actualizada correctamente');
					else $this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso');
				} else {
					$this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso. Alguna de las reservas necesarias (al menos para el d&iacute;a '.date( $this->config->item('reserve_date_filter_format'), strtotime($fecha_problematica)).') est&aacute; reservada con otro motivo.');
					redirect(site_url('lessons/detail/'.$id), 'Location'); 
					exit();
				}
		
		 		$this->lessons->signin = $this->input->post('signin');
		 		$this->lessons->monthly = $this->input->post('monthly');
				$this->lessons->updateLessonsPricesSimple($id);
				
				redirect(site_url('lessons/detail/'.$id), 'Location'); 
				exit();
		}


		
		$info = $this->calendario->getCalendarByRange($id);

		//echo '--'.$this->app_common->getPriceValue($info->price);

		/*
		if($info->signin == 0) echo 'aa';
		else echo 'bb';
		print_r($info);
		*/
		if($this->input->post('id_sport')!= '' && $info->id_sport != $this->input->post('id_sport')) $info->id_sport = $this->input->post('id_sport');	//Si llega un id_sport por POST y es diferente del guardado quiere decir que hemos cambiado de deporte y hay que darle preferencia. (se filtrará las pistas disponibles en base a eso)
		
		$profesores = $this->usuario->getActiveUsersArray('', 'users.group_id IN ('.implode( ", ", $this->config->item('lessons_profesor_group')).')');
		$deportes = $this->reservas->getSportsArray();
		//$pistas = $this->pistas->getAvailableCourtsArray($info->id_sport,'');
		$pistas = $this->pistas->getAvailableCourtsArray('','');
		$niveles = $this->lessons->getLevelsArray();
		$generos = $this->lessons->getGendersArray();
		$tarifas = $this->app_common->getPrices('array', $this->config->item('lessons_prices_range'));
		$asistentes = array();
		$espera = array();
		
		$enable_add = TRUE;
		if($info->current_vacancies == 0) $enable_add = FALSE;
		//print("<pre>".$this->reservas->get_max_booking_date());
		//print_r($asistentes);
		//print_r($info);
		//print_r($profesores);
		//print_r($deportes);
		$div_asistentes = $this->load->view('lessons/detail_assistants', array('info' => $info, 'asistentes' => $asistentes, 'enable_add' => $enable_add), true);
		$div_lista_espera = $this->load->view('lessons/detail_waiting', array('info' => $info, 'espera' => $espera, 'enable_add' => $enable_add), true);
		$contenido = $this->load->view('lessons/detail', array('info' => $info, 'tarifas' => $tarifas, 'profesores' => $profesores, 'deportes' => $deportes, 'pistas' => $pistas, 'niveles' => $niveles, 'generos' => $generos, 'asistentes' => $div_asistentes, 'lista_espera' => $div_lista_espera, 'return_url' => $this->session->userdata('returnUrl')), true);

		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta .= '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $id), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			//echo $this->session->userdata('returnUrl');
		$this->load->view('main', $data);

	}






# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function new_daily_report($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);


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
		
		$info = $this->calendario->getCalendarByRange($id, 'array');
		$info['assistants'] = $this->lessons->get_AssitantsData(array('where' => "lessons_assistants.id_lesson = '".$id."' AND lessons_assistants.status < '7'"));

		if($this->input->post('action') && $this->input->post('action')=="save") {
//print("<pre>");print_r($info);print_r($_POST);//exit();
				$datos = array (
					'id_lesson' => $info['id'],
					'date_lesson' => $this->input->post('date_lesson'),
					'id_instructor' => $this->input->post('id_instructor'),
					'done' => $this->input->post('done'),
					'observations' => $this->input->post('observations')
				);
			//print("<pre>");print_r($_POST);exit();
				//exit($valido);
				if($this->lessons->addAssistance($datos) && $this->input->post('done')=="1") {
					foreach($_POST as $code => $value) {
						if(strstr($code, 'user_')) {
							$usuario = $value;
							if($this->input->post('assistant_'.$usuario)) $hecho = 1;
							else $hecho = 0;
							if($this->input->post('done')=='') $hecho = 0;	// Si la clase no se da.. se sobreescriben los checkbox de usuarios
							
							$obs = $this->input->post('obs_'.$usuario);
							$id_user = '';
							$user_desc =  '';
							$user_phone =  '';
							
							foreach($info['assistants'] as $id => $alumno) {
								if($alumno['id'] == $usuario) {
									$id_user = $alumno['id_user'];
									$user_desc =  $alumno['user_desc'];
									$user_phone =  $alumno['user_phone'];
								}
							}
							
							$data = array (
								'id_lesson' => $info['id'],
								'date_lesson' => $this->input->post('date_lesson'),
								'id_user' => $id_user,
								'user_desc' => $user_desc,
								'user_phone' => $user_phone,
								'asistance' => $hecho,
								'observations' => $obs,
							);
							
							$this->lessons->addAssistanceReport($data);
						}
					}
					
					$this->session->set_userdata('info_message', 'Informaci&oacute;n del curso actualizada correctamente');

				} else {
					if($this->input->post('done')!="1") $this->session->set_userdata('info_message', 'Informaci&oacute;n del curso actualizada correctamente');
					else $this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso. ');
				}

				
				redirect(site_url('lessons/assistance/'.$info['id']), 'Location'); 
				exit();
		}


		/*
		
		# Consultas para extraer la informacion del informe diario
		$info_tmp = $this->lessons->get_AssitanceData(array('where' => "lessons_assistance.id_lesson = '".$id."'"));
		print("<pre>");print_r($info_tmp);
		$info = $info_tmp[0];
		$info['assistants'] = $this->lessons->get_AssitantsReport($info['id_lesson'], $info['date_lesson']);
		
		# Consultas de la definición del curso
		$info = $this->calendario->getCalendarByRange($id, 'array');
		$info['assistants'] = $this->lessons->get_AssitantsData(array('where' => "lessons_assistants.id_lesson = '".$info['id']."' AND lessons_assistants.status < '7'"));
		*/
		//print("<pre>");print_r($info);
		$fechas_disponibles = $this->lessons->get_LessonDates($info['id'], 2, 2);
		$profesores = $this->usuario->getActiveUsersArray('', 'users.group_id=5');
		//print("<pre>");print_r($info);//exit();
		//print("<pre>");print_r($info);exit();
		//print("<pre>".$this->reservas->get_max_booking_date());
		//print_r($asistentes);
		//print_r($info);
		//print_r($profesores);
		//print_r($deportes);
		$contenido = $this->load->view('lessons/new_daily_report', array('info' => $info, 'fechas_disponibles' => $fechas_disponibles, 'profesores' => $profesores), true);

		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		//$extra_meta .= '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $info['id']), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}






# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function detail_daily_report($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);


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
		$info_tmp = $this->lessons->get_AssitanceData(array('where' => "lessons_assistance.id = '".$id."'"));
		$info = $info_tmp[0];
		//$info = $this->calendario->getCalendarByRange($id, 'array');
		$info['assistants'] = $this->lessons->get_AssitantsReport($info['id_lesson'], $info['date_lesson']);


		if($this->input->post('action') && $this->input->post('action')=="save") {
			//print("<pre>");print_r($info);print_r($_POST);exit();

				$datos = array (
					'id' => $info['id'],
					'id_lesson' => $info['id_lesson'],
					'date_lesson' => $info['date_lesson'],
					'id_instructor' => $this->input->post('id_instructor'),
					'done' => $this->input->post('done'),
					'observations' => $this->input->post('observations')
				);
				if($this->input->post('recovered')=="1") {
					$datos['done'] = $this->input->post('recovered');
					$datos['recovered'] = $this->input->post('recovered');
					$datos['recovered_date'] = date($this->config->item('date_db_format'), $this->input->post('recovered_date'));
					$datos['recovered_obs'] = $this->input->post('recovered_obs');
				} else {
					$datos['recovered'] = '0';
					$datos['recovered_date'] = '';
					$datos['recovered_obs'] = '';
				}
			//print("<pre>");print_r($datos);//exit();
				//exit($valido);
				if($this->lessons->updateAssistance($info['id'], $datos) && $this->input->post('done')=="1") {
					foreach($_POST as $code => $value) {
						if(strstr($code, 'user_')) {
							$usuario = $value;
							if($this->input->post('assistant_'.$usuario)) $hecho = 1;
							else $hecho = 0;
							if($this->input->post('done')=='') $hecho = 0;	// Si la clase no se da.. se sobreescriben los checkbox de usuarios
							
							$obs = $this->input->post('obs_'.$usuario);
							$id_user = '';
							$user_desc =  '';
							$user_phone =  '';
							
							foreach($info['assistants'] as $id => $alumno) {
								if($alumno['id'] == $usuario) {
									$id_user = $alumno['id_user'];
									$user_desc =  $alumno['user_desc'];
									$user_phone =  $alumno['user_phone'];
								}
							}
							
							$data = array (
								'id_lesson' => $info['id_lesson'],
								'date_lesson' => $info['date_lesson'],
								'id_user' => $id_user,
								'user_desc' => $user_desc,
								'user_phone' => $user_phone,
								'asistance' => $hecho,
								'observations' => $obs,
							);
							
							$this->lessons->updateAssistanceReport($data);
						}
					}
					
					$this->session->set_userdata('info_message', 'Informaci&oacute;n del curso actualizada correctamente');

				} else {
					$this->lessons->deleteAssistance($info['id_lesson'], $info['date_lesson']);
					$this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso. ');
				}

				
				redirect(site_url('lessons/assistance/'.$info['id_lesson']), 'Location'); 
				exit();
		}


		
		//print("<pre>");print_r($info);
		//$editable = FALSE;
		if(stristr($info['admin_check'], 's')) $editable = FALSE;
		else $editable = TRUE;
		//$recovery = FALSE;
		
		if($info['recovered']) $editable = FALSE;
		
		/*
		
		# Consultas para extraer la informacion del informe diario
		$info_tmp = $this->lessons->get_AssitanceData(array('where' => "lessons_assistance.id_lesson = '".$id."'"));
		print("<pre>");print_r($info_tmp);
		$info = $info_tmp[0];
		$info['assistants'] = $this->lessons->get_AssitantsReport($info['id_lesson'], $info['date_lesson']);
		
		# Consultas de la definición del curso
		$info = $this->calendario->getCalendarByRange($id, 'array');
		$info['assistants'] = $this->lessons->get_AssitantsData(array('where' => "lessons_assistants.id_lesson = '".$info['id']."' AND lessons_assistants.status < '7'"));
		*/
		//print("<pre>");print_r($info);exit();
		//print("<pre>".$this->reservas->get_max_booking_date());
		//print_r($asistentes);
		//print_r($info);
		//print_r($deportes);
		$profesores = $this->usuario->getActiveUsersArray('', 'users.group_id=5');
		//print_r($profesores);
		$contenido = $this->load->view('lessons/detail_daily_report', array('info' => $info, 'profesores' => $profesores, 'editable' => $editable), true);

		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta .= '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $info['id_lesson']), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}






# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla para marcar un día de clase como recuperado ... la que graba es detail_daily_report
# -------------------------------------------------------------------
	function recover_daily_report($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);


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
		$info_tmp = $this->lessons->get_AssitanceData(array('where' => "lessons_assistance.id = '".$id."'"));
		$info = $info_tmp[0];
		if(stristr($info['done'], 's')) {
			$this->session->set_userdata('error_message', 'Clase impartida. No es necesario definir una clase de recuperaci&oacute;n.');
			//exit();
			redirect(site_url('lessons/assistance/'.$info['id_lesson']), 'Location'); 
			exit();
		}
		//$info = $this->calendario->getCalendarByRange($id, 'array');
		//$info['assistants'] = $this->lessons->get_AssitantsReport($info['id_lesson'], $info['date_lesson']);
		$info['assistants'] = $this->lessons->get_AssitantsData(array('where' => "lessons_assistants.id_lesson = '".$info['id_lesson']."' AND lessons_assistants.status < '7'"));

		if($this->input->post('action') && $this->input->post('action')=="save") {
			/*
			print("<pre>");print_r($info);print_r($_POST);
			echo date($this->config->item('date_db_format'), strtotime($this->input->post('recovered_date')));
			exit();
			*/

				$datos = array (
					'id' => $info['id'],
					'id_lesson' => $info['id_lesson'],
					'date_lesson' => $info['date_lesson'],
					'id_instructor' => $this->input->post('id_instructor'),
					'done' => $this->input->post('done'),
					'observations' => $this->input->post('observations')
				);
				if($this->input->post('recovered')=="1") {
					$datos['done'] = $this->input->post('recovered');
					$datos['recovered'] = $this->input->post('recovered');
					$datos['recovered_date'] = date($this->config->item('date_db_format'), strtotime($this->input->post('recovered_date')));
					$datos['recovered_obs'] = $this->input->post('recovered_obs');
				} else {
					$datos['recovered'] = '0';
					$datos['recovered_date'] = '';
					$datos['recovered_obs'] = '';
				}
			//print("<pre>");print_r($datos);//exit();
				//exit($valido);
				if($this->lessons->updateAssistance($info['id'], $datos)) {
					
					#reseteo valores de los alumnos para ese dia
					$this->lessons->deleteAssistanceReport($info['id_lesson'], $info['date_lesson']);
					
					foreach($_POST as $code => $value) {
						if(strstr($code, 'user_')) {
							$usuario = $value;
							if($this->input->post('assistant_'.$usuario)) $hecho = 1;
							else $hecho = 0;
							if($this->input->post('done')=='') $hecho = 0;	// Si la clase no se da.. se sobreescriben los checkbox de usuarios
							
							$obs = $this->input->post('obs_'.$usuario);
							$id_user = '';
							$user_desc =  '';
							$user_phone =  '';
							
							foreach($info['assistants'] as $id => $alumno) {
								if($alumno['id'] == $usuario) {
									$id_user = $alumno['id_user'];
									$user_desc =  $alumno['user_desc'];
									$user_phone =  $alumno['user_phone'];
								}
							}
							
							$data = array (
								'id_lesson' => $info['id_lesson'],
								'date_lesson' => $info['date_lesson'],
								'id_user' => $id_user,
								'user_desc' => $user_desc,
								'user_phone' => $user_phone,
								'asistance' => $hecho,
								'observations' => $obs,
							);
							
							$this->lessons->addAssistanceReport($data);
						}
					}
					
					$this->session->set_userdata('info_message', 'Informaci&oacute;n del curso actualizada correctamente');

				} else {
					$this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso. ');
				}

				
				redirect(site_url('lessons/assistance/'.$info['id_lesson']), 'Location'); 
				exit();
		}


		
		//print("<pre>");print_r($info);
		//$editable = FALSE;
		$editable = TRUE;
		$recovery = TRUE;

		$profesores = $this->usuario->getActiveUsersArray('', 'users.group_id=5');
		//print_r($profesores);
		$contenido = $this->load->view('lessons/recover_daily_report', array('info' => $info, 'profesores' => $profesores, 'editable' => $editable, 'recovery' => $recovery), true);

		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta .= '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $info['id_lesson']), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

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




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function datafeed_detail($id = NULL)
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

    $ret = $this->calendario->listCalendar($this->input->post('showdate'), $this->input->post('viewtype'), $id);
		        //$ret = $this->calendario->listCalendar('11/3/2010', 'week');

		$this->output->set_header($this->config->item('json_header'));
		echo json_encode($ret); 
		}





# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function datafeed_get($method, $id = NULL, $showdate = NULL, $viewtype = NULL, $timezone = NULL)
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
		        $ret = $this->calendario->listCalendar(str_replace('-', '/', $showdate), $viewtype);
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





# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function save_assistance($leccion, $id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);


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


		if($this->input->post('action')) {
			if($this->input->post('action')=="admin_obs") {
			//print("<pre>");print_r($_POST);exit();
				$obs = $this->input->post('admin_obs');
				if($this->lessons->updateAdminCheck($leccion, $id, $obs)) $this->session->set_userdata('info_message', 'Actualizaci&oacute;n de la informacion realizada');
				else $this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n de la informacion');
			}
		}
		
		
		redirect(site_url('lessons/assistance/'.$leccion), 'Location'); 
		exit();



	}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function detail_save_____($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}


		
		$this->lessons->id = $id;
		$this->lessons->description = $this->input->post('description');
		$this->lessons->active = ($this->input->post('active'))? 1 : 0;
		$this->lessons->weekday = $this->input->post('weekday');
		$this->lessons->start_time = $this->input->post('start_time');
		$this->lessons->end_time = $this->input->post('end_time');
		$this->lessons->start_date = date($this->config->item('date_db_format'), strtotime($this->input->post('start_date')));
		$this->lessons->end_date = date($this->config->item('date_db_format'), strtotime($this->input->post('end_date')));
		$this->lessons->id_sport = $this->input->post('id_sport');
		$this->lessons->id_court = $this->input->post('id_court');
		$this->lessons->id_instructor = $this->input->post('id_instructor');
		$this->lessons->max_vacancies = $this->input->post('max_vacancies');
 		$this->lessons->monthly_payment_day = $this->input->post('monthly_payment_day');
 		$this->lessons->level = $this->input->post('level');
 		$this->lessons->gender = $this->input->post('gender');
		
		$maxima_fecha = $this->reservas->get_max_booking_date();
		$fecha_actual = $this->lessons->start_date;
		//echo $this->lessons->weekday."<br>";
		//echo $fecha_actual."<br>";
		//echo date( 'w', strtotime($fecha_actual))."<br>";
		while( date( 'w', strtotime($fecha_actual)) != $this->lessons->weekday) {
			$fecha_actual = date( $this->config->item('date_db_format'), strtotime($fecha_actual.' +1days'));
			//echo $fecha_actual."<br>";
		}
		//echo $fecha_actual.'---------------------->';
		$fecha_final = $this->lessons->end_date;

		$hora_inicio = $this->lessons->start_time;
		$hora_fin = $this->lessons->end_time;

		$pista = $this->input->post('id_court');

		//echo $maxima_fecha.'<br>';
		//print($hora_inicio.$hora_fin.$this->lessons->weekday."<pre>");
		$valido = 1; $fecha_problematica = '';
		while($fecha_actual <= $maxima_fecha && $fecha_actual <= $fecha_final) {
			//echo $fecha_actual."<br>";
			$hora_actual = date( $this->config->item('hour_db_format'), strtotime($hora_inicio));
			
			$this->app_common->get_court_availability($pista, $fecha_actual);
			while($hora_actual <= $hora_fin) {
				foreach($this->reservas->availability as $disp) {
					//echo $disp[0].' - '.$hora_actual.' - '.$disp[1]."<br>";
					if($disp[0] == $hora_actual && $disp[1] == '1') {
						$valido = 0; 
						$fecha_problematica = $fecha_actual;
						//echo '<b>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br>asdasdasd<br></b>';
					}
				}
				
				$hora_actual = date( $this->config->item('hour_db_format'), strtotime($hora_actual.' +30minutes'));
			}
			//print_r($this->reservas->availability);
			$fecha_actual = date( $this->config->item('date_db_format'), strtotime($fecha_actual.' +7days'));
		}
		//exit($valido);
		if($valido) {
			if($this->lessons->updateLessons($id)) $this->session->set_userdata('info_message', 'Informaci&oacute;n del curso actualizada correctamente');
			else $this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso');
		} else {
			$this->session->set_userdata('error_message', 'Error en la actualizaci&oacute;n del curso. Alguna de las reservas necesarias (al menos para el d&iacute;a '.date( $this->config->item('reserve_date_filter_format'), strtotime($fecha_problematica)).') est&aacute; reservada con otro motivo.');
			redirect(site_url('lessons/detail/'.$id), 'Location'); 
			exit();
		}

 		$this->lessons->signin = $this->input->post('signin');
 		$this->lessons->monthly = $this->input->post('monthly');
		$this->lessons->updateLessonsPricesSimple($id);
		
		redirect(site_url('lessons/detail/'.$id), 'Location'); 
		exit();

	}



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function add_assistant($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('calendario');

//print_r($_POST);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
				$this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
				redirect(site_url(), 'Location'); 
				exit();
			  }
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$info = $this->calendario->getCalendarByRange($id);
		//print("<pre>");print_r($info);
		if($this->input->post('action') && $this->input->post('action')=="add") {
			$this->lessons->id = $id;
			//print_r($_POST);//exit();
			$id_user = $this->input->post('id_user');
			
			# Especificación de fecha de alta para los pagos
			$start_date = $this->input->post('start_date');
			if(isset($start_date) && $start_date !='') {
				$date_temp = explode('-', $start_date);
				if(is_array($date_temp)) $start_date = $date_temp[2].'-'.$date_temp[1].'-'.$date_temp[0];	// Convierto dd-mm-yyyy en yyyy--mm-dd
			} else $start_date = date( $this->config->item('date_db_format'));
			$discount = $this->input->post('discount');
			//echo $discount.'<br>';
			if($discount > 100) $discount = 100;
			if(!isset($discount) || $discount=='' || $discount < 0) $discount = 0;
			//echo $discount.'<br>';exit();
			
			if(!isset($id_user) || $id_user=='') $id_user = 0;
			$user_desc = $this->input->post('user_desc');
			$user_phone = $this->input->post('user_phone');
			if($id_user) {
				$datos_usuario = $this->usuario->get_user($id_user);
				$user_desc = trim($datos_usuario['user_name'].' '.$datos_usuario['user_lastname']);
				$user_phone = $datos_usuario['user_phone'];
			}
			//$paymentway = $this->input->post('paymentway');
			$status = "";
			/*
			if($mode == "waiting") $status = 7;
			elseif($mode == "annual") $status = 1;
			elseif($mode == "unique") $status = 3;
			else $status = 2;
			*/
			$status = 2;
			if($info->current_vacancies <= 0) $status = 7;
			
			if($this->calendario->checkAssistant($id, $id_user, $user_desc)) {
				$this->session->set_userdata('error_message', 'El usuario ya est&aacute; dado de alta en el curso.');
				redirect(site_url('lessons/assistants/'.$id), 'Location'); 
				exit();				
			}
			
			# Si es para la lista de espera o si la cuota de alta es 0 o si, siendo otra, viene con la acción de 'save', osea, ya pagada.. 
				if($this->lessons->addAssistant($id, $status, $id_user, $user_desc, $user_phone, $discount, $start_date)) {
					$this->session->set_userdata('info_message', 'Usuario a&ntilde;adido');
					
					# Si el curso no tiene cuota de alta, lo marco como con el alta pagada
					if($info->signin == 0) {
						if(!isset($start_date)) $start_date = date($this->config->item('log_date_format'));
						$this->lessons->signAssitant($id, NULL, $id_user, $user_desc, $start_date);
						//exit("aaa");
					} 
					//else exit('bbb');
				}
				else $this->session->set_userdata('error_message', 'Error a&ntilde;adiendo al usuario');
				
				/*
				if(floatval($info->signin) > 0) {
					$this->load->model('Payment_model', 'pagos', TRUE);
					$this->pagos->id_type=2; //Clases y cursos
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction='l-'.$id.'-'.$id_user.'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
					$this->pagos->id_user=$id_user;
					$this->pagos->desc_user=$user_desc;
					$this->pagos->id_paymentway=$paymentway;
					$this->pagos->status=9;
					$this->pagos->quantity=$info->signin;
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Alta en curso '.$info->description;
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					$this->pagos->setPayment();
				}
				*/
				redirect(site_url('lessons/assistants/'.$id), 'Location'); 
				exit();
			// Fin del IF que registra el alta
		}

		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array('id' => $id), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('lessons/new_assistant', array('id' => $id), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

	}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function sign_assistant($id, $user, $method)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');

//print_r($_POST);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
      }
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$info = $this->calendario->getCalendarByRange($id);
		//print("<pre>");print_r($info);
			$this->lessons->id = $id;
			//print_r($_POST);echo date($this->config->item('log_date_format'), strtotime($sign_date_tmp));exit();
			$id_user = $this->input->post('id_user');
			if(!isset($id_user) || $id_user=='') $id_user = 0;
			$user_desc = $this->input->post('user_desc');
			$user_phone = $this->input->post('user_phone');
			//$paymentway = $this->input->post('paymentway');
			$status = "";
			/*
			if($mode == "waiting") $status = 7;
			elseif($mode == "annual") $status = 1;
			elseif($mode == "unique") $status = 3;
			else $status = 2;
			*/
			$status = 2;
			if($info->current_vacancies <= '0') $status = 7;
			$usuario = $this->calendario->getAssistantInfo($user);
			
			if($usuario->sign_date != '') {
				$this->session->set_userdata('error_message', 'El usuario ya pag&oacute; la cuota de alta del curso.');
				redirect(site_url('lessons/assistants/'.$id), 'Location'); 
				exit();				
			}
			
			$sign_date_tmp = str_replace('/', '-', $this->input->post('sign_date'));
			$sign_date = date($this->config->item('log_date_format'), strtotime($sign_date_tmp));	// Fecha de alta
			$this->lessons->signAssitant($id, $user, NULL, NULL, $sign_date);

			if(floatval($info->signin) > 0) {
				
				
				if($usuario->id_user != '' && $usuario->id_user != 0) $user_desc = $usuario->first_name.' '.$usuario->last_name;
				else $user_desc = $usuario->user_desc;
				
				$estado = 9;
				if($method == 4) $estado = 2;
				$this->load->model('Payment_model', 'pagos', TRUE);
				$this->pagos->id_type=2; //Clases y cursos
				$this->pagos->id_element=$this->session->userdata('session_id');
				$this->pagos->id_transaction='l-'.$id.'-'.$usuario->id_user.'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
				$this->pagos->id_user=$usuario->id_user;
				$this->pagos->desc_user=$user_desc;
				$this->pagos->id_paymentway=$method;
				$this->pagos->status=$estado;
				$this->pagos->quantity=$info->signin;
				$this->pagos->datetime=date($this->config->item('log_date_format'));
				$this->pagos->description="Alta en curso '".$info->description."'";
				$this->pagos->create_user=$this->session->userdata('user_id');
				$this->pagos->create_time=date($this->config->item('log_date_format'));
				
				$this->pagos->setPayment();
			}
				redirect(site_url('lessons/assistants/'.$id), 'Location'); 
				exit();
			// Fin del IF que registra el alta
		


	}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function subscribe_assistant($curso, $id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
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


		$assistant=$this->calendario->getAssistantInfo($id);
		$info = $this->calendario->getCalendarByRange($assistant->id_lesson);
		$status = 2;
		print("<pre>");print_r($assistant);print_r($info);
	//exit();
		
		if($info->current_vacancies > 0) {
				if($this->lessons->subscribeAssitant($curso, $id)) {
					$this->session->set_userdata('info_message', 'Usuario a&ntilde;adido.');
					
					# Si el curso no tiene cuota de alta, lo marco como con el alta pagada
					if($info->signin == 0) $this->lessons->signAssitant($id, NULL, $assistant->id_user, $assistant->user_desc);
					redirect(site_url('lessons/assistants/'.$assistant->id_lesson), 'Location'); 
					exit();
				}
				else $this->session->set_userdata('error_message', 'Error a&ntilde;adiendo al usuario');	
				redirect(site_url('lessons/waiting/'.$assistant->id_lesson), 'Location'); 
				exit();
				
						
		} else {
			$this->session->set_userdata('error_message', 'Curso actualmente lleno.');
			redirect(site_url('lessons/waiting/'.$assistant->id_lesson), 'Location'); 
			exit();
			
		}
		
		exit();

		

	}





# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function asistant_payment($curso, $id, $paymentway = NULL, $action = NULL, $quantity = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'users', TRUE);
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

		
		print('<pre>');print_r($_POST); //exit();
		
		$assistant=$this->calendario->getAssistantInfo($id);
		//print('<pre>');print_r($assistant);
		$assistant_info = $this->users->get_user($assistant->id_user);
		//print('<pre>');print_r($assistant_info);
		$info = $this->calendario->getCalendarByRange($assistant->id_lesson);
		//print('<pre>');print_r($info); //exit();
		
		if(!isset($quantity) || $quantity == '') $quantity = 1;	// Mensualidades por defecto a pagar
		
		if($info->signin != 0  && $assistant->sign_date == '') {
			$this->session->set_userdata('error_message', 'Debe primero pagar la cuota de alta');		
			redirect(site_url('lessons/assistants/'.$curso), 'Location'); 
			exit();			
		}
		//exit();
		/*
		if(!isset($assistant->last_day_payed) || $assistant->last_day_payed=="") {
			if(date('d', strtotime($assistant->last_day_payed)) >= $info->monthly_payment_day) $last_payd_date = date('Y-m-'.$info->monthly_payment_day, strtotime('+1 month'));
			else $last_payd_date = date('Y-m-'.$info->monthly_payment_day);
		} else $last_payd_date = date('Y-m-'.$info->monthly_payment_day, strtotime($assistant->last_day_payed.'+1 month'));
		*/
		//echo $assistant->last_day_payed."<br>";
		if($this->input->post('payd_date_tmp') != '') {
			//echo '--'.$this->input->post('payd_date_tmp');
			$last_payd_date = date($this->config->item('log_date_format'), strtotime($this->input->post('payd_date_tmp')));
			//echo '<br>'.$last_payd_date;
			//exit();
		
		} else {
			//exit('no');
			if(!isset($assistant->last_day_payed) || $assistant->last_day_payed=="") {
				$ultima_fecha = $assistant->sign_date;
				$dia = date('d', strtotime($ultima_fecha));
				if($dia < $info->monthly_payment_day) {
					$trozos = explode('-', $ultima_fecha);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$info->monthly_payment_day;
				} elseif($dia > $info->monthly_payment_day) {
					$fecha_siguiente = date($this->config->item('log_date_format'), strtotime($assistant->sign_date.' +'.$info->price_duration.' '.$this->pagos->getFrequencyCommand($info->frequency)));
					$trozos = explode('-', $fecha_siguiente);
					$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$info->monthly_payment_day;
				} else {
					$last_payd_date = date($this->config->item('log_date_format'), strtotime($assistant->sign_date.' +'.$info->price_duration.' '.$this->pagos->getFrequencyCommand($info->frequency)));
				}
				
				//echo "1<br>";
			} else {
				$last_payd_date = date($this->config->item('log_date_format'), strtotime($assistant->last_day_payed.' +'.$info->price_duration.' '.$this->pagos->getFrequencyCommand($info->frequency)));
				//echo "2<br>";
			}
		}
		//echo $last_payd_date;
		//exit();
		# Si es para la lista de espera o si la cuota de alta es 0 o si, siendo otra, viene con la acción de 'save', osea, ya pagada.. 
		if(isset($quantity) && $quantity!=0 && $quantity!="" && isset($paymentway) && $paymentway!=0 && $paymentway!="") {
				if($this->input->post('payable_quota_tmp')!=0) {
					$pay_amount = $this->input->post('payable_quota_tmp') * $quantity;
					//echo '<br>'.$this->input->post('payable_quota_tmp');
				} else {
					$cuota = $this->app_common->getPriceValue($info->price, array('date' => $info->start_date, 'time' => $info->start_time, 'group' => $assistant_info['group_id']));
					$pay_amount_tmp = $cuota * $quantity;
					if($assistant->discount_type == '%') $pay_amount = $pay_amount_tmp - ($pay_amount_tmp * $assistant->discount / 100);
					else $pay_amount = $pay_amount_tmp - $assistant->discount;
				}
				//exit('<br>'.'cuota:'.$pay_amount);
				if($this->lessons->setMonthlyPayment($id, $last_payd_date)) $this->session->set_userdata('info_message', 'Pago hasta el '.date($this->config->item('reserve_date_filter_format'), strtotime($last_payd_date)).' realizado');
				else {
					$this->session->set_userdata('error_message', 'Error en el pago.');
					redirect(site_url('lessons/assistants/'.$assistant->id_lesson), 'Location'); 
					exit();
				}

				if($assistant->id_user != '' && $assistant->id_user != 0) $user_desc = $assistant->first_name.' '.$assistant->last_name;
				else $user_desc = $assistant->user_desc;
			
				$estado = 9;
				if($paymentway == 4) $estado = 2;

				
				$this->pagos->id_type=2; //Clases y cursos
				$this->pagos->id_element=$this->session->userdata('session_id');
				$this->pagos->id_transaction='l-'.$assistant->id_lesson.'-'.$assistant->id_user.'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
				$this->pagos->id_user=$assistant->id_user;
				$this->pagos->desc_user=$user_desc;
				$this->pagos->id_paymentway = $paymentway;
				$this->pagos->status=$estado;
				$this->pagos->quantity = $pay_amount;
				$this->pagos->datetime=date($this->config->item('log_date_format'));
				$this->pagos->description="Cuota del curso '".$info->description."', hasta el ".date($this->config->item('reserve_date_filter_format'), strtotime($last_payd_date));
				$this->pagos->create_user=$this->session->userdata('user_id');
				$this->pagos->create_time=date($this->config->item('log_date_format'));
				
				$this->pagos->setPayment();
			
			
				redirect(site_url('lessons/assistants/'.$assistant->id_lesson), 'Location'); 
				exit();
			} else {
				$this->session->set_userdata('error_message', 'Error en la informaci&oacute;n del pago.');
				redirect(site_url('lessons/assistants/'.$assistant->id_lesson), 'Location'); 
				exit();
			}
		
		// Fin del IF que registra el alta
		
		# Definición de barra de menus
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		
		# Pantalla de pago de la cuota de alta
		$contenido = $this->load->view('lessons/monthly_payment', array('info' => $info, 'id' =>  $id, 'id_user' => $assistant->id_user, 'user_desc' => $assistant->user_desc, 'user_phone' => $assistant->user_phone, 'funcion_destino' => 'asistant_payment', 'last_day_payed' => $assistant->last_day_payed, 'next_day_payed' => $last_payd_date), true);
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),
			'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);
		

	}



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# 
# -------------------------------------------------------------------
	function unsubscribe_assistant($lesson, $id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
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
		$motivo_baja = $this->input->post('unsubscription_reason');
		//echo $motivo_baja; print_r($_POST); exit();
		if($this->lessons->unsubscribeAssitant($lesson, $id, $motivo_baja)) $this->session->set_userdata('info_message', 'Usuario eliminado');
		else $this->session->set_userdata('error_message', 'Error eliminando al usuario');
		
		redirect(site_url('lessons/assistants/'.$lesson), 'Location'); 
		exit();
		

	}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Función que nos redirige al detalle del curso de un 'assistant' dado por su 'id'
# -------------------------------------------------------------------
	function assistant_redirect($id_assistant)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('lessons_admin_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
      }
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$usuario = $this->calendario->getAssistantInfo($id_assistant);
		redirect(site_url('lessons/detail/'.$usuario->id_lesson), 'Location'); 
		exit();				



	}


	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que muestra un resumen de un curso
# -------------------------------------------------------------------

	function tooltip_info($id = NULL)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');


		if(isset($id) && trim($id) != "") {
			
			$info=$this->calendario->getCalendarByRange($id);
			//print_r($info);
			//exit();
			if(isset($info)) $this->load->view('lessons/tooltip_info', array('info' => $info, 'buttons' => FALSE));
			else echo 'Informaci&oacute;n no disponible';
			//$main_content.='<p>Partido en '.$this->config->item('club_name').' el '.$fecha.' - '.$this->config->item('app_name').' en la pista '.$info['reserva'].'.</p>';
			
		}

}


	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que muestra un resumen de info de asistente a un curso
# -------------------------------------------------------------------

	function tooltip_assistant_info($id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('calendario');


		if(isset($id) && trim($id) != "") {
			
			$assistant=$this->calendario->getAssistantInfo($id);
			$info=$this->calendario->getCalendarByRange($assistant->id_lesson);
			//print_r($info);
			//exit();
			if(isset($info)) $this->load->view('lessons/tooltip_assistant_info', array('asistente' => $assistant, 'info' => $info));
			else echo 'Informaci&oacute;n no disponible';
			//$main_content.='<p>Partido en '.$this->config->item('club_name').' el '.$fecha.' - '.$this->config->item('app_name').' en la pista '.$info['reserva'].'.</p>';
			
		}

}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Redirijo al perfil de usuario del asistente a un curso seleccionado
# -------------------------------------------------------------------
	function asistant_info($curso, $id)
	{
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'users', TRUE);
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

		$assistant=$this->calendario->getAssistantInfo($id);
		//print_r($assistant);exit();
		//$this->session->set_userdata('returnUrl', site_url('lessons/assistants/'.$id));	
		if(isset($assistant) && is_object($assistant)) redirect(site_url('users/detail/'.$assistant->id_user), 'Location');
		else {
			$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
			redirect(site_url('lessons/assistants/'.$curso), 'Location'); 
		}
		exit();
	}


	
	

# -------------------------------------------------------------------
# Funcion que muestra el detalle del usuario
# -------------------------------------------------------------------
	function carnet($lesson, $code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->library('calendario');
		$return_url = 'lessons/assistants/'.$lesson;
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$clases_permission = $this->config->item('lessons_admin_permission');
			$carnet=$this->config->item('lessons_enable_lesson_idcard');	// Habilita la emisión de carnet de curso
			if(!$carnet || !$clases_permission ) { 
				$this->session->set_userdata('error_message', 'No tiene permisos para emitir carnets.');
				redirect(site_url($return_url), 'Location'); 
				exit();	
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url($return_url), 'Location'); 
			exit();
		}
		

			# recupero los datos del usuario
		$usuario=$this->calendario->getAssistantInfo($code_user);
		if (!isset($usuario) || count($usuario) <=0)
		{
			$this->session->set_userdata('error_message', 'Informacion de alumno no disponible o usuario inexistente.');
			redirect(site_url($return_url), 'Location'); 
			exit();
		}
		//print_r($assistant);exit();
		$array_result = $this->users->get_user($usuario->id_user);
		if (!isset($array_result) || count($array_result) <=0)
		{
			$this->session->set_userdata('error_message', 'Informacion de usuario no disponible o usuario inexistente.');
			redirect(site_url($return_url), 'Location'); 
			exit();
		}
		# recupero los datos del usuario

		

		
		//echo '<pre>';print_r($usuario); exit();
		
		# Gestión de carnet de socio
		$carnet_permission = $this->config->item('lessons_idcard_template_default');
		$carnet_enabled = FALSE;
		if(!isset($carnet_permission) || trim($carnet_permission) == '') {
			$this->session->set_userdata('error_message', 'Carnet no habilitado para este usuario.');
			redirect(site_url($return_url), 'Location'); 
			exit();
		}
		
		# Gestión de carnet de socio
		$curso_permission = $this->config->item('lessons_idcard_lessons_enabled');
		$carnet_enabled = FALSE;
		if(!isset($curso_permission) || !in_array($lesson, $curso_permission)) {
			$this->session->set_userdata('error_message', 'Carnet no habilitado para este curso.');
			redirect(site_url($return_url), 'Location'); 
			exit();
		}
   
		//print_r($array_result);exit();
		$imgPath = $this->config->item('root_path').'images/templates/'.$carnet_permission;
		$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
		$font = $this->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		if(!file_exists($imgPath) || !file_exists($font)) exit ('Fallo en la carga de las plantillas necesarias');
		if(!file_exists($imgStampPath)) $imgStampPath = $this->config->item('root_path').'images/avatar.jpg';
		//if(!file_exists($imgStampPath)) exit ('Foto del usuario no disponible');

		# Abro Avatar
		$size=getimagesize($imgStampPath);
		switch($size["mime"]){
			case "image/jpeg":
				$fotocarnet = imagecreatefromjpeg($imgStampPath); //jpeg file
			break;
			case "image/gif":
				$fotocarnet = imagecreatefromgif($imgStampPath); //gif file
		  break;
		  case "image/png":
			  $fotocarnet = imagecreatefrompng($imgStampPath); //png file
		  break;
		  default: 
			$fotocarnet=false;
		  break;
		}
		if(!$fotocarnet) exit ('Foto del usuario no disponible');
		
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


		
		$sx = imagesx($fotocarnet);
		$sy = imagesy($fotocarnet);
		$factor = $sx / 120;	// Marco el ancho que quiero que tenga la foto de carnet
		$dx = intval($sx / $factor);
		$dy = intval($sy / $factor);
		if($dy > 180) {
			# Si la imagen es desproporcionadamente alta, pongo el alto como referencia para redimensionar
			$factor = $sy / 180;	// Marco el alto maximo que quiero que tenga la foto de carnet
			$dx = intval($sx / $factor);
			$dy = intval($sy / $factor);
		}
		//echo '<br>'.$factor; echo '<br>'.$dx; echo '<br>'.$dy;
		$fotocarnet_thumb = imagecreatetruecolor($dx, $dy);
		imagecopyresized($fotocarnet_thumb, $fotocarnet, 0, 0, 0, 0, $dx, $dy, $sx, $sy);
		$ancho_fotocarnet_thumb = imagesx($fotocarnet_thumb);
		$alto_fotocarnet_thumb = imagesy($fotocarnet_thumb);

		// Set the margins for the stamp and get the height/width of the stamp image
		$marge_right = 30;
		$marge_bottom = 140;
		imagecopy($image, $fotocarnet_thumb, imagesx($image) - $ancho_fotocarnet_thumb - $marge_right, imagesy($image) - $alto_fotocarnet_thumb - $marge_bottom, 0, 0, $ancho_fotocarnet_thumb, $alto_fotocarnet_thumb);

		
		# Escribo los datos del usuario
		$white = imagecolorallocate($image, 255, 255, 255);
		$grey = imagecolorallocate($image, 128, 128, 128);
		$black = imagecolorallocate($image, 0, 0, 0);
		$fontSize = 18;

		// Add some shadow to the text
		$text_xpos = 45;
		//imagettftext($image, $fontSize, 0, $text_xpos+1, 81, $grey, $font, $array_result['user_lastname']);
		imagettftext($image, $fontSize, 0, $text_xpos, 80, $black, $font, $array_result['user_lastname']);
		//imagettftext($image, $fontSize, 0, $text_xpos+1, 106, $grey, $font, $array_result['user_name']);
		imagettftext($image, $fontSize-2, 0, $text_xpos, 105, $black, $font, $array_result['user_name']);
		//imagettftext($image, $fontSize, 0, $text_xpos+1, 131, $grey, $font, 'ID: '.$array_result['user_id']);
		imagettftext($image, $fontSize, 0, $text_xpos, 140, $black, $font, 'ID: '.$array_result['user_id']);
		

		# Genero el codigo QR con la información importante
		/*
		$ancho_qrcode = 100;
		$qrcode = imagecreatefrompng('http://chart.apis.google.com/chart?cht=qr&chs='.$ancho_qrcode.'x'.$ancho_qrcode.'&chl='.$array_result['user_id'].'|'.$array_result['group_id'].'|'.strtotime($array_result['last_payd_date']));
		$ancho_qrcode = $ancho_qrcode-20;	// Le quito el doble de lo que le quitaré del borde más abajo
		//$qrcode = imagecreatefrompng('chart100.png');
		$qr_marge_right = 150;
		$qr_marge_bottom = 50;
		imagecopy($image, $qrcode, imagesx($image) - $ancho_qrcode - $qr_marge_right, imagesy($image) - $ancho_qrcode - $qr_marge_bottom, 10, 10, $ancho_qrcode, $ancho_qrcode);
		*/

		# Genero el codigo de barras
		require($this->config->item('root_path').'system/libraries/barcode/BCGFontFile.php');
		require($this->config->item('root_path').'system/libraries/barcode/BCGColor.php');
		require($this->config->item('root_path').'system/libraries/barcode/BCGDrawing.php');
		require($this->config->item('root_path').'system/libraries/barcode/BCGcode128.barcode.php');
		 
		$font = $this->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		$font = new BCGFontFile($this->config->item('root_path').'system/fonts/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		 
		// Barcode Part
		$code = new BCGcode128();
		$code->setScale(1);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setStart(NULL);
		$code->setTilde(true);
		$code->setOffsetX(1);
		$code->setOffsetX(1);
		//$code->clearLabels();
		$code->parse($array_result['user_id'].'|'.$array_result['group_id'].'|'.strtotime($array_result['last_payd_date']));
		$tamaño_barcode = $code->getDimension(0, 0);
		$ancho_barcode = $tamaño_barcode[0];
		$alto_barcode = $tamaño_barcode[1];
		//exit('aa');
		$barcode = imagecreatetruecolor($ancho_barcode, $alto_barcode);
		$background_color = imagecolorallocate($barcode, 255, 255, 255);
		imagefill($barcode, 0, 0, $background_color);
		// Drawing Part
		$drawing = new BCGDrawing('', $color_white);
		$drawing->set_im($barcode);
		$drawing->setBarcode($code);
		$drawing->draw($barcode);
		//$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);		
		$marge_right = 290;
		$marge_bottom = 85;
		imagecopy($image, $barcode, imagesx($image) - $ancho_barcode - $marge_right, imagesy($image) - $alto_barcode - $marge_bottom, 0, 0, $ancho_barcode, $alto_barcode);

		header("Content-type: image/jpeg");
		header("Content-type: " . $mime);
		//header("Content-Length: " . $size);
		// NOTE: Possible header injection via $basename
		header("Content-Disposition: attachment; filename=" . $code_user .'.jpg');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		$image = imagerotate($image, 90, 0);
		imagejpeg($image, null, 100);
		// Liberar memoria
		imagedestroy($image);
		exit();
		
						/* pintar */
	

	}	
	
	
	
	
	
	
	

	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */