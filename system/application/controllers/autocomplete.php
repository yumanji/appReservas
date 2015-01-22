<?php
class Autocomplete extends Controller {
 
    function Autocomplete()
    {
        parent::Controller();    
 
        // load models
        $this->load->model('redux_auth_model', 'users', TRUE);
    }
 
    function index()
    {
        $this->load->view('autocomplete', array());
    }
 
    function get_Names()
    {
    	//$q = $this->input->post('q',TRUE);
        //if (!$q) return;
        // form dropdown and myql get countries
        $this->load->model('redux_auth_model', 'users', TRUE);
        $array_users = $this->users->getActiveUsersArray();
 
        // go foreach
       /* foreach($users->result() as $user)
        {
            $items[$user->user_id] = $user->first_name;
        }*/
        $usuarios=array();
        foreach($array_users as $code => $value) if($code!="") array_push($usuarios, array('id' => $code, 'label' => $value, 'value' => $value));
 				//print("<pre>");print_r($array_users);print_r($usuarios);print("</pre>");
        //echo '{"tags":'. json_encode($array_users) .'}'; 
        echo json_encode($usuarios);
    }
}  
