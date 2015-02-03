<?php
	$this->lang->load('reservas');

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
		echo '</tr>'."\r\n";
		echo '<table>'."\r\n";
	}

	if($methods['tpv']) {
		
		$this->config->load('pagos');
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
		
	}

	# Función que envía la petición del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";
	echo '  if(method == "tpv") { pagar_tpv();'."\r\n";
	echo "		var direccion3 = '".site_url('reservas_gest/list_all')."/".time()."';"."\r\n";
	echo '} else {'."\r\n";
	echo "  	var direccion3 = '".site_url('payment/payment_request_tmp')."/'+method+'/".$payment_type."/".$transaction_id."';"."\r\n";
	echo '}'."\r\n";
	?>
		location.href = direccion3;
	<?php
	//echo "    document.getElementById('".$form."').submit();"."\r\n";
	echo '}'."\r\n";
		
	echo '</script>'."\r\n";
?>