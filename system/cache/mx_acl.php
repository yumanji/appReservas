<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is the cached access control list 
 * 
 * save this file as {cache_path}/mx_acl.php
 */
return array(
    'control/index' => array(                       // the "module/controller/method" to protect
        'allowed'    => array('*'),                    // the allowed user role_id array
        'ipl'        => array('*'),          // the allowed IP range array
        'error_uri'  => site_url('secure/setting'),  // the url to redirect to on failure
        'error_msg'  => 'You do not have permission to update this pageee!',
    )
);