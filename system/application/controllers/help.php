<?php

class Help extends Controller {

	function Help()
	{
		parent::Controller();	
	}
	
	function index()
	{
			//print("<pre>");print_r($menu);print("</pre>");
			//$this->session->set_userdata('message',"asasassa");
			//print_r($this->session->all_userdata());
			$data=array(
				'menu' => $this->load->view('menu', '', true),
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

			
			if($this->redux_auth->logged_in()) {
				//print_r($this->redux_auth->profile());
				//$data['page']='index/home_usuario';
				$profile=$this->redux_auth->profile();

				//$data['profile']=$profile;

				# En función del nivel del usuario logueado, cargo una vista u otra
				switch($profile->group) {
					case '1':
					case '2':
					case '3':
					case '4':
					case '5':
					# Para usuarios registrados de nivel bajo
					
						#Llamada a la vista principal
						$data['main_content']=$this->load->view('help/help_admin', '', true);
					break;
					
					default:
						$data['main_content']=$this->load->view('help/help_user', '', true);
						//$data['main_content']=$this->load->view('index/home_usuario', '', true);
					break;
					
				}
						//$data['main_content']=$this->load->view('index/home_usuario', '', true);
					$data['meta']=$this->load->view('meta', array('lib_calendar' => TRUE), true);
					$data['header']=$this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true);
					$data['navigation']=$this->load->view('navigation', '', true);
					$data['footer']=$this->load->view('footer', '', true);
					//print_r($data);
					$data['main_style']='mainContent_index';
		      $this->load->view('main', $data);
				
			}
			else {

				$this->session->set_userdata('error_message', 'Pagina no accesible');
				redirect(site_url(), 'Location'); 
				exit();

			}
								//print		($this->redux_auth->logged_in());		
	}


	
}

/* End of file help.php */
/* Location: ./system/application/controllers/help.php */