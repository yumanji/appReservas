<?php
	$this->lang->load('reservas');
	$this->config->load('pagos');

	isset($this->CI) || $this->CI =& get_instance();
?>
	<script type="text/javascript">
	$(function() {
		$("#accordion").accordion({
			autoHeight: false,
			navigation: true
		});
	$("#accordion").accordion("activate" , 3);
	});
	</script>	

<?php 
	echo '<table border="0">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.img( array('src'=>'images/target.png', 'border'=>'0', 'alt' => 'Reservas', 'align'=>'left')).'</td><td valign="middle">'.$this->lang->line('welcome').', '.$user_name.'. '.$this->lang->line('reserve_index_text')."\r\n";
	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'; 
	
	$attributes = array('id' => 'extraReserva');
	echo form_open(site_url('reservas'), $attributes);

	
	//if(isset($search_fields) && $search_fields!="") echo $search_fields;		
	
	//if(isset($result) && $result!="") echo $result;		
	
 ?>
 	
	





<div id="accordion" style="overflow:auto;">
	<h3><a href="#"><?php echo $this->lang->line('court_date_filters');?></a></h3>
	<div id="search_filters">
		
		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Si quiere reservar de nuevo, entre de nuevo en la secci&oacute;n de reservas o haga click <?php echo anchor('reservas', 'aqu&iacute;'); ?></p>  </div> </div>
		
	</div>
	
	<h3><a href="#"><?php echo $this->lang->line('interval_selection');?></a></h3>
	<div id="search_intervals" style="padding: 0.5em 1em;">
		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Si quiere reservar de nuevo, entre de nuevo en la secci&oacute;n de reservas o haga click <?php echo anchor('reservas', 'aqu&iacute;'); ?></p>  </div> </div>
 	</div>
	<h3><a href="#"><?php echo $this->lang->line('booking_extra_selection');?></a></h3>
	<div id="search_extra">
		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Si quiere reservar de nuevo, entre de nuevo en la secci&oacute;n de reservas o haga click <?php echo anchor('reservas', 'aqu&iacute;'); ?></p>  </div> </div>
 	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_confirmation');?></a></h3>
	<div id="confirm_payment">

<?php


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
	
			$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post');
			if($this->config->item('tpv_popup')) $attributes['target'] = 'tpv';	
			echo form_close();		
			echo form_open($this->config->item('tpv_payment_url'), $attributes);
			
			$amount=str_replace('.', '', str_replace(',', '', number_format($total,2)));
			
			$order = $order;
			$terminal=$this->config->item('tpv_terminal');
			$code=$this->config->item('tpv_codigo_comercio');
			$currency=$this->config->item('tpv_moneda');
			$transactionType=$this->config->item('tpv_transaction_type');
			$urlMerchant=$this->config->item('tpv_url_return');
			$clave=$this->config->item('tpv_palabra_secreta');
			
			/*
			$amount='1235';
			$order='29292929';
			$terminal='1';
			$code='201920191';
			$currency=$this->config->item('tpv_moneda');
			$transactionType=$this->config->item('tpv_transaction_type');
			$urlMerchant='';
			$clave='h2u282kMks01923kmqpo';
			*/
			
			echo '<input type="hidden" name="Ds_Merchant_Amount" value="'.$amount.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Currency" value="'.$currency.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Order"  value="'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$code.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Terminal" value="'.$terminal.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$transactionType.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$urlMerchant.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_ProductDescription" value="'.$paymentDescription.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Titular" value="">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$this->config->item('tpv_url_ok').'/'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$this->config->item('tpv_url_ko').'/'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantName" value="'.substr($this->config->item('club_name'), 0, 25).'">'."\r\n";
			$message = $amount.$order.$code.$currency.$transactionType.$urlMerchant.$clave;
			//$message = $amount.$order.$code.$currency.$clave;
			//$signature = strtolower(sha1($message));			
			$signature = sha1($message);			
			echo '<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$signature.'">'."\r\n";
			
	
			echo '<script type="text/javascript">'."\r\n";
			echo 'function pagar_tpv() {'."\r\n";
			//echo 'alert("'.$message.'");'."\r\n";
			echo "vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');"."\r\n";
			echo "document.getElementById('frmPay').submit();"."\r\n";
			echo '}'."\r\n";
			echo '</script>'."\r\n";
			
		
		 	echo '<br>Estamos a la espera de confirmar el pago con su tarjeta de cr&eacute;dito a trav&eacute;s de la pasarela bancaria. Si el pago por tarjeta ha fallado, esta reserva queda anulada y deber&aacute; realizar una nueva.<br>';
		 	//echo 'Se ha tenido que abrir una nueva ventana solicitándole los datos de su tarjeta. <br>Si &eacute;sto no ha ocurrido quiz&aacute; es que tenga un sistema de bloqueo de popups activo. <br>';
		 	//echo '<p style="font-size: 0.85em;">En caso de que no se haya abierto una nueva ventana solicit&aacute;ndole la informaci&oacute;n bancaria, haga clic en el siguiente bot&oacute;n para abrirla de nuevo: <input type="submit" value="Reintentar pago"><br>';
		 	//echo '(Es importante que no pulse sobre el bot&oacute;n si la ventana de pago ya se abri&oacute;)</p>';
			echo form_close();
		 	
		 
		 
			//echo '<br><p>'.$this->lang->line('confirmation_advice_payment').' '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b> '.$this->lang->line('confirmation_date').' <b>'.$fecha.'</b> '.$this->lang->line('confirmation_from').' <b>'.$info['inicio'].'</b> '.$this->lang->line('confirmation_to').' <b>'.$info['fin'].'</b> </p>';
		 //echo '<p>El c&oacute;digo de reserva es el '.$info['booking_code'].'. Recu&eacute;rdalo para identificarte a la hora de ir al club.</p>';
		
	} else echo img( array('src'=>'images/reservefail.png', "align"=>"left", "border"=>"0")).'<p style="margin-top: 25px;">'.$this->lang->line('payment_error').'</p>';
?>
	</div>
</div>
