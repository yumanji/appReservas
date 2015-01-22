<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */
 
/**
* redux_auth_model
*/
class redux_auth_model extends Model
{
	/**
	 * Holds an array of tables used in
	 * redux.
	 *
	 * @var string
	 **/
	public $tables = array();
	
	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;
	
	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;
	
	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;
	
	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->config('redux_auth');
		$this->tables  = $this->config->item('tables');
		$this->columns = $this->config->item('columns');
		$this->CI =& get_instance();
	}
	
	/**
	 * Misc functions
	 * 
	 * Hash password : Hashes the password to be stored in the database.
     * Hash password db : This function takes a password and validates it
     * against an entry in the users table.
     * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */
	 
	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password($password = false)
	{
	    $salt_length = $this->config->item('salt_length');
	    
	    if ($password === false)
	    {
	        return false;
	    }
	    
		$salt = $this->salt();
		
		$password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
		
		return $password;		
	}
	
	/**
	 * This function takes a password and validates it
     * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password_db($identity = false, $password = false, $identity_column = false)
	{
	    if(!isset($identity_column)) $identity_column   = $this->config->item('identity');
	    $users_table       = $this->tables['users'];
	    $salt_length       = $this->config->item('salt_length');
	    if ($identity === false || $password === false)
	    {
	        return false;
	    }
	    
	    $query  = $this->db->select('password')
                    	   ->where($identity_column, $identity)
                    	   ->limit(1)
                    	   ->get($users_table);
            
        $result = $query->row();
      log_message('debug', 'SQL: '.$this->db->last_query());  
		if ($query->num_rows() !== 1)
		{
				log_message('debug', 'Usuario con codigo '.$identity.' no localizado');  
		    return false;
	    }
	    
		$salt = substr($result->password, 0, $salt_length);

		$password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
        
	    //exit("BB".$password);
		return $password;
	}
	
	/**
	 * Generates a random salt value.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->config->item('salt_length'));
	}
    
	/**
	 * Activation functions
	 * 
     * Activate : Validates and removes activation code.
     * Deactivae : Updates a users row with an activation code.
	 *
	 * @author Mathew
	 */
	
	/**
	 * activate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($code = false)
	{
	    $identity_column = $this->config->item('identity');
	    $users_table     = $this->tables['users'];
	    
	    if ($code === false)
	    {
	        return false;
	    }
	  
	    $query = $this->db->select($identity_column)
                	      ->where('activation_code', $code)
                	      ->limit(1)
                	      ->get($users_table);
                	      
		$result = $query->row();
     //print($this->db->last_query());
		if ($query->num_rows() !== 1)
		{
		    return false;
		}
	    
		$identity = $result->{$identity_column};
     //echo '<br>'.$identity_column.' - '.$identity;
     //exit();   
		
		$data = array('activation_code' => '', 'active' => 1,  'modify_time' => date(DATETIME_DB));
        
		$this->db->update($users_table, $data, array($identity_column => $identity));
		
		return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	/**
	 * Deactivate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($id = false)
	{
	    $users_table = $this->tables['users'];
	    
	    if ($id === false)
	    {
	        return false;
	    }
	    
		$activation_code = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		
		$data = array('activation_code' => $activation_code);
        
		$this->db->update($users_table, $data, array('id' => $id));
		
		return ($this->db->affected_rows() == 1) ? true : false;
	}

	/**
	 * change password
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function change_password($identity = false, $old = false, $new = false)
	{
	    $identity_column   = $this->config->item('identity');
	    $users_table       = $this->tables['users'];
	    
	    if ($identity === false || $old === false || $new === false)
	    {
	        return false;
	    }
	    
	    $query  = $this->db->select('password')
                    	   ->where($identity_column, $identity)
                    	   ->limit(1)
                    	   ->get($users_table);
                    	   
	    $result = $query->row();

	    $db_password = $result->password; 
	    $old         = $this->hash_password_db($identity, $old);
	    $new         = $this->hash_password($new);

	    if ($db_password === $old)
	    {
	        $data = array('password' => $new);
	        
	        $this->db->update($users_table, $data, array($identity_column => $identity));
	        
	        return ($this->db->affected_rows() == 1) ? true : false;
	    }
	    
	    return false;
	}
	/**
	 * change password
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function change_password_admin($identity = false, $new = false)
	{
	    $identity_column   = 'id';
	    $users_table       = $this->tables['users'];
	    //echo $identity.' - '.$new.'<br>';
	    if ($identity === false || $new === false)
	    {
	        return false;
	    }
	    $new         = $this->hash_password($new);
	    //echo $identity.' - '.$new.'<br>';
	    
      $data = array('password' => $new);
      
      $this->db->update($users_table, $data, array($identity_column => $identity));
      //print($this->db->last_query());
      return ($this->db->affected_rows() == 1) ? true : false;
	    
	    return false;
	}
	
	/**
	 * Checks username.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function username_check($username = false)
	{
	    $users_table = $this->tables['users'];
	    
	    if ($username === false)
	    {
	        return false;
	    }
	    
	    $query = $this->db->select('id')
                           ->where('username', $username)
                           ->limit(1)
                           ->get($users_table);
		
		if ($query->num_rows() == 1)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks email.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function email_check($email = false)
	{
	    $users_table = $this->tables['users'];
	    
	    	
	    if ($email === false)
	    {
	        return false;
	    }
	    if (trim($email) == '') {
	    	//exit('aa');
	    	return false;
	    	
	    }
	    	//exit($email.'bb');
	    
	    $query = $this->db->select('id')
                           ->where('email', $email)
                           ->limit(1)
                           ->get($users_table);
		
		if ($query->num_rows() == 1)
		{
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * NIF email.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function nif_check($nif = false)
	{
	    $users_table = $this->tables['meta'];
	    
	    	
	    if ($nif === false)
	    {
	        return false;
	    }
	    if (trim($nif) == '') {
	    	//exit('aa');
	    	return false;
	    	
	    }
	    	//exit($email.'bb');
	    
	    $query = $this->db->select('id')
                           ->where('nif', $nif)
                           ->limit(1)
                           ->get($users_table);
		
		if ($query->num_rows() == 1)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Identity check
	 *
	 * @return void
	 * @author Mathew
	 **/
	protected function identity_check($identity = false, $identity_column = false)
	{
	    //$identity_column = $this->config->item('identity');
	    $users_table     = $this->tables['users'];
	    
	    if ($identity === false)
	    {
	        return false;
	    }
	    
	    $query = $this->db->select('id')
                           ->where($identity_column, $identity)
                           ->limit(1)
                           ->get($users_table);
		log_message('debug', 'SQL: '.$this->db->last_query());
		if ($query->num_rows() == 1)
		{
			return true;
		}
		
		return false;
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password($email = false, $codigo = false)
	{
	    $users_table = $this->tables['users'];
	    
	    if ($email === false && $codigo === false)
	    {
	        return false;
	    }
	    
	    if ($codigo != false) {
		    $query = $this->db->select('forgotten_password_code')
	                    	   ->where('id', $codigo)
	                    	   ->limit(1)
	                    	   ->get($users_table);
	    } elseif ($email != false) {
		    $query = $this->db->select('forgotten_password_code')
	                    	   ->where('email', $email)
	                    	   ->get($users_table);	 
	       if ($query->num_rows() > 1) return false;            	      	
	    }
            
        $result = $query->row();
		
		$code = $result->forgotten_password_code;

			//echo 'aa'.$code.'<br>'.$this->db->last_query();
		if (empty($code) || $code == 0)
		{
			$key = $this->hash_password(microtime().$email);
			
			$this->forgotten_password_code = $key;
		
			$data = array('forgotten_password_code' => $key);
			
			$this->db->update($users_table, $data, array('email' => $email));
			//echo 'generando codigo nuevo';
			return ($this->db->affected_rows() == 1) ? true : false;
		}
		else
		{
			//echo 'ya tenia codigo';
			$this->forgotten_password_code = $code;
			return true; /// era false..
		}
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code = false)
	{
	    $users_table = $this->tables['users'];
	    $identity_column = $this->config->item('identity'); 
	    
	    if ($code === false)
	    {
	        return false;
	    }
	    
	    $query = $this->db->select('id')
                    	   ->where('forgotten_password_code', $code)
                           ->limit(1)
                    	   ->get($users_table);
        
        $result = $query->row();
        
        if ($query->num_rows() > 0)
        {
            $salt       = $this->salt();
		    $password   = $this->hash_password($salt);
		    
		    $this->new_password = $salt;
		    
            $data = array('password'                => $password,
                          'forgotten_password_code' => '0');
            
            $this->db->update($users_table, $data, array('forgotten_password_code' => $code));

            return true;
        }
        
        return false;
	}

	/**
	 * profile
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function profile($identity = false)
	{
	    $users_table     = $this->tables['users'];
	    $groups_table    = $this->tables['groups'];
	    $meta_table      = $this->tables['meta'];
	    $meta_join       = $this->config->item('join');
	    //$identity_column = $this->config->item('identity');    
	    $identity_column = 'id';    
	    
	    if ($identity === false)
	    {
	        return false;
	    }
	    
		$this->db->select($users_table.'.id, '.
						  $users_table.'.username, ' .
						  $users_table.'.password, '.
						  $users_table.'.email, '.
						  $users_table.'.activation_code, '.
						  $users_table.'.forgotten_password_code , '.
						  $users_table.'.ip_address, '.
						  $users_table.'.code, '.
						  $groups_table.'.id AS `group`');
		
		if (!empty($this->columns))
		{
		    foreach ($this->columns as $value)
    		{
    			$this->db->select($meta_table.'.'.$value);
    		}
		}
		//print("<pre>");print_r($this->db);
		$this->db->from($users_table);
		$this->db->join($meta_table, $users_table.'.id = '.$meta_table.'.'.$meta_join, 'left');
		$this->db->join($groups_table, $users_table.'.group_id = '.$groups_table.'.id', 'left');
		
		if (strlen($identity) === 40)
	    {
	        $this->db->where($users_table.'.forgotten_password_code', $identity);
	    }
	    else
	    {
	        $this->db->where($users_table.'.'.$identity_column, $identity);
	    }
	    
		$this->db->limit(1);
		$i = $this->db->get();
		//print($this->db->last_query());
		
		return ($i->num_rows > 0) ? $i->row() : false;
	}

	/**
	 * Basic functionality
	 * 
	 * Register
	 * Login
	 *
	 * @author Mathew
	 */
	
	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	//public function register($username = false, $password = false, $email = false)
	public function register($password = false, $email = false)
	{
	    $users_table        = $this->tables['users'];
	    $meta_table         = $this->tables['meta'];
	    $groups_table       = $this->tables['groups'];
	    $meta_join          = $this->config->item('join');
	    $additional_columns = $this->config->item('columns');
	    
	    //if ($username === false || $password === false || $email === false)
	    if ($password === false || $email === false)
	    {
	    	//exit("aqui");
	        return false;
	    }
	    
        // Group ID
        $group_id = $this->input->post('group_id');
        if(!isset($group_id) || $group_id=='') {
			    $query    = $this->db->select('id')->where('name', $this->config->item('default_group'))->get($groups_table);
			    $result   = $query->row();
			    $group_id = $result->id;
			  }
	    
        // IP Address
        $ip_address = $this->input->ip_address();
	    
		$password = $this->hash_password($password);
		
        // Users table.
		$data = array(
						//'username' => $username, 
					  'password' => $password, 
					  'email'    => $email,
					  //'group_id' => $group_id,
					  'group_id' => $group_id,
					  'create_user' => 0,
					  'create_time' => date(DATETIME_DB),
					  'ip_address' => $ip_address);
		  
		$this->db->insert($users_table, $data);
     log_message('debug',$this->db->last_query());   
		// Meta table.
		$id = $this->db->insert_id();
		
		$data = array($meta_join => $id);
		
		if (!empty($additional_columns))
	    {
	        foreach ($additional_columns as $input)
	        {
				if($input=='birth_date') $data[$input] =  date($this->config->item('date_db_format'), strtotime($this->input->post('birth_date')));
	            else $data[$input] = $this->input->post($input);
	        }
	        $data['allow_mail_notification'] = 1;
	        $data['allow_phone_notification'] = 1;
	        $data['reto_notifica'] = 1;
	        $data['create_user'] = 0;
	        $data['create_time'] = date(DATETIME_DB);
	        $data['create_ip'] = $this->session->userdata('ip_address');
	    }
        
		$this->db->insert($meta_table, $data);
		log_message('debug',$this->db->last_query());
		return ($this->db->affected_rows() > 0) ? $id : false;
	}
	
	/**
	 * login
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function login($identity = false, $password = false, $code = false)
	{
	    $identity_column = $this->config->item('identity');
	    $users_table     = $this->tables['users'];
	    //echo $identity."-".$password."-".$code;
	    if(isset($identity) && $identity !="") $identity_column = "email";
	    if(isset($code) && $code !="") { $identity_column = "id";  $identity = $code;}
	    //exit("AA".$identity_column);
	    if (($identity === false && $code === false) || $password === false || $this->identity_check($identity, $identity_column) == false)
	    {
	    		log_message('debug', 'Datos de acceso malos');
	        return false;
	    }
	    //exit("AA".$identity_column);

		/*
		$query = $this->db->query("SELECT users.id as id, replace(concat(meta.first_name, meta.last_name), ' ', '') as pass FROM users, meta where users.id = meta.user_id");

		foreach ($query->result_array() as $row)
		{
			$id =  $row['id'];
			$pwd =  $row['pass'];
			$password = $this->hash_password($pwd);
			
			$this->db->query("UPDATE users set password = '".$password."' WHERE id = ".$id);
		}
			*/
	    
	    $query = $this->db->select($identity_column.', password, activation_code, id,  email, group_id')
                    	   ->where($identity_column, $identity)
                    	   ->get($users_table);
	    
        //$result = $query->row();
        //log_message('debug', 'SQL1: '.$this->db->last_query());
        if ($query->num_rows() >= 1)
        {
        	foreach ($query->result() as $result) {
        		$password_saved = $result->password;
            $password = $this->hash_password_db($result->id, $password, 'id');
            //log_message('debug', 'pwd hasheado '.$password);
            //log_message('debug', 'pwd hasheado de bd '.$password_saved);
            if (!empty($result->activation_code)) { log_message('debug', 'Usuario no validado'); return false; }
            
		    		if ($password_saved === $password)
		    		{
		            //exit("DD");
		    		    $this->session->set_userdata('id',  $result->{'id'});
		    		    $this->session->set_userdata('user_id',  $result->{'id'});
		    		    $this->session->set_userdata('email',  $result->{'email'});
		    		    $this->session->set_userdata('group_id',  $result->{'group_id'});
		    		    //log_message('debug', 'Todo parece ok');
		    		    return true;
		    		} else log_message('debug', 'Password incorrecto: '.$password_saved .' - '. $password);
		    	}
        } else log_message('debug', 'Usuario no localizado ');
             //exit("EE");
       
		return false;		
	}
	
	
	
		/**
	 * login_online
	 * Funcion para crear la sesion de un usuario concreto, en caliente, sin login de por medio
	 * @return void
	 * @author Mathew
	 **/
	public function login_online($code = false)
	{
	    $identity_column = $this->config->item('identity');
	    $users_table     = $this->tables['users'];
	    //echo $identity_column."-".$password."-".$code;
	    if(isset($code) && $code !="") { $identity_column = "id";  $identity = $code;}
	    //exit("AA".$identity_column);

	    //exit("AA".$identity_column);
	    
	    $query = $this->db->select($identity_column.', password, activation_code, id, email, group_id')
                    	   ->where($identity_column, $identity)
                    	   ->limit(1)
                    	   ->get($users_table);
	    
        $result = $query->row();
        //log_message('debug', 'SQL: '.$this->db->last_query());
        //echo $this->db->last_query();
        if ($query->num_rows() == 1)
        {
            //exit("DD");
            //echo  'AAAA'.$result->{'id'};exit();
		    		    $this->session->set_userdata('id',  $result->{'id'});
		    		    $this->session->set_userdata('email',  $result->{'email'});
		    		    $this->session->set_userdata('user_id',  $result->{'id'});
		    		    $this->session->set_userdata('group_id',  $result->{'group_id'});
    		    log_message('debug', 'Todo parece ok');
    		    return true;
				}
             //exit("EE");
       
		return false;		
	}
	
	
	
	


	/**
	 * get_data
	 *
	 * @return resultset
	 **/
