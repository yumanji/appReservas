<?php
	$this->lang->load('reservas');
	
	$attributes = array('class' => $form, 'id' => $form);
	echo form_open(site_url(''), $attributes);


	echo '<p>'.$this->lang->line('confirmation_advice').$this->app_common->IntervalToTime($info['intervals']).' (<b>'.$info['inicio'].'-'.$info['fin'].'</b>) '.$this->lang->line('confirmation_court').' <b>\''.$info['court'].'\'</b>. '.$this->lang->line('confirmation_total').'<b>'.$info['total_price'].$this->lang->line('currency').'</b>.</p>';
	if($info['light']) echo '<p>'.$this->lang->line('confirmation_light_extra').'('.$info['light_price'].$this->lang->line('currency').')</p>'."\r\n"; 
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
		echo form_checkbox($data,'','', $js).'&nbsp;&nbsp;'.$this->lang->line('confirmation_no_cost');
		$data = array(
      'name'        => 'no_cost_desc',
      'id'          => 'no_cost_desc',
      'value'       => '',
      'maxlength'   => '255',
      'size'        => '50'
    );
		echo '<div id="motivo" style="display:none;">'.$this->lang->line('confirmation_no_cost_desc').': '.form_input($data).'</div>';
		
	}



	if(isset($user_data) && $user_data==1) {
		# Solicito los datos del usuario que hace reserva anónima
		
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
		echo '<br>'.$this->lang->line('confirmation_extra_user_data');
		echo '<br>'.$this->lang->line('user_name').': '.form_input($data);
		echo '<br>'.$this->lang->line('user_phone').': '.form_input($data2).'<br>';
		

	}
	if(isset($pay) && $pay==1 && $pay_content!="") {
		# Pinto los elementos de formas de pago... Llamaré a una vista que lo pinte según config.. a la cual le pasaré id de transacción, descripcion y coste
		echo '<br>'.$this->lang->line('payment_ways').'<br>'.$pay_content;
		

	}
# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.

	echo form_close();




	# Función que envía la petición del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";
	echo '  var $ok=1;'."\r\n";
	//echo "    if(document.getElementById('no_cost').checked) alert(' marcado');"."\r\n";
	//echo "    if(document.getElementById('motivo').value=='' || document.getElementById('motivo').value==undefined) alert('vacio');"."\r\n";
	//echo "    return;"."\r\n";
	echo "  	var no_cost_desc = document.getElementById('no_cost_desc').value;"."\r\n";
	echo "  	if(no_cost_desc == '') no_cost_desc = 'null';"."\r\n";

	echo "  if(document.getElementById('no_cost').checked && (document.getElementById('no_cost_desc').value==''  || document.getElementById('no_cost_desc').value==undefined)) {"."\r\n";
	echo '    $ok=0;'."\r\n";
	echo "    alert('".$this->lang->line('no_cost_reason_required')."');"."\r\n";
	echo '  } '."\r\n";
		echo '  if($ok==1) {'."\r\n";
	echo "    var direccion3 = '".site_url('reservas/pay')."/'+method+'/".$transaction_id."/'+document.getElementById('no_cost').checked+'/'+no_cost_desc+'/".$info['user']."/null/null/".time()."';"."\r\n";
	echo "     document.getElementById('".$form."').action = direccion3; "."\r\n";
	echo "     document.getElementById('".$form."').submit(); "."\r\n";


	//echo "    document.getElementById('".$form."').submit();"."\r\n";
	echo '  }'."\r\n";
	echo '}'."\r\n";
	

		
	echo '</script>'."\r\n";
	?>
