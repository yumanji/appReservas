<?php
			$this->CI->config->load('pagos');
			$this->CI->config->load('pago_'.$this->config->item('tpv_library_prefix'));
			$amount_sin_formato=$this->input->post('Ds_Merchant_Amount');
			$amount_recibido = str_replace('.', '', str_replace(',', '', $this->input->post('Ds_Merchant_Amount')));
  		if($amount_recibido!='') {
  			$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post');
  			echo form_open($this->config->item('tpv_payment_url'), $attributes);
				$order=$this->input->post('Ds_Merchant_Order');
  			
  		}	else {
  			$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post');
	  		echo form_open(current_url(), $attributes);
				$order=$codigo_pedido;
  		}
			
			//$amount=str_replace('.', '', str_replace(',', '', number_format($info['total_price'],2)));
			
			# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
			/*
			if(strlen($proximo_pago)<4) $order=sprintf("%04s", $proximo_pago).'pr';
			else $order=$proximo_pago.'pr';
			*/
			
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
			
			echo '<input type="hidden" name="Ds_Merchant_Amount" value="'.$amount_recibido.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Currency" value="'.$currency.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Order"  value="'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$code.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Terminal" value="'.$terminal.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$transactionType.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$urlMerchant.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_ProductDescription" value="Recarga saldo prepago">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_Titular" value="'.$user_desc.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$this->config->item('tpv_url_ok').'/'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$this->config->item('tpv_url_ko').'/'.$order.'">'."\r\n";
			echo '<input type="hidden" name="Ds_Merchant_MerchantName" value="'.substr($this->config->item('club_name'), 0, 25).'">'."\r\n";
			$message = $amount_recibido.$order.$code.$currency.$transactionType.$urlMerchant.$clave;
			//$message = $amount.$order.$code.$currency.$clave;
			//$signature = strtolower(sha1($message));			
			$signature = sha1($message);			
			echo '<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$signature.'">'."\r\n";
			
			echo form_close();
			
			echo '<script type="text/javascript">'."\r\n";
			echo 'function pagar_tpv() {'."\r\n";
			echo 'if($(\'input[name="amount"]\').val()!="" && $(\'input[name="amount"]\').val()!= "0.00") { alert(\'Debe especificar una cantidad\'); return; }'."\r\n";
			echo '$(\'input[name="Ds_Merchant_Amount"]\').val($(\'input[name="amount"]\').val());'."\r\n";
			//echo 'alert("'.$message.'");'."\r\n";
			echo "vent=window.open('','tpv','width=700,height=650,scrollbars=no,resizable=yes,status=yes,menubar=no,location=no');"."\r\n";
			echo "document.getElementById('frmPay').submit();"."\r\n";
			echo '}'."\r\n";
			echo '</script>'."\r\n";
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