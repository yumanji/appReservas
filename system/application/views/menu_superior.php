<?php 
	$this->CI =& get_instance();
	
	$panel_permission = $this->config->item('control_panel_permission');
	$caja_permission = $this->config->item('cierre_caja_permission');
	
	$perfil=$this->CI->redux_auth->profile();
	$nombre_usuario = $perfil->first_name;
	/*
	if($perfil->last_name != '') {
		if($nombre_usuario!='') $nombre_usuario .= ' ';
		$nombre_usuario .= $perfil->last_name;
	}
	*/
	//print_r($perfil);
?>
<!--navegacion superior -->
<ul class="navtop">
  <li class="user">Hola ! <?php echo $nombre_usuario; ?></li>
  <?php if($panel_permission[$perfil->group]) { ?><li><a href="#" onClick="javascript: f_open_window_max('<?php echo site_url('recepcion/index');?>', 'recepcion');"><?php echo img( array('src'=>'images/ico_panel.png', 'border'=>'0',  'width' => '35', 'height'=>'35', 'alt' => 'Panel')); ?>panel</a></li> <?php } ?>
  <?php if($perfil->group > 4) { ?>
  	<li><a href="<?php echo site_url('users/profile'); ?>"><?php echo img( array('src'=>'images/ico_user.png', 'border'=>'0',  'width' => '35', 'height'=>'35', 'alt' => 'Usuario')); ?>mi perfil</a></li>
  <?php } else { ?>
  	<li><a href="<?php echo site_url('users/new_user'); ?>"><?php echo img( array('src'=>'images/nuevo_usuario.png', 'border'=>'0',  'width' => '35', 'height'=>'35', 'alt' => 'Nuevo usuario')); ?>nuevo</a></li>
  <?php } ?>
  <li><a href="<?php echo site_url('reservas/index/'.time());?>"><?php echo img( array('src'=>'images/ico_reserva.png', 'border'=>'0',  'width' => '35', 'height'=>'35', 'alt' => 'Reservas')); ?>reservar</a></li>
  <?php if($caja_permission[$perfil->group]) { ?><li><a href="<?php echo site_url('informes/cierre_dia');?>"><?php echo img( array('src'=>'images/ico_caja.png', 'border'=>'0',  'width' => '35', 'height'=>'35', 'alt' => 'Caja')); ?>caja</a></li><?php } ?>
  <li><a href="<?php echo site_url('welcome/logout'); ?>"><?php echo img( array('src'=>'images/ico_exit.png', 'border'=>'0',  'width' => '35', 'height'=>'35', 'alt' => 'Salir')); ?>exit</a></li>
  </ul>
<!--fin navegacion superior -->
