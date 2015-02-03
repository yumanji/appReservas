<?php
class Payment_model extends CI_Model {

    var $id_type   = NULL;
    var $id_element   = NULL;
    var $id_user   = NULL;
    var $desc_user   = NULL;
    var $status   = NULL;
    var $quantity   = NULL;
    var $datetime   = NULL;
    var $fecha_valor   = NULL;
    var $description   = NULL;
    var $id_transaction   = NULL;
    var $id_paymentway   = NULL;
    var $ticket_number   = NULL;
    var $create_user   = NULL;
    var $create_time   = NULL;
    var $create_ip   = NULL;

    public function __construct()
    {
    	parent::__construct();
    	//isset($this->CI) || $this->CI =& get_instance(); 
    }
    
##############################################################################

	function setPayment() {
		
		# Compruebo, si el pago no es una devolución, que no hay ningún pago asociado a la misma transacción.. 
		if(isset($this->status)  && isset($this->id_transaction) && $this->id_transaction != '') {
			$test = $this->getPaymentByTransaction($this->id_transaction);
			//var_export ($test);exit();
			if(isset($test) && $test->id_transaction == $this->id_transaction && $test->desc_user == $this->desc_user && $test->quantity == $this->quantity && $test->id_user == $this->id_user ) {
				log_message('debug','Intento de crear un pago con id_transaction ya utilizado para otro existente: '.$this->id_transaction);
				//echo '<br>Intento de crear un pago con id_transaction ya utilizado para otro existente: '.$this->id_transaction;
				return NULL;
			}
		} 
		//else echo '<br>Devolucion';
		
		
		# Si la fecha valor no está definida, lo relleno con la fecha actual
		if(!isset($this->fecha_valor) || $this->fecha_valor == '') $this->fecha_valor = date($this->config->item('date_db_format'));
		$ticket_number = $this->getNextTicketNumber(date($this->config->item('date_db_format'), strtotime($this->datetime)));
		//exit($ticket_number.'------');
		$this->ticket_number = $ticket_number;
		
		if(!isset($this->create_user) || $this->create_user == '') $this->create_user = $this->session->userdata('user_id');
		if(!isset($this->create_time) || $this->create_time == '') $this->create_time = date($this->config->item('date_db_format'));
		if(!isset($this->create_ip) || $this->create_ip == '') $this->create_ip = $this->session->userdata('ip_address');
		
	  $this->db->insert('payments', $this);
	  log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  
			# Devuelvo el ID del pago generado
			return $this->db->insert_id();
	}

##############################################################################

