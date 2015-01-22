<?php

class Ranking extends Controller {

	function Ranking()
	{
		parent::Controller();	
		$this->load->config('ranking');
		$this->load->library('rank_lib');
	}
	



# -------------------------------------------------------------------
#  devuelve el listado de retos
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function index()
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'description',index:'fecha', width:10, align:'center'},
						   		{name:'inicio', index:'start_date', width:6, align:'center', editable:true},
						   		{name:'final', index:'end_date', width:6, align:'center', editable:true},
						   		{name:'sport_desc',index:'sport_desc', width:20, align:'center'},
						   		{name:'teams', index:'teams', width:5, align:'center'},
						   		{name:'active', index:'active', width:5, align:'center'},
								";
		$colnames = "'Id','Nombre','Inicio','Final','Deporte','Equipos','Activo'";

		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "ranking/jqgrid_list_all", 
				'title' => 'Listado de rankings', 
				'default_orderfield' => 'start_date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';

		$permiso_editar=$this->config->item('ranking_permission');
		$editar = $permiso_editar[$profile->group];
		$permiso_borrar=$this->config->item('ranking_permission');
		$borrar = $permiso_borrar[$profile->group];
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('gestion/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('ranking/list_all', array('grid_code' => $grid_code, 'editar' => $editar, 'borrar' => $borrar, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}






# -------------------------------------------------------------------
#  devuelve el listado de retos
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function publico()
	{
		
		$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->helper('jqgrid');


		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			

		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		
		$colmodel = "	{name:'id',index:'id', width:1, align:'center',hidden:true},
						   		{name:'description',index:'fecha', width:10, align:'center'},
						   		{name:'inicio', index:'start_date', width:6, align:'center', editable:true},
						   		{name:'final', index:'end_date', width:6, align:'center', editable:true},
						   		{name:'sport_desc',index:'sport_desc', width:20, align:'center'},
						   		{name:'teams', index:'teams', width:5, align:'center'},
						   		{name:'active', index:'active', width:5, align:'center'},
								";
		$colnames = "'Id','Nombre','Inicio','Final','Deporte','Equipos','Activo'";

		
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "ranking/jqgrid_list_all_user/".$user_id, 
				'title' => 'Listado de rankings', 
				'default_orderfield' => 'start_date', 
				'default_orderway' => 'desc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';

		$permiso_editar=$this->config->item('ranking_permission');
		$editar = $permiso_editar[$profile->group];
		$permiso_borrar=$this->config->item('ranking_permission');
		$borrar = $permiso_borrar[$profile->group];
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => $this->load->view('users/submenu_navegacion', array(), true)), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			//'main_content' => $this->load->view('jqgrid/main', array('colnames' => $colnames, 'colmodel' => $colmodel), true),
			'main_content' => $this->load->view('ranking/list_all', array('grid_code' => $grid_code, 'destino_clic' => site_url('ranking/partidos'), 'editar' => FALSE, 'borrar' => FALSE, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
			
		
		# Carga de la vista principal
		$this->load->view('main', $data);
	}










# -------------------------------------------------------------------
#  devuelve el listado de rankings disponibles
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all ($add_params = NULL)
	{
		$this->load->model('ranking_model', 'ranking', TRUE);

		$where = '';
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el ver el modulo de ranking
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        exit(0);
			}
		}	else {
      exit(0);
		}

		//$req_param = array ();
		$req_param = array (
				"orderby" => $this->input->post( "sidx", TRUE ),
				"orderbyway" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
		);

		if($req_param['search']=='true' && $req_param['search_field']!='' && $req_param['search_operator']!='' && $req_param['search_str']!='') {
			if(trim($where)!="") $where .= ' AND ';
			
			$where .= $req_param['search_field'];
			switch($req_param['search_operator']) {
				case 'cn':
					$where .=' LIKE \'%'.$req_param['search_str'].'%\' '; 
				break;
			}	
		}

		if(trim($where)!="") $where .= ' AND ';
		if(isset($add_params['where']) && $add_params['where']!='') $where .= $add_params['where'];

		$req_param['where'] = $where;
//print($where);
		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->ranking->get_data($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->ranking->get_data ($req_param, 'none');
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );
	}








# -------------------------------------------------------------------
#  devuelve el listado de rankings disponibles
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_list_all_user ($usuario)
	{

		$this->load->model('ranking_model', 'ranking', TRUE);

		$where = '';
		
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
		}	else {
      exit(0);
		}

		//$req_param = array ();
		$req_param = array (
				"orderby" => $this->input->post( "sidx", TRUE ),
				"orderbyway" => $this->input->post( "sord", TRUE ),
				"page" => $this->input->post( "page", TRUE ),
				"num_rows" => $this->input->post( "rows", TRUE ),
				"search" => $this->input->post( "_search", TRUE ),
				"where" => '',
				"search_field" => $this->input->post( "searchField", TRUE ),
				"search_operator" => $this->input->post( "searchOper", TRUE ),
				"search_str" => $this->input->post( "searchString", TRUE ),
		);

		if($req_param['search']=='true' && $req_param['search_field']!='' && $req_param['search_operator']!='' && $req_param['search_str']!='') {
			if(trim($where)!="") $where .= ' AND ';
			$where .= $req_param['search_field'];

			switch($req_param['search_operator']) {
				case 'cn':
					$where .=' LIKE \'%'.$req_param['search_str'].'%\' '; 
				break;
			}	
		}

			if(trim($where)!="") $where .= ' AND ';
			$where .= "ranking.active = 1 AND ranking.id IN (select id_ranking from ranking_teams, ranking_teams_members WHERE ranking_teams.id = ranking_teams_members.id_team AND ranking_teams_members.id_user = ".$usuario.")";

		
		if(isset($add_params['where']) && $add_params['where']!='') {
			if(trim($where)!="") $where .= ' AND ';
			$where .= $add_params['where'];
			
		}

		$req_param['where'] = $where;
//print($where);exit();
		$data->page = $this->input->post( "page", TRUE );
		$data->records = count ($this->ranking->get_data($req_param,"all"));
		$data->total = ceil ($data->records / $req_param['num_rows'] );
		$records = $this->ranking->get_data ($req_param, 'none');
		$data->rows = $records;

		echo json_encode ($data );
		exit( 0 );

	}






# -------------------------------------------------------------------
#  devuelve el listado de partidos disponibles
# -------------------------------------------------------------------
# -------------------------------------------------------------------
public function jqgrid_matchs ($id, $round, $add_params = NULL)
	{
		$this->load->model('ranking_model', 'ranking', TRUE);
		$this->load->library('rank_lib');
		
		$data = $this->rank_lib->getMatchs($id, $round, $add_params);

		echo json_encode ($data);
		exit( 0 );
	}













# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de creacion de ranking
# -------------------------------------------------------------------
	function new_rank ()
	{
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->library('rank_lib');
		$this->load->model('Payment_model', 'payment', TRUE);
		//$this->load->model('Pistas_model', 'pistas', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//$equipos = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
		//$this->rank_lib->calculaJornadas($equipos);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}
			# Variable que habilita o no el poder ver los rankings
			$edit_permiso=$this->config->item('ranking_create_permission');
			if(!$edit_permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'No tiene permiso para crear rankings');
        redirect(site_url(), 'Location'); 
        exit();
			}
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}


		###########
		# Función de creacion
		if($this->input->post('action') && $this->input->post('action')=="save") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$data['start_date'] = date($this->config->item('date_db_format'), strtotime($data['start_date']));
			$data['end_date'] = date($this->config->item('date_db_format'), strtotime($data['end_date']));
			//print("<pre>");print_r($data);exit();
			
			$id = $this->rank->createRanking($data);
			
      $this->session->set_userdata('info_message', 'Ranking creado.');
			redirect(site_url('ranking/detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del creacion del registro
		##########

		//print("<pre>");print_r($info);print("</pre>");
		$deportes = $this->rank->getSportsArray();
		$generos = $this->rank->getGendersArray();
		$promociones = $this->rank->getPromotionTypes();
		$tarifas = $this->app_common->getPrices();
		$frecuencias = $this->payment->getPaymentsFrequencies();
		
		//$rondas = $this->rank_lib->createRounds($info);	// Crea las rondas desde cero
		//print("<pre>");print_r($rondas);print("</pre>");
		
		//$niveles = $this->lessons->getLevelsArray();
		
		$edit_permiso=$this->config->item('ranking_create_permission');
		

		$vista = 'ranking/new';
		
		//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		$contenido = $this->load->view($vista, array('deportes' => $deportes, 'generos' => $generos, 'tarifas' => $tarifas, 'promociones' => $promociones, 'frecuencias' => $frecuencias), true);
		$submenu_content = NULL;
		
		//$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}











# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de detalle del ranking
# -------------------------------------------------------------------
	function detail ($id)
	{
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->library('rank_lib');
		//$this->load->model('Redux_auth_model', 'usuario', TRUE);
		//$this->load->model('Pistas_model', 'pistas', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//$equipos = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
		//$this->rank_lib->calculaJornadas($equipos);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}


		# Recuperando información general del ranking
		$info = $this->rank->getRanking($id);
		$rondas = $this->rank_lib->getRounds($id);	// Recupera las rondas
		//print("<pre>");print_r($rondas);print("</pre>");
		$info['rondas'] = $rondas;
		//print("<pre>");print_r($info);print("</pre>");

		
		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);

		###########
		# Función de guardado
		if($this->input->post('action') && $this->input->post('action')=="save") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$data['start_date'] = date($this->config->item('date_db_format'), strtotime($data['start_date']));
			$data['end_date'] = date($this->config->item('date_db_format'), strtotime($data['end_date']));
			//print("<pre>");print_r($data);exit();
			
			$this->rank->updateRanking($data);
			
			$this->session->set_userdata('info_message', 'Los datos han sido actualizados.');
			redirect(site_url('ranking/detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del guardado del registro
		##########

		###########
		# Función de inicio de ranking
		if($this->input->post('action') && $this->input->post('action')=="start") {
			
			$rondas = $this->rank_lib->createRounds($info);

			if(count($rondas)>0) {
				$this->rank->setRounds($id, $rondas);

				$this->rank->startRanking($id);
				//$this->rank->createTeams($id);
				
				$this->session->set_userdata('info_message', 'El ranking se ha bloqueado al iniciarse.');
	      
			}	else $this->session->set_userdata('error_message', 'El ranking no ha podido ser iniciado. Revise los datos.');
	    
			redirect(site_url('ranking/detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del inicio de ranking
		##########


		###########
		# Función de inicio de ronda
		if($this->input->post('action') && $this->input->post('action')=="start_round") {
			
			# Jornada, dentro del ranking, que quiero iniciar
			$jornada_iniciada = $this->input->post('id_round');
			# Hago el calculo de las promociones (si no estamos en la primera jornada)
			/*
			if($jornada_iniciada != 1) {
				
				//echo 'calculando promociones<br>';
				# Establezco la ronda de la que se visualizarán datos
				$ronda_visualizar = $jornada_iniciada - 1;
				//echo '--'.$ronda_visualizar;//exit();
				
				$equipos_tmp = $this->rank_lib->getTeams($id, $ronda_visualizar);

				$equipos = $this->app_common->ordenar_array($equipos_tmp, 'group', SORT_ASC, 'puntos', SORT_DESC, 'PG', SORT_DESC, 'JG', SORT_DESC);

				$resultado = array();
				for($i = 1; $i <= $info['groups']; $i++) {
					$resultado[$i] = array();
					for($j = 1; $j <= $info['teams']; $j++) {
						if(count($equipos)>0) {
							foreach($equipos as $equipo) {
								if($equipo['group'] == $i && $equipo['order'] == $j) $resultado[$i][$j] = $equipo;
							}
						}
						if(!isset($resultado[$i][$j])) $resultado[$i][$j] = array('id' => '0', 'group' => $i, 'order' => $j, 'main_user' => '0', 'status' => '0', 'description' => '&nbsp;', 'main_user_description' => '', 'PJ' => '', 'PG' => '', 'PP' => '', 'puntos' => '');
					}
				}
				//print("<pre>");print_r($info);print_r($equipos);print("</pre>");//print_r($resultado);
				
				# llamo a la funcion de promocion
				$this->rank_lib->runPromotion($id, $resultado, $jornada_iniciada);
				 
			}
			*/
			
			//exit($jornada_iniciada);
			# REcorro cada grupo del ranking para calcular los partidos dentro de cada uno en función de los equipos que tienen.
			for($i = 1; $i <= $info['groups']; $i++) {
				
				# Recupero los equipos activos para este grupo
				$equipos_grupo_tmp = $this->rank->get_ActiveTeams($id, $i);
				$equipos_grupo = array();
				foreach($equipos_grupo_tmp as $equip) array_push($equipos_grupo, $equip['id']);
				//print($i."<pre>");print_r($equipos_grupo_tmp);print("</pre>");
				//print($i."<pre>");print_r($equipos_grupo);print("</pre>");
				
				# En función de los codigos de equipos apuntados, calculo los cruces todos contra todos
				$partidos = $this->rank_lib->calculaJornadas($equipos_grupo);
				//print($i."<pre>");print_r($partidos);print("</pre>");
				
				$group = $i;
				# Obtengo un array con las fechas estimadas y los equipos implicados para cada partido
				$partidos_planificados = $this->rank_lib->scheduleMatchs($info, $group, $jornada_iniciada, $partidos);
				//print("<pre>");print_r($partidos_planificados);print("</pre>");
				
				# Si he obtenido partidos, limpio la lista de partidos para esta jornada y grupo y los vuelvo a insertar
				if(count($partidos_planificados) > 0) {
					$this->rank->cleanMatchs($id, $group, $jornada_iniciada);
					foreach($partidos_planificados as $encuentro) {
						$this->rank->createMatch($id, $group, $jornada_iniciada, $encuentro['team1'], $encuentro['team2'], $encuentro['estimated_date']);
					}
				}
			}
			
			# MArco como iniciada la jornada
			$this->rank->startRound($jornada_iniciada);
			
			//exit('ronda: '.$this->input->post('id_round'));
			
			$this->session->set_userdata('info_message', 'La jornada ha sido iniciada.');
			redirect(site_url('ranking/detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del guardado del registro
		##########

		###########
		# Función de finalzacion de ronda
		if($this->input->post('action') && $this->input->post('action')=="end_round") {
			
			# Jornada, dentro del ranking, que quiero finalizar
			$jornada_finalizada = $this->input->post('id_round');
			//echo $jornada_finalizada.'<br>';
			$partidos = $this->rank->get_matchs_data(array('where' => 'ranking_matchs.status < 5 and ranking_matchs.id_ranking = '.$id.' and ranking_matchs.round = '.$jornada_finalizada));
			foreach($partidos as $partido) {
				$datos = array('fecha' => date(DATETIME_DB), 'vencedor' => 0, 'estado' => 8, 'tanteo' => array());
				for($i=1; $i<=$info['score_parts']; $i++) {
					array_push($datos['tanteo'], array('1' => 0, '2' => 0));
				}
				$this->rank->setMatchResult($partido['id'], $datos);
			}
			//print("<pre>");print_r($partidos);print("</pre>");exit();
			# Marco como finalizada la jornada
			$this->rank->endRound($jornada_finalizada);
			
			# Guardo resultados 
			$resultado_jornada = $this->rank_lib->getTeamsCalculated($id, $jornada_finalizada); //exit();
			$this->rank->setRoundScoring($id, $jornada_finalizada, $resultado_jornada);
			
			# Obtengo el id de la proxima jornada (tanto el id como el numero ordinal dentro del ranking)
			$rondas = $this->rank_lib->getRounds($id);
			$anterior='';
			foreach($rondas as $ronda) {
				if($anterior!='' && $anterior == $jornada_finalizada) { $proxima_jornada = $ronda['id']; $proxima_jornada_number = $ronda['round']; }
				$anterior = $ronda['id'];
			}
			
			# Marco como finalizada la jornada
			$this->rank->endRound($jornada_finalizada, $proxima_jornada);


			$this->rank->setRoundScoring($id, $proxima_jornada, $resultado_jornada, TRUE);
			//print("<pre>");print_r($resultado_jornada);print("</pre>");exit();
			//echo 'proxima jornada '.$proxima_jornada; exit();
			//print("<pre>");print_r($rondas);print("</pre>");exit();

			# llamo a la funcion de promocion
				$resultado = array();
				for($i = 1; $i <= $info['groups']; $i++) {
					$resultado[$i] = array();
					for($j = 1; $j <= $info['teams']; $j++) {
						if(count($resultado_jornada)>0) {
							foreach($resultado_jornada as $equipo) {
								if($equipo['group'] == $i && $equipo['order'] == $j) $resultado[$i][$j] = $equipo;
							}
						}
						if(!isset($resultado[$i][$j])) $resultado[$i][$j] = array('id' => '0', 'group' => $i, 'order' => $j, 'main_user' => '0', 'status' => '0', 'description' => '&nbsp;', 'main_user_description' => '', 'PJ' => '', 'PG' => '', 'PP' => '', 'puntos' => '');
					}
				}
			//print("<pre>");print_r($resultado);print("</pre>");//exit();
				$this->rank_lib->runPromotion($id, $resultado, $proxima_jornada);








			/*
			if($jornada_iniciada != 1) {
				
				//echo 'calculando promociones<br>';
				# Establezco la ronda de la que se visualizarán datos
				$ronda_visualizar = $jornada_iniciada - 1;
				//echo '--'.$ronda_visualizar;//exit();
				
				$equipos_tmp = $this->rank_lib->getTeams($id, $ronda_visualizar);

				$equipos = $this->app_common->ordenar_array($equipos_tmp, 'group', SORT_ASC, 'puntos', SORT_DESC, 'PG', SORT_DESC, 'JG', SORT_DESC);

				$resultado = array();
				for($i = 1; $i <= $info['groups']; $i++) {
					$resultado[$i] = array();
					for($j = 1; $j <= $info['teams']; $j++) {
						if(count($equipos)>0) {
							foreach($equipos as $equipo) {
								if($equipo['group'] == $i && $equipo['order'] == $j) $resultado[$i][$j] = $equipo;
							}
						}
						if(!isset($resultado[$i][$j])) $resultado[$i][$j] = array('id' => '0', 'group' => $i, 'order' => $j, 'main_user' => '0', 'status' => '0', 'description' => '&nbsp;', 'main_user_description' => '', 'PJ' => '', 'PG' => '', 'PP' => '', 'puntos' => '');
					}
				}
				//print("<pre>");print_r($info);print_r($equipos);print("</pre>");//print_r($resultado);
				
				# llamo a la funcion de promocion
				$this->rank_lib->runPromotion($id, $resultado, $jornada_iniciada);
				 
			}
			*/







			
			//exit('ronda: '.$this->input->post('id_round'));
			
			$this->session->set_userdata('info_message', 'La jornada ha sido finalizada.');
			redirect(site_url('ranking/detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del guardado del registro
		##########

		
		
		//print("<pre>");print_r($info);print("</pre>");
		$deportes = $this->rank->getSportsArray();
		$generos = $this->rank->getGendersArray();
		$promociones = $this->rank->getPromotionTypes();
		$tarifas = $this->app_common->getPrices();
		
		//$rondas = $this->rank_lib->createRounds($info);	// Crea las rondas desde cero
		//print("<pre>");print_r($rondas);print("</pre>");
		
		//$niveles = $this->lessons->getLevelsArray();
		
		$enable_add = TRUE;
		if($info['current_vacancies'] == 0) $enable_add = FALSE;
		
		$edit_permiso=$this->config->item('ranking_edit_permission');
		
		if($edit_permiso[$profile->group]) {
			$vista = 'ranking/edit';
			$submenu = 'ranking/submenu_navegacion';
		}	else {
			$vista = 'ranking/detail';
			$submenu = 'ranking/submenu_navegacion';
		}
		
		//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		$contenido = $this->load->view($vista, array('info' => $info, 'deportes' => $deportes, 'generos' => $generos, 'tarifas' => $tarifas, 'promociones' => $promociones), true);
		$submenu_content = NULL;
		if($info['started'] == '1') $submenu_content = $this->load->view($submenu, array('id' => $id), true);
		
		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta .= '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}






# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function equipos ($id)
	{
		
		//$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->model('App', 'app', TRUE);
		$this->load->helper('jqgrid');
		//print("<pre>");print_r($this->rank->get_ActiveTeams($id));
		//exit();

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}
		

		$info = $this->rank->getRanking($id);
		$rondas = $this->rank_lib->getRounds($id);
		$info['rondas'] = $rondas;




		# Establezco la ronda de la que se visualizarán datos
		if($this->input->post('round') && $this->input->post('round')!="") $ronda_visualizar = $this->input->post('round');
		else {
			$ronda_visualizar_tmp = $this->rank->getRounds($id, 'current');
			//print_r($ronda_visualizar_tmp);
			if(isset($ronda_visualizar_tmp) && count($ronda_visualizar_tmp) > 0 ) $ronda_visualizar = $ronda_visualizar_tmp['id'];
			else $ronda_visualizar = '';
		}
		//echo '--'.$ronda_visualizar;exit();
		
		$equipos = $this->rank_lib->getTeams($id, $ronda_visualizar);
		
		$resultado = array();
		for($i = 1; $i <= $info['groups']; $i++) {
			$resultado[$i] = array();
			for($j = 1; $j <= $info['teams']; $j++) {
				if(count($equipos)>0) {
					foreach($equipos as $equipo) {
						if($equipo['group'] == $i && $equipo['order'] == $j) $resultado[$i][$j] = $equipo;
					}
				}
				if(!isset($resultado[$i][$j])) $resultado[$i][$j] = array('id' => '0', 'group' => $i, 'order' => $j, 'main_user' => '0', 'status' => '0', 'description' => '&nbsp;', 'main_user_description' => '', 'PJ' => '', 'PG' => '', 'PP' => '', 'puntos' => '');
			}
		}
		//print("<pre>");print_r($info);print_r($equipos);print("</pre>");//print_r($resultado);

		
		//print("<pre>");print_r($resultado);print("</pre>");
		
		$submenu = 'ranking/submenu_usuario';
		
		//echo '---'.$info['current_round_id'];
		# Definicion de visualizacion de los botones de accion
		$permisos = array('new' => false, 'detail' => false, 'unsubscribe' => false, 'notification' => false, 'calendar' => false, 'up' => false, 'down' => false);
		foreach($info['rondas'] as $ronda) {if($ronda['started']=='1' && $ronda['finished']!='1') { $permisos['unsubscribe'] = false; $permisos['up'] = false; $permisos['down'] = false; }}
		if($info['current_round_id'] != $ronda_visualizar && $info['current_round_id'] != '0' && $info['current_round_id'] != '') { $permisos['new'] = false; $permisos['unsubscribe'] = false; $permisos['up'] = false; $permisos['down'] = false; }
		
		
		$contenido = $this->load->view('ranking/teams', array('info' => $info, 'resultado' => $resultado, 'permisos' => false, 'ronda_visualizar' => $ronda_visualizar), true);
		
		$submenu_content = NULL;
		if($info['started'] == '1') $submenu_content = $this->load->view($submenu, array('id' => $id), true);
		
		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').link_tag(base_url().'css/ranking.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);
	}










# -------------------------------------------------------------------
#  devuelve el listado de usuarios apuntados al curso
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	function assistants ($id)
	{
		
		//$this->load->model('redux_auth_model', 'users', TRUE);
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->model('App', 'app', TRUE);
		$this->load->helper('jqgrid');
		//print("<pre>");print_r($this->rank->get_ActiveTeams($id));
		//exit();
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			
			# Variable que habilita o no el visualizar información de cursos
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
      }
		}	else {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
		}
		

		$info = $this->rank->getRanking($id);
		$rondas = $this->rank_lib->getRounds($id);
		$info['rondas'] = $rondas;

		###########
		# Función de bajada de un equipo
		if($this->input->post('action') && $this->input->post('action')=="down_team") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			
			$equipo = $this->input->post('selected_team');
			
			//print("<b>INFO</b><pre>");print_r($info);
			$this->rank_lib->moveTeamDown($id, $equipo);
			/*
			print("<b>DATOS</b><pre>");print_r($data);exit();
			exit();
			$data['start_date'] = date($this->config->item('date_db_format'), strtotime($data['start_date']));
			$data['end_date'] = date($this->config->item('date_db_format'), strtotime($data['end_date']));
			
			$this->rank->updateRanking($data);
			*/
      //$this->session->set_userdata('info_message', 'El equipo ha sido desplazado.');
			redirect(site_url('ranking/assistants/'.$id), 'Location'); 
			exit();
		}
		# Fin de  bajada de un equipo
		##########


		###########
		# Función de subida de un equipo
		if($this->input->post('action') && $this->input->post('action')=="up_team") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			
			$equipo = $this->input->post('selected_team');
			
			//print("<b>INFO</b><pre>");print_r($info);
			$this->rank_lib->moveTeamUp($id, $equipo);
			/*
			print("<b>DATOS</b><pre>");print_r($data);exit();
			exit();
			$data['start_date'] = date($this->config->item('date_db_format'), strtotime($data['start_date']));
			$data['end_date'] = date($this->config->item('date_db_format'), strtotime($data['end_date']));
			
			$this->rank->updateRanking($data);
			*/
      //$this->session->set_userdata('info_message', 'El equipo ha sido desplazado.');
			redirect(site_url('ranking/assistants/'.$id), 'Location'); 
			exit();
		}
		# Fin de  bajada de un equipo
		##########


		###########
		# Función de dar de baja un equipo
		if($this->input->post('action') && $this->input->post('action')=="unsubscribe_team") {
			$debug = FALSE;
			$equipo = $this->input->post('selected_team');
			$equipo_info = $this->rank_lib->getTeam($equipo);
			//$equipos = $this->rank_lib->getTeams($id, $info['current_round_id'], '', FALSE);
			$equipos = $this->rank->getRoundScoring($id, $info['current_round_id'], '', FALSE);
			//print("<b>DATOS</b><pre>");print_r($equipos);//print_r($equipo_info);
			
			$this->rank->unsubscribeTeam($equipo);

			$grupo_actual = $equipo_info['group'];
			$orden_actual = $equipo_info['order'];
			//$equipos = $this->rank->getRoundScoring($id, $info['current_round_id'], '', FALSE);
			if($debug) { print("<b>DATOS</b><pre>");print_r($equipos); }//exit();//print_r($equipo_info);
			//exit();
			$lanzado = 0;
			for($i = 1; $i<=$info['groups']; $i++) {
				for($j=1; $j<=$info['teams']; $j++) {
					if($debug) echo '<br>revisando ronda '.$i.' y equipo '.$j.' ';
					if($grupo_actual == $i && $orden_actual == $j) {
						if($debug) echo '<br>localizado equipo a borrar en ronda '.$i.' y equipo '.$j.' ';
						$lanzado = 1;
						$orden = $j;
						$ronda = $i;
						continue;				
					}
					
					if($lanzado==1) {
						if($debug) echo '<br>modificando equipos de ronda '.$i.' y equipo '.$j.' ';
						//$equipos = $this->rank->getRoundScoring($id, $info['current_round_id'], '', FALSE);
						for($k=0; $k<count($equipos); $k++) {
							if($equipos[$k]['group'] == $i && $equipos[$k]['order'] == $j) {
								if($debug) echo '<br>subiendo equipo '.$equipos[$k]['description'].' ('.$equipos[$k]['id'].') en la lista';
								$this->rank_lib->moveTeamUp($id, $equipos[$k]['id'], TRUE);
								//$equipos = $this->rank->getRoundScoring($id, $info['current_round_id'], '', FALSE);
								//print("<b>DATOS</b><pre>");print_r($equipos);exit();
							}
						}						
					}
					$orden = $j;
					$ronda = $i;					
				}
			}
			
			//print("<b>DATOS</b><pre>");print_r($equipos);print_r($equipo_info);exit();

			redirect(site_url('ranking/assistants/'.$id), 'Location'); 
			//exit();
		}
		# Fin de  dar de baja un equipo
		##########


		


		# Establezco la ronda de la que se visualizarán datos
		if($this->input->post('round') && $this->input->post('round')!="") $ronda_visualizar = $this->input->post('round');
		else {
			$ronda_visualizar_tmp = $this->rank->getRounds($id, 'current');
			//print_r($ronda_visualizar_tmp);
			if(isset($ronda_visualizar_tmp) && count($ronda_visualizar_tmp) > 0 ) $ronda_visualizar = $ronda_visualizar_tmp['id'];
			else $ronda_visualizar = '';
		}
		//echo '--'.$ronda_visualizar;
		//exit();
		
		//$equipos = $this->rank_lib->getTeams($id, $ronda_visualizar);
		//print_r($ronda_visualizar_tmp);
		$ronda_visualizar_tmp = $this->rank->getRounds($id, 'current');
		 $reordenar = FALSE;
		if($ronda_visualizar == $ronda_visualizar_tmp['id'] && $ronda_visualizar_tmp['started'] == '1' && $ronda_visualizar_tmp['finished'] == '0') $reordenar = TRUE;
		
		$equipos = $this->rank_lib->getTeams($id, $ronda_visualizar, '', $reordenar); // El TRUE es para que no reordene y grabe la ordenación por resultados, dado que estamos viendo una jornada no iniciada del todo
		//echo $ronda_visualizar_tmp['id'] .'--'. $ronda_visualizar;
		if(!$reordenar && $ronda_visualizar_tmp['id'] != $ronda_visualizar) $equipos = $this->app_common->ordenar_array($equipos, 'group', SORT_ASC, 'puntos', SORT_DESC, 'PG', SORT_DESC, 'SG', SORT_DESC, 'JG', SORT_DESC);

		//print("<pre>");print_r($equipos);print("</pre>");
//$equipos = $this->app_common->ordenar_array($equipos, 'group', SORT_ASC, 'puntos', SORT_DESC, 'PG', SORT_DESC, 'JG', SORT_DESC);

		
		/*
		$equipos_tmp = $equipos;
		if(isset($equipos_tmp) && is_array($equipos_tmp) && count($equipos_tmp) > 0 ) {
			$equipos = $this->app_common->ordenar_array($equipos_tmp, 'group', SORT_ASC, 'puntos', SORT_DESC, 'PG', SORT_DESC, 'SG', SORT_DESC, 'SP', SORT_ASC, 'JG', SORT_DESC, 'JP', SORT_ASC);
			if($info['current_round_id'] == $ronda_visualizar) {
				$grupo_ = 1; $pos_ = 1; $id_ = 0;
				foreach($equipos as $equipo) {
					if($grupo_ != $equipo['group']) $pos_ = 1;
					$grupo_ = $equipo['group'];
						//echo '<br>poniendo equipo '.$equipo['description'].' en posicion '.$pos_;
					//$this->rank->setTeamPosition($id, $equipo['id'], $equipo['group'], $pos_);
					$equipos[$id_]['order'] =  $pos_;
					$pos_++; $id_++;
				}
			}
			
//print("##########<br>###################<br><pre>");print_r($equipos);print("</pre>");
			//if($info['current_round_id'] == $ronda_visualizar) $this->rank->setRoundScoring($id, $ronda_visualizar, $equipos);
		}
		*/
		//$equipos = $this->rank_lib->getTeams($id, $ronda_visualizar);
		//print("*************************<pre>");print_r($equipos);
		$resultado = array();
		for($i = 1; $i <= $info['groups']; $i++) {
			$resultado[$i] = array();
			for($j = 1; $j <= $info['teams']; $j++) {
				if(isset($equipos) && is_array($equipos) && count($equipos) > 0 ) {
					$i_orden=1;
					foreach($equipos as $equipo) {
						//if($equipo['group'] == $i && $equipo['order'] == $j) $resultado[$i][$i_orden] = $equipo;
						if($equipo['group'] == $i) $resultado[$i][$i_orden] = $equipo;
						$i_orden++;
					}
				}
				//if(!isset($resultado[$i][$j])) $resultado[$i][$j] = array('id' => '0', 'group' => $i, 'order' => $j, 'main_user' => '0', 'status' => '0', 'description' => '&nbsp;', 'main_user_description' => '', 'PJ' => '', 'PG' => '', 'PP' => '', 'puntos' => '');
			}
		}
		
		
		//print("#####################<pre>");print_r($info);print_r($resultado);print("</pre>");//print_r($resultado);

		
		//print("<pre>");print_r($resultado);print("</pre>");
		
		$edit_permiso=$this->config->item('ranking_edit_permission');
		if($edit_permiso[$profile->group]) {
			$submenu = 'ranking/submenu_navegacion';
		}	else {
			$submenu = 'ranking/submenu_navegacion';
		}
		
		//echo '---'.$info['current_round_id'];
		# Definicion de visualizacion de los botones de accion
		# Desactivo el subir y bajar de forma global mientras desarrollo algo que permita intercambiar equipos sean de donde sean. Lo de subir y bajar no es efectivo cuando la jornada es nueva y están todos con 0 puntos
		$permisos = array('new' => true, 'detail' => true, 'unsubscribe' => true, 'notification' => true, 'calendar' => true, 'up' => true, 'down' => true);
		foreach($info['rondas'] as $ronda) {if($ronda['started']=='1' && $ronda['finished']!='1') { $permisos['unsubscribe'] = false; $permisos['up'] = false; $permisos['down'] = false; }}
		if($info['current_round_id'] != $ronda_visualizar && $info['current_round_id'] != '0' && $info['current_round_id'] != '') { $permisos['new'] = false; $permisos['unsubscribe'] = false; $permisos['up'] = false; $permisos['down'] = false; }
		
		
		$contenido = $this->load->view('ranking/teams', array('info' => $info, 'resultado' => $resultado, 'permisos' => $permisos, 'ronda_visualizar' => $ronda_visualizar), true);
		
		$submenu_content = NULL;
		if($info['started'] == '1') $submenu_content = $this->load->view($submenu, array('id' => $id), true);
		
		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').link_tag(base_url().'css/ranking.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);
	}











# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de creacion de equipo
# -------------------------------------------------------------------
	function new_team ($id)
	{
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->library('rank_lib');
		$this->load->model('Payment_model', 'payment', TRUE);
		//$this->load->model('Pistas_model', 'pistas', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//$equipos = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
		//$this->rank_lib->calculaJornadas($equipos);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$info = $this->rank->getRanking($id);
		# Compruebo si hay huecos libres
		if($info['current_vacancies'] == 0) {
      $this->session->set_userdata('error_message', 'No hay plazas disponibles en el ranking');
      redirect(site_url('ranking/assistants/'.$id), 'Location'); 
      exit();
		}
		
		$rondas = $this->rank_lib->getRounds($id);
		$info['rondas'] = $rondas;

		###########
		# Función de creacion
		if($this->input->post('action') && $this->input->post('action')=="save") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$data['players'] = array();
			for($i=1; $i < $info['team_mates']; $i++) {
				$id_user = 0;
				if(isset($data['id_user_'.$i])) $id_user = $data['id_user_'.$i];
				$user_desc = 0;
				if(isset($data['user_desc_'.$i])) $user_desc = $data['user_desc_'.$i];
				
				$añadido = array('id_user' => $id_user, 'user_name' => $user_desc, 'user_phone' => '');
				array_push($data['players'], $añadido);
			}
			
			//print("<pre>");print_r($info);print_r($hueco);print_r($data);exit();
			//exit();
			$hueco = $this->rank->getNextSlot($id);
			//print("<pre>");print_r($hueco);exit();
			$data['group'] = $hueco['group'];
			$data['order'] = $hueco['order'];
			if($this->rank_lib->newTeam($id, $data)) {
				$this->session->set_userdata('info_message', 'Equipo a&ntilde;adido al ranking.');
				
			}	else {
				$this->session->set_userdata('error_message', 'Equipo NO a&ntilde;adido al ranking.');
			}
			redirect(site_url('ranking/assistants/'.$id), 'Location'); 
			exit();
		}
		# Fin del creacion del registro
		##########



		$vista = 'ranking/new_team';
		
		//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		$contenido = $this->load->view($vista, array('info' => $info), true);
		$submenu_content = NULL;
		
		//$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}











# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de creacion de equipo
# -------------------------------------------------------------------
	function team ($id_team)
	{
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->library('rank_lib');
		$this->load->model('Payment_model', 'payment', TRUE);
		//$this->load->model('Pistas_model', 'pistas', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//$equipos = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
		//$this->rank_lib->calculaJornadas($equipos);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$info = $this->rank_lib->getTeam($id_team);

		###########
		# Función de creacion
		if($this->input->post('action') && $this->input->post('action')=="save") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$data['players'] = array();
			for($i=1; $i < $info['team_mates']; $i++) {
				$id_user = 0;
				if(isset($data['id_user_'.$i])) $id_user = $data['id_user_'.$i];
				$user_desc = 0;
				if(isset($data['user_desc_'.$i])) $user_desc = $data['user_desc_'.$i];
				
				$añadido = array('id_user' => $id_user, 'user_desc' => $user_desc, 'user_phone' => '');
				array_push($data['players'], $añadido);
			}
			
			//print("<pre>");print_r($info);print_r($hueco);print_r($data);exit();
			//exit();
			$hueco = $this->rank->getNextSlot($id);
			$data['group'] = $hueco['group'];
			$data['order'] = $hueco['order'];
			$this->rank_lib->newTeam($id, $data);
			
			
      $this->session->set_userdata('info_message', 'Equipo a&ntilde;adido al ranking.');
			redirect(site_url('ranking/assistants/'.$id), 'Location'); 
			exit();
		}
		# Fin del creacion del registro
		##########



		$vista = 'ranking/detail_team';
		
		//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		$contenido = $this->load->view($vista, array('info' => $info), true);
		$submenu_content = NULL;
		
		//$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta = '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}














# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de partidos del ranking
# -------------------------------------------------------------------
	function matchs ($id)
	{
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->helper('jqgrid');
		//$this->load->model('Redux_auth_model', 'usuario', TRUE);
		//$this->load->model('Pistas_model', 'pistas', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//$equipos = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
		//$this->rank_lib->calculaJornadas($equipos);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}


		# Recuperando información general del ranking
		$info = $this->rank->getRanking($id);
		$rondas = $this->rank_lib->getRounds($id);
		$info['rondas'] = $rondas;
		//print("<pre>");print_r($info);print("</pre>");
		# Establezco la ronda de la que se visualizarán datos
		if($this->input->post('round') && $this->input->post('round')!="") $ronda_visualizar = $this->input->post('round');
		else {
			$ronda_visualizar_tmp = $this->rank->getRounds($id, 'current');
			//print_r($ronda_visualizar_tmp);
			if(isset($ronda_visualizar_tmp) && count($ronda_visualizar_tmp) > 0 ) $ronda_visualizar = $ronda_visualizar_tmp['id'];
			else $ronda_visualizar = '';
		}
		
		//echo '--'.$ronda_visualizar;
		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);

		###########
		# Función de guardado
		if($this->input->post('action') && $this->input->post('action')=="save") {
			
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$data['start_date'] = date($this->config->item('date_db_format'), strtotime($data['start_date']));
			$data['end_date'] = date($this->config->item('date_db_format'), strtotime($data['end_date']));
			//print("<pre>");print_r($data);exit();
			
			$this->rank->updateRanking($data);
			
      $this->session->set_userdata('info_message', 'Los datos han sido actualizados.');
			redirect(site_url('ranking/detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del guardado del registro
		##########

		
		$edit_permiso=$this->config->item('ranking_edit_permission');
		$permisos = array();
		
		$submenu = 'ranking/submenu_navegacion';
		
		$colmodel = "	{name:'id', index:'id', width:1, align:'center',hidden:true},
						   		{name:'group', index:'group', width:10, align:'center'},
						   		{name:'equipo1', index:'team1', width:25, align:'center'},
						   		{name:'equipo2', index:'team2', width:25, align:'center'},
						   		{name:'estado', index:'status', width:10, align:'center'},
						   		{name:'fecha', index:'estimated_date', width:10, align:'center'},
						   		{name:'ganador', index:'winner', width:16, align:'center'},
						   		{name:'resultado', index:'resultado', width:16, align:'center'},
								";
		$colnames = "'Id','Grupo','Equipo 1','Equipo 2','Estado','Fecha','Ganador','Resultado'";

		foreach($info['rondas'] as $ronda) { if($ronda['id']==$ronda_visualizar) $numero_ronda = $ronda['round']; }
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "ranking/jqgrid_matchs/".$id."/".$ronda_visualizar, 
				'title' => 'Listado de partidos (jornada '.$numero_ronda.')', 
				'default_orderfield' => 'group', 
				'default_orderway' => 'asc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		
				//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		//$contenido = $this->load->view('ranking/matchs', array('grid_code' => $grid_code, 'info' => $info, 'permisos' => $permisos, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true);
		$submenu_content = NULL;
		if($info['started'] == '1') $submenu_content = $this->load->view($submenu, array('id' => $id), true);
		
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $this->load->view('ranking/matchs', array('grid_code' => $grid_code, 'info' => $info, 'ronda_visualizar' => $ronda_visualizar, 'permisos' => $permisos, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true),		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}












# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de partidos del ranking
# -------------------------------------------------------------------
	function partidos ($id)
	{
		//exit('aaaa');
		$this->load->model('Ranking_model', 'rank', TRUE);
		$this->load->helper('jqgrid');
		//$this->load->model('Redux_auth_model', 'usuario', TRUE);
		//$this->load->model('Pistas_model', 'pistas', TRUE);
		//$this->load->model('Reservas_model', 'reservas', TRUE);
		
		//$equipos = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
		//$this->rank_lib->calculaJornadas($equipos);

		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;

		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}


		# Recuperando información general del ranking
		$info = $this->rank->getRanking($id);
		$rondas = $this->rank_lib->getRounds($id);
		$info['rondas'] = $rondas;
		//print("<pre>");print_r($info);print("</pre>");
		# Establezco la ronda de la que se visualizarán datos
		if($this->input->post('round') && $this->input->post('round')!="") $ronda_visualizar = $this->input->post('round');
		else {
			$ronda_visualizar_tmp = $this->rank->getRounds($id, 'current');
			//print_r($ronda_visualizar_tmp);
			if(isset($ronda_visualizar_tmp) && count($ronda_visualizar_tmp) > 0 ) $ronda_visualizar = $ronda_visualizar_tmp['id'];
			else $ronda_visualizar = '';
		}
		
		//echo '--'.$ronda_visualizar;
		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);

		
		
		$edit_permiso = FALSE;
		$permisos = array();
		
		$submenu = 'ranking/submenu_usuario';
		
		$colmodel = "	{name:'id', index:'id', width:1, align:'center',hidden:true},
						   		{name:'group', index:'group', width:10, align:'center'},
						   		{name:'equipo1', index:'team1', width:25, align:'center'},
						   		{name:'equipo2', index:'team2', width:25, align:'center'},
						   		{name:'estado', index:'status', width:10, align:'center'},
						   		{name:'ganador', index:'winner', width:16, align:'center'},
						   		{name:'resultado', index:'resultado', width:10, align:'center'},
								";
		$colnames = "'Id','Grupo','Equipo 1','Equipo 2','Estado','Ganador','Resultado'";

		foreach($info['rondas'] as $ronda) { if($ronda['id']==$ronda_visualizar) $numero_ronda = $ronda['round']; }
		#Array de datos para el grid
		$para_grid = array(
				'colmodel' => $colmodel, 
				'colnames' => $colnames, 
				'data_url' => "ranking/jqgrid_matchs/".$id."/".$ronda_visualizar."/".$user_id, 
				'title' => 'Listado de partidos (jornada '.$ronda_visualizar.')', 
				'default_orderfield' => 'group', 
				'default_orderway' => 'asc', 
				'row_numbers' => 'false', 
				'default_rows' => '20',
				'mainwidth' => '960',
				'row_list_options' => '10,20,50',
		);
		
		$grid_code = '<div style="position:relative; width: 960px; height: 600px;">'.jqgrid_creator($para_grid).'</div>';
		
		
				//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		//$contenido = $this->load->view('ranking/matchs', array('grid_code' => $grid_code, 'info' => $info, 'permisos' => $permisos, 'enable_buttons' => TRUE, 'menu_lateral' => NULL), true);
		$submenu_content = NULL;
		if($info['started'] == '1') $submenu_content = $this->load->view($submenu, array('id' => $id), true);
		
		$form_action = 'ranking/partidos/';
		
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => TRUE), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $this->load->view('ranking/matchs', array('grid_code' => $grid_code, 'info' => $info, 'ronda_visualizar' => $ronda_visualizar, 'permisos' => $permisos, 'enable_buttons' => FALSE, 'menu_lateral' => NULL, 'form_action' => $form_action), true),		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}









# -------------------------------------------------------------------
# -------------------------------------------------------------------
# Pantalla de detalle del ranking
# -------------------------------------------------------------------
	function match_detail ($id)
	{
		$this->load->model('Ranking_model', 'rank', TRUE);


		# Defino el usuario activo, por sesion o por id, según si es anónimo o no..
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
			$user_group=$profile->group;
			
			# Variable que habilita o no el poder ver los rankings
			$permiso=$this->config->item('ranking_permission');
			if(!$permiso[$profile->group]) {
        $this->session->set_userdata('error_message', 'Acceso a zona no habilitada');
        redirect(site_url(), 'Location'); 
        exit();
			}
		}	else {
			redirect(site_url(), 'Location'); 
			exit();
		}

		$partido = $this->rank_lib->getMatchInfo($id);
		$info = $this->rank->getRanking($partido['id_ranking']);
		//print("<pre>");print_r($partido);print("</pre>");//print_r($info);exit();
		
		# Recuperando información general del ranking
		//$rondas = $this->rank_lib->getRounds($id);	// Recupera las rondas
		//print("<pre>");print_r($rondas);print("</pre>");
		//$info['rondas'] = $rondas;
		//print("<pre>");print_r($info);print("</pre>");

		
		//print("<pre>");print_r($this->session->userdata('bookingInterval'));print("</pre>");
		//echo $this->load->view('reservas/simple_result', array('availability' => $this->reservas->availability), true);

		###########
		# Función de guardado
		if($this->input->post('action') && $this->input->post('action')=="save") {
			//print("<pre>");print_r($_POST);print("</pre>");//exit();
			$data = array();
			foreach($_POST as $code => $value) $data[$code] = $this->input->post($code);
			$tanteo = array();
			foreach($data as $code => $value) {
				if(strstr($code, 'result_')) {
					$datos = explode('_', $code);
					if(!isset($tanteo[$datos[2]]) || !is_array($tanteo[$datos[2]])) $tanteo[$datos[2]] = array();
					$tanteo[$datos[2]][$datos[1]] = $value;
				}
			}
			
			$estado = 5;
			if(isset($data['lesion']) && $data['lesion']!='') {
				$estado = 6;
				$perdedor = $data['lesionado'];
				if($perdedor == $partido['team1']) {
					$vencedor = $partido['team2'];
					$tanteo = array(array('1' => $this->config->item('ranking_lost_lesion_perdedor_tanteo'), '2' => $this->config->item('ranking_lost_lesion_ganador_tanteo')), array('1' => $this->config->item('ranking_lost_lesion_perdedor_tanteo'), '2' => $this->config->item('ranking_lost_lesion_ganador_tanteo')), array('1' => 0, '2' => 0));
				} else {
					$vencedor = $partido['team1'];
					$tanteo = array(array('1' => $this->config->item('ranking_lost_lesion_ganador_tanteo'), '2' => $this->config->item('ranking_lost_lesion_perdedor_tanteo')), array('1' => $this->config->item('ranking_lost_lesion_ganador_tanteo'), '2' => $this->config->item('ranking_lost_lesion_perdedor_tanteo')), array('1' => 0, '2' => 0));
				}
			}
			if(isset($data['ausencia']) && $data['ausencia']!='') {
				$estado = 8;
				$perdedor = $data['ausente'];
				if($perdedor == $partido['team1']) {
					$vencedor = $partido['team2'];
					$tanteo = array(array('1' => $this->config->item('ranking_lost_ausente_perdedor_tanteo'), '2' => $this->config->item('ranking_lost_ausente_ganador_tanteo')), array('1' => $this->config->item('ranking_lost_ausente_perdedor_tanteo'), '2' => $this->config->item('ranking_lost_ausente_ganador_tanteo')), array('1' => 0, '2' => 0));
				} else {
					$vencedor = $partido['team1'];
					$tanteo = array(array('1' => $this->config->item('ranking_lost_ausente_ganador_tanteo'), '2' => $this->config->item('ranking_lost_ausente_perdedor_tanteo')), array('1' => $this->config->item('ranking_lost_ausente_ganador_tanteo'), '2' => $this->config->item('ranking_lost_ausente_perdedor_tanteo')), array('1' => 0, '2' => 0));
				}
			}
			if($estado==5) {
				$contador = array('1' => 0, '2' => 0);
				foreach($tanteo as $set) {
					if($set[1] > $set[2]) $contador[1]++;
					elseif($set[1] < $set[2]) $contador[2]++;
					else {$contador[1]++; $contador[2]++; }
					
					if($contador[1] > $contador[2]) $vencedor = $partido['team1'];
					else  $vencedor = $partido['team2'];
				}
			}
			$datos = array(
				'tanteo' => $tanteo,
				'fecha' => date($this->config->item('date_db_format'), strtotime($data['played_date'])),
				'vencedor' => $vencedor, 
				'estado' => $estado
			);
			//print($vencedor."<pre>");print_r($datos);print_r($data);print_r($tanteo);exit();
			$this->rank->setMatchResult($id, $datos);
			
			
      $this->session->set_userdata('info_message', 'Los datos del partido han sido almacenados.');
			redirect(site_url('ranking/match_detail/'.$id), 'Location'); 
			exit();
		}
		# Fin del guardado del registro
		##########

		
		//print("<pre>");print_r($info);print("</pre>");
		$estados = $this->rank->getMatchsStatus();
		
		//$rondas = $this->rank_lib->createRounds($info);	// Crea las rondas desde cero
		//print("<pre>");print_r($rondas);print("</pre>");
		
		//$niveles = $this->lessons->getLevelsArray();
		
		$enable_add = TRUE;
		if($partido['status'] >= 5) $enable_add = FALSE;
		
		$vista = 'ranking/detail_match';
		$submenu = 'ranking/submenu_navegacion';
		
		//print("<pre>");//.$this->reservas->get_max_booking_date());
		//print_r($info);
		//print_r($deportes);
		$contenido = $this->load->view($vista, array('info' => $info, 'partido' => $partido, 'estados' => $estados), true);
		$submenu_content = NULL;
		if($info['started'] == '1') $submenu_content = $this->load->view($submenu, array('id' => $partido['id_ranking']), true);
		
		$extra_meta = link_tag(base_url().'css/jquery.tooltip.css').'<script src="'.base_url().'js/jquery.tooltip.js" type="text/javascript"></script>';
		$extra_meta .= '<script type="text/javascript" src="'.base_url().'js/jquery.meio.mask.min.js"></script>'."\r\n";
		$data=array(
			'meta' => $this->load->view('meta', array('enable_grid'=>FALSE, 'extra' => $extra_meta), true),
			//'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'header_style' => 'cabecera_con_submenu', 'enable_submenu' => $this->load->view('lessons/submenu_navegacion', array(), true)), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in() , 'enable_submenu' => $submenu_content), true),
			'menu' => $this->load->view('menu', '', true),
			'footer' => $this->load->view('footer', '', true),
			//'form_name' => 'frmDetail',
			'main_content' => $contenido,		
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		

    $this->load->view('main', $data);

	}






}

/* End of file ranking.php */
/* Location: ./system/application/controllers/ranking.php */