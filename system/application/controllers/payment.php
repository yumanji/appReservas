<?php

class Payment extends Controller {

	function Payment()
	{
		parent::Controller();	
		$this->lang->load('payment');
		$this->load->config('facturacion');
	}
	
	function index()
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');



		# opciones del menu
		//$menu=array('menu' => $this->app_common->get_menu_options());
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
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('gestion', $data);
	}




# -------------------------------------------------------------------
#  Listado general de las reservas
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all()
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');



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
		
		if($this->redux_auth->logged_in()) $data['page']='gestion/payment_list_all';		
		
		# Carga de la vista principal
		$this->load->view('gestion', $data);
	
	}





# -------------------------------------------------------------------
#  Grid con información de pagos hecha, por usuario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function list_all_by_user($user)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');



		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		# Grid de datos
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
		array_push($where_arr, "id_user = '".$user."'");
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
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			//'menu' => $this->load->view('menu', $menu, true),
			//'navigation' => $this->load->view('navigation', '', true),
			//'footer' => $this->load->view('footer', '', true),				
			//'filters' => $this->load->view('gestion/filters', array('search_fields' => $this->simpleSearchFields(), 'fieldset_width' => 710), true),
			'form' => 'frmGrid', 
			'enable_grid' => 1,
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
		$this->load->helper('flexigrid');
		$this->load->library('flexigrid');

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
# Funcion que recoge (con el index2.php) la respuesta OK del TPV virtual 
# -------------------------------------------------------------------
	function background_tpv_sermepa()
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->config->load('pagos');
		$this->config->load('pago_'.$this->config->item('tpv_library_prefix'));
		
		$order = $this->input->post('Ds_Order');
			$id_booking = intval(substr($order, 0, -6));	# Recojo el id del pago (quitando los seis ultimos caracteres del código, que representan el minuto y segundo de la operacion y el concepto del pago)
			$payment_type = substr($order, -2);	# Recojo el tipo de pago (los dos ultimos caracteres del código)
			
		$date = $this->input->post('Ds_Date');
		$time = $this->input->post('Ds_Hour');
		$secure_payment = $this->input->post('Ds_SecurePayment');
		$amount = $this->input->post('Ds_Amount');
		$currency = $this->input->post('Ds_Currency');
		$commerce = $this->input->post('Ds_MerchantCode');
		$terminal = $this->input->post('Ds_Terminal');
		$signature = $this->input->post('Ds_Signature');
		$response = $this->input->post('Ds_Response');
		$transaction_type = $this->input->post('Ds_TransactionType');
		$merchant_data = $this->input->post('Ds_MerchantData');
		$authorisation_code = $this->input->post('Ds_AuthorisationCode');
		$urlMerchant = $this->config->item('tpv_url_return');
		$clave = $this->config->item('tpv_palabra_secreta');
		
		$error = 0; $error_code = 0;
		$message = $amount.$order.$commerce.$currency.$response.$clave;
		$calculated_signature = strtoupper(sha1($message));
		log_message('debug', $message."  - ".$calculated_signature."\r\n");
		log_message('debug', 'Comunicacion de TPV recibida: '."\r\n".'Order: '.$order."\r\n".'Cantidad: '.$amount."\r\n".'Moneda: '.$currency."\r\n".'Transaction Type: '.$transaction_type."\r\n".'Fecha: '.$date."\r\n".'Hora: '.$time."\r\n".'Comercio: '.$commerce."\r\n".'Firma: '.$signature."\r\n".'Codigo autorizacion: '.$authorisation_code."\r\n".'Respuesta: '.$response."\r\n");
		
		if(!$error && strlen($order) < 10) {$error = 1; $error_code = 1;}
		if(!$error && $authorisation_code == "") {$error = 1; $error_code = 2;}
		if(!$error && intval($response) > "100") {$error = 1; $error_code = 3;}
		
		//print("<pre>");
		
		switch($payment_type) {
			case "re":
				$info = $this->reservas->getBookingInfoByRealid($id_booking);
				if(!is_array($info)) {
					$error = 1; $error_code = 4;
					log_message('debug','No aparece la reserva... error_code 4');
					} else {
					//print_r($info);
					log_message('debug',var_export($info, true));
					$pago = $this->pagos->getPaymentByTransaction($info['id_transaction']);
					if(!isset($pago)) {
						$error = 1; $error_code = 5;
						} else {
							log_message('debug',var_export($pago, true));
							//print_r($pago);
						}
				}				
				if(isset($pago)) $id_pago = 	$pago->id;
				else $id_pago = NULL;
				$payment_extra = array(
		       'id_payment' =>  $id_pago,
		       'transaction_num' => $order,
		       'payment_datetime' => $date.' '.$time,
		       'secure_payment' => $secure_payment,
		       'amount' => number_format((intval($amount)/100), 2),
		       'commerce_id' => $commerce,
		       'terminal' => $terminal,
		       'control' => $signature,
		       'response' => $response,
		       'authorisation_code' => $authorisation_code		
				);
		
				if($error == 1) {
					log_message('debug', 'ESta el error a 1');
					if (isset($pago)) $this->pagos->updatePaymentStatus('id', $pago->id, '7');
					log_message('debug', 'Va a entrar en setPaymentExtra');
					if(isset($pago)) $this->pagos->setPaymentExtra($pago->id, $payment_extra);
					log_message('debug', 'Vamos a cancelar la reserva');
					if(isset($info)) {
					log_message('debug', 'CAncelando....');
						$this->reservas->cancel_reserve($info['id_transaction'], $this->lang->line('payment_tpv_fail_cancel_message').' '.$error_code);
					}
				} else {
					$this->pagos->updatePaymentStatus('id', $pago->id, '9');
					log_message('debug', 'Va a entrar en setPaymentExtra');
					$this->pagos->setPaymentExtra($pago->id, $payment_extra);
					log_message('debug', 'Va a entrar en complete_reserve de '.$info['id_transaction']);
					$this->reservas->complete_reserve($info['id_transaction']);
					log_message('debug', 'sali de  complete_reserve');
				}
			break;
			
			# Prepago de bono monedero
			case "pr":
				if($error == 1) $this->pagos->updatePaymentStatus('id_transaction', $order, '7');
				else $this->pagos->updatePaymentStatus('id_transaction', $order, '9');
			break;
			
			default:
				$error = 1; $error_code = 99;
			break;
		}					
				
	}
	


	
