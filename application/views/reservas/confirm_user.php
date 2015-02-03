<?php
	$this->lang->load('reservas');
	$this->CI =& get_instance();


	
	
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
		# Pinto los elementos de formas de pago... Llamaré a una vista que lo pinte según config.. a la cual le pasaré id de transacción, descripcion y coste
		echo '<br>'.$this->lang->line('payment_ways').'<br>'.$pay_content;
		
		if($methods['tpv']) {
			$this->CI->config->load('pagos');
			$this->CI->config->load('pago_'.$this->config->item('tpv_library_prefix'));

			$amount=str_replace('.', '', str_replace(',', '', number_format($info['total_price'],2)));
			
			# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
			if(strlen($info['id'])<4) $order=sprintf("%04s", $info['id']).'re';
			else $order=$info['id'].'re';
			
			
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
			echo $this->form_creator('sermepa', $data);

		}
	}
# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.
	//echo form_close();


	# Función que envía la petición del pago
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

