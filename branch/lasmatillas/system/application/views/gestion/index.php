<?php
	$this->lang->load('reservas_gest');
?>
<ul class="big_button_bar">
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 5) {  ?>
		<li class="big_button" ><a href="<?php echo site_url('reservas_gest/list_all');?>"><?php echo img( array('src'=>'images/list_all.png', 'border'=>'0', 'alt' => 'Listado general'));?><br>Reservas</a></li>
		<li class="big_button" ><a href="<?php echo site_url('users/index/active');?>"><?php echo img( array('src'=>'images/registered_user.png', 'border'=>'0', 'alt' => 'Listado usuarios'));?><br>Usuarios</a></li>
		<li class="big_button" ><a href="<?php echo site_url('facturacion/list_all');?>"><?php echo img( array('src'=>'images/euro_currency_sign.png', 'border'=>'0', 'width' => '128', 'alt' => 'Facturacion', 'title' => 'Facturacion'));?><br>Facturaci&oacute;n</a></li>
		<li class="big_button" ><a href="#" onClick="javascript: f_open_window_max('<?php echo site_url('recepcion/index');?>', 'recepcion');"><?php echo img( array('src'=>'images/pista.png', 'border'=>'0', 'width' => '128', 'alt' => 'Panel Control', 'title' => 'Panel Control'));?><br>Panel Control</a></li>
		<li class="big_button" ><a href="<?php echo site_url('lessons/index');?>"><?php echo img( array('src'=>'images/profesor.png', 'border'=>'0', 'width' => '128', 'alt' => 'Clases y cursos', 'title' => 'Clases y cursos'));?><br>Clases</a></li>
		<li class="big_button" ><a href="<?php echo site_url('notifications');?>"><?php echo img( array('src'=>'images/mail_forward.gif', 'border'=>'0', 'width' => '128', 'alt' => 'Mensajes', 'title' => 'Mensajes'));?><br>Mensajes</a></li>
		<li class="big_button" ><a href="<?php echo site_url('retos');?>"><?php echo img( array('src'=>'images/reto.png', 'border'=>'0', 'width' => '128', 'alt' => 'Retos', 'title' => 'Retos'));?><br>Retos</a></li>
		<li class="big_button" ><a href="<?php echo site_url('ranking');?>"><?php echo img( array('src'=>'images/ranking.png', 'border'=>'0', 'width' => '128', 'alt' => 'Ranking', 'title' => 'Ranking'));?><br>Ranking</a></li>
	<?php } ?>
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 2) {  ?>
		<li class="big_button" ><a href="<?php echo site_url('gestion/pistas');?>"><?php echo img( array('src'=>'images/config.png', 'border'=>'0', 'width' => '128', 'alt' => 'Configuracion', 'title' => 'Configuracion'));?><br>Configuracion</a></li>
	<?php } ?>
	<!--<li class="big_button" ><a href="<?php echo site_url('reservas_gest/today');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => 'Listado de hoy'));?><br>Listado de Hoy</a></li>-->
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
</ul>	

