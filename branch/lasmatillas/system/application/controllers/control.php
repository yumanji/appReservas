<?php

class Control extends CI_Controller {

	function Control()
	{
			
	}
	
	function index()
	{
			
			echo "Testigo ".date('YmdHis');
			
	}

	
	function scheduled_eraser()
	{
			$this->load->model('reservas_model', 'reservas', TRUE);
			$this->load->model('Redux_auth_model', 'usuario', TRUE);
			$this->load->library('booking');
			//echo "Testigo ".strtotime(' -3minutes').'  -  '.date('Y-m-d H:i:s', strtotime(' -3minutes'));
			$booking_table = 'booking';
			$payments_table = 'payments';
			$payments_extra_table = 'payments_tpv_extra';
			$limit_datetime = date('Y-m-d H:i:s', strtotime(' -15minutes'));
			//$limit_datetime = date('Y-m-d H:i:s');
			$booking_status_to_erase = '5';
			$booking_status_to_cancel = '7';
			$payment_status_to_erase = '5';
			$payment_status_to_cancel = '7';
			$paymentway_to_erase = '6';
			
			# Borrado de los datos extra de los pagos por TPV a medio hacer
			$this->db->where("id_payment IN (SELECT id FROM ".$payments_table." WHERE datetime <='".$limit_datetime."' and status = '".$payment_status_to_erase."' and id_paymentway = '".$paymentway_to_erase."')");
			//$this->db->where('id_paymentway', $paymentway_to_erase);
			$this->db->delete($payments_extra_table);
			log_message('debug','Pagos inutilizados, borrados: '.$this->db->last_query());
			
			# Cancelación de las reservas asociadas a los pagos que vamos a cancelar
			$this->db->distinct();
			$this->db->select('id_transaction')->from($booking_table);
			$this->db->where("id_transaction IN (SELECT id_transaction FROM ".$payments_table." WHERE datetime <='".$limit_datetime."' and status = '".$payment_status_to_erase."' and id_paymentway = '".$paymentway_to_erase."')");
			$query = $this->db->get();
			foreach ($query->result() as $row)
			{
				//echo '<br>'.$row->id_transaction;
				$info=$this->reservas->getBookingInfoById($row->id_transaction);
				$text_cancel = 'Pago no finalizado';
				//print("<pre>");print_r($info);exit();
	
				$result = $this->booking->cancel_reserve($info, $text_cancel, array('mail' => TRUE, 'refund' => FALSE));


			}			
			//exit($this->db->last_query());
			
			# Cancelacion de los pagos que llevan demasiado tiempo pendientes (presumibles pagos por TPV que no se han cerrado)
			$this->db->where('datetime <=', $limit_datetime);
			$this->db->where('status', $payment_status_to_erase);
			$data = array(
         'status' => $payment_status_to_cancel,
         'modify_user' => '0',
         'modify_time' => date($this->config->item('log_date_format')),
         'modify_ip' => $this->session->userdata('ip_address')
      );
			$this->db->update($payments_table, $data); 			
			log_message('debug',$this->db->last_query());
			
			$this->db->where('create_time <=', $limit_datetime);
			$this->db->where('status', $booking_status_to_erase);
			$this->db->delete($booking_table); 			
			log_message('debug','Reservas no confirmadas, borradas: '.$this->db->last_query());
			
			/*
    	$sql_insert = "insert into booking_cancelled (id_booking, id_transaction, id_user, booking_code, session, id_court, date, intervalo, status, cancelation_reason, id_paymentway, price, no_cost, no_cost_desc, user_nif, user_desc, user_phone, create_user, create_time, create_ip) (select id_booking, id_transaction, id_user, booking_code, session, id_court, date, intervalo, status, 'Reserva cancelada automaticamente por impago', id_paymentway, price, no_cost, no_cost_desc, user_nif, user_desc, user_phone, '0', '".date($this->config->item('log_date_format'))."', '".$this->session->userdata('ip_address')."' from ".$booking_table." where modify_time = '".$limit_datetime."' AND status = '".$booking_status_to_cancel."')";
    	$this->db->query($sql_insert);
			log_message('debug','Reservas impagadas, borradas: '.$this->db->last_query());
    	
			$this->db->where('create_time <=', $limit_datetime);
			$this->db->where('status', $booking_status_to_cancel);
			$this->db->delete($booking_table); 			
			log_message('debug',$this->db->last_query());
    	*/
			
			/*
			$this->db->delete('booking',array('create_time <=', $limit_datetime, 'status', $booking_status_to_cancel));
			log_message('debug',$this->db->last_query());
			*/
			exit();

	}

		
	function scheduled_payments()
	{
			
			# Nuevos pagos pendientes para remesas bancarias para los cursos
			//echo "Testigo ".strtotime(' -3minutes').'  -  '.date('Y-m-d H:i:s', strtotime(' -3minutes'));
			$payments_table = 'payments';
			$limit_datetime = date('Y-m-d H:i:s', strtotime(' -5minutes'));
			$payment_status = '2';
			$paymentway = '4';
			
			$this->db->where("id_payment IN (SELECT id FROM ".$payments_table." WHERE datetime <='".$limit_datetime."' and status = '5' and id_paymentway = '".$paymentway_to_erase."')");
			//$this->db->where('id_paymentway', $paymentway_to_erase);
			$this->db->delete($payments_extra_table);
			log_message('debug','Pagos inutilizados, borrados: '.$this->db->last_query());
			

			exit();

	}

		
	function night_process()
	{
			
			exit();

	}

	


#####################
#
# Función que genera los pagos relativos a las cuotas de usuarios
#
####################
		
	function users_quotas()
	{
			$this->load->library('users_lib');
			$pagadores = $this->users_lib->get_quotable_users('ALL');
			$pagados = array();
			$no_pagados = array();
			//print("<pre>");print_r($pagadores);print_r($invalidos);exit();
			foreach($pagadores as $pagador) {
				if(isset($pagador['code_price']) && $pagador['code_price']!= '0' && $this->users_lib->pay_user_quota($pagador['id'], array('code_price' => $pagador['code_price'], 'name' => trim($pagador['first_name'].' '.$pagador['last_name']), 'group_id' => $pagador['group']))) {
					array_push($pagados, $pagador);
				} else {
					array_push($no_pagados, $pagador);
				}
			}
			if(count($pagados) > 0) {
				$this->load->model('Notifications_model', 'mails', TRUE);
				$message = $this->load->view('users/new_quotas_notification', array('usuarios' => $pagados), true);
				$registro = array(
		       'subject' => 'Creados nuevos pagos de cuotas de usuario',
		       'from' => $this->config->item('email_from'),
		       'destination_id' => 0,
		       'destination_text' => $this->config->item('users_qouta_admin_notification'),
		       'content' => $message,
		       'active' => 1,
		       'create_user' => $this->session->userdata('user_id'),
		       'create_ip' => $this->session->userdata('ip_address'),
		       'create_time' => date(DATETIME_DB)
		    );
		
				$this->mails->createNotificationMessage($registro);
			}
			
			$invalidos = $this->users_lib->get_quotable_users(FALSE);
			#Añado al array de inválidos aquellos que se nos pasaron como válidos pero que luego no han podido ser cobrados por la función
			foreach($no_pagados as $fallo) {
				array_push($invalidos, array('id' => $fallo['id'], 'nombre' => trim($fallo['first_name'].' '.$fallo['last_name'])));
			}
			
			if(count($invalidos) > 0) {
				$this->load->model('Notifications_model', 'mails', TRUE);
				$message = $this->load->view('users/bad_quotas_notification', array('usuarios' => $invalidos), true);
				$registro = array(
		       'subject' => 'Errores en cobro de cuotas de usuario',
		       'from' => $this->config->item('email_from'),
		       'destination_id' => 0,
		       'destination_text' => $this->config->item('users_qouta_admin_notification'),
		       'content' => $message,
		       'active' => 1,
		       'create_user' => $this->session->userdata('user_id'),
		       'create_ip' => $this->session->userdata('ip_address'),
		       'create_time' => date(DATETIME_DB)
		    );
		
				$this->mails->createNotificationMessage($registro);
			}			
			//print("<pre>");print_r($pagadores);print_r($invalidos);exit();
			//echo 'aa';
			//exit();

	}



	


#####################
#
# Función que genera los pagos relativos a las inscripciones de los usuarios en clases
#
####################
		
	function lessons_quotas()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');
		$this->load->model('Lessons_model', 'lessons', TRUE);

