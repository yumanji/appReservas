<?php 
	$this->CI =& get_instance();
	$perfil=$this->CI->redux_auth->profile();
	
	$search_permission = $this->config->item('main_search_permission');
	
	# Permiso para ver los informes
	$report_permission_array = $this->config->item('reports_permission');
	$report_permission = FALSE;
	if(isset($report_permission_array[$perfil->group])) $report_permission = $report_permission_array[$perfil->group];
	
	# Permiso para ver calendario
	$calendar_permission_array = $this->config->item('calendar_permission');
	$calendar_permission = FALSE;
	if(isset($calendar_permission_array[$perfil->group])) $calendar_permission = $calendar_permission_array[$perfil->group];
	
	# Permiso para ver actividades
	$activities_permission_array = $this->config->item('activities_permission');
	$activities_permission = FALSE;
	if(isset($activities_permission_array[$perfil->group])) $activities_permission = $activities_permission_array[$perfil->group];
	
	# Permiso para ver tienda
	$shop_permission_array = $this->config->item('shop_permission');
	$shop_permission = FALSE;
	if(isset($shop_permission_array[$perfil->group])) $shop_permission = $shop_permission_array[$perfil->group];
	
	# Permiso para ver panel control
	$control_panel_permission_array = $this->config->item('control_panel_permission');
	$control_panel_permission = FALSE;
	if(isset($control_panel_permission_array[$perfil->group])) $control_panel_permission = $control_panel_permission_array[$perfil->group];
	
	# Permiso para ver ranking
	$ranking_permission_array = $this->config->item('ranking_permission');
	$ranking_permission = FALSE;
	if(isset($ranking_permission_array[$perfil->group])) $ranking_permission = $ranking_permission_array[$perfil->group];
	
	# Permiso para ver lessons
	$lessons_permission_array = $this->config->item('lessons_admin_permission');
	$lessons_permission = FALSE;
	if(isset($lessons_permission_array[$perfil->group])) $lessons_permission = $lessons_permission_array[$perfil->group];

?>

<ul class="big_button_bar">
	<?php if($control_panel_permission) {  ?><li class="big_button" ><a href="#" onClick="javascript: f_open_window_max('<?php echo site_url('recepcion/index');?>', 'recepcion');"><?php echo img( array('src'=>'images/pista.png', 'border'=>'0', 'width' => '128', 'alt' => 'Panel Control', 'title' => 'Panel Control'));?><br>Panel Control</a></li><?php } ?>
	<li class="big_button" ><a href="<?php echo site_url('reservas/index');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'width' => '128', 'alt' => 'Reservar', 'title' => 'Reservar'));?><br>Reservar</a></li>
	<?php if($lessons_permission) {  ?><li class="big_button" ><a href="<?php echo site_url('lessons/lista');?>"><?php echo img( array('src'=>'images/profesor.png', 'border'=>'0', 'width' => '128', 'alt' => 'Clases y cursos', 'title' => 'Clases y cursos'));?><br>Clases</a></li><?php } ?>
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 7) {  ?><li class="big_button" ><a href="<?php echo site_url('facturacion/list_all');?>"><?php echo img( array('src'=>'images/euro_currency_sign.png', 'border'=>'0', 'width' => '128', 'alt' => 'Facturacion', 'title' => 'Facturacion'));?><br>Facturaci&oacute;n</a></li><?php } ?>
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 7) {  ?><li class="big_button" ><a href="<?php echo site_url('users/index/active');?>"><?php echo img( array('src'=>'images/registered_user.png', 'border'=>'0', 'alt' => 'Listado usuarios'));?><br>Listado Usuarios</a></li><?php } ?>
	<?php if($report_permission) {  ?><li class="big_button" ><a href="<?php echo site_url('informes/index');?>"><?php echo img( array('src'=>'images/informes.png', 'border'=>'0', 'width' => '128', 'alt' => 'Informes', 'title' => 'Informes'));?><br>Informes</a></li><?php } ?>
	<!--<li class="big_button" ><a href="<?php echo site_url('reservas_gest/today');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => 'Listado de hoy'));?><br>Listado de Hoy</a></li>-->
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 5) {  ?><li class="big_button" ><a href="<?php echo site_url('notifications');?>"><?php echo img( array('src'=>'images/mail_forward.gif', 'border'=>'0', 'width' => '128', 'alt' => 'Mensajes', 'title' => 'Mensajes'));?><br>Mensajes</a></li><?php } ?>
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 7) {  ?><li class="big_button" ><a href="<?php echo site_url('retos');?>"><?php echo img( array('src'=>'images/reto.png', 'border'=>'0', 'width' => '128', 'alt' => 'Retos', 'title' => 'Retos'));?><br>Retos</a></li><?php } ?>
	<?php if($ranking_permission) {  ?><li class="big_button" ><a href="<?php echo site_url('ranking');?>"><?php echo img( array('src'=>'images/ranking.png', 'border'=>'0', 'width' => '128', 'alt' => 'Ranking', 'title' => 'Ranking'));?><br><?php echo $this->lang->line('ranking_name'); ?></a></li><?php } ?>
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
</ul>	