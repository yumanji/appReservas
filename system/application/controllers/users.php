<?php

class Users extends Controller {

	function Users()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');

	}
	
	function index( $option = NULL, $export = NULL)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');



		if($this->redux_auth->logged_in()) {
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			$profile=$this->redux_auth->profile();
			$user_group = $profile->group;
			if(!$profiles_visualization[$user_group]) {
				$this->session->set_userdata('error_message', 'Acceso a esa opci&oacute;n no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			}			
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Acceso a esa opci&oacute;n  no permitido');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$colmodel = "	{name:'id',index:'users.id', width:1, align:'center',hidden:true},
						   		{name:'nombre_completo',index:'meta.last_name', width:20, align:'center'},
						   		{name:'email', index:'users.email', width:25, align:'center'},
						   		{name:'group_desc',index:'groups.description', width:10, align:'center'},
						   		{name:'phone', index:'meta.phone', width:10, align:'center'},
						   		{name:'create_time', index:'users.create_time', width:12, align:'center'}";
		$colnames = "'Id', 'Nombre', 'Email', 'Nivel', 'Telefono', 'Fecha de alta'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "users/jqgrid_list_all", 
				'title' => 'Listado de usuarios', 
				'default_orderfield' => 'last_name', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '820',
				'row_list_options' => '10,20,50',
		);
		
		switch($option) {
			case "active":
				$para_grid['data_url'] = 'users/jqgrid_list_active';
				$para_grid['title'] = 'Listado de usuarios activos';
			break;
			case "inactive":
				$para_grid['data_url'] = 'users/jqgrid_list_inactive';
				$para_grid['title'] = 'Listado de usuarios inactivos';
			break;
			case "quotas":
				$para_grid['data_url'] = 'users/jqgrid_list_quotas';
				$para_grid['title'] = 'Listado de usuarios con cuota';
			break;
			case "next_quotas":
				$para_grid['data_url'] = 'users/jqgrid_list_next_quotas';
				$para_grid['title'] = 'Listado de usuarios con cuotas proximas a expirar';
			break;
			case "old_quotas":
				$para_grid['data_url'] = 'users/jqgrid_list_old_quotas';
				$para_grid['title'] = 'Listado de usuarios con cuotas impagadas';
			break;
			case "users":
				$para_grid['data_url'] = 'users/jqgrid_list_users';
				$para_grid['title'] = 'Listado de usuarios';
			break;
			case "members":
				$para_grid['data_url'] = 'users/jqgrid_list_members';
				$para_grid['title'] = 'Listado de socios';
			break;
			case "teacher":
				$para_grid['data_url'] = 'users/jqgrid_list_teacher';
				$para_grid['title'] = 'Listado de profesores';
			break;
		}



		
		# Exportación
		if(isset($export) && $export != '') {
			switch($export) {
				case 'excel':
				default:
					$datos = $this->jqgrid_list_all($param, 'return_rows');
					echo '<pre>';print_r($datos);exit();
					$this->output->set_header("Content-type: application/vnd.ms-excel");
					$this->output->set_header("Content-Disposition: attachment;filename=export_users_".time().".xls");
					
					$salida="";
					$salida='<table boder="1">'."\r\n";
					$salida.='<tr><td>Id Usuario</td><td>Usuario</td><td>Cantidad</td><td>Fecha</td><td>Fecha Valor</td><td>Ticket</td><td>Descripcion</td><td>Cuenta</td><td>Titular</td></tr>';
					foreach($datos as $pago) {
						$salida.='<tr>'."\r\n";
						$i=0;
						
						$salida.='<td>'.$pago['id_user'].'</td>'."\r\n";
						$salida.='<td>'.$pago['desc_user'].'</td>'."\r\n";
						$salida.='<td>'.$pago['quantity'].'</td>'."\r\n";
						$salida.='<td>'.$pago['date'].'</td>'."\r\n";
						$salida.='<td>'.$pago['fecha_valor'].'</td>'."\r\n";
						$salida.='<td>'.$pago['ticket_number'].'</td>'."\r\n";
						$salida.='<td>'.$pago['description'].'</td>'."\r\n";
						$salida.='<td>'.$pago['bank'].'-'.$pago['bank_office'].'-'.$pago['bank_dc'].'-'.$pago['bank_account'].'</td>'."\r\n";
						$salida.='<td>'.$pago['bank_titular'].'</td>'."\r\n";

						$salida.='</tr>'."\r\n";
					}
					$salida.='</table>';
					
					$this->output->set_output($salida);
					return NULL;
					//redirect(site_url('facturacion/list_all/'.$param), 'Location'); 
					//exit();

					//print('<pre>');print_r($datos); exit();
				//echo 'a';
				break;
				
			}
			
			
		} 
		


		
		$grid_code = '<div style="position:relative; width: 820px; height: 600px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = $this->load->view('menu_lateral_gestion', '', true);
		$permisos = array('export_excel' => TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('users/list_all', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'permisos' => $permisos, 'menu_lateral' => $menu_lateral), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}






# -------------------------------------------------------------------
#  devuelve listados de usuarios para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------

# Listado de activos
public function jqgrid_list_active ()
	{
		$opcion = array('where' => "users.active = '1'");
		$this->jqgrid_list_all($opcion);
		
	}
	
# Listado de inactivos
public function jqgrid_list_inactive ()
	{
		$opcion = array('where' => "(users.active = '' OR users.active = '0' OR users.active IS NULL)");
		$this->jqgrid_list_all($opcion);
		
	}
# Listado de usuarios con cuota
public function jqgrid_list_quotas ()
	{
		$opcion = array('where' => "(meta.code_price is not null and meta.code_price<>'')");
		$this->jqgrid_list_all($opcion);
		
	}
# Listado de usuarios con cuota a punto de caducar segun el valor del config de preaviso
public function jqgrid_list_next_quotas ()
	{
		$opcion = array('where' => "(meta.code_price is not null and meta.code_price<>'' and meta.last_payd_date is not null and meta.last_payd_date>'".date($this->config->item('date_db_format'), strtotime(date('U').' +'.$this->config->item('users_qouta_caducity_days').'days'))."')");
		$this->jqgrid_list_all($opcion);
		
	}
# Listado de usuarios con cuota a punto de caducar segun el valor del config de preaviso
public function jqgrid_list_old_quotas ()
	{
		$opcion = array('where' => "(meta.code_price is not null and meta.code_price<>'' and (meta.last_payd_date is null or meta.last_payd_date<='".date($this->config->item('date_db_format'))."'))");
		$this->jqgrid_list_all($opcion);
		
	}
# Listado de usuarios de nivel 'user'
public function jqgrid_list_users ()
	{
		$opcion = array('where' => "(users.group_id=7)");
		$this->jqgrid_list_all($opcion);
		
	}
# Listado de usuarios de nivel 'socio'
public function jqgrid_list_members ()
	{
		$opcion = array('where' => "(users.group_id=6)");
		$this->jqgrid_list_all($opcion);
		
	}
# Listado de usuarios de nivel 'profesor'
public function jqgrid_list_teacher ()
	{
		$opcion = array('where' => "(users.group_id=5)");
		$this->jqgrid_list_all($opcion);
		
	}
	
public function jqgrid_list_all ($option = NULL)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);

		$where = '';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			if($user_group > '3') $where = "users.id = '".$profile->id."'";
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
		if(isset($option) && $option['where']!='') {
			if(trim($req_param['where'])!="") $req_param['where'] .= ' AND ';
			$req_param['where'].= $option['where'];
		}
		
		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->users->get_data($req_param,"all")->result_array());
		if(!isset($req_param['num_rows']) || $req_param['num_rows'] == 0 || $req_param['num_rows'] == '') $req_param['num_rows'] = 100000000;
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->users->get_data ($req_param, 'none')->result_array();
		//print("<pre>");print_r($records);
		
		$i = 0;
		foreach($records as $record) {
			$records[$i]['nombre_completo'] = $record['first_name'];
			if($record['first_name']!="") $records[$i]['nombre_completo'].=' '.$record['last_name'];
			$i++;
		}
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
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
	function reset_password($code_user = NULL, $password = NULL)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		if($this->redux_auth->logged_in()) 
		{			
			$returnUrl = $this->input->post('returnUrl');
			if(!isset($code_user)) $code_user = $this->input->post('id_user');
			if(!isset($password)) $password = $this->input->post('new_password');
			
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
			if($returnUrl!='') redirect($returnUrl, 'Location'); 
			else redirect(site_url(), 'Location'); 
			exit();
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
# Funcion para cargar cuota de usuario
# -------------------------------------------------------------------
	function pay_quota($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->library('users_lib');

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('profiles_visualization_permission');
			if(!$visualization_permission[$profile->group]) { 
				$this->session->set_userdata('error_message', 'No tiene permisos para visualizar esa pagina.');
				redirect(site_url(), 'Location'); 
				exit();	
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		if($this->redux_auth->logged_in()) 
		{			

			# recupero los datos del usuario
			$array_result = $this->users->get_user($code_user);

			$payable_quota = $this->input->post('payable_quota');
			$payd_date_tmp = $this->input->post('payd_date');
			$returnUrl = $this->input->post('returnUrl');
			if($returnUrl=='') $returnUrl = site_url('users/detail/'.$array_result['user_id']);
			
			//echo $returnUrl;
			$paymentway = $this->input->post('paymentway');
			//echo $payable_quota;
			//echo '<br/>'.$payd_date_tmp;
			//print("<pre>");print_r($_POST);
			//echo '<br>last_payd_date:'.$array_result['last_payd_date'];
			//exit();
			$estado = 9;
			if( $paymentway == 4) $estado = 2;
			$pagado = $this->users_lib->pay_user_quota($code_user, array('payable_quota' => $payable_quota, 'payd_date_tmp' => $payd_date_tmp, 'status' => $estado, 'paymentway' => $paymentway, 'code_price' => $array_result['code_price'], 'name' => trim($array_result['user_name'].' '.$array_result['user_lastname']), 'group_id' => $array_result['group_id']));
			
			if($pagado) {
				redirect($returnUrl, 'Location'); 
				exit();
			} else {
				$this->session->set_userdata('error_message', 'Error en la informaci&oacute;n del pago.');
				redirect($returnUrl, 'Location'); 
				exit();
			}			
			
			
exit();
		}
	
}
	
# -------------------------------------------------------------------
# Funcion que muestra el detalle del usuario
# -------------------------------------------------------------------
	function detail($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('profiles_visualization_permission');
			if(!$visualization_permission[$profile->group]) { 
				$this->session->set_userdata('error_message', 'No tiene permisos para visualizar esa pagina.');
				redirect(site_url(), 'Location'); 
				exit();	
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		if($this->redux_auth->logged_in()) 
		{			

			$perfiles_permission = $this->config->item('profiles_visualization_permission');
			
			# Si es el mismo usuario el que quiero ver del que está logueado, le llevo a la página del perfil
			if(!$perfiles_permission[$profile->group] && $code_user == $profile->id) { redirect(site_url('users/profile'), 'Location'); exit(); }

			# Pagina de retorno del formulario
			$returnUrl = $this->session->userdata('returnUrl');
			//echo '---'.$returnUrl.'---'.$this->session->userdata('returnUrl');
			$this->session->unset_userdata('returnUrl');
			if(!isset($returnUrl)) $returnUrl = site_url('users/index'); 

			$this->session->set_userdata('returnUrl', current_url());

			
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
				
				# Compruebo si me llega por POST un nivel de usuario diferente.. y le doy preferencia.
				$grupo_tmp = $this->input->post('group_id');
				if(isset($grupo_tmp) && $grupo_tmp != '') $array_result['group_id'] = $grupo_tmp;
				
				//print("<pre>");print_r($array_result);
				$tarifa_enabled = FALSE;
				$tarifas_permission = $this->config->item('users_quota_group');
				if($tarifas_permission[$array_result['group_id']]) $tarifa_enabled = TRUE;
				$tarifa_payable = $tarifa_enabled;
				if(!isset($array_result['code_price']) || $array_result['code_price'] == '' || $array_result['code_price'] == '0') $tarifa_payable = FALSE;
				
				$change_pwd_enabled = FALSE;
				$change_pwd_enabled_permission = $this->config->item('users_password_admin_change');
				if($change_pwd_enabled_permission[$profile->group]) $change_pwd_enabled = TRUE;
				
				# Para ver si muestro el número de socio
				$numero_socio_visible = $this->config->item('users_member_number_visibility');
				$numero_socio_visible_grupos = $this->config->item('users_member_number_visibility_by_group');
				if($numero_socio_visible) $numero_socio_visible = $numero_socio_visible_grupos[$array_result['group_id']];
				$numero_socio_automatico = $this->config->item('users_member_number_auto');

				# Gestión de carnet de socio
				$carnet_permission = $this->config->item('users_carnet_enabled');
				$carnet_permission = $this->config->item('users_carnet_template_by_group');
				$carnet_enabled = FALSE;
				if($this->config->item('users_carnet_enabled') && isset($carnet_permission) && $carnet_permission[$array_result['group_id']] != '') $carnet_enabled = TRUE;

				$quota = 0;
				if($array_result['code_price']!='') $quota = $this->users->get_userQuota($array_result);
				//if($array_result['code_price']!='') echo '<br>-------'.$array_result['code_price'];
				
				//echo "Cuota: ".$quota;
				# Carga de tarifas posibles
				$array_quotas = $this->users->get_quotas();

				$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
				$next_payment_date = date($this->config->item('reserve_date_filter_format'), strtotime($this->users->getNextPaymentDate($code_user)));
				//echo $next_payment_date;
				
				# Carga de datos para la vista
				$data=array(
					'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('users/submenu_navegacion_detail', array(), true)), true),
					'menu' => $this->load->view('menu', '', true),
					'navigation' => $this->load->view('navigation', '', true),
					'footer' => $this->load->view('footer', '', true),				
					//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
					'form' => 'formDetail',
					'page' => 'users/user_detail',
					'code_user' => $code_user,
					'array_user' => $array_result,
					'array_groups' => $array_groups,
					'array_country' => $array_country,
					'array_province' => $array_province,
					'array_quotas' => $array_quotas,
					'tarifa_enabled' => $tarifa_enabled,
					'tarifa_payable' => $tarifa_payable,
					'carnet_enabled' => $carnet_enabled,
					'numero_socio_visible' => $numero_socio_visible,
					'numero_socio_automatico' => $numero_socio_automatico,
					'change_pwd_enabled' => $change_pwd_enabled,
					'quota' => $quota,
					'next_payment_date' => $next_payment_date,
					'returnUrl' => $returnUrl,
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
			else 
			{
				/* error */
				//print "ERROR pasa por aqui";
				$this->session->set_userdata('error_message', 'Informacion de usuario no disponible o usuario inexistente.');
				redirect(site_url(), 'Location'); 
				exit();
			}		
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	
	

	
# -------------------------------------------------------------------
# Funcion que muestra el perfil de un usuario para su propia consulta.. con acceso a sus reservas, pagos, etc..
# -------------------------------------------------------------------
	function profile()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group = $profile->group;
			$code_user = $profile->id;
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$profiles_visualization[$user_group] && !$panel_permission[$user_group] && $code_user != $profile->id) {
				$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			} 
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
			//print_r($array_result);
			if ($array_result != null)
			{
				//print("<pre>");print_r($array_result);
				$tarifa_enabled = FALSE;
				$tarifas_permission = $this->config->item('users_quota_group');
				if($tarifas_permission[$array_result['group_id']]) $tarifa_enabled = TRUE;
				
				$quota = 0;
				if($array_result['code_price']!='') $quota = $this->users->get_userQuota($array_result);

				# Para ver si muestro el número de socio
				$numero_socio_visible = $this->config->item('users_member_number_visibility');
				$numero_socio_visible_grupos = $this->config->item('users_member_number_visibility_by_group');
				if($numero_socio_visible) $numero_socio_visible = $numero_socio_visible_grupos[$array_result['group_id']];
				$numero_socio_automatico = $this->config->item('users_member_number_auto');
				
				//echo "Cuota: ".$quota;
				# Carga de tarifas posibles
				$array_quotas = $this->users->get_quotas();

				$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";

				# Carga de datos para la vista
				$data=array(
					'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('users/submenu_navegacion', array(), true)), true),
					'menu' => $this->load->view('menu', $menu, true),
					'navigation' => $this->load->view('navigation', '', true),
					'footer' => $this->load->view('footer', '', true),				
					//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
					'form' => 'formDetail',
					'page' => 'users/user_profile',
					'code_user' => $code_user,
					'array_user' => $array_result,
					'array_groups' => $array_groups,
					'array_country' => $array_country,
					'array_province' => $array_province,
					'array_quotas' => $array_quotas,
					'tarifa_enabled' => $tarifa_enabled,
					'quota' => $quota,
					'numero_socio_automatico' => $numero_socio_automatico,
					'numero_socio_visible' => $numero_socio_visible,
					//'array_levels' => $array_levels,
					//'enable_grid' => 1,
					//'js_grid' => $grid_js,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

			$this->session->set_userdata('returnUrl', site_url('users/profile/'.$code_user));

				$this->load->view('main', $data);
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
# Devuelve el listado de reservas por usuario
# -------------------------------------------------------------------



	
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
		
		if($this->redux_auth->logged_in()) 
		{	
					
			$amount = $this->input->post('amount');
			$codigo_pedido = time('U').'pr';
			/* DEFINIR AQUI LO QUE RECIBA POR POST (CANTIDAD, FORMA DE PAGO...) PARA PASAR PARÁMETROS A LA FUNCION .. */
			if(isset($command) && isset($control) && md5($code_user)==$control) {
				//echo $this->input->post('amount');
				//echo $amount;
				//print_r($_POST);
				//exit("AA");
				$this->users->addPrepaidMovement($code_user, $amount, '3', $command, date('U'));

				$this->pagos->updatePaymentStatus('id_transaction', $this->input->post('order_num'), '9');
									
				$this->session->set_userdata('info_message', 'Saldo prepago actualizado.');
				redirect(site_url('users/add_prepaid/'.$code_user), 'Location'); 
				exit();
				
			}
			

			if($amount!='') {
				
					$this->pagos->id_type=3; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$codigo_pedido;
					$this->pagos->id_user=$code_user;
					$this->pagos->id_paymentway='';
					$this->pagos->status=5;
					$this->pagos->quantity=$amount;
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Recarga al bono prepago';
					$this->pagos->create_user=$profile->id;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					$this->pagos->setPayment();
			}
			
				# Carga de datos para la vista
				$extra = '<script type="text/javascript" src="'.base_url().'js/jquery.maskedinput-1.2.2.min.js"></script>'."\r\n";
				
				$proximo_pago = intval($this->users->getLastPrepaidMovement($code_user)) + 1;
				if($profile->group <= 5) $cancelUrl = site_url('users');
				else  $cancelUrl = site_url();
				
				$data=array(
					'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra), true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
					'navigation' => $this->load->view('navigation', '', true),
					'footer' => $this->load->view('footer', '', true),				
					//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
					//'form_name' => 'formDetail',
					'page' => 'users/add_prepaid',
					'grupo' => $profile->group,
					'cancelUrl' => $cancelUrl,
					'proximo_pago' => $proximo_pago,
					'codigo_pedido' => $codigo_pedido,
					'pre_ammount' => $this->users->getPrepaidCash($code_user),
					'user_desc' => $this->users->getUserDesc($code_user),
					'control' => md5($code_user),
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

				$this->load->view('main', $data);
				/* pintar */
	
		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		


	}	
	
# -------------------------------------------------------------------
# Funcion que guarda la imagen del usuario activo
# -------------------------------------------------------------------
	function upload_photo($usuario)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		if($this->redux_auth->logged_in()) 
		{		
			//print("<pre>");
			//print_r($_POST);
			//print_r($_FILES);
			//exit();
			log_message('debug', 'FILES: '.var_export($_FILES, true));
			if($_FILES['archivo']['size'] > 300000) {				
				$this->session->set_userdata('error_message', 'El tama&ntilde;o del fichero excede el m&aacute;ximo (300KB).');
				redirect(site_url('users/detail/'.$usuario), 'Location'); 
				exit();
			}
			if(strstr($_FILES['archivo']['size'], 'image')) {
				$this->session->set_userdata('error_message', 'El fichero enviado no es una imagen con formato v&aacute;lido.');
				redirect(site_url('users/detail/'.$usuario), 'Location'); 
				exit();
			}
			
			$raiz = $this->config->item('root_path');
			if(!isset($raiz) || $raiz=='') {
				$this->session->set_userdata('error_message', 'Problema en la configuraci&oacute;n. Contacte con el administrador.');
				redirect(site_url('users/detail/'.$usuario), 'Location'); 
				exit();
			}
			
			$troceo = explode('.', $_FILES['archivo']['name']);
			$extension = $troceo[count($troceo)-1];
			$ruta_destino = $raiz.'images/users/'.$usuario.'.'.$extension;
			log_message('debug', $_FILES['archivo']['tmp_name']. ' --- ' .$ruta_destino);
			if(file_exists($_FILES['archivo']['tmp_name'])) {
				log_message('debug', 'fichero existe');
				if(is_dir($_FILES['archivo']['tmp_name'])) log_message('debug', 'es directorio');
				else log_message('debug', ' es fichero');
				
			} else log_message('debug', 'fichero No existe');
			//echo $ruta_destino; //exit();
			if(file_exists($ruta_destino)) @unlink($ruta_destino);
			@copy($_FILES['archivo']['tmp_name'], $ruta_destino);
			$this->users->setAvatar($usuario, $extension);
			$this->session->set_userdata('info_message', 'Fotograf&iacute;a actualizada.');
			redirect(site_url('users/detail/'.$usuario), 'Location'); 
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
			//$active_user_init = $this->input->post('user_active');
			$allow_phone_notification = $this->input->post('allow_phone_notification');
			$allow_mail_notification = $this->input->post('allow_mail_notification');
			$active_user = $this->input->post('user_active');
			
			# Cargo datos previos a grabar, para comprobar valores de antes y despues.
			$code_user = $this->input->post('id_user');
			$array_result = $this->users->get_user($code_user);
			
			$nivel_a_grabar = $this->input->post('group_id');
			
			
			//if ($active_user_init == "1") $active_user = '1';
			//print("<pre>");print_r($_POST);//exit();
			$arrayUser = array(
								'id' => $this->input->post('id_user'),
								'first_name' => $this->input->post('first_name'),
								'last_name' => $this->input->post('last_name'),
								'group_id' => $this->input->post('group_id'),
								'group_description' => $this->input->post('group_description'),
								'code' => $this->input->post('code'),
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
								'birth_date' => date($this->config->item('date_db_format'), strtotime($this->input->post('birth_date'))),
								'bank' => $this->input->post('bank'),
								'bank_office' => $this->input->post('bank_office'),
								'bank_dc' => $this->input->post('bank_dc'),
								'bank_account' => $this->input->post('bank_account'),
								'bank_titular' => $this->input->post('bank_titular'),
								'bank_iban' => $this->input->post('bank_iban'),
								'bank_bic' => $this->input->post('bank_bic'),
								'allow_phone_notification' => $allow_phone_notification,
								'allow_mail_notification' => $allow_mail_notification,
								'reto_lunes' => $this->input->post('lunes'),
								'reto_martes' => $this->input->post('martes'),
								'reto_miercoles' => $this->input->post('miercoles'),
								'reto_jueves' => $this->input->post('jueves'),
								'reto_viernes' => $this->input->post('viernes'),
								'reto_sabado' => $this->input->post('sabado'),
								'reto_domingo' => $this->input->post('domingo'),
								'reto_manana' => $this->input->post('manana'),
								'reto_tarde' => $this->input->post('tardes'),
								'reto_finde' => $this->input->post('finde'),
								'reto_notifica' => $this->input->post('avisar_retos'),
								'player_level' => $this->input->post('player_level'),
								);
			if(($this->input->post('code_price'))) $arrayUser['code_price'] = $this->input->post('code_price');
			if(($this->input->post('notas'))) $arrayUser['notas'] = $this->input->post('notas');
			
			$numero_socio_visible_grupos = $this->config->item('users_member_number_visibility_by_group');
			$numero_socio_auto = $this->config->item('users_member_number_auto');
			if(!isset($numero_socio_auto)) $numero_socio_auto = FALSE;
			
			//echo '--'.$nivel_a_grabar;print_r($numero_socio_visible_grupos);print_r($array_result);
			if((!isset($array_result['numero_socio']) || $array_result['numero_socio']=='' || $array_result['numero_socio']=='0') && $numero_socio_visible_grupos[$nivel_a_grabar] && $numero_socio_auto)   $arrayUser['numero_socio'] = $this->users->getNextMemberNumber();
			elseif($numero_socio_visible_grupos[$nivel_a_grabar] && !$numero_socio_auto) $arrayUser['numero_socio'] = $this->input->post('code');

								
			//print("<pre>");print_r($arrayUser);
			//exit();
			$result = $this->users->save_user($arrayUser);
			if ($result)
			{
				/* introducir mensaje */
				$this->session->set_userdata('info_message', 'El usuario se ha modificado correctamente');
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
		
		$returnUrl = $this->session->userdata('returnUrl');
		$this->session->unset_userdata('returnUrl');
		if($returnUrl!='') redirect($returnUrl, 'Location');
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
			$array_groups = $this->users->get_groups(array('exclude_anonymous' => TRUE));
			# Carga de datos para la vista
			$data=array(
				'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
				'menu' => $this->load->view('menu', $menu, true),
				'navigation' => $this->load->view('navigation', '', true),
				'footer' => $this->load->view('footer', '', true),				
				//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
				'form' => 'formDetail',
				'page' => 'users/user_new',
				'auto_password' => $auto_password,
				'array_groups' => $array_groups,
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
        $campos_extra = $this->config->item('users_search_extra_info');
        $array_users = $this->users->getActiveUsersArray($filtro, '', $campos_extra);
 
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
		$this->load->library('redux_auth');
		if($this->redux_auth->logged_in()) 
		{			

			$active_user_init = $this->input->post('user_active');
			$active_user = '0';
			if ($active_user_init == "on") $active_user = '1';
			$arrayUser = array(
								'first_name' => ucwords($this->input->post('first_name')),
								'last_name' => ucwords($this->input->post('last_name')),
								'group_id' => $this->input->post('group_id'),
								'group_description' => $this->input->post('group_description'),
								'email' => strtolower($this->input->post('email')),
								'active' => $active_user,
								'phone' => $this->input->post('user_phone'),
								'mobile_phone' => $this->input->post('mobile_phone'),
								'password' => $this->input->post('password_user')																
								);

			$id_user = $this->redux_auth->register($this->input->post('password_user'), $this->input->post('email'), FALSE, TRUE);
			if ($id_user)
			{
				
					$data = $this->users->get_user($id_user);
					
					$numero_socio_visible = $this->config->item('users_member_number_visibility');
					if($numero_socio_visible) {
						$numero_socio_visible_grupos = $this->config->item('users_member_number_visibility_by_group');
						//echo '--'.$nivel_a_grabar;print_r($numero_socio_visible_grupos);
						if($numero_socio_visible_grupos[$arrayUser['group_id']])  {
							$numero_socio = $this->users->getNextMemberNumber();
							//echo '-----------'.$numero_socio;
							$this->users->setMemberNumber($data['user_id'], $numero_socio);	// Activo el usuario
						}
					}
					
					$this->redux_auth->activate($data['activation_code']);	// Activo el usuario
					
					
					//print("<pre>");print_r($data);print_r($numero_socio_visible_grupos); echo '<br> grupo: '.$arrayUser['group_id'];exit();
					//echo site_url('users/detail/'.$data['user_id']);exit();
				/* introducir mensaje */
				//$this->detail($user_id);
				redirect(site_url('users/detail/'.$data['user_id']), 'Location'); 
				exit();
			}
			else 
			{
				/* introducir mensaje */
				/*print 'Error';*/
				//$this->new_user();
				$this->session->set_userdata('error_message', 'Usuario no creado. Problemas con los datos facilitados.');
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







# -------------------------------------------------------------------
#  Listado general de las reservas usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function reservas($usuario = NULL)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$code_user = $profile->id;
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			if(!$profiles_visualization[$user_group] && isset($usuario) && ($usuario != $profile->id)) {
				$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Si estoy viendo las reservas d eotro usuario, sobreescribo la variable de usuario activo para ver sus datos
		if(isset($usuario) && $usuario!='') $code_user = $usuario;
		
		$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'fecha', width:12, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'inicio', width:10, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'status_desc', index:'status_desc', width:15, align:'center'},
						   		{name:'paymentway_desc', index:'paymentway_desc', width:15, align:'center'},
						   		{name:'price', index:'price', width:10, align:'center'},
						   		{name:'light_desc', index:'price_light', width:10, align:'center'},
						   		{name:'no_cost',index:'no_cost', width:10, align:'center', sortable:false}";
		$colnames = "'Id','Fecha','Inicio','Final','Pista', 'Estado', 'Forma de pago', 'Precio', 'Luz', 'Sin coste'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "reservas_gest/jqgrid_list_by_user/".$code_user, 
				'title' => 'Listado de reservas', 
				'default_orderfield' => 'date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		
		# Si llega usuario definido (es decir, estoy viendo el perfil de otro usuario) cargo el submenú especial que propaga el codigo de usuario
		if(isset($usuario) && $usuario !='') $submenu =  $this->load->view('users/submenu_navegacion_detail', array(), true);
		else $submenu =  $this->load->view('users/submenu_navegacion', array(), true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $submenu), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('users/reservas', array('grid_code' => $grid_code, 'enable_buttons' => FALSE, 'menu_lateral' => NULL), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_userdata('returnUrl', site_url('users/reservas/'.$code_user));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}






# -------------------------------------------------------------------
#  Listado general de los pagos usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function pagos($usuario = NULL)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$code_user = $profile->id;
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$profiles_visualization[$user_group] && isset($usuario) && ($usuario != $profile->id)) {
				$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Si estoy viendo las reservas d eotro usuario, sobreescribo la variable de usuario activo para ver sus datos
		if(isset($usuario) && $usuario!='') $code_user = $usuario;

		
		
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'id_type_desc',index:'zz_payment_type.description', width:13, align:'center'},
						   		{name:'paymentway_desc', index:'zz_paymentway.description', width:10, align:'center'},
						   		{name:'quantity', index:'payments.quantity', width:6, align:'center'},
						   		{name:'status_desc', index:'zz_payment_status.description', width:10, align:'center'},
						   		{name:'fecha_valor', index:'DATE_FORMAT(DATE(payments.fecha_valor),\'%d-%m-%Y\')', width:12, align:'center'},
						   		{name:'datetime', index:'DATE_FORMAT(DATE(payments.datetime),\'%d-%m-%Y\')', width:20, align:'center'},
						   		{name:'description', index:'payments.description', width:50, align:'center'}";
		$colnames = "'Id','Concepto','Forma', '&euro;', 'Estado', 'Fecha Valor', 'Momento pago', 'Descripcion'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "facturacion/jqgrid_list_by_user/".$code_user, 
				'title' => 'Listado de pagos', 
				'default_orderfield' => 'datetime', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';

		# Si llega usuario definido (es decir, estoy viendo el perfil de otro usuario) cargo el submenú especial que propaga el codigo de usuario
		if(isset($usuario) && $usuario !='') $submenu =  $this->load->view('users/submenu_navegacion_detail', array(), true);
		else $submenu =  $this->load->view('users/submenu_navegacion', array(), true);

		
		$permisos = array('change_status' => FALSE, 'return_payment' => FALSE, 'cancel_payment' => FALSE, 'new_payment' => FALSE, 'view_receipt' => FALSE, 'export_excel' => FALSE);
		$this->load->config('facturacion');
		$payment_change_status_permission = $this->config->item('payment_pendiente_change_status_permission');
		$permisos['change_status'] = $payment_change_status_permission[$user_group];
		$payment_repay_permission = $this->config->item('payment_repay_permission');
		$permisos['repay_payment'] = $payment_repay_permission[$user_group]; 
		$payment_devolver = $this->config->item('payment_devuelto_change_status_permission');
		$permisos['return_payment'] = $payment_devolver[$user_group];
		$payment_cancelar = $this->config->item('payment_cancel_change_status_permission');
		$permisos['cancel_payment'] = $payment_cancelar[$user_group];
		$payment_nuevo = $this->config->item('payment_add_custom_permission');
		$permisos['new_payment'] = $payment_nuevo[$user_group];
		$payment_dev = $this->config->item('payment_add_custom_devolution_permission');
		$permisos['new_devolution'] = $payment_dev[$user_group];
		$payment_recibo = $this->config->item('payment_view_receipt_permission');
		$permisos['view_receipt'] = $payment_recibo[$user_group];
		
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";

		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $submenu), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('users/pagos', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'permisos' => $permisos, 'menu_lateral' => NULL, 'code_user' => $code_user), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_userdata('returnUrl', site_url('users/pagos/'.$code_user));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}






# -------------------------------------------------------------------
#  Listado general de las clases a las que está apuntado el usuario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function clases($usuario=NULL)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$code_user = $profile->id;
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			$panel_permission = $this->config->item('bookings_visualization_permission');
			if(!$profiles_visualization[$user_group] && isset($usuario) && ($usuario != $profile->id)) {
				$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Si estoy viendo las reservas d eotro usuario, sobreescribo la variable de usuario activo para ver sus datos
		if(isset($usuario) && $usuario!='') $code_user = $usuario;
		
		
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
				'data_url' => "lessons/jqgrid_list_all/none/".$code_user, 
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
		
		# Si llega usuario definido (es decir, estoy viendo el perfil de otro usuario) cargo el submenú especial que propaga el codigo de usuario
		if(isset($usuario) && $usuario !='') $submenu =  $this->load->view('users/submenu_navegacion_detail', array(), true);
		else $submenu =  $this->load->view('users/submenu_navegacion', array(), true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $submenu), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('users/clases', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL, 'code_user' => $code_user), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_userdata('returnUrl', site_url('users/clases/'.$code_user));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}



# -------------------------------------------------------------------
#  Listado general de los partidos en los que participa el usuario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function partidos($usuario = NULL)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$code_user = $profile->id;
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			if(!$profiles_visualization[$user_group] && isset($usuario) && ($usuario != $profile->id)) {
				$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Si estoy viendo las reservas d eotro usuario, sobreescribo la variable de usuario activo para ver sus datos
		if(isset($usuario) && $usuario!='') $code_user = $usuario;
		
		$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'fecha', width:12, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'inicio', width:10, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:10, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'user_desc',index:'user_desc', width:40, align:'center'}";
		$colnames = "'Id','Fecha','Inicio','Final','Pista', 'Reservado por'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "reservas_gest/jqgrid_list_by_user_shared/".$code_user, 
				'title' => 'Listado de partidos', 
				'default_orderfield' => 'date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		
		# Si llega usuario definido (es decir, estoy viendo el perfil de otro usuario) cargo el submenú especial que propaga el codigo de usuario
		if(isset($usuario) && $usuario !='') $submenu =  $this->load->view('users/submenu_navegacion_detail', array(), true);
		else $submenu =  $this->load->view('users/submenu_navegacion', array(), true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $submenu), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('users/reservas', array('grid_code' => $grid_code, 'enable_buttons' => FALSE, 'menu_lateral' => NULL), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_userdata('returnUrl', site_url('users/reservas/'.$code_user));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}






# -------------------------------------------------------------------
#  Listado general de las reservas usando el jqGrid
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function retos($usuario = NULL)
	{

		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			$code_user = $profile->id;
			$profiles_visualization = $this->config->item('profiles_visualization_permission');
			if(!$profiles_visualization[$user_group] && isset($usuario) && ($usuario != $profile->id)) {
				$this->session->set_userdata('error_message', 'Acceso a ese perfil de usuario no permitido');
				redirect(site_url(), 'Location'); 
				exit();				
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Si estoy viendo las reservas d eotro usuario, sobreescribo la variable de usuario activo para ver sus datos
		if(isset($usuario) && $usuario!='') $code_user = $usuario;
		
		$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'fecha', width:10, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'inicio', width:6, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:6, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'players', index:'booking_shared.players', width:5, align:'center'},
						   		{name:'price_by_player', index:'booking_shared.price_by_player', width:5, align:'center'},
						   		{name:'gender', index:'booking_shared.gender', width:5, align:'center'},
						   		{name:'low_player_level', index:'booking_shared.low_player_level', width:5, align:'center'},
						   		{name:'high_player_level', index:'booking_shared.high_player_level', width:5, align:'center'},
						   		{name:'notified', index:'booking_shared.notified', width:5, align:'center'},
						   		{name:'apuntado', index:'booking_shared.notified', width:5, align:'center'},";
		$colnames = "'Id','Fecha','Inicio','Final','Pista','Jugadores', 'Precio',  'Genero', 'Nivel1', 'Nivel2', 'Mail', 'Apuntado'";

		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "retos/jqgrid_list_by_user/".$code_user, 
				'title' => 'Listado de retos', 
				'default_orderfield' => 'fecha', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		
		# Si llega usuario definido (es decir, estoy viendo el perfil de otro usuario) cargo el submenú especial que propaga el codigo de usuario
		if(isset($usuario) && $usuario !='') $submenu =  $this->load->view('users/submenu_navegacion_detail', array(), true);
		else $submenu =  $this->load->view('users/submenu_navegacion', array(), true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $submenu), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'form_name' => 'frmGrid',				
			'main_content' => $this->load->view('users/retos', array('grid_code' => $grid_code, 'code_user' => $code_user, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			//'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
			$this->session->set_userdata('returnUrl', site_url('users/reservas/'.$code_user));
			
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
}






	function set_users_pwd_batch()
	{
		
		# Revisa todos los usuarios que tienen el password vacio y les pone el password siguiendo un patron dado (ahora mismo, el id+apellidos sin espacios en blanco, todo en minusculas)
		/*if (!isset($term))
		{
			$term = 'A';
		}*/
		$this->load->model('redux_auth_model', 'users', TRUE);
		//$this->output->set_header($this->config->item('json_header'));
		$usuarios = $this->users->get_data(array('where' =>"users.password is null or users.password = ''"))->result_array();
		foreach ($usuarios as $usuario) {
			$pwd = 'usuario'.$usuario['numero_socio'];
			$this->users->change_password_admin($usuario['id'], $pwd);
			echo '<br>usuario '.$usuario['id'].' con pwd '.$pwd;
		}
		echo '<pre>';print_r($return_array);exit();
		//return $return_array;
		//$data=array('page' => 'gestion/list_users',
			//		'array_user' => $return_array,
				//	'term' => $term);
		echo json_encode($return_array);
		//$this->load->view('gestion/list_users', $data);
	}
		




# -------------------------------------------------------------------
#  genera un fichero de texto en el servidor con los datos de usuarios
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function exportacion ($opciones = NULL)
	{
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->library('users_lib');


		$exportacion = $this->users_lib->exportacion(array('formato' => 'array', 'opcion' => $opciones));
		//echo $texto."<pre>"; print_r($exportacion);exit();
		//echo json_encode ($data );
		//exit( 0 );
	}
	


	
	

# -------------------------------------------------------------------
# Funcion que muestra el detalle del usuario
# -------------------------------------------------------------------
	function carnet($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('profiles_visualization_permission');
			if(!$visualization_permission[$profile->group]) { 
				$this->session->set_userdata('error_message', 'No tiene permisos para visualizar esa pagina.');
				redirect(site_url(), 'Location'); 
				exit();	
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		if(!$this->redux_auth->logged_in()) 
		{
			$this->session->set_userdata('error_message', 'No tiene permiso para realizar esta accion.');
			redirect(site_url(), 'Location'); 
			exit();
		}

		# Si es el mismo usuario el que quiero ver del que está logueado, le llevo a la página del perfil
		$perfiles_permission = $this->config->item('profiles_visualization_permission');
		if(!$perfiles_permission[$profile->group]) { $this->session->set_userdata('error_message', 'No tiene permiso para realizar esta accion.'); redirect(site_url(), 'Location'); exit(); }

		# recupero los datos del usuario
		$array_result = $this->users->get_user($code_user);
		if (!isset($array_result) || count($array_result) <=0)
		{
			$this->session->set_userdata('error_message', 'Informacion de usuario no disponible o usuario inexistente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Gestión de carnet de socio
		$carnet_permission = $this->config->item('users_carnet_template_by_group');
		$carnet_enabled = FALSE;
		if(!$this->config->item('users_carnet_enabled') || !isset($carnet_permission) || trim($carnet_permission[$array_result['group_id']]) == '') {
			exit('Carnet no habilitado para este usuario');
		}
   
		//print_r($array_result);exit();
		$imgPath = $this->config->item('root_path').'images/templates/'.$carnet_permission[$array_result['group_id']];
		$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
		$font = $this->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		if(!file_exists($imgPath) || !file_exists($font)) exit ('Fallo en la carga de las plantillas necesarias');
		if(!file_exists($imgStampPath)) exit ('Foto del usuario no disponible');

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
		$ancho_qrcode = 100;
		$qrcode = imagecreatefrompng('http://chart.apis.google.com/chart?cht=qr&chs='.$ancho_qrcode.'x'.$ancho_qrcode.'&chl='.$array_result['user_id'].'|'.$array_result['group_id'].'|'.strtotime($array_result['last_payd_date']));
		$ancho_qrcode = $ancho_qrcode-20;	// Le quito el doble de lo que le quitaré del borde más abajo
		//$qrcode = imagecreatefrompng('chart100.png');
		$qr_marge_right = 150;
		$qr_marge_bottom = 50;
		imagecopy($image, $qrcode, imagesx($image) - $ancho_qrcode - $qr_marge_right, imagesy($image) - $ancho_qrcode - $qr_marge_bottom, 10, 10, $ancho_qrcode, $ancho_qrcode);
		

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
	
	
	
	
	
	
	

	

# -------------------------------------------------------------------
# Funcion que muestra el detalle del usuario
# -------------------------------------------------------------------
	function carnet_recibo($code_user)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('profiles_visualization_permission');
			if(!$visualization_permission[$profile->group]) { 
				$this->session->set_userdata('error_message', 'No tiene permisos para visualizar esa pagina.');
				redirect(site_url(), 'Location'); 
				exit();	
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		if(!$this->redux_auth->logged_in()) 
		{
			$this->session->set_userdata('error_message', 'No tiene permiso para realizar esta accion.');
			redirect(site_url(), 'Location'); 
			exit();
		}

		# Si es el mismo usuario el que quiero ver del que está logueado, le llevo a la página del perfil
		$perfiles_permission = $this->config->item('profiles_visualization_permission');
		if(!$perfiles_permission[$profile->group]) { $this->session->set_userdata('error_message', 'No tiene permiso para realizar esta accion.'); redirect(site_url(), 'Location'); exit(); }

		# recupero los datos del usuario
		$array_result = $this->users->get_user($code_user);
		if (!isset($array_result) || count($array_result) <=0)
		{
			$this->session->set_userdata('error_message', 'Informacion de usuario no disponible o usuario inexistente.');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Gestión de carnet de socio
		$carnet_permission = $this->config->item('users_quota_group');
		$carnet_enabled = FALSE;
		if(!$this->config->item('users_carnet_enabled') || !isset($carnet_permission) || trim($carnet_permission[$array_result['group_id']]) == '') {
			exit('Carnet no habilitado para este usuario');
		}

		$quota = 0;
		if($array_result['code_price']!='') $quota = $this->users->get_userQuota($array_result);
		$quota = number_format($quota, 2);
		//print_r($array_result);exit();
		$imgPath = $this->config->item('root_path').'images/templates/plantilla.jpg';
		$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
		$font = $this->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		$doc_title = 'Duplicado de carnet';
		//print_r($array_result);
		//$imgPath = $this->config->item('root_path').'images/templates/'.$carnet_permission[$array_result['group_id']];
		//$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
		if(!file_exists($imgPath) || !file_exists($font)) exit ('Fallo en la carga de las plantillas necesarias');
		if(!file_exists($imgStampPath)) exit ('Foto del usuario no disponible');
		
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
		$factor = $sx / 300;	// Marco el ancho que quiero que tenga la foto de carnet
		$dx = intval($sx / $factor);
		$dy = intval($sy / $factor);
		if($dy > 400) {
			# Si la imagen es desproporcionadamente alta, pongo el alto como referencia para redimensionar
			$factor = $sy / 300;	// Marco el alto maximo que quiero que tenga la foto de carnet
			$dx = intval($sx / $factor);
			$dy = intval($sy / $factor);
		}
		//echo '<br>'.$factor; echo '<br>'.$dx; echo '<br>'.$dy;
		$fotocarnet_thumb = imagecreatetruecolor($dx, $dy);
		imagecopyresized($fotocarnet_thumb, $fotocarnet, 0, 0, 0, 0, $dx, $dy, $sx, $sy);
		$ancho_fotocarnet_thumb = imagesx($fotocarnet_thumb);
		$alto_fotocarnet_thumb = imagesy($fotocarnet_thumb);

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

		imagettftext($image, $fontSize, 0, $text_xpos, 660, $black, $font, $array_result['user_lastname'].', '.$array_result['user_name']);	// Nombre
		imagettftext($image, $fontSize, 0, $text_xpos+1650, 660, $black, $font, $array_result['nif']);	// DNI
		imagettftext($image, $fontSize, 0, $text_xpos, 850, $black, $font, $array_result['birth_date']);	// fecha nacimiento
		imagettftext($image, $fontSize-5, 0, $text_xpos+450, 850, $black, $font, $array_result['address']);	// direccion
		if(strlen($array_result['population'])<= 15 ) imagettftext($image, $fontSize, 0, $text_xpos+1620, 850, $black, $font, $array_result['population']);	// telefono movil
		elseif(strlen($array_result['population'])<= 20 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1620, 850, $black, $font, $array_result['population']);
		elseif(strlen($array_result['population'])<= 23 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1610, 850, $black, $font, $array_result['population']);
		else imagettftext($image, $fontSize-20, 0, $text_xpos+1610, 850, $black, $font, $array_result['population']);
		imagettftext($image, $fontSize, 0, $text_xpos, 1030, $black, $font, $array_result['cp']);	// codigo postal
		//imagettftext($image, $fontSize, 0, $text_xpos+450, 1030, $black, $font, $array_result['user_phone']);	// telefono fijo
		imagettftext($image, $fontSize, 0, $text_xpos+950, 1030, $black, $font, $array_result['user_phone']);	// telefono movil
		if(strlen($array_result['email'])<= 22 ) imagettftext($image, $fontSize, 0, $text_xpos+1350, 1030, $black, $font, $array_result['user_email']);	// telefono movil
		elseif(strlen($array_result['email'])<= 30 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1350, 1030, $black, $font, $array_result['user_email']);
		elseif(strlen($array_result['email'])<= 34 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1350, 1030, $black, $font, $array_result['user_email']);
		else imagettftext($image, $fontSize-20, 0, $text_xpos+1350, 1030, $black, $font, $array_result['user_email']);

		imagettftext($image, $fontSize-5, 0, $text_xpos, 1500, $black, $font, 'Como '.$array_result['group_description'].' la cuota a abonar es de '.$quota.' euros. ');	// Nombre
		imagettftext($image, $fontSize-5, 0, $text_xpos, 1600, $black, $font, 'En el concepto de pago deberá poner \'Socio '.$array_result['user_id'].'\'');	// Nombre
		imagettftext($image, $fontSize-5, 0, $text_xpos, 1800, $black, $font, 'El ingreso deberá realizarse en alguno de los siguientes números de cuenta:');	// Nombre
		imagettftext($image, $fontSize, 0, $text_xpos+50, 1900, $black, $font, '2105 0039 34 1290022090 (Caja Castilla-La Mancha)');	// Nombre
		imagettftext($image, $fontSize, 0, $text_xpos+50, 2000, $black, $font, '3081 0181 03 2563768528 (Caja Rural)');	// Nombre
		imagettftext($image, $fontSize-10, 0, $text_xpos, 2150, $black, $font, 'Deberá acompañarse la presente solicitud con el justificante del ingreso');	// Nombre

		
		
		//imagettftext($image, $fontSize, 0, $text_xpos, 140, $black, $font, 'ID: '.$array_result['user_id']);


		$anchoo = imagesx($image);
		$altoo = imagesy($image);
		$proporcion = 1.4143;
		$imagen_final = imagecreatetruecolor($anchoo, $altoo);
		$image = imagerotate($image, 90, 0);
		imagecopyresized ($imagen_final, $image, 0, 0, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);
		imagecopyresized ($imagen_final, $image, 0, $altoo / 2, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);


		
		
		header("Content-type: image/jpeg");
		header("Content-type: " . $mime);
		//header("Content-Length: " . $size);
		// NOTE: Possible header injection via $basename
		header("Content-Disposition: attachment; filename=cuota_" . $code_user .'.jpg');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		
		# Rota la imagen 90 grados
		//$image = imagerotate($image, 90, 0);
		
		imagejpeg($imagen_final, null, 100);
		// Liberar memoria
		imagedestroy($image);
		imagedestroy($imagen_final);
		exit();
		
						/* pintar */
	

	}	
	
	
		
	
	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */