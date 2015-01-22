<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class booking
{
	public function booking()
	{
		$this->CI =& get_instance();
		log_message('debug', "booking Class Initialized");
	}


	/**
	 * Cambio de reserva
	 *
	 * @return array
	 * @author Mathew
	 **/
	public function change_reserve($id_transaction_old, $info, $intervalo, $fecha = NULL, $id_court = NULL)
	{

		$this->CI->load->model('reservas_model', 'reservas2', TRUE);
		$this->CI->load->model('Pistas_model', 'pistas', TRUE);
		$this->CI->load->model('Redux_auth_model', 'usuario2', TRUE);

		//CALCULO DIFERENCIA ENTRE FECHAS PARA CONOCER INTERVALOS		
		$array_ini = explode(':', date('H:i',strtotime($intervalo)));
		$hora_inicio = $array_ini[0];
		$minuto_inicio = $array_ini[1];

		log_message('debug', 'Datos recibidos-> id_transaction: '.$id_transaction_old.'; info de reserva: '.var_export($info, TRUE).' intervalo: '.$intervalo.'; hora inicio: '.$hora_inicio.'; minuto inicio: '.$minuto_inicio.'; Fecha: '.$fecha.'; Id_court: '.$id_court);
		//echo 'aaaa';
		$result_message = '';
		$result = false;
		$hora_total_ini = ($hora_inicio * 60) + $minuto_inicio;
		$fecha_reserva = null;
		$id_user = null;
		$status = null;
		$id_paymentway = null;
		if (($minuto_inicio % 30) == 0)
		{
				//CALCULO DATOS ORIGINALES
				/*
				$this->db->select('*, MIN(INTERVALO) AS intervalo_ini, MAX(INTERVALO) AS intervalo_fin, count(*) num_intervalos', FALSE)->from('booking');
		    	$this->db->where('id_transaction',$id_transaction_old); 
		    	$this->db->group_by('id_transaction', 'asc');
				$record = $this->db->get();
				if ($record->num_rows() > 0)
				//log_message('debug', 'SQL: '.$this->db->last_query());
				*/ 
				if( isset($info) && is_array($info) && count($info) > 0 )
				{
					//$row = $record->row();			
							
					//Almaceno los datos que necesito
					//SI EL CAMBIO NO ES DEL MISMO TIEMPO SE CANCELA
						/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
						
					/*
					$array_booking = Array(
             'id'  => $row->id,
             'id_booking'  => $row->id_booking,
             'id_user'  => $row->id_user,
             'id_court'  => $row->id_court,
             'date'  => $row->date,
             'intervalo'  => $row->intervalo_ini,
             'status'  => $row->status,
             'id_paymentway'  => $row->id_paymentway,
             'price'  => $row->price,
             'no_cost'  => $row->no_cost,
             'no_cost_desc'  => $row->no_cost_desc,
             'user_nif'  => $row->user_nif,
             'user_desc'  => $row->user_desc,
             'user_phone'  => $row->user_phone,
             'id_transaction'  => $row->id_transaction,
             'booking_code'  => $row->booking_code,
             'intervalo_fin'  => $row->intervalo_fin,
             'num_intervalos'  => $row->num_intervalos);
           */
		      $array_booking = $info;
		      //print("<pre>");print_r($info);exit();
					$fecha_reserva_old = $info['date'];
					$id_court_old = $info['id_court'];
					$id_user = $info['user'];
					$user_desc = $info['user_desc'];
					$user_phone = $info['user_phone'];
					$status = $info['status'];
					$id_paymentway = $info['paymentway'];
					if(!isset($id_paymentway)) $id_paymentway = 0;
					$new_day = false;
					if($info['light'] == 'false' || !isset($info['price_light'])) $price_light = 0;
					else $price_light = $info['price_light'];
					//CHEQUEO DISPONIBILIDAD DE LA NUEVA RESERVA
					//RELLENO CAMPOS NECESARIOS
					$reserve_interval = $this->CI->pistas->getCourtInterval($info['id_court']);

					//$array_booking = $array_booking_ini[0];
					$suma_minutos_final = ($array_booking['intervals'] * $reserve_interval) + $minuto_inicio;	
					$intervalo_final = date('H:i',mktime($hora_inicio,$suma_minutos_final,0,1,1,1998));
					//Compruebo si la nueva reserva cambia de día y de pista o no coinciden los horarios
					if ($id_court_old != $id_court)
					{
						$new_day = true;
					}
					else if ($fecha != $fecha_reserva_old)
					{
						$new_day = true;
					}
					else if (($intervalo > $array_booking['fin']) && ($intervalo_final < $array_booking['inicio']))
					{
						$new_day = true;	
					}
					//chequeo normal				
					$this->CI->reservas2->court = $id_court; 
					$reserve_interval2 = $this->CI->pistas->getCourtInterval($id_court);

					$this->CI->reservas2->id_transaction = $id_transaction_old;					
					$this->CI->reservas2->date = $fecha;
					$this->CI->reservas2->intervalo = $intervalo;
					$this->CI->reservas2->group = $this->CI->usuario2->getUserGroup($id_user);
					if(!isset($this->CI->reservas2->group) || $this->CI->reservas2->group == '') $this->CI->reservas2->group = 9;
					
					//primero chequeo que esté disponible la nueva fecha
					for ($i = 0 ; $i < $array_booking['intervals']; $i ++) 
					{						
						$suma_minutos = ($i * $reserve_interval2) + $minuto_inicio;
						//relleno los campos necesarios para chequear disponibilidad
						$this->CI->reservas2->intervalo = date('H:i',mktime($hora_inicio,$suma_minutos,0,1,1,1998));
						//$this->intervalo = $intervalo_reserva;
						//chequeo si coincide el intervalo con la reserva anterior
						if (!$new_day)
						{
							//
							if ((date('H:i',strtotime($this->CI->reservas2->intervalo)) >= date('H:i',strtotime($array_booking['inicio']))) &&
								(date('H:i',strtotime($this->CI->reservas2->intervalo)) <= date('H:i',strtotime($array_booking['fin']))))
							{	
								//Coincide, no compruebo nada							
								log_message('debug', 'Coincide..');
							}
							else
							{				
								if (!$this->CI->reservas2->checkExactAvailability())
								{
									log_message('debug', 'No hay disponibilidad para el intervalo'.$this->CI->reservas2->intervalo);
									$result_message = 'No hay disponibilidad para el intervalo '.$this->CI->reservas2->intervalo;
									$result_availability = false;
									return $result_message;
								}								
							}
						}
						else 
						{						
							if (!$this->CI->reservas2->checkExactAvailability())
							{
								log_message('debug', 'No hay disponibilidad para el intervalo '.$this->CI->reservas2->intervalo);
								$result_message = 'No hay disponibilidad para el intervalo '.$this->CI->reservas2->intervalo;
								$result_availability = false;
								return $result_message;
							}
						}
						
					}
					//inserto en BBDD, he pasado la comprobacion
				
					//log_message('debug', 'ID_PAYMENT='.$id_paymentway);
					$reserve_interval2 = $this->CI->pistas->getCourtInterval($id_court);
					$sesion_insert = str_replace('{{slash}}', '\\',$this->CI->session->userdata('session_id'));
					$id_transaction_new = $sesion_insert.$this->CI->reservas2->court."-".date('U', strtotime($this->CI->reservas2->date." ".$this->CI->reservas2->intervalo));
					//&calculate_prices = false;
					for ($i = 0 ; $i < $array_booking['intervals']; $i ++) 
					{					
						$suma_minutos = ($i * $reserve_interval2) + $minuto_inicio;
						//relleno los campos necesarios para chequear disponibilidad
						$this->CI->reservas2->intervalo = date('H:i',mktime($hora_inicio,$suma_minutos,0,1,1,1998));
						
						//debug.log_message('debug','Intervalo: '.$this->intervalo);
						//INSERTO VALORES
						if  ($this->CI->reservas2->getPrice())
						{
								log_message('debug', 'Precio de la luz antes de grabar: '.$this->CI->reservas2->price_light);
								if($info['light'] == 'true') $price_light = $this->CI->reservas2->price_light;
								else $price_light = 0;
								log_message('debug', 'Precio de la luz ntes de grabar: '.$price_light);

								#Compruebo los extras
								$extras = $this->getExtra($id_transaction_new, $this->CI->reservas2->court.'-'.strtotime($this->CI->reservas2->date.' '.$this->CI->reservas2->intervalo), null, $info['create_time']);
								//if($debug) echo "\r\n".'<br>el extra es '.$extras.' ';
								log_message('debug', 'El extra a grabar en esta reserva es de : '.$extras);
								$this->CI->reservas2->price_supl1 = $extras; 
								$this->CI->reservas2->price = $this->CI->reservas2->price + $extras;



								$price_court = $this->CI->reservas2->price_court;
								if($i > 0) {
									$price_court = 0;
									$info['precio_supl1'] = 0;
									$info['precio_supl2'] = 0;
								}
							$data = array(
			               'id_booking' => $this->CI->reservas2->court."-".date('U', strtotime($this->CI->reservas2->date." ".$this->CI->reservas2->intervalo)),
			               //PREGUNTAR SI EL ID ES NUEVO O NO
			               'id_transaction' => $id_transaction_new,
			               'booking_code' => $this->CI->app_common->reserve_encode($this->CI->session->userdata('idTransaction')),
			               'id_user' => $id_user,
			               'user_desc' => $user_desc,
			               'user_phone' => $user_phone,
			               'id_court' => $this->CI->reservas2->court,
			               'date' => $this->CI->reservas2->date,
			               'session' => $sesion_insert,
			               'intervalo' => $this->CI->reservas2->intervalo,
			               //codigo comentado, bbdd desactualizada
			               'price' => $price_court + $price_light + $this->CI->reservas2->price_supl1 + $info['precio_supl2'],
			               'price_light' => $price_light,
			               'price_court' => $price_court,
			               'id_paymentway' => $id_paymentway,
			               'price_supl1' => $this->CI->reservas2->price_supl1,
			               'price_supl2' => $info['precio_supl2'],
			               /*'price_supl3' => 0,
			               'price_supl4' => 0,
			               'price_supl5' => 0,*/
			               'status' => $status,
			               'create_user' => $this->CI->session->userdata('user_id'),
			               'create_time' => $info['create_time']);
							# Si el status es mayor que cero, marco la reserva... Si no, solo ejecuto la funcionalidad de marcar en sesion
							$this->CI->db->insert('booking', $data);
							log_message('debug', 'SQL: '.$this->CI->db->last_query());
							
							$this->CI->reservas2->change_players($id_transaction_old, $id_transaction_new);
							$this->CI->reservas2->change_shared_players($id_transaction_old, $id_transaction_new);
							
							
						}
						else 
						{
							log_message('debug', 'No se han encontrado precios para el intervalo: '.$this->CI->reservas2->intervalo);
							$result_message = "No se han encontrado precios para el intervalo: ".$this->CI->reservas2->intervalo;
							return $result_message;	
						}
						
					}
				
					//UNA VEZ INSERTADOS LOS REGISTROS NUEVOS, BORRO LOS ANTERIORES
					if ($this->CI->reservas2->cancel_reserve($id_transaction_old, 'Cancelacion por cambio de horas'))
					{
						$result_message = "ok|".$id_transaction_new;
					}
					else
					{
						log_message('debug', 'La reserva anterior no se ha borrado');
						$result_message = "Importante: La reserva anterior no se ha borrado";
						return $result_message; 
					}
				}
				else
				{
					log_message('debug', 'La reserva que se quiere modificar, no existe.');
					$result_message = "Importante: La reserva que se quiere modificar no existe";
					return $result_message; 
				}	
				
				
							
			}
			else
			{
				$result_message = 'Las horas introducidas no corresponden con ningún horario';
				log_message('debug', 'Las horas introducidas no corresponden con ningun horario');
				return $result_message;	
			}
		return $result_message;


	}



	/**
	 * Cancelar reserva
	 *
	 * @return boolean
	 * @author 
	 **/
	public function cancel_reserve($reserva, $text_cancel, $opciones = NULL)
	{
			$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);
			$this->CI->load->model('reservas_model', 'reservas', TRUE);
			
			# Determina si el coste de la reserva se devuelve al bono monedero o se da por perdido.
			$refund_array = $this->CI->config->item('cancelled_reserve_refund');
			//print_r($refund_array); 
			//print_r($reserva); 
			$refund = FALSE;
			$grupo = $this->CI->usuario->getUserGroup($reserva['user']);
			//echo 'grupo: '.$grupo.'<br>';
			if(isset($refund_array[$grupo])) $refund = $refund_array[$grupo];
			if(isset($opciones['refund']) && $opciones['refund'] == FALSE) $refund = FALSE;
			
			
			# Determina si al cancelar la reserva se devuelve el pago.
			$cancel_array = $this->CI->config->item('cancelled_reserve_cancel_payment');
			//print_r($refund_array); 
			//print_r($reserva); 
			$cancel = FALSE;
			$grupo = $this->CI->usuario->getUserGroup($reserva['user']);
			//echo 'grupo: '.$grupo.'<br>';
			if(isset($cancel_array[$grupo])) $cancel = $cancel_array[$grupo];
			if(isset($opciones['cancel_payment']) && $opciones['cancel_payment'] == FALSE) $cancel = FALSE;
			if($cancel == TRUE) $refund = FALSE; # Si finalmente el pago se cancela, no devuelvo el pago como saldo de moneder EN NINGUN CASO
				
				/*
				if ($refund) exit("valor: OK");
				else  exit("valor: NO!");
				*/
			$result = $this->CI->reservas->cancel_reserve($reserva['id_transaction'], $text_cancel);
			if ($result)
			{
				log_message('debug', 'result');
				if(isset($opciones['mail']) && $opciones['mail']) {
					log_message('debug', 'mail');
					#Envio de mail de confirmacion de modificacion de reserva
					if($this->CI->config->item('reserve_send_mail')) {
												
						$this->notify_booking($reserva, array('action' => 'cancel'));
						
					}	//else log_message('debug', 'No enviar notificacion');					
					
				}
				
				
				if($cancel) {
					$this->CI->load->model('Payment_model', 'pagos', TRUE);
								
					$info = $this->CI->pagos->getPaymentByTransaction($reserva['id_transaction']);
					if(isset($info) && is_object($info) && $info->status == 2) {
						$this->CI->pagos->updatePaymentStatus('id_transaction', $reserva['id_transaction'], 7);
						
					} elseif(isset($info) && is_object($info) && $info->status != 2 && $info->status == 9) {
						# Creo el pago de devolución
						$this->CI->pagos->id_type=$info->id_type;
						$this->CI->pagos->id_element=$info->id_element;
						$this->CI->pagos->id_transaction=$info->id_transaction;
						$this->CI->pagos->id_user=$info->id_user;
						$this->CI->pagos->desc_user=$info->desc_user;
						$this->CI->pagos->id_paymentway=$info->id_paymentway;
						$this->CI->pagos->status=6;
						$this->CI->pagos->quantity=($info->quantity * (-1));
						$this->CI->pagos->datetime=date($this->CI->config->item('log_date_format'));
						$this->CI->pagos->fecha_valor=$info->fecha_valor;
						$this->CI->pagos->description='Devolucion de '.$info->description;
						$this->CI->pagos->create_user=$this->CI->session->userdata('user_id');
						$this->CI->pagos->create_time=date($this->CI->config->item('log_date_format'));
						$this->CI->pagos->setPayment();
					}
				}
				
				
				if($refund) {
					$this->CI->load->model('Payment_model', 'pagos', TRUE);
								
					#Recargar bono monedero...
					$code_user = $reserva['user'];
					$amount = $reserva['total_price'];
					$codigo_pedido = time('U').'pr';
					/* DEFINIR AQUI LO QUE RECIBA POR POST (CANTIDAD, FORMA DE PAGO...) PARA PASAR PARÁMETROS A LA FUNCION .. */

						//echo $this->input->post('amount');
						//echo $amount;
						$this->CI->usuario->addPrepaidMovement($code_user, $amount, '3', 1, date('U'));
						$info = $this->CI->pagos->getPaymentByTransaction($reserva['id_transaction']);
						
						/*
						print("<pre>");print_r($info);
						exit("AA");
						*/
						if(isset($info) && is_object($info)) {
							# Creo el pago de devolución
							$this->CI->pagos->id_type=$info->id_type;
							$this->CI->pagos->id_element=$info->id_element;
							$this->CI->pagos->id_transaction=$info->id_transaction;
							$this->CI->pagos->id_user=$info->id_user;
							$this->CI->pagos->desc_user=$info->desc_user;
							$this->CI->pagos->id_paymentway=$info->id_paymentway;
							$this->CI->pagos->status=6;
							$this->CI->pagos->quantity=($info->quantity * (-1));
							$this->CI->pagos->datetime=date($this->CI->config->item('log_date_format'));
							$this->CI->pagos->fecha_valor=$info->fecha_valor;
							$this->CI->pagos->description='Devolucion de '.$info->description;
							$this->CI->pagos->create_user=$this->CI->session->userdata('user_id');
							$this->CI->pagos->create_time=date($this->CI->config->item('log_date_format'));
							$this->CI->pagos->setPayment();
						
							# Creo el pago del saldo prepago
							$this->CI->pagos->id_type=3;
							$this->CI->pagos->id_element=$this->CI->session->userdata('session_id');
							$this->CI->pagos->id_transaction=$codigo_pedido;
							$this->CI->pagos->id_user=$code_user;
							$this->CI->pagos->desc_user=$info->desc_user;
							$this->CI->pagos->id_paymentway=1;
							$this->CI->pagos->status=9;
							$this->CI->pagos->quantity=$info->quantity;
							$this->CI->pagos->datetime=date($this->CI->config->item('log_date_format'));
							$this->CI->pagos->fecha_valor=date($this->CI->config->item('log_date_format'));
							$this->CI->pagos->description='Recarga de '.$info->quantity.' euros en bono prepago';
							$this->CI->pagos->create_user=$this->CI->session->userdata('user_id');
							$this->CI->pagos->create_time=date($this->CI->config->item('log_date_format'));
							$this->CI->pagos->setPayment();
						}
						//$this->pagos->updatePaymentStatus('id_transaction', $codigo_pedido, '9');
											
						//$this->session->set_userdata('info_message', 'Saldo prepago actualizado.');
						//redirect(site_url('reservas_gest/list_all'), 'Location'); 
						//exit('juju');
						


										
				}
				//print("<pre>");print_r($info); exit();
				$this->CI->session->set_userdata('info_message','La accion se ha realizado correctamente');
				//if($this->config->item('cancelled_reserve_refund') && isset($info) && $info['user'] && $info['status'] == '9') $this->usuario->addPrepaidMovement($info['user'], $info['total_price'], 1, 1, $id_transaction);
				
				return TRUE;

		}	
		else {
			# Opción solo para usuarios logueados. Si no lo está, se devuelve a página de inicio
			//redirect(site_url(), 'Location'); 
			//exit();
			return FALSE;
		}


	}





	/**
	 * Exportar reservas
	 *
	 * @return boolean
	 * @author 
	 **/
	public function export_data($opciones = NULL)
	{
			$this->CI->load->model('reservas_model', 'reservas2', TRUE);

			$records = $this->CI->reservas2->get_data_to_export();

			return $records;

	}


	/**
	 * Exportar jugadores de reservas
	 *
	 * @return boolean
	 * @author 
	 **/
	public function export_players_data($opciones = NULL)
	{
			$this->CI->load->model('reservas_model', 'reservas2', TRUE);

			$records = $this->CI->reservas2->get_data_players_to_export();

			return $records;

	}

	public function exportacion ($opciones = NULL)
	{
		ini_set('memory_limit', '512M');
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->CI->load->library('calendario');
		//$this->load->library('encrypt');
		//ini_set('memory_limit','512M');
		//phpinfo();exit();
		$exportacion = $this->export_data(array('formato' => 'array', 'opcion' => $opciones));
		$expotacion_clases = $this->CI->calendario->export_data(array('formato' => 'array', 'opcion' => $opciones));
		if(!is_array($expotacion_clases)) $expotacion_clases = array();
		$exportacion = array_merge($exportacion, $expotacion_clases);
		unset($expotacion_clases);
		//echo "<pre>"; print_r($expotacion_clases);echo 'JJJJJJJJJJ';print_r($exportacion);exit();
		//echo "<pre>"; print_r($exportacion);exit();
		$texto = $this->CI->app_common->to_csv($exportacion);
		unset($exportacion);
		//$data->rows = $records;
		
		$fp = fopen($this->CI->config->item('root_path').'data/booking_'.md5($this->CI->config->item('club_name')).'.txt', 'w');
		log_message('debug','Abrindo el fichero '.$this->CI->config->item('root_path').'data/booking_'.md5($this->CI->config->item('club_name')).'.txt');
		fwrite($fp, utf8_decode($texto));
		fclose($fp);
		unset($texto);
		#jugadores
		$exportacion_j = $this->export_players_data(array('formato' => 'array', 'opcion' => $opciones));
		//echo "<pre>"; print_r($exportacion_j);exit();
		$texto = $this->CI->app_common->to_csv($exportacion_j);
		unset($exportacion_j);
		//$data->rows = $records;
		
		$fp = fopen($this->CI->config->item('root_path').'data/booking_players_'.md5($this->CI->config->item('club_name')).'.txt', 'w');
		fwrite($fp, utf8_decode($texto));
		unset($texto);
		fclose($fp);

		//echo $texto."<pre>"; print_r($exportacion);
		//exit();
		//echo json_encode ($data );
		//exit( 0 );
	}



	public function getExtra($id_transaction = NULL, $id_booking = NULL, $time = NULL, $booking_time = NULL)
	{


		//if(!isset($id_transaction) || $id_transaction == '' || !isset($id_booking) || $id_booking == '') return NULL;

		$resultado = 0;
		$calculo_extra_riogrande = $this->CI->config->item('booking_extra_riogrande');
		if($calculo_extra_riogrande) $resultado += $this->getExtraRiogrande($id_transaction, $id_booking, $time, $booking_time);


		return $resultado;
		
	}


	public function getExtraRiogrande($id_transaction, $id_booking = NULL, $time = NULL, $booking_time = NULL)
	{
		# En $booking_time defino la hora en la que se realizó la reserva originalmente (en caso de cambios de reserva, etc, etc..) para que lo tenga en cuenta de cada a los pluses por reserva adelantada
		
		$debug = FALSE;
		$resultado = 0;

		$this->CI->load->model('reservas_model', 'reservas2', TRUE);
		if(!isset($id_transaction) || $id_transaction == '') return $resultado;
		
		$info = $this->CI->reservas2->getBookingInfoById($id_transaction);
		//if(!isset($info) || !is_array($info) || count($info) == 0) return $resultado;
		//if($debug) { echo '<pre>'; print_r($info); }
		//log_message('debug', 'Reserva usada para el getExtraRiogrande: '.var_export($info, TRUE));
		if(!isset($time) || $time == '') {
			if($debug) log_message('debug',  'No esta time');
			if($debug) log_message('debug',  "\r\n".$id_booking.'<br>');
			$intervalo_tmp = explode('-', $id_booking);
			$intervalo = $intervalo_tmp[1];
			$int_comp = date($this->CI->config->item('reserve_hour_filter_format'), $intervalo);
		} else $int_comp = $time;
		
		$i = 0; $primero = FALSE;
		if(isset($info['reserva']) && count($info['reserva']) > 0) {
			foreach($info['reserva'] as $reserva) {
				foreach($reserva as $segmento) {
					if($debug) log_message('debug',  $segmento[0].' - '.$int_comp.'<br>');
					if($i==0 && $segmento[0] == $int_comp) $primero = TRUE;
					$i++;
				}
			}
		} else $primero = TRUE;
		
		$dias = 0;
		if($primero) {
			if(isset($booking_time) && $booking_time != '') $fecha_reserva = strtotime($booking_time);
			elseif(!isset($info) || !is_array($info) || count($info) == 0 || !isset($info['create_time']) || $info['create_time'] == '') $fecha_reserva = strtotime(date($this->CI->config->item('date_db_format')));
			else $fecha_reserva = strtotime(date($this->CI->config->item('date_db_format'), strtotime($info['create_time'])));
			if($debug) log_message('debug', "\r\nFecha reserva: ".$fecha_reserva);
			if(!isset($info) || !is_array($info) || count($info) == 0) $fecha_uso = $intervalo;
			else $fecha_uso = strtotime($info['date'].' '.$int_comp.':00');
			if($debug) log_message('debug', "\r\nFecha uso: ".$fecha_uso);
			$dias = floor( abs( (($fecha_uso - $fecha_reserva) ) / (60 * 60 * 24)));
			if($dias == 0) $dias = 1; // Si estamos en el día actual, le sumo uno para que cobre siempre el mínimo de un día
			if($debug) log_message('debug',  "\r\nDias: ".$dias);
			
			$resultado =  $dias * $this->CI->config->item('booking_extra_riogrande_quantity');
		}
		if($debug) if($primero) log_message('debug', 'Era el primero!!');
		if($debug) log_message('debug',  $intervalo.' - '.date($this->CI->config->item('reserve_hour_filter_format'), $intervalo));
		//if($debug) { echo '<pre>'; print_r($info); }
		log_message('debug', 'Reserva '.$id_transaction.' (su intervalo de '.$int_comp.' - venía por parametro '.$time.'-) creada en '.$info['create_time'].' para jugar el '.$info['date'].' supone una diferencia de dias de '.$dias.' y un sobrecoste por prerreserva de '.$resultado);
		if($primero) log_message('debug', 'Segmento marcado como el primero');
		else log_message('debug', 'Segmento NO marcado como el primero');
		return $resultado;
		
	}




	/*
	|
	|	Comprobación (si corresponde) del número máximo de reservas diarias por usuario
	|
	*/
	public function checkMaximumBookingPerDayUserSport($date, $pista, $usuario, $force=0)
	{

		$this->CI->load->model('Reservas_model', 'reservas', TRUE);
		$this->CI->load->model('Pistas_model', 'pistas', TRUE);
		$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);

		$debug = FALSE;
		
		# Comprobación (si corresponde) del número máximo de reservas diarias por usuario
		$comprobar_reservas_maximas = $this->CI->config->item('reserve_maximum_intervals_check');
		$valida_por_maxima = TRUE;
		if(isset($comprobar_reservas_maximas) && $comprobar_reservas_maximas) {
		
			$deporte = $this->CI->pistas->getCourtSport($pista);
			$nivel_usuario = $this->CI->usuario->getUserGroup($usuario);
			if(!isset($deporte) || $deporte == '' || !isset($nivel_usuario) || $nivel_usuario == '') return FALSE;
		
			if($debug) print 'Comprobamos el numero máximo de reservas disponibles'."\r\n";
			$reservas_maximas = $this->CI->config->item('reserve_maximum_intervals');
			if($debug) print_r ($reservas_maximas);
			if(isset($reservas_maximas) || count($reservas_maximas) > 0)  {
				$maximas_exactas = $reservas_maximas[$deporte][$nivel_usuario];
				$reservas_hechas = $this->CI->reservas->getBookingCountDayUser($deporte, $date, $usuario, $force);
				if($debug) print 'Tenemos un total de '.$reservas_hechas.' para el usuario '.$usuario.' y se dispone de un maximo para el nivel '.$nivel_usuario.' en el deporte '.$deporte.' (pista '.$pista.') de '.$maximas_exactas."\r\n";
				if(isset($maximas_exactas) && $maximas_exactas!='') {
					if($maximas_exactas <= $reservas_hechas) $valida_por_maxima = FALSE;
				} 
				
				
			}
		}

		return $valida_por_maxima;
		
	}




	/**
	 * Cancelar reserva
	 *
	 * @return boolean
	 * @author 
	 **/
	public function notify_booking($reserva, $opciones = NULL)
	{
			$this->CI->load->model('Redux_auth_model', 'usuario', TRUE);
			$this->CI->load->model('reservas_model', 'reservas', TRUE);
			

			$grupo = $this->CI->usuario->getUserGroup($reserva['user']);
			//echo 'grupo: '.$grupo.'<br>';
			
				

			#Envio de mail de confirmacion de modificacion de reserva
			if($this->CI->config->item('reserve_send_mail')) {
				//log_message('debug', '1');
				$email='';
				//echo '<pre>';
				//print_r($reserva);
				//echo '</pre>';
				$info=$reserva;
				//log_message('debug', 'usuario: '.$info['user']);
				if(isset($info['user']) && $info['user']!='0') $email=$this->CI->usuario->getUserMail($info['user']);
				//echo "<br>AA".$email;
				//exit();
				if($email!="") {
					//log_message('debug', '2');
					if(isset($opciones['action']) && $opciones['action']=='cancel') {
						#Cancelacion de reservas
						$message = $this->CI->load->view('reservas/mail_notification_cancel_reserve', array('info' => $info), true);
						$subject = $this->CI->config->item('club_name').' - '.$this->CI->lang->line('mail_booking_cancel').' '.$info['booking_code'];
					} elseif (isset($opciones['action']) && $opciones['action']=='change' && isset($opciones['old_booking'])) {
						#Modificacion de reservas
						$message = $this->CI->load->view('reservas/mail_notification_change_reserve', array('info' => $info, 'info_antigua' => $opciones['old_booking']), true);
						$subject = $this->CI->config->item('club_name').' - '.$this->CI->lang->line('mail_booking_change').' '.$info['booking_code'];
						log_message('debug', '2');
					} else {
						$message = $this->CI->load->view('reservas/mail_notification', array('info' => $info), true);
						$subject = $this->CI->config->item('club_name').' - '.$this->CI->lang->line('mail_booking_confirmation').' '.$info['booking_code'];
					}
					
					//print($message);echo "<hr>";
					//exit();
					if(trim($subject)!='' && trim($message)!='') {
						$this->CI->email->clear();
						$this->CI->email->set_newline("\r\n");
						$this->CI->email->from($this->CI->config->item('email_from'), $this->CI->config->item('email_from_desc')); // Direccion de origen y nombre
						$this->CI->email->reply_to($this->CI->config->item('email_replyto'), $this->CI->config->item('club_name'));
						$this->CI->email->to($email);
						if($this->CI->config->item('reserve_admin_notification_cc')) $this->CI->email->bcc($this->CI->config->item('reserve_admin_notification_mail'));
						//$this->email->to('juanjo.nieto@gmail.com');
						$this->CI->email->subject($subject);
						$this->CI->email->message($message);
						$this->CI->email->send();
						log_message('debug', 'traza: '.$this->CI->email->print_debugger());
					}

					//echo $this->email->print_debugger();
				} //else log_message('debug', 'Usuario sin email');
				
				if(isset($info['playing_users']) && count($info['playing_users']) > 0 ) {
					log_message('debug', 'Mensajes para jugadores');
					//$message2 = $this->CI->load->view('reservas/mail_notification_for_player', array('info' => $info), true);
					
					
					# Texto de los Mensajes a enviar
					if(isset($opciones['action']) && $opciones['action']=='cancel') {
						#Cancelacion de reservas
						$message2 = $this->CI->load->view('reservas/mail_notification_cancel_reserve_for_player', array('info' => $info), true);
						$subject2 = $this->CI->config->item('club_name').' - '.$this->CI->lang->line('mail_booking_cancel').' '.$info['booking_code'];
					} elseif (isset($opciones['action']) && $opciones['action']=='change' && isset($opciones['old_booking'])) {
						#Modificacion de reservas
						$message2 = $this->CI->load->view('reservas/mail_notification_change_reserve_for_player', array('info' => $info), true);
						$subject2 = $this->CI->config->item('club_name').' - '.$this->CI->lang->line('mail_booking_change').' '.$info['booking_code'];
						log_message('debug', '2');
					} else {
						$message2 = $this->CI->load->view('reservas/mail_notification_for_player', array('info' => $info), true);
						$subject2 = $this->CI->config->item('club_name').' - '.$this->CI->lang->line('mail_booking_player').' '.$info['booking_code'];
					}
					
					//$message2 = $this->CI->load->view('reservas/mail_notification_for_player', array('info' => $info), true);
					foreach($info['playing_users'] as $jugador) {
						if(isset($jugador['id_user']) && $jugador['id_user'] != 0 && $jugador['id_user'] != $info['user']) {
							# Si el jugador está definido, existe, está registrado y no es el usuario que reservó, mando email
							
							$email2 = $this->CI->usuario->getUserMail($jugador['id_user']);
							//print($message);echo "<hr>";
							//echo '<br>'.$email2;
							//exit();
							log_message('debug', $email2);
							if($email2 != '' && trim($subject2)!='' && trim($message2)!='') {
								$this->CI->email->clear();
								$this->CI->email->set_newline("\r\n");
								$this->CI->email->from($this->CI->config->item('email_from'), $this->CI->config->item('email_from_desc')); // Direccion de origen y nombre
								$this->CI->email->reply_to($this->CI->config->item('email_replyto'), $this->CI->config->item('club_name'));
								$this->CI->email->to($email2);
								if($this->CI->config->item('reserve_admin_notification_cc')) $this->CI->email->bcc($this->CI->config->item('reserve_admin_notification_mail'));
								//$this->email->to('juanjo.nieto@gmail.com');
								$this->CI->email->subject($subject2);
								$this->CI->email->message($message2);
								$this->CI->email->send();					
							}		
						}
					}
				}
				
			}	//else log_message('debug', 'No enviar notificacion');					
				
				return TRUE;




	}





}