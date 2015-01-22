<?php

class Retos extends Controller {

	function Retos()
	{
		parent::Controller();	
	}
	



# -------------------------------------------------------------------
#  devuelve el listado de retos
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function index()
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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
						   		{name:'notified', index:'booking_shared.notified', width:5, align:'center'},";
		$colnames = "'Id','Fecha','Inicio','Final','Pista','Jugadores', 'Precio',  'Genero', 'Nivel1', 'Nivel2', 'Mail'";

		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "retos/jqgrid_list_all", 
				'title' => 'Listado de retos activos', 
				'default_orderfield' => 'fecha', 
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
			'main_content' => $this->load->view('retos/list_all', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}






# -------------------------------------------------------------------
#  devuelve el listado de retos visibles por un usuario normal
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function publico()
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;

		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		$colmodel = "	{name:'id_transaction',index:'id_transaction', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'fecha', width:7, align:'center', datefmt:'dd/mm/Y', date:true, sorttype:'date'},
						   		{name:'inicio', index:'inicio', width:5, align:'center', editable:true},
						   		{name:'final', index:'intervalo', width:5, align:'center', editable:true},
						   		{name:'court_name',index:'court_name', width:10, align:'center'},
						   		{name:'players', index:'booking_shared.players', width:5, align:'center'},
						   		{name:'price_by_player', index:'booking_shared.price_by_player', width:4, align:'center'},
						   		{name:'gender', index:'booking_shared.gender', width:5, align:'center'},
						   		{name:'low_player_level', index:'booking_shared.low_player_level', width:4, align:'center'},
						   		{name:'high_player_level', index:'booking_shared.high_player_level', width:10, align:'center'}";
		$colnames = "'Id','Fecha','Inicio','Final','Pista','Jugadores', 'Precio',  'Genero', 'Nivel1', 'Nivel2'";

		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "retos/jqgrid_list_all_public", 
				'title' => 'Listado de retos', 
				'default_orderfield' => 'fecha', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('retos/list_all_public', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}







	function new_reto ($id_transaction)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}

		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		# Rutina de grabado de reto
		if($this->input->post('action') && $this->input->post('action')=="save") {
			$this->load->model('Retos_model', 'retos', TRUE);
			
			$registro = array (
				'players' => $this->input->post('players'),
				'price_by_player' => $this->input->post('price_by_player'),
				'gender' => $this->input->post('gender'),
				'limit_date' => date($this->config->item('date_db_format'), strtotime($this->input->post('limit_date'))),
				'low_player_level' => $this->input->post('low_player_level'),
				'high_player_level' => $this->input->post('high_player_level'),
				'visible' => ($this->input->post('visible')=='on')? '1' : '0',
				'last_notify' => $this->input->post('last_notify'),
			);
			
			$this->retos->create($id_transaction, $registro);
			//print_r($_POST);print_r($registro);
      $this->session->set_userdata('info_message', 'Creaci&oacute;n satisfactoria');
      redirect(site_url('retos/detail/'.$id_transaction), 'Location'); 
      exit();
		}


		$generos = $this->usuario->getGendersArray();
		$default = array();

		# Valores por defecto del formulario
		if($this->input->post('limit_date')) $default['limit_date'] = $this->input->post('limit_date');
		else $default['limit_date'] = date($this->config->item('reserve_date_filter_format'), strtotime($info['date'].' -1DAY'));
		
		if($this->input->post('last_notify')) $default['last_notify'] = $this->input->post('last_notify');
		else $default['last_notify'] = '1';
		
		if($this->input->post('players')) $default['players'] = $this->input->post('players');
		else $default['players'] = '0';
		
		if($this->input->post('price_by_player') && $this->input->post('players')) $default['price_by_player'] = intval(($info['total_price'] / $this->input->post('players')) * 100);
		else $default['price_by_player'] = '000';
		
		if($this->input->post('visible') && $this->input->post('visible')=='on') $default['visible'] = 'checked';
		else $default['visible'] = '';
		
		# Fin de valores por defecto
		
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('retos/new', array('id_transaction' => $id_transaction, 'info' => $info, 'generos' => $generos, 'default' => $default), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

		//print("<PRE>");print_r($_POST);
      
	}



