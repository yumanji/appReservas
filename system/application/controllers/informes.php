<?php

class Informes extends Controller {

	function Informes()
	{
		parent::Controller();	
		//$this->load->helper('flexigrid');
		$this->lang->load('reservas');
		$this->load->config('facturacion');
	}
	
	function index()
	{
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('informes/index', array(), true),
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);
	}




	
	function todas_reservas()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$campos_busqueda = $this->simpleSearchFields();
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';
				//booking.id as id, id_booking, id_user, session, id_court, date as fecha, intervalo, status, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, meta.last_name as last_name, zz_booking_status.description as status_desc, zz_paymentway.description as paymentway_desc
		//print_r($this->reservas->get_global_list());
		$where_arr=array();
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_status=$this->input->post('status');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('user');
			$selected_no_cost=$this->input->post('no_cost');
			$selected_date1=$this->input->post('date1');
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));

		if($selected_sport!="") array_push($where_arr, "courts.sport_type = '".$selected_sport."'");
		if($selected_court_type!="") array_push($where_arr, "courts.court_type = '".$selected_court_type."'");
		if($selected_court!="") array_push($where_arr, "id_court = '".$selected_court."'");
		if($selected_status!="") array_push($where_arr, "status = '".$selected_status."'");
		if($selected_paymentway!="") array_push($where_arr, "id_paymentway = '".$selected_paymentway."'");
		if($selected_user!="") array_push($where_arr, "id_user = '".$selected_user."'");
		if($selected_no_cost!="") array_push($where_arr, "no_cost = '".$selected_no_cost."'");
		if($selected_date1!="") array_push($where_arr, "date >= '".$selected_date1."'");
		if($selected_date2!="") array_push($where_arr, "date <= '".$selected_date2."'");
		
		$where=implode(' AND ', $where_arr);
		$order='date, courts.name,  intervalo';
		$order_way='asc';
		$records = $this->reservas->get_global_list($where, $order, $order_way, null);
		/*
		foreach ($records['records']->result() as $row) {
			$resultado[] = array(
				$row->id,
				$row->id_booking,
				$row->id_user,
				$row->session,
				$row->id_court,
				$row->fecha,
				$row->intervalo,
				$row->status,
				$row->id_paymentway,
				$row->price,
				$row->no_cost,
				$row->no_cost_desc,
				$row->user_desc,
				$row->user_phone,
				$row->create_user,
				$row->create_time,
				$row->modify_user,
				$row->modify_time,
				$row->court_name,
				$row->first_name,
				$row->last_name,
				$row->status_desc,
				$row->paymentway_desc
			);
		}
		*/
	
		$resultado=array();
		foreach ($records['records']->result() as $row) {
			if($row->id_user) $usuario = $row->first_name." ".$row->last_name;
			else $usuario = $row->user_desc."(".$row->user_phone.")";
			
			if(!$row->no_cost) $cobro=img( array('src'=>'images/coins.png', "align"=>"absmiddle", "border"=>"0"));
			else $cobro=$row->no_cost_desc;
			
			$resultado[] = array(
				$row->court_name,
				$row->fecha,
				$row->intervalo,
				$row->status_desc,
				$row->paymentway_desc,
				$usuario,
				$row->price,
				$cobro
			);
		}
		
		//print("<pre>");print_r($resultado);
		$fields=array('Pista', 'Fecha', 'Hora', 'Estado de pago', 'Forma de pago', 'Usuario', 'Precio', 'Ingreso');
		
		$contenido = $this->load->view('informes/result', array('resultado' => $resultado, 'campos' => $fields, 'filtros' => $campos_busqueda), TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true),
			'main_content' => $contenido, 
			'form_name' => 'frmInforme', 
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		$this->load->view('main', $data);
	}




	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de facturación diaria
# -------------------------------------------------------------------
	function facturacion_diaria($formato="")
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->library('calendario');
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$campos_busqueda = $this->simpleSearchFields(array('sports'=>0, 'court_type'=>0, 'court'=>0, 'status'=>0, 'paymentway'=>0, 'date1' => date($this->config->item('reserve_date_filter_format')), 'date2' => date($this->config->item('reserve_date_filter_format'))));
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';
				//booking.id as id, id_booking, id_user, session, id_court, date as fecha, intervalo, status, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, meta.last_name as last_name, zz_booking_status.description as status_desc, zz_paymentway.description as paymentway_desc
		//print_r($this->reservas->get_global_list());
		$where_arr=array();

			$selected_date1=$this->input->post('date1');
			if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'));
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));
			$selected_hour1=$this->input->post('hora1');
			if(!isset($selected_hour1) || $selected_hour1=="") $selected_hour1='00:00:00';
			$selected_hour2=$this->input->post('hora2');
			if(!isset($selected_hour2) || $selected_hour2=="") $selected_hour2='23:59:00';


		if($selected_date1!="") array_push($where_arr, "date(datetime) >= '".date($this->config->item('date_db_format'), strtotime($selected_date1))."'");
		if($selected_date2!="") array_push($where_arr, "date(datetime) <= '".date($this->config->item('date_db_format'), strtotime($selected_date2))."'");
		if($selected_hour1!="") array_push($where_arr, "time(datetime) >= '".date($this->config->item('hour_db_format'), strtotime($selected_hour1))."'");
		if($selected_hour2!="") array_push($where_arr, "time(datetime) <= '".date($this->config->item('hour_db_format'), strtotime($selected_hour2))."'");
		
		$estados = $this->config->item('payment_considered_to_report');
		//echo 'aaaa'; print_r($estados);
		array_push($where_arr, "payments.status IN (".implode(', ', $estados).")");
		
		$where=implode(' AND ', $where_arr);
		$order='zz_paymentway.description, payments.id_type ';
		$order_way='asc';
		$records = $this->pagos->get_global_list($where, $order, $order_way, null);
		/*
		foreach ($records['records']->result() as $row) {
			$resultado[] = array(
				$row->id,
				$row->id_booking,
				$row->id_user,
				$row->session,
				$row->id_court,
				$row->fecha,
				$row->intervalo,
				$row->status,
				$row->id_paymentway,
				$row->price,
				$row->no_cost,
				$row->no_cost_desc,
				$row->user_desc,
				$row->user_phone,
				$row->create_user,
				$row->create_time,
				$row->modify_user,
				$row->modify_time,
				$row->court_name,
				$row->first_name,
				$row->last_name,
				$row->status_desc,
				$row->paymentway_desc
			);
		}
		*/
	
		$resultado=array();
		//print("<pre>");print_r($records['records']->result());
		
		foreach ($records['records']->result() as $row) {
			if($row->id_user) $usuario = $row->first_name." ".$row->last_name;
			else $usuario = $row->desc_user;
			if(trim($usuario)=="") $usuario="No registrado";
			$info="";
			if ($row->id_type=="1" || $row->id_type=="4") {
				#Si estamos hablando de reservas de pistas
				$info=$this->reservas->getBookingInfoById($row->id_transaction);
				$cantidad=$info['intervals']/2;
				$fecha=$info['date'];
				$minimo=""; $maximo="";
				if(isset($info['reserva'])) {
					foreach($info['reserva'] as $code => $reserva) {
							if ($row->id_type=="1") $pista='Reservas '.$code;
							else $pista='Retos '.$code;
							foreach($reserva as $dato) {
						//print($dato[0]);
								if($minimo=="" || $minimo > $dato[0]) $minimo = $dato[0];
								if($maximo=="" || $maximo < $dato[1]) $maximo = $dato[1];
							}
					}
				}
			} elseif ($row->id_type=="2") {
				#Si estamos hablando de un curso
				$transaccion = explode('-', $row->id_transaction);
				//echo "AA".$transaccion[1];
				$info = $this->calendario->getCalendarByRange($transaccion[1]);
				//print_r($info);
				if(is_object($info)) {
					//$pista = $info->court_desc;
					//if(!isset($pista) || $pista!="") 
					$pista = "Desconocida";
					$cantidad = (date('U', strtotime($info->end_time)) - date('U', strtotime($info->start_time))) / 3600;
					$minimo = date($this->config->item('hour_db_format'), strtotime($info->start_time));
					$maximo = date($this->config->item('hour_db_format'), strtotime($info->end_time));
				} else {
					$pista = "Desconocida";
					$cantidad = 1;
					$minimo = 1;
					$maximo = 2;
					
				}
				$pista = "Cuota curso";
			}  elseif ($row->id_type=="5") {
				#Si estamos hablando de una cuota de usuario
					$pista = "Cuota usuario";
					$cantidad = 1;
					$minimo = 1;
					$maximo = 2;
			}  elseif ($row->id_type=="3") {
				#Si estamos hablando de pago de prepago
					$pista = "Pago bono prepago";
					$cantidad = 1;
					$minimo = 1;
					$maximo = 2;
			}  elseif ($row->id_type=="98") {
				#Si estamos hablando de suplemento luz
					$pista = "Suplemento luz";
					$cantidad = 1;
					$minimo = 1;
					$maximo = 2;
			}  else {
					$pista = "Pagos varios";
					$cantidad = 1;
					$minimo = 1;
					$maximo = 2;
				
			}
			//print_r($info);
			//echo $row->id_transaction."<br>";
			
			$resultado[] = array(
				$row->paymentway_desc,
				$row->id_type_desc,
				date($this->config->item('reserve_date_filter_format'), strtotime($row->datetime)),
				$pista,
				//number_format($cantidad,2,',', '.'),
				$cantidad,
				//number_format($row->quantity,2,',', '.'),
				$row->quantity,
				$minimo,
				$maximo,
				//$row->datetime,
				//$this->lang->line($row->status_desc),
				$usuario,
				$row->description
			);
		}
		
		//print("<pre>");print_r($resultado);
		if($formato=="excel") {
			$this->output->set_header("Content-type: application/vnd.ms-excel");
			$this->output->set_header("Content-Disposition: attachment;filename=export_".time().".xls");

			$_meses=array('','Enero','Febrero', 'Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre', 'Noviembre','Diciembre'); 
			$_dias=$this->config->item('weekdays_names');
			
			$salida="";
			$salida='<table boder="1">'."\r\n";
			$salida.='<tr><td>N. Ticket</td><td>Tipo reserva</td><td>Fecha Cobro</td><td>Fecha Valor</td><td>Forma pago</td><td>Servicio</td><td>Concepto</td><td>N. Abonado</td><td>Usuario</td><td>Tipo usuario</td><td>Telefono</td><td>Nivel</td><td>Precio</td><td>Año</td><td>Mes</td><td>Mes2</td><td>Dia</td><td>Dia Semana</td><td>Dia Semana 2</td></tr>';
			foreach ($records['records']->result() as $pago) {
				//print_r($pago);exit();
				if($pago->id_user) $usuario = $pago->first_name." ".$pago->last_name;
				else $usuario = $pago->desc_user;
				
				if($pago->grupo != '') $grupo = $pago->grupo;
				else $grupo = 'Anonimo';
				
				if(stristr($pago->description, 'reto ')) $reto = 'Reto';
				else $reto = $pago->id_type_desc;
				
				$salida.='<tr><td>'.$pago->ticket_number.'</td><td>'.$pago->id_type_desc.'</td><td>'.$pago->date.'</td><td>'.$pago->fecha_valor.'</td><td>'.$pago->paymentway_desc.'</td><td>'.$reto.'</td><td>'.$pago->description.'</td><td>'.$pago->numero_socio.'</td><td>'.$usuario.'</td><td>'.$grupo.'</td><td>'.$pago->phone.'</td><td>'.str_replace('.', ',', $pago->player_level).'</td><td>'.str_replace('.', ',', $pago->quantity).'</td><td>'.date('Y', strtotime($pago->date)).'</td><td>'.date('m', strtotime($pago->date)).'</td><td>'.$_meses[date('n', strtotime($pago->date))].'</td><td>'.date('d', strtotime($pago->date)).'</td><td>'.date('w', strtotime($pago->date)).'</td><td>'.$_dias[date('w', strtotime($pago->date))].'</td></tr>';
				/*
				$salida.='<tr>'."\r\n";
				$i=0;
				foreach($pago as $valor) {
					if($i == 4 || $i == 5) $salida.='<td>'.number_format($valor,2,',', '.').'</td>'."\r\n";
					else  $salida.='<td>'.$valor.'</td>'."\r\n";
					$i++;
				}
				$salida.='</tr>'."\r\n";
				*/
			}
			$salida.='</table>';
			$this->output->set_output($salida); 
			return NULL;
		}
		//echo $salida;
		$array_resultados=array();
		//print("<pre>");print_r($resultado);
		# Post proceso el resultado para crear un array jerarquizado por niveles donde contenga los pagos por tipo, pista, etc..
		foreach($resultado as $pago) {
			$total_euro=0;
			$total_cantidad=0;
			
			if(isset($array_resultados[$pago[0]])) {
				# Si esstá definido el elemento, recupero el valor anterior para sumárselo
				$total_euro=$array_resultados[$pago[0]]['total_euro'];
				$total_cantidad=$array_resultados[$pago[0]]['total_cantidad'];
			} else {
				# Si no existe el elemento, es que es la primera vuelta que doy en esa forma de pago, así que reinicio valores.
				$array_resultados[$pago[0]]=array('tipo' => $pago[0], 'total_euro' => 0.0, 'total_cantidad' => 0.0, 'detalle' => array());				
			}
			
			//echo "para pago ".$pago[0]." tenía ".$array_resultados[$pago[0]]['total_euro']." euros y ".$array_resultados[$pago[0]]['total_cantidad']." de cantidad y me quedaré con";
			$array_resultados[$pago[0]]['total_euro'] += $pago[5];
			$array_resultados[$pago[0]]['total_cantidad'] += $pago[4];
			
			
			if(isset($array_resultados[$pago[0]]['detalle'][$pago[3]])) {
				$detalle = $array_resultados[$pago[0]]['detalle'][$pago[3]];
				$detalle['cantidad']+=$pago[4];
				$detalle['euros']+=$pago[5];
				$array_resultados[$pago[0]]['detalle'][$pago[3]] = $detalle;
			} else {
				$array_resultados[$pago[0]]['detalle'][$pago[3]]=array('pista' => $pago[3], 'cantidad' => $pago[4], 'euros' => $pago[5]);
				//print("<pre>");print_r($array_resultados);
			}
			
			//array_push($array_resultados[$pago[0]]['detalle'], );
			//echo $array_resultados[$pago[0]]['total_euro']." euros y ".$array_resultados[$pago[0]]['total_cantidad']." de cantidad<br>".$pago[5].'<br>';
			
			
		}
		
		//print("<pre>");print_r($array_resultados);
		$fields=array('Fecha y hora', 'Concepto','Estado de pago', 'Forma de pago', 'Usuario', 'Cantidad',  'Descripcion');
		
		$contenido = $this->load->view('informes/facturacion_diaria', array('resultado' => $array_resultados, 'campos' => $fields, 'filtros' => $campos_busqueda, 'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true)), TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true),
			'main_content' => $contenido, 
			'form_name' => 'frmInforme', 
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		$this->load->view('main', $data);
	}





	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado para cierre de caja
# -------------------------------------------------------------------
	function cierre_dia($formato="")
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		
		$where="date(datetime) = '".date($this->config->item('date_db_format'))."' AND id_paymentway in (1,2)";
		$order='zz_paymentway.description, payments.id_type ';
		$order_way='asc';
		$records = $this->pagos->get_global_list($where, $order, $order_way, null);
		/*
		foreach ($records['records']->result() as $row) {
			$resultado[] = array(
				$row->id,
				$row->id_booking,
				$row->id_user,
				$row->session,
				$row->id_court,
				$row->fecha,
				$row->intervalo,
				$row->status,
				$row->id_paymentway,
				$row->price,
				$row->no_cost,
				$row->no_cost_desc,
				$row->user_desc,
				$row->user_phone,
				$row->create_user,
				$row->create_time,
				$row->modify_user,
				$row->modify_time,
				$row->court_name,
				$row->first_name,
				$row->last_name,
				$row->status_desc,
				$row->paymentway_desc
			);
		}
		*/
	
		$resultado=array();
		//print("<pre>");
		
		foreach ($records['records']->result() as $row) {
			if($row->id_user) $usuario = $row->first_name." ".$row->last_name;
			else $usuario = $row->desc_user;
			if(trim($usuario)=="") $usuario="No registrado";
			$info="";
			if ($row->id_type=="1") {
				#Si estamos hablando de reservas de pistas
				$info=$this->reservas->getBookingInfoById($row->id_transaction);
				//print("<pre>");print_r($info);
				$cantidad=$info['intervals']/2;
				$fecha=$info['date'];
				$minimo=""; $maximo="";
				if(isset($info['reserva'])){
					foreach($info['reserva'] as $code => $reserva) {
							$pista=$code;
							foreach($reserva as $dato) {
						//print($dato[0]);
								if($minimo=="" || $minimo > $dato[0]) $minimo = $dato[0];
								if($maximo=="" || $maximo < $dato[1]) $maximo = $dato[1];
							}
					}
				}
			}
			//print_r($info);
			//echo $row->id_transaction."<br>";
			
			$resultado[] = array(
				$row->paymentway_desc,
				$row->id_type_desc,
				date($this->config->item('reserve_date_filter_format'), strtotime($row->datetime)),
				$pista,
				//number_format($cantidad,2,',', '.'),
				$cantidad,
				//number_format($row->quantity,2,',', '.'),
				$row->quantity,
				$minimo,
				$maximo,
				//$row->datetime,
				//$this->lang->line($row->status_desc),
				$usuario
			);
		}
		
		//print("<pre>");print_r($resultado);
		if($formato=="excel") {
			$this->output->set_header("Content-type: application/vnd.ms-excel");
			$this->output->set_header("Content-Disposition: attachment;filename=export_".time().".xls");
			
			$salida="";
			$salida='<table boder="1">'."\r\n";
			$salida.='<tr><td>Forma Pago</td><td>Tipo reserva</td><td>Fecha</td><td>Servicio</td><td>Cantidad</td><td>Precio</td><td>Inicio</td><td>Fin</td><td>Usuario</td></tr>';
			foreach($resultado as $pago) {
				$salida.='<tr>'."\r\n";
				foreach($pago as $valor) $salida.='<td>'.$valor.'</td>'."\r\n";
				$salida.='</tr>'."\r\n";
			}
			$salida.='</table>';
			$this->output->set_output($salida); 
			return NULL;
		}
		//echo $salida;
		$array_resultados=array();
		
		# Post proceso el resultado para crear un array jerarquizado por niveles donde contenga los pagos por tipo, pista, etc..
		foreach($resultado as $pago) {
			$total_euro=0;
			$total_cantidad=0;
			
			if(isset($array_resultados[$pago[0]])) {
				# Si esstá definido el elemento, recupero el valor anterior para sumárselo
				$total_euro=$array_resultados[$pago[0]]['total_euro'];
				$total_cantidad=$array_resultados[$pago[0]]['total_cantidad'];
			} else {
				# Si no existe el elemento, es que es la primera vuelta que doy en esa forma de pago, así que reinicio valores.
				$array_resultados[$pago[0]]=array('tipo' => $pago[0], 'total_euro' => 0, 'total_cantidad' => 0, 'detalle' => array());				
			}
			
			//echo "para pago ".$pago[0]." tenía ".$array_resultados[$pago[0]]['total_euro']." euros y ".$array_resultados[$pago[0]]['total_cantidad']." de cantidad y me quedaré con";
			$array_resultados[$pago[0]]['total_euro']+=$pago[5];
			$array_resultados[$pago[0]]['total_cantidad']+=$pago[4];
			
			
			if(isset($array_resultados[$pago[0]]['detalle'][$pago[3]])) {
				$detalle = $array_resultados[$pago[0]]['detalle'][$pago[3]];
				$detalle['cantidad']+=$pago[4];
				$detalle['euros']+=$pago[5];
				$array_resultados[$pago[0]]['detalle'][$pago[3]] = $detalle;
			} else {
				$array_resultados[$pago[0]]['detalle'][$pago[3]]=array('pista' => $pago[3], 'cantidad' => $pago[4], 'euros' => $pago[5]);
			}
			
			//array_push($array_resultados[$pago[0]]['detalle'], );
			//echo $array_resultados[$pago[0]]['total_euro']." euros y ".$array_resultados[$pago[0]]['total_cantidad']." de cantidad<br>";
			
			
		}
		
		//print("<pre>");print_r($array_resultados);
		$fields=array('Fecha y hora', 'Concepto','Estado de pago', 'Forma de pago', 'Usuario', 'Cantidad',  'Descripcion');
		
		$contenido = $this->load->view('informes/cierre_dia', array('resultado' => $array_resultados, 'campos' => $fields, 'filtros' => array()), TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			//'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true),
			'main_content' => $contenido, 
			'form_name' => 'frmInforme', 
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		$this->load->view('main', $data);
	}





	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve los partes diarios de las clases de hoy
# -------------------------------------------------------------------
	function clases_dia($formato="")
	{
		$this->load->library('calendario');
		$this->load->model('Lessons_model', 'clases', TRUE);
		
		//$this->calendario->listCalendarByRange();
		$resultado = $this->calendario->listCalendarByRange(date('U', strtotime(date($this->config->item('date_db_format')))), date('U', strtotime(date($this->config->item('date_db_format')).' 23:59:59')));
		
		$clases = array();
		foreach($resultado['events'] as $clase ) {
			$datos = array();
			$datos['nombre'] = $clase[1];
			$datos['horario'] = date($this->config->item('reserve_hour_filter_format'), strtotime($clase[2])).' - '.date($this->config->item('reserve_hour_filter_format'), strtotime($clase[3]));
			$datos['pista'] = $clase[9];
			$datos['profesor'] = $clase[13];
			$datos['alumnos'] = array();
			$alumn = null;
			$alumn = $this->clases->get_AssitantsData(array('where' => 'lessons.id = '.$clase[0].' and lessons_assistants.status NOT IN (7,9)'));
			foreach($alumn as $alu) array_push($datos['alumnos'], $alu['user_desc']);
			
			//print('<pre>');print_r($alumn);print('</pre>');
			array_push($clases, $datos);
		}
		//print('<pre>');print_r($clases);print('</pre>');
		$contenido = '';
		foreach($clases as $clase) {
			if(count($clase['alumnos']) > 0) {
				$contenido .= $this->load->view('informes/clase_dia', array('datos' => $clase), TRUE);
				$contenido .= '<DIV style="page-break-after: always;"></DIV>';
			}
		}
		//echo $contenido.'<hr>';exit();
		if($contenido!='') {
			$this->load->helper(array('dompdf', 'file'));
			pdf_create($contenido, 'partes_clases_'.date('Ymd'));
		} else {
			redirect('informes');
			exit();
		}
		exit();
		print('<pre>');print_r($clases);

	}





# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve los partes diarios de las clases de hoy
# -------------------------------------------------------------------
	function clases_listado($curso='all', $formato="")
	{
		$this->load->library('calendario');
		$this->load->model('Lessons_model', 'clases', TRUE);
		//echo 'aa';
		//$resultado = $this->clases->get_data(array('where'=>'lessons.id_sport = 4'));
		$where='';
		if($curso!='') $where="lessons.id = ".$curso;
		$resultado = $this->clases->get_data(array('where'=>$where, 'orderby'=>'zz_sports.description ASC, lessons.description', 'orderbyway'=>'ASC'));
		//$resultado = $this->calendario->listCalendarByRange(date('U', strtotime(date($this->config->item('date_db_format')))), date('U', strtotime(date($this->config->item('date_db_format')).' 23:59:59')));
		//print('<pre>');print_r($resultado);print('</pre>');exit();
		$clases = array();
		foreach($resultado as $clase ) {
			$datos = array();
			$datos['nombre'] = $clase['description'];
			$datos['horario'] =$clase['rango_horas'];
			$datos['pista'] = $clase['court_desc'];
			$datos['profesor'] = $clase['profesor'];
			$datos['alumnos'] = array();
			$alumn = null;
			$alumn = $this->clases->get_AssitantsData(array('where' => 'lessons.id = '.$clase['id'].' and lessons_assistants.status NOT IN (7,9)'));
			foreach($alumn as $alu) array_push($datos['alumnos'], array('nombre' => $alu['user_desc'], 'nif' => $alu['nif'], 'nacimiento' => $alu['fecha_nacimiento'], 'telefono' => $alu['user_phone']));
			
			//print('<pre>');print_r($alumn);print('</pre>');exit();
			array_push($clases, $datos);
		}
		//print('<pre>');print_r($clases);print('</pre>');exit();
		if(isset($formato) && $formato=='excel') {
			$this->load->helper('export');
			listado_clases($clases);
			exit();
		} else {
			$contenido = '';
			foreach($clases as $clase) {
				if(count($clase['alumnos']) > 0) {
					$contenido .= $this->load->view('informes/listado_alumnos', array('datos' => $clase), TRUE);
					$contenido .= '<DIV style="page-break-after: always;"></DIV>';
				}
			}
			//echo $contenido.'<hr>';exit();
			if($contenido!='') {
				$this->load->helper(array('dompdf', 'file'));
				pdf_create($contenido, 'listado_clases_'.date('Ymd'));
			} else {
				redirect('informes');
				exit();
			}
		}
		
		
		exit();

	}





	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de reservas diarias
# -------------------------------------------------------------------
	function reserva_diaria($formato="")
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			$this->session->set_userdata('error_message', 'Acceso a pagina no permitida');
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		####################
		# FILTROS
		$campos_busqueda = $this->simpleSearchFields(array('sports'=>0, 'court_type'=>0, 'court'=>0, 'status'=>0, 'paymentway'=>0, 'date1' => date($this->config->item('reserve_date_filter_format')), 'date2' => date($this->config->item('reserve_date_filter_format')), 'hora1' => '00:00:00', 'hora2' => '23:59:00'));
		//print("<pre>");print_r($campos_busqueda);
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';
				//booking.id as id, id_booking, id_user, session, id_court, date as fecha, intervalo, status, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, meta.last_name as last_name, zz_booking_status.description as status_desc, zz_paymentway.description as paymentway_desc
		//print_r($this->reservas->get_global_list());
		$where_arr=array();

		$selected_date1=$this->input->post('date1');
		if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'));
		$selected_date2=$this->input->post('date2');
		if(!isset($selected_date2) || $selected_date2=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));

		if($selected_date1!="") array_push($where_arr, "`date` >= '".date($this->config->item('date_db_format'), strtotime($selected_date1))."'");
		if($selected_date2!="") array_push($where_arr, "`date` <= '".date($this->config->item('date_db_format'), strtotime($selected_date2))."'");
		
		$selected_time1=$this->input->post('hora1');
		if(!isset($selected_time1) || $selected_time1=="") $selected_time1='00:00:00';
		$selected_time2=$this->input->post('hora2');
		if(!isset($selected_time2) || $selected_time2=="") $selected_time2='23:59:00';

		if($selected_time1!="") array_push($where_arr, "`intervalo` >= '".$selected_time1."'");
		if($selected_time2!="") array_push($where_arr, "`intervalo` <= '".$selected_time2."'");
		
		$where=implode(' AND ', $where_arr);
		$order='courts.name, booking.date, booking.intervalo ';
		$order_way='asc';
		$records = $this->reservas->get_global_list($where, $order, $order_way, null);
	
		$resultado=array();
		//print("aaaa<pre>");
		//print_r($records['records']->result_array());
		$trans="";
		foreach ($records['records']->result() as $row) {
			if($trans != $row->id_transaction) {
				$info=$this->reservas->getBookingInfoById($row->id_transaction);
				//echo $info['inicio']; print_r($info); print_r($row); exit();
				//print("<pre>"); print_r($row);
				if($row->id_user) $usuario = $row->first_name." ".$row->last_name;
				elseif($row->user_desc) $usuario = $row->user_desc;
				else $usuario = "Desconocido";
				
				if($row->id_user) $telefono = $row->phone;
				else $telefono = $row->user_phone;
				
				if($row->id_paymentway == 0 ) $forma_pago = 'No pagado';
				else $forma_pago = $row->paymentway_desc;

				if($row->no_cost == 0 ) $no_cost = '';
				else $no_cost = $row->no_cost_desc;
				
				
				$resultado[] = array(
					$row->id_transaction,
					$row->court_name,
					$row->fecha,
					$info['inicio'],
					$info['fin'],
					$row->id_user,
					$usuario,
					$telefono,
					$info['intervals'],
					$forma_pago,
					$no_cost
					
				);
				$trans=$row->id_transaction;
			}
		}
		
		//print("<pre>");print_r($resultado);
		if($formato=="excel") {
			$this->output->set_header("Content-type: application/vnd.ms-excel");
			$this->output->set_header("Content-Disposition: attachment;filename=export_".time().".xls");
			
			$salida="";
			$salida='<table boder="1">'."\r\n";
			$salida.='<tr><td>Id</td><td>Pista</td><td>Fecha</td><td>Inicio</td><td>Fin</td><td>Id Usuario</td><td>Usuario</td><td>Telefono</td><td>Intervalos</td><td>Forma Pago</td><td>Sin Coste</td></tr>';
			foreach($resultado as $pago) {
				$salida.='<tr>'."\r\n";
				foreach($pago as $valor) $salida.='<td>'.$valor.'</td>'."\r\n";
				$salida.='</tr>'."\r\n";
			}
			$salida.='</table>';
			$this->output->set_output($salida); 
			return NULL;
		}
		//echo $salida;
		
		
		$array_resultados=array();
		
		# Post proceso el resultado para crear un array jerarquizado por niveles donde contenga las reservas por pista
		foreach($resultado as $reserva) {
			$total_euro=0;
			$total_cantidad=0;
			
			if(!isset($array_resultados[$reserva[1]])) {
				# Si no existe el elemento, es que es la primera vuelta que doy en esa pista
				$array_resultados[$reserva[1]]=array();				
			}
			
			array_push($array_resultados[$reserva[1]], array('id_transaction' => $reserva[0], 'id_user' => $reserva[5], 'user' => $reserva[6], 'phone' => $reserva[7], 'fecha' => $reserva[2], 'inicio' => $reserva[3], 'fin' => $reserva[4], 'intervalos' => $reserva[8], 'payment_way' => $reserva[9]));


			
			
		}
		
		//print("<pre>");print_r($array_resultados);
		$fields=array(htmlentities('Nº Socio'), 'Fecha','Inicio', 'Fin', 'Usuario', 'Telefono', 'Forma Pago');
		
		$contenido = $this->load->view('informes/reserva_diaria', array('resultado' => $array_resultados, 'campos' => $fields, 'filtros' => $campos_busqueda, 'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true)), TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'navigation' => $this->load->view('navigation', '', true),
			'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true),
			'main_content' => $contenido, 
			'form_name' => 'frmInforme', 
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		$this->load->view('main', $data);
	}









	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que devuelve listado de ocupación
# -------------------------------------------------------------------
	function reserva_ocupacion($formato="")
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		####################
		# FILTROS
		$campos_busqueda = $this->simpleSearchFields(array('sports'=>0, 'court_type'=>0, 'court'=>0, 'status'=>0, 'paymentway'=>0, 'date1' => date($this->config->item('reserve_date_filter_format')), 'date2' => date($this->config->item('reserve_date_filter_format'))));
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';
				//booking.id as id, id_booking, id_user, session, id_court, date as fecha, intervalo, status, id_paymentway, price, no_cost, no_cost_desc, user_desc, user_phone, booking.create_user as create_user, booking.create_time as create_time, booking.modify_user as modify_user, booking.modify_time as modify_time, courts.name as court_name, meta.first_name as first_name, meta.last_name as last_name, zz_booking_status.description as status_desc, zz_paymentway.description as paymentway_desc
		//print_r($this->reservas->get_global_list());
		$where_arr=array();

		$selected_date1=$this->input->post('date1');
		if(!isset($selected_date1) || trim($selected_date1)=="") $selected_date1=date($this->config->item('reserve_date_filter_format'));
		$selected_date2=$this->input->post('date2');
		if(!isset($selected_date2) || trim($selected_date2)=="") $selected_date2=date($this->config->item('reserve_date_filter_format'));
		$selected_hour1=$this->input->post('hora1');
		if(!isset($selected_hour1) || $selected_hour1=="") $selected_hour1='00:00:00';
		$selected_hour2=$this->input->post('hora2');
		if(!isset($selected_hour2) || $selected_hour2=="") $selected_hour2='23:59:00';

		$order='courts.name, booking.date, booking.intervalo ';
		$order_way='asc';

		$fecha1 = date($this->config->item('date_db_format'), strtotime($selected_date1));
		$fecha2 = date($this->config->item('date_db_format'), strtotime($selected_date2));
		$hora1 = date($this->config->item('hour_db_format'), strtotime($selected_hour1));
		$hora2 = date($this->config->item('hour_db_format'), strtotime($selected_hour2));
		$resultado = $this->reservas->get_complete_court_ocupation($fecha1, $fecha2, $hora1, $hora2);
	
		//print("<pre>");print_r($resultado);
		if($formato=="excel") {
			$this->output->set_header("Content-type: application/vnd.ms-excel");
			$this->output->set_header("Content-Disposition: attachment;filename=export_".time().".xls");
			
			$salida="";
			$salida='<table boder="1">'."\r\n";
			$salida.='<tr><td>Id</td><td>Pista</td><td>Horas totales</td><td>Total facturado</td><td>Facturable ( + sin coste)</td><td>Horas potenciales</td></tr>';
			foreach($resultado as $linea) {
				$salida.='<tr>'."\r\n";
				$salida.='<td>'.$linea['id'].'</td>'."\r\n";
				$salida.='<td>'.$linea['name'].'</td>'."\r\n";
				$salida.='<td>'.number_format($linea['total_horas'],1,',', '.').'</td>'."\r\n";
				$salida.='<td>'.number_format($linea['total_facturado'],2,',', '.').'</td>'."\r\n";
				$salida.='<td>'.number_format($linea['total_facturable'],2,',', '.').'</td>'."\r\n";
				$salida.='<td>'.number_format($linea['maximo_horas'],1,',', '.').'</td>'."\r\n";
				$salida.='</tr>'."\r\n";
			}
			$salida.='</table>';
			$this->output->set_output($salida); 
			return NULL;
		}
		//echo $salida;
		
		

		
		//print("<pre>");print_r($array_resultados);
		$fields=array( 'Pista','Horas', 'Ocupaci&oacute;n', 'Facturaci&oacute;n', 'Fact. Potencial');
		
		$contenido = $this->load->view('informes/reserva_ocupacion', array('resultado' => $resultado, 'campos' => $fields, 'filtros' => $campos_busqueda, 'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true)), TRUE);
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'search_fields' => $this->load->view('informes/search_fields', array('search_fields'=> $campos_busqueda, 'disabled' => ''), true),
			'main_content' => $contenido, 
			'form_name' => 'frmInforme', 
			'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		$this->load->view('main', $data);
	}










function simpleSearchFields($options=array())
	{
			$this->load->model('Reservas_model', 'reservas', TRUE);
			$this->load->model('Pistas_model', 'pistas', TRUE);

			if($this->redux_auth->logged_in()) {
				$profile=$this->redux_auth->profile();
				$user_group=$profile->group;
			}	else {
				redirect(site_url(), 'Location'); 
				exit();
			}

			#########################
			## CREACION DE FILTROS 
			######

			//$page_id=$this->app_common->get_page_id();
			//echo $page_id;
			
			/*
			$filtros=$this->session->userdata('filters');
			if(!isset($filtros)) $this->session->set_userdata('filters', array());	
			if(!isset($filtros[$page_id])) $filtros[$page_id]= array();
			*/
			$filter_array=array();
			
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_status=$this->input->post('status');
			$selected_paymentway=$this->input->post('paymentway');
			$selected_user=$this->input->post('user');
			$selected_no_cost=$this->input->post('no_cost');
			$selected_date1=$this->input->post('date1');
			
			//if(!isset($selected_date1) || $selected_date1=="") $selected_date1=date($this->config->item('reserve_date_filter_format'));
			//echo date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')))). " -1 month";
			//echo date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
			if(!isset($selected_date1) || $selected_date1=="") {
				if(!isset($options['date1']) || $options['date1']=="0") $selected_date1=date($this->config->item('reserve_date_filter_format'), strtotime(date($this->config->item('reserve_date_filter_format')). " -1 month"));
				else $selected_date1=$options['date1'];	// Valor por defecto pasado por parámetros
			}
			$selected_date2=$this->input->post('date2');
			if(!isset($selected_date2) || $selected_date2=="") {
				
				if(!isset($options['date1']) || $options['date1']=="0") $selected_date2=date($this->config->item('reserve_date_filter_format'));
				else $selected_date2=$options['date2'];	// Valor por defecto pasado por parámetros
			}
			$selected_hour1=$this->input->post('hora1');
			if(!isset($selected_hour1) || $selected_hour1=="") {
				if(!isset($options['hora1']) || $options['hora1']=="0") $selected_hour1='00:00:00';
				else $selected_hour1=$options['hora1'];	// Valor por defecto pasado por parámetros
			}
			$selected_hour2=$this->input->post('hora2');
			if(!isset($selected_hour2) || $selected_hour2=="") {
				if(!isset($options['hora2']) || $options['hora2']=="0") $selected_hour2='23:59:00';
				else $selected_hour2=$options['hora2'];	// Valor por defecto pasado por parámetros
			}
			
			
			# Filtro de DEPORTE
			if(!isset($options['sports']) || $options['sports']=="1") {
				$options=$this->reservas->getSportsArray();
				if(isset($options) && count($options)!=1) {
					$equipo=array('name' => 'sports', 'desc' => $this->lang->line('sport'), 'default' => $selected_sport, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'sports', 'type' => 'select', 'value' => $options);
					array_push($filter_array, $equipo);
				}
			}
			
			


			# Filtro de TIPO DE PISTA
			
			if(!isset($options['court_type']) || $options['court_type']=="1") {
				$options=$this->pistas->getAvailableCourtsTypesArray($selected_sport);
				if(isset($options) && count($options)!=1) {
					$tipopista=array('name' => 'court_type', 'desc' => $this->lang->line('court_type'), 'default' => $selected_court_type, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'court_type',  'type' => 'select', 'value' => array('' => $options));
					array_push($filter_array, $tipopista);
				}
			}
			
			


			# Filtro de PISTAS
			if(!isset($options['court']) || $options['court']=="1") {
				$options=$this->pistas->getAvailableCourtsArray($selected_sport,$selected_court_type);
				if(isset($options) && count($options)!=1) {
					$pista=array('name' => 'court', 'desc' => $this->lang->line('court'), 'default' => $selected_court, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'court', 'type' => 'select', 'value' => array('' => $options));
					array_push($filter_array, $pista);
				}
			}


			
			# Filtro de ESTADO DE RESERVA
			if(!isset($options['status']) || $options['status']=="1") {
				$options=$this->reservas->getReserveStatusArray();
				if(isset($options) && count($options)!=1) {
					$equipo=array('name' => 'status', 'desc' => $this->lang->line('reserve_status'), 'default' => $selected_status, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'status', 'type' => 'select', 'value' => $options);
					array_push($filter_array, $equipo);
				}
			}
			

			
			# Filtro de FORMA DE PAGO
			if(!isset($options['paymentway']) || $options['paymentway']=="1") {
				$options=$this->reservas->getPaymentWaysArray();
				if(isset($options) && count($options)!=1) {
					$equipo=array('name' => 'paymentway', 'desc' => $this->lang->line('payment_ways'), 'default' => $selected_paymentway, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'paymentway', 'type' => 'select', 'value' => $options);
					array_push($filter_array, $equipo);
				}
			}
			


			# Filtro de FECHA
			if(!isset($options['date']) || $options['date']!="0") {
				$fecha=array('name' => 'date1', 'desc' => $this->lang->line('date1'),  'default' => $selected_date1, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'date1', 'type' => 'date');
				array_push($filter_array, $fecha);
				# Filtro de FECHA
				$fecha=array('name' => 'date2', 'desc' => $this->lang->line('date2'),  'default' => $selected_date2, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'date2', 'type' => 'date');
				array_push($filter_array, $fecha);
			}



			# Filtro de FECHA
			if(!isset($options['hora1']) || $options['hora1']!="0" || !isset($options['hora2']) || $options['hora2']!="0") {
				$fecha=array('name' => 'hora1', 'desc' => $this->lang->line('hora1'),  'default' => $selected_hour1, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'hora1', 'type' => 'time');
				array_push($filter_array, $fecha);
				# Filtro de FECHA
				$fecha=array('name' => 'hora2', 'desc' => $this->lang->line('hora2'),  'default' => $selected_hour2, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'hora2', 'type' => 'time');
				array_push($filter_array, $fecha);
			}


			
			# Completo la informacion de los filtros en sesion
			/*
			$this->session->set_userdata('filters', $filtros);	
			//print("<pre>"); print_r($filter_array);
			$filtros2=$this->session->userdata('filters');print("<pre>"); print_r($filtros2);
			*/
			
			return $filter_array;
		}
		
		
	function email()
	{
		$this->load->library('ckeditor');
		$this->load->helper('ckeditor');
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		
		$data=array(
			'meta' => $this->load->view('meta', array('extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $this->load->view('editor/main', '', true),
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		
		$this->load->view('main', $data);
	}
	
	

function jquery()
	{
		
		$this->load->helper('jqgrid');
		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'fecha',index:'fecha', width:12, align:'center'},
						   		{name:'intervalo', index:'intervalo', width:10, align:'center'},
						   		{name:'court_name',index:'court_name', width:20, align:'center'},
						   		{name:'user_desc', index:'user_desc', width:30, align:'center'},
						   		{name:'user_phone', index:'user_phone', width:12, align:'center'},
						   		{name:'status_desc', index:'status_desc', width:15, align:'center'},
						   		{name:'paymentway_desc', index:'paymentway_desc', width:15, align:'center'},
						   		{name:'price', index:'price', width:10, align:'center'},
						   		{name:'no_cost',index:'no_cost', width:10, align:'center', sortable:false}";
		$colnames = "'Id','Fecha','Hora','Pista','Usuario', 'Telefono', 'Estado', 'Forma de pago', 'Precio', 'Gratis'";
		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "informes/browse", 
				'title' => 'Listado de reservas', 
				'default_orderfield' => 'date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'row_list_options' => '10,20,50',
		);
		
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE, 'extra' => link_tag(base_url().'css/informes.css')), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => '<div style="position:relative; width: 960px; height: 660px;">'.jqgrid_creator($para_grid).'</div>',
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		//if($this->session->userdata('logged_in')) $page='reservas_user_index';
		//if($this->redux_auth->logged_in()) $data['page']='reservas_gest/index';

		
		$this->load->view('main', $data);
	}
		
public function browse ()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);

		//$req_param = array ();
		$req_param = array (

				"orderby" => $this->input->post( "sidx", TRUE ),
				"orderbyway" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"where" => ''
				/*
				"sort_by" => $this->input->post( "sidx", TRUE ),
				"sort_direction" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
				"search_field_1" => "msg_to",
				"search_field_2" => "msg_in_inbox",
				"user_id" => $this->session->userdata('user_id')
				*/
		);

		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->reservas->get_data($req_param,"all")->result_array());
		$data->total = ceil ($data->records /10 );
		$records = $this->reservas->get_data ($req_param, 'none')->result_array();
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}
		
}

/* End of file reports.php */
/* Location: ./system/application/controllers/reports.php */