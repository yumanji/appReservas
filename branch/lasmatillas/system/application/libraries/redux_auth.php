<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */
 
/**
* Redux Authentication 2
*/
class redux_auth
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;

	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * __construct
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function __construct()
	{
		isset($this->CI) || $this->CI =& get_instance();
		$email = $this->CI->config->item('email');
		$this->CI->load->library('email', $email);
	}
	
	/**
	 * Activate user.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($code)
	{
		return $this->CI->redux_auth_model->activate($code);
	}
	
	/**
	 * Deactivate user.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($identity)
	{
	    return $this->CI->redux_auth_model->deactivate($code);
	}
	
	/**
	 * Change password.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function change_password($identity, $old, $new)
	{
        return $this->CI->redux_auth_model->change_password($identity, $old, $new);
	}

	/**
	 * forgotten password feature
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password($email, $code)
	{
		$forgotten_password = $this->CI->redux_auth_model->forgotten_password($email, $code);
		//echo '---'.$forgotten_password; //exit();
		log_message('debug','Recuperacion de password de : '.$email.' ('.$forgotten_password.')');
		if ($forgotten_password)
		{
			//echo '<br>aaa..';
			// Get user information.
			$datos = $this->CI->redux_auth_model->get_user_by_email($email);
			//echo '<pre><br>--';print_r($datos);//exit();
			if(!isset($datos) || !is_array($datos)) return false;
			log_message('debug','Usuario recuperado');
			
			$profile = $this->CI->redux_auth_model->profile($datos['user_id']);
			//echo '<pre><br>--';print_r($profile);//exit();
			if(!isset($profile) || !is_object($profile)) return false;
			log_message('debug','Datos del usuario cargados en profile');
			
//echo '<br>--';print_r($profile);
			$data = array('identity'                => $profile->{$this->CI->config->item('identity')},
    			          'forgotten_password_code' => $this->CI->redux_auth_model->forgotten_password_code);
//print_r($data);
		//exit('true');
                
			$message = $this->CI->load->view($this->CI->config->item('email_templates').'forgotten_password', $data, true);
				
			$this->CI->email->clear();
			$this->CI->email->set_newline("\r\n");
			$this->CI->email->from($this->CI->config->item('email_from'), $this->CI->config->item('email_from_desc'));
			$this->CI->email->reply_to($this->CI->config->item('email_replyto'), $this->CI->config->item('club_name'));
			$this->CI->email->to($profile->email);
			$this->CI->email->subject('Recuperacion de contrase�a (Primer paso)');
			$this->CI->email->message($message);
			return $this->CI->email->send();
			log_message('debug','Mail de recordatorio enviado');
		}
		else
		{
			//exit ('false');
			return false;
		}
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
	    $identity                 = $this->CI->config->item('identity');
	    $profile                  = $this->CI->redux_auth_model->profile($code);
		$forgotten_password_complete = $this->CI->redux_auth_model->forgotten_password_complete($code);

		if ($forgotten_password_complete)
		{
			$data = array('identity'    => $profile->{$identity},
				         'new_password' => $this->CI->redux_auth_model->new_password);
            
			$message = $this->CI->load->view($this->CI->config->item('email_templates').'new_password', $data, true);
				
			$this->CI->email->clear();
			$this->CI->email->set_newline("\r\n");
			$this->CI->email->from($this->CI->config->item('email_from'), $this->CI->config->item('email_from_desc'));
			$this->CI->email->reply_to($this->CI->config->item('email_replyto'), $this->CI->config->item('club_name'));
			$this->CI->email->to($profile->email);
			$this->CI->email->subject('Nuevo password');
			$this->CI->email->message($message);
			return $this->CI->email->send();
			//exit('true');
		}
		else
		{
			//exit('false');
			return false;
		}
	}

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	//public function register($username, $password, $email)
	public function register($password, $email, $email_check = TRUE)
	{
			log_message('debug','Intento de registro: '.$email.' - '.$password);
	    $email_activation = $this->CI->config->item('email_activation');
	    $email_folder     = $this->CI->config->item('email_templates');
//exit('bb');
		if($email_check && $this->CI->redux_auth_model->email_check($email)) {
			log_message('debug','Fallo en la validacion de mail en la creacion de usuario: '.$email);
			return false;
		}
		
		log_message('debug','Email, disponible: '.$email);
		if (!$email_activation)
		{
			$resultado = $this->CI->redux_auth_model->register($password, $email);
			if(!$resultado) { log_message('debug','Fallo en el registro del usuario: '.$email.' - '.$password); return $resultado; }
			else log_message('debug','usuario registrado sin email: '.$email);
			
			$data = $this->CI->redux_auth_model->get_user($resultado);
			$this->CI->load->config('reservas');
			
      //$data['activation'] = $activation_code;
      $data['clear_password'] = $password;
      $data['club_name'] = $this->CI->config->item('club_name');
//exit('cc');
            
			if($email!='') {
				log_message('debug','envio de email de registro ');
				$message = $this->CI->load->view($email_folder.'register', $data, true);
	      $contenido = $this->CI->load->view('notification', array('content' => $message, 'header' => $this->CI->load->view('email/email_header', '', true), 'footer' => $this->CI->load->view('email/email_footer', '', true)), true);
				$this->CI->email->clear();
				$this->CI->email->set_newline("\r\n");
				$this->CI->email->from($this->CI->config->item('email_from'), $this->CI->config->item('email_from_desc'));
				$this->CI->email->reply_to($this->CI->config->item('email_replyto'), $this->CI->config->item('club_name'));
				$this->CI->email->to($email);
				$this->CI->email->subject('Confirmacion de registro');
				$this->CI->email->message($contenido);
				
				if(!$this->CI->email->send()) log_message('debug','Fallo en el env�o del mail de confirmacion de la creacion de usuario (2)');
				else log_message('debug','email enviado: ');
			}			
			
			
			
			return $resultado;
		}
		else
		{
			$register = $this->CI->redux_auth_model->register( $password, $email);
            
			if (!$register) { 
				log_message('debug','Fallo en el registro del usuario: '.$email.' - '.$password);
				return false; 
				}
			log_message('debug','usuario registrado con email: '.$email);
			
			$deactivate = $this->CI->redux_auth_model->deactivate($register);

			if (!$deactivate) { 
				log_message('debug','Fallo en la desactivacion del usuario: '.$register);
				return false; 
				}
			log_message('debug','usuario desactivado: '.$register);
		//

			$activation_code = $this->CI->redux_auth_model->activation_code;


			$data = array(
								//'username' => $username,
        				'password'   => $password,
        				'email'      => $email,
        				'activation' => $activation_code);
        				
			$data = $this->CI->redux_auth_model->get_user($register);
			$this->CI->load->config('reservas');
			
      $data['activation'] = $activation_code;
      $data['clear_password'] = $password;
      $data['club_name'] = $this->CI->config->item('club_name');
//exit('cc');
            
			if($email!='') {
				log_message('debug','envio de email de registro ');
				$message = $this->CI->load->view($email_folder.'activation', $data, true);
	      $contenido = $this->CI->load->view('notification', array('content' => $message, 'header' => $this->CI->load->view('email/email_header', '', true), 'footer' => $this->CI->load->view('email/email_footer', '', true)), true);
				$this->CI->email->clear();
				$this->CI->email->set_newline("\r\n");
				$this->CI->email->from($this->CI->config->item('email_from'), $this->CI->config->item('email_from_desc'));
				$this->CI->email->reply_to($this->CI->config->item('email_replyto'), $this->CI->config->item('club_name'));
				$this->CI->email->to($email);
				$this->CI->email->subject('Email de activacion y registro');
				$this->CI->email->message($contenido);
				
				if(!$this->CI->email->send()) log_message('debug','Fallo en el env�o del mail de confirmacion de la creacion de usuario ');
				else log_message('debug','email enviado: ');
			} 
			
			log_message('debug','usuario registrado satisfactoriamente. ');
			return $register;
		}
	}
	
	/**
	 * login
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function login($identity, $password, $code)
	{
		return $this->CI->redux_auth_model->login($identity, $password, $code);
	}
	
	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
	    $identity = $this->CI->config->item('identity');
	    $this->CI->session->unset_userdata($identity);
		$this->CI->session->sess_destroy();
	}
	
	/**
	 * logged_in
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logged_in()
	{
	  $identity = $this->CI->config->item('identity');
		return ($this->CI->session->userdata($identity)) ? true : false;
	}
	
	/**
	 * Profile
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function profile()
	{
	    //$session  = $this->CI->config->item('identity');
	    //$identity = $this->CI->session->userdata($session);	// Comento estas lineas para tirar por 'id' en vez de por 'email'
	    $identity = $this->CI->session->userdata('user_id');
	    return $this->CI->redux_auth_model->profile($identity);
	}
	
}
