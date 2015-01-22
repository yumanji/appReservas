<?php
	//$this->lang->load('lessons');
	
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p align="center">'."\r\n";
	if($asistente->id_user != 0) {$usuario = $asistente->first_name; if($asistente->last_name) $usuario .= ' '.$asistente->last_name; }
	else $usuario = $asistente->user_desc;
	
	echo 'Nombre: <b>'.$usuario.'</b><br>'."\r\n";
	echo 'Alta en el curso: '.date($this->config->item('reserve_date_filter_format'), strtotime($asistente->sign_date)).'<br>'."\r\n";
	echo 'Ultimo pago: '.(($asistente->last_payd_date!="")?date($this->config->item('reserve_date_filter_format'), strtotime($asistente->last_payd_date)):'Ning&uacute;n pago registrado').'<br>'."\r\n";
	
	$mes_pagado = date('Ym', strtotime($asistente->last_payd_date));
	//echo 'pagado: '.$mes_pagado;
	$mes_pendiente = date('Ym', strtotime($asistente->last_payd_date . '+1 month'));
	//echo 'pendiente: '.$mes_pendiente;
	$proximo = $mes_pendiente.$asistente->monthly_payment_day;
	echo 'Pr&oacute;ximo pago: '.date($this->config->item('reserve_date_filter_format'), strtotime($proximo)).'<br>'."\r\n";
	if( $proximo - date($this->config->item('date_db_format')) < 3) echo '<span style="color: red;">Atenci&oacute;n! Fecha de próximo pago de riesgo!!</span>'.'<br>'."\r\n";

	
	echo '</p>'."\r\n";
?>

