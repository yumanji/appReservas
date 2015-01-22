<?php
	$this->lang->load('reservas');
	$this->CI =& get_instance();
?>
	<script type="text/javascript">
	$(function() {
		$("#accordion").accordion({
			autoHeight: false,
			navigation: true
		});
	$("#accordion").accordion("activate" , 2);
	});
	</script>	

<?php 
	echo '<table border="0">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.img( array('src'=>'images/target.png', 'border'=>'0', 'alt' => 'Reservas', 'align'=>'left')).'</td><td valign="middle">'.$this->lang->line('welcome').', '.$user_name.'. '.$this->lang->line('reserve_index_text')."\r\n";
	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'; 
	
	$attributes = array('id' => 'extraReserva');
	echo form_open(site_url('reservas'), $attributes);

	
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

<?php
	
	

	echo '<input type="hidden" name="id_transaction" value="'.$id_transaction.'">'."\r\n";
	

	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';




	####################################
	# Opciones de la reserva en cuestión
	####################################
	if(isset($options) && is_array($options)) {
		echo '<fieldset>'."\r\n";
		echo '<legend>Opciones</legend>'."\r\n";
		echo '<div>'."\r\n";
		echo '<table border="0" width="100%">'."\r\n";
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
	
	
	
	$ancho = 680;
	if(!isset($shared_booking) || $shared_booking!=1) $ancho = $ancho + 120;
	
	echo '<div style="float:left; width: '.$ancho.'px; height:120px;">';

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
		    'size'        => '30'
		  );
			$data2 = array(
		    'name'        => 'user_phone',
		    'id'          => 'user_phone',
		    'value'       => '',
		    'maxlength'   => '25',
		    'size'        => '30'
		  );
			echo '<input type="hidden" id="id_user" name="id_user" value="">'."\r\n";
			echo '<br>'.$this->lang->line('confirmation_extra_user_data');
			echo '<br>'.$this->lang->line('user_name').': '.form_input($data);
			echo '<br>'.$this->lang->line('user_phone').': '.form_input($data2).'<br>';
		}

		//echo '</div>';






	
	
	
	
	
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
		echo '</div><div style="float:left; width: 120px; height:60px; padding-top: 50px;">';
		$js = 'id="buttonConfirma2" style="height: 35px; width: 120px;" ';
		echo form_button('buttonConfirma2', 'Pagar la '."\r\n".'reserva!', $js);
		//echo '</td></tr></table>'; 					
		echo '</div>';
		
		if(isset($shared_booking) && $shared_booking==1) {
			echo '<div style="float:left; width: 100px; height:120px; padding-top: 50px;">';
			$js = 'id="buttonShared" style="height: 35px; width: 90px;" ';
			echo form_button('buttonShared', 'Crear '."\r\n".'reto!', $js);
			echo '</div>';
		}
			
# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.


?>
	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_selection');?></a></h3>
	<div id="search_payment">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes seleccionar pista, fecha y hora.</p>
      </div>
    </div>
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
?>

<script type="text/javascript">
$(function() {
	$("#buttonConfirma2")
		.click( function() {
				
			var ok = 1;
			if( $("#id_user").val() == '' && ($("#user_desc").val() == '' || $("#user_phone").val() == '') ) {
				ok = 0;
				alert('<?php echo $this->lang->line('user_info_required'); ?>');
			}
			if(ok == 1) {
	
				var direccion2 =<?php echo "'".site_url('reservas/confirm2/'.time().'/'.$id_transaction);?>';

	  		$("#extraReserva").attr("action", direccion2);
	  		$("#extraReserva").submit();
	
			}
		});
		
	$("#buttonShared")
		.click( function() {
			$("#extraReserva").attr("action", "<?php echo site_url('reservas/confirm_reto/'.time().'/'.$id_transaction);?>");	
			$("#extraReserva").submit();
		});
	});
</script>