		//echo '----'.$this->config->item('log_date_format').'---'.date($this->config->item('log_date_format'));
		#Recupero lista de usuarios
		//$where= "lessons.active = 1 and lessons_assistants.status IN (1,2,3) and lessons.start_date <= '".date($this->config->item('date_db_format'))."' AND lessons.end_date >= '".date($this->config->item('date_db_format'))."'";
		$where= "lessons.active = 1 and lessons_assistants.status IN (1,2,3)  AND lessons.end_date >= '".date($this->config->item('date_db_format'))."' ";
		$pagadores = $this->lessons->get_AssitantsData(array('where' => $where, 'order_by' => ' lessons.id asc, users.group_id'),"all");
		
		//print("<pre>");print_r($pagadores);exit();
		foreach($pagadores as $pagador) {
			$this->calendario->pay_user_quota($pagador['id'], array('object' => $pagador));
		}

	}



	


#####################
#
# Función que genera los pagos relativos a las inscripciones de los usuarios en clases
#
####################
		
	function lessons_quotas_torrijos($curso_id = NULL)
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');
		$this->load->library('calendario');
		$this->load->model('Lessons_model', 'lessons', TRUE);
		$this->load->library('users_lib');
		$debug = FALSE;
				# Genero el codigo de barras
		require($this->config->item('root_path').'system/libraries/barcode/BCGFontFile.php');
		require($this->config->item('root_path').'system/libraries/barcode/BCGColor.php');
		require($this->config->item('root_path').'system/libraries/barcode/BCGDrawing.php');
		require($this->config->item('root_path').'system/libraries/barcode/BCGcode128.barcode.php');

		
		set_time_limit (900);
		ini_set("memory_limit","900M");
		//echo '----'.$this->config->item('log_date_format').'---'.date($this->config->item('log_date_format'));
		#Recupero lista de usuarios
		$add_where = "";
		if(isset($curso_id)) $add_where = " and lessons.id = '".$curso_id."'  ";
		//if(isset($curso_id)) $add_where = " and lessons.id >= '".$curso_id."' and lessons.id <= '".(intval($curso_id)+2)."'  ";
		//$where= "lessons.active = 1 and lessons_assistants.status IN (1,2,3) and lessons.start_date <= '".date($this->config->item('date_db_format'))."' AND lessons.end_date >= '".date($this->config->item('date_db_format'))."'";
		$where= "lessons.active = 1 and lessons_assistants.status IN (1,2,3)  AND lessons.end_date >= '".date($this->config->item('date_db_format'))."'  ".$add_where; //and users.group_id < 7";
		$pagadores = $this->lessons->get_AssitantsData(array('where' => $where, 'order_by' => ' lessons.id asc, meta.last_name'),"all");

		require($this->config->item('root_path').'system/libraries/fpdf/fpdf.php');

		$pdf = new FPDF();
		$i=0;print("<pre>");print_r($pagadores); 
		if($debug) print("<pre>");
		if($debug) print_r($pagadores); 
		//if($debug) exit();
		$id_curso=0; $desc_curso = '';
		foreach($pagadores as $pagador) {
			$chk_couta = FALSE;
			if($id_curso==0 || $id_curso != intval($pagador['id_lesson'])) {
				if($id_curso!=0) $pdf->Output($this->config->item('root_path').'data/recibos/'.$desc_curso.'.pdf', 'F');
				if($debug) echo '<br>Grabo el fichero '.$desc_curso.'.pdf';
				unset($pdf);
				$pdf = new FPDF();
				$desc_curso = urlencode($pagador['description']).' ('.$pagador['id_lesson'].')';
				$id_curso = $pagador['id_lesson'];
			}
			$i++;
			if($debug) echo '<br>Alumno '.$pagador['user_desc'].' del curso '.$pagador['description'];
				# Si el tio no era socio, subimos de nivel
				if($pagador['group_id']>=7) {
					$chk_couta = TRUE;
					if($pagador['group_id']==7) $cuota_socio = 6;
					else $cuota_socio = 10;
					$this->db->query("update users set group_id = group_id-2 WHERE id = '".$pagador['id_user']."'");
					$this->users_lib->pay_user_quota($pagador['id_user'], array('payable_quota'=> $cuota_socio));
					//$assistant=get_object_vars ($this->calendario->getAssistantInfo($pagador['id']));
					if($debug) echo 'Pago su cuota de usuario';
				}
							
				if(isset($pagador) && is_array($pagador)) $assistant=$pagador;
				else $assistant=get_object_vars ($this->calendario->getAssistantInfo($pagador['id']));
				$tmp__ = 		$pagadores = $this->lessons->get_AssitantsData(array('where' => $where." and lessons_assistants.id_user = ".$pagador['id_user'], 'order_by' => ' lessons.id asc, meta.last_name'),"all");

				$assistant=$tmp__[0];
				//print('asistente<pre>');print_r($assistant);exit();	
				$assistant_info = $this->users->get_user($assistant['id_user']);
				//print('usuario<pre>');print_r($assistant_info);exit();
				$info = $this->calendario->getCalendarByRange($assistant['id_lesson']);
				//print('curso<pre>');print_r($info);exit();
				$this->load->model('Payment_model', 'pagos', TRUE);
		//exit();

				if(!isset($quantity) || $quantity == '') $quantity = 1;	// Mensualidades por defecto a pagar
				
				# Si se ha marcado como dado de alta el usuario, seguimos..
				if($assistant['signed'] == '1') {
					if($debug) echo '<br>Está apuntado al curso ';
					if(!isset($assistant['last_day_payed']) || $assistant['last_day_payed']=="") {
						$ultima_fecha = $assistant['sign_date'];
						$dia = date('d', strtotime($ultima_fecha));
						$dia_de_pago = '01';
						if($dia < $dia_de_pago) {
							$trozos = split('-', $ultima_fecha);
							$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
						} elseif($dia > $dia_de_pago) {
							$fecha_siguiente = date($this->config->item('log_date_format'), strtotime($assistant['sign_date'].' +'.$info->price_duration.' '.$this->pagos->getFrequencyCommand($info->frequency)));
							$trozos = split('-', $fecha_siguiente);
							$last_payd_date = $trozos[0].'-'.$trozos[1].'-'.$dia_de_pago;
							if(!is_array($trozos)) { print('asistente<pre>');print_r($assistant); $last_payd_date = date('Y').'-'.date('m').'-'.$dia_de_pago;}
						} else {
							$last_payd_date = date($this->config->item('log_date_format'), strtotime($assistant['sign_date'].' +'.$info->price_duration.' '.$this->pagos->getFrequencyCommand($info->frequency)));
						}
						
						//echo "1<br>";
					} else {
						$last_payd_date = date($this->config->item('log_date_format'), strtotime($assistant['last_day_payed'].' +'.$info->price_duration.' '.$this->pagos->getFrequencyCommand($info->frequency)));
						//echo "2<br>";
					}		
					if($debug) echo '<br>... las_payd_date: '.$last_payd_date; //exit();
					
					$paymentway = 4;	//Forma pago temporal.. por banco

					if(isset($quantity) && $quantity!=0 && $quantity!="" && isset($paymentway) && $paymentway!=0 && $paymentway!="") {
							/*
							$cuota = $assistant['quota'];
							$pay_amount_tmp = $cuota * $quantity;
							if($assistant->discount_type == '%') $pay_amount = $pay_amount_tmp - ($pay_amount_tmp * $assistant->discount / 100);
							else $pay_amount = $pay_amount_tmp - $assistant->discount;
							*/
							


							
							
							
							
							$pay_amount = $assistant['quota'];
							if($debug) echo '<br>La cuota es '.$pay_amount;
							//exit('cuota:'.$pay_amount);
							if($this->lessons->setMonthlyPayment($pagador['id'], $last_payd_date)) { if($debug) echo '<br>Pagado: Pago hasta el '.date($this->config->item('reserve_date_filter_format'), strtotime($last_payd_date)).' realizado';}
							else {
								if($debug) echo '<br>Error al definir la cuota como pagada';
							}
			
							if($assistant['id_user'] != '' && $assistant['id_user'] != 0) $user_desc = $assistant['first_name'].' '.$assistant['last_name'];
							else $user_desc = $assistant['user_desc'];
						
							$estado = 9;
							if($paymentway == 4) $estado = 2;
							if($pay_amount == 0) $estado = 9;	
							
							$this->pagos->id_type=2; //Clases y cursos
							$this->pagos->id_element=$this->session->userdata('session_id');
							$this->pagos->id_transaction='l-'.$assistant['id_lesson'].'-'.$assistant['id_user'].'-'.date('U');	// Formato 'l' de lesson, codigo de curso, codigo de usuario y fecha del momento del pago
							$this->pagos->id_user=$assistant['id_user'];
							$this->pagos->desc_user=$user_desc;
							$this->pagos->id_paymentway = $paymentway;
							$this->pagos->status=$estado;
							$this->pagos->quantity = $pay_amount;
							$this->pagos->datetime=date($this->config->item('log_date_format'));
							$this->pagos->description="Cuota mensual del curso '".$assistant['description']."', hasta el ".$last_payd_date;
							$this->pagos->create_user=$this->session->userdata('user_id');
							$this->pagos->create_time=date($this->config->item('log_date_format'));
							
							$this->pagos->setPayment();
							if($debug) echo '<br>Pago realizado';
						
							$pago = $this->pagos->getPaymentByTransaction($this->pagos->id_transaction);
							//print_r($pago); exit();
						


							##########################################3
							##########################################
							## PINTAR
							###############################
							if($debug) echo '<br>Comienzo a pintar la hoja..';
							$quota = number_format($pay_amount, 2);
							//print_r($array_result);exit();
							$imgPath = $this->config->item('root_path').'images/templates/plantilla.jpg';
							//$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
							$font = $this->config->item('root_path').'system/fonts/FreeSansBold.ttf';
							$doc_title = 'Cuota de curso';
							//print_r($array_result);
							//$imgPath = $this->config->item('root_path').'images/templates/'.$carnet_permission[$array_result['group_id']];
							//$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
							if(!file_exists($imgPath) || !file_exists($font)) exit ('Fallo en la carga de las plantillas necesarias');
							
							# Abro plantilla de carnet
							$size=getimagesize($imgPath);
							switch($size["mime"]){
								case "image/jpeg":
									$image = imagecreatefromjpeg($imgPath); //jpeg file
								break;
								case "image/gif":
									$image = imagecreatefromgif($imgPath); //gif file
							  break;
							  case "image/png":
								  $image = imagecreatefrompng($imgPath); //png file
							  break;
							  default: 
								$image=false;
							  break;
							}
							if(!$image) exit ('Fallo en la carga de las plantillas necesarias');


							


							// Set the margins for the stamp and get the height/width of the stamp image
							$marge_right = 200;
							$marge_bottom = 1900;
							//imagecopy($image, $fotocarnet_thumb, imagesx($image) - $ancho_fotocarnet_thumb - $marge_right, imagesy($image) - $alto_fotocarnet_thumb - $marge_bottom, 0, 0, $ancho_fotocarnet_thumb, $alto_fotocarnet_thumb);

							
							# Escribo los datos del usuario
							$white = imagecolorallocate($image, 255, 255, 255);
							$grey = imagecolorallocate($image, 128, 128, 128);
							$black = imagecolorallocate($image, 0, 0, 0);
							$fontSize = 46;	// Tamaño de texto normal
							$text_xpos = 270;	// Margen izquierdo

							imagettftext($image, 72, 0, 1200, 300, $black, $font, $doc_title);	// Titulo
							
							//$array_result= array('user_lastname' => 'Nieto Castellano', 'user_name' => 'Juan José', 'cif' => '50107654S', 'birthdate' => '20/08/1977', 'address' => 'Calle de Constancia, 17, 3º C', 'population' => 'Torrijos de arribarrigota', 'cp' => '28058', 'phone' => '915092162', 'phone2' => '656424453', 'email' => 'juanjitojuanjitoo0000o.nieto@gmail.com');

							imagettftext($image, $fontSize, 0, $text_xpos, 665, $black, $font, $assistant_info['user_lastname'].', '.$assistant_info['user_name']);	// Nombre
							imagettftext($image, $fontSize, 0, $text_xpos+1600, 665, $black, $font, $assistant_info['nif']);	// DNI
							imagettftext($image, $fontSize, 0, $text_xpos, 850, $black, $font, date($this->config->item('reserve_date_filter_format'),strtotime($assistant_info['birth_date'])));	// fecha nacimiento
							imagettftext($image, $fontSize-5, 0, $text_xpos+400, 850, $black, $font, $assistant_info['address']);	// direccion
							if(strlen($assistant_info['population'])<= 15 ) imagettftext($image, $fontSize, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);	// telefono movil
							elseif(strlen($assistant_info['population'])<= 20 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);
							elseif(strlen($assistant_info['population'])<= 23 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);
							else imagettftext($image, $fontSize-20, 0, $text_xpos+1600, 850, $black, $font, $assistant_info['population']);
							imagettftext($image, $fontSize, 0, $text_xpos, 1030, $black, $font, $assistant_info['cp']);	// codigo postal
							//imagettftext($image, $fontSize, 0, $text_xpos+450, 1030, $black, $font, $assistant_info['user_phone']);	// telefono fijo
							imagettftext($image, $fontSize, 0, $text_xpos+400, 1030, $black, $font, $assistant_info['user_phone']);	// telefono movil
							imagettftext($image, $fontSize, 0, $text_xpos+900, 1030, $black, $font, $assistant_info['user_id']);	// telefono movil
							if(strlen($assistant_info['user_email'])<= 22 ) imagettftext($image, $fontSize, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);	// telefono movil
							elseif(strlen($assistant_info['user_email'])<= 28 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);
							elseif(strlen($assistant_info['user_email'])<= 33 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);
							else imagettftext($image, $fontSize-20, 0, $text_xpos+1175, 1030, $black, $font, $assistant_info['user_email']);

							if($chk_couta) {
								imagettftext($image, $fontSize-5, 0, $text_xpos, 1300, $black, $font, 'Cuota de socio es de '.$cuota_socio.' euros.');	// Nombre
								imagettftext($image, $fontSize-5, 0, $text_xpos, 1400, $black, $font, 'Cuota para el curso "'.$info->description.'" es de '.$quota.' euros. ');	// Nombre
								imagettftext($image, $fontSize-5, 0, $text_xpos, 1500, $black, $font, 'El total a abonar es de '.intval($cuota_socio + $quota).' euros');	// Nombre
							
							} else {
								imagettftext($image, $fontSize-5, 0, $text_xpos, 1300, $black, $font, 'Cuota para el curso "'.$info->description.'" es de '.$quota.' euros. ');	// Nombre
							}
							imagettftext($image, $fontSize-5, 0, $text_xpos, 1800, $black, $font, 'El ingreso deberá realizarse en alguno de los siguientes números de cuenta:');	// Nombre
							imagettftext($image, $fontSize, 0, $text_xpos+50, 1900, $black, $font, '2105 0039 34 1290022090 (Caja Castilla-La Mancha)');	// Nombre
							imagettftext($image, $fontSize, 0, $text_xpos+50, 2000, $black, $font, '3081 0181 03 2563768528 (Caja Rural)');	// Nombre
							imagettftext($image, $fontSize-5, 0, $text_xpos, 2100, $black, $font, 'En el concepto de pago deberá poner \''.$pago->ticket_number.'\'');	// Nombre
							imagettftext($image, $fontSize-10, 0, $text_xpos, 2250, $black, $font, 'Deberá acompañarse la presente solicitud con el justificante del ingreso');	// Nombre

							





		 
		$font = $this->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		$font = new BCGFontFile($this->config->item('root_path').'system/fonts/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		 
		// Barcode Part
		$code = new BCGcode128();
		$code->setScale(5);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setStart(NULL);
		$code->setTilde(true);
		$code->setOffsetX(1);
		$code->setOffsetX(1);
		//$code->clearLabels();
		$code->parse($pago->ticket_number);
		$tamaño_barcode = $code->getDimension(0, 0);
		$ancho_barcode = $tamaño_barcode[0];
		$alto_barcode = $tamaño_barcode[1];
		//exit('aa');
		$barcode = imagecreatetruecolor($ancho_barcode, $alto_barcode);
		$background_color = imagecolorallocate($barcode, 255, 255, 255);
		imagefill($barcode, 0, 0, $background_color);
		// Drawing Part
		$drawing = new BCGDrawing('', $color_white);
		$drawing->set_im($barcode);
		$drawing->setBarcode($code);
		$drawing->draw($barcode);
		//$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);		
		$marge_right = 1200;
		$marge_bottom = 800;
		imagecopy($image, $barcode, imagesx($image) - $ancho_barcode - $marge_right, imagesy($image) - $alto_barcode - $marge_bottom, 0, 0, $ancho_barcode, $alto_barcode);

		
							//imagettftext($image, $fontSize, 0, $text_xpos, 140, $black, $font, 'ID: '.$assistant_info['user_id']);


							//$anchoo = imagesx($image);
							//$altoo = imagesy($image);
							//$proporcion = 1.4143;
							//$imagen_final = imagecreatetruecolor($anchoo, $altoo);
							//$image = imagerotate($image, 90, 0);
							//imagecopyresized ($imagen_final, $image, 0, 0, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);
							//imagecopyresized ($imagen_final, $image, 0, $altoo / 2, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);


							
							
							//header("Content-type: image/jpeg");
							//header("Content-type: image/jpeg");
							//header("Content-Length: " . $size);
							// NOTE: Possible header injection via $basename
							//header("Content-Disposition: attachment; filename=cuota_" . $code_user .'.jpg');
							//header('Content-Transfer-Encoding: binary');
							//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
							
							# Rota la imagen 90 grados
							//$image = imagerotate($image, 90, 0);
							$recibo_path  = $this->config->item('root_path').'data/recibos/'.$pago->ticket_number.'.jpg';
							imagejpeg($image,$recibo_path, 100);
							// Liberar memoria
							imagedestroy($image);

							$pdf->AddPage();
							$pdf->SetFont('Arial','B',16);
							//$pdf->Cell(40,10,'¡Hola, Mundo!');
							$pdf->Image($recibo_path, 0, 0, 210);
							//exit();
							
							//imagedestroy($imagen_final);










						
							
						} 

				
				} 




			//if($i==25) $pdf->Output();


		}
							//$pdf->Output($this->config->item('root_path').'data/recibos/recibos.pdf', 'F');
		$pdf->Output($this->config->item('root_path').'data/recibos/'.$desc_curso.'.pdf', 'F');


	}


	


