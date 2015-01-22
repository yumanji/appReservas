<?php

class Notifications extends Controller {

	function Notifications()
	{
		parent::Controller();	
	}
	
	function index()
	{
			
			if($this->redux_auth->logged_in()) {
				
				$menu=array('menu' => $this->app_common->get_menu_options());
				//print("<pre>");print_r($menu);print("</pre>");
				//$this->session->set_userdata('message',"asasassa");
				//print_r($this->session->all_userdata());
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
	
				//print_r($this->redux_auth->profile());
				$data['page']='notifications/home_admin';
				$profile=$this->redux_auth->profile();
				$data['profile']=$profile;
	      $this->load->view('main', $data);
				
			}
			else {
    		redirect(base_url(), 'location');
    		exit();
			}
								//print		($this->redux_auth->logged_in());		
	}


	
	#######################
	# Funcion que permite enviar emails a grupos de usuarios
	##################
	function new_mail()
	{
			
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}				
			
		if($this->input->post('action') && $this->input->post('action')=="send") {

			$this->load->library('comunicacion');
			
			$tipo_comunicacion = $this->input->post('comm_type');
			if(!isset($tipo_comunicacion) || $tipo_comunicacion == '') $tipo_comunicacion = '1';
			
			$registro = array(
	       'subject' => $this->input->post('subject'),
	       'from' => $this->config->item('email_from'),
	       'type' => 5,
	       //'destination_text' => $this->input->post('destination'),
	       'content' => $this->input->post('content'),
	       'active' => 1,
	       'create_user' => $this->session->userdata('user_id'),
	       'create_ip' => $this->session->userdata('ip_address'),
	       'create_time' => date(DATETIME_DB)
	    );
			
			
			if($this->comunicacion->send_general_notification($registro, $tipo_comunicacion)) {
				$this->session->set_userdata('info_message', 'Mensaje creado.');
			} else {
				$this->session->set_userdata('error_message', 'Error en la creacion del mensaje.');
			}
			
      redirect(site_url('notifications/new_mail'), 'Location'); 
      exit();
		}

		$this->load->library('ckeditor');
		$this->load->helper('ckeditor');
		
		//$menu=array('menu' => $this->app_common->get_menu_options());
		//print("<pre>");print_r($menu);print("</pre>");
		//$this->session->set_userdata('message',"asasassa");
		//print_r($this->session->all_userdata());
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		$editors_code = $this->load->view('editor/main', array('textbox_id' => 'content', 'style' => 'width:100%'), true);
		//$contenido = $this->load->view('notifications/new_mail', array('editors_code' => $editors_code), true);
		
		//print_r($this->redux_auth->profile());
		$data['editors_code']=$editors_code;
		$data['page']='notifications/new_mail';
		$profile=$this->redux_auth->profile();
		$data['profile']=$profile;
    $this->load->view('main', $data);
				

