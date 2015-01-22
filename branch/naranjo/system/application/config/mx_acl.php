<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MX_Acl configuration
 *
 * Save this file as application/config/mx_acl.php
 */
$config = array(
    'check_uri'    => TRUE,
    'error_var'    => 'error_message',
    'error_msg'    => 'You don\'t have sufficient access rights to view this pageee!',
    'session_var'  => 'group_id',
); 