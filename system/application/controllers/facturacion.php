<?php

class Facturacion extends Controller {

	function Facturacion()
	{
		parent::Controller();	
		$this->load->config('facturacion');
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');
	}
	
	function index()
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
		
		
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		if($this->redux_auth->logged_in()) $data['page']='facturacion/index';

		
		
		$this->load->view('main', $data);
	}




# -------------------------------------------------------------------
#  Listado general de los pagos
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all($param = NULL, $export = NULL)
	{
		$this->load->helper('jqgrid');
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		
		$this->session->set_userdata('returnUrl', site_url('facturacion/list_all/'.$param));	
			
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'id_type_desc',index:'zz_payment_type.description', width:14, align:'center'},
						   		{name:'paymentway_desc', index:'zz_paymentway.description', width:14, align:'center'},
						   		{name:'desc_user',index:'payments.desc_user', width:20, align:'center'},
						   		{name:'quantity', index:'payments.quantity', width:6, align:'center'},
						   		{name:'status_desc', index:'zz_payment_status.description', width:12, align:'center'},
						   		{name:'date', index:'payments.datetime', width:12, align:'center'},
						   		{name:'time', index:'TIME(payments.datetime)', width:10, align:'center'},
						   		{name:'description', index:'payments.description', width:35, align:'center'},
						   		{name:'ticket_number', index:'payments.ticket_number', width:10, align:'center', hidden:true, search:true,  searchoptions:{searchhidden:true}}";
						   		// Con el searchoptions marco que, aunque no se vea el campo, puedo buscar por él.. 
		$colnames = "'Id','Concepto','Forma','Usuario', '&euro;', 'Estado', 'Fecha', 'Hora', 'Descripcion', 'Ticket'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "facturacion/jqgrid_list_all", 
				'title' => 'Listado de pagos', 
				'default_orderfield' => 'datetime', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '820',
				'row_list_options' => '10,20,50,100',
		);
		# Si envío un parámetro adicional para filtrar, lo traspaso al que carga los datos
		if(isset($param)) $para_grid['data_url'] = "facturacion/jqgrid_list_all/".$param;
		
		# Exportación
		if(isset($export) && $export != '') {
			switch($export) {
				case 'excel':
				default:
					$datos = $this->jqgrid_list_all($param, 'return_rows');
					//echo '<pre>';print_r($datos);exit();
					$this->output->set_header("Content-type: application/vnd.ms-excel");
					$this->output->set_header("Content-Disposition: attachment;filename=export_".time().".xls");
					
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
		
		
		$grid_code = '<div style="position:relative; width: 820px; height: 660px; float: right;">'.jqgrid_creator($para_grid).'</div>';
		$menu_lateral = $this->load->view('menu_lateral_gestion', '', true);

		$permisos = array('change_status' => FALSE, 'return_payment' => FALSE, 'cancel_payment' => FALSE, 'new_payment' => FALSE, 'view_receipt' => FALSE, 'export_excel' => FALSE);
		
		$payment_change_status_permission = $this->config->item('payment_pendiente_change_status_permission');
		if($param == 'remesa') $permisos['change_status'] = $payment_change_status_permission[$profile->group];
		else $permisos['change_status'] = FALSE;
		$payment_repay_permission = $this->config->item('payment_repay_permission');
		if($param == 'remesa_pend') {$permisos['repay_payment'] = $payment_repay_permission[$profile->group]; $permisos['change_payed'] = $payment_repay_permission[$profile->group]; $permisos['export_excel'] = TRUE;}
		else { $permisos['repay_payment'] = FALSE; $permisos['change_payed'] = FALSE; }
		$payment_devolver = $this->config->item('payment_devuelto_change_status_permission');
		if($param != 'remesa_pend') $permisos['return_payment'] = $payment_devolver[$profile->group];
		else $permisos['return_payment'] = FALSE;
		$payment_cancelar = $this->config->item('payment_cancel_change_status_permission');
		$permisos['cancel_payment'] = $payment_cancelar[$profile->group];
		$payment_nuevo = $this->config->item('payment_add_custom_permission');
		$permisos['new_payment'] = $payment_nuevo[$profile->group];
		$payment_dev = $this->config->item('payment_add_custom_devolution_permission');
		$permisos['new_devolution'] = $payment_dev[$profile->group];
		$payment_recibo = $this->config->item('payment_view_receipt_permission');
		$permisos['view_receipt'] = $payment_recibo[$profile->group];
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('payment/list_all', array('grid_code' => $grid_code, 'permisos' => $permisos, 'enable_buttons' => TRUE, 'menu_lateral' => $menu_lateral), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}







# -------------------------------------------------------------------
#  Listado general de los pagos con flexigrid (obsoleto en breve)
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all2()
	{
		$this->load->model('Payment_model', 'pagos', TRUE);



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
		$colModel['id'] = array('ID',90,FALSE,'center',1, TRUE);
		$colModel['id_type_desc'] = array('Tipo',80,FALSE,'center',0);
		$colModel['paymentway_desc'] = array('Forma pago',70,FALSE,'center',0, FALSE);
		$colModel['user_desc'] = array('Usuario',130, FALSE, 'center',0);
		$colModel['quantity'] = array('Cantidad',40,FALSE,'right',1);
		$colModel['status_desc'] = array('Estado',55,FALSE,'center',1, FALSE);
		$colModel['date'] = array('Fecha',60,FALSE,'center',0, FALSE);
		$colModel['time'] = array('Hora',35,FALSE,'center',0, FALSE);
		$colModel['description'] = array('Concepto',200,FALSE,'center',0, FALSE);

/*		
		$colModel['action_cancel'] = array('Cancelar',40, FALSE, 'center',0, FALSE , 'cancelarReserva');
		$colModel['action_change'] = array('Mod. Hora',45, FALSE, 'center',0);
		$colModel['action_payment'] = array('Cobrar',35, FALSE, 'center',0, FALSE , 'cobrarReserva');
		$colModel['action_light'] = array('Set Luz',35, FALSE, 'center',0, FALSE , 'setLight');
		*/
		
		
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
		'title' => 'Listado de pagos',
		'showTableToggleBtn' => false
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		
		//$buttons[] = array('Delete','delete','buttons');
		//$buttons[] = array('separator');
		//$buttons[] = array($this->lang->line('new_reserve'),'add','buttons');
		$buttons[] = array('separator');
//		$buttons[] = array('separator');
//		$buttons[] = array('Anular','delete','validarReserva');
		
		
		# Filtros a pasar al grid
		$where_arr=array();
			$selected_type=$this->input->post('id_type');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('id_user');
			$selected_status=$this->input->post('status');
			$selected_date1=$this->input->post('date1');
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));

		if($selected_type!="") array_push($where_arr, "courts.sport_type = '".$selected_type."'");
		if($selected_status!="") array_push($where_arr, "status = '".$selected_status."'");
		if($selected_paymentway!="") array_push($where_arr, "id_paymentway = '".$selected_paymentway."'");
		if($selected_user!="") array_push($where_arr, "id_user = '".$selected_user."'");
		if($selected_date1!="") array_push($where_arr, "date(datetime) >= '".date($this->config->item('date_db_format'), strtotime($selected_date1))."'");
		if($selected_date2!="") array_push($where_arr, "date(datetime) <= '".date($this->config->item('date_db_format'), strtotime($selected_date2))."'");
		
		$where=implode(' AND ', $where_arr);
		//echo $where;
		
		# Con esto guardo las condiciones extra en session.
		$this->session->set_flashdata('where', $where);

		#Guardo en sesion la URL de la página para volver a ella al terminar acciones como pagar, anular, etc..
		$this->session->set_userdata('return_url', $this->uri->uri_string());

		//print_r($buttons);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		
		$grid_js = build_grid_js('flex1',site_url("/facturacion/payment_list_all"),$colModel,'datetime','desc',$gridParams,$buttons);
		
		
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
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
		
		if($this->redux_auth->logged_in()) $data['page']='gestion/payment_list_all';		
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	
	}





# -------------------------------------------------------------------
#  Grid con información de pagos hecha, por usuario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all_by_user($user)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		# Control de acceso a usuarios. Solo pueden ver los datos de un usuario, el propio usuario o los gestores y admins
		if($user != $profile->id && $profile->group > 3 ) {
			echo "Acceso no permitido";
			exit();
		}
		
		# Grid de datos
		$colModel = array();
		$colModel['id'] = array('ID',90,FALSE,'center',1, TRUE);
		$colModel['id_type_desc'] = array('Tipo',90,FALSE,'center',0);
		$colModel['paymentway_desc'] = array('Forma pago',70,FALSE,'center',0, FALSE);
		$colModel['user_desc'] = array('Usuario',130, FALSE, 'center',0, TRUE);
		$colModel['quantity'] = array('Cantidad',40,FALSE,'right',1);
		$colModel['status_desc'] = array('Estado',60,FALSE,'center',1, FALSE);
		$colModel['date'] = array('Fecha',60,FALSE,'center',0, FALSE);
		$colModel['time'] = array('Hora',35,FALSE,'center',0, FALSE);
		$colModel['description'] = array('Concepto',210,FALSE,'center',0, FALSE);

/*		
		$colModel['action_cancel'] = array('Cancelar',40, FALSE, 'center',0, FALSE , 'cancelarReserva');
		$colModel['action_change'] = array('Mod. Hora',45, FALSE, 'center',0);
		$colModel['action_payment'] = array('Cobrar',35, FALSE, 'center',0, FALSE , 'cobrarReserva');
		$colModel['action_light'] = array('Set Luz',35, FALSE, 'center',0, FALSE , 'setLight');
		*/
		
		
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
		'title' => 'Listado de pagos',
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
		$buttons[] = array('Remesa','remesa','buttons');
//		$buttons[] = array($this->lang->line('deselect_all'),'delete','test');
//		$buttons[] = array('separator');
//		$buttons[] = array('Anular','delete','validarReserva');
		
		
		# Filtros a pasar al grid
		$where_arr=array();
		/*
			$selected_type=$this->input->post('id_type');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('id_user');
			$selected_status=$this->input->post('status');
			$selected_date1=$this->input->post('date1');
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));

		if($selected_type!="") array_push($where_arr, "courts.sport_type = '".$selected_type."'");
		if($selected_status!="") array_push($where_arr, "status = '".$selected_status."'");
		if($selected_paymentway!="") array_push($where_arr, "id_paymentway = '".$selected_paymentway."'");
		if($selected_date1!="") array_push($where_arr, "date(datetime) >= '".date($this->config->item('date_db_format'), strtotime($selected_date1))."'");
		if($selected_date2!="") array_push($where_arr, "date(datetime) <= '".date($this->config->item('date_db_format'), strtotime($selected_date2))."'");
		*/
		array_push($where_arr, "id_user = '".$user."'");
		$where=implode(' AND ', $where_arr);
		//echo $where;
		
		# Con esto guardo las condiciones extra en session.
		$this->session->set_flashdata('where', $where);

		#Guardo en sesion la URL de la página para volver a ella al terminar acciones como pagar, anular, etc..
		$this->session->set_userdata('return_url', $this->uri->uri_string());

		//print_r($buttons);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		
		$grid_js = build_grid_js('flex1',site_url("/facturacion/payment_list_all"),$colModel,'datetime','desc',$gridParams,$buttons);
		
		
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid' => TRUE), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			//'menu' => $this->load->view('menu', $menu, true),
			//'navigation' => $this->load->view('navigation', '', true),
			//'footer' => $this->load->view('footer', '', true),				
			//'filters' => $this->load->view('gestion/filters', array('search_fields' => $this->simpleSearchFields(), 'fieldset_width' => 710), true),
			'form' => 'frmGrid', 
			//'enable_grid' => 1,
			'js_grid' => $grid_js,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->redux_auth->logged_in()) $data['page']='gestion/payment_list_all';		
		
		# Carga de la vista principal
		$this->load->view('gestion/payment_list_by_user', $data);
	
	}




	
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas
# -------------------------------------------------------------------
	function payment_list_all()
	{
		$this->load->model('Payment_model', 'pagos', TRUE);

		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('id','id_type', 'id_element', 'id_user', 'id_transaction','desc_user', 'status', 'datetime', 'quantity', 'description', 'zz_paymentway.description','zz_payment_status.description',	'id_user','desc_user');
		
		$this->flexigrid->validate_post('datetime','desc',$valid_fields);
		
		$add_where=$this->session->flashdata('where');

		$records = $this->pagos->get_global_list($add_where, $this->flexigrid->post_info['sortname'], $this->flexigrid->post_info['sortorder']);
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		 $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
		 $record_items = array();
		foreach ($records['records']->result() as $row)
		{
			$registro=array();
			
			if($row->id_user) $usuario = $row->first_name.' '.$row->last_name;
			else $usuario = $row->desc_user;
			if(trim($usuario)=="") $usuario="No registrado";
			
			
			$registro = array(
				$row->id,
				$row->id,
				$row->id_type_desc,
				$this->lang->line($row->paymentway_desc),
				$usuario,
				$row->quantity,
				$this->lang->line($row->status_desc),
				date($this->config->item('reserve_date_filter_format') ,strtotime($row->datetime)),
				date($this->config->item('reserve_hour_filter_format') ,strtotime($row->datetime)),
				$row->description
			);	
			//print("<pre>");print_r($row);print("</pre>");
			$record_items[] = $registro;
		}
		//log_message('debug', $record_items[0][0]);
		//Print please
		//print("<pre>");print_r($record_items);print("</pre>");
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	


	
# -------------------------------------------------------------------
# Funcion que devuelve listado en JSON de todas los pagos para el jqGrid
# -------------------------------------------------------------------
	function jqgrid_list_all($add_params = NULL, $response = NULL)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		
		$where = '';
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			if($user_group > '3') $where = "payments.id_user = '".$profile->id."'";
		}	else {
			exit(0);
		}
		
