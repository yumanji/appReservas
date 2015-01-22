<?php

class Reservas extends Controller {
 /*
 # CONTENIDO
 #
 # index()
 # preselect()
 # simpleSearchFields()
 # confirm() *
 # pay() *
 # search2()
 # confirm2()
 # extras()
 # pay2()
 # resume()
 # tooltip_info()
 # clean_block_reserves()
 
 */
	function Reservas()
	{
		parent::Controller();	
		$this->lang->load('reservas');
		$this->load->library('user_agent');

		
	}
	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de búsqueda de pista
# -------------------------------------------------------------------

	function index($dummy = NULL)
	{

		if(!isset($dummy)) {
			redirect(site_url('reservas/index/'.time()), 'Location'); 
			exit();
		}

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			$user_name=$profile->username;
		}	else {
			$user_id=0;
			$user_group=9;
			$user_name=$this->lang->line('anonymous_user');
		}
		

		# Vacio el array de elementos seleccionados		
		$this->session->set_userdata('bookingInterval', array());		


		# Definición de barra de menus
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);
		
		$validation_script="";
		$disabled="";
		$filtro=$this->config->item('court_required');
		if(isset($filtro[$user_group]) && $filtro[$user_group]) {
			$validation_script.="if(document.getElementById('court').value=='' ) {  alert('".$this->lang->line('court_required_alert')."'); document.getElementById('frmReserva').action='".site_url('reservas')."'; return; }"."\r\n";
			if(!$this->input->post('court')) $disabled = " disabled ";
		} else $validation_script='';
		
		$filtro=$this->config->item('date_required');
		if($filtro[$user_group]) {
			$validation_script.="if(document.getElementById('date').value=='' ) {  alert('".$this->lang->line('date_required_alert')."'); document.getElementById('frmReserva').action='".site_url('reservas')."'; return; }"."\r\n";
			//if(!$this->input->post('date')) $disabled = " disabled ";
		} else $validation_script='';
		
		$data=array(
			'menu' => $this->load->view('menu', $menu, true),
			'user_name' => $user_name,
			'search_fields' => $this->load->view('reservas/search_fields2', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true),
			//'result' => $this->load->view('reservas/simple_result', array('availability' => $availability_array, 'user_id' => $user_id, 'filters' => $this->load->view('reservas/search_fields', array('search_fields'=> $this->simpleSearchFields()), true)), true),
			'validation_script' => $validation_script,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
		# En función del IE, cargo una pantalla u otra
		if(in_array($this->agent->browser(), $this->config->item('special_browser'))) $data['search_fields'] = $this->load->view('reservas/search_fields2_iexplorer', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true);
		else $this->load->view('reservas/search_fields2', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true);
		
		$added = '';
		if(in_array($this->agent->browser(), $this->config->item('special_browser'))) $added = array('added_footer' => 'Pagina adaptada a IE');

		$extra = link_tag(base_url().'css/prettyPhoto.css')."\r\n".'<script src="'.base_url().'js/jquery.prettyPhoto.js" type="text/javascript"></script>';

		if($this->redux_auth->logged_in()) {
			$data['meta']=$this->load->view('meta', array('extra' => $extra), true);
			$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
			$data['footer']=$this->load->view('footer', $added, true);
		} else {
			$data['meta']=$this->load->view('meta_index', array('extra' => $extra), true);
			$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
			$data['footer']=$this->load->view('footer_index', $added, true);
		}
		
		$data['page']='reservas/search';

    $this->load->view('main', $data);
    //print("<pre>");print_r($this->session);
	}



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de preseleccion de pista
# -------------------------------------------------------------------

	function preselect($usuario, $selection, $dummy)
	{
		#Tercer parámetro no vale para nada.. es para evitar el caché de IE.
		
		$debug = FALSE;
		# Función que marca como preseleccionados los elementos seleccionados por el usuario
		# Será solo llamado por AJAX
		# Parámetros de entrada: usuario e id de lo seleccionado
 
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->library('booking');


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
		}	else {
			$user_group=9;
		}
		
		#Comprobar si la seleccion realizada está libre o no... si está libre, reservar... SI no está libre, y el usuario de reserva es el actual, deseleccionar
			
		$partes=explode('-', $selection);
		$this->reservas->court=$partes[0];
		$deporte = $this->pistas->getCourtSport($partes[0]);
		$this->reservas->date=date($this->config->item('date_db_format'), $partes[1]);
		$this->reservas->intervalo=date($this->config->item('hour_db_format'), $partes[1]);
		$reserve_interval = $this->pistas->getCourtInterval($this->reservas->court);
		//$this->reservas->price=5.3;

		$this->reservas->id_user=$usuario;
		$this->reservas->create_user=$usuario;
		//$nivel_usuario = $this->usuario->getUserGroup($usuario);
		$nivel_usuario = $user_group;
		$this->reservas->sesion = $this->session->userdata('session_id');


		$this->app_common->get_court_availability($this->reservas->court, $this->reservas->date);
		$availability_array = $this->reservas->availability;
		$encontrado = FALSE; $estado_anterior = 0; $id_anterior = '';
		$celdas = array();
		
		# Defino el numero de celdas conjuntas que se seleccionan en base al nivel del usuario
		$conjunto_celdas_arr = $this->config->item('reserve_minimum_intervals');
		$conjunto_celdas = $conjunto_celdas_arr[$user_group];
		if(!isset($conjunto_celdas) || $conjunto_celdas == '') $conjunto_celdas = 9;
		
		
		//echo '-------'.$conjunto_celdas;
		if(!isset($conjunto_celdas) || $conjunto_celdas == '' || $conjunto_celdas < 1) $conjunto_celdas = 1;
		$contador = 0;
		
		foreach($availability_array as $intervalo => $datos) {
			if($debug) echo '<br>Cont: '.$contador.' - Estado anterior '.$estado_anterior.' - '.$datos[0].' - '.$id_anterior.' - '.$this->session->userdata('idTransaction');
			if(($contador % $conjunto_celdas == 0 && ($estado_anterior != 0 || ($estado_anterior == 0 && $id_anterior == $this->session->userdata('idTransaction')))) || ($estado_anterior == 0 && $id_anterior != $this->session->userdata('idTransaction'))) {
				# Si comienzo un conjunto de celdas nuevo, reinicio el array de celdas que se seleccionan y el contador de elementos que usamoos para hacer la operacio aritmetica 'modulo'
				$celdas = array();
				$contador = 0;
				$encontrado = FALSE;
				if($debug) echo '<br>Comienzo conjunto en '.$datos[0];
			}
			
			if($intervalo == $selection ) {
				$encontrado = TRUE;
				if($debug) echo '<br>Se ha encontrado el intervalo seleccionado en este conjunto!! '.$intervalo . '  -  '. $this->reservas->intervalo;
			}
			
			array_push($celdas, $intervalo);
			
			if(count($celdas) == $conjunto_celdas && $encontrado) {
				if($debug) echo '<br>Trabajo hecho';
				break;
			}
			
			$estado_anterior = $datos[1];
			$id_anterior = $datos[2];
			$contador++;
		}
		
		# Grabo copia de las celdas vinculadas a esta reserva para, en caso de necesitar desactivarlo todo, tener guardadas cuales eran
		$persistent_celdas = $celdas;

		//print("aa<pre>"); print_r($this->reservas->getBookingInfoBySession($this->reservas->sesion));print("</pre>");
		$ok_total = 1;
		$reservas = array();
		
		if($debug) { print("aa<pre>"); print_r($celdas); }
		# Si no he encontrado correspondencia.. fallo
		if(count($celdas) != $conjunto_celdas || !$encontrado) { 
			$celdas = array(); 
			$ok_total = 0; 
			if($debug) {
				echo 'count celdas: '.count($celdas).' y conjunto de celdas: '.$conjunto_celdas;
				if($encontrado) echo 'encontrado'; else echo 'no encontrado';
			}
		}


		# Comprobación (si corresponde) del número máximo de reservas diarias por usuario

		$valida_por_maxima = $this->booking->checkMaximumBookingPerDayUserSport($this->reservas->date, $partes[0], $usuario, 1);
		if($debug) { if($valida_por_maxima) echo 'reserva valida respecto a maximos diarios de reserva'; else echo 'reserva INVALIDA respecto a maximos diarios de reserva';  }
		if(!$valida_por_maxima) {
			$celdas = array(); 
			$ok_total = 0;
		}
		
		# Fin de la comprobacicón de reservas máximas por usuario/dia/deporte
		
		if($debug) { print("bbbb<pre>"); print_r($celdas); }
		//if($debug) print_r($availability_array);
		if($debug) print("</pre>"); //exit();
		
		# Recorro los intervalos para sacar el máximo y el mínimo horario
		$inicio_seleccion = ''; $fin_seleccion = '';
		foreach($celdas as $interv) {
			$partes2=explode('-', $interv);
			$int_selecc = date($this->config->item('hour_db_format'), $partes2[1]);
			$tiempo = explode(':', $int_selecc);
			$horas = $tiempo[0];
			$minutos = $tiempo[1];
			
			$suma_minutos = $reserve_interval + $minutos;
			//relleno los campos necesarios para chequear disponibilidad
			$int_selecc_plus = date($this->config->item('hour_db_format'),mktime($horas,$suma_minutos,0,1,1,1998));
			//if($debug) echo '<br> horas: '.$horas.'   minutos: '.$suma_minutos.'  hora inicio: '.$int_selecc.' hora plus: '.$int_selecc_plus;
			if($inicio_seleccion == '' || $inicio_seleccion > $int_selecc) $inicio_seleccion = $int_selecc;
			if($fin_seleccion == '' || $fin_seleccion < $int_selecc_plus) $fin_seleccion = $int_selecc_plus;
			
		}
		
		if($debug) echo '<br>Rango de la seleccion hecha: '.$inicio_seleccion.' - '.$fin_seleccion;
		
		# Recorro los intervalos preseleccionados para ver si todos están disponibles. Si no, todos a cero
		foreach($celdas as $interv) {
			if($debug) echo '<br>Para el intervalo '.$interv.' el ok total es '.$ok_total;
			# Vuelvo a grabar las propiedades del objeto reservas con el intervalo de estudio
			$partes2=explode('-', $interv);
			$this->reservas->court = $partes2[0];
			$this->reservas->date = date($this->config->item('date_db_format'), $partes2[1]);
			$this->reservas->intervalo = date($this->config->item('hour_db_format'), $partes2[1]);
			
			
			if($this->reservas->checkExactAvailability()) {
				# Si está libre, pido precio
				$this->reservas->getPrice();
				
				#Compruebo los extras
				$extras = 0;
				$extras = $this->booking->getExtra($this->session->userdata('idTransaction'), $this->reservas->court.'-'.strtotime($this->reservas->date.' '.$this->reservas->intervalo));
				if($debug) echo "\r\n".'<br>el extra es '.$extras.' ';
				$this->reservas->price_supl1 = $extras; 
				$this->reservas->price = $this->reservas->price + $extras;
				
				if($debug) echo '<br>el intervalo '.$interv.' tiene como precio '.$this->reservas->price_court;
				$ok = 1;
				# Recupero información de las reservas con esta sesion para ver si estoy pinchando una consecutiva
				if(!isset($info)) $info = $this->reservas->getBookingInfoById($this->session->userdata('idTransaction'));
				
				//print("<pre>"); print_r($info);print("</pre>");
				
				if(isset($info) && is_array($info) && count($info)>0 && $info['status']<'7') {
					if($debug) echo '<br>hay una reserva activa con esta sesion '.$this->session->userdata('idTransaction');
					$sel_part = explode("-", $interv);
					if ($sel_part[0] != $info['id_court']) {
						$ok = 0;
						$ok_total = 0;
						if($debug) echo '<br>el intervalo '.$interv.' tiene pista diferente de las otras reservas activas:'.$sel_part[0].' - '.$info['id_court'];
						//log_message('debug',var_export($info, TRUE));
						//log_message('debug', 'Pista diferente');
					}
					$sel_fecha=date($this->config->item('date_db_format'), $sel_part[1]);
					//echo $sel_fecha;
					if ($sel_fecha != $info['date']) {
						$ok = 0;
						$ok_total = 0;
						if($debug) echo '<br>el intervalo '.$interv.' tiene fecha diferente de las otras reservas activas:'.$sel_fecha.' - '.$info['date'];
						//log_message('debug',var_export($info, TRUE));
						//log_message('debug', 'Fecha diferente');
					}
					$sel_hora=date($this->config->item('hour_db_format'), $sel_part[1]);
					$sel_hora_ext=date($this->config->item('hour_db_format'), $sel_part[1]+($reserve_interval * 60));
					//echo $sel_hora;
					
					
					
					if($fin_seleccion != $info['inicio'] && $inicio_seleccion != $info['fin'] && $inicio_seleccion != $info['inicio'] && $fin_seleccion != $info['fin']) {
						if($debug) echo '<br>el intervalo '.$interv.' tiene un horario que no está pegado a alguna de las celdas ya reservadas:'.$fin_seleccion.' - '.$info['inicio'].' ---- '.$inicio_seleccion.' - '.$info['fin'];
						$ok = 0;
						$ok_total = 0;
						//log_message('debug',var_export($info, TRUE));
						//log_message('debug', 'Horas diferentes.. la reserva iba de '.$info['inicio'].' a '.$info['fin'].' y seleccioné '.$sel_hora);
					}
				# Cierro el IF de la comprobación de $info
				}
				
				if($debug) echo '<br>el intervalo '.$interv.' tiene un valor de ok: '.$ok.' y el total: '.$ok_total;
				//echo "<br> OK: ".$ok."<br>";
				if($ok) {
				
					if(isset($this->reservas->price_court)) {
						if($debug) echo '<br>el intervalo '.$interv.' intento reservarlo';
						# Si está libre, intento seleccionarlo
						$this->reservas->status=5;
						//$this->reservas->status=0;	# Marcamos el status como '0' para que solo lo marque en sesion, pero no guarde la reserva
						if($this->reservas->bookingInterval()) {
							if($debug) echo '<br>el intervalo '.$interv.' ha sido reservado satisfactoriamente';
							# Si lo he reservado, lo marco así
							$estilo="selected";
							$estado=1; // Lo marco como reservado		
							//if($this->reservas->price_court == 0) $this->reservas->price_court = 0.01;
							if($this->reservas->price == 0) $this->reservas->price = 0.01;

							//print_r($this->session->flashdata('bookingInterval'));			
							//exit('A');
						} else {
							
							$ok_total = 0;
							if($debug) echo '<br>el intervalo '.$interv.' NO ha podido ser reservado';
							# Si no he podido reservarlo, compruebo si aún está disponible.. 
							# para saber si falló por estar ya ocupado o porque falló la reserva
							if($this->reservas->checkExactAvailability()) {
								if($debug) echo '<br>el intervalo '.$interv.' NO se ha reservado pese a estar aún libre';
								$estilo="free";
								$estado=1; // Lo marco como libre
								$this->reservas->price=$this->reservas->price*(-1);
								$this->reservas->price_court=$this->reservas->price_court*(-1);
								$this->reservas->price_light=$this->reservas->price_light*(-1);
							} else {
								if($debug) echo '<br>el intervalo '.$interv.' NO se ha reservado porque ya se había reservado por otro sitio';
								$estilo="full";
								$estado=0; // Ya estaba reservado						
								$this->reservas->price=0;
								$this->reservas->price_court=0;
								$this->reservas->price_light=0;
							}
						}
					} else {
						
						if($debug) echo '<br>el intervalo '.$interv.' no tenía precio definido';
						
						$ok_total = 0;
						
						$estilo="free";
						$estado=1; // Ya estaba reservado
						$this->reservas->price=0;	
						$this->reservas->price_court=0;	
						$this->reservas->price_light=0;
					}	
					
				} else {
					
					if($debug) echo '<br>el intervalo '.$interv.' no era contiguo a las anteriores hechas con esta sesion';
					
					# Si la reserva no es contigua a las anteriores
					$ok_total = 0;
					
					$estilo="free";
					$estado=1; // Esta libre
					$this->reservas->price=0;	
					$this->reservas->price_court=0;	
					$this->reservas->price_light=0;
				}			
						
			} else {
				//$ok_total = 0;
				if($debug) echo '<br>el intervalo '.$interv.' no estaba disponible';
				
				if($this->reservas->eraseInterval()) {
					if($debug) echo '<br>el intervalo '.$interv.' ha sido borrado satisfactoriamente';
					$estilo="free";
					$estado=1; // Ya estaba reservado
					$this->reservas->getPrice();
					$this->reservas->price=$this->reservas->price*(-1);
					$this->reservas->price_court=$this->reservas->price_court*(-1);
					$this->reservas->price_light=$this->reservas->price_light*(-1);
				} else {
					if($debug) echo '<br>el intervalo '.$interv.' ha quedado marcado como reservado por otro';
					$estilo="full";
					$estado=0; // Ya estaba reservado				
					$this->reservas->price=0;
					$this->reservas->price_court=0;
				}
			} // Fin del if-else que comprueba si la celda está disponible
		
				array_push($reservas ,
									array(
										'estado' => $estado,
										'estilo' => $estilo,
										//'coste' => number_format($this->reservas->price_court, 2),
										'coste' => number_format($this->reservas->price, 2),
										'id' => $interv,
										'luz' => number_format($this->reservas->price_light, 2)
									)
							);		
		
		}	// Fin del foreach que recorre los intervalos preseleccionados

		if($debug) { print("Celdas antes de borrar<pre>"); print_r($celdas); }
		if(count($celdas) == 0) $celdas = $persistent_celdas;
		if($debug) { print("Celdas antes de borrar (2)<pre>"); print_r($celdas); }
		
		# Si el estado de alguna de las reservas era 0 ($ok_total = 0;) recorro el array para borrar las anteriores
		if($ok_total == 0) {
			# Vacio el array de reservas a enviar por XML
			$reservas = array();
			
			foreach($celdas as $interv) {
				
				# Vuelvo a grabar las propiedades del objeto reservas con el intervalo de estudio
				$partes3=explode('-', $interv);
				$this->reservas->court=$partes3[0];
				$this->reservas->date=date($this->config->item('date_db_format'), $partes3[1]);
				$this->reservas->intervalo=date($this->config->item('hour_db_format'), $partes3[1]);

				$this->reservas->eraseInterval();
				
					$estilo="free";
					$estado=1; // Ya estaba reservado
					//$this->reservas->getPrice();
					$this->reservas->price=0;
					$this->reservas->price_court=0;
					$this->reservas->price_light=0;

				

				# Relleno el array de resultados
				array_push($reservas ,
									array(
										'estado' => $estado,
										'estilo' => $estilo,
										//'coste' => number_format($this->reservas->price_court, 2),
										'coste' => number_format($this->reservas->price, 2),
										'id' => $interv,
										'luz' => number_format($this->reservas->price_light, 2)
									)
							);		

				
			}
			
		}
		if($debug) { print("<pre>"); print_r($reservas);print("</pre>"); exit();}
		$page ="reservas/preselect";
		$data=array(
							'reservas' => $reservas,
							);
      $this->load->view($page, $data);

	}



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de generacion de los filtros
# -------------------------------------------------------------------
	function simpleSearchFields()
	{
			$this->load->model('Reservas_model', 'reservas', TRUE);
			$this->load->model('Pistas_model', 'pistas', TRUE);


			if($this->redux_auth->logged_in()) {
				$profile=$this->redux_auth->profile();
				$user_group=$profile->group;
			}	else $user_group=9;

			#########################
			## CREACION DE FILTROS para pantalla de busqueda sencilla
			######
			# Filtro de deportes

			$filter_array=array();
			$selected_sport=$this->input->post('sports');
			$selected_court_type=$this->input->post('court_type');
			$selected_court=$this->input->post('court');
			$selected_date=$this->input->post('date');
			if(!isset($selected_date) || $selected_date=="") $selected_date=date($this->config->item('reserve_date_filter_format'));
			
			# Filtro de DEPORTE
			$options=$this->reservas->getSportsArray();
			$equipo=array('name' => 'sports', 'desc' => $this->lang->line('sport'), 'default' => $selected_sport, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'sports', 'onchange' => 'document.getElementById(\'frmReserva\').submit();', 'type' => 'select', 'value' => $options);
			if(count($options)==1) {
				$equipo['type']='hidden';
				if(isset($options)) foreach($options as $code => $value) $equipo['value']=$code;
				
			}
			array_push($filter_array, $equipo);
			


			# Filtro de TIPO DE PISTA
			
			$tipopista=array('name' => 'court_type', 'desc' => $this->lang->line('court_type'), 'default' => $selected_court_type, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'court_type', 'onchange' => 'document.getElementById(\'frmReserva\').submit();', 'type' => 'select', 'value' => array('' => 'Seleccione Tipo'));
			if($this->config->item('sport_required') && (!isset($selected_sport) || $selected_sport=='')) $tipopista['enabled'] = FALSE;
			else {
				$options=$this->pistas->getAvailableCourtsTypesArray($selected_sport);
				if(count($options)==1) {
					$tipopista['type']='hidden';
					foreach($options as $code=>$value) $tipopista['value']=$code;				
				} else $tipopista['value']=$options;
			}
			array_push($filter_array, $tipopista);
			
			


			# Filtro de PISTAS
			$pista=array('name' => 'court', 'desc' => $this->lang->line('court'), 'default' => $selected_court, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'court', 'onchange' => 'document.getElementById(\'frmReserva\').submit();', 'type' => 'select', 'value' => array('' => 'Seleccione Pista'));
			if(($this->config->item('sport_required') && (!isset($selected_sport) || $selected_sport=='')) || ($this->config->item('courtype_required') && (!isset($selected_court_type) || $selected_court_type==''))) $pista['enabled'] = FALSE;
			else {
				$options=$this->pistas->getAvailableCourtsArray($selected_sport,$selected_court_type);
				if(count($options)==1) {
					$pista['type']='hidden';
					foreach($options as $code=>$value) $pista['value']=$code;				
				} else $pista['value']=$options;
				
			}
			array_push($filter_array, $pista);
			


			# Filtro de FECHA
			$fecha=array('name' => 'date', 'desc' => $this->lang->line('date'), 'maxdays' => 2, 'default' => $selected_date, 'visible' => TRUE, 'enabled'=> TRUE, 'id' => 'date', 'type' => 'date');
			//if($this->config->item('sport_required') && (!isset($selected_sport) || $selected_sport=='')) $fecha['enabled'] = FALSE;
			//if($this->config->item('courtype_required') && (!isset($selected_court_type) || $selected_court_type=='')) $fecha['enabled'] = FALSE;
			//$filtro=$this->config->item('court_required');
			//if($filtro[$user_group] && (!isset($selected_court) || $selected_court=='')) $fecha['enabled'] = FALSE;
			$filtro=$this->config->item('max_search_days');
			if(isset($filtro[$user_group]) && $filtro[$user_group]!="") $fecha['maxdays'] = $filtro[$user_group];
			array_push($filter_array, $fecha);


			/*
			if(count($options)>1) $sports_options= $this->lang->line('sport').": ".form_dropdown('sports', $options, $this->input->post('sports'), $js);
			else foreach($options as $code=>$value) $sports_options= form_hidden('sports', $code);
			$search_fields=array($sports_options);		
			
			if(($this->config->item('sport_required') && $this->input->post('sports')!="") || !$this->config->item('sport_required')) {
				# Filtro de tipo de pista
				$options=$this->pistas->getAvailableCourtsTypesArray($this->input->post('sports'));
	      $js = 'id="court_type" onChange="document.getElementById(\'frmReserva\').submit();"';
				if(count($options)>1) $court_type_options= $this->lang->line('court_type').": ".form_dropdown('court_type', $options, $this->input->post('court_type'), $js);
				else foreach($options as $code=>$value) $court_type_options= form_hidden('court_type', $code);
				array_push($search_fields,$court_type_options);		

				if(($this->config->item('courtype_required') && $this->input->post('court_type')!="") || !$this->config->item('courtype_required')) {
					# Filtro de pista si viene el tipo de pista filtrado
					$options=$this->pistas->getAvailableCourtsArray('',$this->input->post('court_type'));
		      $js = 'id="court" ';
					if(count($options)>1) $court_options= $this->lang->line('court').": ".form_dropdown('court', $options, $this->input->post('court'), $js);
					else foreach($options as $code=>$value) $court_options= form_hidden('court', $code);
					array_push($search_fields,$court_options);
					
					# Campo de filtro de tipo fecha en el futuro
					$data = array('name'=> 'date', 'id'=> 'date', 'value'=> date('Y-m-d'));
					array_push($search_fields, $this->lang->line('date').": ".form_input($data));
					
					# Campo de filtro de hora aproximada en que quiero pista (combo en el futuro)
					$data = array('name'=> 'time', 'id'=> 'time', 'value'=> date('H:30'));
					array_push($search_fields, $this->lang->line('init_time').": ".form_input($data));
					
				} 
				}*/
			
			
			return $filter_array;
		}



	function confirm($id_transaction = NULL)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'redux', TRUE);

		
		# Si no está definida la transacción, vuelvo a la página de inicio
		if(!isset($id_transaction)) {
			redirect(site_url(), 'Location'); 
			exit();
		}
		
		//$session=$this->session->userdata('session_id');
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_level=$profile->group;
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}


		//print("<pre>"); print_r($this->reservas->getBookingInfoBySession($session));
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);print("</pre>");exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_flashdata('message', '<p class="error">'.$this->lang->line('booking_lost_alert').'</p>');
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}
		//print_r($this->redux_auth->profile());

		
		# opciones del menu
		$menu=array('menu' => $this->app_common->get_menu_options());
		
		# Recoge las formas de pago disponibles para este nivel de usuario
		$this->load->model('Payment_model', 'pagos', TRUE);
		$paymentMethods=$this->pagos->getPaymentMethodsByUser($user_level);
		# Especificaciones de métodos de pago de esta pantalla
		//$paymentMethods['creditcard'] = TRUE;
		//s$paymentMethods['tpv'] = FALSE;
		if($user_level<=7) {
			$paymentMethods['tpv'] = FALSE;
		}	
		if($user_level==7) {
			$paymentMethods['cash'] = FALSE;
			$paymentMethods['creditcard'] = FALSE;
		}	
		
		//$paymentMethods['reserve'] =FALSE;
		//$paymentMethods['prepaid'] = FALSE;
		//print_r($paymentMethods);
		
		# Variable que habilita o no el marcar la reserva sin coste
		$no_cost=0;
		if($user_level<=7) {
			$no_cost=1;			
		}
		$filtro=$this->config->item('no_cost_permission');
		if(isset($filtro[$user_level])) $no_cost = $filtro[$user_level];
		
		# Variable que habilita o no el reservar por otros usuarios
		$alt_reserve=0;
		if($user_level<=7) $alt_reserve=1;
		
		# Variable que habilita o no el solicitar los datos del usuario para la reserva
		$user_data=0;
		if(!$this->redux_auth->logged_in()) $user_data=1;
		
		# Cargo la vista de formas de pago
		$paymnet_content= $this->load->view('reservas/pago', array('methods' => $paymentMethods, 'transaction_id' => $id_transaction, 'info' => $info), true);
		//print("<pre>");print_r($menu);print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);
		
		if($user_level < 7) {
			$main_content= $this->load->view('reservas/confirm_gestion', array('info' => $info, 'no_cost' => $no_cost, 'pay' => 1, 'pay_content' => $paymnet_content, 'form' => 'frmReserva', 'transaction_id' => $id_transaction), true);
		} 
		
		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $main_content,
			//'page'=>'reservas/confirm',
			'info' => $info,
			'no_cost' => 1,
			'reserve' => 1,
			'pay' => 1,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

      $this->load->view('main', $data);
	}




	####################
	# Funcion que genera la pantalla en la que se selecionan los extras de la reserva (luz, etc..), 
	# se selecciona si será un partido compartido y quien es el titular de la reserva.
	####################
	
	function extras($dummy, $id_transaction)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);


		$session=$this->session->userdata('session_id');
		$idt=$this->session->userdata('idTransaction');

		
		//echo $idt.'---';
		/*
		if($idt != $id_transaction) {
			echo "ERROR!! Acceso fraudulento!";
			exit();
		}
		*/
		$id_transaction = $idt;
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_level=$profile->group;
			$user_name=$profile->username;
		}	else {
			$user_id=0;
			$user_level=9;
			$user_name=$this->lang->line('anonymous_user');
		}
		
		/*
		if($light=="true") $this->reservas->setLight($this->session->userdata('idTransaction'),$price_light);
		*/
		
		//echo $this->session->userdata('idTransaction');
		//print("<pre>"); print_r($this->reservas->getBookingInfoBySession($session));
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);print("</pre>");exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_userdata('error_message', $this->lang->line('session_lost_alert'));
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}
		
		############
		# Array de opciones de la reserva
		###########
		$options = array();
		$precio_luz = $this->reservas->getLight($id_transaction);
		//echo "AA".$precio_luz;
		
		$light_option = $this->load->view('reservas/booking_light_option', array('precio' => $precio_luz), TRUE);
		$options['light'] = $light_option;
		
		$record_players = $this->config->item('booking_record_players');
		$record_players_range = array();
		if(isset($record_players) && $record_players) { 
			$record_players_range = $this->config->item('booking_record_players_range');
			//print_r($record_players_range);
			$players_option = $this->load->view('reservas/booking_players_option', array('record_players_range' => $record_players_range), TRUE);
			//print ($players_option);
			$options['players'] = $players_option;
		}
		#############



		//print_r($this->redux_auth->profile());
		/*
		if($this->redux_auth->logged_in()) {
			echo "logueado";
		}
		*/
		
		//echo $this->input->post('allow_light').' - '.$light;
		
		
		# Variable que habilita o no el marcar la reserva sin coste
		$no_cost=0;
		if($user_level<=4) $no_cost=1;
		$filtro=$this->config->item('no_cost_permission');
		if(isset($filtro[$user_level])) $no_cost = $filtro[$user_level];
		
		# Variable que habilita o no el marcar la reserva sin coste
		$record_players=$this->config->item('booking_record_players');
		if(isset($record_players) && $record_players) { $record_players_number = $this->config->item('booking_record_players_number');}
		//echo $record_players_number;
		if(!isset($record_players_number) || $record_players_number == '') $record_players_number = 1;
		//echo $record_players_number;
		
		# Variable que habilita o no el poder seleccionar usuarios para la reserva
		$multiuser=0;
		if($user_level<=4) $multiuser=1;
		
		# Variable que habilita o no el reservar por otros usuarios
		$alt_reserve=0;
		if($user_level<=4) $alt_reserve=1;
		
		# Variable que habilita o no el solicitar los datos del usuario para la reserva
		$user_data=0;
		if(!$this->redux_auth->logged_in()) $user_data=1;
		
		# Variable que habilita o no el registrar la reserva como 'partido compartido'
		$permiso=$this->config->item('shared_bookings_permission');
		$shared_booking=0;
		if($permiso[$user_level]) $shared_booking=1;
		
		if(in_array($this->agent->browser(), $this->config->item('special_browser'))) {
		$added = array('added_footer' => 'Pagina adaptada a IE');
      	
				$data=array(
					//'menu' => $this->load->view('menu', $menu, true),
					'user_name' => $user_name,
					//'search_fields' => $this->load->view('reservas/search_fields2', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true),
					//'result' => $this->load->view('reservas/simple_result', array('availability' => $availability_array, 'user_id' => $user_id, 'filters' => $this->load->view('reservas/search_fields', array('search_fields'=> $this->simpleSearchFields()), true)), true),
					//'validation_script' => $validation_script,
						'record_players_number' => $record_players_number,
						'info_message' => $this->session->userdata('info_message'),
						'error_message' => $this->session->userdata('error_message')
					);
					$this->session->unset_userdata('info_message');
					$this->session->unset_userdata('error_message');
				
				if($this->redux_auth->logged_in()) {
					$data['meta']=$this->load->view('meta', '', true);
					$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
					$data['footer']=$this->load->view('footer', $added, true);			
				} else {
					$data['meta']=$this->load->view('meta_index', '', true);
					$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
					$data['footer']=$this->load->view('footer_index', $added, true);
				}
      	$data['main_content']=$this->load->view('reservas/extra_iexplorer', array('user_name' => $user_name, 'info' => $info, 'no_cost' => $no_cost, 'multiuser' => $multiuser,  'form' => 'frmReserva', 'logged_user' => $user_id, 'id_transaction' => $id_transaction, 'options' => $options, 'shared_booking' => $shared_booking, 'record_players_number' => $record_players_number), true);
      	$this->load->view('main', $data);
      } else $this->load->view('reservas/extra', array('info' => $info, 'no_cost' => $no_cost, 'multiuser' => $multiuser,  'form' => 'frmReserva', 'logged_user' => $user_id, 'id_transaction' => $id_transaction, 'options' => $options, 'shared_booking' => $shared_booking, 'record_players_number' => $record_players_number));
	}


	function pay($mode, $idTransaction)
	{

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else $user_id=0;
		$logged_user=$user_id;
		
		# Definición de barra de menus
		$menu=array('menu' => $this->app_common->get_menu_options());

		$no_cost=0;
		if($this->input->post('no_cost')) $no_cost=$this->input->post('no_cost');
		
		//$idTransaction=$this->session->userdata('idTransaction');
		$info=$this->reservas->getBookingInfoById($idTransaction);
		$user_id = $info['user'];
		//print("<pre>");print_r($info);print("</pre>");//exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
			$this->session->set_userdata('error_message', $this->lang->line('session_lost_alert'));
			redirect(site_url('/reservas/'), 'Location'); exit();
	
		}

		//echo $user_id; //exit();
		if($this->input->post('no_cost')) $info['total_price']=0;
		
		
		$intervalos=array();
		$tmp=$this->session->userdata('bookingInterval');
		//print_r($tmp);exit();
		if(is_array($tmp)) {
			$intervalos=$tmp;
		}
				
		$success=0;
		switch($mode) {
			case 'prepaid':
				$estado_reserva=9;
				$modo_pago=5;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user_id, $this->input->post('user_desc'), $this->input->post('user_phone'), $no_cost, $this->input->post('no_cost_desc'));
				if(!$no_cost) {
					
					if($this->usuario->addPrepaidMovement($user_id, floatval($info['total_price'] * (-1)), '1', 1, $idTransaction)) $success=1;
					
					/*
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Cargo al credito prepago';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
					*/
					
				} else $success=1;
			break;
			case 'creditcard':
				$estado_reserva=9;
				$modo_pago=2;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user_id, $this->input->post('user_desc'), $this->input->post('user_phone'), $no_cost, $this->input->post('no_cost_desc'));
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user_id;
					$this->pagos->desc_user=$this->input->post('user_desc');
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva por tarjeta credito';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			
			case 'paypal':
				$estado_reserva=9;
				$modo_pago=3;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user_id, $this->input->post('user_desc'), $this->input->post('user_phone'), $no_cost, $this->input->post('no_cost_desc'));
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user_id;
					$this->pagos->desc_user=$this->input->post('user_desc');
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva por Paypal';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			
			case 'cash':
				$estado_reserva=9;
				$modo_pago=1;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user_id, $this->input->post('user_desc'), $this->input->post('user_phone'), $no_cost, $this->input->post('no_cost_desc'));
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user_id;
					$this->pagos->desc_user=$this->input->post('user_desc');
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva pagada en efectivo';
					$this->pagos->create_user=$user_id;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			
			case 'bank':
				$estado_reserva=9;
				$estado_pago = 2;
				$modo_pago=1;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user_id, $this->input->post('user_desc'), $this->input->post('user_phone'), $no_cost, $this->input->post('no_cost_desc'));
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user_id;
					$this->pagos->desc_user=$this->input->post('user_desc');
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva pagada en efectivo';
					$this->pagos->create_user=$user_id;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			
			case 'reserve':
				$estado_reserva=7;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, 0, $user_id, $this->input->post('user_desc'), $this->input->post('user_phone'), $no_cost, $this->input->post('no_cost_desc'));
				$success=1;
			break;
		}

		#Cada vez que voy a buscar, actualizo el id de transaccion
		$this->session->set_userdata('idTransaction', $this->app_common->getIdTransaction());
		
		
		//echo "aaa".$profile->email;
		
		# Mail de notificación de reservas
		$this->booking->notify_booking($info);


		$data=array(
			'meta' => $this->load->view('meta', '', true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'error' => $this->session->flashdata('message'),
			'main_content' => $this->load->view('reservas/payment_gestion', array('info' => $info, 'success' => $success,  'return_url' => $this->session->userdata('return_url')), true),
			//'page'=>'reservas/confirm',
			'info' => $info,
			'no_cost' => 1,
			'reserve' => 1,
			'pay' => 1,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');

      $this->load->view('main', $data);		

	}
	
	
	
	
	
# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de búsqueda de pista para AJAX
# -------------------------------------------------------------------

	function search2($fecha, $pista=NULL,$deporte=NULL, $tipo=NULL, $dummy )
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Lessons_model', 'lessons', TRUE);
		
		if(!isset($fecha) || !isset($dummy)) { redirect(base_url(), 'Location'); exit(); }

		# Si vienen con el texto 'null', las vacío, porque es que no fueron seleccionadas como filtro
		if($pista=='null') $pista='';
		if($deporte=='null') $deporte='';
		if($tipo=='null') $tipo='';
		
		$fecha_form=$fecha;
		$fecha=date($this->config->item('date_db_format'), strtotime($fecha));
		
//echo $deporte."-".$tipo."-".$pista."-".$fecha;

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			$user_name=$profile->username;
		}	else {
			$user_id=0;
			$user_group=9;
			$user_name=$this->lang->line('anonymous_user');
		}

		# Vacio el array de elementos seleccionados
		$this->session->set_userdata('bookingInterval', array());		

		#Cada vez que voy a buscar, actualizo el id de transaccion
		$id_transaction = $this->app_common->getIdTransaction();
		$this->session->set_userdata('idTransaction', $id_transaction);
		//echo $this->session->userdata('idTransaction');
		

		if(isset($fecha) && $fecha!="" && isset($pista) && $pista!="") {
			$pista_elegida=$pista;
			$pistas=array($pista_elegida);	
			$this->pistas->id=$pista_elegida;			
			$nombre_pista=$this->pistas->getDescription();			
			$dia_elegido=$fecha;
			//$this->reservas->date=$dia_elegido;			
			//$this->reservas->court=$pista_elegida;			
			$this->reservas->id_user=$user_id;			
			if($this->redux_auth->logged_in()) $this->reservas->clearByUser($user_id);	// Si hay alguien logueado, borro las reservas pendientes de confirmar al hacer una nueva búsqueda
			else $this->reservas->clearBySession($this->session->userdata('session_id'));
			//print("<pre>");
			
			/*
			$this->reservas->getSpecialTimetableByCourt();
			print("a");print_r($this->reservas->availability);exit();
			if(!$this->reservas->availability) $this->reservas->getSpecialTimetable();
			print("b");print_r($this->reservas->availability);
			if(!$this->reservas->availability) $this->reservas->availability=$this->pistas->getTimetable($dia_elegido);
			//print_r($this->reservas->availability);
			print("c");print_r($this->reservas->availability);
			$this->reservas->getAvailabilityByCourt($fecha,$pista);
			print("d");print_r($this->reservas->availability);
			$this->reservas->availability=$this->lessons->updateTimetable($fecha, $pista, $this->reservas->availability);
			*/
			
			$this->app_common->get_court_availability($pista_elegida, $dia_elegido);
			$availability_array[$nombre_pista]=$this->reservas->availability;
			//$availability_array=array($this->reservas->availability);
			//print_r($this->reservas->availability);

			# Hay que mirar si tiene horas reservadas para modificar el horario de base...
		
		} elseif (isset($fecha) && $fecha!="") {
			# Si no seleccionan pista, saco toda la info de todas las pistas
			
			$pistas=$this->pistas->getAvailableCourts($deporte,$tipo);
			//print_r($pistas);
			$availability_array=array();
			foreach($pistas as $elemento) {
				$pista_elegida=$elemento;	// Valor de ejemplo .. Deberá ser el seleccionado
				$this->pistas->id=$pista_elegida;			
				$nombre_pista=$this->pistas->getDescription();			
				$dia_elegido=$fecha;
				//$this->reservas->date=$dia_elegido;			
				//$this->reservas->court=$pista_elegida;			
				$this->reservas->id_user=$user_id;		
				$this->reservas->availability=NULL;	
				//echo 'Pista '.$this->pistas->id.'<br>';
				
				if($this->redux_auth->logged_in()) $this->reservas->clearByUser($user_id);	// Si hay alguien logueado, borro las reservas pendientes de confirmar al hacer una nueva búsqueda

			
				
				/*
				$this->reservas->getSpecialTimetableByCourt();
				if(!$this->reservas->availability) $this->reservas->getSpecialTimetable();
				if(!$this->reservas->availability) $this->reservas->availability=$this->pistas->getTimetable($dia_elegido);
				$this->reservas->getAvailabilityByCourt($fecha,$pista_elegida);
				$this->reservas->availability=$this->lessons->updateTimetable($fecha, $pista_elegida, $this->reservas->availability);
				*/
				$this->app_common->get_court_availability($pista_elegida, $dia_elegido);
				$availability_array[$nombre_pista]=$this->reservas->availability;
				//print("a<pre>");print_r($availability_array);exit();
				//array_push(, $this->reservas->availability);
			}
		}

		

		
			//echo $this->agent->browser().'----'; print_r($this->config->item('special_browser'));
			//if(in_array($this->agent->browser(), $this->config->item('special_browser'))) echo "AAAAA";
      //$this->load->view('main', $data);
      if(in_array($this->agent->browser(), $this->config->item('special_browser'))) {
      	$added = array('added_footer' => 'Pagina adaptada a IE');
				$data=array(
					//'menu' => $this->load->view('menu', $menu, true),
					'user_name' => $user_name,
					//'search_fields' => $this->load->view('reservas/search_fields2', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true),
					//'result' => $this->load->view('reservas/simple_result', array('availability' => $availability_array, 'user_id' => $user_id, 'filters' => $this->load->view('reservas/search_fields', array('search_fields'=> $this->simpleSearchFields()), true)), true),
					//'validation_script' => $validation_script,
						'info_message' => $this->session->userdata('info_message'),
						'error_message' => $this->session->userdata('error_message')
					);
					$this->session->unset_userdata('info_message');
					$this->session->unset_userdata('error_message');
				
				if($this->redux_auth->logged_in()) {
					$data['meta']=$this->load->view('meta', '', true);
					$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
					$data['footer']=$this->load->view('footer', $added, true);			
				} else {
					$data['meta']=$this->load->view('meta_index', '', true);
					$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
					$data['footer']=$this->load->view('footer_index', $added, true);
				}
      	$data['main_content']=$this->load->view('reservas/simple_result2_iexplorer', array('id_transaction' => $this->session->userdata('idTransaction'), 'availability' => $availability_array, 'pistas' => $pistas, 'user_name' => $user_name, 'user_id' => $user_id, 'date' => $fecha_form), true);
      	$this->load->view('main', $data);
      } else $this->load->view('reservas/simple_result2', array('id_transaction' => $this->session->userdata('idTransaction'), 'availability' => $availability_array, 'pistas' => $pistas, 'user_id' => $user_id, 'date' => $fecha_form));
      //print("<pre>");print_r($this->session);
	}
	




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de seleccion de pago para AJAX
# -------------------------------------------------------------------
	function confirm2($dummy, $id_transaction, $full_view = 0, $prereserved = 0)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');

		$debug = TRUE;
		$session=$this->session->userdata('session_id');
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_level=$profile->group;
		}	else {
			$user_id=0;
			$user_level=9;
		}
		//print("<pre>");print_r($_POST);exit();
		
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);//exit();
		# Comprobación (si corresponde) del número máximo de reservas diarias por usuario
		$booking_id_user = $this->input->post('id_user');
		$booking_id_user = $user_id;	// Con esto se comprueba el maximo de reservas en base al usuario logueado, no en base al usuario de la reserva.. 
		if($booking_id_user=='') $booking_id_user = 0;
		if($booking_id_user != 0) $valida_por_maxima = $this->booking->checkMaximumBookingPerDayUserSport($info['date'], $info['id_court'], $booking_id_user);
		else $valida_por_maxima = TRUE;
		if($debug) { if($valida_por_maxima) echo 'reserva valida respecto a maximos diarios de reserva'; else echo 'reserva INVALIDA respecto a maximos diarios de reserva';  }
		//exit();
		if(!$valida_por_maxima) {
        //$this->session->set_userdata('error_message', 'El usuario ha excedido el n&uacte;mero m&aacute;ximo de reservas diarias para ese deporte');
        //redirect(site_url(), 'Location'); 
        echo '<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p>'.img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')).' El usuario ha excedido el n&uacute;mero m&aacute;ximo de reservas diarias para ese deporte. Para reservar de nuevo, haga click '.anchor('reservas', 'aqu&iacute;').'</p>  </div> </div>';
        exit();
		}
		
		# Fin de la comprobacicón de reservas máximas por usuario/dia/deporte
	
		
		
		
		
		
		
		$multi_player = FALSE;
		$multi_player = $this->config->item('booking_record_players');
		$record_players_number = array();
		if(isset($multi_player) && $multi_player) { 
			$record_players_range = $this->config->item('booking_record_players_range');
		}
		if(!isset($record_players_range)) $record_players_range = array(1);

		# Grabación del usuario principal		
		$booking_id_user = $this->input->post('id_user');
		$booking_user_desc = $this->input->post('user_desc');
		$booking_user_phone = $this->input->post('user_phone');
		$add = 1;
		if((!isset($booking_id_user) || $booking_id_user == '') && (!isset($booking_user_desc) || $booking_user_desc == '') && (!isset($booking_user_phone) || $booking_user_phone =='')) $add = 0;
		if($booking_id_user=='') $booking_id_user = 0;
		if($add==1) $this->reservas->add_player($id_transaction, array('id_user' => $booking_id_user, 'user_desc' => $booking_user_desc , 'user_phone' => $booking_user_phone));
		
		#Si viene el usuario reegistrado seleccionado, me aseguro de rellenar los campos de nombre de usuario y telefono
		if($booking_id_user!='' && $booking_id_user!=0) {
			$booking_user_desc = $this->usuario->getUserDesc($booking_id_user);
			$booking_user_phone = $this->usuario->getUserPhone($booking_id_user);
		}


		#Si la opción de grabar multiples jugadores está activa, recojo los datos y los grabo
		if($multi_player) {
			for($i=1; $i< max($record_players_range); $i++) {	
				$booking_id_user_X = $this->input->post('id_user_'.$i);
				$booking_user_desc_X = $this->input->post('user_desc_'.$i);
				$booking_user_phone_X = $this->input->post('user_phone_'.$i);
				if($booking_id_user_X=='') $booking_id_user_X = 0;
				if($booking_id_user_X != 0 || ($booking_user_desc_X!='' && $booking_user_phone_X!='')) $this->reservas->add_player($id_transaction, array('id_user' => $booking_id_user_X, 'user_desc' => $booking_user_desc_X , 'user_phone' => $booking_user_phone_X));
			}

		} 
		
		
		$booking_no_cost = $this->input->post('no_cost');
		if($booking_no_cost == "true") $booking_no_cost = TRUE;
		else $booking_no_cost = FALSE;
		if($booking_no_cost) $info['total_price']=0;
		$booking_no_cost_desc = $this->input->post('no_cost_desc');
		
		$booking_light = $this->input->post('allow_light');
		if($booking_light == "true") $booking_light = TRUE;
		else $booking_light = FALSE;

		if($booking_light) $this->reservas->setLight($id_transaction);
		//echo "AA".$id_transaction;
		//echo $this->session->userdata('idTransaction');
		//print("<pre>"); print_r($this->reservas->getBookingInfoBySession($session));
		
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);print("</pre>");exit();
		
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_flashdata('message', '<p class="error">'.$this->lang->line('session_lost_alert').'</p>');
            //redirect(site_url('/reservas/'), 'Location'); 
            exit();
		}


		# Si viene marcado el check de preserervada, es que viene de una reserva ya hecha y solo viene a ser pagada.. así que los datos los saco de la reserva, no del POST. (especial para el panel decontrol)
		if($prereserved) {
			$booking_id_user = $info['user'];
			$booking_user_desc = $info['user_desc'];
			$booking_user_phone = $info['user_phone'];
			if($info['no_cost']) {
				$booking_no_cost = TRUE;
				$booking_no_cost_desc = $info['no_cost_desc'];
			}	else {
				$booking_no_cost = FALSE;
				$booking_no_cost_desc = null;
			}
			
			if($info['light']) $booking_light = TRUE;
			else $booking_light = FALSE;
		}

		$success=0;
		if(isset($info['status']) && $info['status']!="") $estado_reserva=$info['status'];
		else $estado_reserva=5;

		if($booking_no_cost) {
			# Si se ha marcado que la reserva no tiene coste, podemos saltarnos la siguiente pestaña y marcar la reserva como hecha
			
			$estado_reserva=9;
			$modo_pago=1;
			$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $booking_id_user, $booking_user_desc, $booking_user_phone, 1, $booking_no_cost_desc);
			$this->reservas->setPrice($id_transaction);
			$info=$this->reservas->getBookingInfoById($id_transaction);
			
			# Una vez marcada como reservada, pinto el javascript necesario para que se redirija a la última pestaña.
			//$method, $id_transaction, $dummy, $no_cost = '0', $no_cost_desc = NULL
			$this->session->set_flashdata('booking_no_cost_desc',$booking_no_cost_desc);
			
			echo '<script type="text/javascript">'."\r\n";
			?>
				var direccion2 = '<?php echo site_url('reservas/payd/cash/'.$id_transaction.'/'.time().'/1');?>';
				$("#accordion").accordion({ animated: 'slide' });
				$("#confirm_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
				$.ajax({					
				  type: 'GET',
				  url: direccion2,
				  success: function(data) {
				    $("#search_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda o confirmar la seleccion.</p>  </div> </div>');
						$("#accordion").accordion("activate" , 4);
				    $("#search_extra").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda o confirmar la seleccion.</p>  </div> </div>');
				    $("#confirm_payment").html(data);
				  }
				});
			
			<?php			
			echo '</script>'."\r\n";
			
		} else {
			# Si no está marcada como 'sin coste', completo la información de la reserva con los datos facilitados, grabo en session la información de la reserva y me redirijo a la pantalla de pagos.
			$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, 0, $booking_id_user, $booking_user_desc, $booking_user_phone, 0);	
			$this->reservas->setPrice($id_transaction);	//exit();
			$info=$this->reservas->getBookingInfoById($id_transaction);
			//print("<pre>");print_r($info);print("</pre>");
			//exit();
			# Gestión de métodos de pago a mostrar
			$this->load->model('Payment_model', 'pagos', TRUE);
			$paymentMethods=$this->pagos->getPaymentMethodsByUser($user_level);
			if($user_level==7) {
				$paymentMethods['cash'] = FALSE;
				$paymentMethods['creditcard'] = FALSE;
			}	
			$this->session->set_flashdata('paymentMethods', $paymentMethods);
			
			$conceptos = array();
			
			array_push($conceptos, array('text' => "Reserva ".$info['court']." (".$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).")", 'value' => $info['price']));
			if($info['light']) array_push($conceptos, array('text' => "Suplemento de luz", 'value' => $info['light_price']));
			if(isset($info['precio_supl1']) && $info['precio_supl1'] != 0) array_push($conceptos, array('text' => "Suplemento reserva anticipada", 'value' => $info['precio_supl1']));
			if(isset($info['playing_users']) && count($info['playing_users']) > 1) {
				$externos = 0;
				if($debug) print_r($info['players']);
				foreach($info['playing_users'] as $usuario) {
					if(isset($usuario['id_user']) && $usuario['id_user'] == 0) $externos++;
				}
				
				$precio = 0;
				if($externos > 0 ) {
					$precio = ($externos * 7 );
					$this->reservas->setPriceExtra($id_transaction, 'price_supl2', $precio, 'first');
					//echo $externos;
					array_push($conceptos, array('text' => "Invitados (".$externos.")", 'value' => $precio));
				}
			}
			$booking_extra_lasmatillas=$this->config->item('booking_extra_lasmatillas');
			$booking_extra_lasmatillas_quantity=$this->config->item('booking_extra_lasmatillas_quantity');
			if(isset($booking_extra_lasmatillas) && $booking_extra_lasmatillas && isset($booking_extra_lasmatillas_quantity)){
				if($info['intervals']/3 >= 1 && $info['intervals']%3 == 0) {
					$price_supl3 = $booking_extra_lasmatillas_quantity * intval($info['intervals']/3);
					$this->reservas->setPriceExtra($id_transaction, 'price_supl3', $price_supl3, 'first');
					array_push($conceptos, array('text' => "Descuento hora y media", 'value' => $price_supl3));
				}
			}
			//echo ($info['intervals']/3).'<br>';
			//echo '<pre>';print_r($info);print_r($conceptos);
			//exit();
			$this->session->set_flashdata('paymentLines', $conceptos);
			//$this->session->set_flashdata('returnOkUrl', site_url('reservas/booking_confirmation/'.$id_transaction.'/1/'.time()));
			$this->session->set_flashdata('returnKoUrl', site_url('reservas/booking_confirmation/'.$id_transaction.'/0/'.time()));
			
			
			
			redirect(site_url('payment/payment_request/1/'.$id_transaction), 'Location'); 
			exit();

		}
	}







# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de confirmación de partido de reto
# -------------------------------------------------------------------
	function confirm_reto($dummy, $id_transaction, $full_view = 0, $prereserved = 0)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);


		$session=$this->session->userdata('session_id');
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_level=$profile->group;
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		//print("<pre>");print_r($_POST);
		
		$booking_id_user = $user_id;
		
		$booking_no_cost = FALSE;
		$booking_no_cost_desc = '';
		
		$booking_light = $this->input->post('allow_light');
		if($booking_light == "true") $booking_light = TRUE;
		else $booking_light = FALSE;

		if($booking_light) $this->reservas->setLight($id_transaction);
		//echo "AA".$id_transaction;
		//echo $this->session->userdata('idTransaction');
		//print("<pre>"); print_r($this->reservas->getBookingInfoBySession($session));
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);print("</pre>");//exit();
		
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_userdata('error_message', $this->lang->line('session_lost_alert'));
            redirect(site_url('reservas'), 'Location'); 
            exit();
		}


		$success=0;
		$estado_reserva=7;

		# Si no está marcada como 'sin coste', completo la información de la reserva con los datos facilitados, grabo en session la información de la reserva y me redirijo a la pantalla de pagos.
		$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, 0, $booking_id_user, '', '', 0);	
		$this->reservas->setSelectionShared($id_transaction);	
		//exit();
		
		redirect(site_url('retos/new_reto/'.$id_transaction), 'Location'); 
		exit();

		
	}






# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de seleccion de pago para AJAX en pantalla de recepcion
# Se le llama al pulsar sobre la opción de 'pagar' del menú contextual del panel de recepción
# -------------------------------------------------------------------
	function confirm3($dummy, $price_light = NULL, $light = NULL, $id_transaction = NULL)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);


		$session=$this->session->userdata('session_id');
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_level=$profile->group;
		}	else {
			$user_id=0;
			$user_level=9;
		}
		
		if(!isset($id_transaction)) {
			$id_transaction=$this->session->userdata('idTransaction');
			if($light=="true") $this->reservas->setLight($id_transaction,$price_light);
		}
		//echo $this->session->userdata('idTransaction');
		//print("<pre>"); print_r($this->reservas->getBookingInfoBySession($session));
		$info=$this->reservas->getBookingInfoById($id_transaction);
		//print("<pre>");print_r($info);print("</pre>");//exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_flashdata('message', '<p class="error">'.$this->lang->line('session_lost_alert').'</p>');
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}
		//print_r($this->redux_auth->profile());
		/*
		if($this->redux_auth->logged_in()) {
			echo "logueado";
		}
		*/
		
		//echo $this->input->post('allow_light').' - '.$light;
		
		# Recoge las formas de pago disponibles para este nivel de usuario
		$this->load->model('Payment_model', 'pagos', TRUE);
		$paymentMethods=$this->pagos->getPaymentMethodsByUser($user_level);
		# Especificaciones de métodos de pago de esta pantalla
		
		$paymentMethods['tpv'] = FALSE;
		$paymentMethods['reserve'] = FALSE;
		$paymentMethods['prepaid'] = FALSE;
		$paymentMethods['paypal'] = FALSE;
		//print_r($paymentMethods);
		
		# Variable que habilita o no el marcar la reserva sin coste
		$no_cost=0;
		if($user_level<=7) $no_cost=1;
		$filtro=$this->config->item('no_cost_permission');
		if(isset($filtro[$user_level])) $no_cost = $filtro[$user_level];
		
		# Variable que habilita o no el reservar por otros usuarios
		$alt_reserve=0;
		if($user_level<=7) $alt_reserve=1;
		
		# Variable que habilita o no el solicitar los datos del usuario para la reserva
		$user_data=0;
		if(!$this->redux_auth->logged_in()) $user_data=1;
		
		# Cargo la vista de formas de pago
		$paymnet_content= $this->load->view('reservas/pago', array('methods' => $paymentMethods, 'transaction_id' => $session, 'info' => $info), true);
		//print("<pre>");print_r($menu);print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);
		
		$this->load->view('reservas/confirm_admin2', array('info' => $info, 'no_cost' => $no_cost, 'alt_reserve' => $alt_reserve, 'multiuser' => '1', 'pay' => 1, 'pay_content' => $paymnet_content, 'form' => 'frmReserva', 'transaction_id' => $id_transaction));
		

	}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de confirmacion de pago para AJAX
# -------------------------------------------------------------------
	function pay2($idTransaction, $no_cost, $no_cost_desc = NULL, $user = NULL, $user_desc = NULL, $user_phone = NULL, $dummy)
	{

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');

		if($no_cost_desc=="null") $no_cost_desc = NULL;
		if($user=="null") $user = 0;
		if($user_desc=="null") $user_desc = NULL;
		if($user_phone=="null") $user_phone = NULL;

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else $user_id=0;
		$logged_user=$user_id;
		
		
		$session=$this->session->userdata('user_id');
		$info=$this->reservas->getBookingInfoById($idTransaction);
		print("<pre>");print_r($info);print("</pre>");exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_userdata('error_message', $this->lang->line('session_lost_alert'));
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}

		if($no_cost=="true") $no_cost=1;
		else $no_cost=0;
		
		if($no_cost) $info['total_price']=0;
		
				
		$success=0;
		switch($mode) {
			case 'prepaid':
				$estado_reserva=9;
				$modo_pago=5;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				if(!$no_cost) {
					
					if($this->usuario->addPrepaidMovement($user, floatval($info['total_price'] * (-1)), '1', 1, $idTransaction)) $success=1;
					
					/*
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Cargo al credito prepago';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
					*/
					
				} else $success=1;
			break;
			case 'creditcard':
				$estado_reserva=9;
				$modo_pago=2;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->desc_user=$user_desc;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva por tarjeta credito';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			case 'tpv':
				$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=6;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->desc_user=$user_desc;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva por tarjeta credito';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=9;
				} else $success=1;
			break;
			
			case 'paypal':
				$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=3;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->desc_user=$user_desc;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago	;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva por Paypal';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=9;
				} else $success=1;
			break;
			
			case 'cash':
				$estado_reserva=9;
				$modo_pago=1;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//echo "AA";exit();
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->desc_user=$user_desc;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_reserva;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva pagada en efectivo';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			
			case 'bank':
				$estado_reserva=9;
				$estado_pago = 2;
				$modo_pago=1;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//echo "AA";exit();
				if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$session;
					$this->pagos->id_transaction=$idTransaction;
					$this->pagos->id_user=$user;
					$this->pagos->desc_user=$user_desc;
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago;
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description='Reserva pagada en efectivo';
					$this->pagos->create_user=$logged_user;
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				} else $success=1;
			break;
			
			case 'reserve':
				$estado_reserva=7;
				$this->reservas->setSelectionReserved($idTransaction, $estado_reserva, 0, $user_id, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				$success=1;
			break;
		}

		
		# Mail de notificación de reservas
		if($success == "1" && $this->config->item('reserve_send_mail')) {
			$this->booking->notify_booking($info);
		}

		#Cada vez que voy a buscar, actualizo el id de transaccion
		$this->session->set_userdata('idTransaction', $this->app_common->getIdTransaction());
		
		
		$this->load->view('reservas/payment', array('info' => $info, 'success' => $success));

	}
	
	

	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de gestión de la reserva tras el pago de la misma
