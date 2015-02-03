<?php
class Notifications_model extends Model {

    function Notifications_model() {
        // Call the Model constructor
        parent::Model();
    }
    
    function getNextMessages($quantity = 0) {
    	# Devuelve nombre de la pista
    	$this->CI =& get_instance();
			$this->CI->load->model('redux_auth_model', 'usuario', TRUE);
			//$this->CI->load->config('email');
			$this->CI->config->item('email_automated_send_quantity');
			
			if(!is_numeric($quantity) || $quantity < 1) $quantity = $this->CI->config->item('email_automated_send_quantity');
			//echo 'aa'.$this->CI->config->item('email_automated_send_quantity');
			$table = "notification_mails";
			
			$this->db->select("id, id_notification, subject, `from`, type, destination_type, destination_id, destination_text, content, active, start_process, end_process")->from($table);
			$this->db->where("active = 1 AND send = 0 ANd error_count <= 3");
			$this->db->order_by("id", "ASC");
			$this->db->limit($quantity);
			
			$query = $this->db->get()->result_array();
			for($i=0; $i<count($query); $i++) {
				if($query[$i]['destination_id']!='' && $query[$i]['destination_id']!='0') {
					$email = $this->CI->usuario->getUserMail($query[$i]['destination_id']);
					if($email!='') $query[$i]['destination_text'] = $email;
				}
			}
			//log_message('debug', 'SQL: '.$this->db->last_query());
			//echo $this->db->last_query();
			return $query;
					
    }


##    Función genérica para la creación de mails.
    function createMessage($data = NULL) {
    	# Devuelve nombre de la pista
			
			if(!isset($data) || !is_array($data)) return FALSE;
			
			
		
				if(!isset($data['active']) || $data['active']=="") $data['active'] = 0;
				if(!isset($data['send']) || $data['send']=="") $data['send'] = 0;
				if(!isset($data['status']) || $data['status']=="") $data['status'] = 0;
				if(!isset($data['status_desc']) || $data['status_desc']=="") $data['status_desc'] = '';
				if(!isset($data['start_process']) || $data['start_process']=="") $data['start_process'] = date(DATETIME_DB);
				
				$table = "notification";
				$registro = array(
           'subject' => $data['subject'],
           '`from`' => $data['from'],
           'type' => $data['type'],
           'destination_type' => $data['destination_type'],
           'destination_id' => $data['destination_id'],
           'content' => $data['content'],
           'active' => $data['active'],
           'start_process' => $data['start_process'],
           'status' => $data['status'],
           'status_desc' => $data['status_desc'],
           'create_user' => $this->session->userdata('user_id'),
           'create_ip' => $this->session->userdata('ip_address'),
           'create_time' => date(DATETIME_DB)
        );

				$this->db->insert($table, $registro);
				log_message('debug', 'SQL: '.$this->db->last_query());
				//echo $this->db->last_query();

				$data['id_notification'] = $this->db->insert_id();

				$table = "notification_mails";
				$registro = array(
           'id_notification' => $data['id_notification'],
           'subject' => $data['subject'],
           '`from`' => $data['from'],
           'type' => $data['type'],
           'destination_type' => $data['destination_type'],
           'destination_id' => $data['destination_id'],
           'destination_text' => $data['destination_text'],
           'content' => $data['content'],
           'active' => $data['active'],
           'start_process' => $data['start_process'],
           'send' => $data['send'],
           'create_user' => $this->session->userdata('user_id'),
           'create_ip' => $this->session->userdata('ip_address'),
           'create_time' => date(DATETIME_DB)
        );

				$this->db->insert($table, $registro);
				//log_message('debug', 'SQL: '.$this->db->last_query());
				//echo $this->db->last_query();

			
			return TRUE;
					
    }



##    Función genérica para la creación de mails con multiples destinatarios.
    function createMultiMessage($data = NULL, $destinations) {
    	# Devuelve nombre de la pista
			
			if(!isset($data) || !is_array($data)) return FALSE;
			if(!isset($destinations) || !is_array($destinations)) return FALSE;
			
			
		
				if(!isset($data['active']) || $data['active']=="") $data['active'] = 0;
				if(!isset($data['send']) || $data['send']=="") $data['send'] = 0;
				if(!isset($data['status']) || $data['status']=="") $data['status'] = 0;
				if(!isset($data['status_desc']) || $data['status_desc']=="") $data['status_desc'] = '';
				if(!isset($data['start_process']) || $data['start_process']=="") $data['start_process'] = date(DATETIME_DB);
				
				$table = "notification";
				$registro = array(
           'subject' => $data['subject'],
           '`from`' => $data['from'],
           'type' => $data['type'],
           'destination_type' => 3,
           'destination_id' => 0,
           'content' => $data['content'],
           'active' => $data['active'],
           'start_process' => $data['start_process'],
           'status' => $data['status'],
           'status_desc' => $data['status_desc'],
           'create_user' => $this->session->userdata('user_id'),
           'create_ip' => $this->session->userdata('ip_address'),
           'create_time' => date(DATETIME_DB)
        );

				$this->db->insert($table, $registro);
				log_message('debug', 'SQL: '.$this->db->last_query());
				//echo $this->db->last_query();

				$data['id_notification'] = $this->db->insert_id();

				$table = "notification_mails";
				$registro = array(
           'id_notification' => $data['id_notification'],
           'subject' => $data['subject'],
           '`from`' => $data['from'],
           'type' => $data['type'],
           'destination_type' => 7,
           'destination_id' => 0,
           'content' => $data['content'],
           'active' => $data['active'],
           'start_process' => $data['start_process'],
           'send' => $data['send'],
           'create_user' => $this->session->userdata('user_id'),
           'create_ip' => $this->session->userdata('ip_address'),
           'create_time' => date(DATETIME_DB)
        );

				foreach($destinations as $destinatario) {
					$registro['destination_text'] = $destinatario;
					$this->db->insert($table, $registro);
				}
				//log_message('debug', 'SQL: '.$this->db->last_query());
				//echo $this->db->last_query();

			
			return TRUE;
					
    }



##    Función genérica para la creación de mails.
    function createPersonalizedMessage($data = NULL, $parameters = NULL) {
    	# Devuelve nombre de la pista
			
			if(!isset($data) || !is_array($data) || !isset($parameters) || !is_array($parameters)) return FALSE;
			
			
		
				if(!isset($data['active']) || $data['active']=="") $data['active'] = 0;
				if(!isset($data['send']) || $data['send']=="") $data['send'] = 0;
				if(!isset($data['status']) || $data['status']=="") $data['status'] = 0;
				if(!isset($data['status_desc']) || $data['status_desc']=="") $data['status_desc'] = '';
				if(!isset($data['start_process']) || $data['start_process']=="") $data['start_process'] = date(DATETIME_DB);
				
				$table = "notification";
				$registro = array(
           'subject' => $data['subject'],
           '`from`' => $data['from'],
           'type' => $data['type'],
           'destination_type' => $data['destination_type'],
           'destination_id' => $data['destination_id'],
           'content' => $data['content'],
           'active' => $data['active'],
           'start_process' => $data['start_process'],
           'status' => $data['status'],
           'status_desc' => $data['status_desc'],
           'create_user' => $this->session->userdata('user_id'),
           'create_ip' => $this->session->userdata('ip_address'),
           'create_time' => date(DATETIME_DB)
        );

				$this->db->insert($table, $registro);
				log_message('debug', 'SQL: '.$this->db->last_query());
				//echo $this->db->last_query();

				$data['id_notification'] = $this->db->insert_id();
				foreach($parameters as $mail => $params) {
					$table = "notification_mails";
					$content = $data['content'];
					$subject = $data['subject'];
					foreach($params as $param => $value) {
						$content = str_replace('[#'.$param.'#]', $value, $content);
						$subject = str_replace('[#'.$param.'#]', $value, $subject);
					}
					$registro = array(
	           'id_notification' => $data['id_notification'],
	           'subject' => $subject,
	           '`from`' => $data['from'],
	           'type' => $data['type'],
	           'destination_type' => '7',
	           'destination_id' => 0,
	           'destination_text' => $mail,
	           'content' => $content,
	           'active' => $data['active'],
	           'start_process' => $data['start_process'],
	           'send' => $data['send'],
	           'create_user' => $this->session->userdata('user_id'),
	           'create_ip' => $this->session->userdata('ip_address'),
	           'create_time' => date(DATETIME_DB)
	        );
	
					$this->db->insert($table, $registro);
					//log_message('debug', 'SQL: '.$this->db->last_query());
				//echo $this->db->last_query();
				}
			
			return TRUE;
					
    }




##    Función para la creación de mails con notificaciones individuales, para ser enviadas.
    function createNotificationMessage($data = NULL) {
    	# Devuelve nombre de la pista
			
			if(!isset($data) || !is_array($data)) return FALSE;
			
			$table = "notification_mails";
			
			if(!isset($data['active']) || $data['active']=="") $data['active'] = 0;
			if(!isset($data['send']) || $data['send']=="") $data['send'] = 0;
			if(!isset($data['start_process']) || $data['start_process']=="") $data['start_process'] = date(DATETIME_DB);

			$registro = array(
         'subject' => $data['subject'],
         'from' => $data['from'],
         'type' => 1,
         'destination_type' => 7,
         'destination_id' => $data['destination_id'],
         'destination_text' => $data['destination_text'],
         'content' => $data['content'],
         'active' => $data['active'],
         'start_process' => $data['start_process'],
         'send' => $data['send'],
         'create_user' => $this->session->userdata('user_id'),
         'create_ip' => $this->session->userdata('ip_address'),
         'create_time' => date(DATETIME_DB)
      );

			$this->createMessage($registro);
			
			return TRUE;
					
    }


		//**************************************
    // Registro el correo como enviado satisfactoriamente
    
    function setMessageSended($id) {
    	# Devuelve nombre de la pista
			
			$table = "notification_mails";
			$data = array(
						'send' => 1, 
						'end_process' => date(DATETIME_DB), 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->update($table, $data, array('id' => $id));
			log_message('debug', 'SQL: '.$this->db->last_query());
			//echo $this->db->last_query();

					
    }



		//**************************************
    // Registro el correo como con error
    
    function setMessageFailed($id, $error_text) {
    	# Devuelve nombre de la pista
			
			$table = "notification_mails";
			$data = array(
						'error_desc' => $error_text, 
						'end_process' => date(DATETIME_DB), 
						'modify_time' => date(DATETIME_DB), 
						'modify_user' => $this->session->userdata('user_id'),
						'modify_ip' => $this->session->userdata('ip_address'));
			$this->db->set('error_count', 'error_count+1', FALSE);
			$this->db->update($table, $data, array('id' => $id));
			log_message('debug', 'SQL: '.$this->db->last_query());
			//echo $this->db->last_query();

					
    }





	public function get_notification($id)
		{
			
			$table_name = 'notification';
			
			//Build contents query
			$this->db->select($table_name.'.id, '.$table_name.'.subject, '.$table_name.'.from, '.$table_name.'.type, zz_notification_type.description as type_desc, '.$table_name.'.destination_type, zz_notification_dest_type.description as destination_type_desc, '.$table_name.'.destination_id, '.$table_name.'.destination, '.$table_name.'.content, '.$table_name.'.status, zz_notification_status.description as status_description, '.$table_name.'.status_desc, '.$table_name.'.active, '.$table_name.'.start_process, '.$table_name.'.end_process, '.$table_name.'.create_user, '.$table_name.'.create_time, '.$table_name.'.create_ip, '.$table_name.'.modify_user, '.$table_name.'.modify_time, '.$table_name.'.modify_ip', FALSE)->from($table_name);

			$this->db->join('zz_notification_dest_type', ''.$table_name.'.destination_type=zz_notification_dest_type.id', 'left outer');
			$this->db->join('zz_notification_status', ''.$table_name.'.status=zz_notification_status.id', 'left outer');
			$this->db->join('zz_notification_type', ''.$table_name.'.type=zz_notification_type.id', 'left outer');
	
	
			$this->db->where($table_name.'.id = \''.$id.'\'');
		
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			$resultado =  $query->result_array();
			return $resultado[0];
		

		}



	public function get_data($params = "" , $page = "all")
		{
			
			$table_name = 'notification';
			
			//Build contents query
			$this->db->select($table_name.'.id, '.$table_name.'.subject, '.$table_name.'.from, '.$table_name.'.type, zz_notification_type.description as type_desc, '.$table_name.'.destination_type, zz_notification_dest_type.description as destination_type_desc, '.$table_name.'.destination_id, '.$table_name.'.destination, '.$table_name.'.content, '.$table_name.'.status, zz_notification_status.description as status_description, '.$table_name.'.status_desc, '.$table_name.'.active, '.$table_name.'.start_process, '.$table_name.'.end_process, '.$table_name.'.create_user, '.$table_name.'.create_time, '.$table_name.'.create_ip, '.$table_name.'.modify_user, '.$table_name.'.modify_time, '.$table_name.'.modify_ip', FALSE)->from($table_name);

			$this->db->join('zz_notification_dest_type', ''.$table_name.'.destination_type=zz_notification_dest_type.id', 'left outer');
			$this->db->join('zz_notification_status', ''.$table_name.'.status=zz_notification_status.id', 'left outer');
			$this->db->join('zz_notification_type', ''.$table_name.'.type=zz_notification_type.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			return $query->result_array();
		

		}




	public function get_data_sended($params = "" , $page = "all")
		{
			
			$table_name = 'notification_mails';
			
			//Build contents query
			$this->db->select($table_name.'.id, '.$table_name.'.id_notification, '.$table_name.'.subject, '.$table_name.'.from, '.$table_name.'.type, zz_notification_type.description as type_desc, '.$table_name.'.destination_type, zz_notification_dest_type.description as destination_type_desc, '.$table_name.'.destination_id, '.$table_name.'.destination_text, '.$table_name.'.content, '.$table_name.'.active, '.$table_name.'.send, '.$table_name.'.start_process, '.$table_name.'.end_process, '.$table_name.'.error_count, '.$table_name.'.error_desc, '.$table_name.'.create_user, '.$table_name.'.create_time, '.$table_name.'.create_ip, '.$table_name.'.modify_user, '.$table_name.'.modify_time, '.$table_name.'.modify_ip', FALSE)->from($table_name);

			$this->db->join('zz_notification_dest_type', ''.$table_name.'.destination_type=zz_notification_dest_type.id', 'left outer');
			$this->db->join('zz_notification_type', ''.$table_name.'.type=zz_notification_type.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			//if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			return $query->result_array();
		

		}


	public function get_data_sended_count($params = "" , $page = "all")
		{
			
			$table_name = 'notification_mails';
			
			//Build contents query
			$this->db->select('distinct '.$table_name.'.id', FALSE)->from($table_name);

			$this->db->join('zz_notification_dest_type', ''.$table_name.'.destination_type=zz_notification_dest_type.id', 'left outer');
			$this->db->join('zz_notification_type', ''.$table_name.'.type=zz_notification_type.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			return count($query->result_array());
		

		}


	public function get_data_count($params = "" , $page = "all")
		{
			
			$table_name = 'notification';
			
			//Build contents query
			$this->db->select('distinct '.$table_name.'.id', FALSE)->from($table_name);

			$this->db->join('zz_notification_dest_type', ''.$table_name.'.destination_type=zz_notification_dest_type.id', 'left outer');
			$this->db->join('zz_notification_status', ''.$table_name.'.status=zz_notification_status.id', 'left outer');
			$this->db->join('zz_notification_type', ''.$table_name.'.type=zz_notification_type.id', 'left outer');
	
	
			if (!empty ($params['where'])) $this->db->where($params['where']);
		
			if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
			
			if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
			
			//Get contents
			$query = $this->db->get();
			log_message('debug',$this->db->last_query());
			
			return count($query->result_array());
		

		}










    function get_status()
    {
			$sql_select = "SELECT id, description FROM zz_notification_status ";
			$query = $this->db->query($sql_select);

			foreach ($query->result() as $row)
			{
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'description'  => $row->description);
			}

			return $array_all;
    }


    function get_types()
    {
			$sql_select = "SELECT id, description FROM zz_notification_dest_type ";
			$query = $this->db->query($sql_select);

			foreach ($query->result() as $row)
			{
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'description'  => $row->description);
			}

			return $array_all;
    }


    function get_destination_types()
    {
			$sql_select = "SELECT id, description FROM zz_notification_type ";
			$query = $this->db->query($sql_select);

			foreach ($query->result() as $row)
			{
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'description'  => $row->description);
			}

			return $array_all;
    }


}