# -------------------------------------------------------------------
# Funcion que recoge (con el index2.php) la respuesta KO del TPV virtual 
# -------------------------------------------------------------------
	function confirm_tpv_ko_sermepa($order)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		
		$payment_type = substr($order, -2);	# Recojo el tipo de pago (los dos ultimos caracteres del código)
		
		//$menu=array('menu' => $this->app_common->get_menu_options());
			
		//print("<pre>");
		$error = 0; $error_code = 0;
		switch($payment_type) {
			case "re":
				$id_payment = intval(substr($order, 0, -6));	# Recojo el id del pago (quitando los dos ultimos caracteres del código, que representan el minuto y segundo del pago y el concepto del pago)
				$info = $this->reservas->getBookingInfoByRealid($id_payment);
				if(!is_array($info)) {
					$error = 1; $error_code = 4;
					} else {
					//print_r($info);
					//log_message('debug',var_export($info, true));
					$pago = $this->pagos->getPaymentByTransaction($info['id_transaction']);
					if(!isset($pago)) {
						$error = 1; $error_code = 5;
						} else {

							$contenido = $this->load->view('reservas/payment', array('info' => $info, 'success' => "0"), TRUE);


						}
				}
			break;
			
			# Prepago de bono monedero
			case "pr":
				$this->pagos->updatePaymentStatus('id_transaction', $order, '7');
			break;
			
			default:
				$error = 1; $error_code = 99;
			break;
		}	

		//if($error == 1) $contenido = $this->load->view('payment/data_error', array('error_code' => $error_code), TRUE);
		$contenido = $this->load->view('reservas/payment', array('info' => $info, 'success' => "0"), TRUE);
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => 0, 'header_style' => 'cabecera_variable'), true),
			//'menu' => $this->load->view('menu', $menu, true),
			'mainDiv' => 'mainContentPercent',
			'main_content' => $contenido,
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		//print_r($data);
    $this->load->view('main', $data);			
		//print_r($_GET);
		//print_r($_POST);

	}
	

	