#############################
#
#
#############################

	function detail ($id_transaction)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->config->load('retos');

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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

		$this->load->library('retos_lib');

		# Rutina de grabado de reto
		if($this->input->post('action') && $this->input->post('action')=="save") {
			$this->load->model('Retos_model', 'retos', TRUE);
			
			$registro = array (
				'players' => $this->input->post('players'),
				'price_by_player' => $this->input->post('price_by_player'),
				'gender' => $this->input->post('gender'),
				'limit_date' => date($this->config->item('date_db_format'), strtotime($this->input->post('limit_date'))),
				'low_player_level' => $this->input->post('low_player_level'),
				'high_player_level' => $this->input->post('high_player_level'),
				'visible' => ($this->input->post('visible')=='on')? '1' : '0',
				'last_notify' => $this->input->post('last_notify'),
			);
			
			$this->retos->update($id_transaction, $registro);
			//print_r($_POST);print_r($registro);
			$this->session->set_userdata('info_message', 'Creaci&oacute;n satisfactoria');
			redirect(site_url('retos/detail/'.$id_transaction), 'Location'); 
			exit();
		}

		# Rutina de grabado de ganadores del reto
		if($this->input->post('action') && $this->input->post('action')=="result") {
			//echo 'aa<pre>'; print_r($_POST);  //print_r($this->input->post()); exit();
			$ganadores = array();
			$jugadores = $this->retos->get_data(array('where' => "booking_players.id_transaction = '".$id_transaction."' and booking_players.status not in (2,3) "))->result_array();
			foreach($_POST as $code => $value) {
				if(strstr($code, 'win_'))  array_push($ganadores, $value);
			}
			foreach($jugadores as $id =>$jugador) {
				if(in_array($jugador['id_user'], $ganadores)) $jugadores[$id]['win_game'] = 1;
				else $jugadores[$id]['win_game'] = 0;
			}
			
			//echo 'aa<pre>'; print_r($jugadores);
			$this->retos_lib->save_result($id_transaction, $jugadores); 
			//exit();
			$this->session->set_userdata('info_message', 'Grabaci&oacute;n de resultado del reto satisfactoria');
			redirect(site_url('retos/detail/'.$id_transaction), 'Location'); 
			exit();
		}
		
		
		# Rutina de grabado de ganadores del reto
		if($this->input->post('action') && $this->input->post('action')=="del_result") {
			//echo 'aa<pre>'; print_r($_POST);  //print_r($this->input->post()); exit();
			$ganadores = array();
			$jugadores = $this->retos->get_data(array('where' => "booking_players.id_transaction = '".$id_transaction."' and booking_players.status not in (2,3) "))->result_array();
			
			//echo 'aa<pre>'; print_r($jugadores);
			$this->retos_lib->delete_result($id_transaction, $jugadores); 
			//exit();
			$this->session->set_userdata('info_message', 'Resultado del reto eliminado satisfactoriamente');
			redirect(site_url('retos/detail/'.$id_transaction), 'Location'); 
			exit();
		}
		
				# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		$generos = $this->usuario->getGendersArray();
		$open = $this->retos_lib->reto_is_open($id_transaction);
		//if ($open) echo 'abierto';
		
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$submenu = $this->load->view('retos/submenu_navegacion', array('id' => $id_transaction), true);
		$vista_a_cargar = 'retos/detail';
		$jugadores = array();
		
		if(!$open) { 
			//$submenu = ''; 
			$vista_a_cargar = 'retos/detail_closed'; 
			$jugadores = $this->retos->get_data(array('where' => "booking_players.id_transaction = '".$id_transaction."' and booking_players.status not in (2,3) "))->result_array();
		}
		//echo '<pre>';print_r($jugadores);

		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view($vista_a_cargar, array('id_transaction' => $id_transaction, 'info' => $info, 'generos' => $generos, 'jugadores' => $jugadores, 'retos_save_results' => $this->config->item('retos_save_results')), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

		//print("<PRE>");print_r($_POST);
      
	}




#############################
#
#
#############################

	function add_player ($id_transaction)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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


		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		# Rutina de grabado de usuario
		if($this->input->post('action') && $this->input->post('action')=="add") {
			$this->load->model('Retos_model', 'retos', TRUE);
			//print_r($_POST);exit();
			$usuario_sel = $this->input->post('id_user');
			$datos_usuario = $this->usuario->get_user($usuario_sel);
			$status = '1';
			if($info['players']<=$info['signed']) $status = '2';
			$returnUrl = $this->input->post('returnUrl');
			$registro = array (
				'id_user' => $usuario_sel,
				'status' => $status,
			);
			
			if($datos_usuario['player_level'] < $info['low_player_level'] || $datos_usuario['player_level'] > $info['high_player_level'] ) $response = 'Nivel de usuario insuficiente para este reto.';
			else $response = $this->retos->add_player($id_transaction, $registro);
			//print_r($_POST);print_r($registro);
			# Si devuelve algo, es que ha habido error..
			if(!$response){
	      if($status == '1') {
	      	$this->session->set_userdata('info_message', 'Has sido a&ntilde;adido al partido. Recuerda llevar preparado el pago del mismo.');
					if($info['players']==$info['signed']) {
						#####
						# Notificacion de partido completo
							$this->load->model('Notifications_model', 'mails', TRUE);
						
							$subject = 'Reto completado para el '.$info['fecha'];
							//$suscribe_url = site_url('retos/suscribe/'.$id_transaction.'/'.md5($id_transaction.$this->config->item('secret_word')).'/[#param_1#]/[#param_2#]');
							$content = $this->load->view('retos/mail_complete', array('info' => $info), true);
							//echo $content;
							$registro = array(
					       'type' => 1,
					       'destination_type' => 1,
					       'destination_id' => 0,
					       'subject' => $subject,
					       'from' => $this->config->item('email_from'),
					       'destination_text' => '',
					       'content' => $content,
					       'active' => 1,
					    );
					    $parameters = array();
					    //$usuarios = $this->usuarios->get_global_list("users.active = '1' AND meta.allow_mail_notification = '1'");
					    //print_r($usuarios['records']->result_array());
					    foreach($usuarios as $usuario) {
					    	$parameters[$usuario['email']] = array (
					    			'param_3' => $usuario['name']
					    		)
					    	;
					    }
					    //print_r($parameters);
							$this->mails->createPersonalizedMessage($registro, $parameters);
			
						# Fin de notificación
					}
	      } else $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido a la lista de espera del partido. Si hubiera alg&uacte;n cambio se te notificar&iacute;a.');
	    } else $this->session->set_userdata('error_message', $response);
	    
      if($returnUrl!='') redirect($returnUrl, 'Location'); 
      else redirect(site_url('retos/players/'.$id_transaction), 'Location'); 
      exit();
		}

		//print("<pre>");print_r($info); //exit();


		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $this->load->view('retos/submenu_navegacion', array('id' => $id_transaction), true)), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('retos/new_player', array('id_transaction' => $id_transaction), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

		//print("<PRE>");print_r($_POST);
      
	}




