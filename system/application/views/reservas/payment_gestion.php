<?php
	$this->lang->load('reservas');
	if($success){
		if(!isset($return_url) || $return_url == "") $return_url = 'reservas_gest/list_all';
			$fecha = date($this->config->item('reserve_date_filter_format'), strtotime($info['date']));
		 	echo img( array('src'=>'images/reserveok.png', "align"=>"left")).$this->lang->line('payment_success');
			echo '<br><p>'.$this->lang->line('confirmation_advice_payment').' '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b> '.$this->lang->line('confirmation_date').' <b>'.$fecha.'</b> '.$this->lang->line('confirmation_from').' <b>'.$info['inicio'].'</b> '.$this->lang->line('confirmation_to').' <b>'.$info['fin'].'</b> </p>';
			echo '<p style="	margin-left: 500px;"><a href="'.site_url($return_url).'">Volver al listado</a></p> ';

	} else img( array('src'=>'images/reservefail.png', "align"=>"absmiddle")).$this->lang->line('payment_error');
?>