# -------------------------------------------------------------------
# Funcion que recoge (con el index2.php) la respuesta KO del TPV virtual 
# -------------------------------------------------------------------
	function confirm_tpv_ok_sermepa($order)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		
		
		if(isset($order)) {
			$payment_type = substr($order, -2);	# Recojo el tipo de pago (los dos ultimos caracteres del código)
		} else {
			$payment_type = ""; $id_payment = ""; 
		}
		
		//$menu=array('menu' => $this->app_common->get_menu_options());
			
		//print("<pre>");
		$error = 0; $error_code = 0;
		switch($payment_type) {
			
			# Pago de reserva
			case "re":
				$id_payment = intval(substr($order, 0, -6));	# Recojo el id del pago (quitando los dos ultimos caracteres del código, que representan el minuto y segundo del pago y el concepto del pago)
				$info = $this->reservas->getBookingInfoByRealid($id_payment);
				if(!is_array($info)) {
					$error = 1; $error_code = 4;
					} else {
					//print_r($info);
					//log_message('debug',var_export($info, true));
					$pago = $this->pagos->getPaymentByTransaction($info['id_transaction']);
					if(!isset($pago)) {
						$error = 1; $error_code = 5;
						} else {

							$contenido = $this->load->view('reservas/payment', array('info' => $info, 'success' => "1"), TRUE);


						}
				}
			break;
			
			# Prepago de bono monedero
			case "pr":
				$this->pagos->updatePaymentStatus('id_transaction', $order, '9');
			break;
			
			default:
				$error = 1; $error_code = 99;
			break;
		}	

		if($error == 1) $contenido = $this->load->view('payment/data_error', array('error_code' => $error_code), TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => 0, 'header_style' => 'cabecera_variable'), true),
			//'menu' => $this->load->view('menu', $menu, true),
			'mainDiv' => 'mainContentPercent',
			'main_content' => $contenido,
			'footer' => $this->load->view('footer', '', true),				
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');


		//print_r($data);
    $this->load->view('main', $data);			

	}
	




	
# -------------------------------------------------------------------
# Funcion que, dada una información de un pago a realizar (entre POST y SESSION) muestra las formas de pago y pide que se pague 
# -------------------------------------------------------------------
	function payment_request($payment_type, $id_transaction, $full = 0, $options = NULL)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->library('user_agent');
		

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_level=$profile->group;
			$user_name=$profile->username;
		}	else {
			$user_id=0;
			$user_level=9;
			$user_name=$this->lang->line('anonymous_user');
		}

		$paymentMethods = $this->session->flashdata('paymentMethods');
		//echo "<br>";
		//print_r($paymentMethods);
		$paymentLines = $this->session->flashdata('paymentLines');
		//echo "aaaa<br>";
		//print_r($paymentLines);
		$returnOkUrl = $this->session->flashdata('returnOkUrl');
		//echo "<br>".$returnOkUrl;
		$returnKoUrl = $this->session->flashdata('returnKoUrl');
		//echo "<br>".$returnKoUrl;
		//echo "<br>".$id_transaction."<br>";
		//exit();
		$prepaid_enabled = FALSE;
		
		if(count($paymentMethods)>0 && isset($paymentLines) && count($paymentLines)>0) {


			switch($payment_type) {
				case 1:
				case 98:
					$this->load->model('Reservas_model', 'reservas', TRUE);
					$this->load->model('Redux_auth_model', 'usuario', TRUE);
					
					$info=$this->reservas->getBookingInfoById($id_transaction);
					//exit();

					# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
					if(strlen($info['id'])<4) $order=sprintf("%04s", $info['id']).date('is').'re';
					else $order=$info['id'].date('is').'re';
					
					$paymentDescription = 'Reserva codigo '.$info['booking_code'];
					
					$available_prepaid_amount = 0;
					$bank_enabled = FALSE;
					if($info['user'] != 0) {
						$available_prepaid_amount = $this->usuario->getPrepaidCash($info['user']);
						
						$cuenta_tmp = $this->usuario->getUserBank($info['user']);
						$cuenta = $cuenta_tmp[0];
						if(isset($cuenta) && strlen($cuenta)>=20) $bank_enabled = TRUE;
						else $bank_enabled = FALSE;
					}
					//echo "<br>Disponible ".$available_prepaid_amount." del usuario ".$info['user']." para pagar ".$info['total_price'];
					if($available_prepaid_amount >= $info['total_price']) $prepaid_enabled = TRUE;
					
				break;
			}
	
			$this->session->set_flashdata('paymentMethods', $paymentMethods);
			$this->session->set_flashdata('paymentLines', $paymentLines);
			$this->session->set_flashdata('returnOkUrl', $returnOkUrl);
			$this->session->set_flashdata('returnKoUrl', $returnKoUrl);
			
			# Cargo la vista de formas de pago
			if($full == '1') {
				
				$paymnet_content= $this->load->view('payment/pago_fullsize', array('methods' => $paymentMethods, 'lines' => $paymentLines, 'order' => $order, 'paymentDescription' => $paymentDescription, 'payment_type' => $payment_type, 'prepaid_enabled' => $prepaid_enabled, 'bank_enabled' => $bank_enabled, 'transaction_id' => $id_transaction), TRUE);
				$data=array(
					'meta' => $this->load->view('meta', '', true),
					'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
					'menu' => $this->load->view('menu', '', true),
					'footer' => $this->load->view('footer', '', true),
					'main_content' => $paymnet_content,
					'info_message' => $this->session->userdata('info_message'),
					'error_message' => $this->session->userdata('error_message')
					);
					$this->session->unset_userdata('info_message');
					$this->session->unset_userdata('error_message');
				
		    $this->load->view('main', $data);
			}	else {
				
					if(in_array($this->agent->browser(), $this->config->item('special_browser'))) {
      			$added = array('added_footer' => 'Pagina adaptada a IE');
						$data=array(
							//'menu' => $this->load->view('menu', $menu, true),
							'user_name' => $user_name,
							//'search_fields' => $this->load->view('reservas/search_fields2', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true),
							//'result' => $this->load->view('reservas/simple_result', array('availability' => $availability_array, 'user_id' => $user_id, 'filters' => $this->load->view('reservas/search_fields', array('search_fields'=> $this->simpleSearchFields()), true)), true),
							//'validation_script' => $validation_script,
								'info_message' => $this->session->userdata('info_message'),
								'error_message' => $this->session->userdata('error_message')
							);
							$this->session->unset_userdata('info_message');
							$this->session->unset_userdata('error_message');
						
						if($this->redux_auth->logged_in()) {
							$data['meta']=$this->load->view('meta', '', true);
							$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
							$data['footer']=$this->load->view('footer', $added, true);			
						} else {
							$data['meta']=$this->load->view('meta_index', '', true);
							$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
							$data['footer']=$this->load->view('footer_index', $added, true);
						}
		      	$data['main_content']=$this->load->view('payment/pago_iexplorer', array('user_name' => $user_name, 'methods' => $paymentMethods, 'lines' => $paymentLines, 'order' => $order, 'paymentDescription' => $paymentDescription, 'payment_type' => $payment_type, 'prepaid_enabled' => $prepaid_enabled, 'bank_enabled' => $bank_enabled, 'transaction_id' => $id_transaction), true);
		      	$this->load->view('main', $data);
		      } else $this->load->view('payment/pago', array('methods' => $paymentMethods, 'lines' => $paymentLines, 'order' => $order, 'paymentDescription' => $paymentDescription, 'payment_type' => $payment_type, 'prepaid_enabled' => $prepaid_enabled, 'bank_enabled' => $bank_enabled, 'transaction_id' => $id_transaction));
				
			}
			
			

		} else {
			echo "Datos incorrectos";
		}

	}
	


# -------------------------------------------------------------------
# Funcion que realiza el registro del pago 
# -------------------------------------------------------------------
	function payment_request_tmp($method, $payment_type, $id_transaction)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		

		$paymentMethods = $this->session->flashdata('paymentMethods');
		//echo "<br>";
		//print_r($paymentMethods);
		$paymentLines = $this->session->flashdata('paymentLines');
		//echo "<br>";
		//print_r($paymentLines);
		$returnOkUrl = $this->session->flashdata('returnOkUrl');
		//echo "<br>".$returnOkUrl;
		$returnKoUrl = $this->session->flashdata('returnKoUrl');
		//echo "<br>".$returnKoUrl;
		//exit();
		switch($payment_type) {
			case "1":
				$this->load->model('Reservas_model', 'reservas', TRUE);
				$info=$this->reservas->getBookingInfoById($id_transaction);
				
				# A esta URL volverá al terminar el pago
				$UrlRetorno = site_url('/reservas/payd/'.$method.'/'.$id_transaction.'/'.time());
			break;
			case "98":
				$this->load->model('Reservas_model', 'reservas', TRUE);
				$info=$this->reservas->getBookingInfoById($id_transaction);
				$num_intervalos = $info['intervals'];
			
				//calculo de precio de luz
				$this->reservas->court = $info['id_court'];
		    $this->reservas->date = $info['date'];
		    $this->reservas->intervalo = $info['inicio'];
				if ($this->reservas->getPrice())
				{
					$price_light = $this->reservas->price_light; 
				}			
				$cargo_luz = $price_light * $num_intervalos;
				$result = $this->reservas->setLight($id_transaction, $num_intervalos);
				$info['total_price'] = $cargo_luz;
				$info['operation_desc'] = "Suplemento luz reserva ".$info['booking_code']." (".$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).")";
				
				# A esta URL volverá al terminar el pago
				$UrlRetorno = $returnOkUrl;
				//echo $UrlRetorno;
				//exit();
			break;
		}
		//print("<pre>"); print_r($info);exit();
		$success = 0; $estado_pago=0;
		switch($method) {
			case 'prepaid':
				//$estado_reserva=9;
				//$modo_pago=5;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				$this->load->model('Redux_auth_model', 'usuario', TRUE);	
				if($this->usuario->addPrepaidMovement($info['user'], floatval($info['total_price'] * (-1)), '1', 1, $id_transaction)) $success=1;
					
			break;
			case 'creditcard':
				$estado_reserva=9;
				$modo_pago=2;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//if(!$no_cost) {
					$this->pagos->id_type=$payment_type; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$id_transaction;
					$this->pagos->id_user=$info['user'];
					$this->pagos->desc_user=$info['user_desc'];
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description=$info['operation_desc'];
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				//} else $success=1;
			break;
			case 'tpv':
				//$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=6;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//if(!$no_cost) {
					$this->pagos->id_type=$payment_type; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$id_transaction;
					$this->pagos->id_user=$info['user'];
					$this->pagos->desc_user=$info['user_desc'];
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description=$info['operation_desc'];
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=9;
				//} else $success=1;
			break;
			
			case 'paypal':
				//$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=3;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//if(!$no_cost) {
					$this->pagos->id_type=$payment_type; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$id_transaction;
					$this->pagos->id_user=$info['user'];
					$this->pagos->desc_user=$info['user_desc'];
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago	;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description=$info['operation_desc'];
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=9;
				//} else $success=1;
			break;
			
			case 'cash':
				$estado_reserva=9;
				$estado_pago = 9;
				$modo_pago=1;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//echo "AA";exit();
				//if(!$no_cost) {
					$this->pagos->id_type=$payment_type; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$id_transaction;
					$this->pagos->id_user=$info['user'];
					$this->pagos->desc_user=$info['user_desc'];
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description=$info['operation_desc'];
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				//} else $success=1;
			break;
			
				case 'bank':
				$estado_reserva=9;
				$estado_pago = 2;
				$modo_pago=4;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//echo "AA";exit();
				//if(!$no_cost) {
					$this->pagos->id_type=$payment_type; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$id_transaction;
					$this->pagos->id_user=$info['user'];
					$this->pagos->desc_user=$info['user_desc'];
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago;	// Pago bancario pendiente
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description=$info['operation_desc'];
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				//} else $success=1;
			break;
			
			case 'reserve':
				//$estado_reserva=7;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, 0, $user_id, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				$success=5;
				$estado_pago=3;
			break;
		}
		//echo '<br>exito: '.$success;
		if($success) {
			$this->session->set_flashdata('paymentMethods', $paymentMethods);
			$this->session->set_flashdata('paymentLines', $paymentLines);
			//$this->session->set_flashdata('returnOkUrl', $returnOkUrl);
			
			# Si el pago es paypal o tpv, pongo estado=9 en el 'confirmation' para que pinte el aviso como temporal .. pinto el 1 si el pago quedará terminado
			if($estado_pago==5) $estado_final = 9;
			elseif($estado_pago==2) $estado_final = 9;	// Pago pendiente del banco
			elseif($estado_pago==3) $estado_final = 5;	// Solo reservado
			else $estado_final = 1;
			//echo '<br>'.$estado_final;
			if($returnOkUrl!="") $this->session->set_flashdata('returnOkUrl', $returnOkUrl);
			else $this->session->set_flashdata('returnOkUrl', site_url('reservas/booking_confirmation/'.$id_transaction.'/'.$estado_final.'/'.time()));
			$this->session->set_flashdata('returnKoUrl', $returnKoUrl);
//exit('<br>'.$UrlRetorno);
			redirect($UrlRetorno, 'Location'); exit();
		} else echo "ERROR en el pago";
		//exit();

}
	

	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que muestra un resumen de una reserva concreta