								//print		($this->redux_auth->logged_in());		
	}


	

	
	function new_single_mail()
	{
			
			if($this->redux_auth->logged_in()) {
				
				$this->load->library('ckeditor');
				$this->load->helper('ckeditor');
				
				$menu=array('menu' => $this->app_common->get_menu_options());
				//print("<pre>");print_r($menu);print("</pre>");
				//$this->session->set_userdata('message',"asasassa");
				//print_r($this->session->all_userdata());
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
				
				$editors_code = $this->load->view('editor/main', array('textbox_id' => 'content', 'style' => 'width:100%'), true);
				$contenido = $this->load->view('notifications/new_single_mail', array('editors_code' => $editors_code), true);
				
				//print_r($this->redux_auth->profile());
				$data['page']='notifications/new_single_mail';
				$profile=$this->redux_auth->profile();
				$data['profile']=$profile;
	      $this->load->view('main', $data);
				
			}
			else {
    		redirect(base_url(), 'location');
    		exit();
			}
								//print		($this->redux_auth->logged_in());		
	}


	
	
	function create_notification()
	{
			
		$this->load->model('Notifications_model', 'mails', TRUE);


		$registro = array(
       'subject' => 'Mail de prueba',
       'from' => $this->config->item('email_from'),
       'destination_text' => 'jorge.egalite@gmail.com',
       'content' => '<b>texto de prueba</b> enviado desde el "enviador autom&aacute;tico" y creado con un objeto apropiado..<br> ahi vaaaa... <i>adem&aacute;s con HTML y formatos</i>',
       'active' => 1,
       'create_user' => $this->session->userdata('user_id'),
       'create_ip' => $this->session->userdata('ip_address'),
       'create_time' => date(DATETIME_DB)
    );

		if($this->mails->createNotificationMessage($registro)) {
			echo "Mail creado guay";
		} else {
			echo "Mail creado con errores";
		}
		

		//print("<pre>");print_r($mails);print("</pre>");
		echo "Fin";
	}




	
	
	function create_single_notification()
	{
			
		$this->load->model('Notifications_model', 'mails', TRUE);


		$registro = array(
       'subject' => $this->input->post('subject'),
       'from' => $this->config->item('email_from'),
       'destination_text' => $this->input->post('destination'),
       'content' => $this->input->post('content'),
       'active' => 1,
       'create_user' => $this->session->userdata('user_id'),
       'create_ip' => $this->session->userdata('ip_address'),
       'create_time' => date(DATETIME_DB)
    );

		if($this->mails->createNotificationMessage($registro)) {
			//echo "Mail creado guay";
			$this->session->set_userdata('info_message', 'Mail creado satisfactoriamente y puesto en cola de espera para su env&iacute;o.');
  		redirect(site_url('notifications'), 'location');
  		exit();

		} else {
			$this->session->set_userdata('error_message', 'Error en la creación del email. Imposible su env&iacute;o.');
  		redirect(site_url('notifications'), 'location');
  		exit();
		}
		

		//print("<pre>");print_r($mails);print("</pre>");
		echo "Fin";
	}




	
	
	function create_single_notification_by_id()
	{
			
		$this->load->model('Notifications_model', 'mails', TRUE);


		$registro = array(
       'subject' => $this->input->post('subject'),
       'from' => $this->config->item('email_from'),
       'destination_id' => $this->input->post('id_destination'),
       'destination_text' => '',
       'content' => $this->input->post('content'),
       'active' => 1,
       'create_user' => $this->session->userdata('user_id'),
       'create_ip' => $this->session->userdata('ip_address'),
       'create_time' => date(DATETIME_DB)
    );

		if($this->mails->createNotificationMessage($registro)) {
			//echo "Mail creado guay";
			$this->session->set_userdata('info_message', 'Mail creado satisfactoriamente y puesto en cola de espera para su env&iacute;o.');
  		redirect(site_url('notifications'), 'location');
  		exit();

		} else {
			echo "Mail creado con errores";
			$this->session->set_userdata('error_message', 'Error en la creación del email. Imposible su env&iacute;o.');
  		redirect(site_url('notifications'), 'location');
  		exit();
		}
		

		//print("<pre>");print_r($mails);print("</pre>");
		echo "Fin";
	}


		
	
	function automated_send()
	{
			
		$this->load->model('Notifications_model', 'mails', TRUE);

		$mails = $this->mails->getNextMessages();	//Llamo a la función que devuelve el objeto resultset y directamente llamo al result_array() sobre él
		$timestamp = time();
		//print_r($mails); exit();
		# Recorro el array de mails
		foreach($mails as $mail) {
			
			# Traza de tiempo
			$mail['content'] .= '<br>&nbsp;<br>&nbsp;<br><h6>id: '.$mail['id'].' - start: '.$timestamp.' - launch: '.time().'</h6>';
			
			$this->email->clear();
			$this->email->set_newline("\r\n");
			//$this->email->from($mail['from'], $mail['from']); // Direccion de origen y nombre
			$this->email->from($this->config->item('email_from'), $this->config->item('email_from_desc')); // Direccion de origen y nombre
			$this->email->to($mail['destination_text']);
			if($this->config->item('reserve_admin_notification_cc')) $this->email->bcc($this->config->item('reserve_admin_notification_mail'));
			if($this->config->item('email_reply_to')) $this->email->reply_to($this->config->item('email_reply_to_address'));
			//$this->email->to('juanjo.nieto@gmail.com');
			$this->email->subject($mail['subject']);
			$this->email->message($mail['content']);
			if($this->email->send()) {
				//echo 'Email con id '.$mail['id'].' enviado satisfactoriamente.<br>';
				$this->mails->setMessageSended($mail['id']);	//Llamo a la función que devuelve el objeto resultset y directamente llamo al result_array() sobre él
			
			} else {
				//echo 'Error en envio de mail '.$mail['id'].'<br>';
				$this->mails->setMessageFailed($mail['id'],$this->email->print_debugger());
			}
			

		}
		//print("<pre>");print_r($mails);print("</pre>");
		//echo "Fin";
		exit();
	}





