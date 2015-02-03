<?php
	$this->lang->load('informes');
?>
<h1><?php echo $this->lang->line('informes_title'); ?></h1>


<ul class="big_button_bar">
	<li class="big_button" ><a href="<?php echo site_url('informes/facturacion_diaria');?>"><?php echo img( array('src'=>'images/euro_currency_sign.png', 'border'=>'0', 'alt' => $this->lang->line('report_facturacion_diaria')));?><br><?php echo $this->lang->line('report_facturacion_diaria'); ?></a></li>
	<li class="big_button" ><a href="<?php echo site_url('informes/reserva_diaria');?>"><?php echo img( array('src'=>'images/list_by_date.png', 'border'=>'0', 'alt' => $this->lang->line('report_reserva_diaria')));?><br><?php echo $this->lang->line('report_reserva_diaria'); ?></a></li>
	<li class="big_button" ><a href="<?php echo site_url('informes/reserva_ocupacion');?>"><?php echo img( array('src'=>'images/ocupacion.png', 'border'=>'0', 'alt' => $this->lang->line('report_reserva_ocupacion')));?><br><?php echo $this->lang->line('report_reserva_ocupacion'); ?></a></li>
	<li class="big_button" ><a href="<?php echo site_url('informes/cierre_dia');?>"><?php echo img( array('src'=>'images/closeday.png', 'border'=>'0', 'alt' => $this->lang->line('report_cierre_dia')));?><br><?php echo $this->lang->line('report_cierre_dia'); ?></a></li>
	<li class="big_button" ><a href="<?php echo site_url('informes/clases_dia');?>"><?php echo img( array('src'=>'images/profesor.png', 'border'=>'0', 'width'=>'128', 'alt' => $this->lang->line('report_clases_dia')));?><br><?php echo $this->lang->line('report_clases_dia'); ?></a></li>
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
	<li class="big_button" ><?php echo img( array('src'=>'images/page.png', 'border'=>'0', 'alt' => 'Por definir'));?><br>Por definir</li>
</ul>	