public function get_data($params = "" , $page = "all")
	{
			
			$table_name = 'booking';
			
			//Build contents query
	    $users_table     = $this->tables['users'];
	    $groups_table    = $this->tables['groups'];
	    $meta_table      = $this->tables['meta'];
	    $meta_join       = $this->config->item('join');
	    $identity_column = $this->config->item('identity');    
	    
    
		$this->db->select($users_table.'.id, '.
						 // $users_table.'.username, ' .
						  $users_table.'.password, '.
						  $users_table.'.email, '.
						  $users_table.'.activation_code, '.
						  $users_table.'.forgotten_password_code , '.
						  $users_table.'.ip_address, '.
						  $users_table.'.create_time, '.
						  $users_table.'.active, '.
						  $users_table.'.code, '.
						  $groups_table.'.id AS `group`, '.
						  $groups_table.'.description AS group_desc');
		
		if (!empty($this->columns))
		{
		    foreach ($this->columns as $value)
    		{
    			$this->db->select($meta_table.'.'.$value);
    		}
  			$this->db->select('zz_province.description AS provincia');
		}
		//print("<pre>");print_r($this->db);
		

		
		$this->db->from($users_table);
		$this->db->join($meta_table, $users_table.'.id = '.$meta_table.'.'.$meta_join, 'left');
		$this->db->join($groups_table, $users_table.'.group_id = '.$groups_table.'.id', 'left');
		$this->db->join('zz_province', $meta_table.'.code_province = '.'zz_province.id', 'left');
		
		if (!empty ($params['where'])) $this->db->where($params['where']);
	
		if (!empty ($params['orderby']) && !empty ($params['orderbyway'])) $this->db->order_by($params['orderby'], $params['orderbyway']);
		
		if ($page != "all") $this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
		
		//Get contents
		$query = $this->db->get();
		//echo $this->db->last_query();
		//log_message('debug',$this->db->last_query());
		
		return $query;
		

		}



