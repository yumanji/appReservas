  <noscript>This site just doesn't work, period, without JavaScript</noscript>

   <!-- IF LOGGED IN -->

          <!-- Content here -->

   <!-- IF LOGGED OUT -->

<h2>
	<?php 
	echo $this->lang->line('welcome_title').', ';
	if($profile->first_name!="") echo $profile->first_name;
	else echo $this->lang->line('usuario');
	?>
</h2>

<p><?php echo $this->lang->line('welcome_text_logged'); ?></p>

<ul class="big_button_bar">
	<?php if(isset($profile) && isset($profile->group) && $profile->group < 3) {  ?><li class="big_button" ><a href="#" onClick="javascript: f_open_window_max('<?php echo site_url('recepcion/index');?>', 'recepcion');"><?php echo img( array('src'=>'images/controlpanel.gif', 'border'=>'0', 'alt' => 'Panel Control'));?><br>Panel Control</a></li> <?php } ?>
	<li class="big_button" ><a href="<?php echo site_url('reservas/index');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => 'Reservar'));?><br>Reservar</a></li>
	<!--<li class="big_button" ><a href="<?php echo site_url('reservas_gest/today');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => 'Listado de hoy'));?><br>Listado de Hoy</a></li>-->
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
</ul>	