	function updatePaymentStatus($field, $value, $status) {
		
    	$data = array(
               'status' => $status,
               'remesa' => '',
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      $this->db->where($field, $value);
			$this->db->update('payments', $data);
			log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}

##############################################################################

	function updatePaymentPaymentway($field, $value, $status) {
		
    	$data = array(
               'id_paymentway' => $status,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      $this->db->where($field, $value);
			$this->db->update('payments', $data);
			log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}
##############################################################################

	function updatePaymentDesc($field, $value, $desc) {
		
    	$data = array(
               'description' => $desc,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      $this->db->where($field, $value);
			$this->db->update('payments', $data);
			log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}
	
##############################################################################

	function updatePaymentQuantity($id, $quantity) {
		
    	$data = array(
               'quantity' => $quantity,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      $this->db->where('id', $id);
			$this->db->update('payments', $data);
			log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}

	
##############################################################################

	function updatePaymentFechaValor($field, $value, $fecha) {
		
		if(!is_int($fecha)) return FALSE;
		
    	$data = array(
               'fecha_valor' => date($this->config->item('date_db_format'), $fecha),
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
		$this->db->where($field, $value);
		$this->db->update('payments', $data);
		log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}



	
##############################################################################

	function updatePaymentDateTime($field, $value, $fecha) {
		#Fecha en timestamp
		if(!is_int($fecha) && !is_long($fecha)) return FALSE;
		
    	$data = array(
               'datetime' => date($this->config->item('log_date_format'), $fecha),
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
		$this->db->where($field, $value);
		$this->db->update('payments', $data);
		log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}


##############################################################################

	function setRemesa($remesa, $pagos) {
		
		if(!is_array($pagos)) $pagos = array($pagos);
		
    	$data = array(
               'remesa' => $remesa,
               'status' => 9,
	             'modify_user' => $this->session->userdata('user_id'),
	             'modify_time' => date($this->config->item('log_date_format')),
	             'modify_ip' => $this->session->userdata('ip_address')
      );
      $this->db->where_in('id', $pagos);
			$this->db->update('payments', $data);
			log_message('debug',$this->db->last_query());
	  //echo $this->db->last_query();
	  return TRUE;
	}


##############################################################################

	function setPaymentExtra($id_payment, $data) {
		
			$data['create_user'] = $this->session->userdata('user_id');
			$data['create_time'] = date($this->config->item('log_date_format'));
			$data['create_ip'] = $this->session->userdata('ip_address');

			# Si el status es mayor que cero, marco la reserva... Si no, solo ejecuto la funcionalidad de marcar en sesion
			$this->db->insert('payments_tpv_extra', $data);	  
			log_message('debug',$this->db->last_query());
			
			return TRUE;
	}

##############################################################################

	function getPaymentById($id_payment) {
		
		$this->db->select('payments.id, payments.id_type, payments.id_element, payments.id_user, payments.id_transaction, payments.desc_user, payments.status, payments.quantity, payments.datetime, payments.fecha_valor, payments.description, payments.id_paymentway, payments.ticket_number, payments.desc_user as desc_user, meta.first_name as first_name, meta.last_name as last_name, meta.phone as user_phone, zz_payment_status.description as status_desc, zz_paymentway.description as paymentway_desc, zz_payment_type.description as id_type_desc');
		$this->db->from('payments');
		$this->db->join('meta', 'payments.id_user=meta.user_id', 'left outer');
		$this->db->join('zz_payment_status', 'payments.status=zz_payment_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'payments.id_paymentway=zz_paymentway.id', 'left outer');
		$this->db->join('zz_payment_type', 'payments.id_type=zz_payment_type.id', 'left outer');
		$this->db->where('payments.id', $id_payment);
		
		$record = $this->db->get();
		//echo $this->db->last_query();
				if ($record->num_rows() > 0) 
				{
					return $record->result();
				} else return NULL;

	}


##############################################################################

	function getPaymentByTransaction($id_transaction) {
		
		$this->db->select('payments.id, payments.id_type, payments.id_element, payments.id_user, payments.id_transaction, payments.desc_user, payments.status, payments.quantity, payments.datetime, payments.fecha_valor, payments.description, payments.id_paymentway, payments.ticket_number, payments.desc_user as desc_user, meta.first_name as first_name, meta.last_name as last_name, meta.phone as user_phone, zz_payment_status.description as status_desc, zz_paymentway.description as paymentway_desc, zz_payment_type.description as id_type_desc');
		$this->db->from('payments');
		$this->db->join('meta', 'payments.id_user=meta.user_id', 'left outer');
		$this->db->join('zz_payment_status', 'payments.status=zz_payment_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'payments.id_paymentway=zz_paymentway.id', 'left outer');
		$this->db->join('zz_payment_type', 'payments.id_type=zz_payment_type.id', 'left outer');
		$this->db->where('payments.id_transaction', $id_transaction);
		
		$record = $this->db->get();
		log_message('debug',$this->db->last_query());
		//echo $this->db->last_query();
				if ($record->num_rows() > 0) 
				{
					return $record->row();
				} else return NULL;

	}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de todos los pagos
# -------------------------------------------------------------------
	public function get_global_list($filters="", $orderby="", $orderbyway="", $limit="", $flexigrid=FALSE) 
	{
		

		//Select table name
		$table_name = "payments";
		
		//Build contents query
		$this->db->select('payments.id as id, payments.id_type as id_type, payments.id_transaction as id_transaction, payments.id_user, payments.desc_user as desc_user, payments.status as status, payments.quantity, payments.ticket_number, payments.datetime as datetime, DATE_FORMAT(DATE(payments.fecha_valor), \'%d-%m-%Y\') as fecha_valor, DATE_FORMAT(DATE(payments.datetime), \'%d-%m-%Y\') as date, TIME(payments.datetime) as time, payments.description as description, payments.id_paymentway as id_paymentway, payments.create_user as create_user, payments.create_time as create_time, payments.modify_user as modify_user, payments.modify_time as modify_time, meta.first_name as first_name, meta.last_name as last_name, meta.player_level as player_level, meta.phone as phone, groups.description as grupo, zz_payment_status.description as status_desc, zz_paymentway.description as paymentway_desc, zz_payment_type.description as id_type_desc, meta.bank_titular, meta.bank, meta.bank_office, meta.bank_dc, meta.bank_account, meta.numero_socio', FALSE)->from($table_name);
		if($flexigrid) $this->CI->flexigrid->build_query();
		//$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		//$this->db->join('booking', 'payments.id_transaction=booking.id_transaction', 'left outer');
		$this->db->join('meta', 'payments.id_user=meta.user_id', 'left outer');
		$this->db->join('users', 'payments.id_user=users.id', 'left outer');
		$this->db->join('groups', 'users.group_id=groups.id', 'left outer');
		$this->db->join('zz_payment_status', 'payments.status=zz_payment_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'payments.id_paymentway=zz_paymentway.id', 'left outer');
		$this->db->join('zz_payment_type', 'payments.id_type=zz_payment_type.id', 'left outer');


		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
	
		if (isset($orderby) && trim($orderby)!="" && isset($orderbyway) && trim($orderbyway)!="") $this->db->order_by($orderby, $orderbyway);
		
		if (isset($limit) && trim($limit)!="") $this->db->limit($limit);
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo "A<br>A<br>A<br>A<br>A<br>A".$this->db->last_query()."CCCCCCCCCCC";
		log_message('debug', 'SQL: '.$this->db->last_query());
		//Build count query
		
		
		# Para devolver el numero de registros
		$this->db->select('count(payments.id) as record_count')->from($table_name);
		if($flexigrid) $this->CI->flexigrid->build_query();
		//$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		//$this->db->join('booking', 'payments.id_transaction=booking.id_transaction', 'left outer');
		$this->db->join('meta', 'payments.id_user=meta.user_id', 'left outer');
		$this->db->join('users', 'payments.id_user=users.id', 'left outer');
		$this->db->join('groups', 'users.group_id=groups.id', 'left outer');
		$this->db->join('zz_payment_status', 'payments.status=zz_payment_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'payments.id_paymentway=zz_paymentway.id', 'left outer');
		$this->db->join('zz_payment_type', 'payments.id_type=zz_payment_type.id', 'left outer');
		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
		//if (isset($orderby) && trim($orderby)!="" && isset($orderbyway) && trim($orderbyway)!="") $this->db->order_by($orderby, $orderbyway);
		//if (isset($limit) && trim($limit)!="") $this->db->limit($limit);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		
		
		//Return all
		return $return;
	}
	
	


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas UNIFICADAS, en formato array, 
# -------------------------------------------------------------------
	public function get_next_payments($filters = NULL, $order = NULL, $orderway = NULL , $limit = NULL) 
	{

		$records = $this->get_global_list($filters, $order, $orderway, $limit );

		 $buttons=''; $registro=array(); $transaccion=""; $min_time=""; $max_time="";$precio=0;
		 $record_items = array();
		foreach ($records['records']->result() as $row)
		{
			$registro=array();
			
			if($row->id_user) $usuario = $row->first_name.' '.$row->last_name;
			else $usuario = $row->desc_user;
			if(trim($usuario)=="") $usuario="No registrado";
			
			
			$registro = array(
				$row->id,
				$row->id,
				$row->id_type_desc,
				$this->lang->line($row->paymentway_desc),
				$usuario,
				$row->quantity,
				$this->lang->line($row->status_desc),
				date($this->config->item('reserve_date_filter_format') ,strtotime($row->datetime)),
				date($this->config->item('reserve_hour_filter_format') ,strtotime($row->datetime)),
				$row->description
			);	
			//print("<pre>");print_r($row);print("</pre>");
			$record_items[] = $registro;
		}
		
		return $record_items;

	}


##############################################################################


    
    function getStatusArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array(""=>"Selecciona opcion");
        $sql = "SELECT id, description FROM zz_payment_status "; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$this->lang->line($row->description);
				}	
					return $result;				
    }


##############################################################################



    
    function getPaymentWaysArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $enable_reserve = $this->config->item('enable_reserve');
        $enable_cash = $this->config->item('enable_cash');
        $enable_prepaid = $this->config->item('enable_prepaid');
        $enable_creditcard = $this->config->item('enable_creditcard');
        $enable_paypal = $this->config->item('enable_paypal');
        $enable_bank = $this->config->item('enable_bank');
        $enable_tpv = $this->config->item('enable_tpv');

        $result=array(""=>"Selecciona opcion");
        $sql = "SELECT id, description FROM zz_paymentway WHERE active=1"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$ok = 0;
					switch ($row->id) {
						case 1:
							if($enable_cash) $ok = 1;
						break;
						case 2:
							if($enable_creditcard) $ok = 1;
						break;
						case 3:
							if($enable_paypal) $ok = 1;
						break;
						case 4:
							if($enable_bank) $ok = 1;
						break;
						case 5:
							if($enable_prepaid) $ok = 1;
						break;
						case 6:
							if($enable_tpv) $ok = 1;
						break;
					}
					if($ok) $result[$row->id]=$this->lang->line($row->description);
				}
				
				
				return $result;				
    }


##############################################################################


    
    function getPaymentTypesArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array(""=>"Selecciona opcion");
        $sql = "SELECT id, description FROM zz_payment_type WHERE active=1"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$this->lang->line($row->description);
				}	
					return $result;				
    }



##############################################################################



  function getPaymentsFrequencies ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_payment_frequency WHERE active = 1 ORDER BY id ASC"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }



##############################################################################



  function getFrequencyCommand ($id)
  {
	//$query = $this->db->get('entries', 10);
	//return $query->result();
	$sql = "SELECT php_interval_name FROM zz_payment_frequency WHERE active = 1 AND id = ?"; 
	$query = $this->db->query($sql, array($id));
	foreach ($query->result() as $row)
	{
		return $row->php_interval_name;
	}	
	return null;				
  }




##############################################################################


	
public function get_data($params = "" , $page = "all")
	{
		

		//Select table name
		$table_name = "payments";
		
		//Build contents query
		if($page=='count') { $this->db->count_all_results($table_name); $this->db->from($table_name); }
		else $this->db->select('payments.id as id, payments.id_type as id_type, payments.id_transaction as id_transaction, payments.id_user, payments.desc_user as desc_user, payments.status as status, payments.quantity, payments.ticket_number, payments.datetime as datetime, DATE_FORMAT(DATE(payments.fecha_valor), \'%d-%m-%Y\') as fecha_valor, DATE_FORMAT(DATE(payments.datetime), \'%d-%m-%Y\') as date, TIME(payments.datetime) as time, payments.description as description, payments.id_paymentway as id_paymentway, payments.create_user as create_user, payments.create_time as create_time, payments.modify_user as modify_user, payments.modify_time as modify_time, meta.first_name as first_name, meta.last_name as last_name, zz_payment_status.description as status_desc, zz_paymentway.description as paymentway_desc, zz_payment_type.description as id_type_desc , meta.bank_titular, meta.bank, meta.bank_office, meta.bank_dc, meta.bank_account', FALSE)->from($table_name);
		//$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		//$this->db->join('booking', 'payments.id_transaction=booking.id_transaction', 'left outer');
		$this->db->join('meta', 'payments.id_user=meta.user_id', 'left outer');
		$this->db->join('zz_payment_status', 'payments.status=zz_payment_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'payments.id_paymentway=zz_paymentway.id', 'left outer');
		$this->db->join('zz_payment_type', 'payments.id_type=zz_payment_type.id', 'left outer');

		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		
		if ($page != "all" && $page != "count") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		//exit($params ["num_rows"].' - '.$params ["page"]);
		//Get contents
		if($page=='count') return $this->db->count_all_results();
		else {
			$query = $this->db->get();
			//log_message('debug',$this->db->last_query());
			return $query;
		}
	

	}



##############################################################################


	
public function get_data_to_export($params = "" , $page = "all")
	{
		
		isset($this->CI) || $this->CI =& get_instance();

		//Select table name
		$table_name = "payments";
		
		//Build contents query
		$this->db->select('payments.id_user as id_usuario, payments.desc_user as Usuario, payments.quantity as Importe, payments.ticket_number as NumeroTicket, payments.datetime as FechaFactura, payments.description as Descripcion, payments.create_user as UsuarioQueFactura, zz_payment_status.description as EstadoPago, zz_paymentway.description as FormaPago, zz_payment_type.description as ConceptoPago, zz_sports.description as Deporte, payments.id_transaction as id_element  ', FALSE)->from($table_name);
		$this->db->distinct();
		//$this->db->join('courts', 'courts.id=booking.id_court', 'left outer');
		//$this->db->join('booking', 'payments.id_transaction=booking.id_transaction', 'left outer');
		$this->db->join('meta', 'payments.id_user=meta.user_id', 'left outer');
		$this->db->join('booking', 'payments.id_transaction=booking.id_transaction', 'left outer');
		$this->db->join('courts', 'booking.id_court=courts.id', 'left outer');
		$this->db->join('courts_types', 'courts.court_type=courts_types.id', 'left outer');
		$this->db->join('zz_sports', 'courts_types.id_sport=zz_sports.id', 'left outer');
		$this->db->join('zz_payment_status', 'payments.status=zz_payment_status.id', 'left outer');
		$this->db->join('zz_paymentway', 'payments.id_paymentway=zz_paymentway.id', 'left outer');
		$this->db->join('zz_payment_type', 'payments.id_type=zz_payment_type.id', 'left outer');

		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		
		if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		$resultado = $query->result_array();
		//exit($this->db->last_query());
		//log_message('debug',$this->db->last_query());
		
		return $resultado;
	

	}


##############################################################################



    function getPaymentMethodsByUser($user_level) {
    	# Devuelve array de los diferentes métodos de pago disponibles
    	isset($this->CI) || $this->CI =& get_instance();
    	$this->CI->load->config('pagos');
    	
    	$payment = array ('reserve' => FALSE, 'cash' => FALSE, 'paypal' => FALSE, 'prepaid' => FALSE, 'creditcard' => FALSE, 'tpv' => FALSE, 'bank' => FALSE);
    	//echo $user_level.'<br>';
    	//print_r($payment);
    	foreach($payment as $type => $value) {
    		$payment[$type] = $this->app_common->PaymentMethodStatus($type);
    	}
    	
    	if($user_level < 3 ) $pagos_adaptado = $this->CI->config->item('payment_admin_available');
    	elseif($user_level >= 3 && $user_level < 4 ) $pagos_adaptado = $this->CI->config->item('payment_operator_available');
    	elseif($user_level >= 4 && $user_level < 5 ) $pagos_adaptado = $this->CI->config->item('payment_profesor_available');
    	elseif($user_level >= 5 && $user_level < 7 ) $pagos_adaptado = $this->CI->config->item('payment_advanced_user_available');
    	elseif($user_level >= 7 && $user_level < 8 ) $pagos_adaptado = $this->CI->config->item('payment_user_available');
    	else  $pagos_adaptado = $this->CI->config->item('payment_anonimo_available');
    	
    	//print_r($payment);print_r($pagos_adaptado);//exit("AAAAAA");
    	foreach($payment as $type => $value) {
    		if($payment[$type] && isset($pagos_adaptado[$type]) && !$pagos_adaptado[$type]) $payment[$type] = FALSE;
    	}
    	
			return $payment;			
		        
    }



##############################################################################
	
function getNextTicketNumber($date, $extra = NULL)
	{
		
		if(!isset($date)) return NULL;
		isset($this->CI) || $this->CI =& get_instance();
		$this->CI->load->config('pagos');
		
		$numero_diario = date($this->CI->config->item('payment_ticket_format_prefix')).sprintf($this->CI->config->item('payment_ticket_format_number'), 1); 
		//echo '--'.$numero_diario.'<br>';
		//Select table name
		$table_name = "payments";
		
		//Build contents query
		$this->db->select_max('payments.ticket_number', FALSE)->from($table_name);

		$this->db->where('date(datetime)', $date);
			
		//Get contents
		$query = $this->db->get();
		log_message('debug',$this->db->last_query());
		if ($query->num_rows() > 0)
		{
		  $row = $query->row(); 
			$maximo = $row->ticket_number;
			if(isset($maximo) && $maximo != '') {
				$sub_maximo = intval(substr($maximo, (-1 * $this->CI->config->item('payment_ticket_format_number_length'))));
				//echo '--'.$sub_maximo.'<br>';
				$numero_diario = date($this->CI->config->item('payment_ticket_format_prefix')).sprintf($this->CI->config->item('payment_ticket_format_number'), $sub_maximo + 1);
			}
		   //echo '--'.$row->ticket_number;
		}		
		//echo 'Final--'.$numero_diario.'<br>';
		return $numero_diario;
	

	}


}
?>