#############################
#
#
#############################

	function validate_player ($id_transaction, $usuario)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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


		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		$this->load->model('Retos_model', 'retos', TRUE);
		//print_r($_POST);exit();
		if($info['players']<=$info['signed']) {
			$this->session->set_userdata('error_message', 'No hay plazas libres en este reto para un nuevo jugador.');
			redirect(site_url('retos/waiting/'.$id_transaction), 'Location'); 
    	exit();
		}
		$returnUrl = $this->input->post('returnUrl');

		
		$response = $this->retos->validate_player($id_transaction, $usuario);
		
		
			#####
			# Notificacion de cancelación
				$this->load->model('Notifications_model', 'mails', TRUE);
			
				$subject = 'Aceptado para el reto del '.$info['fecha'];
				$content = $this->load->view('retos/mail_validation', array('info' => $info, 'usuario' => $this->usuario->getUserDesc($usuario)), true);
				//echo $content;
				$registro = array(
		       'type' => 1,
		       'destination_id' => $usuario,
		       'subject' => $subject,
		       'from' => $this->config->item('email_from'),
		       'destination_text' => '',
		       'content' => $content,
		       'active' => 1,
		    );


		    //print_r($parameters);
				$this->mails->createNotificationMessage($registro);

			# Fin de notificación		
		
		
		
		//print_r($_POST);print_r($registro);
		# Si devuelve algo, es que ha habido error..
		$this->session->set_userdata('info_message', 'El usuario ha sido a&ntilde;adido al partido desde la lista de espera.');
    
    if($returnUrl!='') redirect($returnUrl, 'Location'); 
    else redirect(site_url('retos/players/'.$id_transaction), 'Location'); 
    exit();


      
	}


#############################
#
#
#############################

	function pay_player ($id_transaction, $usuario, $metodo)
	{
		
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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


		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		$this->load->model('Retos_model', 'retos', TRUE);
		//print_r($_POST);exit();
		$returnUrl = $this->input->post('returnUrl');

		
		$response = $this->retos->pay_player($id_transaction, $usuario);
		
		$this->pagos->id_type=4; //Reto
		$this->pagos->id_transaction=$id_transaction;
		$this->pagos->id_user=$usuario;
		$this->pagos->desc_user=$this->usuario->getUserDesc($usuario);
		$this->pagos->id_paymentway=$metodo;
		$this->pagos->status=9;
		$this->pagos->quantity=$info['price_by_player'];
		$this->pagos->datetime=date($this->config->item('log_date_format'));
		$this->pagos->description='Participaci&oacute;n en reto el '.$info['fecha'].' en '.$info['court'];
		$this->pagos->create_user=$user_id;
		$this->pagos->create_time=date($this->config->item('log_date_format'));
		
		$this->pagos->setPayment();		
	
		
		
		
		//print_r($_POST);print_r($registro);
		# Si devuelve algo, es que ha habido error..
		$this->session->set_userdata('info_message', 'La participaci&oacute;n del usuario en el reto ha sido marcada como abonada.');
    
    if($returnUrl!='') redirect($returnUrl, 'Location'); 
    else redirect(site_url('retos/players/'.$id_transaction), 'Location'); 
    exit();


      
	}




#############################
#
#
#############################

	function remove_player ($id_transaction, $usuario)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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


		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		$this->load->model('Retos_model', 'retos', TRUE);
		//print_r($_POST);exit();

		$returnUrl = $this->input->post('returnUrl');
		//exit();
		$response = $this->retos->remove_player($id_transaction, $usuario);
		
		
			#####
			# Notificacion de cancelación
				$this->load->model('Notifications_model', 'mails', TRUE);
			
				$subject = 'Cancelada participacion para el reto del '.$info['fecha'];
				$content = $this->load->view('retos/mail_player_cancelation', array('info' => $info, 'usuario' => $this->usuario->getUserDesc($usuario)), true);
				//echo $content;
				$registro = array(
		       'type' => 1,
		       'destination_id' => $usuario,
		       'subject' => $subject,
		       'from' => $this->config->item('email_from'),
		       'destination_text' => '',
		       'content' => $content,
		       'active' => 1,
		    );
		    $parameters = array();
		    //$usuarios = $this->usuarios->get_global_list("users.active = '1' AND meta.allow_mail_notification = '1'");
		    //print_r($usuarios['records']->result_array());
		    foreach($usuarios as $usuario) {
		    	$parameters[$usuario['email']] = array (
		    			'param_3' => $usuario['name']
		    		)
		    	;
		    }
		    //print_r($parameters);
				$this->mails->createNotificationMessage($registro);

			# Fin de notificación		
		
		
		
		//print_r($_POST);print_r($registro);
		# Si devuelve algo, es que ha habido error..
		$this->session->set_userdata('info_message', 'El usuario ha sido eliminado del reto.');
    
    if($returnUrl!='') redirect($returnUrl, 'Location'); 
    else redirect(site_url('retos/players/'.$id_transaction), 'Location'); 
    exit();


      
	}




#############################
#
#
#############################

	function suscribe ($id_transaction = NULL, $id_transaction_check = NULL, $user = NULL, $user_check = NULL)
	{
		if(!isset($id_transaction) || !isset($id_transaction_check) || !isset($user) || !isset($user_check)) {
        $this->session->set_userdata('error_message', 'Acceso inapropiado a la aplicaci&oacute;n');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		if($id_transaction == '' || $id_transaction_check != md5($id_transaction.$this->config->item('secret_word')))  {
        $this->session->set_userdata('error_message', 'Acceso inapropiado a la aplicaci&oacute;n. 1');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		if($user == '' || $user_check != md5($user.$this->config->item('secret_word')))  {
        $this->session->set_userdata('error_message', 'Acceso inapropiado a la aplicaci&oacute;n. 2');
        redirect(site_url(), 'Location'); 
        exit();
		}
		# Si llego hasta aquí es que los parámetros facilitados son auténticos y el link ha salido de nuestra aplicación
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->usuario->login_online($user);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			//echo $user_id."AAAAAA";

		}	else {
        $this->session->set_userdata('error_message', 'Pruebe a acceder primero a la aplicacion con su usuario y password y luego vuelva a intentar apuntarse al reto.');
        redirect(site_url(), 'Location'); 
        exit();
		}

		//print("<pre>");print_r($profile); //exit();
//exit();
		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);


		# Rutina de grabado de reto
		if($this->input->post('action') && $this->input->post('action')=="suscribe") {
			$this->load->model('Retos_model', 'retos', TRUE);
			
			$status = '1';
			if($info['players']<=$info['signed']) $status = '2';
			$returnUrl = $this->input->post('returnUrl');
			$registro = array (
				'id_user' => $user_id,
				'status' => $status,
			);
			
			$response = $this->retos->add_player($id_transaction, $registro);
			//print_r($_POST);print_r($registro);
			# Si devuelve algo, es que ha habido error..
			if(!$response){
	      if($status == '1') $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido al partido. Recuerda llevar preparado el pago del mismo.');
	      else $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido a la lista de espera del partido. Si hubiera alg&uacte;n cambio se te notificar&iacute;a.');
	    } else $this->session->set_userdata('error_message', $response);
	    
      if($returnUrl!='') redirect($returnUrl, 'Location'); 
      else redirect(site_url('retos/players/'.$id_transaction), 'Location'); 
      exit();
		}

		//print("<pre>");print_r($info); //exit();

		$apuntable = FALSE;
		if($profile->player_level >= $info['low_player_level'] && $profile->player_level <= $info['high_player_level']) $apuntable = TRUE;
		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() ), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('retos/suscribe', array('id_transaction' => $id_transaction, 'info' => $info, 'profile' => $profile, 'apuntable' => $apuntable), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

		//print("<PRE>");print_r($_POST);
      
	}