# -------------------------------------------------------------------
#  Listado general de los envios de mails usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all()
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('mails_visualization_permission');
			if(!$panel_permission[$user_group]) {
				$this->session->set_userdata('error_message','No tiene permisos para visualizar esa pagina.');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'type_desc',index:'type', width:10, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'destination_type_desc', index:'destination_type', width:10, align:'center', editable:true},
						   		{name:'status_description', index:'status', width:10, align:'center', editable:true},
						   		{name:'subject', index:'subject', width:30, align:'center', editable:true},
						   		{name:'active',index:'active', width:5, align:'center'},
						   		{name:'start_process', index:'start_process', width:13, align:'center'},
						   		{name:'end_process', index:'end_process', width:13, align:'center'},
							";
		$colnames = "'Id','Tipo','Destinatario','Estado Envio','Asunto','Activo','Inicio', 'Fin'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "notifications/jqgrid_list_all", 
				'title' => 'Listado de comunicaciones', 
				'default_orderfield' => 'id', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '990',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 990; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = '';//$this->load->view('reservas_gest/menu_lateral', '', true);
		
		
		//, 'enable_submenu' => $this->load->view('notifications/submenu_navegacion', array(), true)
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('notifications/list_all', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => $menu_lateral), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_flashdata('returnOkUrl', site_url('notifications/list_all'));
			$this->session->set_flashdata('returnKoUrl', site_url('notifications/list_all'));
			
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
		$this->load->model('Notifications_model', 'mails', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('mails_visualization_permission');
			if(!$panel_permission[$user_group]) {
				redirect(site_url(), 'Location'); 
				exit();				
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
		
		$req_param['where'] = $where;
		if(isset($add_params) && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		$data->page = $this->input->post( "page", TRUE );


		//print("<pre>");print_r($record_items);exit();
		$data->records = $this->mails->get_data_count($req_param,"all");
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->mails->get_data ($req_param, 'none');
		$data->rows = $records;
		//echo "<pre>"; print_r($data->rows);
		
		echo json_encode ($data );
		exit( 0 );
	}
	
	

	
# -------------------------------------------------------------------
# Funcion que muestra el detalle del usuario
# -------------------------------------------------------------------
	function detail($code)
	{
		$this->load->model('Notifications_model', 'mails', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('mails_visualization_permission');
			if(!$panel_permission[$user_group]) {
				redirect(site_url(), 'Location'); 
				exit();				
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		
		# Carga estatus para la vista
		$array_status = $this->mails->get_status();
		# Carga tipos para la vista
		$array_types = $this->mails->get_types();
		# Carga tipos de destinatarios para la vista
		$array_destination_types = $this->mails->get_destination_types();


			# Carga de datos para la vista
			$data=array(
				'meta' => $this->load->view('meta', '', true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('notifications/submenu_navegacion', array(), true)), true),
				'menu' => $this->load->view('menu', '', true),
				'navigation' => $this->load->view('navigation', '', true),
				'footer' => $this->load->view('footer', '', true),				
				//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
				//'form_name' => 'formDetail',
				'page' => 'notifications/detail',
				'code' => $code,
				'notification' => $this->mails->get_notification($code),
				'array_status' => $array_status,
				'array_types' => $array_types,
				'array_destination_types' => $array_destination_types,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

			$this->load->view('main', $data);
			/* pintar */
	

	}	
	




# -------------------------------------------------------------------
#  Listado general de los envios de mails usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function sended($codigo)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('mails_visualization_permission');
			if(!$panel_permission[$user_group]) {
				$this->session->set_userdata('error_message','No tiene permisos para visualizar esa pagina.');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$colmodel = "	{name:'id_notification',index:'id_notification', width:1, align:'center',hidden:true},
						   		{name:'type_desc', index:'zz_notification_type.description', width:10, align:'center', editable:true},
						   		{name:'subject', index:'subject', width:25, align:'center', editable:true},
						   		{name:'destination_type_desc', index:'zz_notification_dest_type.description', width:10, align:'center', editable:true},
						   		{name:'destination_text',index:'destination_text', width:25, align:'center'},
						   		{name:'active',index:'active', width:5, align:'center'},
						   		{name:'send',index:'send', width:5, align:'center'},
						   		{name:'start_process', index:'start_process', width:13, align:'center'},
						   		{name:'end_process', index:'end_process', width:13, align:'center'},
						   		{name:'error_count', index:'error_count', width:5, align:'center'},
							";
		$colnames = "'Id','Tipo','Asunto','Tipo Envio','Destinatario','Activo','Enviado','Inicio', 'Fin','Error'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "notifications/jqgrid_sended/".$codigo, 
				'title' => 'Listado de emails individuales', 
				'default_orderfield' => 'id', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '990',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 990; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = '';//$this->load->view('reservas_gest/menu_lateral', '', true);
		
		
		//, 'enable_submenu' => $this->load->view('notifications/submenu_navegacion', array(), true)
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('notifications/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('notifications/list_sended', array('grid_code' => $grid_code, 'enable_buttons' => FALSE, 'menu_lateral' => $menu_lateral), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_flashdata('returnOkUrl', site_url('notifications/list_all'));
			$this->session->set_flashdata('returnKoUrl', site_url('notifications/list_all'));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}



# -------------------------------------------------------------------
#  devuelve el listado de reservas para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_sended ($codigo)
	{
		$this->load->model('Notifications_model', 'mails', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$panel_permission = $this->config->item('mails_visualization_permission');
			if(!$panel_permission[$user_group]) {
				redirect(site_url(), 'Location'); 
				exit();				
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
		
		if($where !='') $where.=' AND ';
		$where.= 'id_notification = \''.$codigo.'\'';
		$req_param['where'] = $where;
		if(isset($add_params) && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		$data->page = $this->input->post( "page", TRUE );


		//print("<pre>");print_r($record_items);exit();
		$data->records = $this->mails->get_data_sended_count($req_param,"all");
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->mails->get_data_sended ($req_param, 'none');
		$data->rows = $records;
		//echo "<pre>"; print_r($data->rows);
		
		echo json_encode ($data );
		exit( 0 );
	}
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */