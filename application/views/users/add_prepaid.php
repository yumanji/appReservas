<?php
			$this->CI->config->load('pagos');
			$this->CI->config->load('pago_'.$this->config->item('tpv_library_prefix'));
			$this->load->helper('tpv');
			$amount_sin_formato=$this->input->post('Ds_Merchant_Amount');
			$amount_recibido = str_replace('.', '', str_replace(',', '', $this->input->post('Ds_Merchant_Amount')));
  		if($amount_recibido!='') {
				$order=$this->input->post('Ds_Merchant_Order');
  			
  		}	else {
				$order=$codigo_pedido;
  		}
			
			//$amount=str_replace('.', '', str_replace(',', '', number_format($info['total_price'],2)));
			
			# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
			/*
			if(strlen($proximo_pago)<4) $order=sprintf("%04s", $proximo_pago).'pr';
			else $order=$proximo_pago.'pr';
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
			echo form_creator('sermepa', $data);
?>
<script>
	$(function() {
		$( "button").button();
	});
</script>
<fieldset style="width: 600px; 	border-color: #2D588B; 	color: #2D588B; 	font-size: 11px; padding-left: 10px;">
	<legend style="color: #2D588B;">Recarga saldo bono para <?php echo $user_desc;?></legend>

	<?php
	
		$attributes = array('class' => 'frmPay2', 'id' => 'frmPay2', 'method' => 'post');
		echo form_open(current_url(), $attributes);

		echo '<br>Cantidad: <input type="text" size="10" id="amount" name="amount" value="'.$amount_sin_formato.'">&nbsp;(Saldo previo: '.$pre_ammount.$this->lang->line('currency').')<br>&nbsp;<br>'."\r\n";
		echo '<input type="hidden" name="order_num" value="'.$order.'">'."\r\n";
		
		echo form_close();
		
		if($amount_recibido!='') {
			if($grupo > 5) {
			?>
					Forma de pago: <button id="1" class="buttonpay">Efectivo</button>&nbsp;<button id="2" class="buttonpay">Tarjeta</button>&nbsp;<br>&nbsp;
			<?php
			} else {
			?>
					Forma de pago: <button id="buttontpv">TPV</button><br>&nbsp;
			<?php
			}
		} else {
	?>
	<button id="validar">Validar</button><br>&nbsp;&nbsp;	
	
	<?php
		}
	?>
</fieldset>
<button value="Cancelar" onClick="javascript: location.href='<?php echo $cancelUrl?>'">Cancelar</button>
<script>
	$(function() {
		
		<?php
			if($amount_recibido!='') echo '$("#target").attr("disabled", true);'."\r\n";
		
		?>
		
		$( ".buttonpay" ).click(function() { 
			$("#frmPay2").attr("action", "<?php echo site_url($this->uri->uri_string()); ?>/"+$(this).attr('id')+"/<?php echo $control;?>");
			$("#frmPay2").submit();
			return false;
			});



		$( "#validar" ).click(function() { 

			if($('input[name="amount"]').val()=="" || $('input[name="amount"]').val()== "00.00") { alert('Debe especificar una cantidad'); return; }
			$('input[name="Ds_Merchant_Amount"]').val($('input[name="amount"]').val());

			$("#frmPay").submit();


			return false;
			});
		
		$( '#buttontpv' ).click(function() { 
			vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');
			$("#frmPay").attr("target","tpv");
			document.getElementById('frmPay').submit();


			return false;
			});
	$("#amount").mask("99.99");

	});
</script>