#############################
#
#
#############################

	function detail_user ($user = NULL, $id_transaction = NULL)
	{
		if(!isset($id_transaction) || !isset($user)) {
        $this->session->set_userdata('error_message', 'Acceso inapropiado a la aplicaci&oacute;n');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		# Si llego hasta aquí es que los parámetros facilitados son auténticos y el link ha salido de nuestra aplicación
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->usuario->login_online($user);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			//echo $user_id."AAAAAA";

		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}

		//print("<pre>");print_r($profile); //exit();
//exit();
		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info); //exit();

		$usuario_presente = 0;
		foreach($info['signed_users'] as $signed) {
			if($signed['id_user'] == $user_id) $usuario_presente = 1;
		}
		foreach($info['waiting_users'] as $signed) {
			if($signed['id_user'] == $user_id) $usuario_presente = 1;
		}
		
		# Rutina de grabado de reto
		if($this->input->post('action') && $this->input->post('action')=="suscribe") {
			$this->load->model('Retos_model', 'retos', TRUE);
			
			$status = '1';
			if($info['players']<=$info['signed']) $status = '2';
			$returnUrl = $this->input->post('returnUrl');
			$registro = array (
				'id_user' => $user_id,
				'status' => $status,
			);
			
			$response = $this->retos->add_player($id_transaction, $registro);
			//print_r($_POST);print_r($registro);
			# Si devuelve algo, es que ha habido error..
			if(!$response){
	      if($status == '1') $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido al partido. Recuerda llevar preparado el pago del mismo.');
	      else $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido a la lista de espera del partido. Si hubiera alg&uacte;n cambio se te notificar&iacute;a.');
	    } else $this->session->set_userdata('error_message', $response);
	    
      if($returnUrl!='') redirect($returnUrl, 'Location'); 
      else redirect(site_url('retos/detail_user/'.$user.'/'.$id_transaction), 'Location'); 
      exit();
		}

		# Rutina de grabado de reto
		if($this->input->post('action') && $this->input->post('action')=="unsuscribe") {
			$this->load->model('Retos_model', 'retos', TRUE);
			$response = $this->retos->remove_player($id_transaction, $user);
		
		
			#####
			# Notificacion de cancelación
				$this->load->model('Notifications_model', 'mails', TRUE);
			
				$subject = 'Cancelada participacion para el reto del '.$info['fecha'];
				$content = $this->load->view('retos/mail_player_cancelation', array('info' => $info, 'usuario' => $this->usuario->getUserDesc($user)), true);
				//echo $content;
				$registro = array(
		       'type' => 1,
		       'destination_id' => $user,
		       'subject' => $subject,
		       'from' => $this->config->item('email_from'),
		       'destination_text' => '',
		       'content' => $content,
		       'active' => 1,
		    );
		    $parameters = array();
		    //$usuarios = $this->usuarios->get_global_list("users.active = '1' AND meta.allow_mail_notification = '1'");
		    //print_r($registro);
		    //print_r($usuarios['records']->result_array());
		    foreach($info['signed_users'] as $usuario) {
		    	$parameters[$usuario['email']] = array (
		    			'param_3' => $usuario['name']
		    		)
		    	;
		    }
		    //print_r($parameters);
				$this->mails->createNotificationMessage($registro);

			# Fin de notificación			
					
      if($returnUrl!='') redirect($returnUrl, 'Location'); 
      else redirect(site_url('retos/detail_user/'.$user.'/'.$id_transaction), 'Location'); 
      exit();
		}
		//print("<pre>");print_r($info); //exit();

		$submenu =  $this->load->view('users/submenu_navegacion', array(), true);
		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $submenu), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('retos/detail_user', array('id_transaction' => $id_transaction, 'info' => $info, 'profile' => $profile, 'activo' => $usuario_presente), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

		//print("<PRE>");print_r($_POST);
      
	}






#############################
#
#
#############################

	function view ($id_transaction)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}

		//print("<pre>");print_r($profile); //exit();
