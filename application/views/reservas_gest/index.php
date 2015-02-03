<?php
	$this->lang->load('reservas_gest');
?>
<h1><?php echo $this->lang->line('reservas_gest_title'); ?></h1>


<ul class="big_button_bar">
	<li class="big_button" ><a href="<?php echo site_url('reservas_gest/list_all');?>"><?php echo img( array('src'=>'images/list_all.png', 'border'=>'0', 'alt' => 'Listado general'));?><br>Listado General</a></li>
	<li class="big_button" ><a href="<?php echo site_url('reservas_gest/today');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => 'Reservas de hoy'));?><br>Reservas de hoy</a></li>
	<!--<li class="big_button" ><a href="<?php echo site_url('reservas_gest/today');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => 'Listado de hoy'));?><br>Listado de Hoy</a></li>-->
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
</ul>	

