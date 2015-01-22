<?php
	$this->lang->load('reservas');
	
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);
	
	echo '<div id="reserve_resume"'."\r\n";
	echo '<p>Reserva con una duraci&oacute;n de '.$this->app_common->IntervalToTime($info['intervals']).'. '.$this->lang->line('confirmation_total').$info['total_price'].$this->lang->line('currency').'.</p>';
	echo $this->lang->line('confirmation_detail_intro');
	echo '<fieldset>';
	echo '<legend>'.$this->lang->line('confirmation_reserved_for').' '.date($this->config->item('reserve_date_filter_format'),strtotime($info['date'])).'</legend>';
	print('<ul id="pistas">'."\r\n");
	foreach($info['reserva'] as $pista => $datos) {
		print('<li id="pista"><b>'.$pista.'</b>'."\r\n");
		print('<ul id="intervalos">'."\r\n");
		foreach($datos as $dato) {
			print('<li id="intervalo">De '.$dato[0].' a '.$dato[1].' ('.$dato[2].$this->lang->line('currency').')'."\r\n");			
		}
		print('</ul>'."\r\n");
		print('</li>'."\r\n");
	}
	print('</ul>'."\r\n");
	echo '</fieldset>';

	echo '</div>';
?>

