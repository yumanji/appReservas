<?php
	$this->lang->load('reservas');
?>
	<script type="text/javascript">
	$(document).ready(function() {
		/*place jQuery actions here*/
	    $("a[rel^='prettyPhoto']").prettyPhoto();
		$(function() {
			$("#accordion").accordion({
				autoHeight: false,
				navigation: true
			});
		});
	});
	</script>	

<?php 
	echo '<table border="0" cellpadding="10">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.img( array('src'=>'images/target.png', 'border'=>'0', 'alt' => 'Reservas', 'align'=>'left')).'</td><td valign="middle">'.$this->lang->line('welcome').', '.$user_name.'. '.$this->lang->line('reserve_index_text')."\r\n";
	echo '<td align="center">';
	$mapa = $this->config->item('club_map');
	if(isset($mapa) && $mapa!='') echo anchor(base_url().'images/'.$mapa, img( array('src'=>'images/map.png', 'border'=>'0', 'alt' => 'Ver mapa instalaciones', 'title' => 'Ver mapa instalaciones', 'align'=>'right')), array('title' => 'Ver mapa instalaciones', 'rel' => 'prettyPhoto' )).'<br><b>Ver mapa</b>';
	$normativa = $this->config->item('club_normativa');
	if(isset($normativa) && $normativa!='') echo anchor_popup(base_url().'images/'.$normativa, img( array('src'=>'images/map.png', 'border'=>'0', 'alt' => 'Ver normativa', 'title' => 'Ver normativa', 'align'=>'right')), array('title' => 'Ver normativa' )).'<br><b>Ver normativa</b>';
	echo '</td>'."\r\n";
	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'; 
	
	$attributes = array('class' => 'frmReserva', 'id' => 'frmReserva');
	echo form_open(site_url('reservas/index/'.time()), $attributes);

	
	//if(isset($search_fields) && $search_fields!="") echo $search_fields;		
	
	//if(isset($result) && $result!="") echo $result;		
	
 ?>
 	
	





<div id="accordion" style="overflow:auto;">
	<h3><a href="#"><?php echo $this->lang->line('court_date_filters');?></a></h3>
	<div id="search_filters">
		
		<?php
			if(isset($search_fields) && $search_fields!="") echo $search_fields;
		?>
		
	</div>
	<h3><a href="#"><?php echo $this->lang->line('interval_selection');?></a></h3>
	<div id="search_intervals" style="padding: 0.5em 1em;">
		<?php
			//if(isset($result) && $result!="") echo '<p>'.$result.'</p>';
		?>
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes seleccionar pista y fecha.</p>
      </div>
    </div>
 	</div>
	<h3><a href="#"><?php echo $this->lang->line('booking_extra_selection');?></a></h3>
	<div id="search_extra">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes seleccionar pista, fecha y hora.</p>
      </div>
    </div>
	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_selection');?></a></h3>
	<div id="search_payment">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes seleccionar pista, fecha y hora.</p>
      </div>
    </div>
	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_confirmation');?></a></h3>
	<div id="confirm_payment">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes confirmar la reserva.</p>
      </div>
    </div>
	</div>
</div>
<?php
	echo form_close();
?>
