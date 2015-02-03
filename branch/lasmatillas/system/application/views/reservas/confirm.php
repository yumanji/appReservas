<?php
	$this->lang->load('reservas');
	isset($this->CI) || $this->CI =& get_instance();
	
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);

	echo '<input type="hidden" name="id_transaction" value="'.$id_transaction.'">'."\r\n";
	

	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';


	if(isset($pay) && $pay==1 && $pay_content!="") {
		# Pinto los elementos de formas de pago... Llamaré a una vista que lo pinte según config.. a la cual le pasaré id de transacción, descripcion y coste
		echo '<br>'.$this->lang->line('payment_ways').'<br>'.$pay_content;
		
		if($methods['tpv']) {
			
			$this->CI->config->load('pagos');
			$this->CI->config->load('pago_'.$this->config->item('tpv_library_prefix'));

  		$attributes = array('class' => 'frmPay', 'id' => 'frmPay', 'method' => 'post', 'target' => 'tpv');			
  		echo form_open($this->config->item('tpv_payment_url'), $attributes);
			
			$amount=str_replace('.', '', str_replace(',', '', number_format($info['total_price'],2)));
			
			# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
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
		
		
		
		

	# Función que envía la petición del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";
	echo "  if(document.getElementById('user_desc').value=='' || document.getElementById('user_phone').value=='' ) {"."\r\n";
	echo "    alert('".$this->lang->line('user_info_required')."');"."\r\n";
	echo '  } else {'."\r\n";
	//echo "    document.getElementById('".$form."').action='".site_url('reservas/pay')."/'+method+'/".$this->session->userdata('session_id')."';"."\r\n";
	echo "    var direccion3 = '".site_url('reservas/pay2')."/'+method+'/".$id_transaction."/".time()."';"."\r\n";
	echo '  if(method == "tpv") {pagar_tpv();}'."\r\n";
	?>
				$(function() {

							//document.write("url:"+direccion3);
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
	echo '  }'."\r\n";
	echo '}'."\r\n";
	echo '</script>'."\r\n";		
		
		
		
	}
	
	
	
###################
# Boton de confirmación de reserva
##################	
		//echo '</td></tr><tr><td valign="top">';
		$js = 'id="buttonConfirma2" ';
		echo form_button('buttonConfirma2', 'Pagar la reserva!', $js);
		//echo '</td></tr></table>'; 					

	
# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.

	//echo form_close();

?>
<script type="text/javascript">
$(function() {
	$("#buttonConfirma2")
		.click( function() {
				
			//var direccion2 =<?php echo "'".site_url('reservas/confirm2/'.time())."/'+document.getElementById('numLight').value+'/'+document.getElementById('allow_light').checked";?>;
			var ok = 1;
			//alert($("#id_user").val()+'-'+$("#user_desc").val()+'-'+$("#user_phone").val())
			if( $("#id_user").val() == '' && ($("#user_desc").val() == '' || $("#user_phone").val() == '') ) {
				ok = 0;
				alert('<?php echo $this->lang->line('user_info_required'); ?>');
			}
			if(ok == 1) {
	
				var direccion2 =<?php echo "'".site_url('reservas/confirm2/'.time().'/'.$id_transaction);?>';
				//alert(direccion2);
				//return;
				//alert( $( "#accordion" ).accordion( "option", "animated" ));
				$("#accordion").accordion({ animated: 'slide' });
				$("#search_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
				$("#accordion").accordion("activate" , 3);
				$.ajax({
					
				  type: 'POST',
				  url: direccion2,
				  data: {id_user: $("#id_user").val(), user_desc: $("#user_desc").val(), user_phone: $("#user_phone").val(), allow_light: $("#allow_light").attr('checked'), no_cost: $("#no_cost").attr('checked'), no_cost_desc: $("#no_cost_desc").val(), id_transaction: $("#id_transaction").val() }, 
				  success: function(data) {
				  	//alert(data);
				    $("#search_extra").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda o confirmar la seleccion.</p>  </div> </div>');
				    //alert('Load was performed');
				    //$("#accordion").accordion("activate" , 3);
				    $("#search_payment").html(data);
				  }
				});
				direccion2 =<?php echo "'".site_url('reservas/extras')."'";?>; // Reseteo variable
	
			
			}
			
			
		})
	});
</script>
