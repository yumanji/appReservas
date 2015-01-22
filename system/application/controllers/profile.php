<?php

class Users extends Controller {

	function Users()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');

	}
	
	function index()
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		# Grid de datos
		$colModel['id'] = array('ID',20,FALSE,'center',1);
		$colModel['nombre'] = array('Nombre',100,FALSE,'center',2);
		$colModel['email'] = array('Email',180,FALSE,'center',1, FALSE);
		$colModel['nivel'] = array('Nivel',70,FALSE,'center',1, FALSE);
		$colModel['phone'] = array('Telefono',60,FALSE,'center',0, FALSE);
		//$colModel['estado'] = array('Estado',30,FALSE,'center',0);
		$colModel['create_time'] = array('Fecha de alta',110,FALSE,'center',0);
		$colModel['changeStatus'] = array(' ',20, FALSE, 'center',0, FALSE, 'changeStatus');
		$colModel['changePassword'] = array(' ',20, FALSE, 'center',0, FALSE, 'changePassword');
		$colModel['detail'] = array(' ',20, FALSE, 'center',0, FALSE, 'detail');
		$colModel['prepaid'] = array(' ',20, FALSE, 'center',0, FALSE, 'prepaid');
		$colModel['reserved'] = array(' ',20, FALSE, 'center',0, FALSE, 'reserved');
		
		
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
		'title' => 'Listado de usuarios',
		'showTableToggleBtn' => false,
		'singleSelect' => true
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		
		//$buttons[] = array('Delete','delete','test');
		$buttons[] = array('separator');
		$buttons[] = array('Nuevo usuario','add','buttons');
		//$buttons[] = array('Select All','add','test');
		//$buttons[] = array($this->lang->line('deselect_all'),'delete','test');
		//$buttons[] = array('Anular','delete','test');
		
		
		# Filtros a pasar al grid
		$where_arr=array();
		//echo "AA".$this->input->post('last_name');
		$selected_name=$this->input->post('first_name');
		$selected_last_name=$this->input->post('last_name');
		$selected_phone=$this->input->post('phone');
		$selected_email=$this->input->post('email');
		$selected_group=$this->input->post('group');
		$selected_active=$this->input->post('active');
		if($selected_active!='' || $selected_active==='') $selected_active=$selected_active;
		else $selected_active = '1';
		$selected_code=$this->input->post('id');

		if($selected_name!="") array_push($where_arr, "meta.first_name like '%".$selected_name."%'");
		if($selected_last_name!="") array_push($where_arr, "meta.last_name like '%".$selected_last_name."%'");
		if($selected_phone!="") array_push($where_arr, "meta.phone like '%".$selected_phone."%'");
		if($selected_email!="") array_push($where_arr, "users.email like '%".$selected_email."%'");
		if($selected_group!="") array_push($where_arr, "users.group_id = '".$selected_group."'");
		if($selected_active!="") array_push($where_arr, "users.active = '".$selected_active."'");
		if($selected_code!="") array_push($where_arr, "users.id = '".$selected_code."'");
		
		$where=implode(' AND ', $where_arr);
		//echo $where;
		
		# Con esto guardo las condiciones extra en session.
		$this->session->set_flashdata('where', $where);


		//print_r($buttons);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url("/users/ajax_list_all"),$colModel,'first_name','asc',$gridParams,$buttons);
		
		
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'filters' => $this->load->view('gestion/filters', array('search_fields' => $this->simpleSearchFields()), true),
			'form' => 'frmGrid', 
			'enable_grid' => 1,
			'js_grid' => $grid_js,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		if($this->redux_auth->logged_in()) $data['page']='gestion/users_list_all';		
		
		# Carga de la vista principal
		$this->load->view('gestion', $data);
	}








	
