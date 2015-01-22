<?php
class Activities_model extends Model {

    function Activities_model()
    {
        // Call the Model constructor
        parent::Model();
    }

    /* FUNCTION PARA CAPTURAR TODAS LAS ACTIVIDADES POR DÍA*/
    function getAvailableActivities($fecha, $day_of_week, $id_sport)
    {
    	$filtro_sport = true;
    	if ($id_sport == null)
    	{
    		$filtro_sport = false;
    	}
    	/*CAPTURO LOS DATOS DEL USUARIO*/
		$sql_select = "SELECT a.id, a.id_sport, a.id_court, a.description, a.active, a.id_manager, a.weekday,". 
					"a.start_time, a.end_time, s.description as nombre_deporte, c.name as nombre_pista, ".
					"m.first_name as nombre_profesor, m.last_name as apellido_profesor,".
					"'periodicidad' as periodicidad_curso, 'fecha curso' as fecha_curso ".
					"FROM activities a, zz_sports s, courts c, meta m ".
					"where a.id_sport = s.id ".
					"and a.id_court = c.id ".
					"and a.id_manager = m.user_id ".
					"and weekday = ? ".
					"and a.active = 1";
		if ($filtro_sport)
		{
			$sql_select+= " and a.id_sport = ?";
		}
		if ($filtro_sport)
		{
			$query = $this->db->query($sql_select, array($day_of_week, $id_sport));
		}
		else 
		{
			$query = $this->db->query($sql_select, array($day_of_week));
		}
		
		//if($day_of_week=="2") echo $this->db->last_query();
		if ($query->num_rows() > 0) 
		{
			foreach ($query->result() as $row)
			{
			/* RELLENO EL ARRAY CON LOS DATOS DE LOS CURSOS PARA DEVOLVER AL CONTROLLER */
			$array_activities[$row->id] = array(
                   'id'  => $row->id,
                   'id_sport'  => $row->id_sport,
                   'id_court'  => $row->id_court,
                   'description'  => $row->description,
                   'active'  => $row->active,
                   'id_manager'  => $row->id_manager,
                   'weekday'  => $row->weekday,
                   'start_time'  => $row->start_time,
                   'end_time'  => $row->end_time,
                   'nombre_deporte'  => $row->nombre_deporte,
                   'nombre_pista'  => $row->nombre_pista,
                   'nombre_profesor'  => $row->nombre_profesor,
                   'apellido_profesor'  => $row->apellido_profesor,
                   'periodicidad'  => $row->periodicidad_curso,
                   'fecha_curso'  => $row->fecha_curso);
			}
		}
		else
		{
			return null;
			/* NO ENCUENTRA NINGUN CURSO PARA ESTA FECHA*/	
		}
		return $array_activities;
    }
    
    /* FUNCTION PARA CAPTURAR TODAS LAS ACTIVIDADES POR DÍA*/
    function getActivity($id_activity)
    {
    	/*CAPTURO LOS DATOS DEL CURSO*/
    	//OLD QUERY
		/*$sql_select = "SELECT a.id, a.id_sport, a.id_court, a.description, a.active, a.id_manager, a.weekday,". 
					"a.capacity, a.used_vacancies, ".
					"a.start_time, a.end_time, s.description as nombre_deporte, c.name as nombre_pista, ".
					"m.first_name as nombre_profesor, m.last_name as apellido_profesor,".
					"if (ase.type=1, 'Anual','Día') as periodicidad_curso, ase.date as fecha_curso ".
					"FROM activities a, zz_sports s, courts c, meta m, activities_schedule_exceptions ase ".
					"where a.id_sport = s.id ".
					"and a.id_court = c.id ".
					"and a.id_manager = m.user_id ".
					"and a.id = ase.id_activity ".
					"and a.id = ?";*/
    	//NEW QUERY
    	$sql_select = "SELECT a.id, a.id_sport, a.id_court, a.description, a.active, a.id_manager, a.weekday,". 
					"a.capacity, a.used_vacancies, a.seasson_cost, a.month_cost, a.individual_cost, ".
					"a.start_time, a.end_time, s.description as nombre_deporte, c.name as nombre_pista, ".
					"m.first_name as nombre_profesor, m.last_name as apellido_profesor ".
					"FROM activities a, zz_sports s, courts c, meta m ".
					"where a.id_sport = s.id ".
					"and a.id_court = c.id ".
					"and a.id_manager = m.user_id ".
					"and a.id = ?";
		$query = $this->db->query($sql_select, array($id_activity));
		
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			/* RELLENO EL ARRAY CON LOS DATOS DE LOS CURSOS PARA DEVOLVER AL CONTROLLER */
			$diaSemana = "No seleccionado";
			switch($row->weekday){
			   case 1:
			      $diaSemana = "Lunes";
			      break;
			   case 2:
			      $diaSemana = "Martes";
			      break;
			   case 3:
			      $diaSemana = "Miércoles";
			      break;
			   case 4:
			      $diaSemana = "Jueves";
			      break;
			   case 5:
			      $diaSemana = "Viernes";
			      break;
			   case 6:
			      $diaSemana = "Sábado";
			      break;
			   case 7:
			      $diaSemana = "Domingo";
			      break;
			   default:
			      $diaSemana = "No seleccionado";
			      break;
			} 
			$array_activity = array(
                   'id'  => $row->id,
                   'id_sport'  => $row->id_sport,
                   'id_court'  => $row->id_court,
                   'description'  => $row->description,
                   'active'  => $row->active,
                   'id_manager'  => $row->id_manager,
                   'weekday'  => $row->weekday,
                   'start_time'  => $row->start_time,
                   'end_time'  => $row->end_time,
                   'nombre_deporte'  => $row->nombre_deporte,
                   'nombre_pista'  => $row->nombre_pista,
                   'nombre_profesor'  => $row->nombre_profesor,
                   'apellido_profesor'  => $row->apellido_profesor,
                   //'periodicidad'  => $row->periodicidad_curso,
                   'diaSemana'  => $diaSemana,
                   'seasson_cost'  => $row->seasson_cost,
                   'month_cost'  => $row->month_cost,
                   'individual_cost'  => $row->individual_cost,
                   'capacity'  => $row->capacity,
                   'used_vacancies'  => $row->used_vacancies,
                   'free_vacancies'  => ($row->capacity - $row->used_vacancies));
                   //'fecha_curso'  => date('d-m-Y', strtotime($row->fecha_curso)));
		}
		else
		{
			return null;
			/* NO ENCUENTRA NINGUN CURSO PARA ESTA FECHA*/	
		}
		return $array_activity;
    }    

    /* FUNCTION PARA VALIDAR UNA RESERVA*/
    function save_reserve_activity($activity, $user, $date_activity, $all_activity)
    {		
		if ($all_activity == 1)
		{
    		$data = array('id_activity' => $activity['id'], 
    				'id_user' => $activity['id'], 
    				'user_desc' => $user['first_name'].' '.$user['last_name'], 
    				'user_phone' => $user['phone'], 
    				'subscription_type' => 1, 
    				'initial_date' => date(date(DATETIME_DB)), 
    				'active' => 1,
					'create_time' => date(date(DATETIME_DB)), 
					'create_user' => $this->session->userdata('user_id'));
		}
		else
		{
			//WARNING, FALTA CHEQUEAR QUE LA FECHA SELECCIONADA ES CORRECTA//
    		$data = array('id_activity' => $activity['id'], 
    				'id_user' => $activity['id'], 
    				'user_desc' => $user['first_name'].' '.$user['last_name'], 
    				'user_phone' => $user['phone'], 
    				'subscription_type' => 1, 
    				'initial_date' => date(date($date_activity)), 
    				'end_date' => date(date($date_activity)), 
    				'active' => 1,
					'create_time' => date(date(DATETIME_DB)), 
					'create_user' => $this->session->userdata('user_id'));			
		}	
    		
		$this->db->insert('activities_subscriptions',$data);
		return ($this->db->affected_rows() >= 1) ? true : false;
    }    
    
    /* FUNCTION PARA ACTUALIZAR OCUPACIÓN DE UN CURSO*/
    function update_activity_vacancies($id_activity, $new_value)
    {	
    	$data = array( 
    			'used_vacancies' => 'used_vacancies' + $new_value,
				'modify_time' => date(date(DATETIME_DB)), 
				'modify_user' => $this->session->userdata('user_id'));
    		
		$this->db->update('activities',$data, array('id'=>$id_activity));
		return ($this->db->affected_rows() >= 1) ? true : false;
    }
    

}
?>