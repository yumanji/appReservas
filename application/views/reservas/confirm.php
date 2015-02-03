<?php
	$this->lang->load('reservas');
	$this->CI =& get_instance();
	
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);

	echo '<input type="hidden" name="id_transaction" value="'.$id_transaction.'">'."\r\n";
	

	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';


	if(isset($pay) && $pay==1 && $pay_content!="") {
		# Pinto los elementos de formas de pago... Llamaré a una vista que lo pinte según config.. a la cual le pasaré id de transacción, descripcion y coste
		echo '<br>'.$this->lang->line('payment_ways').'<br>'.$pay_content;
		
		if($methods['tpv']) {
			
			$this->CI->config->load('pagos');
			$this->CI->config->load('pago_'.$this->config->item('tpv_library_prefix'));
			$this->load->helper('tpv');
			$amount=str_replace('.', '', str_replace(',', '', number_format($info['total_price'],2)));
			
			# Tamaño minimo de los caracteres numéricos = 4 .. más un sufijo para identificar el registro que estoy pagando
			if(strlen($info['id'])<4) $order=sprintf("%04s", $info['id']).'re';
			else $order=$info['id'].'re';
			

			
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
