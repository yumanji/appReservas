<?php
	$this->lang->load('reservas');
	//print("<pre>");print_r($info);
	$info = $info[0];
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p align="center">'."\r\n";
	echo ''.$info->description.'<br>'."\r\n";
	echo 'con un pago de <b>'.$info->quantity.'&euro;</b>.<br>'."\r\n";
	echo 'a pagar a partir del '.date($this->config->item('reserve_date_filter_format'), strtotime($info->datetime)).'<br>'."\r\n";
	//echo $this->lang->line($info['status_desc']).'&nbsp;&nbsp;&nbsp;Precio: '.$info['quantity'].'&euro;&nbsp;&nbsp;&nbsp;';
	//else echo '<b>SIN</b> luz'."\r\n";
	
	
	/*
	if(isset($buttons) && $buttons) {
		echo '<p align="right">'."\r\n";
		if($info['status']<'9') echo img( array('src'=>'images/coins.png', 'border'=>'0', 'alt' => 'Pagar'));
		echo '</p>'."\r\n";
	}
	*/
	
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

