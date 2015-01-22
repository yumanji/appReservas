<?php
	$this->lang->load('reservas');
	$this->CI =& get_instance();
	
	
	$attributes = array('id' => 'extraReserva');
	echo form_open(site_url('reservas'), $attributes);

	echo '<input type="hidden" name="id_transaction" value="'.$id_transaction.'">'."\r\n";
	

	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals'], $info['id_court']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';




	####################################
	# Opciones de la reserva en cuestión
	####################################
	if(isset($options) && is_array($options)) {
		echo '<fieldset>'."\r\n";
		echo '<legend>Opciones</legend>'."\r\n";
		echo '<div>'."\r\n";
		echo '<table border="0" width="100%" cellpadding=0 cellspacing=0>'."\r\n";
		echo '<tr>'."\r\n";
		//echo '<tr><td>jhdsjh</td></tr><tr><td>jhdsjh</td></tr><tr><td>jhdsjh</td></tr>'."\r\n";
		foreach($options as $name => $option) {
			echo '<td valign="baseline">'."\r\n";
			echo $option."\r\n";
			echo '</td>'."\r\n";
			echo ''."\r\n";
			
		}
		echo '</tr>'."\r\n";
		echo '</table>'."\r\n";
		echo '</div>'."\r\n";
		echo '</fieldset>'."\r\n";
	} 









	####################################
	# Control de la posibilidad de pagar sin coste
	####################################

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
	
	
	
	$ancho = 720;
	if(!isset($shared_booking) || $shared_booking!=1) $ancho = $ancho + 120;
	
	//echo '<div style="float:left; width: '.$ancho.'px; height:120px;">';
	echo '<div style="float:left; width: '.$ancho.'px; ">';

	####################################
	# Control de la selección de usuario
	####################################

	if(isset($multiuser) && $multiuser==1) {
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
	  

	  
	  
		//echo ''.$this->lang->line('confirmation_user_data_explanation')."\r\n";
		echo '<div id="multiuser_detail" border="1"><fieldset><legend>Destinatario de la reserva</legend>'."\r\n";
		echo '<table width="100%" border=0 cellspacing=0><tr>'."\r\n";
		echo '<td style="	border-right-width: thin; border-right-style: solid; border-right-color: #2d588b; ">'.$this->lang->line('confirmation_multiuser_desc').': <input type="hidden" id="id_user" name="id_user">'.form_input($search_user).'</td>'."\r\n";
		echo '<td>'.$this->lang->line('user_name').': </td><td align="left">'.form_input($data3).'</td>'."\r\n";
		echo '<td align="right">'.$this->lang->line('user_phone').': </td><td align="left">'.form_input($data2).'</td>'."\r\n";
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
		} elseif($logged_user > 0) {
			
			echo '<input type="hidden" id="id_user" name="id_user" value="'.$logged_user.'">'."\r\n";
			echo '<input type="hidden" id="user_desc" name="user_desc" value="">'."\r\n";
			echo '<input type="hidden" id="user_phone" name="user_phone" value="">'."\r\n";
			
		} else {
			#############################################
			# Solicito los datos del usuario que hace reserva anónima	
			#############################################
			
			$data = array(
		    'name'        => 'user_desc',
		    'id'          => 'user_desc',
		    'value'       => '',
		    'maxlength'   => '75',
		    'size'        => '40'
		  );
			$data2 = array(
		    'name'        => 'user_phone',
		    'id'          => 'user_phone',
		    'value'       => '',
		    'maxlength'   => '25',
		    'size'        => '25'
		  );
			echo '<input type="hidden" id="id_user" name="id_user" value="">'."\r\n";
			echo '<br>'.$this->lang->line('confirmation_extra_user_data');
			echo '<br>'.$this->lang->line('user_name').': '.form_input($data);
			echo '<br>'.$this->lang->line('user_phone').': '.form_input($data2).'<br>';
		}




## Seleccion de usuarios múltiples para ser jugadores

		//echo $record_players_number."\r\n";
	  if(isset($record_players_number) && $record_players_number > 1 ) {
	  	
					echo '<div id="multiuser_detail2" border="1" style="display:none"><fieldset><legend>Jugadores adicionales</legend>'."\r\n";
					echo '<table width="100%" border=0 cellspacing=5>'."\r\n";

	  	for($i=1; $i < $record_players_number; $i++) {
					echo '<tr>'."\r\n";
					$data = array(
				    'name'        => 'multiuser_'.$i,
				    'id'          => 'multiuser_'.$i,
				    'value'       => '1',
				    'checked'     => TRUE
			    );
					$js = '';
					//echo form_checkbox($data,'','', $js).'&nbsp;&nbsp;'.$this->lang->line('confirmation_multiuser');
			
					$hidden_user = array(
				    'name'        => 'id_user_'.$i,
				    'id'          => 'id_user_'.$i
			    );
			
					$search_user = array(
				    'name'        => 'usuarios_'.$i,
				    'id'          => 'usuarios_'.$i,
			    	'size'        => '20'	
			    );
			
					# Datos del usuario que hace reserva anónima
					$data3 = array(
				    'name'        => 'user_desc_'.$i,
				    'id'          => 'user_desc_'.$i,
				    'value'       => '',
				    'maxlength'   => '75',
				    'size'        => '20'
				  );
					$data2 = array(
				    'name'        => 'user_phone_'.$i,
				    'id'          => 'user_phone_'.$i,
				    'value'       => '',
				    'maxlength'   => '25',
				    'size'        => '20'
				  );
				  
				  $visible = 'none';
				  //if($i > 1) $visible = 'none';
					echo '<td><div id="multiuser_detail_'.$i.'" style = "display: '.$visible.'" > <table><tr>'."\r\n";
					  
					echo '<td style="	border-right-width: thin; border-right-style: solid; border-right-color: #2d588b;">'.$this->lang->line('confirmation_multiuser_desc').' '.$i.': <input type="hidden" id="id_user_'.$i.'" name="id_user_'.$i.'">'.form_input($search_user).'</td>'."\r\n";
					echo '<td>'.$this->lang->line('user_name').' '.$i.': </td><td align="left">'.form_input($data3).'</td>'."\r\n";
					echo '<td align="right">'.$this->lang->line('user_phone').' '.$i.': </td><td align="left">'.form_input($data2).'</td>'."\r\n";
					echo '</tr></table>'."\r\n";
					  
					echo '</td></tr>'."\r\n";
					  
				  
				  
	  	} // Fin del FOR
					echo '</table>'."\r\n";
					echo '</fieldset>'."\r\n";
					echo '</div>'."\r\n";
	  }

