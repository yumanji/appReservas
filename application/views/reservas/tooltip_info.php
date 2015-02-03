<?php
	$this->lang->load('reservas');
	//print("<pre>");print_r($info);
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p align="center">'."\r\n";
	echo '<b>'.$info['court'].'</b> de '.$info['inicio'].' a '.$info['fin'].'<br>'."\r\n";
	echo 'con codigo <b>'.$info['booking_code'].'</b>.<br>'."\r\n";
	if(!isset($info['playing_users']) || count($info['playing_users'])<=1) echo '<b>'.$info['user_desc'].'</b> ('.$info['user_phone'].')<br>'."\r\n";
	else {
		$i = 0;
		foreach($info['playing_users'] as $jugador) {
			if($i==0) echo '<b>';
			if($i==0) echo img( array('src'=>'images/pagador.png', 'height'=>'16', 'width'=>'16', 'border'=>'0', 'alt' => 'Usuario que reserv&oacute;')).'&nbsp;';
			else{
				if($jugador['user_desc']!='' && $jugador['user_desc']!='0') echo img( array('src'=>'images/jugadores.png', 'height'=>'16', 'width'=>'16', 'border'=>'0', 'alt' => 'Jugador registrado')).'&nbsp;';
				else echo img( array('src'=>'images/invitado.png', 'height'=>'16', 'width'=>'16', 'border'=>'0', 'alt' => 'Jugador externo')).'&nbsp;';
			}
			echo $jugador['user_desc'].' ('.$jugador['user_phone'].')';
			if($i==0) echo '</b>';
			echo '<br>'."\r\n";
			$i++;
		}
	}
	echo $this->lang->line($info['status_desc']).'&nbsp;&nbsp;&nbsp;Precio: '.$info['total_price'].'&euro;&nbsp;&nbsp;&nbsp;';
	if($info['light']=='1') echo '<b>CON</b> luz'."\r\n";
	//else echo '<b>SIN</b> luz'."\r\n";
	
	
	if(isset($buttons) && $buttons) {
		echo '<p align="right">'."\r\n";
		if($info['status']<'9') echo img( array('src'=>'images/coins.png', 'border'=>'0', 'alt' => 'Pagar'));
		echo '</p>'."\r\n";
	}
	
	//con una duraci&oacute;n de '.$this->app_common->IntervalToTime($info['intervals']).'. '.$this->lang->line('confirmation_total').$info['price'].$this->lang->line('currency').'.';
	//print("<pre>");print_r($info);
	/*
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
*/
	//echo '</div>';
	echo '</p>'."\r\n";
?>

