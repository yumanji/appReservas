<div id="search_result">
	<?php  
				
	if(is_array($availability)) {
		foreach($availability as $name => $avail) {
			print('<div id="court_availability"><table id="availability"><tr>');
			echo '<td>'.$name.'</td>';
			foreach($avail as $code => $value) {
				$text = $value[0];
				if(strstr($text, ':30')) $text = $this->config->item('half_hour_simbol');
				else $text = '&nbsp;'.substr($text, 0, 2).'&nbsp;';
				if($value[1]=="0") print('<td class="full" >'.$text.'</td>');
				else print('<td class="free" id="'.$code.'" onClick="javascript: reservar(\''.$code.'\', \''.$user_id.'\', this);">'.$text.'</td>'."\r\n");
			}
			echo "</tr></table></div>";   
		}   	 
	}
	?>
	<div id="coste"></div><input type="hidden" name="price" id="numCoste" value="0">
	<?php
		$js = 'id="buttonConfirma" disabled onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url('reservas/confirm').'\'; document.getElementById(\'frmReserva\').submit(); "';
		echo form_button('buttonConfirma', 'Confirma tu reserva!', $js);

	?>
</div>