<?php
if($enable_add) {
?>
<script type="text/javascript">
	$(function() {
		$( "#add_user" ).autocomplete({
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

				//alert(document.getElementById("id_user").value);
			}
		});
		
		$( "#btnAdd,#btnAdd2" ).click(function() { 
			if($("#id_user").val() != "" || ($("#user_desc").val() && $("#user_phone").val())) {
				$("#frmDetail").attr("action", "<?php echo site_url('lessons/add_assistant/'.$info->id.'/subscribe'); ?>");
	
				$("#frmDetail").submit();
			}
			return false;
			});		

		
		$( ".btnPay" ).click(function() { 
			$(document.location).attr("href", "<?php echo site_url('lessons/asistant_payment/'); ?>/"+$(this).attr('id'));
			return false;
			});	
		
		// Si tocan el campo de autorelleno, borro el campo oculto con el valor .. por seguridad.
		$('#add_user').keyup(function() {
		  	$('#id_user').val('');
		});			
						
	});
	
				
	</script>


<?php

} // Fin del IF



	$this->lang->load('lessons');


	
	echo ''."\r\n";
	echo '<fieldset>'."\r\n";
	echo '<legend>Asistentes</legend>'."\r\n";
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="5" style="font-size:11px;">'."\r\n";
	if(count($asistentes)>0) {
		foreach($asistentes as $id => $datos) {
			echo '<tr>'."\r\n";
			echo '<td>'."\r\n";

			if(!isset($datos['last_day_payed']) || $datos['last_day_payed']=="") {
				$image_properties = array(
		    	'src' => 'images/exclamation.png',
		      //'width' => '48',
		      'align' => 'absmiddle',
		      'class' => 'btnNoPayd',
		      'id' => $datos['id'],
		      //'id' => 'btnInfo',
		      'style' => 'cursor: pointer; cursor: hand;',
		      'title' => 'El usuario no ha pagado a&uacute;n ninguna cuota',
				);
		
				echo img($image_properties)."\r\n";
			} elseif (isset($datos['last_day_payed']) && (date($this->config->item('date_db_format'), strtotime($datos['last_day_payed'].' -'.$this->config->item('payment_advice_prev_days').'  days')) < date($this->config->item('date_db_format')))) {
				$image_properties = array(
		    	'src' => 'images/aviso.png',
		      //'width' => '48',
		      'align' => 'absmiddle',
		      'class' => 'btnNoPayd',
		      'id' => $datos['id'],
		      //'id' => 'btnInfo',
		      'style' => 'cursor: pointer; cursor: hand;',
		      'title' => 'El usuario ha pagado hasta el '.date($this->config->item('reserve_date_filter_format'), strtotime($datos['last_day_payed'])),
				);
		
				echo img($image_properties)."\r\n";
				
			}	
			
					
			$image_properties = array(
	    	'src' => 'images/user_delete.png',
	      //'width' => '48',
	      'align' => 'absmiddle',
	      'id' => 'btnErase',
	      'onClick' => 'javascript: if(confirm(\'Va a dar de baja al usuario del curso. Est&aacute; seguro?\')) {$(\'#frmDetail\').attr(\'action\', \''.site_url('lessons/unsubscribe_assistant/'.$info->id.'/'.$datos['id']).'\'); $(\'#frmDetail\').submit();}',
	      'style' => 'cursor: pointer; cursor: hand;',
	      'title' => 'Dar de baja al usuario',
			);
	
			echo img($image_properties)."\r\n";
			
			
			$image_properties = array(
	    	'src' => 'images/information.png',
	      //'width' => '48',
	      'align' => 'absmiddle',
	      'class' => 'btnInfo',
	      'id' => $datos['id'],
	      //'id' => 'btnInfo',
	      'style' => 'cursor: pointer; cursor: hand;',
	      'title' => 'Informacion del usuario',
			);
	
			echo img($image_properties)."\r\n";

			
			$image_properties = array(
	    	'src' => 'images/coins.png',
	      //'width' => '48',
	      'align' => 'absmiddle',
	      'class' => 'btnPay',
	      'id' => $datos['id'],
	      //'id' => 'btnInfo',
	      'style' => 'cursor: pointer; cursor: hand;',
	      'title' => 'Pago del usuario',
			);
	
			echo img($image_properties)."\r\n";
			
			echo ' - '.$datos['user_desc'].' ('.$datos['user_phone'].')'."\r\n";
			echo '</td>'."\r\n";
			echo '</tr>'."\r\n";
		}
	} else {
			echo '<tr>'."\r\n";
			echo '<td>'."\r\n";
			echo 'No hay usuarios suscritos';
			echo '</td>'."\r\n";
			echo '</tr>'."\r\n";
	}
	echo '</table>'."\r\n";
	echo '</fieldset>'."\r\n";

	if($enable_add) {
		$search_user = array(
	    'name'        => 'add_user',
	    'id'          => 'add_user',
	  	'size'        => '20'	
	  );
		$user_desc = array(
	    'name'        => 'user_desc',
	    'id'          => 'user_desc',
	  	'size'        => '20',	
	  	'MAXLENGTH'        => '75'	
	  );
		$user_phone = array(
	    'name'        => 'user_phone',
	    'id'          => 'user_phone',
	  	'size'        => '20',	
	  	'MAXLENGTH'        => '15'	
	  );
		$image_properties = array(
	  	'src' => 'images/add.png',
	    //'width' => '48',
	    'align' => 'absmiddle',
	    'id' => 'btnAdd',
	    'style' => 'cursor: pointer; cursor: hand;',
	    'title' => 'A&ntilde;adir usuario',
		);
		$image_properties2 = array(
	  	'src' => 'images/add.png',
	    //'width' => '48',
	    'align' => 'absmiddle',
	    'id' => 'btnAdd2',
	    'style' => 'cursor: pointer; cursor: hand;',
	    'title' => 'A&ntilde;adir usuario',
		);
	
		echo '<p style="font-size: 11px;"><b>A&ntilde;adir usuario registrado</b><br>Buscar:&nbsp;&nbsp;&nbsp;<input type="hidden" id="id_user" name="id_user">'.form_input($search_user).' '.img($image_properties)."\r\n";
		echo '<br><b>A&ntilde;adir an&oacute;nimo</b><br>Nombre: '.form_input($user_desc).' '.img($image_properties2).'<br>Telefono:'.form_input($user_phone).' '.'</p>'."\r\n";
	}	// Fin del IF
?>
<script type="text/javascript">
			$('.btnInfo').tooltip({ 
			    bodyHandler: function() { 
			        //return $($(this).attr("href")).html(); 
			        var html = $.ajax({
												  url: "<?php echo site_url('lessons/tooltip_assistant_info'); ?>/"+$(this).attr('id'),
												  async: false
												 }).responseText;

			        return html;
			    }, 
			    delay: 0,
			    track: true,
					showURL: false 
			});	
</script>