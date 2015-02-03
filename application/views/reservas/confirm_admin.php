<?php
	$this->lang->load('reservas');
	$this->CI =& get_instance();



	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);


	//echo '<fieldset>';
	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';
	if($info['light']) echo '<p>'.$this->lang->line('confirmation_light_extra').'('.$info['light_price'].$this->lang->line('currency').')</p>'."\r\n"; 
	//echo '</fieldset>';
	//echo $this->lang->line('confirmation_detail_intro');
	/*
	echo '<fieldset>';
	echo '<legend>'.$this->lang->line('confirmation_reserved_for').' '.date($this->config->item('reserve_date_filter_format'),strtotime($info['date'])).'</legend>';
	print('<ul id="pistas">'."\r\n");
	foreach($info['reserva'] as $pista => $datos) {
		print('<li id="pista">'.$pista."\r\n");
		print('<ul id="intervalos">'."\r\n");
		foreach($datos as $dato) {
			print('<li id="intervalo">De '.$dato[0].' a '.$dato[1].' ('.$dato[2].$this->lang->line('currency').')'."\r\n");			
		}
		if($info['light']) print('<li>Suplemento luz ('.$info['light_price'].$this->lang->line('currency').')'."\r\n");
		print('</ul>'."\r\n");
		print('</li>'."\r\n");
	}
	print('</ul>'."\r\n");
	echo '</fieldset>';
	*/

# Opciones de la reserva en cuestión
	if(isset($options) && is_array($options)) {
		echo '<fieldset>'."\r\n";
		echo '<legend>Opciones</legend>'."\r\n";
		echo '<table border="0" width="100%">'."\r\n";
		echo '<tr>'."\r\n";
		foreach($options as $name => $option) {
			echo '<td>'."\r\n";
			echo $option."\r\n";
			echo '</td>'."\r\n";
			echo ''."\r\n";
			
		}
		echo '</tr>'."\r\n";
		echo '</table>'."\r\n";
		echo '</fieldset>'."\r\n";
	}



	if(isset($no_cost) && $no_cost==1) {
		echo "<br>";
		$data = array(
	    'name'        => 'no_cost',
	    'id'          => 'no_cost',
	    'value'       => '1',
	    'checked'     => FALSE
    );
    
    # Al pinchar en no_cost debemos desactivar todos los posibles botones menos el de pago al contado, que será el que permita registrar ese pago.
		$js = 'onClick="if(document.getElementById(\'no_cost\').checked) document.getElementById(\'motivo\').style.display=\'block\'; else document.getElementById(\'motivo\').style.display=\'none\';"';
		echo form_checkbox($data,'','', $js).'&nbsp;&nbsp;'.$this->lang->line('confirmation_no_cost')."\r\n";
		$data = array(
      'name'        => 'no_cost_desc',
      'id'          => 'no_cost_desc',
      'value'       => '',
      'maxlength'   => '255',
      'size'        => '50'
    );
		echo '<div id="motivo" style="display:none;">'.$this->lang->line('confirmation_no_cost_desc').': '.form_input($data).'</div>'."\r\n";
		
	} else {
		$data = array(
	    'name'        => 'no_cost',
	    'id'          => 'no_cost',
	    'value'       => '1',
	    'checked'     => FALSE
    );
    
		echo form_checkbox($data,'','', 'style="display: none;"')."\r\n";
		$data = array(
      'name'        => 'no_cost_desc',
      'id'          => 'no_cost_desc',
      'value'       => '',
      'style' => 'display: none;'
    );
		echo ''.form_input($data).''."\r\n";
		
	}

	if(isset($multiuser)) {
		echo "<br>";
		$data = array(
	    'name'        => 'multiuser',
	    'id'          => 'multiuser',
	    'value'       => '1',
	    'checked'     => TRUE
    );
		$js = '';
		//echo form_checkbox($data,'','', $js).'&nbsp;&nbsp;'.$this->lang->line('confirmation_multiuser');

		$hidden_user = array(
	    'name'        => 'id_user',
	    'id'          => 'id_user'
    );

		$search_user = array(
	    'name'        => 'usuarios',
	    'id'          => 'usuarios',
    	'size'        => '20'	
    );

		# Datos del usuario que hace reserva anónima
		$data3 = array(
	    'name'        => 'user_desc',
	    'id'          => 'user_desc',
	    'value'       => '',
	    'maxlength'   => '75',
	    'size'        => '20'
	  );
		$data2 = array(
	    'name'        => 'user_phone',
	    'id'          => 'user_phone',
	    'value'       => '',
	    'maxlength'   => '25',
	    'size'        => '20'
	  );
		echo ''.$this->lang->line('confirmation_user_data_explanation')."\r\n";
		echo '<div id="multiuser_detail" border="1"><fieldset><legend>Destinatario de la reserva</legend>'."\r\n";
		echo '<table width="100%" border=0 cellspacing=5><tr>'."\r\n";
		echo '<td style="	border-right-width: thin; border-right-style: solid; border-right-color: #2d588b; ">'.$this->lang->line('confirmation_multiuser_desc').': <input type="hidden" id="id_user" name="id_user">'.form_input($search_user).'</td>'."\r\n";
		echo '<td><table width="100%" border=0 cellspacing=0 cellpadding=0><tr><td align="right">'.$this->lang->line('user_name').': </td><td align="left">'.form_input($data3).'</td></tr>'."\r\n";
		echo '<tr><td align="right">'.$this->lang->line('user_phone').': </td><td align="left">'.form_input($data2).'</td></tr></table></td>'."\r\n";
		echo '</tr></table>'."\r\n";
		echo '</fieldset>'."\r\n";
			echo '</div>'."\r\n";
			?>
			
		<script>
		var available_prepaid_amount= 0;
		$(function() {
			$( "#usuarios" ).autocomplete({
				source: function(req, add){
					//var parametros = req.split("=");
					//dumpProps(req);
					//alert(req.term);
					//pass request to server
					$.getJSON("<?php echo site_url('users/get_Names'); ?>/"+ req.term, function(data) {
	
						//create array for response objects
						var suggestions = [];
	
						//process response
						$.each(data, function(i, val){
							//{ data:val.id, value:val.name, result:val.name };
						suggestions.push({id:val.id, value:val.value});
					});
	
					//pass array to callback
					add(suggestions);
				});
			},
				minLength: 2,
				select: function( event, ui ) {
					$("#id_user").val(ui.item.id);
					$.get("<?php echo site_url('users/getPrepaidCash/'); ?>/"+ ui.item.id, function(data) {
						available_prepaid_amount = data;
					});
	
					//alert(document.getElementById("id_user").value);
				}
			});
		});
		</script>


		<?php			
		} else {
			echo "<br>".$this->lang->line('confirmation_allow_reserve')."<br>";
			
		}


	if(isset($pay) && $pay==1 && $pay_content!="") {
		# Pinto los elementos de formas de pago... Llamaré a una vista que lo pinte según config.. a la cual le pasaré id de transacción, descripcion y coste
		echo '<br>'.$this->lang->line('payment_ways').'<br>'.$pay_content;
		//echo "BB";print_r($methods);
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

	}

# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.

	//echo form_close();




	# Función que envía la petición del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";
	echo '  var $ok=1;'."\r\n";
	echo '  if(!(document.getElementById("no_cost").checked) && method == "prepaid" && available_prepaid_amount < '.$info['total_price'].') {alert("Saldo insuficiente en bono monedero."); $ok=0;}'."\r\n";
	echo '  if(!(document.getElementById("no_cost").checked) && method == "tpv") {pagar_tpv();}'."\r\n";
	//echo "    if(document.getElementById('no_cost').checked) alert(' marcado');"."\r\n";
	//echo "    if(document.getElementById('motivo').value=='' || document.getElementById('motivo').value==undefined) alert('vacio');"."\r\n";
	//echo "    return;"."\r\n";
	echo "  if(document.getElementById('no_cost').checked && (document.getElementById('no_cost_desc').value==''  || document.getElementById('no_cost_desc').value==undefined)) {"."\r\n";
	echo '    $ok=0;'."\r\n";
	echo "    alert('".$this->lang->line('no_cost_reason_required')."');"."\r\n";
	echo '  } '."\r\n";
	echo "  if( document.getElementById('id_user').value=='' && (document.getElementById('user_desc').value=='' || document.getElementById('user_phone').value=='') ) {"."\r\n";
	echo '    $ok=0;'."\r\n";
	echo "    alert('".$this->lang->line('multiuser_value_required')."');"."\r\n";
	echo '  } '."\r\n";
	echo '  if($ok==1) {'."\r\n";
	echo "  	var no_cost_desc = document.getElementById('no_cost_desc').value;"."\r\n";
	echo "  	var id_user = document.getElementById('id_user').value;"."\r\n";
	echo "  	var user_desc = document.getElementById('user_desc').value;"."\r\n";
	echo "  	var user_phone = document.getElementById('user_phone').value;"."\r\n";
	echo "  	if(id_user == '') id_user = 'null';"."\r\n";
	echo "  	if(user_desc == '') user_desc = 'null';"."\r\n";
	echo "  	if(user_phone == '') user_phone = 'null';"."\r\n";
	echo "  	if(no_cost_desc == '') no_cost_desc = 'null';"."\r\n";
	echo "    var direccion3 = '".site_url('reservas/pay2')."/'+method+'/".$transaction_id."/'+document.getElementById('no_cost').checked+'/'+no_cost_desc+'/'+id_user+'/'+user_desc+'/'+user_phone+'/".time()."';"."\r\n";
	?>
			
				$(function() {
					// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!

							//alert("url:"+direccion3);
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
							<?php 	echo "  direccion3 = '".site_url('reservas/pay2')."/'+method+'/".$transaction_id."/'+document.getElementById('no_cost').checked+'/'+no_cost_desc+'/'+id_user+'/'+user_desc+'/'+user_phone+'/".time()."';"."\r\n"; ?>
							
							
					});	
	<?php
	//echo "    document.getElementById('".$form."').submit();"."\r\n";
	echo '  }'."\r\n";
	echo '}'."\r\n";
	

		
	echo '</script>'."\r\n";
	?>