public function get_data_to_export($params = "" , $page = "all")
{
		$datos = $this->get_data($params, $page)->result_array();
		$resultado = array();
		foreach($datos as $usuario) {
			array_push($resultado, array(
				'id_usuario' => $usuario['id'],
				'email' => $usuario['email'],
				'FechaCreacion' => $usuario['create_time'],
				'Activo' => $usuario['active'],
				'TipoUsuario' => $usuario['group_desc'],
				'Nombre' => $usuario['first_name'],
				'Apellidos' => $usuario['last_name'],
				'Direccion' => $usuario['address'],
				'Localidad' => $usuario['population'],
				'CP' => $usuario['cp'],
				'Genero' => $usuario['gender'],
				'NIF' => $usuario['nif'],
				'FechaNacimiento' => $usuario['birth_date'],
				'Telefono' => $usuario['phone'],
				'BonoMonedero' => number_format($usuario['prepaid_cash'], 2, ',', ''),
				'TitularCuenta' => $usuario['bank_titular'],
				'NivelJuego' => number_format($usuario['player_level'], 1, ',', ''),
				'PermiteEmail' => $usuario['allow_mail_notification'],
				'PermiteReto' => $usuario['reto_notifica'],
				'Provincia' => $usuario['provincia'],
				'NumeroSocio' => $usuario['numero_socio']
				
			
			));
		}
		//print_r($resultado);exit();

		return $resultado;
}
	
	
	
	/**
	 * login
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function get_global_list($filters="", $orderby="", $orderbyway="", $limit="", $flexigrid=NULL) 
	{
		$this->CI =& get_instance();
	    $users_table     = $this->tables['users'];
	    $groups_table    = $this->tables['groups'];
	    $meta_table      = $this->tables['meta'];
	    $meta_join       = $this->config->item('join');
	    $identity_column = $this->config->item('identity');    
	    
    
		$this->db->select($users_table.'.id, '.
						  $users_table.'.username, ' .
						  $users_table.'.password, '.
						  $users_table.'.email, '.
						  $users_table.'.activation_code, '.
						  $users_table.'.forgotten_password_code , '.
						  $users_table.'.ip_address, '.
						  $users_table.'.create_time, '.
						  $users_table.'.active, '.
						  $users_table.'.code, '.
						  $meta_table.'.allow_mail_notification, '.
						  $meta_table.'.first_name, '.
						  $meta_table.'.last_name, '.
						  $meta_table.'.player_level, '.
						  $groups_table.'.id AS `group`, '.
						  $groups_table.'.description AS group_desc');
		
		if (!empty($this->columns))
		{
		    foreach ($this->columns as $value)
    		{
    			$this->db->select($meta_table.'.'.$value);
    		}
		}
		//print("<pre>");print_r($this->db);
		
		// OLD CODE, añadido nuevo parámetro $this->CI->flexigrid->build_query();
		if($flexigrid) $this->CI->flexigrid->build_query();
		
		$this->db->from($users_table);
		$this->db->join($meta_table, $users_table.'.id = '.$meta_table.'.'.$meta_join, 'left');
		$this->db->join($groups_table, $users_table.'.group_id = '.$groups_table.'.id', 'left');
		

		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
	
		if (isset($orderby) && trim($orderby)!="" && isset($orderbyway) && trim($orderbyway)!="") $this->db->order_by($orderby, $orderbyway);
		
		if (isset($limit) && trim($limit)!="") $this->db->limit($limit);
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo "A<br>A<br>A<br>A<br>A<br>A".$this->db->last_query()."CCCCCCCCCCC";
		//log_message('debug', 'SQL: '.$this->db->last_query());
		//Build count query
		
		
		# Para devolver el numero de registros
		$this->db->select('count(users.id) as record_count')->from($users_table);
		$this->db->join($meta_table, $users_table.'.id = '.$meta_table.'.'.$meta_join, 'left');
		$this->db->join($groups_table, $users_table.'.group_id = '.$groups_table.'.id', 'left');
		if (isset($filters) && trim($filters)!="") $this->db->where($filters);
		if (isset($orderby) && trim($orderby)!="" && isset($orderbyway) && trim($orderbyway)!="") $this->db->order_by($orderby, $orderbyway);
		if (isset($limit) && trim($limit)!="") $this->db->limit($limit);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		
		//Return all
		return $return;	
	
	}	
	
	
	
	
	
	/**
	 * getActiveUsersArray
	 *
	 * @return array
	 **/
	public function getActiveUsersArray($filtros=NULL, $extra = NULL, $info_extra = NULL) 
	{
		//echo "AA";
		$filtro=array('users.active' => 1 );
        $filtros_extra = $this->config->item('users_search_extra_filters');

		if(isset($filtros) && trim($filtros)!="") $filtro="users.active = 1 AND (".implode(" like '%".$filtros."%' OR ", $filtros_extra). " like '%".$filtros."%')"; //meta.first_name like '%".$filtros."%' OR meta.last_name like '%".$filtros."%' OR meta.nif like '%".$filtros."%' OR meta.phone like '%".$filtros."%' OR meta.numero_socio like '%".$filtros."%' OR users.code like '%".$filtros."%' OR users.id like '%".$filtros."%')";
		else $filtro="users.active = 1";
		if(isset($extra) && trim($extra)!="") $filtro .= " AND ".$extra;
		$campos = 'meta.first_name, meta.last_name';
		//echo '---'.$filtro;
		/*
		if(isset($info_extra)) {
			if(is_array($info_extra) && count($info_extra)>0) { foreach($info_extra as $campo=> $alias) { $campos .=', '.$campo.' as '.$alias; }}
			else if(!is_array($info_extra)) $campos.=', '.$info_extra;
		}
		*/
		$resultado= $this -> get_global_list($filtro, $campos, 'asc');

		//print("<pre>");

		$usuarios=array('' => 'Selecciona opcion');
		foreach ($resultado['records']->result() as $row)
		{
			//print_r($row);
			$nombre=$row->first_name;
			if(trim($row->last_name)!="") $nombre.=' '.$row->last_name;
			//if(trim($row->numero_socio)!="" && trim($row->numero_socio)!="0") $nombre.=' ('.$row->numero_socio.')';
			if(isset($info_extra)) {
				if(is_array($info_extra) && count($info_extra)>0) { foreach($info_extra as $campo=>$alias) { if($row->$alias!='') $nombre.=' ('.$row->$alias.')'; }}
				else if(!is_array($info_extra)) $campos.=', '.$info_extra;
			}
			$usuarios[$row->id]=$nombre;
		}
		//Return all
		return $usuarios;	
		
		}	
	


	
	
	/**
	 * getPrepaidCash
	 *
	 * @return number
	 **/
	public function getPrepaidCash($user) 
	{
		//echo "AA";
		$sql_select = "select prepaid_cash from meta where user_id = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			return $row->prepaid_cash;

		}	else return NULL;	
	}	
	

	/**
	 * getPrepaidCash
	 *
	 * @return number
	 **/
	public function getLastPrepaidCargeDate($user) 
	{
		//echo "AA";
		$sql_select = "select max(date(datetime)) as fecha from payments where id_type='3' and status = 9 and id_user = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			return $row->fecha;

		}	else return NULL;	
	}	
	


	
	
	/**
	 * getUserMail
	 *
	 * @return number
	 **/
	public function getUserMail($user) 
	{
		//echo "AA";
		$sql_select = "select email from users where id = ?";
		$query = $this->db->query($sql_select, array($user));
		//log_message('debug', 'SQL: '.$this->db->last_query());
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			return $row->email;

		}	else return NULL;	
	}	
	


	
	
	/**
	 * getUserBank
	 *
	 * @return number
	 **/
	public function getUserBank($user, $separator = '') 
	{
		//echo "AA";
		$sql_select = "select bank, bank_office, bank_dc, bank_account, bank_titular from meta where user_id = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			$banco = NULL;
			if($row->bank!='' && $row->bank_office!='' && $row->bank_dc!='' && $row->bank_account!='') {
				if($row->bank_titular!='') $titular = $row->bank_titular;
				else $titular = $this->getUserDesc($user);
				$banco = array($row->bank.$separator.$row->bank_office.$separator.$row->bank_dc.$separator.$row->bank_account, $titular);
			}
			return $banco;

		}	else return NULL;	
	}	
	

	
	
	/**
	 * getUserBank
	 *
	 * @return number
	 **/
	public function getUserBankIBAN($user, $separator = '') 
	{
		//echo "AA";
		$sql_select = "select bank_bic, bank_iban, bank_titular from meta where user_id = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			$banco = NULL;
			if($row->bank_iban!='') {
				if($row->bank_titular!='') $titular = $row->bank_titular;
				else $titular = $this->getUserDesc($user);
				$banco = array($row->bank_iban, $titular, $row->bank_bic);
			}
			return $banco;

		}	else return NULL;	
	}	
	
	
	
	/**
	 * getUserGroup
	 *
	 * @return number
	 **/
	public function getUserGroup($user) 
	{
		//echo "AA";
		$sql_select = "select group_id from users where id = ?";
		$query = $this->db->query($sql_select, array($user));
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			return $row->group_id;

		}	else return NULL;	
	}	
	

	
	
	/**
	 * getUserDesc
	 *
	 * @return number
	 **/
	public function getUserDesc($user) 
	{
		//echo "AA";
		$sql_select = "select first_name, last_name from meta where user_id = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			$nombre = '';
			if($row->first_name!="") $nombre = $row->first_name;
			if($row->last_name!="") {
				if($nombre!="") $nombre.= ' ';
				$nombre .= $row->last_name;
			}
			return $nombre;

		}	else return NULL;	
	}	
	


	public function getUserPhone($user) 
	{
		//echo "AA";
		$sql_select = "select phone from meta where user_id = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			$nombre = '';
			if($row->phone!="") $nombre = $row->phone;

			return $nombre;

		}	else return NULL;	
	}	
	



		
	
	/**
	 * getLastPrepaidMovement
	 *
	 * @return number
	 **/
	public function getLastPrepaidMovement($user) 
	{
		//echo "AA";
		$sql_select = "select max(id) as id from users_prepaid_movements where id_user = ?";
		$query = $this->db->query($sql_select, array($user));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			$ultimo = '';
			if($row->id!="") $ultimo = $row->id;

			return $ultimo;

		}	else return '0';	
	}	
	



	
	
	/**
	 * getPrepaidCash
	 *
	 * @return number
	 **/
	public function addPrepaidMovement($user, $amount, $payment_type, $paymentway, $id_transaction) 
	{
		
		$saldo = 0;
		$saldo = $this->getPrepaidCash($user);
		$saldo = $saldo + $amount;
		$data = array(
             'prepaid_cash' => $saldo,
						  'modify_user' => $this->session->userdata('user_id'),
						  'modify_time' => date(DATETIME_DB),
            );
		
		$this->db->where('user_id', $user);
		$this->db->update('meta', $data); 
		log_message('debug', 'SQL: '.$this->db->last_query());
		
		$data = array(
              'id_user' => $user,
              'payment_type' => $payment_type,
              'id_paymentway' => $paymentway,
              'id_transaction' => $id_transaction,
              'amount' => $amount,
						  'create_user' => $this->session->userdata('user_id'),
						  'create_time' => date(DATETIME_DB),
            );
		$this->db->insert('users_prepaid_movements', $data); 
		log_message('debug', 'SQL: '.$this->db->last_query());

		return TRUE;	
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
		$sql_select = "SELECT u.id, u.password, u.email, u.active, u.code, u.activation_code, u.forgotten_password_code, u.group_id, g.description as description_group,".
					" m.first_name, m.last_name, m.phone, m.address,m.population,m.province,m.gender,m.code_population,".
					"m.code_province,m.code_country,m.cp,m.nif,m.birth_date,m.mobile_phone,  ".
					"m.bank,m.bank_office,m.bank_dc,m.bank_account,m.bank_titular,m.bank_charge,m.bank_iban, m.bank_bic, ".
					"m.player_level, m.allow_phone_notification, m.allow_mail_notification, m.alt_code, m.code_price, m.last_payd_date, ".
					"m.reto_lunes, m.reto_martes, m.reto_miercoles, m.reto_jueves, m.reto_viernes, m.reto_sabado, m.reto_domingo, m.reto_manana, m.reto_tarde, m.reto_finde, m.reto_notifica, ".
					"p.description as price_desc, p.duration as price_duration, p.id_frequency as frequency, m.numero_socio, m.avatar, m.prepaid_cash, m.notas ".
					"FROM users as u ".
					"inner join meta as m on (u.id = m.user_id) ".
					"left outer join prices as p on (m.code_price = p.id) ".
					"inner join groups as g on (u.group_id = g.id) ".
					"and u.id = ?";
		$query = $this->db->query($sql_select, array($id_user));
		//echo "A<br>A<br>A<br>A<br>A<br>A".$this->db->last_query()."CCCCCCCCCCC";
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			/* RELLENO EL ARRAY CON LOS DATOS DEL USUARIO PARA DEVOLVER AL CONTROLLER */
			$newdata = array(
                   'user_id'  => $row->id,
                   'user_name'  => $row->first_name,
                   'user_lastname'  => $row->last_name,
                   'group_id'  => $row->group_id,
                   'activation_code'  => $row->activation_code,
                   'forgotten_password_code'  => $row->forgotten_password_code,
                   'group_description'  => $row->description_group,
                   'code'  => $row->code,
                   'user_email'  => $row->email,
                   'user_active'  => $row->active,
                   'user_phone'  => $row->phone,
                   'password'  => $row->password,
                   'address'  => $row->address,
                   'population'  => $row->population,
                   'province'  => $row->province,
                   'gender'  => $row->gender,
                   'code_population'  => $row->code_population,
                   'code_province'  => $row->code_province,
                   'code_country'  => $row->code_country,
                   'cp'  => $row->cp,
                   'nif'  => $row->nif,
                   'birth_date'  => $row->birth_date,
                   'mobile_phone'  => $row->mobile_phone,
                   'bank'  => $row->bank,
                   'bank_office'  => $row->bank_office,
                   'bank_dc'  => $row->bank_dc,
                   'bank_account'  => $row->bank_account,
                   'bank_titular'  => $row->bank_titular,
                   'bank_charge'  => $row->bank_charge,
                   'bank_iban'  => $row->bank_iban,
                   'bank_bic'  => $row->bank_bic,
                   'allow_mail_notification'  => $row->allow_mail_notification,
                   'allow_phone_notification'  => $row->allow_phone_notification,
                   'reto_lunes'  => $row->reto_lunes,
                   'reto_martes'  => $row->reto_martes,
                   'reto_miercoles'  => $row->reto_miercoles,
                   'reto_jueves'  => $row->reto_jueves,
                   'reto_viernes'  => $row->reto_viernes,
                   'reto_sabado'  => $row->reto_sabado,
                   'reto_domingo'  => $row->reto_domingo,
                   'reto_manana'  => $row->reto_manana,
                   'reto_tarde'  => $row->reto_tarde,
                   'reto_finde'  => $row->reto_finde,
                   'alt_code'  => $row->alt_code,
                   'code_price'  => $row->code_price,
                   'price_desc'  => $row->price_desc,
                   'price_duration'  => $row->price_duration,
                   'frequency'  => $row->frequency,
                   'last_payd_date'  => $row->last_payd_date,
                   'reto_notifica'  => $row->reto_notifica,
                   'player_level'  => $row->player_level,
                   'numero_socio'  => $row->numero_socio,
                   'avatar'  => $row->avatar,
                   'notas'  => $row->notas,
                   'prepaid_cash'  => $row->prepaid_cash);
			/* DEVUELVO EL ARRAY */
			if(date('Y', strtotime($newdata['last_payd_date'])) < 1990) $newdata['last_payd_date'] = '';
			//debug.log_message('debug','lalalalalalala:    '.$row->id_level_play);
			return $newdata;
		}
		else
		{
			return null;
			/* NO ENCUENTRA EL USUARIO, VOLVER A PÁGINA ANTERIOR MOSTRANDO UN MENSAJE*/	
		}
    }




    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_user_by_email($email)
    {
    	$user_activated = '0';
    	/*CAPTURO LOS DATOS DEL USUARIO*/
		$sql_select = "SELECT u.id, u.password, u.email, u.active, u.code, u.activation_code, u.forgotten_password_code, u.group_id, g.description as description_group,".
					" m.first_name, m.last_name, m.phone, m.address,m.population,m.province,m.gender,m.code_population,".
					"m.code_province,m.code_country,m.cp,m.nif,m.birth_date,m.mobile_phone,  ".
					"m.bank,m.bank_office,m.bank_dc,m.bank_account,m.bank_titular,m.bank_charge,m.bank_iban, m.bank_bic, ".
					"m.player_level, m.allow_phone_notification, m.allow_mail_notification, m.alt_code, m.code_price, m.last_payd_date, ".
					"m.reto_lunes, m.reto_martes, m.reto_miercoles, m.reto_jueves, m.reto_viernes, m.reto_sabado, m.reto_domingo, m.reto_manana, m.reto_tarde, m.reto_finde, m.reto_notifica, ".
					"p.description as price_desc, p.duration as price_duration, p.id_frequency as frequency ".
					"FROM users as u ".
					"inner join meta as m on (u.id = m.user_id) ".
					"left outer join prices as p on (m.code_price = p.id) ".
					"inner join groups as g on (u.group_id = g.id) ".
					"and u.email = ?";
		$query = $this->db->query($sql_select, array($email));
		if ($query->num_rows() > 0) 
		{
			$row = $query->row();
			/* RELLENO EL ARRAY CON LOS DATOS DEL USUARIO PARA DEVOLVER AL CONTROLLER */
			$newdata = array(
                   'user_id'  => $row->id,
                   'user_name'  => $row->first_name,
                   'user_lastname'  => $row->last_name,
                   'group_id'  => $row->group_id,
                   'activation_code'  => $row->activation_code,
                   'forgotten_password_code'  => $row->forgotten_password_code,
                   'group_description'  => $row->description_group,
                   'code'  => $row->code,
                   'user_email'  => $row->email,
                   'user_active'  => $row->active,
                   'user_phone'  => $row->phone,
                   'password'  => $row->password,
                   'address'  => $row->address,
                   'population'  => $row->population,
                   'province'  => $row->province,
                   'gender'  => $row->gender,
                   'code_population'  => $row->code_population,
                   'code_province'  => $row->code_province,
                   'code_country'  => $row->code_country,
                   'cp'  => $row->cp,
                   'nif'  => $row->nif,
                   'birth_date'  => $row->birth_date,
                   'mobile_phone'  => $row->mobile_phone,
                   'bank'  => $row->bank,
                   'bank_office'  => $row->bank_office,
                   'bank_dc'  => $row->bank_dc,
                   'bank_account'  => $row->bank_account,
                   'bank_titular'  => $row->bank_titular,
                   'bank_charge'  => $row->bank_charge,
                   'bank_iban'  => $row->bank_iban,
                   'bank_bic'  => $row->bank_bic,
                  'allow_mail_notification'  => $row->allow_mail_notification,
                   'allow_phone_notification'  => $row->allow_phone_notification,
                   'reto_lunes'  => $row->reto_lunes,
                   'reto_martes'  => $row->reto_martes,
                   'reto_miercoles'  => $row->reto_miercoles,
                   'reto_jueves'  => $row->reto_jueves,
                   'reto_viernes'  => $row->reto_viernes,
                   'reto_sabado'  => $row->reto_sabado,
                   'reto_domingo'  => $row->reto_domingo,
                   'reto_manana'  => $row->reto_manana,
                   'reto_tarde'  => $row->reto_tarde,
                   'reto_finde'  => $row->reto_finde,
                   'alt_code'  => $row->alt_code,
                   'code_price'  => $row->code_price,
                   'price_desc'  => $row->price_desc,
                   'price_duration'  => $row->price_duration,
                   'frequency'  => $row->frequency,
                   'last_payd_date'  => $row->last_payd_date,
                   'reto_notifica'  => $row->reto_notifica,
                  'player_level'  => $row->player_level);
			/* DEVUELVO EL ARRAY */
			
			//debug.log_message('debug','lalalalalalala:    '.$row->id_level_play);
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
    	//print("<pre>");print_r($array_user);exit();
		$data_users = array('group_id' => $array_user['group_id'],
							'email' => $array_user['email'],
							'code' => $array_user['code'],
							'active' => $array_user['active'], 'modify_time' => date(date(DATETIME_DB)), 'modify_user' => $this->session->userdata('user_id'));
							
		$data_meta = array(
							'first_name' => $array_user['first_name'],
							'last_name' => $array_user['last_name'],
							'phone' => $array_user['phone'],
							'mobile_phone' => $array_user['mobile_phone'],
							'address' => $array_user['address'],
							'cp' => $array_user['cp'],
							'population' => $array_user['population'],
							'code_province' => $array_user['code_province'],
							'code_country' => $array_user['code_country'],
							'gender' => $array_user['gender'],
							'nif' => $array_user['nif'],
							'birth_date' => $array_user['birth_date'],
							'player_level' => $array_user['player_level'], 
							'allow_phone_notification' => $array_user['allow_phone_notification'], 
							'allow_mail_notification' => $array_user['allow_mail_notification'], 
							'reto_lunes' => $array_user['reto_lunes'], 
							'reto_martes' => $array_user['reto_martes'], 
							'reto_miercoles' => $array_user['reto_miercoles'], 
							'reto_jueves' => $array_user['reto_jueves'], 
							'reto_viernes' => $array_user['reto_viernes'], 
							'reto_sabado' => $array_user['reto_sabado'], 
							'reto_domingo' => $array_user['reto_domingo'], 
							'reto_manana' => $array_user['reto_manana'], 
							'reto_tarde' => $array_user['reto_tarde'], 
							'reto_finde' => $array_user['reto_finde'], 
							'reto_notifica' => $array_user['reto_notifica'], 
							'modify_time' => date(DATETIME_DB), 
							'modify_user' => $this->session->userdata('user_id'),
							'modify_ip' => $this->session->userdata('ip_address'), 
							);

							
		if($data_meta['code_province']=='') $data_meta['code_province'] = 0;
		if(isset($array_user['numero_socio']) && $array_user['numero_socio']!='') $data_meta['numero_socio'] = $array_user['numero_socio'];
		if(isset($array_user['bank'])) $data_meta['bank'] = $array_user['bank'];
		if(isset($array_user['bank_office'])) $data_meta['bank_office'] = $array_user['bank_office'];
		if(isset($array_user['bank_dc'])) $data_meta['bank_dc'] = $array_user['bank_dc'];
		if(isset($array_user['bank_account'])) $data_meta['bank_account'] = $array_user['bank_account'];
		if(isset($array_user['bank_titular'])) $data_meta['bank_titular'] = $array_user['bank_titular'];
		if(isset($array_user['bank_iban'])) $data_meta['bank_iban'] = $array_user['bank_iban'];
		if(isset($array_user['bank_bic'])) $data_meta['bank_bic'] = $array_user['bank_bic'];
		if(isset($array_user['code_price'])) $data_meta['code_price'] = $array_user['code_price'];
		if(isset($array_user['notas'])) $data_meta['notas'] = $array_user['notas'];
		//echo 'aa<pre>';print_r($array_user);print_r($data_meta);exit();
		$this->db->update('users',$data_users, array('id' => $array_user['id']));
		log_message('debug', 'Actualizo usuario: '.$this->db->last_query());
		if ($this->db->affected_rows() == 1) $result_update = true;
		$this->db->update('meta',$data_meta, array('user_id' => $array_user['id']));
		log_message('debug', 'Actualizo usuario: '.$this->db->last_query());
		if ($this->db->affected_rows() == 1) $result_update = true;
		
		return $result_update;
		
    }



    /* FUNCTION PARA INSERTAR UN USUARIO*/
    function new_user($array_user)
    {
    	/*NECESITO INSERTAR EN DOS TABLAS*/
    	/* PRIMERO TABLA USERS */
    	$new_password = $this->hash_password($array_user['password']);
    	$data = array(
			  'password' => $new_password, 
			  'email'    => $array_user['email'],
			  'active'    => $array_user['active'],
			  'group_id' => $array_user['group_id'],
			  'create_user' => $this->session->userdata('user_id'),
			  'create_time' => date(DATETIME_DB),
			  'ip_address' => $this->input->ip_address()
			  );  
		$this->db->insert('users', $data);
		$id = $this->db->insert_id();
		$data_meta = array('first_name' => $array_user['first_name'],
							'user_id' => $id,
							'last_name' => $array_user['last_name'],
							'phone' => $array_user['phone'],
							'create_user' => $this->session->userdata('user_id'),
							'create_time' => date(DATETIME_DB));
		if(isset($array_user['player_level'])) $data_meta['player_level']=$array_user['player_level'];
		if(isset($array_user['address'])) $data_meta['address']=$array_user['address'];
		if(isset($array_user['population'])) $data_meta['population']=$array_user['population'];
		if(isset($array_user['gender'])) $data_meta['gender']=$array_user['gender'];
		if(isset($array_user['cp'])) $data_meta['cp']=$array_user['cp'];
		if(isset($array_user['nif'])) $data_meta['nif']=$array_user['nif'];
		if(isset($array_user['birth_date'])) $data_meta['birth_date']=$array_user['birth_date'];
		$this->db->insert('meta', $data_meta);
		
		
		return $id;
		
    }

    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_groups($extra = NULL)
    {
    	$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			
			$sql_select = "SELECT id, name, description FROM groups WHERE id >= ".$user_group;
			if(isset($extra['exclude_anonymous'])) $sql_select .=" AND id < 9 ";
			$sql_select .=" order by description";
			$query = $this->db->query($sql_select);
			$array_all = array();
			foreach ($query->result() as $row)
			{
				/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'name'  => $row->name,
	                   'description'  => $row->description);
				
			}
			/* DEVUELVO EL ARRAY */
			//print("<pre>");print_r($array_all);
			return $array_all;
    }

    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_provinces()
    {
    	$user_activated = '0';
    	/*CAPTURO LOS DATOS DEL USUARIO*/
			$sql_select = "SELECT id, id_country, abreviatura, description FROM zz_province where id_country = 196";
			$query = $this->db->query($sql_select);
			$array_all = array();
			$array_all = array('' => '');
			foreach ($query->result() as $row)
			{
				/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'id_country'  => $row->id_country,
	                   'abreviatura'  => $row->abreviatura,
	                   'description'  => $row->description);
				
			}
			/* DEVUELVO EL ARRAY */
			return $array_all;
    }

    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_countries()
    {
			$sql_select = "SELECT id, description FROM zz_country ";
			$query = $this->db->query($sql_select);
			$array_all = array('0' => '');
			foreach ($query->result() as $row)
			{
				/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'description'  => $row->description);
				
			}
			/* DEVUELVO EL ARRAY */
			return $array_all;
    }

    /* FUNCTION PARA CAPTURAR LOS NIVELES*/
    function get_levels()
    {
			$sql_select = "SELECT id, name, description FROM zz_level_play where active = '1'";
			$query = $this->db->query($sql_select);
			//$array_all = array('0' => '');
			foreach ($query->result() as $row)
			{
				/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'name'  => $row->name,
	                   'description'  => $row->description);
				
			}
			/* DEVUELVO EL ARRAY */
			return $array_all;
    }
    
    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_users_list()
    {
    	/*CAPTURO LOS USUARIOS*/
    	 //$this->db->select('user_id, firs_name, last_name, phone', FALSE)->from('meta');
		$this->db->select('user_id,first_name', FALSE)->from('meta'); 
    	$this->db->order_by('first_name', 'asc');
		$return = $this->db->get()->result_array();
		//log_message('debug', 'SQL: '.$this->db->last_query());
		 /*$sql_select = "SELECT id,first_name, phone from meta";
			$query = $this->db->query($sql_select);
			//$array_all = array('0' => '');
			$array_all = array();
			foreach ($query->result() as $row)
			{
				/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
			/*	$array_all[$row->id] = array('name' => $row->first_name,
											'phone' => $row->phone);
				
			}
			/* DEVUELVO EL ARRAY */
			//return $array_all;
			return $return;
		 
	}
    