//exit();
		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info); exit();
		
		$usuario_presente = 0;
		if(isset($info['signed_users']) && is_array($info['signed_users'])) {
			foreach($info['signed_users'] as $signed) {
				if($signed['id_user'] == $user_id) $usuario_presente = 1;
			}
		}
		if(isset($info['waiting_users']) && is_array($info['waiting_users'])) {
			foreach($info['waiting_users'] as $signed) {
				if($signed['id_user'] == $user_id) $usuario_presente = 1;
			}
		}
		//log_message('debug', 'RETO: llego ('.$usuario_presente.')');
		
		# Rutina de grabado de reto
		if($usuario_presente == 0 && $this->input->post('action') && $this->input->post('action')=="suscribe") {
			$this->load->model('Retos_model', 'retos', TRUE);
			//log_message('debug', 'RETO: entro a registrar al usuario '.$usuario_presente);
			
			$status = '1';
			if($info['players']<=$info['signed']) $status = '2';
			//log_message('debug', 'RETO: status del usuario: '.$status);

			$returnUrl = $this->input->post('returnUrl');
			$registro = array (
				'id_user' => $user_id,
				'status' => $status,
			);
			
			$response = $this->retos->add_player($id_transaction, $registro);
			//log_message('debug', 'RETO: añadido');

			//print_r($_POST);print_r($registro);
			# Si devuelve algo, es que ha habido error..
			if(!$response){
	      if($status == '1') $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido al partido. Recuerda llevar preparado el pago del mismo.');
	      else $this->session->set_userdata('info_message', 'Has sido a&ntilde;adido a la lista de espera del partido. Si hubiera alg&uacte;n cambio se te notificar&iacute;a.');
	    } else $this->session->set_userdata('error_message', $response);
	    
      if($returnUrl!='') redirect($returnUrl, 'Location'); 
      else redirect(site_url('retos/players/'.$id_transaction), 'Location'); 
      exit();
		}

		//print("<pre>");print_r($info); //exit();

		$apuntable = FALSE;
		if($profile->player_level >= $info['low_player_level'] && $profile->player_level <= $info['high_player_level']) $apuntable = TRUE;

		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() ), true),
			'menu' => $this->load->view('menu', '', true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('retos/view', array('id_transaction' => $id_transaction, 'info' => $info, 'profile' => $profile, 'activo' => $usuario_presente, 'apuntable' => $apuntable), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);

		//print("<PRE>");print_r($_POST);
      
	}





	function cancel ($id_transaction)
	{
		
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Retos_model', 'retos', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}

		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);
		
		//print("<pre>");print_r($info);exit();
		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		if($this->reservas->cancel_reserve($id_transaction, 'Reserva para reto, cancelado')) {
			$usuarios = array();
			foreach($info['signed_users'] as $signed) {
				//echo '<br> Cancelar usuario '.$signed['id_user'];
				$user_data = $this->usuario->get_user($signed['id_user']);
				array_push($usuarios, array('email' => $user_data['user_email'], 'name' => $user_data['user_name']));
				switch($signed['status']) {
					case '1':
						$this->retos->remove_player($id_transaction, $signed['id_user']);
					break;
					
					case '2':
						$this->retos->remove_player($id_transaction, $signed['id_user']);
					
					break;
					
					case '5':
						$this->retos->remove_player($id_transaction, $signed['id_user']);
					
					break;
				}
			}
			
			$this->retos->cancel_reto($id_transaction);
			
			#####
			# Notificacion de cancelación
				$this->load->model('Notifications_model', 'mails', TRUE);
			
				$subject = 'Cancelacion del reto del '.$info['fecha'];
				//$suscribe_url = site_url('retos/suscribe/'.$id_transaction.'/'.md5($id_transaction.$this->config->item('secret_word')).'/[#param_1#]/[#param_2#]');
				$content = $this->load->view('retos/mail_cancelation', array('info' => $info), true);
				//echo $content;
				$registro = array(
		       'type' => 1,
		       'destination_type' => 1,
		       'destination_id' => 0,
		       'subject' => $subject,
		       'from' => $this->config->item('email_from'),
		       'destination_text' => '',
		       'content' => $content,
		       'active' => 1,
		    );
		    $parameters = array();
		    //$usuarios = $this->usuarios->get_global_list("users.active = '1' AND meta.allow_mail_notification = '1'");
		    //print_r($usuarios['records']->result_array());
		    foreach($usuarios as $usuario) {
		    	$parameters[$usuario['email']] = array (
		    			'param_3' => $usuario['name']
		    		)
		    	;
		    }
		    //print_r($parameters);
				$this->mails->createPersonalizedMessage($registro, $parameters);

			# Fin de notificación
			
			
			
      $this->session->set_userdata('info_message', 'Reto cancelado. Reserva liberada.');
      redirect(site_url('retos'), 'Location'); 
      exit();
			
		}

    $this->session->set_userdata('error_message', 'Reto no ha podido ser eliminado.');
    redirect(site_url('retos/detail/'.$id_transaction), 'Location'); 
    exit();
	}





	function notify ($id_transaction)
	{
		
		$this->load->model('Redux_auth_model', 'usuarios', TRUE);
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Retos_model', 'retos', TRUE);

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}


		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);exit();

		$this->load->model('Notifications_model', 'mails', TRUE);
		
		$subject = 'Nuevo reto para el '.$info['fecha'];
		$suscribe_url = site_url('retos/suscribe/'.$id_transaction.'/'.md5($id_transaction.$this->config->item('secret_word')).'/[#param_1#]/[#param_2#]');
		$content = $this->load->view('retos/mail_notification', array('info' => $info, 'suscribe_url' => $suscribe_url), true);
		//echo $content;
		$registro = array(
       'type' => 1,
       'destination_type' => 1,
       'destination_id' => 0,
       'subject' => $subject,
       'from' => $this->config->item('email_from'),
       'destination_text' => '',
       'content' => $content,
       'active' => 1,
    );
    $parameters = array();
    $dia_semana = date('w', strtotime($info['date']));
    //echo $dia_semana;
    
    $filtro = '';
    switch($dia_semana) {
    	case '0':
    		$filtro = " AND meta.reto_lunes = '1'";
    	break;
    	case '1':
    		$filtro = " AND meta.reto_martes = '1'";
    	break;
    	case '2':
    		$filtro = " AND meta.reto_miercoles = '1'";
    	break;
    	case '3':
    		$filtro = " AND meta.reto_jueves = '1'";
    	break;
    	case '4':
    		$filtro = " AND meta.reto_viernes = '1'";
    	break;
    	case '5':
    		$filtro = " AND (meta.reto_sabado = '1' OR meta.reto_finde = '1')";
    	break;
    	case '6':
    		$filtro = " AND (meta.reto_domingo = '1' OR meta.reto_finde = '1')";
    	break;
    }
    
    $filtro2 = '';
    if($info['fin'] < $this->config->item('retos_afternoon_edge')) $filtro2 = " AND meta.reto_manana = '1'";
    else $filtro2 = " AND meta.reto_tarde = '1'";
    
    # Filtro por nivel de juego
    $filtro3 = " AND meta.player_level >= ".$info['low_player_level']." AND meta.player_level <= ".$info['high_player_level']." ";
    
    $usuarios = $this->usuarios->get_global_list("users.active = '1' AND meta.allow_mail_notification = '1' AND meta.reto_notifica = '1'".$filtro.$filtro2.$filtro3);
    foreach($usuarios['records']->result_array() as $usuario) {
    	$parameters[$usuario['email']] = array (
    			'param_1' => $usuario['id'],
    			'param_2' => md5($usuario['id'].$this->config->item('secret_word')),
    			'param_3' => $usuario['first_name']
    		)
    	;
    }
    //print_r($parameters);
//echo $this->db->last_query(); print_r($usuarios);exit();
		if($this->mails->createPersonalizedMessage($registro, $parameters)) {
			
			$this->retos->setRetoNotified($id_transaction);
			
			$this->session->set_userdata('info_message', 'Mail creado satisfactoriamente y puesto en cola de espera para su env&iacute;o.');
  		redirect(site_url('retos/detail/'.$id_transaction), 'location');
  		exit();

		} else {
			echo "Mail creado con errores";
			$this->session->set_userdata('error_message', 'Error en la creación del email. Imposible su env&iacute;o.');
  		redirect(site_url('retos/detail/'.$id_transaction), 'location');
  		exit();
		}



	}





# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function players($id_transaction)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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
		
		$colmodel = "	{name:'id_user',index:'booking_players.id_user', width:1, align:'center',hidden:true},
						   		{name:'nombre_completo',index:'meta.last_name', width:30, align:'center'},
						   		{name:'phone',index:'meta.phone', width:10, align:'center'},
						   		{name:'player_level', index:'meta.player_level', width:5, align:'center'},
						   		{name:'status_desc', index:'booking_players.status', width:20, align:'center'}";
		$colnames = "'Id', 'Nombre', 'Telefono', 'Nivel', 'Estado'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "retos/jqgrid_list_players/".$id_transaction, 
				'title' => 'Listado de jugadores', 
				'default_orderfield' => 'last_name', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('retos/submenu_navegacion', array('id' => $id_transaction), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('retos/list_players', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL, 'id_transaction' => $id_transaction), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}







# -------------------------------------------------------------------
#  devuelve el listado de usuarios en lista de espera
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function waiting($id_transaction)
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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
		
		$colmodel = "	{name:'id_user',index:'booking_players.id_user', width:1, align:'center',hidden:true},
						   		{name:'nombre_completo',index:'meta.last_name', width:30, align:'center'},
						   		{name:'phone',index:'meta.phone', width:10, align:'center'},
						   		{name:'player_level', index:'meta.player_level', width:5, align:'center'},
						   		{name:'status_desc', index:'booking_players.status', width:20, align:'center'}";
		$colnames = "'Id', 'Nombre', 'Telefono', 'Nivel', 'Estado'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "retos/jqgrid_list_players_waiting/".$id_transaction, 
				'title' => 'Listado de jugadores en lista de espera', 
				'default_orderfield' => 'last_name', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('retos/submenu_navegacion', array('id' => $id_transaction), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('retos/list_players_waiting', array('grid_code' => $grid_code, 'enable_buttons' => TRUE, 'menu_lateral' => NULL, 'id_transaction' => $id_transaction), true),
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
public function jqgrid_list_all ()
	{
		$this->load->model('retos_model', 'retos', TRUE);

		$where = '';
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
		}	else {
      exit(0);
		}

		//$req_param = array ();
		$req_param = array (
				"orderby" => 'date',
				"orderbyway" => 'desc',
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
		//$where.= 'booking.shared = \'1\'';
		$where.= 'booking.shared = \'1\' AND booking.date >= \''.date($this->config->item('date_db_format')).'\'';

		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->retos->get_global_data($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->retos->get_global_data ($req_param, 'none');
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}






# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_by_user ($user)
	{
		$this->load->model('retos_model', 'retos', TRUE);

		$where = '';
		
		if(!isset($user) || $user=='') exit(0);
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			if($user != $user_id) {
        exit(0);
			}
		}	else {
      exit(0);
		}

		//$req_param = array ();
		$req_param = array (
				"orderby" => 'date, intervalo',
				"orderbyway" => 'desc',
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
		$where.= 'booking.shared = \'1\'';

		$req_param['where'] = $where;
		$req_param['usuario'] = $user;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->retos->get_global_data($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->retos->get_global_data ($req_param, 'none');
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}







# -------------------------------------------------------------------
#  devuelve el array de resultados para la pantalla de listado público por usuario
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all_public ()
	{
		$this->load->model('retos_model', 'retos', TRUE);

		$where = '';
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
		}	else {
      exit(0);
		}

		//$req_param = array ();
		$req_param = array (
				"orderby" => 'date',
				"orderbyway" => 'desc',
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
		$where.= 'booking.shared = \'1\' AND (booking.date >= \''.date($this->config->item('date_db_format')).'\' OR booking.id_transaction IN (SELECT DISTINCT id_transaction FROM booking_players  WHERE  booking_players.id_user = \''.$user_id.'\'))';

		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$records = $this->retos->get_global_data ($req_param, 'none');
		//$data->records = $this->retos->get_global_data($req_param,"count");
		$data->records = count($records);
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}




# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_players ($id_transaction = NULL)
	{
		$this->load->model('retos_model', 'retos', TRUE);

		$where = '';
		
		if(!$id_transaction) exit(0);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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
		$where.= 'booking_players.id_transaction = \''.$id_transaction.'\' AND booking_players.status NOT IN (2,3)';
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->retos->get_data($req_param,"all")->result_array());
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->retos->get_data ($req_param, 'none')->result_array();
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
#  devuelve el listado de usuarios en lista de espera
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_players_waiting ($id_transaction = NULL)
	{
		$this->load->model('retos_model', 'retos', TRUE);

		$where = '';
		
		if(!$id_transaction) exit(0);
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el registrar la reserva como 'partido compartido'
			$permiso=$this->config->item('shared_bookings_permission');
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
		$where.= 'booking_players.id_transaction = \''.$id_transaction.'\' AND booking_players.status = \'2\'';
		$req_param['where'] = $where;

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->retos->get_data($req_param,"all")->result_array());
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->retos->get_data ($req_param, 'none')->result_array();
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





	function reminder ()
	{
		
		$this->load->model('Redux_auth_model', 'usuarios', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Retos_model', 'retos', TRUE);

		/*	
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		*/

		# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
		//$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);
		$condiciones = array('orderby' => 'booking.date ASC, booking.intervalo ASC', 'where' => 'booking.shared = \'1\' AND booking_shared.notified = 0 AND booking.date >= \''.date($this->config->item('date_db_format')).'\'');
		$resultado = $this->retos->get_global_data($condiciones,"all");
		
		if(count($resultado) > 0) {
			$resumen_retos = array();
			$retos_procesados = array();
			foreach($resultado as $reto) {
				//$info=$this->reservas->getBookingInfoById($id_transaction);
				$suscribe_url = site_url('retos/suscribe/'.$reto['id_transaction'].'/'.md5($reto['id_transaction'].$this->config->item('secret_word')).'/[#param_1#]/[#param_2#]');
				$contenido = $this->load->view('retos/mail_reminder_list_element', array('info' => $reto, 'suscribe_url' => $suscribe_url), true);
				array_push($resumen_retos, $contenido);
				array_push($retos_procesados, $reto['id_transaction']);
			}
			
			$cuerpo_mail = $this->load->view('retos/mail_reminder', array('resumen_retos' => $resumen_retos), true);
			//print($cuerpo_mail);//print($cuerpo_mail."<pre>");print_r($resumen_retos);print_r($resultado);
			
	//exit();
			$this->load->model('Notifications_model', 'mails', TRUE);
			
			$subject = 'Retos activos - Apuntate!';
			//$suscribe_url = site_url('retos/suscribe/'.$id_transaction.'/'.md5($id_transaction.$this->config->item('secret_word')).'/[#param_1#]/[#param_2#]');
			//$content = $this->load->view('retos/mail_notification', array('info' => $info, 'suscribe_url' => $suscribe_url), true);
			//echo $content;
			$registro = array(
	       'type' => 1,
	       'destination_type' => 1,
	       'destination_id' => 0,
	       'subject' => $subject,
	       'from' => $this->config->item('email_from'),
	       'destination_text' => '',
	       'content' => $cuerpo_mail,
	       'active' => 1,
	    );
	    $parameters = array();
	    $usuarios = $this->usuarios->get_global_list("users.active = '1' AND meta.allow_mail_notification = '1'");
	    //print_r($usuarios['records']->result_array());
	    foreach($usuarios['records']->result_array() as $usuario) {
	    	$parameters[$usuario['email']] = array (
	    			'param_1' => $usuario['id'],
	    			'param_2' => md5($usuario['id'].$this->config->item('secret_word')),
	    			'param_3' => $usuario['first_name']
	    		)
	    	;
	    }
	    
	    //print_r($parameters);exit();
			if($this->mails->createPersonalizedMessage($registro, $parameters)) {
				
				foreach($retos_procesados as $numero) $this->retos->setRetoNotified($numero);
	
			} 
		}
		exit();



	}





}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */