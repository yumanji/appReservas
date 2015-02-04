<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class app_common
{
	public function app_common()
	{
		isset($this->CI) || $this->CI =& get_instance();
		log_message('debug', "app_common Class Initialized");
	}
	
	public function arrayToOptionConverter($array_values = NULL)
    {
		

		# Si viene vacío, devuelvo vacío
		if(!isset($array_values) || !is_array($array_values)) return NULL;
		
		$salida='';
		foreach($array_values as $codigo => $valor) $salida.='<option value="'.$codigo.'">'.$valor.'</option>';
		
		return $salida;
	}

    public function get_menu_options()
    {
			# Menu básico
			$menu=array ();							

			# Si está logueado, de momento, pinto todas las opciones restantes
			if($this->redux_auth->logged_in()) {
				$profile=$this->redux_auth->profile();
				$user_group=$profile->group;
				
				$menu=array (
														array('mnu_inicio', ''),
														array('mnu_reserva', 'reservas')
											);
											
				if($user_group < 7) {
					array_push($menu, 
													array('mnu_gestion', 'gestion', array(
														array('mnu_reservas', 'reservas_gest', array(
																array('mnu_reservas_all', 'reservas_gest/list_all'),
																array('mnu_reservas_today', 'reservas_gest/today'),
																array('mnu_reservas_status', 'reservas_gest/status'),
																array('mnu_reservas_canceled', 'reservas_gest/canceled'),
																array('mnu_reservas_by_user', 'reservas_gest/list_owner'),
																array('mnu_reservas_new_by_phone', 'reservas_gest/new_phone'),
																array('mnu_reservas_new_present', 'reservas_gest/new_present')
																)
														),
														array('mnu_socios', 'users', array(
																array('mnu_socios_new', 'welcome/under_construction'),
																array('mnu_socios_list', 'welcome/under_construction')
																)
														),
														array('mnu_factura', 'facturacion/list_all')
														)
													)
										);
					} // Find e chequeo de nivel < 7
					
					
					
				if($user_group < 3) {
					//print("<pre>");print_r($menu[2][2]);print_r($menu);
					# Gestión de clases
					array_push($menu[2][2], array('mnu_actividades', 'activities'));
					
					# Modulo de informes
					array_push($menu, 
													array('mnu_report', 'informes', array(
														array('mnu_reservas', 'informes/reserva_diaria'),
														array('mnu_socios', 'welcome/under_construction', array(
																array('mnu_socios_new', 'socios/new'),
																array('mnu_socios_list', 'socios')
																)
														),
														array('mnu_factura', 'informes/facturacion_diaria'),
														array('mnu_actividades', 'welcome/under_construction')
														)
													),
														array('mnu_recepcion', 'recepcion/index', NULL,1)
												);
					}// Find e chequeo de nivel < 3

					if($user_group == 7) {
						array_push($menu, 
													array('mnu_perfil', 'welcome/under_construction')
										);
					}
					array_push($menu, 
													array('mnu_logout', 'welcome/logout')
										);
					
			}
			
			return $menu;
    }





    public function IntervalToTime($number) {
    	if($number===0) return '0 '.$this->CI->lang->line('minutes');
    	
    	$salida="";
    	$salida=floor($number/2);
    	if((floor($number/2))!=1 ) $salida.= ' '.$this->CI->lang->line('hours');
    	else $salida.= ' '.$this->CI->lang->line('hour');
			if($number%2) $salida.= ' '.$this->CI->lang->line('and').' 30 '.$this->CI->lang->line('minutes');
			
			return $salida;
    }
    
    

    public function getIdTransaction() {
			$idTransaction=$this->CI->session->userdata('session_id').'-'.time();			
			return $idTransaction;
    }
    
    
    
/*
|--------------------------------------------------------------------------
| PaymentMethodStatus
|--------------------------------------------------------------------------
|
| Comprueba si una forma de pago concreta está habilitada por configuración o no
|	cash, reserve, paypal, creditcard, bank, prepaid
|
*/
    public function PaymentMethodStatus($method) {
    	$method=strtolower($method);
    	
    	switch($method) {
    		case "cash":
    			return $this->CI->config->item('enable_cash');
    		break;

    		case "reserve":
    			return $this->CI->config->item('enable_reserve');
    		break;

    		case "prepaid":
    			return $this->CI->config->item('enable_prepaid');
    		break;

    		case "paypal":
    			return $this->CI->config->item('enable_paypal');
    		break;

    		case "creditcard":
    			return $this->CI->config->item('enable_creditcard');
    		break;

    		case "tpv":
    			return $this->CI->config->item('enable_tpv');
    		break;

    		case "bank":
    			return $this->CI->config->item('enable_bank');
    		break;
    		
    		default:
    			return FALSE;
    		break;
    	}
    	
    }
    
    
    
/*
|--------------------------------------------------------------------------
| get_page_id
|--------------------------------------------------------------------------
|
| Devuelte el nombre de la pagina en que estamos en formato almacenable
|
*/
    public function get_page_id() {
    	
			return str_replace('/', '_', $this->CI->uri->uri_string());
			
    	
    }
    
 
  
    
    
/*
|--------------------------------------------------------------------------
| reserve_encode
|--------------------------------------------------------------------------
|
| Compone un número alfanumérico  a partir del id de reserva
|
*/
 
	public function reserve_encode ($code) {
		$longitud=5;
		$pass1 = strtoupper(md5($code));
		$rand_start = 8;
		$pass2 = strtoupper(substr($pass1, $rand_start, $longitud).substr($pass1, 2, 1));
		return sprintf("%'A6s", $pass2);
	}	
}
