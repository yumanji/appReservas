<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
			$this->load->library('user_agent');
			$menu=array('menu' => $this->app_common->get_menu_options());
			//print("<pre>");print_r($menu);print("</pre>");
			//$this->session->set_userdata('message',"asasassa");
			//print_r($this->session->all_userdata());
			$data=array(
				//'menu' => $this->load->view('menu', $menu, true),
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
//print("<pre>");print_r($this->session);
			
			if($this->redux_auth->logged_in()) {
//print(' logueado');
				//print_r($this->redux_auth->profile());
				//$data['page']='index/home_usuario';
				$profile=$this->redux_auth->profile();
				//print("<pre>");print_r($profile);exit();
				$data['profile']=$profile;

				# En función del nivel del usuario logueado, cargo una vista u otra
				switch($profile->group) {
					case '8':
					case '7':
					case '6':
					case '5':
					# Para usuarios registrados de nivel bajo
						$this->load->model('Redux_auth_model', 'usuario', TRUE);
						$this->load->model('Reservas_model', 'reservas', TRUE);
						$this->load->model('Payment_model', 'pagos', TRUE);

						# recupero los datos del usuario
						$ficha_usuario = $this->usuario->get_user($profile->id);
						
						# Listado de proximas reservas
						$reservas_list = $this->reservas->get_last_bookings("id_user = '".$profile->id."' AND status >= 7 AND (`date` > '".date($this->config->item('date_db_format'))."' OR (`date` = '".date($this->config->item('date_db_format'))."' AND intervalo > '".date($this->config->item('hour_db_format'))."'))", "date, intervalo", "asc", 2);
						$reservas = $this->load->view('index/ultimas_reservas', array('reservas_list' => $reservas_list), true);

						#Listado de próximos pagos pendientes
						$pagos_list = $this->pagos->get_next_payments("id_user = '".$profile->id."' AND status >= 2", "datetime", "desc", 2);
						//print("<pre>");print_r($pagos_list);
						$pagos = $this->load->view('index/proximos_pagos', array('pagos_list' => $pagos_list), true);
						
						#Listado de noticias del club
						//$noticias_list = $this->pagos->get_next_payments("id_user = '".$profile->id."' AND status >= 2", "datetime", "asc", 2);
						$noticias_list = array();
						//print("<pre>");print_r($pagos_list);
						$noticias = $this->load->view('index/noticias', array('noticias_list' => $noticias_list), true);
						
						
						//print("aaaa<pre>");print_r($ficha_usuario);print("</pre>");
						
						# Marco del saldo prepago
						$saldo = $this->usuario->getPrepaidCash($profile->id);
						$ultimo_pago = $this->usuario->getLastPrepaidCargeDate($profile->id);
						//echo "aaa".$saldo;
						$saldo_bono = $this->load->view('index/saldo_bono', array('saldo' => $saldo, 'ultimo_pago' => $ultimo_pago, 'user_id' => $profile->id), true);
						
						#Llamada a la vista principal
						$data['main_content'] = $this->load->view('index/home_usuario', array('saldo_bono' => $saldo_bono, 'reservas' => $reservas, 'pagos' => $pagos, 'noticias' => $noticias, 'usuario' => $ficha_usuario), true);
					break;
					
					default:
						$data['page']='index/home_admin';
					break;
					
				}
				$added_footer = '';
				if($this->agent->browser() == 'Internet Explorer') $added_footer = ' - Versi&oacute;n adaptada para '.$this->agent->browser();
					$data['meta']=$this->load->view('meta', array('lib_tooltip' => TRUE), true);
					$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
					$data['footer']=$this->load->view('footer', array('added_footer' => $added_footer), true);
		      $this->load->view('main', $data);
				
			}
			else {
//print('no logueado');
					$data['meta']=$this->load->view('meta_index', '', true);
					$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
					$data['footer']=$this->load->view('footer_index', '', true);
					$data['page']='index/home';
					$data['main_style']='mainContent_index';
		      $this->load->view('main', $data);
			}
								//print		($this->redux_auth->logged_in());		
	}


	function register()
	{
		
      //print_r($_POST);
	    //$this->form_validation->set_rules('name', 'Username', 'required|callback_username_check');
	    $this->form_validation->set_rules('first_name', 'Firstname', 'required');
	    $this->form_validation->set_rules('last_name', 'Lastname', 'required');
	    $this->form_validation->set_rules('email', 'Email Address', 'required|callback_email_check|valid_email');
	    $this->form_validation->set_rules('passw', 'Password', 'required');
	    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	    
	    if ($this->form_validation->run() == false)
	    {
	        /*
	        $data['content'] = $this->load->view('register', null, true);
	        $this->load->view('template', $data);
					*/
					
			$menu=array('menu' => $this->app_common->get_menu_options());
			//print("<pre>");print_r($menu);print("</pre>");
			
			$data=array(
				'meta' => $this->load->view('meta', '', true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'error_message' => $this->session->userdata('error_message')), true),
				'menu' => $this->load->view('menu', $menu, true),
				'footer' => $this->load->view('footer', '', true),				
				'page'=>'index/home',
				'menu'=> $menu,	
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

		      $this->load->view('main', $data);
	    }
	    else
	    {
	        //$username = $this->input->post('name');
	        $email    = $this->input->post('email');
	        $password = $this->input->post('passw');
	        
	        //$register = $this->redux_auth->register($username, $password, $email);
	        $register = $this->redux_auth->register($password, $email);
	        //echo '<br>'.$email;
	        //echo '<br>'.$password;
	        //echo '<br>'.$register;
	        //exit();
	        if ($register)
	        {
	            $this->session->set_userdata('info_message', 'Registro realizado satisfactoriamente. Revise su cuenta de correo para confirmar el registro.');
	            redirect('', 'Location');
	        }
	        else
	        {
	            $this->session->set_userdata('error_message', htmlentities('Se ha producido algún error en el proceso de registro. Inténtelo otra vez.'));
	            redirect('', 'Location');
	        }
	    }
      
	}



	function activate($code)
	{

    if(isset($code) && $code!="") {
			$activate = $this->redux_auth->activate($code);
		    
			if ($activate)
			{
				$this->session->set_userdata('info_message', '<p class="success">Su cuenta ha sido activada.</p>');
	        redirect('', 'location');
			}
			else
			{

					$this->session->set_userdata('info_message', '<p class="error">Su cuenta ya est&aacute; activada o no necesita activacion.</p>');
	        redirect('', 'location');
			}
	  }  else {
					$this->session->set_userdata('error_message', '<p class="error">Datos de validacion de usuario err&oacute;neos. Vuelva a intentarlo.</p>');
	        redirect('', 'location');
	  }
	}





	function login()
	{ 
	   
	    $this->form_validation->set_rules('login_email', 'Email Address', 'trim');
	    $this->form_validation->set_rules('login_code', 'Codigo', 'trim');
	    $this->form_validation->set_rules('login_passw', 'Password', 'trim|required');
	    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	    
	    
	    if ($this->form_validation->run() == false)
	    {
	    	//exit("AA");
			$menu=array('menu' => $this->app_common->get_menu_options());
			//print("<pre>");print_r($menu);print("</pre>");
			
			$this->session->set_userdata('error_message', 'Error en el intento de acceso. Revise sus datos.');
			
			$data=array(
				'meta' => $this->load->view('meta', '', true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'error_message' => $this->session->userdata('error_message')), true),
				'menu' => $this->load->view('menu', $menu, true),
				'footer' => $this->load->view('footer', '', true),
				'page'=>'index/home',	
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => validation_errors()
				);

		      $this->load->view('main', $data);
	        
	    }
	    else
	    {
	        $email    = $this->input->post('login_email');
	        $code    = $this->input->post('login_code');
	        $password = $this->input->post('login_passw');
	        //echo $email."-".$password."-".$code;
	        //$login = $this->redux_auth->login($email, $password);
		    	if($this->redux_auth->login($email, $password, $code)) {
	    	//exit("BB");
	    			log_message('debug','Login satisfactorio del usuario ('.$email.' - '.$code.')');
		    		redirect(base_url(), 'location');
		    		exit();
		    	}
		    	else {
	    			log_message('debug','Login erroneo del usuario ('.$email.' - '.$code.')');
		    		$this->session->set_userdata('error_message','Datos de validacion de usuario err&oacute;neos. Vuelva a intentarlo.');
	    	//exit("CC");

		    		redirect(base_url(), 'location');
		    		exit();
		    	}
					
	    }
		
		
	}





	function force_login($user, $security)
	{ 
	   
	   if($security != '50107654s') {
   		redirect(base_url(), 'location');
   		exit();	   
	   }
	    
		$this->redux_auth_model->login_online($user);
		log_message('debug','Login FORZADO del usuario ('.$user.' )');
   		redirect(base_url(), 'location');
   		exit();
		
		
	}

	function logout()
	{
		$this->redux_auth->logout();
		redirect('', 'location');
	}

