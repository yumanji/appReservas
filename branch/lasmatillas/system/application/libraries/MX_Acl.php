<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MX_Acl - Access Control library
 * 
 * Save this file as application/libraries/MX_Acl.php
 * $autoload['libraries'] = array('mx_acl', ... );
 * $config['cache_path'] must be set
 * 
 * @copyright     Copyright (c) Wiredesignz & Maxximus 2009-04-20
 * @version        0.12
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class MX_Acl 
{    
    var $uri, $config, $session, $cache_path;
    
    function MX_Acl($config = array()) {
        $ci =& get_instance();    
        $ci->load->helper('url');        
        $ci->load->library('session');
    
        /* PHP4 needs this helper */
        if (floor(phpversion() < 5)) {
            $ci->load->helper('strripos');
        }
        
        $this->uri =& $ci->uri;
        $this->config = $config;
        $this->session =& $ci->session;
        $this->cache_path = $ci->config->item('cache_path');
        
        /* previous flashdata is available to views */
        $ci->load->vars($config['error_var'], $this->session->flashdata($config['error_var']));
        
        /* run the access control check now */
        ($config['check_uri']) AND $this->check_uri();
    }
    
    /**
     * Check the current uri and user privileges against the cached ACL array
     * Redirect if access is denied
     * 
     * @return void
     */
    function check_uri() {
        
        /* Load the cached access control list or show error */
        //echo $this->cache_path.'mx_acl'.EXT.'   -  '.$cached_acl;
        (is_file($cached_acl = $this->cache_path.'mx_acl'.EXT)) OR show_error($this->config['error_msg']. "JJJJJJJJJ");
        
        $acl = include $cached_acl;
        //echo '<br><br><br><br><br><br>'.$this->current_uri();print_r($acl);//exit();
        /* Match current url to access list */
        if (is_array($acl) AND $acl = $this->match_uri($this->current_uri(), $acl)) {
            /* Check session group against access level group */
            $allow_access = (bool)(in_array($this->session->userdata($this->config['session_var']), $acl['allowed']));
            //print_r($acl['allowed']);
            //echo $this->session->userdata($this->config['session_var'])."AAA";
            //if($allow_access) echo 'BBB'; else echo 'CCC';
                
             /* Additional check to allow IP addresses in range */
            if ( ! $allow_access AND isset($acl['ipl'])) {
            	$allow_access = $this->check_ip($acl['ipl']);
            	//echo "JJJ";
            }
            //if($allow_access) echo 'DDD'; else echo 'EEE';
            if ($allow_access == FALSE)    {
                
                /* Set a return url into the session */
                $this->session->set_userdata('return_url', $this->uri->uri_string());
                
                /* set the error message... */
                $error_msg = (isset($acl['error_msg'])) ? $acl['error_msg'] : $this->config['error_msg'];
                    
                /* set a flash message... */
                $this->session->set_userdata($this->config['error_var'], $error_msg);        
                    
                /* redirect to absolute url */
                die(header("Location: ".$acl['error_uri'], TRUE, 302));
            }
        }
    }
    
    /**
     * Return the access control profile for a given url
     * 
     * @return string
     * @param string $current_uri
     * @param array  $acl
     */
    function match_uri($current_uri, $acl) {
        if (array_key_exists($current_uri, $acl)) {
            return $acl[$current_uri];            
        } else {
            if ($pos = strripos($current_uri, '/')) {
                return $this->match_uri(substr($current_uri, 0, $pos), $acl);
            }
        }
    }

    /**
     * Returns the first 2 or 3 segments from the current uri
     * 
     * @return string
     */
    function current_uri() {
        if ($this->uri->total_segments() == 2)    {        
            return $this->uri->slash_rsegment(1).$this->uri->rsegment(2);
        }
        return $this->uri->slash_rsegment(1).$this->uri->slash_rsegment(2).$this->uri->rsegment(3);
    }

    /** 
     * Checks the remote IP address against the specified $ipl array
     * 
     * @return bool
     * @param array $ipl
     * @param string $remote_ip[optional]
     */    
     function check_ip($ipl, $remote_ip = NULL) {
        
        /* Convert ip address into a double (for lousy OSes)*/
        $remote_ip = floatval(ip2long(($this->session->userdata('ip_address'))));
        
        /* Loop through the ip list array */
        foreach ($ipl as $allowed_ip) {
            
            /* Replace '*' (for IP ranges) with a suitable range number */
            $min = str_replace("*", "0", $allowed_ip);        
            $max = str_replace("*", "255", $allowed_ip);

            /* Check for a match */
            if (($remote_ip >= floatval(ip2long($min))) AND ($remote_ip <= floatval(ip2long($max)))) {
                return TRUE;
            }
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                      