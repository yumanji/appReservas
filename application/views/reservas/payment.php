<?php
	$this->lang->load('reservas');
	$this->config->load('pagos');
	if($success == "1"){
			$fecha = date($this->config->item('reserve_date_filter_format'), strtotime($info['date']));
		 echo img( array('src'=>'images/reserveok.png', "align"=>"left")).$this->lang->line('payment_success');
		 echo '<br><p>'.$this->lang->line('confirmation_advice_payment').' '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b> '.$this->lang->line('confirmation_date').' <b>'.$fecha.'</b> '.$this->lang->line('confirmation_from').' <b>'.$info['inicio'].'</b> '.$this->lang->line('confirmation_to').' <b>'.$info['fin'].'</b> </p>';
		 echo '<p>El c&oacute;digo de reserva es el '.$info['booking_code'].'. Recu&eacute;rdalo para identificarte a la hora de ir al club.</p>';
		 echo '<p align="right">Comenta con tus amigos esta reserva! ';

		 # Link para el twiter
		 echo '<a href="http://twitter.com/home?status=He reservado un partido en '.$this->config->item('club_name').' para el '.$fecha.'" title="Comp&aacute;rtelo en Twitter"  target="_blank">'.img(array('src' => 'images/twitter.png', 'alt' => 'Comparte con Twiter', 'align' => 'absmiddle', 'title' => 'Comparte con Twiter', 'border'=>'0')).'</a>';
		 
		 # Link para el Facebook
		 echo '<a href="javascript: void(0);" onclick="window.open(\'http://www.facebook.com/sharer.php?u='.urlencode(site_url('reservas/resume/'.$info['id_transaction'].'/'.$info['booking_code'])).'\',\'ventanacompartir\', \'toolbar=0, status=0, width=650, height=450\');">'.img(array('src' => 'images/facebook.png', 'align' => 'absmiddle', 'alt' => 'Comparte con Facebook', 'title' => 'Comparte con Facebook', 'border'=>'0')).'</a>';
		 
		 # Link para el tuenti
		 echo '<a href="http://www.tuenti.com/share?url='.urlencode(site_url('reservas/resume/'.$info['id_transaction'].'/'.$info['booking_code'])).'" target="_blank">'.img(array('src' => 'images/tuenti.png', 'alt' => 'Comparte con Tuenti', 'align' => 'absmiddle', 'title' => 'Comparte con Tuenti', 'border'=>'0')).'</a>';
		 
		 
		 if($permiso_ticket) echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.site_url('facturacion/view_receipt/'.$pago->id).'">'.img(array('src' => 'images/printer-icon.png', 'alt' => 'Imprimir tiquet de reserva', 'align' => 'absmiddle', 'title' => 'Imprimir tiquet de reserva', 'border'=>'0')).'</a>';
		 echo '</p>';
	} 	elseif($success == "5") {
		# Para reservas.. sin pago.
			$fecha = date($this->config->item('reserve_date_filter_format'), strtotime($info['date']));
		 echo img( array('src'=>'images/reloj.png', "align"=>"left")).$this->lang->line('payment_reserve');
			echo '<br><p>'.$this->lang->line('confirmation_advice_payment').' '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b> '.$this->lang->line('confirmation_date').' <b>'.$fecha.'</b> '.$this->lang->line('confirmation_from').' <b>'.$info['inicio'].'</b> '.$this->lang->line('confirmation_to').' <b>'.$info['fin'].'</b> </p>';
		 echo '<p>El c&oacute;digo de reserva es el '.$info['booking_code'].'. Recu&eacute;rdalo para identificarte a la hora de ir al club.</p>';
		 echo '<p align="right">Comenta con tus amigos esta reserva! ';

		 # Link para el twiter
		 echo '<a href="http://twitter.com/home?status=He reservado un partido en '.$this->config->item('club_name').' para el '.$fecha.'" title="Comp&aacute;rtelo en Twitter"  target="_blank">'.img(array('src' => 'images/twitter.png', 'alt' => 'Comparte con Twiter', 'align' => 'absmiddle', 'title' => 'Comparte con Twiter', 'border'=>'0')).'</a>';
		 
		 # Link para el Facebook
		 echo '<a href="javascript: void(0);" onclick="window.open(\'http://www.facebook.com/sharer.php?u='.urlencode(site_url('reservas/resume/'.$info['id_transaction'].'/'.$info['booking_code'])).'\',\'ventanacompartir\', \'toolbar=0, status=0, width=650, height=450\');">'.img(array('src' => 'images/facebook.png', 'align' => 'absmiddle', 'alt' => 'Comparte con Facebook', 'title' => 'Comparte con Facebook', 'border'=>'0')).'</a>';
		 
		 # Link para el tuenti
		 echo '<a href="http://www.tuenti.com/share?url='.urlencode(site_url('reservas/resume/'.$info['id_transaction'].'/'.$info['booking_code'])).'" target="_blank">'.img(array('src' => 'images/tuenti.png', 'alt' => 'Comparte con Tuenti', 'align' => 'absmiddle', 'title' => 'Comparte con Tuenti', 'border'=>'0')).'</a>';
		 
		 echo '</p>';
	} else if($success == "9") {
			//$fecha = date($this->config->item('reserve_date_filter_format'), strtotime($info['date']));
		 echo img( array('src'=>'images/reloj.png', "align"=>"left")).$this->lang->line('payment_waiting');


			$this->config->load('pago_'.$this->config->item('tpv_library_prefix'));
			$this->load->helper('tpv');
			
			$amount=str_replace('.', '', str_replace(',', '', number_format($total,2)));
			
			$data = array();
			$data['Ds_Merchant_Amount'] = $amount;
			$data['tpv_payment_url'] = $this->config->item('tpv_payment_url');
			$data['Ds_Merchant_Currency'] = $this->config->item('tpv_moneda');
			$data['Ds_Merchant_Order'] = $order;
			$data['Ds_Merchant_MerchantCode'] = $this->config->item('tpv_codigo_comercio');
			$data['Ds_Merchant_Terminal'] = $this->config->item('tpv_terminal');
			$data['Ds_Merchant_TransactionType'] = $this->config->item('tpv_transaction_type');
			$data['Ds_Merchant_MerchantURL'] = $this->config->item('tpv_url_return');
			$data['Ds_Merchant_ProductDescription'] = "Reserva codigo ".$info['booking_code'];
			$data['Ds_Merchant_Titular'] = $info['user_desc'];
			$data['Ds_Merchant_UrlOK'] = $this->config->item('tpv_url_ok').'/'.$order;
			$data['Ds_Merchant_UrlKO'] = $this->config->item('tpv_url_ko').'/'.$order;
			$data['Ds_Merchant_MerchantName'] = substr($this->config->item('club_name'), 0, 25);
			$data['Ds_Merchant_SecretWord'] = $this->config->item('tpv_palabra_secreta');
			echo form_creator('sermepa', $data);
			
		
		 	echo '<br>Estamos a la espera de confirmar el pago con su tarjeta de cr&eacute;dito a trav&eacute;s de la pasarela bancaria. Si el pago por tarjeta ha fallado, esta reserva queda anulada y deber&aacute; realizar una nueva.<br>';
		 	//echo 'Se ha tenido que abrir una nueva ventana solicitándole los datos de su tarjeta. <br>Si &eacute;sto no ha ocurrido quiz&aacute; es que tenga un sistema de bloqueo de popups activo. <br>';
		 	//echo '<p style="font-size: 0.85em;">En caso de que no se haya abierto una nueva ventana solicit&aacute;ndole la informaci&oacute;n bancaria, haga clic en el siguiente bot&oacute;n para abrirla de nuevo: <input type="submit" value="Reintentar pago"><br>';
		 	//echo '(Es importante que no pulse sobre el bot&oacute;n si la ventana de pago ya se abri&oacute;)</p>';
		 	
		 
		 
			//echo '<br><p>'.$this->lang->line('confirmation_advice_payment').' '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b> '.$this->lang->line('confirmation_date').' <b>'.$fecha.'</b> '.$this->lang->line('confirmation_from').' <b>'.$info['inicio'].'</b> '.$this->lang->line('confirmation_to').' <b>'.$info['fin'].'</b> </p>';
		 //echo '<p>El c&oacute;digo de reserva es el '.$info['booking_code'].'. Recu&eacute;rdalo para identificarte a la hora de ir al club.</p>';
		
	} else echo img( array('src'=>'images/reservefail.png', "align"=>"left", "border"=>"0")).'<p style="margin-top: 25px;">'.$this->lang->line('payment_error').'</p>';
?>