<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class app_common
{
	public function app_common()
	{
		$this->CI =& get_instance();
		log_message('debug', "app_common Class Initialized");
	}
	
	public function arrayToOptionConverter($array_values = NULL)
    {
		//$this->CI =& get_instance();

		# Si viene vacío, devuelvo vacío
		if(!isset($array_values) || !is_array($array_values)) return NULL;
		
		$salida='';
		foreach($array_values as $codigo => $valor) $salida.='<option value="'.$codigo.'">'.$valor.'</option>';
		
		return $salida;
	}

    public function get_menu_options()
    {
    	$this->CI->load->library('redux_auth');
    	
			# Menu básico
			$menu=array ();							

			# Si está logueado, de momento, pinto todas las opciones restantes
			if($this->CI->redux_auth->logged_in()) {
				$profile=$this->CI->redux_auth->profile();
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
														array('mnu_factura', 'facturacion/list_all'),
														array('mnu_recepcion', 'recepcion/index', NULL,1)
														)
													)
										);
					} // Fin de chequeo de nivel < 7
					
					
					
				if($user_group < 3) {
					//print("<pre>");print_r($menu[2][2]);print_r($menu);
					# Gestión de clases
					array_push($menu[2][2], array('mnu_actividades', 'lessons'));
					
					# Modulo de notificaciones
					array_push($menu[2][2],  array('mnu_notification', 'notifications'));
					
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
													)
												);
					}// Fin de chequeo de nivel < 3

					array_push($menu, 
												array('mnu_perfil', 'users/profile/'.$this->CI->session->userdata('user_id'))
									);

					array_push($menu, 
													array('mnu_logout', 'welcome/logout')
										);
					
			}
			
			return $menu;
    }




    public function IntervalToTime($number, $court = NULL) {
    	if($number===0) return '0 '.$this->CI->lang->line('minutes');
		$reserve_interval = $this->CI->config->item('reserve_interval');
		
		if(isset($court) && $court!='') {
			$this->CI->load->model('Pistas_model', 'pistas', TRUE);
			$reserve_interval = $this->CI->pistas->getCourtInterval($court);
		}
		
		if(!isset($reserve_interval) || $reserve_interval == '') return '0 '.$this->CI->lang->line('minutes');
    	$minutos = $number * $reserve_interval;
		$horas = $minutos/60;
    	$salida="";
    	$salida = floor($horas);
    	if($horas >=2 ) $salida.= ' '.$this->CI->lang->line('hours');
    	else $salida.= ' '.$this->CI->lang->line('hour');
		
		if($minutos%60) $salida.= ' '.$this->CI->lang->line('and').' '.($minutos%60).' '.$this->CI->lang->line('minutes');
			
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



		function changePaymentwayNotation($description) {
			$id = NULL;
			switch($description) {
				case 'cash':
					$id = 1;
				break;
				case 'paypal':
					$id = 3;
				break;
				case 'prepaid':
					$id = 5;
				break;
				case 'creditcard':
					$id = 2;
				break;
				case 'tpv':
					$id = 6;
				break;
				case 'bank':
					$id = 4;
				break;
			}
			return $id;
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
	
	
/*
|--------------------------------------------------------------------------
| get_court_availability
|--------------------------------------------------------------------------
|
| Devuelve un array de disponibilidad de una pista para una fecha
|	[0] Hora inicio (intervalo)
|	[1] Disponibilidad (1-> Disponible para reservar)
|	[2] Id_Transaction en caso de haberlo
|	[3] Estado de la reserva en caso de haberla
|	[4] Rango de horas de la reserva asociada
|	[5] Shared (1-> Si es un reto) [opcional! no siempre aparece!]
|	[6] Sin coste (1-> reserva sin coste) [opcional! no siempre aparece!]
|
*/
 
	public function get_court_availability ($court = NULL, $date = NULL, $exclusion = NULL, $options = NULL) {
		
		if(!isset($court) || $court == '' || !isset($date) || $date == '') return NULL;
		
		$this->CI =& get_instance();
		$this->CI->load->model('Reservas_model', 'reservas', TRUE);
		$this->CI->load->model('Pistas_model', 'pistas', TRUE);
		$this->CI->load->model('Lessons_model', 'lessons', TRUE);
		
		$this->CI->reservas->availability = NULL;
		
		$this->CI->reservas->date=$date;
		$this->CI->reservas->court=$court;
		$this->CI->pistas->id=$court;
		$reserve_interval = $this->CI->pistas->getCourtInterval($court);
		
		$this->CI->reservas->getSpecialTimetableByCourt();
		if(!$this->CI->reservas->availability) $this->CI->reservas->getSpecialTimetable();
		if(!$this->CI->reservas->availability) $this->CI->reservas->availability=$this->CI->pistas->getTimetable($date);
		//print_r($this->CI->pistas->getTimetable($date));
		$this->CI->reservas->availability=$this->CI->lessons->updateTimetable($date, $court, $this->CI->reservas->availability);
		$this->CI->reservas->getAvailabilityByCourt($date, $court, $exclusion, $options);
		$disponibilidad = $this->CI->reservas->availability;
		foreach($disponibilidad as $time => $datos) {
			if(!isset($disponibilidad[$time][4]) || $disponibilidad[$time][4]=='') $disponibilidad[$time][4]= $datos[0].'-'.date($this->CI->config->item('hour_db_format'), strtotime($datos[0])+($reserve_interval *60));
		}
		$this->CI->reservas->availability = $disponibilidad;
		//print("<pre>");print_r($this->CI->reservas->availability);exit();
		return $this->CI->reservas->availability;
		//print("ppp");print_r($this->CI->reservas->getSpecialTimetableByCourt());exit();
	}
	
	
	
	
	
	
	
	
	
/*
|--------------------------------------------------------------------------
| getPrices
|--------------------------------------------------------------------------
|
| Devuelve lista de las tarifas disponibles
|
*/
 
  function getPrices ($format = 'array', $tipo = '')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM prices";
      if($tipo != '') $sql .=' WHERE type = '.$tipo.' '; 
      $sql .=' ORDER BY description'; 
			$query = $this->CI->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }

	



/*
|--------------------------------------------------------------------------
| getPriceValue
|--------------------------------------------------------------------------
|
| Devuelve precio para una tarifa concreta para los datos concretos aportados
|
*/    function getPriceValue($id_price, $data_extra = NULL, $options = NULL) {
    	# Devuelve precio del intervalo del día para la pista solicitada
//echo "A";    	
 //echo "B";    	
 $this->CI->load->config('facturacion');
    	$debug = FALSE;



			# Comprobacion de calculo de tarifa especial   prices_alternative_funtion
    	$alternative_function = $this->CI->config->item('prices_alternative_funtion');
    	$alternative_function_price = $this->CI->config->item('prices_alternative_values');
    	//print_r($alternative_function_price);
    	if(isset($alternative_function) && isset($alternative_function_price) && $alternative_function && in_array($id_price, $alternative_function_price)) {
    		$precio = $this->getPriceValueAlternative($id_price, $data_extra, $options);
		   if($debug) echo 'precio alternativo';
    		return $precio;
    	}
    	
   if($debug) echo 'C';
    	
    	if(isset($data_extra['date']) && $data_extra['date'] ) $date = $data_extra['date'];
    	else $date = date($this->CI->config->item('log_date_format'));
    	
    	if(isset($data_extra['time']) && $data_extra['time'] ) $time = $data_extra['time'];
    	else $time = date($this->CI->config->item('hour_db_format'));
    	
    	$group_test = $this->CI->session->userdata('group_id');
    	if(isset($data_extra['group']) && $data_extra['group'] ) $group = $data_extra['group'];
    	elseif(isset($group_test)) $group = $group_test;
    	else $group = 9;
    	//if(!isset($group)) $group = 9;
    	
			$weekday=@date('N', strtotime($date));
			$interval=@date($this->CI->config->item('hour_db_format'), strtotime($time));

//echo "C";    	
			
				if(isset($id_price) && $id_price != "") {
//echo "D";    	
					# Debo comprobar si el día está definido como festivo en algún calendario para saber qué consulta hacer
					
					# Si no es festivo
			    $sql = "SELECT prices.quantity, prices.by_group, prices.by_weekday, prices.by_time FROM prices WHERE prices.id = ? and prices.active = '1' and prices.start_date <= ? and prices.end_date >= ?  LIMIT 1"; 
					$query = $this->CI->db->query($sql, array($id_price, $date, $date));
					//log_message('debug', 'SQL: '.$this->db->last_query());
					if($debug) echo "\r\n".$this->CI->db->last_query();
					if ($query->num_rows() > 0) {	
//echo "E";    	
						$row = $query->row();
						$quantity = $row->quantity;
						$by_group = $row->by_group;
						$by_weekday = $row->by_weekday;
						$by_time = $row->by_time;
						
						if ($by_weekday == '1' && $by_time == '0') {
							
							# Si la tarifa es solo de weekday y no de time, busco los registros con weekday='0'
							$sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and start_date <= ? and end_date >= ? and weekday = ?"; 
							$query2 = $this->CI->db->query($sql2, array($id_price, $date, $date, $weekday));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							if($debug) echo "\r\n".$this->CI->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;
							
						} elseif ($by_weekday == '1' && $by_time == '1' && $by_group == '1') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
							$sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and id_group = ? and weekday = ? and start_date <= ? and end_date >= ? and time = ?"; 
							$query2 = $this->CI->db->query($sql2, array($id_price, $group, $weekday, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							if($debug) echo "\r\n".$this->CI->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
							if($debug) echo "\r\n---!!-".$row2->quantity;
																			
						} elseif ($by_weekday == '0' && $by_time == '1' && $by_group == '1') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
							$sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and id_group = ? and weekday = 0 and start_date <= ? and end_date >= ? and time = ?"; 
							$query2 = $this->CI->db->query($sql2, array($id_price, $group, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							if($debug) echo "\r\n".$this->CI->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
							if($debug) echo "\r\n------".$row2->quantity;
												
						}	 elseif ($by_weekday == '1' && $by_time == '1' && $by_group == '0') {
							
							# Si la tarifa es de weekday y de time, busco los registros con weekday concreto
							$sql2 = "SELECT quantity FROM prices_by_time WHERE id_price = ? and weekday = ? and start_date <= ? and end_date >= ? and time = ?"; 
							$query2 = $this->CI->db->query($sql2, array($id_price, $weekday, $date, $date, $interval));
							//log_message('debug', 'SQL: '.$this->db->last_query());
							//echo $this->db->last_query();
							if($debug) echo "\r\n".$this->CI->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;		
												
						} elseif($by_group == '1') {
							
							# Si la tarifa es de grupos, busco los registros en la tabla adecuada
							$sql2 = "SELECT quantity FROM prices_by_group WHERE id_price = ? and id_group = ? and start_date <= ? and end_date >= ?"; 
							$query2 = $this->CI->db->query($sql2, array($id_price, $group, $date, $date));
							if($debug) echo "\r\n".$this->CI->db->last_query();
							//echo $this->db->last_query();
							$row2 = $query2->row();
							$quantity = $row2->quantity;							
							
						} elseif($by_weekday == '0' && $by_time == '0' && $by_group == '0') {
							
							# Si la tarifa es fija.. se coge el quantity simplemente
							$quantity = $row->quantity;							
							
						}
						
						
						if(isset($options['light']) && $options['light']) {if(!$this->getLightPrice()) $this->price_light = 0; }
						//$this->price = $quantity;
						//$this->price_court = $quantity;
						//echo "<br>--".$quantity;    exit();	
						if(isset($options['load_result']) && $options['load_result']!='') $this->$options['load_result'] = $quantity;
						return $quantity;
					} else return NULL;
					//date('H:i', strtotime($row->interval))
					
					
					# Si es festivo
					# ...
					
				} else return NULL;
    }










/*
|--------------------------------------------------------------------------
| getPriceValueAlternative
|--------------------------------------------------------------------------
|
| Devuelve precio para una tarifa concreta para los datos concretos aportados
|
*/    function getPriceValueAlternative($id_price, $data_extra = NULL, $options = NULL) {
    	# Devuelve precio del intervalo del día para la pista solicitada
//echo "A";    	
//echo "B";    	
    	$debug = FALSE;



			# Comprobacion de calculo de tarifa especial   prices_alternative_funtion
    	$alternative_function = $this->CI->config->item('prices_alternative_funtion');
    	$alternative_function_price = $this->CI->config->item('prices_alternative_values');

    	if(!isset($alternative_function) || !isset($alternative_function_price) || !$alternative_function) return NULL;
    	//exit ('assasasa');
    	//$id_price = 2;
    	
    	if(isset($data_extra['date']) && $data_extra['date'] ) $date = $data_extra['date'];
    	else $date = date($this->CI->config->item('log_date_format'));
    	
    	if(isset($data_extra['time']) && $data_extra['time'] ) $time = $data_extra['time'];
    	else $time = date($this->CI->config->item('hour_db_format'));
    	
    	$group_test = $this->CI->session->userdata('group_id');
    	if(isset($data_extra['group']) && $data_extra['group'] ) $group = $data_extra['group'];
    	elseif(isset($group_test)) $group = $group_test;
    	else $group = 9;
    	//if(!isset($group)) $group = 9;
    	
    	if($debug) print_r($data_extra);
    	
			$weekday=@date('N', strtotime($date));
			$interval=@date($this->CI->config->item('hour_db_format'), strtotime($time));
//echo "C";    	
			
				if(isset($id_price) && $id_price != "") {
//echo "D";    	
					# Debo comprobar si el día está definido como festivo en algún calendario para saber qué consulta hacer
					
					# Si no es festivo
			    $sql = "SELECT prices.quantity, prices.by_group, prices.by_weekday, prices.by_time FROM prices WHERE prices.id = ? and prices.active = '1' and prices.start_date <= ? and prices.end_date >= ?  LIMIT 1"; 
					$query = $this->CI->db->query($sql, array($id_price, $date, $date));
					//log_message('debug', 'SQL: '.$this->db->last_query());
					if($debug) echo '<br>'.$this->CI->db->last_query();
					if ($query->num_rows() > 0) {	
//echo "E";    	
						$row = $query->row();
						$quantity = $row->quantity;
						$by_group = $row->by_group;
						$by_weekday = $row->by_weekday;
						$by_time = $row->by_time;
						
						$hoy = strtotime(date($this->CI->config->item('date_db_format')));
						$reserva = strtotime($date);
						//if($debug) echo 'Hoy: '.$hoy.' Reserva '.$date;
						$diferencia = $reserva - $hoy;
						$dif_dias = ($diferencia / 86400);
						if($debug) echo date($this->CI->config->item('log_date_format')).' - '.$date.' Hoy: '.$hoy.' Reserva '.$reserva.' - diferencia '.$diferencia.' segundos y '.$dif_dias.' dias ';
						
						
						if(isset($options['light']) && $options['light']) {if(!$this->getLightPrice()) $this->price_light = 0; }
						//$this->price = $quantity;
						//$this->price_court = $quantity;
						$precio = $quantity*$dif_dias;
						if(isset($options['load_result']) && $options['load_result']!='') $this->$options['load_result'] = $precio;
						//echo "\r\n".$quantity*$dif_dias;
						return $precio;
					} else {
						if($debug) echo 'asdasdadsda';
						return NULL;
					}
					//date('H:i', strtotime($row->interval))
					
					
					# Si es festivo
					# ...
					
				} else return NULL;
    }


	
	
/*
|--------------------------------------------------------------------------
| getPaymentFrequency
|--------------------------------------------------------------------------
|
| Devuelve lista de las tarifas disponibles
|
*/
 
  function getPaymentFrequency ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_payment_frequency";
      if($tipo != '') $sql .=' WHERE type = '.$tipo.' '; 
      else $sql .=' WHERE 1 = 1 '; 
      $sql .=' AND Active = 1 ORDER BY description'; 
			$query = $this->CI->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }



	
	
	






function to_csv( $array ) {
 $csv;
if(!is_array($array)) return $csv;
 ## Grab the first element to build the header
 $arr = array_pop( $array );
if(!is_array($arr)) return $csv;
 $temp = array_keys($arr);

 $csv = implode( "\t", $temp );
//return $csv;
 ## Add the data from the first element
 $csv .= $this->to_csv_line( $arr );

 ## Add the data for the rest
 foreach( $array as $arr ) {   
   $csv .= $this->to_csv_line( $arr );
 }
	//$csv .= "\r";
 return $csv;
}

function to_csv_line( $array ) {
 $temp = array();
 foreach( $array as $elt ) {
   $temp[] = '"' . addslashes( html_entity_decode ($elt, ENT_NOQUOTES, 'UTF-8') ) . '"';
 }

 $string = "\r\n" . implode( "\t", $temp );

 return $string;
}	
	


	
	function ordenar_array() { 
  $n_parametros = func_num_args(); // Obenemos el número de parámetros 
  if ($n_parametros<3 || $n_parametros%2!=1) { // Si tenemos el número de parametro mal... 
    return false; 
  } else { // Hasta aquí todo correcto...veamos si los parámetros tienen lo que debe ser... 
    $arg_list = func_get_args(); 
 
    if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) { 
      return false; // Si el primero no es un array...MALO! 
    } 
    for ($i = 1; $i<$n_parametros; $i++) { // Miramos que el resto de parámetros tb estén bien... 
      if ($i%2!=0) {// Parámetro impar...tiene que ser un campo del array... 
        if (!array_key_exists($arg_list[$i], current($arg_list[0]))) { 
          return false; 
        } 
      } else { // Par, no falla...si no es SORT_ASC o SORT_DESC...a la calle! 
        if ($arg_list[$i]!=SORT_ASC && $arg_list[$i]!=SORT_DESC) { 
          return false; 
        } 
      } 
    } 
    $array_salida = $arg_list[0]; 
 
    // Una vez los parámetros se que están bien, procederé a ordenar... 
    $a_evaluar = "foreach (\$array_salida as \$fila){\n"; 
    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada columna... 
      $a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n"; 
    } 
    $a_evaluar .= "}\n"; 
    $a_evaluar .= "array_multisort(\n"; 
    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada elemento... 
      $a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[".($i+1)."],\n"; 
    } 
    $a_evaluar .= "  \$array_salida);"; 
    // La verdad es que es más complicado de lo que creía en principio... :) 
 
    eval($a_evaluar); 
    return $array_salida; 
  } 
} 
	




/**
 * This file is part of the array_column library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2013 Ben Ramsey <http://benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */


    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {

            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }







	
	
}
