<?php

class Comms extends Controller {

	function Comms()
	{
		parent::Controller();	
	}
	
	function index()
	{
			
			echo "Testigo ".date('YmdHis');
			
	}


	
	/*
	
		función que realiza el login y devuelve texto con el resultado	
	
	*/
	
	function login()
	{
			
			$this->load->model('Redux_auth_model', 'usuario', TRUE);
			
      $email    = '';
      $code    = $this->input->post('code');
      $password = $this->input->post('passw');

    	if($this->redux_auth->login($email, $password, $code)) {
				echo 'OK';
    		exit();
    	}
    	else {
  			log_message('debug','Login erroneo desde movil del usuario ('.$code.')');
				echo 'NO';
    		exit();
    	}			
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
			$limit_datetime = date('Y-m-d H:i:s', strtotime(' -10minutes'));
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
			$pagadores = $this->users_lib->get_quotable_users();
			$pagados = array();
			$no_pagados = array();
			//print("<pre>");print_r($pagadores);print_r($invalidos);exit();
			foreach($pagadores as $pagador) {
				if($this->users_lib->pay_user_quota($pagador['id'], array('code_price' => $pagador['code_price'], 'name' => trim($pagador['first_name'].' '.$pagador['last_name']), 'group_id' => $pagador['group']))) {
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
		$where= "lessons.active = 1 and lessons_assistants.status IN (1,2,3)  AND lessons.end_date >= '".date($this->config->item('date_db_format'))."'";
		$pagadores = $this->lessons->get_AssitantsData(array('where' => $where),"all");
		
		//print("<pre>");print_r($pagadores);
		foreach($pagadores as $pagador) {
			$this->calendario->pay_user_quota($pagador['id'], array('object' => $pagador));
		}

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
		
		//set_time_limit(300);
		ini_set('memory_limit', '256M');
		$this->load->library('booking');
		$this->load->library('users_lib');
		$this->load->library('payment');
		
		
		$this->booking->exportacion();
		$this->users_lib->exportacion();
		$this->payment->exportacion();


		
		
	}
}

/* End of file comms.php */
/* Location: ./system/application/controllers/comms.php */