?>

		<script>
		var available_prepaid_amount= 0;
		$(function() {
			
			$( "#usuarios_1" ).autocomplete({
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
						suggestions.push({id:val.id, value:val.value});
					});
	
					//pass array to callback
					add(suggestions);
				});
			},
				minLength: 2,
				select: function( event, ui ) {
					$("#id_user_1").val(ui.item.id);

	
					//alert(document.getElementById("id_user").value);
				}
			});
			
			
			$( "#usuarios_2" ).autocomplete({
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
						suggestions.push({id:val.id, value:val.value});
					});
	
					//pass array to callback
					add(suggestions);
				});
			},
				minLength: 2,
				select: function( event, ui ) {
					$("#id_user_2").val(ui.item.id);

	
					//alert(document.getElementById("id_user").value);
				}
			});
			
			
			$( "#usuarios_3" ).autocomplete({
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
						suggestions.push({id:val.id, value:val.value});
					});
	
					//pass array to callback
					add(suggestions);
				});
			},
				minLength: 2,
				select: function( event, ui ) {
					$("#id_user_3").val(ui.item.id);

	
					//alert(document.getElementById("id_user").value);
				}
			});
			
			
		});
		</script>
<?php
## Fin de seleccion de jugadores




		echo '</div>';






	
	
	
	
	
	/*
	echo $this->lang->line('confirmation_detail_intro');
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
		
	
	
###################
# Boton de confirmación de reserva
##################	
		//echo '</td></tr><tr><td valign="top">';
		echo '</div><div style="float:left; width: 100px; height:120px; padding-top: 20px;">';
		$js = 'id="buttonConfirma2" style="height: 35px; width: 90px;" ';
		echo form_button('buttonConfirma2', 'Pagar la reserva!', $js);
		//echo '</td></tr></table>'; 					
		echo '</div>';
		
		if(isset($shared_booking) && $shared_booking==1) {
			echo '<div style="float:left; width: 100px; height:120px; padding-top: 20px;">';
			$js = 'id="buttonShared" style="height: 35px; width: 90px;" ';
			echo form_button('buttonShared', 'Crear '."\r\n".'reto!', $js);
			echo '</div>';
		}
			
# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.

	echo form_close();

?>
<script type="text/javascript">
$(function() {
	$("#buttonConfirma2")
		.click( function() {
				
			var ok = 1;
			var jugadores = $("#number_players").val();
			if( $("#id_user").val() == '' && ($("#user_desc").val() == '' || $("#user_phone").val() == '') ) {
				ok = 0;
				alert('<?php echo $this->lang->line('user_info_required'); ?>');
			}
			
			for(i=1; i < jugadores; i ++) {
				if( $("#id_user_"+i).val() == '' && ($("#user_desc_"+i).val() == '' || $("#user_phone_"+i).val() == '') ) {
					ok = 0;
					alert('Debe rellenar los nombres de todos los jugadores');
					break;
				}				
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
	<?php
	  if(isset($record_players_number) && $record_players_number > 1 ) {	
	  	
	  	echo '				  data: {id_user: $("#id_user").val(), user_desc: $("#user_desc").val(), user_phone: $("#user_phone").val(), allow_light: $("#allow_light").attr("checked"), no_cost: $("#no_cost").attr("checked"), no_cost_desc: $("#no_cost_desc").val(), id_transaction: $("#id_transaction").val()'; 

	  	for($i=1; $i < $record_players_number; $i++) {
	  		
	  		echo ',id_user_'.$i.': $("#id_user_'.$i.'").val(), user_desc_'.$i.': $("#user_desc_'.$i.'").val(), user_phone_'.$i.': $("#user_phone_'.$i.'").val()';
	  		
			}
		
		echo ' },';
		} else {
	?>
				  data: {id_user: $("#id_user").val(), user_desc: $("#user_desc").val(), user_phone: $("#user_phone").val(), allow_light: $("#allow_light").attr('checked'), no_cost: $("#no_cost").attr('checked'), no_cost_desc: $("#no_cost_desc").val(), id_transaction: $("#id_transaction").val() }, 
		<?php
		}
	?>			  
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
			
			
		});
		
	$("#buttonShared")
		.click( function() {
			$("#extraReserva").attr("action", "<?php echo site_url('reservas/confirm_reto/'.time().'/'.$id_transaction);?>");	
			$("#extraReserva").submit();
		});
	});
</script>