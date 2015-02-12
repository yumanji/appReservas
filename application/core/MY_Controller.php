<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function _output($content)
    {
        // Load the base template with output content available as $content
    	if($this->redux_auth->logged_in()) {
    		$data = array();
        	$data['content'] = &$content;
        	echo($this->load->view('responsive', $data, true));
    	} else {
    		$data = array();
        	$data['content'] = &$content;
        	echo($this->load->view('not_logged', $data, true));
       	}
    }

}