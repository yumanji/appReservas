<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email']['protocol']  = 'smtp';
//$config['email']['smtp_host'] = 'smtp.rdeportiva.com';
$config['email']['smtp_host'] = '176.28.102.241';
$config['email']['smtp_user'] = 'noreply@rdeportiva.com';
$config['email']['smtp_pass'] = 'sinrespuesta';
$config['email']['smtp_port'] = '587';
$config['email']['mailtype']  = 'html';
$config['email']['charset']   = 'utf-8';
$config['email']['starttls'] = TRUE;
$config['email']['smtp_timeout'] = 5;
$config['email']['newline'] = "\r\n";

$config['email_automated_send_quantity']   = 50;