##############################################################################
## Funcion que calcula la fecha del ultimo pago del usuario (si está en blanco, en funcion de los periodos de la tarifa)

    function getLastPayedDate($usuario)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
				$last_payd_date = '';
				$resultado = $this->get_user($usuario);
				//print_r($resultado);
				if(!isset($resultado['last_payd_date']) || $resultado['last_payd_date'] == '' || date('Y', strtotime($resultado['last_payd_date'])) < 1990) {
					switch($resultado['frequency']) {
						case 5:
						# Mensual
							//$last_payd_date = date($this->config->item('log_date_format'), strtotime($this->config->item('users_monthly_quota_next_date').' +'.$resultado['price_duration'].' month'));
							$last_payd_date = date($this->CI->config->item('date_db_format'), strtotime($this->CI->config->item('users_monthly_quota_next_date')));
						break;
						case 9:
						# Anual
							//$last_payd_date = date($this->config->item('log_date_format'), strtotime($this->config->item('users_yearly_quota_next_date').' +'.$resultado['price_duration'].' month'));
							$last_payd_date = date($this->CI->config->item('date_db_format'), strtotime($this->CI->config->item('users_yearly_quota_next_date')));
						break;
						
					}
					/*
					if($resultado['price_duration']=="1") $last_payd_date = date($this->config->item('log_date_format'), strtotime($this->config->item('users_monthly_quota_next_date').' +'.$resultado['price_duration'].' month'));
					else $last_payd_date = date($this->config->item('log_date_format'), strtotime($this->config->item('users_yearly_quota_next_date').' +'.$resultado['price_duration'].' month'));
					*/
					//echo "1<br>";
				} else {
					$last_payd_date = $resultado['last_payd_date'];
					//echo "2<br>";
				}
				
				
					

			}catch(Exception $e){
				return FALSE;
		  }        
				return $last_payd_date;
    }