# -------------------------------------------------------------------
# Funcion que cambia el estado del usuario
# -------------------------------------------------------------------
	function change_status($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		if($this->redux_auth->logged_in()) 
		{			

			$result = $this->users->change_status($code_user);
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
		
		redirect(site_url('users'), 'Location'); 
		exit();


	}
	




	
# -------------------------------------------------------------------
# Funcion que cambia el estado del usuario
# -------------------------------------------------------------------
	function reset_password($code_user, $password)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		if($this->redux_auth->logged_in()) 
		{			

			$result = $this->users->change_password_admin($code_user, $password);
			if ($result)
			{
				$this->session->set_userdata('info_message', 'Password actualizado.');
			}
			else 
			{
				$this->session->set_userdata('error_message', 'Password NO actualizado.');
				/* introducir mensaje */
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		//exit($this->session->userdata('error_message')."A");
		$this->index();


	}
	

	
# -------------------------------------------------------------------
# Funcion que muestra el detalle de un usuario
# -------------------------------------------------------------------
	function detail($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		if($this->redux_auth->logged_in()) 
		{			
			
			# Carga de niveles para la vista
			$array_groups = $this->users->get_groups();
			# Carga de países para la vista
			$array_country = $this->users->get_countries();
			# Carga de ciudades para la vista
			$array_province = $this->users->get_provinces();
			# Carga de niveles para la vista
			//$array_levels = $this->users->get_levels();
			# recupero los datos del usuario
			$array_result = $this->users->get_user($code_user);
			if ($array_result != null)
			{
				# Carga de datos para la vista
				$data=array(
					'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
					'menu' => $this->load->view('menu', $menu, true),
					'navigation' => $this->load->view('navigation', '', true),
					'footer' => $this->load->view('footer', '', true),				
					//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
					'form' => 'formDetail',
					'page' => 'gestion/user_detail',
					'code_user' => $code_user,
					'array_user' => $array_result,
					'array_groups' => $array_groups,
					'array_country' => $array_country,
					'array_province' => $array_province,
					//'array_levels' => $array_levels,
					//'enable_grid' => 1,
					//'js_grid' => $grid_js,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

				$this->load->view('gestion', $data);
				/* pintar */
			}
			else 
			{
				/* error */
				print "ERROR pasa por aqui";
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	
	

	
# -------------------------------------------------------------------
# Funcion que muestra el perfil de un usuario
# -------------------------------------------------------------------
	function profile($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		if($this->redux_auth->logged_in()) 
		{			
			
			# Carga de niveles para la vista
			$array_groups = $this->users->get_groups();
			# Carga de países para la vista
			$array_country = $this->users->get_countries();
			# Carga de ciudades para la vista
			$array_province = $this->users->get_provinces();
			# Carga de niveles para la vista
			//$array_levels = $this->users->get_levels();
			# recupero los datos del usuario
			$array_result = $this->users->get_user($code_user);
			if ($array_result != null)
			{
				# Carga de datos para la vista
				$data=array(
					'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
					'menu' => $this->load->view('menu', $menu, true),
					'navigation' => $this->load->view('navigation', '', true),
					'footer' => $this->load->view('footer', '', true),				
					//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
					'form' => 'formDetail',
					'page' => 'gestion/user_profile',
					'code_user' => $code_user,
					'array_user' => $array_result,
					'array_groups' => $array_groups,
					'array_country' => $array_country,
					'array_province' => $array_province,
					//'array_levels' => $array_levels,
					//'enable_grid' => 1,
					//'js_grid' => $grid_js,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

				$this->load->view('gestion', $data);
				/* pintar */
			}
			else 
			{
				/* error */
				print "ERROR pasa por aqui";
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	
	

	
# -------------------------------------------------------------------
# Funcion que permite añadir saldo prepago al usuario
# -------------------------------------------------------------------
	function add_prepaid($code_user, $command = NULL, $control = NULL)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		if($this->redux_auth->logged_in()) 
		{	
					
			$amount = $this->input->post('amount');
			/* DEFINIR AQUI LO QUE RECIBA POR POST (CANTIDAD, FORMA DE PAGO...) PARA PASAR PARÁMETROS A LA FUNCION .. */
			if(isset($command) && isset($control) && md5($code_user)==$control) {
				
				$this->users->addPrepaidMovement($code_user, $amount, '2', $command, date('U'));

					$this->pagos->id_type=2; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=date('U');
					$this->pagos->id_user=$code_user;
					$this->pagos->id_paymentway=$command;
					$this->pagos->status=9;
					$this->pagos->quantity=$amount;
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Recarga al bono prepago';
					$this->pagos->create_user=$profile->id;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					$this->pagos->setPayment();
									
				redirect(site_url('users/add_prepaid/'.$code_user), 'Location'); 
				exit();
				
			}
				# Carga de datos para la vista
				$data=array(
					'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
					'menu' => $this->load->view('menu', $menu, true),
					'navigation' => $this->load->view('navigation', '', true),
					'footer' => $this->load->view('footer', '', true),				
					//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
					'form' => 'formDetail',
					'page' => 'gestion/user_add_prepaid',
					'pre_ammount' => $this->users->getPrepaidCash($code_user),
					'user_desc' => $this->users->getUserDesc($code_user),
					'control' => md5($code_user),
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

				$this->load->view('gestion', $data);
				/* pintar */
	
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	
	
# -------------------------------------------------------------------
# Funcion que actualiza los datos de un usuario
# -------------------------------------------------------------------
	function edit_user()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		if($this->redux_auth->logged_in()) 
		{			
			$active_user_init = $this->input->post('user_active');
			$active_user = '0';
			if ($active_user_init == "on") $active_user = '1';
			
			$arrayUser = array(
								'id' => $this->input->post('id_user'),
								'first_name' => $this->input->post('first_name'),
								'last_name' => $this->input->post('last_name'),
								'group_id' => $this->input->post('group_id'),
								'group_description' => $this->input->post('group_description'),
								'email' => $this->input->post('email'),
								'active' => $active_user,
								'phone' => $this->input->post('user_phone'),
								'mobile_phone' => $this->input->post('mobile_phone'),
								'address' => $this->input->post('address'),
								'cp' => $this->input->post('cp'),
								'population' => $this->input->post('population'),
								'code_province' => $this->input->post('code_province'),
								'code_country' => $this->input->post('code_country'),
								'gender' => $this->input->post('gender'),
								'nif' => $this->input->post('nif'),
								'birth_date' => $this->input->post('birth_date'),
								'bank' => $this->input->post('bank'),
								'bank_office' => $this->input->post('bank_office'),
								'bank_dc' => $this->input->post('bank_dc'),
								'bank_account' => $this->input->post('bank_account'),
								'bank_titular' => $this->input->post('bank_titular'),
								'player_level' => $this->input->post('player_level')
								);
								
								//print("<pre>");print_r($arrayUser);exit();
			$result = $this->users->save_user($arrayUser);
			if ($result)
			{
				/* introducir mensaje */
				$this->session->set_userdata('error', 'El usuario se ha modificado correctamente');
			}
			else 
			{
				/* introducir mensaje */
				/*print 'Error';*/
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$this->session->set_userdata('info_message', 'Grabado satisfactoriamente');
		
		redirect(site_url('users/detail/'.$this->input->post('id_user')), 'Location');
		exit();
		//$this->detail($this->input->post('id_user'));
	}
	
# -------------------------------------------------------------------
# Funcion que muestra el formulario para crear un usuario
# -------------------------------------------------------------------
	function new_user()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		if($this->config->item('auto_password')) $auto_password = TRUE;
		else  $auto_password = FALSE;
		if($this->redux_auth->logged_in()) 
		{	
			# Carga de niveles para la vista
			$array_groups = $this->users->get_groups();
			# Carga de datos para la vista
			$data=array(
				'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
				'menu' => $this->load->view('menu', $menu, true),
				'navigation' => $this->load->view('navigation', '', true),
				'footer' => $this->load->view('footer', '', true),				
				//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
				'form' => 'formDetail',
				'page' => 'gestion/user_new',
				'auto_password' => $auto_password,
				'array_groups' => $array_groups,
				//'enable_grid' => 1,
				//'js_grid' => $grid_js,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

			$this->load->view('gestion', $data);
			/* pintar */		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	




# -------------------------------------------------------------------
# Funcion que responde a la peticion JSON del autocompletar del formulario de usuarios .. 
# -------------------------------------------------------------------

    function get_Names($filtro=NULL)
    {
    	//$q = $this->input->post('q',TRUE);
        //if (!$q) return;
        // form dropdown and myql get countries
        $this->load->model('redux_auth_model', 'users', TRUE);
        $array_users = $this->users->getActiveUsersArray($filtro);
 
        // go foreach
       /* foreach($users->result() as $user)
        {
            $items[$user->user_id] = $user->first_name;
        }*/
        $usuarios=array();
        foreach($array_users as $code => $value) if($code!="") array_push($usuarios, array('id' => $code, 'label' => $value, 'value' => $value));
 				//print("<pre>");print_r($array_users);print_r($usuarios);print("</pre>");
        //echo '{"tags":'. json_encode($array_users) .'}'; 
        echo json_encode($usuarios);
    }




# -------------------------------------------------------------------
# Funcion que responde a la peticion para saber el saldo
# -------------------------------------------------------------------

    function getPrepaidCash($user)
    {
      $this->load->model('redux_auth_model', 'users', TRUE);

      echo $this->users->getPrepaidCash($user);
    }


	
# -------------------------------------------------------------------
# Funcion que muestra el formulario para crear un usuario
# -------------------------------------------------------------------
	function create_user()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		if($this->redux_auth->logged_in()) 
		{			

			$active_user_init = $this->input->post('user_active');
			$active_user = '0';
			if ($active_user_init == "on") $active_user = '1';
			$arrayUser = array(
								'first_name' => $this->input->post('first_name'),
								'last_name' => $this->input->post('last_name'),
								'group_id' => $this->input->post('group_id'),
								'group_description' => $this->input->post('group_description'),
								'email' => $this->input->post('email'),
								'active' => $active_user,
								'phone' => $this->input->post('user_phone'),
								'password' => $this->input->post('password_user')																
								);
			$user_id = $this->users->new_user($arrayUser);
			if (isset($user_id))
			{
				/* introducir mensaje */
				//$this->detail($user_id);
				redirect(site_url('users/detail/'.$user_id), 'Location'); 
				exit();
			}
			else 
			{
				/* introducir mensaje */
				/*print 'Error';*/
				//$this->new_user();
				redirect(site_url('users/new_user'), 'Location'); 
				exit();
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	

	function get_users_list()
	{
		/*if (!isset($term))
		{
			$term = 'A';
		}*/
		$this->load->model('redux_auth_model', 'users', TRUE);
		//$this->output->set_header($this->config->item('json_header'));
		$return_array = $this->users->get_users_list();
		//print_r($return_array);
		//return $return_array;
		//$data=array('page' => 'gestion/list_users',
			//		'array_user' => $return_array,
				//	'term' => $term);
		echo json_encode($return_array);
		//$this->load->view('gestion/list_users', $data);
	}
		


# -------------------------------------------------------------------
# Funcion que devuelve listado de todos los usuarios
# -------------------------------------------------------------------
	function ajax_list_all()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);

		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('id_booking','date','intervalo','courts.name','zz_paymentway.description','zz_booking_status.description','price','id_user','user_desc','no_cost');
		
		$this->flexigrid->validate_post('first_name','asc',$valid_fields);
		
		$add_where=$this->session->flashdata('where');

		$records = $this->users->get_global_list($add_where);
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		 $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
		foreach ($records['records']->result() as $row)
		{
			//print_r($row);
			
			/* CAMBIO, PREGUNTAR A JUANJO */
				/*$active=1;
				if($row->activation_code!='') $active=0;*/
				// NUEVO CODIGO PEPE 
				$active = $row->active;
			/* FIN NUEVO CAMBIO */
				
			/*$active_out=img( array('src'=>'images/accept.png', "align"=>"absmiddle", "title"=>"Activo", "border"=>"0"));;
			if($row->activation_code!='') { $active_out=img( array('src'=>'images/close.png', "title"=>"Inactivo", "align"=>"absmiddle", "border"=>"0")); $active=0;}
			/
			
			/*
			$butt_array=array();
			array_push($butt_array, '<a href=\'#\'  onClick="javascript: alert(\'Modificar saldo prepago\');" ><img value="12" border=\'0\'  "title"="Gestion de prepago" src=\''.$this->config->item('base_url').'images/coins.png\'></a>');
			array_push($butt_array, '<a href=\'#\'  onClick="javascript: alert(\'Ver detalle del usuario\');" ><img value="12" border=\'0\'  "title"="Ver detalle" src=\''.$this->config->item('base_url').'images/magnifier.png\'></a>');
			$buttons=implode(' ', $butt_array);*/
			if($active) $button1= '<img id="activar"  "title"="Desactivar usuario" value="12" border=\'0\' src=\''.$this->config->item('base_url').'images/accept.png\'>';
			else 	 $button1=  '<img id="activar" border=\'0\'  "title"="Activar usuario" src=\''.$this->config->item('base_url').'images/close.png\'>';
			$button2= '<a href=\'#detail\'><img value="12" border=\'0\'  "title"="Ver detalle" src=\''.$this->config->item('base_url').'images/edit.gif\'></a>';
			$button3= '<img value="12" border=\'0\'  "title"="Gestion de prepago" src=\''.$this->config->item('base_url').'images/coins.png\'>';
			$button4= '<img value="12" border=\'0\'  "title"="Reservas activas" src=\''.$this->config->item('base_url').'images/history.gif\'>';
			$button5= '<img value="12" border=\'0\'  "title"="Cambiar password" src=\''.$this->config->item('base_url').'images/pass.gif\'>';
			
			$record_items[] =  array(
				$row->id,
				$row->id,
				$row->first_name.' '.$row->last_name,
				$row->email,
				$row->group_desc,
				$row->phone,
				$row->create_time,
				$button1,
				$button5,
				$button2,
				$button3,
				$button4
			);
			
		}
		//Print please
		//print("<pre>");print_r($record_items);print("</pre>");
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));		
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
			
			$selected_name=$this->input->post('first_name');
			$selected_last_name=$this->input->post('last_name');
			$selected_phone=$this->input->post('phone');
			$selected_group=$this->input->post('group');
			$selected_active=$this->input->post('active');
			if($selected_active!='' || $selected_active==='') $selected_active=$selected_active;
			else $selected_active = '1';
			$selected_email=$this->input->post('email');
			$selected_code=$this->input->post('id');
			
			# Filtro de usuario
			if(!isset($options['first_name']) || $options['first_name']=="1") {
				if(isset($options) && count($options)!=1) {
					$contenido=array('name' => 'first_name', 'desc' => 'Nombre', 'default' => $selected_name, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'first_name', 'type' => 'text', 'value' => $selected_name);
					array_push($filter_array, $contenido);
				}
			}
			
			# Filtro de apellido
			if(!isset($options['last_name']) || $options['last_name']=="1") {
				if(isset($options) && count($options)!=1) {
					$contenido=array('name' => 'last_name', 'desc' => 'Apellido', 'default' => $selected_last_name, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'last_name', 'type' => 'text', 'value' => $selected_last_name);
					array_push($filter_array, $contenido);
				}
			}
			
			# Filtro de email
			if(!isset($options['email']) || $options['email']=="1") {
				if(isset($options) && count($options)!=1) {
					$contenido=array('name' => 'email', 'desc' => 'Email', 'default' => $selected_email, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'email', 'type' => 'text', 'value' => $selected_email);
					array_push($filter_array, $contenido);
				}
			}
			
			# Filtro de telefono
			if(!isset($options['phone']) || $options['phone']=="1") {
				if(isset($options) && count($options)!=1) {
					$contenido=array('name' => 'phone', 'desc' => 'Telefono', 'default' => $selected_phone, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'phone', 'type' => 'text', 'value' => $selected_phone);
					array_push($filter_array, $contenido);
				}
			}
			
			# Filtro de nivel
			if(!isset($options['group']) || $options['group']=="1") {
				$options_=$this->users->get_groups();
				$options=array('' => '');
				foreach($options_ as $option) $options[$option['id']]=$option['description'];
				if(isset($options) && count($options)!=1) {
					$contenido=array('name' => 'group', 'desc' => 'Grupo', 'default' => $selected_group, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'group', 'type' => 'select', 'value' => $options);
					array_push($filter_array, $contenido);
				}
			}
			
			# Filtro de activo
			if(!isset($options['group']) || $options['group']=="1") {
				$options=array('' => '', '1'=> 'Activo', '0' => 'Inactivo');
				if(isset($options) && count($options)!=1) {
					$contenido=array('name' => 'active', 'desc' => 'Estado', 'default' => $selected_active, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'active', 'type' => 'select', 'value' => $options);
					array_push($filter_array, $contenido);
				}
			}
			
			
			return $filter_array;


		}



}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */