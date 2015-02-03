<?php
	$this->lang->load('reservas');
	isset($this->CI) || $this->CI =& get_instance();


	
	
	$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);


	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';
	if($info['light']) echo '<p>'.$this->lang->line('confirmation_light_extra').'('.$info['light_price'].$this->lang->line('currency').')</p>'."\r\n"; 
	//echo $this->lang->line('confirmation_detail_intro');
	/*
	echo '<fieldset>';
	echo '<legend>'.$this->lang->line('confirmation_reserved_for').' '.date($this->config->item('reserve_date_filter_format'),strtotime($info['date'])).'</legend>';
	//echo $this->lang->line('confirmation_detail_intro');
	print('<ul id="pistas">'."\r\n");
	foreach($info['reserva'] as $pista => $datos) {
		print('<li id="pista">'.$pista."\r\n");
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

	if(isset($pay) && $pay==1 && $pay_content!="") {
		# Pinto los elementos de formas de pago... Llamar� a una vista que lo pinte seg�n config.. a la cual le pasar� id de transacci�n, descripcion y coste
		echo '<br>'.$this->lang->line('payment_ways').'<br>'.$pay_content;
		
		if($methods['tpv']) {
			
			$this->CI->config->load('pagos');
			$this->CI->config->load('pago_'.$this->config->item('tpv_library_prefix'));

  		$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post', 'target' => 'tpv');			
  		echo form_open($this->config->item('tpv_payment_url'), $attributes);
			
			$amount=str_replace('.', '', str_replace(',', '', number_format($info['total_price'],2)));
			
			# Tama�o minimo de los caracteres num�ricos = 4 .. m�s un sufijo para identificar el registro que estoy pagando
			if(strlen($info['id'])<4) $order=sprintf("%04s", $info['id']).'re';
			else $order=$info['id'].'re';
			
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
			echo '<input type="hidden" name="Ds_Merchant_ProductDescription" value="Reserva codigo '.$info['booking_code'].'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Titular" value="'.$info['user_desc'].'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$this->config->item('tpv_url_ok').'/'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$this->config->item('tpv_url_ko').'/'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantName" value="'.substr($this->config->item('club_name'), 0, 25).'">'."\r\n";
			$message = $amount.$order.$code.$currency.$transactionType.$urlMerchant.$clave;
			//$message = $amount.$order.$code.$currency.$clave;
			//$signature = strtolower(sha1($message));			
			$signature = sha1($message);			
			echo '<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$signature.'">'."\r\n";
			
			echo form_close();
			
			echo '<script type="text/javascript">'."\r\n";
			echo 'function pagar_tpv() {'."\r\n";
			//echo 'alert("'.$message.'");'."\r\n";
			echo "vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');"."\r\n";
			echo "document.getElementById('frmPay').submit();"."\r\n";
			echo '}'."\r\n";
			echo '</script>'."\r\n";
		}
	}
# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.
	//echo form_close();


	# Funci�n que env�a la petici�n del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";

	if($saldo_prepago < $info['total_price']) echo "if(method == 'prepaid' ) { alert(\"Saldo insuficiente en tu monedero.\"); return true;}"."\r\n";
	//echo "    document.getElementById('".$form."').action='".site_url('reservas/pay')."/'+method+'/".$this->session->userdata('session_id')."';"."\r\n";
	echo "    var direccion3 = '".site_url('reservas/pay2')."/'+method+'/".$transaction_id."/false/null/".$logged_user."/null/null/".time()."';"."\r\n";
	echo '  if(method == "tpv") {pagar_tpv();}'."\r\n";
	?>
				$(function() {
					// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!

							//alert(direccion3);
							//return;
							$("#accordion").accordion({ animated: 'slide' });
							$("#confirm_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 10px; margin-left: 5px;"></span>Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
							$("#accordion").accordion("activate" , 3);
							$.ajax({
							  url: direccion3,
							  success: function(data) {
							  	//alert(data);
							    $("#search_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda.</p>  </div> </div>');
							    //alert('Load was performed');
							    $("#confirm_payment").html(data);
							  }
							});
							direccion3 =<?php echo " '".site_url('reservas/pay2')."/'+method+'/".$this->session->userdata('session_id')."';"."\r\n"; ?>; // Reseteo variable
							
							
					});	
	<?php
	echo '}'."\r\n";
	echo '</script>'."\r\n";
	
	?>