##############################################################################
## Función que calcula la fecha hasta la que pagaremos si pagamos la cuota del usuario

    function getNextPaymentDate($usuario)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
				$next_payment_date = '';
				$resultado = $this->get_user($usuario);
				//print_r($resultado);
				//echo $resultado['last_payd_date'];
				//echo '---'.date('Y', strtotime($resultado['last_payd_date']));
				if(!isset($resultado['last_payd_date']) || $resultado['last_payd_date'] == '' || date('Y', strtotime($resultado['last_payd_date'])) < 1990) $last_payd_date = $this->getLastPayedDate($usuario); 
				else $last_payd_date = $resultado['last_payd_date'];
				if(!isset($last_payd_date) || $last_payd_date=='') $last_payd_date = $this->CI->config->item('users_monthly_quota_next_date');
				//echo $last_payd_date;
				
				switch($resultado['frequency']) {
					case 5:
					# Mensual
					//echo 'C';
						$next_payment_date = date($this->CI->config->item('date_db_format'), strtotime($last_payd_date.' +'.$resultado['price_duration'].' month'));
					break;
					case 9:
					# Anual
					//echo 'D';
						$next_payment_date = date($this->CI->config->item('date_db_format'), strtotime($last_payd_date.' +'.$resultado['price_duration'].' month'));
					break;
				}
				/*
				if($resultado['price_duration']=="1") $last_payd_date = date($this->config->item('log_date_format'), strtotime($this->config->item('users_monthly_quota_next_date').' +'.$resultado['price_duration'].' month'));
				else $last_payd_date = date($this->config->item('log_date_format'), strtotime($this->config->item('users_yearly_quota_next_date').' +'.$resultado['price_duration'].' month'));
				*/
				//echo "1<br>";

				
				
					

			}catch(Exception $e){
				return FALSE;
		  }        
				return $next_payment_date;
    }




##############################################################################


    function setMonthlyPayment($usuario, $last_payd_date)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'last_payd_date' => $last_payd_date,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      
	      $this->db->where('user_id', $usuario);
				$this->db->update('meta', $data);
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }




##############################################################################


    function setAvatar($usuario, $extension)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'avatar' => $usuario.'.'.$extension,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      
	      $this->db->where('user_id', $usuario);
				$this->db->update('meta', $data);
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }



##############################################################################


    
    function getGendersArray()
    {
        //$query = $this->db->get('entries', 10);
        //return $query->result();
        $result=array("0"=>"Indiferente");
        $sql = "SELECT id, description FROM zz_gender ORDER BY Description"; 
				$query = $this->db->query($sql);
				foreach ($query->result() as $row)
				{
					$result[$row->id]=$row->description;
				}	
				
				return $result;				
    }






/*
|--------------------------------------------------------------------------
| getUserQuota
|--------------------------------------------------------------------------
|
| Parametrización 
|
|
*/
    function get_userQuota($usuario) {
    	# Devuelve cuota a pagar por el usuario según la tarifa elegida


//echo "C";    	
			
				if($usuario['code_price']!="") {
//echo "D";    	
					# Debo comprobar si el día está definido como festivo en algún calendario para saber qué consulta hacer
					
					# Si no es festivo
			    $sql = "SELECT prices.quantity, prices.by_group FROM prices WHERE prices.id = ? and prices.active = '1'  LIMIT 1"; 
					$query = $this->db->query($sql, array($usuario['code_price']));
					//log_message('debug', 'SQL: '.$this->db->last_query());
					//echo $this->db->last_query();
					if ($query->num_rows() > 0) {	
//echo "E";    	
						$row = $query->row();
						$quantity = $row->quantity;
						$by_group = $row->by_group;
						
						if($by_group == '1') {
//echo "F";    	
							
							# Si la tarifa es de grupos, busco los registros en la tabla adecuada
					    $sql2 = "SELECT quantity FROM prices_by_group WHERE id_price = ? and id_group = ? and start_date <= '".date($this->config->item('date_db_format'))."' and end_date >= '".date($this->config->item('date_db_format'))."'"; 
							$query2 = $this->db->query($sql2, array($usuario['code_price'], $usuario['group_id']));
							if ($query2->num_rows() > 0) {
								$row2 = $query2->row();
								$quantity = $row2->quantity;
							} 
							
						}
						
						
//echo "<br>--".$quantity;    	
						return $quantity;
					} else return NULL;
					//date('H:i', strtotime($row->interval))
					
					
					# Si es festivo
					# ...
					
				} else return NULL;
    }



    /* FUNCTION PARA CAPTURAR LOS DATOS DE UN USUARIO*/
    function get_quotas($extra = NULL)
    {
    	$profile=$this->redux_auth->profile();
			$user_group=$profile->group;
			
			$sql_select = "SELECT id, description FROM prices WHERE type = '1'";
			$sql_select .=" order by description";
			$query = $this->db->query($sql_select);
			$array_all = array();
			foreach ($query->result() as $row)
			{
				/* RELLENO EL ARRAY CON LOS DATOS DEL GRUPO */
				$array_all[$row->id] = array(
	                   'id'  => $row->id,
	                   'description'  => $row->description);
				
			}
			/* DEVUELVO EL ARRAY */
			//print("<pre>");print_r($array_all);
			return $array_all;
    }




    /* FUNCTION PARA GENERAR EL PROXIMO NUMERO DE SOCIO/ABONADO VALIDO*/
    function getNextMemberNumber()
    {
			$formato = $this->CI->config->item('users_member_number_auto_format');
			
			$sql_select = "SELECT max(numero_socio) as numero_socio FROM meta";
			$query = $this->db->query($sql_select);
			$array_all = array();
			if ($query->num_rows() > 0)
			{
				if($row = $query->row()) {
					$numero_socio = intval($row->numero_socio);
				}
			}
			//echo '--'.$row->numero_socio.'--'.$numero_socio;
			if(!isset($numero_socio) || intval($numero_socio) == 0) $numero_socio = sprintf($formato, 1);
			else $numero_socio = sprintf($formato, intval($numero_socio)+1);
			
			return $numero_socio;


    }



##############################################################################


    function setMemberNumber($usuario, $member_number)
    {
    	try {
        //$query = $this->db->get('entries', 10);
        //return $query->result();

				# ...
	    	$data = array(
           'numero_socio' => $member_number,
           'modify_user' => $this->session->userdata('user_id'),
           'modify_time' => date($this->config->item('log_date_format')),
           'modify_ip' => $this->session->userdata('ip_address')
	      );
	      
	      $this->db->where('user_id', $usuario);
				$this->db->update('meta', $data);
				log_message('debug',$this->db->last_query());
			}catch(Exception $e){
				return FALSE;
		  }        
				return TRUE;				
    }
    


    
        

}