#####################
#
# Función que genera los pagos relativos a las inscripciones de los usuarios en rankings
#
####################
		
	function ranking_quotas()
	{
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->library('rank_lib');
		$this->load->library('app_common');
		$this->load->model('Ranking_model', 'rank', TRUE);

		//echo '----'.$this->config->item('log_date_format').'---'.date($this->config->item('log_date_format'));
		
		# Recupero lista de rankings
		$rankings = $this->rank->get_data(array('where'=>"ranking.started = 1 AND ranking.active = 1"));
		//print("<pre>");print_r($rankings);
		
		
		# Lista a la que añado todos los pagos que habrá que realizar
		$pagadores = array();
		
		# Recorro los rankings activos
		foreach($rankings as $ranking) {
			# Recupero lista de equipos
			$equipos = $this->rank->get_ActiveTeams($ranking['id']);
			//print("<pre>");print_r($equipos);
			# Recorro los equipos
			foreach($equipos as $equipo) {
				# Recupero lista de jugadores
				$jugadores = $this->rank->get_teams_members(array('where'=>"ranking.started = 1 AND ranking_teams.status = 1 AND ranking_teams_members.status = 1 AND ranking_teams.id = ".$equipo['id']." "));
				# Recorro los equipos
				$i = 0;
				foreach($jugadores as $jugador) {
					$this->rank_lib->pay_user_quota($jugador['id']);
					
					//exit('Primer jugador!!');
					//$jugadores[$i]['grupo'] = $this->users->getUserGroup($jugador['id_user']);
					//$jugadores[$i]['cuota'] = $this->app_common->getPriceValue($ranking['price'], array('group' => $jugadores[$i]['grupo']));
					$i++;
					
				}
				
				//print("Equipo ".$equipo['id']."<pre>");print_r($jugadores);
				
			}
		}
		//exit();


	}

	


#####################
#
# Función que genera los pagos automaticos para remesas
#
####################
		
	function generate_quotas()
	{
		//echo 'a';
		# Generacion de cuotas de usuarios
		$this->users_quotas();
		
		//echo 'b';
		# Generacion de cuotas de clases
		$this->lessons_quotas();
		
		//echo 'c';
		# Generacion de cuotas de ranking
		$this->ranking_quotas();
		
		
	}



#####################
#
# Función que genera automaticamente los ficheros de exportacion de reservas, usuarios y facturacion
#
####################
		
	function export_data()
	{
		
		log_message('debug','Exportación de datos a fichero');
		//set_time_limit(300);
		ini_set('memory_limit', '1024M');
		$this->load->library('booking');
		$this->load->library('users_lib');
		$this->load->library('payments_lib');
		
		
		$this->booking->exportacion();
		$this->users_lib->exportacion();
		$this->payments_lib->exportacion();
		log_message('debug','Fin de exportación de datos a fichero');


		
		
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */