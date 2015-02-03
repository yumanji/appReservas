<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class users_lib
{
	public function users_lib()
	{
		isset($this->CI) || $this->CI =& get_instance();
		log_message('debug', "users_lib Class Initialized");
		$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);
	}


 ##############
 #
 # Devuelve listado de codigos de usuario que, según parametrización, ya deben pagar sus cuotas
 #
 ####################
	public function get_quotable_users($valid = TRUE) {
		
		$niveles_que_pagan = $this->get_quotable_groups();
		//print("aa<pre>");print_r($niveles_que_pagan);exit();
		
		# Si no hay niveles a los que cobrar.. lista vacía
		if(!count($niveles_que_pagan)) return NULL;

		$where = "users.active = '1'";
		
		$dias_caducidad = $this->CI->config->item('users_qouta_caducity_days');
		if(!isset($dias_caducidad) || $dias_caducidad =='') $dias_caducidad = 0;
		$fecha_control = date($this->CI->config->item('date_db_format'), strtotime(date($this->CI->config->item('date_db_format')).' +'.$dias_caducidad.'days'));
		//echo $fecha_control.'---';
		$usuarios = $this->CI->usuario->get_data(array('where' => "users.active = '1' and users.group_id IN ('".implode("'. '", $niveles_que_pagan)."') and meta.code_price is not null and meta.code_price <> '0' and meta.last_payd_date is not null and meta.last_payd_date <= '".$fecha_control."'"))->result_array();
		//echo $this->CI->db->last_query();
		$pagadores = array();
		$sin_datos = array();
		foreach($usuarios as $usuario) {
			/*
			if($usuario['bank'] != '' && $usuario['bank_office'] != '' && $usuario['bank_dc'] != '' && $usuario['bank_account'] != '' && intval($usuario['bank']) != 0 && intval($usuario['bank_office']) != 0 && $usuario['bank_dc'] != '0' && intval($usuario['bank_account']) != '0' ) array_push($pagadores, $usuario);
			else  array_push($sin_datos, array('id' => $usuario['id'], 'code_price' => $usuario['code_price'], 'nombre' => trim($usuario['first_name'].' '.$usuario['last_name'])));
			*/
			
			array_push($pagadores, $usuario);
		}
		if($valid) return $pagadores;
		else return $sin_datos;
		

	}




 ##############
 #
 # Devuelve listado de codigos de usuario que, según parametrización, ya deben pagar sus cuotas
 #
 ####################
	public function pay_user_quota($code_user, $options = NULL) {
		
			$last_payd_date = $this->CI->usuario->getLastPayedDate($code_user);
			$next_payment_date = '';
			if(isset($options['payd_date_tmp']) && $options['payd_date_tmp']!='') $next_payment_date = date($this->CI->config->item('date_db_format'), strtotime($options['payd_date_tmp']));
			else $next_payment_date = $this->CI->usuario->getNextPaymentDate($code_user);
			//echo '<br>nueva last_payd_date:'.$last_payd_date;
			//echo '<br>siguiente fecha pago:'.$next_payment_date;
			//exit();
			$quota = 0;
			if(isset($options['payable_quota']) && $options['payable_quota']!='' && is_numeric($options['payable_quota'])) $quota = $options['payable_quota'];
			else if($options['code_price']!='') $quota = $this->CI->usuario->get_userQuota($options);
			//echo '<br>cuota de usuario '.$code_user.': '.$quota;
			//exit();

			
			if(isset($options['paymentway']) && $options['paymentway']!='') $paymentway = $options['paymentway'];
			else $paymentway = $this->CI->config->item('users_qouta_default_paymentway');
			//echo '<br>Forma de pago: '.$paymentway;
			
			if(isset($options['status']) && $options['status']!='') $estado = $options['status'];
			else $estado = $this->CI->config->item('users_qouta_default_payment_status');
			//echo '<br>Estado de pago: '.$estado;
			
			
			# Si es para la lista de espera o si la cuota de alta es 0 o si, siendo otra, viene con la acción de 'save', osea, ya pagada.. 
			if(isset($quota) && $quota!="" && isset($paymentway) && $paymentway!=0 && $paymentway!="") {
				$fecha_humana = date($this->CI->config->item('reserve_date_filter_format'), strtotime($next_payment_date));
				if($this->CI->usuario->setMonthlyPayment($code_user, $next_payment_date)) $this->CI->session->set_userdata('info_message', 'Pago hasta el '.$fecha_humana.' realizado');
				else {
					return FALSE;
				}

				$user_desc = $options['name'];
			
				//$estado = 9;
				//if($estado=='' && $paymentway == 4) $estado = 2;
				if($quota == 0) $estado = 9;	// Si la cuota resultante es '0', se pone siempre como pagado para evitar pagos raros pendientes en remesas

				$this->CI->load->model('Payment_model', 'pagos', TRUE);
				$this->CI->pagos->id_type = 5; //Clases y cursos
				$this->CI->pagos->id_element = $this->CI->session->userdata('session_id');
				$this->CI->pagos->id_transaction = '5-'.$code_user.'-'.date('U', strtotime($next_payment_date));	// Formato '5' de usuario, codigo de usuario, y timestamp de fecha de pago
				$this->CI->pagos->id_user = $code_user;
				$this->CI->pagos->desc_user = $user_desc;
				$this->CI->pagos->id_paymentway = $paymentway;
				$this->CI->pagos->status = $estado;
				$this->CI->pagos->quantity = $quota;
				//$this->pagos->description="Cuota de usuario '".$user_desc."', hasta el ".$fecha_humana;
				$this->CI->pagos->description = "Cuota de usuario hasta el ".$fecha_humana;
				$this->CI->pagos->datetime = date($this->CI->config->item('log_date_format'));
				$this->CI->pagos->fecha_valor = $last_payd_date;
				$this->CI->pagos->create_user = $this->CI->session->userdata('user_id');
				$this->CI->pagos->create_time = date($this->CI->config->item('log_date_format'));
				
				$this->CI->pagos->setPayment();
			
				return TRUE;
				//redirect($returnUrl, 'Location'); 
				exit();
			} else return FALSE;
	}



 ##############
 #
 # Devuelve listado de niveles de usuario que, según parametrización, deben pagar  cuotas
 #
 ####################
	public function get_quotable_groups() {
		
		$niveles_que_pagan_arr = $this->CI->config->item('users_quota_group');
		$niveles_que_pagan = array();
		foreach($niveles_que_pagan_arr as $grupo => $permiso) if($permiso) array_push($niveles_que_pagan, $grupo);
		
		if(count($niveles_que_pagan)) return $niveles_que_pagan;
		else return NULL;

}




	/**
	 * Generar el array de datos a exportar
	 *
	 * @return boolean
	 * @author 
	 **/
	public function export_data($opciones = NULL)
	{
			$this->CI->load->model('redux_auth_model', 'redux2', TRUE);

			//$records = $this->CI->payment2->get_data(array('page' => 1, 'num_rows' => 25));
			$records = $this->CI->redux2->get_data_to_export();
			//$resultado = $records->result_array();
			
		//echo $texto."<pre>"; print_r($records);exit();

			return $records;

	}


# -------------------------------------------------------------------
#  genera un fichero de texto en el servidor con los datos de usuarios
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function exportacion ($opciones = NULL)
	{
		//$this->load->model('Reservas_model', 'reservas', TRUE);


		$exportacion = $this->export_data(array('formato' => 'array', 'opcion' => $opciones));
		//echo $texto."<pre>"; print_r($exportacion);exit();
		$texto = $this->CI->app_common->to_csv($exportacion);
		//$data->rows = $records;
		//echo $texto."<pre>"; print_r($exportacion);exit();
		
		$fp = fopen($this->CI->config->item('root_path').'data/users_'.md5($this->CI->config->item('club_name')).'.txt', 'w');
		fwrite($fp, utf8_decode($texto));
		fclose($fp);

		//echo json_encode ($data );
		//exit( 0 );
	}




/** 
 * my_bcmod - get modulus (substitute for bcmod) 
 * string my_bcmod ( string left_operand, int modulus ) 
 * left_operand can be really big, but be carefull with modulus :( 
 * by Andrius Baranauskas and Laurynas Butkus :) Vilnius, Lithuania 
 **/ 
function personal_bcmod ( $x, $y ) 
{ 
    // how many numbers to take at once? carefull not to exceed (int) 
    $take = 5;     
    $mod = ''; 

    do 
    { 
        $a = (int)$mod.substr( $x, 0, $take ); 
        $x = substr( $x, $take ); 
        $mod = $a % $y;    
    } 
    while ( strlen($x) ); 

    return (int)$mod; 
} 	
	
	
 /**
 * Gernerar el DC, dígito control de IBAN, y devolver en nuevo IBAN con DC
 * 
 * @link http://www.desarrolloweb.com/articulos/2484.php
 * @param string $_iban
 * @return $iban_
 */
 function generarDCInToIban( $_iban ) {
  $ibanConDC_ = -1;
  
  // IBAN sin DC, DC = 00 : BE00999999999999 
   // IBAN con DC, DC = 89 : BE89999999999999
  
  // Mover los cuatro primeros caracteres del número IBAN a la derecha: 
  $ibanConDC_ = substr($_iban,4)."".substr($_iban,0,4);
  
  
  // Convertir las letras a números según la siguiente tabla:
  // A=10 G=16 M=22  S=28 Y=34
  // B=11 H=17 N=23  T=29 Z=35
  // C=12 I=18 O=24 U=30
  // D=13 J=19 P=25 V=31
  // E=14 K=20 Q=26 W=32
  // F=15 L=21 R=27 X=33
  $letras_array = array("A","B","C","D","E","F","G","H","I","J","K","L",
        "M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
  $numeros_array = array("10","11","12","13","14","15","16","17","18","19","20","21",
        "22","23","24","25","26","27","28","29","30","31","32","33","34","35");
  
  
  $ibanConDC_ = str_replace( $letras_array, $numeros_array, $ibanConDC_);
  
  
  // Aplicar la operación módulo 97 y restar al número 98, el valor obtenido. 
  // Si el resultado consta de sólo un dígito, insertar un cero a la izquierda.
  
  //$modulo97 = intval($ibanConDC_) % 97;
  //$modulo97 = $ibanConDC_ % 97;
  $modulo97 = $this->personal_bcmod($ibanConDC_,  97);
  //exit('aaaaaaaaaaaaaaaaaaaaaaaa');
  
  $dc = 98 - $modulo97;
  
  // insertar 0 a la izquierda si fuera menor de dos dígitos
  $dc = sprintf("%02d",$dc);
  
  // Sustituimos los dígitos 2 y 4 por el $dc
  $ibanConDC_ =  substr($_iban,0,2).$dc.substr($_iban,4);
  
  
  return $ibanConDC_;
 }	
	
	

}