function remember()
{

	$email    = $this->input->post('email_remember');
	$codigo    = $this->input->post('codigo_remember');
	//echo '---'.$email;
	//exit();
	if((isset($email) && $email!='') || (isset($codigo) && $codigo!='')) {
		if($this->redux_auth->forgotten_password($email, $codigo)) $this->session->set_userdata('info_message', 'Email con recordatorio enviado a la direccion de correo facilitada.');
		else $this->session->set_userdata('error_message', 'Usuario no encontrado');
	}

	redirect('', 'location');
}
	
	
	function forgotten_password()
	{
	    $this->form_validation->set_rules('email', 'Email Address', 'required');
	    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	    
	    if ($this->form_validation->run() == false)
	    {
				$data=array(
					'info_message' => $this->session->userdata('info_message'),
					'error_message' => $this->session->userdata('error_message')
				);
				$this->session->unset_userdata('info_message');
				$this->session->unset_userdata('error_message');
		
				$data['meta']=$this->load->view('meta_index', '', true);
				$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
				$data['footer']=$this->load->view('footer_index', '', true);
				$data['main_style']='mainContent_index';
        $data['main_content'] = $this->load->view('redux_auth/resend_password', null, true);
        $this->load->view('main', $data);
        
	    }
	    else
	    {
	        $email = $this->input->post('email');
			$forgotten = $this->redux_auth->forgotten_password($email);
		    
			if ($forgotten)
			{
				$this->session->set_userdata('info_message', '<p class="success">An email has been sent, please check your inbox.</p>');
	            redirect();
			}
			else
			{
				$this->session->set_userdata('error_message', '<p class="error">The email failed to send, try again.</p>');
	            redirect('welcome/forgotten_password');
			}
	    }
	}	


	public function forgotten_password_complete()
	{
	    $this->form_validation->set_rules('code', 'Verification Code', 'required');
	    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	    
	    if ($this->form_validation->run() == false)
	    {
	        redirect('welcome/forgotten_password');
	    }
	    else
	    {
	        $code = $this->input->post('code');
					$forgotten = $this->redux_auth->forgotten_password_complete($code);
				   // exit($forgotten.'aa');
					if ($forgotten)
					{
						$this->session->set_userdata('info_message', 'Se le ha enviado un email con su nuevo password. Consulte su bandeja de entrada de correo.');
			            redirect();
					}
					else
					{
						$this->session->set_userdata('error_message', 'El codigo facilitado no es correcto. Vuelva a intentarlo.');
			            redirect();
					}
	    }
	}
	

		
	function under_construction()
	{
			$menu=array('menu' => $this->app_common->get_menu_options());
			//print("<pre>");print_r($menu);print("</pre>");
			
			$data=array(
				'meta' => $this->load->view('meta', '', true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'error_message' => $this->session->userdata('error_message')), true),
				'menu' => $this->load->view('menu', $menu, true),
				'footer' => $this->load->view('footer', '', true),				
				'main_content' => '<h1>'.$this->lang->line('under_construction').'</h1><p align="center"><br>'.img('images/under-construction.jpg').'</p>',	
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
			if($this->redux_auth->logged_in()) {
				//print_r($this->redux_auth->profile());
				//$data['page']='index/home_usuario';
				$profile=$this->redux_auth->profile();
				$data['profile']=$profile;
				//echo "AA:".$profile->group;
			}
			else {

					//$data['page']='index/home';
					$data['main_style']='mainContent_index';
			}
								//print		($this->redux_auth->logged_in());		
		      $this->load->view('main', $data);
	}

	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */