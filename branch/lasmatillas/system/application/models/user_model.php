<?php
class User_model extends Model {

    var $email   = '';
    var $password = '';

    function User_model()
    {
        // Call the Model constructor
        parent::Model();
    }
    
    function login()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $sql = "SELECT id, name FROM users WHERE mail = ? "; 

				$query = $this->db->query($sql, array($this->email));
				//$row = $query->row();
				
				if ($query->num_rows() > 0) {
					
					$row = $query->row();

					$newdata = array(
                   'user_id'  => $row->id,
                   'user_name'  => $row->name,
                   'email'     => $this->email,
                   'logged_in' => TRUE
          );

					$this->session->set_userdata($newdata);
					return TRUE;
					
				} else {
					
					$newdata = array(
                   'user_id'  => 0,
                   'error'     => "login_fail",
                   'logged_in' => FALSE
          );

					$this->session->set_userdata($newdata);
					return FALSE;
					
				}
    }

    function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function insert_entry()
    {
        $this->title   = $_POST['title']; // please read the below note
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->insert('entries', $this);
    }

    function update_entry()
    {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }

    /* FUNCTION PARA CAMBIAR EL ESTADO DE UN USUARIO*/
    function change_status($id_user)
    {
    	$user_activated = '0';
    	/*COMPRUEBO VALOR ACTUAL DEL USUARIO*/
		$sql_select = "select active from users where id = ?";
		$query = $this->db->query($sql_select, array($id_user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			/* ACTUALIZO EL USUARIO CAMBIANDO EL VALOR QUE TUVIESE */
			if ($row->active == '0') $user_activated = '1';
			else $user_activated = '0';
			$data = array('active' => $user_activated);
			$this->db->update('users',$data, array('id' => $id_user));
			return ($this->db->affected_rows() == 1) ? true : false;
		}
		else
		{
			return false;
			/* NO ENCUENTRA EL USUARIO, VOLVER A PÁGINA ANTERIOR MOSTRANDO UN MENSAJE*/	
		}
    }

    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_user($id_user)
    {
    	$user_activated = '0';
    	/*CAPTURO LOS DATOS DEL USUARIO*/
		$sql_select = "SELECT u.id, m.first_name, m.last_name, u.group_id, g.description as description_group, u.email, u.active, m.phone FROM users u, meta m, groups g where u.id = m.user_id and u.group_id = g.id and u.id = ?";
		$query = $this->db->query($sql_select, array($id_user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			/* RELLENO EL ARRAY CON LOS DATOS DEL USUARIO PARA DEVOLVER AL CONTROLLER */
			$newdata = array(
                   'user_id'  => $row->id,
                   'user_name'  => $row->first_name,
                   'user_lastname'  => $row->last_name,
                   'group_id'  => $row->group_id,
                   'group_description'  => $row->description_group,
                   'user_email'  => $row->email,
                   'user_active'  => $row->active,
                   'user_phone'  => $row->phone);
			/* DEVUELVO EL ARRAY */
			return $newdata;
		}
		else
		{
			return null;
			/* NO ENCUENTRA EL USUARIO, VOLVER A PÁGINA ANTERIOR MOSTRANDO UN MENSAJE*/	
		}
    }

    /* FUNCTION PARA ACTUALIZAR LOS DATOS DE UN USUARIO*/
    function save_user($array_user)
    {
    	$result_update = false;
    	/*NECESITO ACTUALIZAR DOS TABLAS*/
    	/* PRIMERO TABLA USERS */
		$data_users = array('group_id' => $array_user['group_id'],
							'email' => $array_user['email'],
							'active' => $array_user['active'], 'modify_time' => date(date(DATETIME_DB)), 'modify_user' => $this->session->userdata('email'));
		$data_meta = array('first_name' => $array_user['first_name'],
							'last_name' => $array_user['last_name'],
							'phone' => $array_user['phone'], 'modify_time' => date(date(DATETIME_DB)), 'modify_user' => $this->session->userdata('email'));
		
		$this->db->update('users',$data_users, array('id' => $array_user['id']));
		if ($this->db->affected_rows() == 1) $result_update = true;
		$this->db->update('meta',$data_meta, array('user_id' => $array_user['id']));
		if ($this->db->affected_rows() == 1) $result_update = true;
		
		return $result_update;
		
    }

    /* FUNCTION PARA INSERTAR UN USUARIO*/
    function new_user($array_user)
    {
    	/*NECESITO INSERTAR EN DOS TABLAS*/
    	/* PRIMERO TABLA USERS */
    	$data = array(
			  'password' => $array_user['password'], 
			  'email'    => $array_user['email'],
			  'active'    => $array_user['active'],
			  'group_id' => $array_user['group_id'],
			  'group_id' => $array_user['group_id'],
			  'create_user' => $this->session->userdata('email'),
			  'create_time' => date(DATETIME_DB),
			  'ip_address' => $this->input->ip_address()
			  );  
		$this->db->insert('users', $data);
		$id = $this->db->insert_id();
		$data_meta = array('first_name' => $array_user['first_name'],
							'user_id' => $id,
							'last_name' => $array_user['last_name'],
							'phone' => $array_user['phone'],
							'create_user' => 0,
							'create_time' => date(DATETIME_DB));		
		$this->db->insert('meta', $data_meta);
		
		
		return $id;
		
    }
    
    

}
?>