//print_r($_POST);
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
		
		//if($req_param['num_rows']==0 || $req_param['num_rows'] == '') $req_param['num_rows'] = 20; 
		# Si se está filtrando por algo
		//if($this->input->post( "_search", TRUE )=='true' && $this->input->post( "searchField", TRUE )!="" && $this->input->post( "searchOper", TRUE )!="" && "search_operator" => $this->input->post( "searchOper", TRUE )!="") {
		if($req_param['search']=='true' && $req_param['search_field']!='' && $req_param['search_operator']!='' && $req_param['search_str']!='') {
			$this->load->helper('jqgrid');
			if(trim($where)!="") $where .= ' AND ';
			
			$condicion_calculada = getJqgridFilter ($req_param['search_field'], $req_param['search_operator'], $req_param['search_str']);
			
			$where .= $condicion_calculada;
			
		}


		if(isset($add_params)) {
			switch($add_params) {
				case "future":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(payments.fecha_valor >= '".date($this->config->item('date_db_format'))."')";
				break;
				case "today":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(DATE(payments.datetime) = '".date($this->config->item('date_db_format'))."')";
				break;
				case "month":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(MONTH(payments.datetime) = MONTH('".date($this->config->item('date_db_format'))."'))";
				break;
				case "week":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(WEEK(payments.datetime,3) = WEEK('".date($this->config->item('date_db_format'))."',3))";
				break;
				case "last_week":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(WEEK(payments.datetime,3) = (WEEK('".date($this->config->item('date_db_format'))."',3)-1))";
				break;
				case "last_month":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(MONTH(payments.datetime) = MONTH('".date($this->config->item('date_db_format'))."')-1)";
				break;
				case "remesa":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(payments.id_paymentway = '4' AND payments.status IN (6, 7, 9) AND payments.remesa is not null and payments.remesa <> '')";
				break;
				case "remesa_pend":
					if(trim($where)!="") $where .= ' AND ';
					$where .= "(payments.id_paymentway = '4' AND payments.status = '2' AND (payments.remesa is null or payments.remesa = ''))";
				break;
			}
		}

		
		$req_param['where'] = $where;
		if(isset($add_params) && is_array($add_params) && $add_params['where'] != '') { if(trim($req_param['where']) != '') $req_param['where'] .= ' AND '; $req_param['where'] .= $add_params['where'];}
		
		$data->page = $this->input->post( "page", TRUE );
		$data->records = $this->pagos->get_data($req_param,"count");
		if($req_param['num_rows']==0 || $req_param['num_rows'] == '') $num_rows = 1; 
		else $num_rows = $req_param['num_rows'];
		//exit($data->records.'-'.$num_rows);
		$data->total = ceil ($data->records /$num_rows );
		//exit($data->total );
		$records = $this->pagos->get_data ($req_param, 'none')->result_array();
		$data->rows = $records;

		if(isset($response)) {
			if($response == 'return') {
				return $data;
			} elseif($response == 'return_rows') {
				return $data->rows;
			}
		}
		
		echo json_encode ($data );
		exit( 0 );


		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		
		/*
		$valid_fields = array('id','id_type', 'id_element', 'id_user', 'id_transaction','desc_user', 'status', 'datetime', 'quantity', 'description', 'zz_paymentway.description','zz_payment_status.description',	'id_user','desc_user');
		
		$this->flexigrid->validate_post('datetime','desc',$valid_fields);
		
		$add_where=$this->session->flashdata('where');

		$records = $this->pagos->get_global_list($add_where, $this->flexigrid->post_info['sortname'], $this->flexigrid->post_info['sortorder']);
		$this->output->set_header($this->config->item('json_header'));
		*/
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		 $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
		 $record_items = array();
		foreach ($records['records']->result() as $row)
		{
			$registro=array();
			
			if($row->id_user) $usuario = $row->first_name.' '.$row->last_name;
			else $usuario = $row->desc_user;
			if(trim($usuario)=="") $usuario="No registrado";
			
			
			$registro = array(
				$row->id,
				$row->id,
				$row->id_type_desc,
				$this->lang->line($row->paymentway_desc),
				$usuario,
				$row->quantity,
				$this->lang->line($row->status_desc),
				date($this->config->item('reserve_date_filter_format') ,strtotime($row->datetime)),
				date($this->config->item('reserve_hour_filter_format') ,strtotime($row->datetime)),
				$row->description
			);	
			//print("<pre>");print_r($row);print("</pre>");
			$record_items[] = $registro;
		}
		//log_message('debug', $record_items[0][0]);
		//Print please
		//print("<pre>");print_r($record_items);print("</pre>");
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	

# -------------------------------------------------------------------
# Funcion que devuelve listado en JSON de todas los pagos por usuario para el jqGrid
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
		
		$parametros = array('where' => "payments.id_user = '".$user."'");
		$this->jqgrid_list_all($parametros);
}





#############
# Creación de filtros para pagos
###############

function simpleSearchFields($options=array())
	{
			$this->load->model('Payment_model', 'pagos', TRUE);
			$this->load->model('redux_auth_model', 'users', TRUE);

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
			
			$selected_type=$this->input->post('id_type');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('id_user');
			$selected_status=$this->input->post('status');
			$selected_date1=$this->input->post('date1');
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));
			
			
			# Filtro de Tipo de pago
			if(!isset($options['id_type']) || $options['id_type']=="1") {
				$optionss=$this->pagos->getPaymentTypesArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'id_type', 'desc' => $this->lang->line('id_type'), 'default' => $selected_type, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'id_type', 'type' => 'select', 'value' => $optionss);
					array_push($filter_array, $equipo);
				}
			}
			
		
			# Filtro de FORMA DE PAGO
			if(!isset($options['paymentway']) || $options['paymentway']=="1") {
				$optionss=$this->pagos->getPaymentWaysArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'paymentway', 'desc' => $this->lang->line('payment_ways'), 'default' => $selected_paymentway, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'paymentway', 'type' => 'select', 'value' => $optionss);
					array_push($filter_array, $equipo);
				}
			}
			
			
			
			# Filtro de estado del pago
			if(!isset($options['status']) || $options['status']=="1") {
				$optionss=$this->pagos->getStatusArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'status', 'desc' => $this->lang->line('payment_status'), 'default' => $selected_status, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'status', 'type' => 'select', 'value' => $optionss);
					array_push($filter_array, $equipo);
				}
			}
			
		
			
			# Filtro de Usuarios
			if(!isset($options['id_user']) || $options['id_user']=="1") {
				$optionss=$this->users->getActiveUsersArray();
				if(isset($optionss) && count($optionss)!=1) {
					$equipo=array('name' => 'id_user', 'desc' => $this->lang->line('user'), 'default' => $selected_status, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'id_user', 'type' => 'select', 'value' => $optionss);
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
# -------------------------------------------------------------------
# Funcion que genera un fichero de remesa bancaria con todos los pagos 'planificados' con modo de pago 'transferencia' y sin número de remesas
# -------------------------------------------------------------------

	function genera_remesa()
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->library('aeb19writter');
		$this->load->config('pagos');
		
		$returnUrl = $this->input->post('returnUrl');
		$id_user = $this->input->post('id_user');
		if(!isset($returnUrl) || $returnUrl == '') {
			$returnUrl = site_url('facturacion/list_all/remesa');
		}
		
		//echo $returnUrl.'<br>';
		//echo $id_user.'<br>';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('payment_managment_permission');
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



		$req_param = array (
				"orderby" => 'id_user',
				"orderbyway" => 'asc',
				"page" => '',
				"num_rows" => '',
				//"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				//"search_field" => $this->input->post( "searchField", TRUE ),
				//"search_operator" => $this->input->post( "searchOper", TRUE ),
				//"search_str" => $this->input->post( "searchString", TRUE ),
		);

		$where = "(payments.id_paymentway = '4' AND payments.status = '2' AND (payments.remesa is null or payments.remesa = ''))";
		$req_param['where'] = $where;
		$pagos_pendientes = $this->pagos->get_data($req_param,"all")->result_array();
		//echo '<pre>';print_r($pagos_pendientes);//exit();
		//echo json_encode ($data );
		
		
		###############
		# Parametrizacion de emision de remesa
		###############
		
		//Número de cuenta ficticio, para el ordenante y el presentador
		$cuenta = $this->config->item('aeb19_bank_account');

		//CIF ficticio, para el ordenante y el presentador
		$cif = $this->config->item('aeb19_business_cif');

		//Nombre del presentador y del ordenante
		$empresa = $this->config->item('aeb19_business_name');
		
		//Asignamos los campos del presentador
		//El código presentador hay que indicarlo con ceros a la derecha, así que lo hacemos a mano
		$this->aeb19writter->insertarCampo('codigo_presentador', str_pad($cif, 12, '0', STR_PAD_RIGHT));
		$this->aeb19writter->insertarCampo('fecha_fichero', date('dmy'));
		$this->aeb19writter->insertarCampo('nombre_presentador', $empresa);
		$this->aeb19writter->insertarCampo('entidad_receptora', $cuenta[0]);
		$this->aeb19writter->insertarCampo('oficina_presentador', $cuenta[1]);
		
		//La fecha de cargo, que será dentro de 2 días
		$fechaCargo = date('dmy', strtotime('+2 day'));
		
		//Asignamos los campos del ordenante y guardamos el registro
		$this->aeb19writter->insertarCampo('codigo_ordenante', str_pad($cif, 12, '0', STR_PAD_RIGHT));
		$this->aeb19writter->insertarCampo('fecha_cargo', $fechaCargo);
		$this->aeb19writter->insertarCampo('nombre_ordenante', $empresa);
		$this->aeb19writter->insertarCampo('cuenta_abono_ordenante', implode('', $cuenta));
		$this->aeb19writter->guardarRegistro('ordenante');
		
		//Establecemos el código del ordenante para los registros obligatorios
		$this->aeb19writter->insertarCampo('ordenante_domiciliacion' , str_pad($cif, 12, '0', STR_PAD_RIGHT));

		$pagos_procesados = array();
		$pagos_unificados = array(); $usuario_tratado = ''; $usuario_nombre = ''; $cantidad = 0; $concepto = ''; $identificador = '';
		//print('<pre>'); print_r($pagos_pendientes);//exit();
		foreach ($pagos_pendientes as $pago) {
			//echo '<br>'.$identificador; 
			if($usuario_tratado != $pago['id_user'] && $usuario_tratado != '') {
				
				if(intval($cuenta_bancaria) != 0) {
					array_push($pagos_unificados, array (
							'id' => $identificador,
							'quantity' => $cantidad,
							'id_user' => $usuario_tratado,
							'desc_user' => $usuario_nombre,
							'account' => $cuenta_bancaria,
							'description' => $concepto
						)
					);
			}
				
			}
			
			$cuenta_bancaria_tmp = $this->users->getUserBank($pago['id_user']);
			$cuenta_bancaria = $cuenta_bancaria_tmp[0];
			//echo 'Banco para usuario '.$pago['id_user'].': '.$cuenta_bancaria;
			if(trim($cuenta_bancaria)!='' && intval($cuenta_bancaria)!=0) {
			
				if($usuario_tratado == '' || $usuario_tratado != $pago['id_user']) {
					$usuario_tratado = $pago['id_user'];
					$usuario_nombre = $cuenta_bancaria_tmp[1];
					$cantidad = $pago['quantity'];
					$concepto = $pago['description'];
					$identificador = $pago['id'];
				} else {
					$cantidad = $cantidad + $pago['quantity'];
					$concepto = 'Pagos varios';
				}
				
				array_push($pagos_procesados, $pago['id']);
			}
			
		}
		# El ultimo usuario
				if(intval($cuenta_bancaria) != 0) {
					array_push($pagos_unificados, array (
							'id' => $identificador,
							'quantity' => $cantidad,
							'id_user' => $usuario_tratado,
							'desc_user' => $usuario_nombre,
							'account' => $cuenta_bancaria,
							'description' => $concepto
						)
					);
				}



		//echo '<pre>';print_r($pagos_unificados);exit();
		
		foreach ($pagos_unificados as $pago) {
			
	    //El % IVA aplicado en la factura
	    $iva = $this->config->item('payment_general_iva');
	    //El importe de IVA aplicado en la factura
	    $importeIva = round((($pago['quantity']*$iva*100)/(100+($iva*100))), 2);
	    //Total de la factura, IVA incluido
	    $totalFactura = $pago['quantity'];
	    $netoFactura = $pago['quantity'] - $importeIva;
	
			//echo $importeIva.'-'.$netoFactura.'-'.$totalFactura;
	    //Con el codigo_referencia_domiciliacion podremos referenciar la domiciliación
	    $this->aeb19writter->insertarCampo('codigo_referencia_domiciliacion', 'fra-'.$pago['id']);
	    //Cliente al que le domiciliamos
	    $this->aeb19writter->insertarCampo('nombre_cliente_domiciliacion', $pago['desc_user']);
	    //Cuenta del cliente en la que se domiciliará la factura
	    $this->aeb19writter->insertarCampo('cuenta_adeudo_cliente', $pago['account']);
	    //El importe de la domiciliación (tiene que ser en céntimos de euro y con el IVA aplicado)
	    $this->aeb19writter->insertarCampo('importe_domiciliacion', ($totalFactura * 100));
	    //Código para asociar la devolución en caso de que ocurra
	    $this->aeb19writter->insertarCampo('codigo_devolucion_domiciliacion', $pago['id']);
	    //Código interno para saber a qué corresponde la domiciliación
	    $this->aeb19writter->insertarCampo('codigo_referencia_interna', 'fra-'.$pago['id']);
	
	    //Preparamos los conceptos de la domiciliación, en un array
	    //Disponemos de 80 caracteres por línea (elemento del array). Más caracteres serán cortados
	    //El índice 8 y 9 contendrían el sexto registro opcional, que es distinto a los demás
	    $conceptosDom = array();
	    //Los dos primeros índices serán el primer registro opcional
	    $conceptosDom[] = str_pad("Recibo por ".$pago['description'], 80, ' ', STR_PAD_RIGHT);
	    $conceptosDom[] = str_pad('emitido el ' . date('d/m/Y') . ' para: ', 40, ' ', STR_PAD_RIGHT) . str_pad("CIF: ES-$cif", 40, ' ', STR_PAD_RIGHT);
	    //Los dos segundos índices serán el segundo registro opcional
	    $conceptosDom[] = str_pad($pago['desc_user'], 40, ' ', STR_PAD_RIGHT);
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT) . 'Base imponible:' . str_pad(number_format($netoFactura, 2, ',', '.') . ' EUR', 25, ' ', STR_PAD_LEFT);
	    //Los dos terceros índices serán el tercer registro opcional
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
	        'IVA ' . str_pad(number_format($iva * 100, 2, ',', '.'), 2, '0', STR_PAD_LEFT) . '%:'.
	        str_pad(number_format($importeIva, 2, ',', '.') . ' EUR', 29, ' ', STR_PAD_LEFT);
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
	        'Total:' . str_pad(number_format($totalFactura, 2, ',', '.') . ' EUR', 34, ' ', STR_PAD_LEFT);
	
	    //Añadimos la domiciliación
	    $this->aeb19writter->guardarRegistro('domiciliacion', $conceptosDom);			
		}
		
		//$momento = date('YmdHis');
		$momento = time();
		# Marco los pagos como procesados por esta remesa
		if(count($pagos_procesados)>0) $this->pagos->setRemesa($momento, $pagos_procesados);
		
		//Construimos el documento y lo mostramos por pantalla
		$this->output->set_header("Content-Type: application/n19");
		$this->output->set_header("Content-Disposition: attachment;filename=remesa_".$momento.".n19");
		//$this->output->set_output('{'.$this->aeb19writter->construirArchivo().'}');
		$this->output->set_output($this->aeb19writter->construirArchivo());
		return NULL;
		exit(0);
		echo "{$this->aeb19writter->construirArchivo()}";
		
}






# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que genera un fichero de remesa bancaria con todos los pagos 'planificados' con modo de pago 'transferencia' y sin número de remesas
# -------------------------------------------------------------------

	function regenera_remesa($idremesa)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->library('aeb19writter');
		$this->load->config('pagos');
		
		$returnUrl = $this->input->post('returnUrl');
		$id_user = $this->input->post('id_user');
		if(!isset($returnUrl) || $returnUrl == '') {
			$returnUrl = site_url('facturacion/list_all/remesa');
		}
		
		//echo $returnUrl.'<br>';
		//echo $id_user.'<br>';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('payment_managment_permission');
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



		$req_param = array (
				"orderby" => 'id_user',
				"orderbyway" => 'asc',
				"page" => '',
				"num_rows" => '',
				//"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				//"search_field" => $this->input->post( "searchField", TRUE ),
				//"search_operator" => $this->input->post( "searchOper", TRUE ),
				//"search_str" => $this->input->post( "searchString", TRUE ),
		);

		$where = "(payments.id_paymentway = '4' AND payments.status = '9' AND (payments.remesa = '".$idremesa."'))";
		$req_param['where'] = $where;
		$pagos_pendientes = $this->pagos->get_data($req_param,"all")->result_array();
		//echo '<pre>';print_r($pagos_pendientes);//exit();
		//echo json_encode ($data );
		
		
		###############
		# Parametrizacion de emision de remesa
		###############
		
		//Número de cuenta ficticio, para el ordenante y el presentador
		$cuenta = $this->config->item('aeb19_bank_account');

		//CIF ficticio, para el ordenante y el presentador
		$cif = $this->config->item('aeb19_business_cif');

		//Nombre del presentador y del ordenante
		$empresa = $this->config->item('aeb19_business_name');
		
		//Asignamos los campos del presentador
		//El código presentador hay que indicarlo con ceros a la derecha, así que lo hacemos a mano
		$this->aeb19writter->insertarCampo('codigo_presentador', str_pad($cif, 12, '0', STR_PAD_RIGHT));
		$this->aeb19writter->insertarCampo('fecha_fichero', date('dmy'));
		$this->aeb19writter->insertarCampo('nombre_presentador', $empresa);
		$this->aeb19writter->insertarCampo('entidad_receptora', $cuenta[0]);
		$this->aeb19writter->insertarCampo('oficina_presentador', $cuenta[1]);
		
		//La fecha de cargo, que será dentro de 2 días
		$fechaCargo = date('dmy', strtotime('+2 day'));
		
		//Asignamos los campos del ordenante y guardamos el registro
		$this->aeb19writter->insertarCampo('codigo_ordenante', str_pad($cif, 12, '0', STR_PAD_RIGHT));
		$this->aeb19writter->insertarCampo('fecha_cargo', $fechaCargo);
		$this->aeb19writter->insertarCampo('nombre_ordenante', $empresa);
		$this->aeb19writter->insertarCampo('cuenta_abono_ordenante', implode('', $cuenta));
		$this->aeb19writter->guardarRegistro('ordenante');
		
		//Establecemos el código del ordenante para los registros obligatorios
		$this->aeb19writter->insertarCampo('ordenante_domiciliacion' , str_pad($cif, 12, '0', STR_PAD_RIGHT));

		$pagos_procesados = array();
		$pagos_unificados = array(); $usuario_tratado = ''; $usuario_nombre = ''; $cantidad = 0; $concepto = ''; $identificador = '';
		//print('<pre>'); print_r($pagos_pendientes);//exit();
		foreach ($pagos_pendientes as $pago) {
			//echo '<br>'.$identificador; 
			if($usuario_tratado != $pago['id_user'] && $usuario_tratado != '') {
				
				if(intval($cuenta_bancaria) != 0) {
					array_push($pagos_unificados, array (
							'id' => $identificador,
							'quantity' => $cantidad,
							'id_user' => $usuario_tratado,
							'desc_user' => $usuario_nombre,
							'account' => $cuenta_bancaria,
							'description' => $concepto
						)
					);
			}
				
			}
			
			$cuenta_bancaria_tmp = $this->users->getUserBank($pago['id_user']);
			$cuenta_bancaria = $cuenta_bancaria_tmp[0];
			//echo 'Banco para usuario '.$pago['id_user'].': '.$cuenta_bancaria;
			if(trim($cuenta_bancaria)!='' && intval($cuenta_bancaria)!=0) {
			
				if($usuario_tratado == '' || $usuario_tratado != $pago['id_user']) {
					$usuario_tratado = $pago['id_user'];
					$usuario_nombre = $cuenta_bancaria_tmp[1];
					$cantidad = $pago['quantity'];
					$concepto = $pago['description'];
					$identificador = $pago['id'];
				} else {
					$cantidad = $cantidad + $pago['quantity'];
					$concepto = 'Pagos varios';
				}
				
				array_push($pagos_procesados, $pago['id']);
			}
			
		}
		# El ultimo usuario
				if(intval($cuenta_bancaria) != 0) {
					array_push($pagos_unificados, array (
							'id' => $identificador,
							'quantity' => $cantidad,
							'id_user' => $usuario_tratado,
							'desc_user' => $usuario_nombre,
							'account' => $cuenta_bancaria,
							'description' => $concepto
						)
					);
				}



		//echo '<pre>';print_r($pagos_unificados);exit();
		
		foreach ($pagos_unificados as $pago) {
			
	    //El % IVA aplicado en la factura
	    $iva = $this->config->item('payment_general_iva');
	    //El importe de IVA aplicado en la factura
	    $importeIva = round((($pago['quantity']*$iva*100)/(100+($iva*100))), 2);
	    //Total de la factura, IVA incluido
	    $totalFactura = $pago['quantity'];
	    $netoFactura = $pago['quantity'] - $importeIva;
	
			//echo $importeIva.'-'.$netoFactura.'-'.$totalFactura;
	    //Con el codigo_referencia_domiciliacion podremos referenciar la domiciliación
	    $this->aeb19writter->insertarCampo('codigo_referencia_domiciliacion', 'fra-'.$pago['id']);
	    //Cliente al que le domiciliamos
	    $this->aeb19writter->insertarCampo('nombre_cliente_domiciliacion', $pago['desc_user']);
	    //Cuenta del cliente en la que se domiciliará la factura
	    $this->aeb19writter->insertarCampo('cuenta_adeudo_cliente', $pago['account']);
	    //El importe de la domiciliación (tiene que ser en céntimos de euro y con el IVA aplicado)
	    $this->aeb19writter->insertarCampo('importe_domiciliacion', ($totalFactura * 100));
	    //Código para asociar la devolución en caso de que ocurra
	    $this->aeb19writter->insertarCampo('codigo_devolucion_domiciliacion', $pago['id']);
	    //Código interno para saber a qué corresponde la domiciliación
	    $this->aeb19writter->insertarCampo('codigo_referencia_interna', 'fra-'.$pago['id']);
	
	    //Preparamos los conceptos de la domiciliación, en un array
	    //Disponemos de 80 caracteres por línea (elemento del array). Más caracteres serán cortados
	    //El índice 8 y 9 contendrían el sexto registro opcional, que es distinto a los demás
	    $conceptosDom = array();
	    //Los dos primeros índices serán el primer registro opcional
	    $conceptosDom[] = str_pad("Recibo por ".$pago['description'], 80, ' ', STR_PAD_RIGHT);
	    $conceptosDom[] = str_pad('emitido el ' . date('d/m/Y') . ' para: ', 40, ' ', STR_PAD_RIGHT) . str_pad("CIF: ES-$cif", 40, ' ', STR_PAD_RIGHT);
	    //Los dos segundos índices serán el segundo registro opcional
	    $conceptosDom[] = str_pad($pago['desc_user'], 40, ' ', STR_PAD_RIGHT);
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT) . 'Base imponible:' . str_pad(number_format($netoFactura, 2, ',', '.') . ' EUR', 25, ' ', STR_PAD_LEFT);
	    //Los dos terceros índices serán el tercer registro opcional
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
	        'IVA ' . str_pad(number_format($iva * 100, 2, ',', '.'), 2, '0', STR_PAD_LEFT) . '%:'.
	        str_pad(number_format($importeIva, 2, ',', '.') . ' EUR', 29, ' ', STR_PAD_LEFT);
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
	        'Total:' . str_pad(number_format($totalFactura, 2, ',', '.') . ' EUR', 34, ' ', STR_PAD_LEFT);
	
	    //Añadimos la domiciliación
	    $this->aeb19writter->guardarRegistro('domiciliacion', $conceptosDom);			
		}
		
		//$momento = date('YmdHis');
		$momento = time();
		# Marco los pagos como procesados por esta remesa
		//if(count($pagos_procesados)>0) $this->pagos->setRemesa($momento, $pagos_procesados);
		
		//Construimos el documento y lo mostramos por pantalla
		$this->output->set_header("Content-Type: application/n19");
		$this->output->set_header("Content-Disposition: attachment;filename=remesa_".$idremesa.".n19");
		//$this->output->set_output('{'.$this->aeb19writter->construirArchivo().'}');
		$this->output->set_output($this->aeb19writter->construirArchivo());
		return NULL;
		exit(0);
		echo "{$this->aeb19writter->construirArchivo()}";
		
}


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que genera un fichero de remesa bancaria con todos los pagos 'planificados' con modo de pago 'transferencia' y sin número de remesas
# Sistema nuevo
# -------------------------------------------------------------------

	function genera_remesa_sepa($numero_remesa = NULL)
	{

		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->library('n19sepa_base');
		$this->load->library('n19sepa_DirectDebit');
		$this->load->library('n19sepa_exception');
		require_once($this->config->item('root_path').'system/application/libraries/DirectDebit/Transaction.php');
		$this->load->config('pagos');
		
		$returnUrl = $this->input->post('returnUrl');
		$id_user = $this->input->post('id_user');
		if(!isset($returnUrl) || $returnUrl == '') {
			$returnUrl = site_url('facturacion/list_all/remesa');
		}
		
		//echo $returnUrl.'<br>';
		//echo $id_user.'<br>';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('payment_managment_permission');
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



		$req_param = array (
				"orderby" => 'id_user',
				"orderbyway" => 'asc',
				"page" => '',
				"num_rows" => '',
				//"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				//"search_field" => $this->input->post( "searchField", TRUE ),
				//"search_operator" => $this->input->post( "searchOper", TRUE ),
				//"search_str" => $this->input->post( "searchString", TRUE ),
		);

		$where = "(payments.id_paymentway = '4' AND payments.status = '2' AND (payments.remesa is null or payments.remesa = ''))";
		if(isset($numero_remesa) && $numero_remesa!='') $where = "(payments.remesa='".$numero_remesa."')";
		$req_param['where'] = $where;
		$pagos_pendientes = $this->pagos->get_data($req_param,"all")->result_array();
		//echo '<pre>';print_r($pagos_pendientes);//exit();
		//echo json_encode ($data );



		####################
		## Comienza la generacion de la remesa
		####################
		$momento = time();
		## Cabecera
		// Unique identifier for this job
		$SepaFile = new n19sepa_DirectDebit();
		$SepaFile->setMessageIdentification($momento);

		// Name of the party sending the job. Usually the creditor
		$SepaFile->setInitiatingPartyName($this->config->item('aeb19_business_name'));

		// Your own unique identifier for this batch
		$SepaFile->setPaymentInfoId($momento);

		// Account on which payment should be recieved
		$SepaFile->setCreditorIban($this->config->item('aeb19_bank_account_iban'));
		$SepaFile->setCreditorBic($this->config->item('aeb19_bank_account_bic'));

		// Creditor Scheme Identification. This might differ per bank. Example is for Rabobank
		$SepaFile->setCreditorId( $this->config->item('aeb19_bank_bic') );

		// Date on which the job should be executed
		$SepaFile->setRequestedExecutionDate(date('Y-m-d', strtotime('+2 day')));


		# Unificación de pagos para 
		$pagos_procesados = array();
		$pagos_unificados = array(); $usuario_tratado = ''; $usuario_nombre = ''; $cantidad = 0; $concepto = ''; $identificador = '';
		//print('<pre>'); print_r($pagos_pendientes);//exit();
		foreach ($pagos_pendientes as $pago) {
			//echo '<br>'.$identificador; 
			if($usuario_tratado != $pago['id_user'] && $usuario_tratado != '') {
				
				if(isset($cuenta_bancaria) && trim($cuenta_bancaria)!='') {
					array_push($pagos_unificados, array (
							'id' => $identificador,
							'quantity' => $cantidad,
							'id_user' => $usuario_tratado,
							'desc_user' => $usuario_nombre,
							'account' => $cuenta_bancaria,
							'bank_bic' => $bank_bic,
							'description' => $concepto
						)
					);
			}
				
			}
			
			$cuenta_bancaria_tmp = $this->users->getUserBankIBAN($pago['id_user']);
			//print_r($cuenta_bancaria_tmp);
			$cuenta_bancaria = $cuenta_bancaria_tmp[0];
			$bank_bic = $cuenta_bancaria_tmp[2];
			//echo '<br>Banco para usuario '.$pago['id_user'].': '.$cuenta_bancaria.': '.$bank_bic;
			//if(trim($cuenta_bancaria)!='' && trim($bank_bic)!='') 
			if(trim($cuenta_bancaria)!='' ) {
				//echo '<br>Banco para usuario '.$pago['id_user'].'('.$usuario_tratado.') : '.$cuenta_bancaria;
				if($usuario_tratado == '' || $usuario_tratado != $pago['id_user']) {
					$usuario_tratado = $pago['id_user'];
					$usuario_nombre = $cuenta_bancaria_tmp[1];
					$cantidad = $pago['quantity'];
					$concepto = $pago['description'];
					$identificador = $pago['id'];
					//echo '<br>Pago: '.$pago['id'];
				} else {
					$cantidad = $cantidad + $pago['quantity'];
					$concepto = 'Pagos varios';
				}
				
				array_push($pagos_procesados, $pago['id']);
			}
			
		}
		# El ultimo usuario
		if(intval($cuenta_bancaria) != 0 && intval($bank_bic) != 0) {
			array_push($pagos_unificados, array (
					'id' => $identificador,
					'quantity' => $cantidad,
					'id_user' => $usuario_tratado,
					'desc_user' => $usuario_nombre,
					'account' => $cuenta_bancaria,
					'bank_bic' => $bank_bic,
					'description' => $concepto
				)
			);
		}

		//print('<pre>'); print_r($pagos_unificados);exit();
		if(count($pagos_unificados)==0) {
				$this->session->set_userdata('error_message', 'No hay pagos pendientes con los que generar una remesa.');
				redirect(site_url(), 'Location'); 
				exit();	
		
		}
		#Comienzo a escribir las diferentes transacciones
		foreach ($pagos_unificados as $pago) {
			//El % IVA aplicado en la factura
			$iva = $this->config->item('payment_general_iva');
			//El importe de IVA aplicado en la factura
			$importeIva = round((($pago['quantity']*$iva*100)/(100+($iva*100))), 2);
			//Total de la factura, IVA incluido
			$totalFactura = $pago['quantity'];
			$netoFactura = $pago['quantity'] - $importeIva;
		
			$SepaFile->addTransaction(
				Sepa_DirectDebit_Transaction::factory()
					->setEndToEndId('fra-'.$pago['id']) // Unique identifier
					->setAmount($totalFactura)
					->setTransactionIdentifier('fra-'.$pago['id'])
					->setSignatureDate(date('Y-m-d', strtotime('+2 day')))
					->setDebtorName($pago['desc_user'])
					->setDebtorIban($pago['account'])
					->SetDebtorBic($pago['bank_bic'])
					->setTransactionDescription($pago['description'])
			);
		}



	$SepaFile = str_replace('&ordf;', '', $SepaFile);//exit();
	$SimpleXml = new SimpleXmlElement($SepaFile);
	$dom = dom_import_simplexml($SimpleXml)->ownerDocument;
	$dom->formatOutput = true;
	//echo $dom->saveXML();

	# Marco los pagos como procesados por esta remesa
	if(count($pagos_procesados)>0) $this->pagos->setRemesa($momento, $pagos_procesados);
	
	//Construimos el documento y lo mostramos por pantalla
	$this->output->set_header("Content-Type: application/n19");
	$this->output->set_header("Content-Disposition: attachment;filename=remesa_".$momento.".xml");
	//$this->output->set_output('{'.$this->aeb19writter->construirArchivo().'}');
	$this->output->set_output($dom->saveXML());
	return NULL;
	exit(0);

		exit();

		
		exit('<br>FIN');
		
		###############
		# Parametrizacion de emision de remesa
		###############
		
		//Número de cuenta ficticio, para el ordenante y el presentador
		$cuenta = $this->config->item('aeb19_bank_account');

		//CIF ficticio, para el ordenante y el presentador
		$cif = $this->config->item('aeb19_business_cif');

		//Nombre del presentador y del ordenante
		$empresa = $this->config->item('aeb19_business_name');
		
		//Asignamos los campos del presentador
		//El código presentador hay que indicarlo con ceros a la derecha, así que lo hacemos a mano
		$this->aeb19writter->insertarCampo('codigo_presentador', str_pad($cif, 12, '0', STR_PAD_RIGHT));
		$this->aeb19writter->insertarCampo('fecha_fichero', date('dmy'));
		$this->aeb19writter->insertarCampo('nombre_presentador', $empresa);
		$this->aeb19writter->insertarCampo('entidad_receptora', $cuenta[0]);
		$this->aeb19writter->insertarCampo('oficina_presentador', $cuenta[1]);
		
		//La fecha de cargo, que será dentro de 2 días
		$fechaCargo = date('dmy', strtotime('+2 day'));
		
		//Asignamos los campos del ordenante y guardamos el registro
		$this->aeb19writter->insertarCampo('codigo_ordenante', str_pad($cif, 12, '0', STR_PAD_RIGHT));
		$this->aeb19writter->insertarCampo('fecha_cargo', $fechaCargo);
		$this->aeb19writter->insertarCampo('nombre_ordenante', $empresa);
		$this->aeb19writter->insertarCampo('cuenta_abono_ordenante', implode('', $cuenta));
		$this->aeb19writter->guardarRegistro('ordenante');
		
		//Establecemos el código del ordenante para los registros obligatorios
		$this->aeb19writter->insertarCampo('ordenante_domiciliacion' , str_pad($cif, 12, '0', STR_PAD_RIGHT));

		$pagos_procesados = array();
		$pagos_unificados = array(); $usuario_tratado = ''; $usuario_nombre = ''; $cantidad = 0; $concepto = ''; $identificador = '';
		//print('<pre>'); print_r($pagos_pendientes);//exit();
		foreach ($pagos_pendientes as $pago) {
			//echo '<br>'.$identificador; 
			if($usuario_tratado != $pago['id_user'] && $usuario_tratado != '') {
				
				if(intval($cuenta_bancaria) != 0) {
					array_push($pagos_unificados, array (
							'id' => $identificador,
							'quantity' => $cantidad,
							'id_user' => $usuario_tratado,
							'desc_user' => $usuario_nombre,
							'account' => $cuenta_bancaria,
							'description' => $concepto
						)
					);
			}
				
			}
			
			$cuenta_bancaria_tmp = $this->users->getUserBank($pago['id_user']);
			$cuenta_bancaria = $cuenta_bancaria_tmp[0];
			//echo 'Banco para usuario '.$pago['id_user'].': '.$cuenta_bancaria;
			if(trim($cuenta_bancaria)!='' && intval($cuenta_bancaria)!=0) {
			
				if($usuario_tratado == '' || $usuario_tratado != $pago['id_user']) {
					$usuario_tratado = $pago['id_user'];
					$usuario_nombre = $cuenta_bancaria_tmp[1];
					$cantidad = $pago['quantity'];
					$concepto = $pago['description'];
					$identificador = $pago['id'];
				} else {
					$cantidad = $cantidad + $pago['quantity'];
					$concepto = 'Pagos varios';
				}
				
				array_push($pagos_procesados, $pago['id']);
			}
			
		}
		# El ultimo usuario
		if(intval($cuenta_bancaria) != 0) {
			array_push($pagos_unificados, array (
					'id' => $identificador,
					'quantity' => $cantidad,
					'id_user' => $usuario_tratado,
					'desc_user' => $usuario_nombre,
					'account' => $cuenta_bancaria,
					'description' => $concepto
				)
			);
		}



		//echo '<pre>';print_r($pagos_unificados);exit();
		
		foreach ($pagos_unificados as $pago) {
			
	    //El % IVA aplicado en la factura
	    $iva = $this->config->item('payment_general_iva');
	    //El importe de IVA aplicado en la factura
	    $importeIva = round((($pago['quantity']*$iva*100)/(100+($iva*100))), 2);
	    //Total de la factura, IVA incluido
	    $totalFactura = $pago['quantity'];
	    $netoFactura = $pago['quantity'] - $importeIva;
	
			//echo $importeIva.'-'.$netoFactura.'-'.$totalFactura;
	    //Con el codigo_referencia_domiciliacion podremos referenciar la domiciliación
	    $this->aeb19writter->insertarCampo('codigo_referencia_domiciliacion', 'fra-'.$pago['id']);
	    //Cliente al que le domiciliamos
	    $this->aeb19writter->insertarCampo('nombre_cliente_domiciliacion', $pago['desc_user']);
	    //Cuenta del cliente en la que se domiciliará la factura
	    $this->aeb19writter->insertarCampo('cuenta_adeudo_cliente', $pago['account']);
	    //El importe de la domiciliación (tiene que ser en céntimos de euro y con el IVA aplicado)
	    $this->aeb19writter->insertarCampo('importe_domiciliacion', ($totalFactura * 100));
	    //Código para asociar la devolución en caso de que ocurra
	    $this->aeb19writter->insertarCampo('codigo_devolucion_domiciliacion', $pago['id']);
	    //Código interno para saber a qué corresponde la domiciliación
	    $this->aeb19writter->insertarCampo('codigo_referencia_interna', 'fra-'.$pago['id']);
	
	    //Preparamos los conceptos de la domiciliación, en un array
	    //Disponemos de 80 caracteres por línea (elemento del array). Más caracteres serán cortados
	    //El índice 8 y 9 contendrían el sexto registro opcional, que es distinto a los demás
	    $conceptosDom = array();
	    //Los dos primeros índices serán el primer registro opcional
	    $conceptosDom[] = str_pad("Recibo por ".$pago['description'], 80, ' ', STR_PAD_RIGHT);
	    $conceptosDom[] = str_pad('emitido el ' . date('d/m/Y') . ' para: ', 40, ' ', STR_PAD_RIGHT) . str_pad("CIF: ES-$cif", 40, ' ', STR_PAD_RIGHT);
	    //Los dos segundos índices serán el segundo registro opcional
	    $conceptosDom[] = str_pad($pago['desc_user'], 40, ' ', STR_PAD_RIGHT);
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT) . 'Base imponible:' . str_pad(number_format($netoFactura, 2, ',', '.') . ' EUR', 25, ' ', STR_PAD_LEFT);
	    //Los dos terceros índices serán el tercer registro opcional
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
	        'IVA ' . str_pad(number_format($iva * 100, 2, ',', '.'), 2, '0', STR_PAD_LEFT) . '%:'.
	        str_pad(number_format($importeIva, 2, ',', '.') . ' EUR', 29, ' ', STR_PAD_LEFT);
	    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
	        'Total:' . str_pad(number_format($totalFactura, 2, ',', '.') . ' EUR', 34, ' ', STR_PAD_LEFT);
	
	    //Añadimos la domiciliación
	    $this->aeb19writter->guardarRegistro('domiciliacion', $conceptosDom);			
		}
		
		//$momento = date('YmdHis');
		$momento = time();
		# Marco los pagos como procesados por esta remesa
		if(count($pagos_procesados)>0) $this->pagos->setRemesa($momento, $pagos_procesados);
		
		//Construimos el documento y lo mostramos por pantalla
		$this->output->set_header("Content-Type: application/n19");
		$this->output->set_header("Content-Disposition: attachment;filename=remesa_".$momento.".n19");
		//$this->output->set_output('{'.$this->aeb19writter->construirArchivo().'}');
		$this->output->set_output($this->aeb19writter->construirArchivo());
		return NULL;
		exit(0);
		echo "{$this->aeb19writter->construirArchivo()}";
		
}

	
	function view_receipt($id_payment)
	{
		
		$this->load->model('Payment_model', 'pagos', TRUE);

		if($this->redux_auth->logged_in() && isset($id_payment) && $id_payment != '') {
			$profile=$this->redux_auth->profile();
			$visualization_permission = $this->config->item('payment_managment_permission');
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

		$info_tmp = $this->pagos->getPaymentById($id_payment);
		$info = $info_tmp[0];
		$payment_generate_receipt_option = $this->config->item('payment_generate_receipt_option');

		#Intento recuperar una dirección a la que redirigirnos. Si no, voy al listado de pagos
		$returnUrl = $this->session->userdata('returnUrl');
		$this->session->unset_userdata('returnUrl');
		if(!isset($returnUrl) || $returnUrl == '') $returnUrl = site_url('facturacion/list_all');
		
		//print($info->status."<pre>");print_r($info);print_r($payment_generate_receipt_option);exit();

		if(!isset($info) || !is_object($info) || count($info) <= 0 || !in_array($info->status, $payment_generate_receipt_option)) {
			$this->session->set_userdata('error_message', 'Pago no disponible para generar recibo.');
			redirect($returnUrl, 'Location'); 
			exit();			
		}
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		$data['main_content']='Redirigiendo...<br>&nbsp;<br><i>(Impresi&oacute;n de ticket lanzada en ventana emergente)</i><script type="text/javascript">
					var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=380, height=460, top=85, left=140";
					var pagina=\''.site_url('payment/view_receipt/'.$id_payment).'\';
					window.open(pagina,"",opciones);
					location.href=\''.$returnUrl.'\';</script>';

		
		
		$this->load->view('main', $data);
	}







# -------------------------------------------------------------------
#  genera un fichero de texto en el servidor con los datos de facturacion
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function exportacion ($opciones = NULL)
	{
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->library('payment');


		$exportacion = $this->payment->exportacion();
		exit();
		$exportacion = $this->payment->exportacion(array('formato' => 'array', 'opcion' => $opciones));

		//echo json_encode ($data );
		//exit( 0 );
	}
	

/* End of file facturacion.php */
/* Location: ./system/application/controllers/facturacion.php */
}