# -------------------------------------------------------------------

	function tooltip_info($id = NULL, $buttons = FALSE)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);


		if(isset($id) && trim($id) != "") {
			
			$info=$this->pagos->getPaymentById($id);
			
			$this->load->view('payment/tooltip_info', array('info' => $info, 'buttons' => $buttons));
			//$main_content.='<p>Partido en '.$this->config->item('club_name').' el '.$fecha.' - '.$this->config->item('app_name').' en la pista '.$info['reserva'].'.</p>';
			
		}

}


	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que permite crear un pago a medida
# -------------------------------------------------------------------

	function add_payment($status = 9)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('redux_auth_model', 'users', TRUE);

		$returnUrl = $this->input->post('returnUrl');
		$id_user = $this->input->post('id_user');
		if(!isset($returnUrl) || $returnUrl == '') {
			if(!isset($id_user) || $id_user == '') $returnUrl = site_url();
			else $returnUrl = site_url('users/pagos/'.$id_user);
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


		
		if($this->input->post('action') && $this->input->post('action')=="save") {
			$formapago = $this->app_common->changePaymentwayNotation($this->input->post('paymentway')); // Convierto el pago de descripcion a id
			//echo $formapago;
			if(($this->input->post('id_user')!="" || $this->input->post('user_description')!="") && $this->input->post('quantity')!="" && floatval($this->input->post('quantity'))>0 && $this->input->post('description')!="" && isset($formapago)) {
				//print("<pre>");print_r($_POST);//exit();
				//inserto una entrada en payments para corroborar el pago
				$this->load->model('payment_model', 'payment_model', TRUE);
				$usuario = $this->input->post('id_user');
				$usuario_desc = $this->users->getUserDesc($this->input->post('id_user'));
				if(!isset($usuario) || $usuario == '') { $usuario = '0'; $usuario_desc = $this->input->post('user_description'); }
				
				//$status = 9; // Ahora llega por parámetro
				$control_estado = $this->config->item('payment_creado_pendiente');
				if(in_array($formapago, $control_estado)) $status = 2;
				
				$cantidad = $this->input->post('quantity');
				if($status == 6 ) $cantidad = $cantidad * (-1);
				
				//exit('aa-'.$usuario.'-'.$usuario_desc);
				//relleno las variable necesarias
				$this->payment_model->id_type = '99';
				$this->payment_model->id_element = date('U');
				$this->payment_model->id_user = $usuario;
				$this->payment_model->desc_user = $usuario_desc;
				$this->payment_model->status = $status;
				$this->payment_model->id_paymentway = $formapago;
				$this->payment_model->quantity = $cantidad;
				$this->payment_model->datetime = date(DATETIME_DB);
				$this->payment_model->fecha_valor = date( $this->config->item('date_db_format'), strtotime($this->input->post('fecha_valor')));
				$this->payment_model->description = $this->input->post('description');
				$this->payment_model->id_transaction = $this->input->post('id_user').'-'.date('U');
				$this->payment_model->create_user = $this->session->userdata('user_id');
				$this->payment_model->create_time = date(DATETIME_DB);
				$this->payment_model->create_ip = $this->session->userdata('ip_address');
				//inserto el registro
				$resultado = $this->payment_model->setPayment();
				
				$this->session->set_userdata('info_message', 'Pago grabado correctamente.');
				
				$this->session->set_userdata('returnUrl', $returnUrl);
				
				$ticket = $this->config->item('payment_autoticket');
				if($status == 6 ) $ticket = FALSE;
				
				# Si la opción de mostrar automaticamente el ticket está activa, cambio el url de desstino para que se muestre
				if($ticket) $returnUrl = site_url('facturacion/view_receipt/'.$resultado);
				//exit ($returnUrl);
				redirect($returnUrl, 'Location'); 
				exit();

			}
		}
		
		$user_desc = '';
		if(isset($id_user) && $id_user != '') $user_desc = $this->users->getUserDesc($id_user);
		
		$paymentMethods = $this->pagos->getPaymentMethodsByUser($profile->group);
		$paymentMethods['prepaid'] = FALSE;	//Desactivo la forma de pago por bono monedero para esta cuestion
		//print_r($paymentMethods);
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		
		$description = 'Nuevo Pago';
		if($status == 6 ) $description = 'Nueva Devolucion';
		
		# Carga de datos para la vista
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => NULL), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'filters' => $this->load->view('reservas_gest/filters', array('search_fields' => $this->simpleSearchFields()), true),
			//'form' => 'formDetail',
			'page' => 'payment/new_generic',
			'paymentMethods' => $paymentMethods,
			'description' => $description,
			'returnUrl' => $returnUrl,
			'id_user' => $id_user,
			'user_desc' => $user_desc,
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');

		$this->load->view('main', $data);


}




	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que permite crear un pago a medida
# -------------------------------------------------------------------

	function change($action, $status, $id)
	{
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('redux_auth_model', 'users', TRUE);

		$returnUrl = $this->input->post('returnUrl');
		if(!isset($returnUrl) || $returnUrl == '') {
			$returnUrl = $this->session->userdata('returnUrl');
			$this->session->unset_userdata('returnUrl');
			if(!isset($returnUrl) || $returnUrl == '') $returnUrl = site_url('facturacion/list_all');
		}
		
		//echo $returnUrl.'<br>';
		//echo $id_user.'<br>';

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$payment_change_status_permission = $this->config->item('payment_change_status_permission');
			if(!$payment_change_status_permission[$profile->group]) { 
				$this->session->set_userdata('error_message', 'No tiene permisos para cambiar un pago.');
				redirect(site_url(), 'Location'); 
				exit();	
			}
		}	else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			$this->session->set_userdata('error_message', 'Pagina no accesible sin acceder a la aplicacion previamente.');
			redirect(site_url(), 'Location'); 
			exit();
		}


		
		//inserto una entrada en payments para corroborar el pago
		$this->load->model('payment_model', 'pagos', TRUE);
		$info = $this->pagos->getPaymentById($id);
		//echo'<pre>';print_r($info);print_r($_POST);//exit();
		if($action == 'status') {
				//inserto el registro
			if($status == 2) $opciones = $this->config->item('payment_change_status_pendiente_option');
			elseif($status == 6) $opciones = $this->config->item('payment_change_status_devuelto_option');
			elseif($status == 7) $opciones = $this->config->item('payment_change_status_cancelar_option');
			//echo $status.'--'.strval($info[0]->status); print_r($opciones);//exit();
			if(in_array($info[0]->status, $opciones)) {
				if($status != 6) {
					//echo'<pre>';print_r($info);print_r($_POST);exit();
					
					# Si llega el parámetro que indica que quiero cambiar el Datetime del pago, lo hago
					$fecha_cambio = $this->input->post('payable_date_tmp');
					if(isset($fecha_cambio) && $fecha_cambio!='') {
						list($day, $month, $year) = sscanf($fecha_cambio, '%02d-%02d-%04d');
						$date_ = new DateTime("$year-$month-$day ".date('H:i:s'));
						//$date_ = date_create_from_format('d-m-Y', );
						$fecha_cambio_mod = intval($date_->format('U'));
						$result = $this->pagos->updatePaymentDateTime('id', $id, $fecha_cambio_mod);
						//echo 'entro en fecha pago';
					}
					//echo '---'.$fecha_cambio_mod;
					
					# Si llega el parámetro que indica que quiero cambiar la cantidad del pago, lo hago
					$cantidad_cambio = $this->input->post('payable_quantity_tmp');
					if(isset($cantidad_cambio) && $cantidad_cambio != 0) $result = $this->pagos->updatePaymentQuantity($id, $cantidad_cambio);
		
					
					# Si llega el parámetro que indica que quiero cambiar la cantidad del pago, lo hago
					$formapago_cambio = $this->input->post('payable_paymentway_tmp');
					if(isset($formapago_cambio) && $formapago_cambio != 0 && $formapago_cambio != $info['id_paymentway']) $result = $this->pagos->updatePaymentPaymentway('id', $id, $formapago_cambio);

					$result = $this->pagos->updatePaymentStatus('id', $id, $status);
					//$info = $this->pagos->getPaymentById($id); print_r($info);
		//exit();
					
					
				} else {
					# Creo el pago de devolución
					$this->pagos->id_type=$info[0]->id_type;
					$this->pagos->id_element=$info[0]->id_element;
					$this->pagos->id_transaction=$info[0]->id_transaction;
					$this->pagos->id_user=$info[0]->id_user;
					$this->pagos->desc_user=$info[0]->desc_user;
					$this->pagos->id_paymentway=$info[0]->id_paymentway;
					$this->pagos->status=6;
					$this->pagos->quantity=($info[0]->quantity * (-1));
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->fecha_valor=$info[0]->fecha_valor;
					$this->pagos->description='Devolucion de '.$info[0]->description;
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					$this->pagos->setPayment();
				}
				$this->session->set_userdata('info_message', 'Realizado el cambio de estado del pago.');
			} else $this->session->set_userdata('error_message', 'Imposible actualizar pagos de este estado.');
		} elseif($action == 'remesa') {
				//inserto el registro
			if($status == 9 && $info[0]->status == 2) {
					//echo'<pre>';print_r($info);print_r($_POST);exit();
					
					# Si llega el parámetro que indica que quiero cambiar el Datetime del pago, lo hago
					$fecha_cambio = $this->input->post('payable_date_tmp');
					if(isset($fecha_cambio) && $fecha_cambio!='') {
						list($day, $month, $year) = sscanf($fecha_cambio, '%02d-%02d-%04d');
						$date_ = new DateTime("$year-$month-$day ".date('H:i:s'));
						//$date_ = date_create_from_format('d-m-Y', );
						$fecha_cambio_mod = intval($date_->format('U'));
						$result = $this->pagos->updatePaymentDateTime('id', $id, $fecha_cambio_mod);
						//echo 'entro en fecha pago';
					}
					//echo '---'.$fecha_cambio_mod;
					
					# Si llega el parámetro que indica que quiero cambiar la cantidad del pago, lo hago
					$cantidad_cambio = $this->input->post('payable_quantity_tmp');
					if(isset($cantidad_cambio) && $cantidad_cambio != 0) $result = $this->pagos->updatePaymentQuantity($id, $cantidad_cambio);
		
					
					# Si llega el parámetro que indica que quiero cambiar la cantidad del pago, lo hago
					$formapago_cambio = $this->input->post('payable_paymentway_tmp');
					if(isset($formapago_cambio) && $formapago_cambio != 0 && $formapago_cambio != $info['id_paymentway']) $result = $this->pagos->updatePaymentPaymentway('id', $id, $formapago_cambio);

					$result = $this->pagos->updatePaymentStatus('id', $id, $status);
					//$info = $this->pagos->getPaymentById($id); print_r($info);
		//exit();
					
				$this->session->set_userdata('info_message', 'Realizado el cambio de estado del pago.');
			} else $this->session->set_userdata('error_message', 'Imposible actualizar pagos de este estado.');
		} elseif($action == 'repay') {
				//inserto el registro
			$opciones = $this->config->item('payment_repay_option');

			//echo $status.'--'.strval($info[0]->status); print_r($info);print_r($opciones);exit();
			if(in_array($info[0]->status, $opciones)) {

				$result = $this->pagos->updatePaymentStatus('id', $id, 9);
				$result = $this->pagos->updatePaymentDateTime('id', $id, time());
				$result = $this->pagos->updatePaymentPaymentway('id', $id, $status);
				$result = $this->pagos->updatePaymentDesc('id', $id, 'Nuevo pago de '.$info[0]->description);

				$this->session->set_userdata('info_message', 'Pago pendiente pagado de nuevo.');
			} else $this->session->set_userdata('error_message', 'Imposible actualizar pagos de este estado.');			
		}
				
		redirect($returnUrl, 'Location'); 
		exit();



}


	
	function view_receipt($id_payment, $type_receipt = "")
	{
		
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->config('pagos');

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
		
		$returnUrl = $this->session->userdata('returnUrl');
		$this->session->unset_userdata('returnUrl');
		if(!isset($returnUrl) || $returnUrl == '') $returnUrl = site_url('facturacion/list_all');

		
		$info_tmp = $this->pagos->getPaymentById($id_payment);
		$pago = $info_tmp[0];
		//sprint("<pre>");print_r($pago);
		$contenido = '';
		
		switch($pago->id_type) {
			case 1:
				# Reserva de pista
				$this->load->model('Reservas_model', 'reservas', TRUE);
				$this->lang->load('reservas');
				$detalle = $this->reservas->getBookingInfoById($pago->id_transaction);
				//print($pago->id_transaction."<pre>");print_r($detalle);exit();
				$contenido = $this->load->view('payment/recibo_reserva', array('info' => $detalle, 'pago' => $pago), true);
			break;
			case 2:
				# Pago de clases
				$this->load->library('calendario');
				$datos_pago = explode('-', $pago->id_transaction);
				$detalle = $this->calendario->getCalendarByRange($datos_pago[1]);
				//print($pago->id_transaction."---".count($detalle)."<pre>");print_r($detalle);//exit();
				$contenido = $this->load->view('payment/recibo_curso', array('info' => $detalle, 'pago' => $pago), true);
			break;
			default:
				# Pago de clases
				$this->load->library('calendario');
				//$datos_pago = explode('-', $pago->id_transaction);
				//$detalle = $this->calendario->getCalendarByRange($datos_pago[1]);
				//print($pago->id_transaction."---".count($detalle)."<pre>");print_r($detalle);//exit();
				$detalle = array(1, 2, 3);
				$contenido = $this->load->view('payment/recibo_generico', array( 'pago' => $pago), true);
			break;
		}
		
		if($type_receipt == 'extended') {
			$this->load->model('redux_auth_model', 'users', TRUE);
			$this->load->library('payments_lib');
			$user_data = $this->users->get_user($pago->id_user);
			$pago_array = (array) $pago;
			$detalle_array = (array) $detalle;
			//echo '<pre>'; print_r($user_data);//exit();
			$this->payments_lib->recibo_extendido($pago_array, $detalle_array, $user_data);
			exit();
		}
		$payment_generate_receipt_option = $this->config->item('payment_generate_receipt_option');
		//print($info->status."<pre>");print_r($info);exit();

		if(!isset($detalle) || (!is_array($detalle) && !is_object($detalle)) || count($detalle) <= 0 || !isset($pago) || !is_object($pago) || count($pago) <= 0 || !in_array($pago->status, $payment_generate_receipt_option)) {
			echo '<html><head><script> 
				function cerrarse(){ 
				window.close() 
				} 
				</script> 
				</head> 
				
				<body> 
				
				Imposible generar ticket 
				
				<form> 
				<input type=button value="Cerrar ventana" onclick="cerrarse()"> 
				</form> </body></html>';
			exit();			
		}
		$data=array(
			'meta' => $this->load->view('payment/meta_recibo', array('title' => 'Recibo '.$pago->ticket_number), true),
			'header' => $this->load->view('payment/header_recibo', array('ticket_number' => $pago->ticket_number), true),
			'footer' => $this->load->view('payment/footer_recibo', '', true)
			);
		
		$data['main_content']=$contenido;

		
		
		$this->load->view('payment/recibo', $data);
	}



/* End of file payment.php */
/* Location: ./system/application/controllers/payment.php */
}