# -------------------------------------------------------------------
	function payd($method, $id_transaction, $dummy, $no_cost = '0', $no_cost_desc = NULL)
	{

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');

		$paymentMethods = $this->session->flashdata('paymentMethods');
		//echo "<br>";
		//print_r($paymentMethods);
		$paymentLines = $this->session->flashdata('paymentLines');
		//echo "<br>";
		//print_r($paymentLines);
		$returnOkUrl = $this->session->flashdata('returnOkUrl');
		//echo "<br>".$returnOkUrl;
		$returnKoUrl = $this->session->flashdata('returnKoUrl');
		//echo "<br>".$returnKoUrl;
		//echo "<br>".$id_transaction."<br>";


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else $user_id=0;
		$logged_user=$this->session->userdata('user_id');
		
		
		$session=$this->session->userdata('user_id');
		$info=$this->reservas->getBookingInfoById($id_transaction);
	//	print("<pre>");print_r($info);print("</pre>");//exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_flashdata('message', '<p class="error">'.$this->lang->line('session_lost_alert').'</p>');
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}

		$no_cost_desc = $this->session->flashdata('booking_no_cost_desc');
				
		$success=0;
		switch($method) {
			case 'prepaid':
				$estado_reserva=9;
				$modo_pago=5;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
			break;
			case 'creditcard':
				$estado_reserva=9;
				$modo_pago=2;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
			break;
			case 'tpv':
				$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=6;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=5;
			break;
			
			case 'paypal':
				$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=3;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
			break;
			
			case 'reserve':
				$estado_reserva=7;
				$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, 0, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc);
				$success=5;
			break;
			
			case 'cash':
				$estado_reserva=9;
				$modo_pago=1;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
				//echo "AA";exit();
			break;
			
			case 'bank':
				$estado_reserva=9;
				$modo_pago=4;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
				//echo "AA";exit();
			break;
		}
//exit();
		//log_message('debug','Estado: '.$success);
		
		# Mail de notificación de reservas
		if(($success == "1" ||$success == "5")&& $this->config->item('reserve_send_mail')) {
			$this->booking->notify_booking($info);
		}

		#Cada vez que voy a buscar, actualizo el id de transaccion
		$this->session->set_userdata('idTransaction', $this->app_common->getIdTransaction());
		
		if($returnOkUrl != '') {
			//exit($returnOkUrl);
			redirect($returnOkUrl, 'Location'); 
			exit();			
		}
		
		if(strlen($info['id'])<4) $order=sprintf("%04s", $info['id']).date('is').'re';
		else $order=$info['id'].date('is').'re';
		$paymentDescription = $info['operation_desc'];
		
		$this->load->view('reservas/payment', array('info' => $info, 'success' => $success, 'order' => $order, 'total' => $info['total_price'], 'paymentDescription' => $paymentDescription));

	}
	
	

	


# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de confirmacion de pago para AJAX
# -------------------------------------------------------------------
	function booking_confirmation($idTransaction, $success, $dummy)
	{

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');

		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			$user_name=$profile->username;
		}	else {
			$user_id=0;
			$user_group=9;
			$user_name=$this->lang->line('anonymous_user');
		}
		
		$logged_user=$user_id;
		
		
		$session=$this->session->userdata('session_id');
		$info=$this->reservas->getBookingInfoById($idTransaction);
		$pago=$this->pagos->getPaymentByTransaction($idTransaction);
		$permiso_ticket_arr = $this->config->item('payment_managment_permission');
		$permiso_ticket = FALSE;
		if(isset($permiso_ticket_arr) && $permiso_ticket_arr[$user_group]) $permiso_ticket = TRUE;
		
		//print("<pre>");print_r($pago);print("</pre>");//exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_flashdata('message', '<p class="error">'.$this->lang->line('session_lost_alert').'</p>');
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}
		
		
		if(isset($info['user']) && $info['user']!='0') {
			$this->session->set_userdata('returnUrl', site_url('users/pagos/'.$info['user']));
		}
		
		if($success == "1") {
			$this->reservas->complete_reserve($idTransaction);

			
			# Mail de notificación de reservas
			if(1==2 && $this->config->item('reserve_send_mail')) {

					$this->booking->notify_booking($info);

			}
		}

		#Cada vez que voy a buscar, actualizo el id de transaccion
		$this->session->set_userdata('idTransaction', $this->app_common->getIdTransaction());
		
		
		# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
		if(strlen($info['id'])<4) $order=sprintf("%04s", $info['id']).date('is').'re';
		else $order=$info['id'].date('is').'re';
		$paymentDescription = $info['operation_desc'];
		
		if(in_array($this->agent->browser(), $this->config->item('special_browser'))) {
      	$added = array('added_footer' => 'Pagina adaptada a IE');
				$data=array(
					//'menu' => $this->load->view('menu', $menu, true),
					'user_name' => $user_name,
					//'search_fields' => $this->load->view('reservas/search_fields2', array('search_fields'=> $this->simpleSearchFields(), 'form' => 'frmReserva', 'disabled' => $disabled), true),
					//'result' => $this->load->view('reservas/simple_result', array('availability' => $availability_array, 'user_id' => $user_id, 'filters' => $this->load->view('reservas/search_fields', array('search_fields'=> $this->simpleSearchFields()), true)), true),
					//'validation_script' => $validation_script,
						'info_message' => $this->session->userdata('info_message'),
						'error_message' => $this->session->userdata('error_message')
					);
					$this->session->unset_userdata('info_message');
					$this->session->unset_userdata('error_message');
				
				if($this->redux_auth->logged_in()) {
					$data['meta']=$this->load->view('meta', '', true);
					$data['header']=$this->load->view('header', array('enable_menu' => '1'), true);
					$data['footer']=$this->load->view('footer', $added, true);			
				} else {
					$data['meta']=$this->load->view('meta_index', '', true);
					$data['header']=$this->load->view('header_index', array('enable_menu' => '0'), true);
					$data['footer']=$this->load->view('footer_index', $added, true);
				}
      	$data['main_content'] = $this->load->view('reservas/payment_iexplorer', array('user_name' => $user_name, 'info' => $info, 'pago' => $pago, 'permiso_ticket' => $permiso_ticket, 'success' => $success, 'order' => $order, 'total' => $info['total_price'], 'paymentDescription' => $paymentDescription), true);
      	$this->load->view('main', $data);
      } else $this->load->view('reservas/payment', array('info' => $info, 'pago' => $pago, 'permiso_ticket' => $permiso_ticket, 'success' => $success, 'order' => $order, 'total' => $info['total_price'], 'paymentDescription' => $paymentDescription));

	}
	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que muestra un resumen de una reserva concreta
# -------------------------------------------------------------------

	function resume($transaction=NULL, $encode=NULL)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);

		/*
		$transaction = $this->input->get('transaction');
		$encode = $this->input->get('encode');
		*/
		if(!isset($transaction) || trim($transaction) =="" || !isset($encode) || trim($encode) =="" || $encode != $this->app_common->reserve_encode($transaction)) {
			$menu=array('menu' => $this->app_common->get_menu_options());
			$title = $this->config->item('app_name').' - '.'Intento fraudulento de acceso a datos de una reserva';
			$main_content='<h1>Intento fraudulento de acceso a datos de una reserva</h1>';
		} else {
			
			$info=$this->reservas->getBookingInfoById($transaction);
			$menu=array('menu' => $this->app_common->get_menu_options());
			//print("<pre>");print_r($info);
			
			$fecha=date($this->config->item('reserve_date_filter_format'), strtotime($info['date']));
			$title='Participaci&oacute;n en un partido en '.$this->config->item('club_name').' el '.$fecha.' - '.$this->config->item('app_name');
			
			$main_content='<h1>Detalle en un partido en '.$this->config->item('club_name').'.</h1>';
			$main_content.=			$this->load->view('reservas/detail', array('info' => $info), TRUE);
			//$main_content.='<p>Partido en '.$this->config->item('club_name').' el '.$fecha.' - '.$this->config->item('app_name').' en la pista '.$info['reserva'].'.</p>';
			
		}


		$data=array(
			'meta' => $this->load->view('meta', array('title' => $title), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in()), true),
			'menu' => $this->load->view('menu', $menu, true),
			'footer' => $this->load->view('footer', '', true),				
			'main_content' => $main_content,
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		
    $this->load->view('main', $data);

	}


	

# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que muestra un resumen de una reserva concreta
# -------------------------------------------------------------------

	function tooltip_info($transaction = NULL)
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);


		if(isset($transaction) && trim($transaction) != "") {
			
			$info=$this->reservas->getBookingInfoById($transaction);
			
			$this->load->view('reservas/tooltip_info', array('info' => $info, 'buttons' => TRUE));
			//$main_content.='<p>Partido en '.$this->config->item('club_name').' el '.$fecha.' - '.$this->config->item('app_name').' en la pista '.$info['reserva'].'.</p>';
			
		}

}




# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion que borra reservas
# -------------------------------------------------------------------

	function clean_block_reserves()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);


		if(isset($transaction) && trim($transaction) != "") {
			
			if($this->reservas->cleanBlockReserves()) echo '['.date('YmdHis').'] Borrado satisfactorio'."\r\n";
			else echo '['.date('YmdHis').'] Borrado con errores'."\r\n";
			
		}

}








# -------------------------------------------------------------------
#  devuelve el listado de reservas para jqGrid en JSON
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function exportacion ($opciones = NULL)
	{
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->library('booking');
		//$this->load->library('encrypt');





		$exportacion = $this->booking->exportacion();

		//echo $texto."<pre>"; print_r($exportacion);
		//exit();
		//echo json_encode ($data );
		//exit( 0 );
	}
	
	





public function test ($id_transaction, $id_booking, $time, $opciones = NULL)
	{
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->library('booking');

		$exportacion = $this->booking->getExtra($id_transaction, $id_booking, $time);
		echo '<pre>'; print($exportacion);
		exit();

	}
	
	



# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Funcion de gestión de la reserva tras el pago de la misma
# -------------------------------------------------------------------
	function pagoautomatico($id_transaction)
	{

		$this->load->model('Reservas_model', 'reservas', TRUE);
		$this->load->model('Pistas_model', 'pistas', TRUE);
		$this->load->model('Payment_model', 'pagos', TRUE);
		$this->load->model('Redux_auth_model', 'usuario', TRUE);
		$this->load->library('booking');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}	else $user_id=0;
		$logged_user=$this->session->userdata('user_id');
		
		
		$session=$this->session->userdata('user_id');
		$info=$this->reservas->getBookingInfoById($id_transaction);
	//	print("<pre>");print_r($info);print("</pre>");//exit();
		if(!isset($info) || !is_array($info) || count($info)<1) {
            $this->session->set_flashdata('message', '<p class="error">'.$this->lang->line('session_lost_alert').'</p>');
            redirect(site_url('/reservas/'), 'Location'); exit();
	
		}

		//print("<pre>"); print_r($info);exit();
		$success = 0; $estado_pago=0;

				$estado_reserva=9;
				$estado_pago = 2;
				$modo_pago=4;
				//$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $user, $user_desc, $user_phone, $no_cost, $no_cost_desc);
				//echo "AA";exit();
				//if(!$no_cost) {
					$this->pagos->id_type=1; //Reserva de pista
					$this->pagos->id_element=$this->session->userdata('session_id');
					$this->pagos->id_transaction=$id_transaction;
					$this->pagos->id_user=$info['user'];
					$this->pagos->desc_user=$info['user_desc'];
					$this->pagos->id_paymentway=$modo_pago;
					$this->pagos->status=$estado_pago;	// Pago bancario pendiente
					$this->pagos->quantity=$info['total_price'];
					$this->pagos->datetime=date($this->config->item('log_date_format'));
					$this->pagos->description=$info['operation_desc'];
					$this->pagos->create_user=$this->session->userdata('user_id');
					$this->pagos->create_time=date($this->config->item('log_date_format'));
					
					if($this->pagos->setPayment()) $success=1;
				//} else $success=1;







		$no_cost_desc = '';
				
		$success=0;

				$estado_reserva=9;
				$modo_pago=4;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], 0, $no_cost_desc)) $success=1;

/*
		switch($method) {
			case 'prepaid':
				$estado_reserva=9;
				$modo_pago=5;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
			break;
			case 'creditcard':
				$estado_reserva=9;
				$modo_pago=2;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
			break;
			case 'tpv':
				$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=6;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=5;
			break;
			
			case 'paypal':
				$estado_reserva=7;
				$estado_pago=5;
				$modo_pago=3;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
			break;
			
			case 'reserve':
				$estado_reserva=7;
				$this->reservas->setSelectionReserved($id_transaction, $estado_reserva, 0, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc);
				$success=5;
			break;
			
			case 'cash':
				$estado_reserva=9;
				$modo_pago=1;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
				//echo "AA";exit();
			break;
			
			case 'bank':
				$estado_reserva=9;
				$modo_pago=4;
				if($this->reservas->setSelectionReserved($id_transaction, $estado_reserva, $modo_pago, $info['user'], $info['user_desc'], $info['user_phone'], $no_cost, $no_cost_desc)) $success=1;
				//echo "AA";exit();
			break;
		}
*/


	}
	
	





	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */