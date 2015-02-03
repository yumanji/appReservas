<?php
	$this->lang->load('reservas');
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
	<h3><a href="#"><?php echo $this->lang->line('payment_selection');?></a></h3>
	<div id="search_payment">


<?php
	echo 'Vamos a proceder a realizar el pago de los siguientes conceptos:'."\r\n";
	echo '<table align="center">'."\r\n";
	echo '<tr><td align="center"><b>Concepto</b></td><td align="right">Importe</td></tr>'."\r\n";
	$total = 0;
	foreach($lines as $id => $line) {
		echo '<tr>'."\r\n";
		echo '<td cellpadding=5>'.$line['text'].'</td>'."\r\n";
		echo '<td cellpadding=5 align="right">'.number_format($line['value'],2).'&euro;</td>'."\r\n";
		echo '</tr>'."\r\n";
		$total = $total + $line['value'];
	}
	echo '<tr>'."\r\n";
	echo '<td cellpadding=10><b>Total</b></td>'."\r\n";
	echo '<td cellpadding=5 align="right"><b>'.number_format($total,2).'&euro;</b></td>'."\r\n";
	echo '</tr>'."\r\n";
	echo ''."\r\n";
	echo '<table>'."\r\n";

	$cont = 0;
	foreach ($methods as $method => $active) {
			if($active) $cont++;
	}

	# Cargo el listado de formas de pago
	if(count($methods) > 0) {
		echo '<table align="center" border="0">'."\r\n";
		echo '<tr><td colspan="'.$cont.'" align="left">'.$this->lang->line('payment_ways').':</td></tr>'."\r\n";
		echo '<tr>'."\r\n";
		
		$attributes = array('id' => 'frmReserva');
		echo form_open(site_url('reservas'), $attributes);
		
		foreach ($methods as $method => $active) {
			if($active) {
				//$link='reservas/pay/'.$method.'/'.$transaction_id;
				echo '<td>'."\r\n";
				switch	($method) {
					case "reserve":
						//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						echo form_button('buttonReserva', $this->lang->line('reserve_button'), $js).'&nbsp;';
					break;
					
					case "cash":
						//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						echo form_button('buttonReserva', $this->lang->line('cash_button'), $js).'&nbsp;';
					break;
					
					case "prepaid":
						//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						if(!isset($prepaid_enabled) || !$prepaid_enabled) $js.= ' disabled';
						echo form_button('buttonReserva', $this->lang->line('prepaid_button'), $js).'&nbsp;';
						
					break;
					
					case "paypal":					
						//echo img('https://www.paypal.com/es_ES/ES/i/bnr/horizontal_solution_PP.gif', FALSE);
						//echo anchor($link, img('images/paypal.jpg'), array('title' => $this->lang->line('paypal_button'), 'onClick' => "javascript: pago('".$method."');"));
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						echo form_button('buttonReserva', $this->lang->line('paypal_button'), $js).'&nbsp;';
					break;
					
					case "creditcard":
						//echo anchor($link, img('images/creditcard.jpg'), array('title' => 'Pague con Tarjeta de Crédito!'));
						//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						echo form_button('buttonReserva', $this->lang->line('creditcard_button'), $js).'&nbsp;';
					break;
					
					case "tpv":
						//echo anchor($link, img('images/creditcard.jpg'), array('title' => 'Pague con Tarjeta de Crédito!'));
						//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						echo form_button('buttonReserva', $this->lang->line('tpv_button'), $js).'&nbsp;';
					break;
					
					case "bank":
						//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
						$js = 'id="buttonReserva" onClick="pago(\''.$method.'\'); "';
						if(!isset($bank_enabled) || !$bank_enabled) $js.= ' disabled';
						echo form_button('buttonReserva', $this->lang->line('bank_button'), $js).'&nbsp;';
					break;
					
					default:
					break;
				}
				echo '</td>'."\r\n";
			}
			
		}
		
		echo form_close();
		echo '</tr>'."\r\n";
		echo '</table>'."\r\n";
	}

	if($methods['tpv']) {
		
		$this->config->load('pagos');
		$this->config->load('pago_'.$this->config->item('tpv_library_prefix'));

		$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post');
		if($this->config->item('tpv_popup')) $attributes['target'] = 'tpv';			
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
		echo form_close();
?>		
	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_confirmation');?></a></h3>
	<div id="confirm_payment">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes confirmar la reserva.</p>
      </div>
    </div>
	</div>
</div>
<?php
	echo form_close();
		echo '<script type="text/javascript">'."\r\n";
		echo 'function pagar_tpv() {'."\r\n";
		//echo 'alert("'.$message.'");'."\r\n";
		echo "vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');"."\r\n";
		echo "document.getElementById('frmPay').submit();"."\r\n";
		echo '}'."\r\n";
		echo '</script>'."\r\n";
		
	}

	# Función que envía la petición del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";
	echo '  if(method == "tpv") { pagar_tpv();'."\r\n";
	//echo "		var direccion3 = '".site_url('reservas/booking_confirmation')."/".$transaction_id."/9/".time()."';"."\r\n";
	echo "  	var direccion3 = '".site_url('payment/payment_request_tmp')."/'+method+'/".$payment_type."/".$transaction_id."';"."\r\n";
	echo '} else {'."\r\n";
	echo "  	var direccion3 = '".site_url('payment/payment_request_tmp')."/'+method+'/".$payment_type."/".$transaction_id."';"."\r\n";
	echo '}'."\r\n";
	?>

		$("#frmReserva").attr("action", direccion3);
	  $("#frmReserva").submit();

	<?php
	//echo "    document.getElementById('".$form."').submit();"."\r\n";
	echo '}'."\r\n";
		
	echo '</script>'."\r\n";
?>