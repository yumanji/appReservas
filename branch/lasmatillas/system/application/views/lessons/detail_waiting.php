	<script>
	$(function() {
		$( "#add_user2" ).autocomplete({
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
				$("#id_user2").val(ui.item.id);

				//alert(document.getElementById("id_user").value);
			}
		});
		
		$("#btnAddWait,#btnAddWait2").click(function() {
			if($("#id_user2").val() != "" || ($("#user_desc2").val() && $("#user_phone2").val())) {
				$("#frmDetail").attr("action", "<?php echo site_url('lessons/add_assistant/'.$info->id.'/waiting'); ?>");
				$("#frmDetail").submit();
			}
			return false;
			});				

		// Si tocan el campo de autorelleno, borro el campo oculto con el valor .. por seguridad.
		$('#add_user2').keyup(function() {
		  	$('#id_user2').val('');
		});			

	});
	</script>
<?php
		$this->lang->load('lessons');


	
	echo ''."\r\n";
	echo '<fieldset>'."\r\n";
	echo '<legend>Lista espera</legend>'."\r\n";
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="5" style="font-size:11px;">'."\r\n";
	if(count($espera)>0) {
		foreach($espera as $id => $datos) {
			echo '<tr>'."\r\n";
			echo '<td>'."\r\n";
			
			$image_properties = array(
	    	'src' => 'images/add.png',
	      //'width' => '48',
	      'align' => 'absmiddle',
	      'id' => 'btnSubscribe',
	      'onClick' => 'javascript: if(confirm(\'Va a dar de alta al usuario. Est&aacute; seguro?\')) {$(\'#frmDetail\').attr(\'action\', \''.site_url('lessons/subscribe_assistant/'.$datos['id']).'/subscribe\'); $(\'#frmDetail\').submit();}',
	      'style' => 'cursor: pointer; cursor: hand;',
	      'title' => 'A&ntilde;adir usuario',
			);
			if(!$enable_add) $image_properties['onClick'] = 'javascript: alert(\'El curso est&aacute; actualmente lleno.\')';
			echo img($image_properties)."\r\n";
	
			$image_properties = array(
	    	'src' => 'images/close.png',
	      //'width' => '48',
	      'align' => 'absmiddle',
	      'id' => 'btnErase',
	      'onClick' => 'javascript: if(confirm(\'Va a dar de baja al usuario del curso. Est&aacute; seguro?\')) {$(\'#frmDetail\').attr(\'action\', \''.site_url('lessons/unsubscribe_assistant/'.$info->id.'/'.$datos['id']).'\'); $(\'#frmDetail\').submit();}',
	      'style' => 'cursor: pointer; cursor: hand;',
	      'title' => 'Eliminar usuario',
			);
	
			echo img($image_properties)."\r\n";

			/*
			$image_properties = array(
	    	'src' => 'images/user.png',
	      //'width' => '48',
	      'align' => 'absmiddle',
	      'id' => 'btnInfo',
	      'style' => 'cursor: pointer; cursor: hand;',
	      'title' => 'Informacion del usuario',
			);
	
			echo img($image_properties)."\r\n";
			*/
			
			echo ' - '.$datos['user_desc'].' ('.$datos['user_phone'].')'."\r\n";
			echo '</td>'."\r\n";
			echo '</tr>'."\r\n";
		}
	} else {
			echo '<tr>'."\r\n";
			echo '<td>'."\r\n";
			echo 'No hay usuarios en espera';
			echo '</td>'."\r\n";
			echo '</tr>'."\r\n";
	}
	echo '</table>'."\r\n";
	echo '</fieldset>'."\r\n";

		$search_user = array(
	    'name'        => 'add_user',
	    'id'          => 'add_user2',
	  	'size'        => '20'	
	  );
		$user_desc = array(
	    'name'        => 'user_desc2',
	    'id'          => 'user_desc2',
	  	'size'        => '20',	
	  	'MAXLENGTH'        => '75'	
	  );
		$user_phone = array(
	    'name'        => 'user_phone2',
	    'id'          => 'user_phone2',
	  	'size'        => '20',	
	  	'MAXLENGTH'        => '15'	
	  );
		$image_properties = array(
	  	'src' => 'images/add.png',
	    //'width' => '48',
	    'align' => 'absmiddle',
	    'id' => 'btnAddWait',
	    'style' => 'cursor: pointer; cursor: hand;',
	    'title' => 'A&ntilde;adir usuario',
		);
		$image_properties2 = array(
	  	'src' => 'images/add.png',
	    //'width' => '48',
	    'align' => 'absmiddle',
	    'id' => 'btnAddWait2',
	    'style' => 'cursor: pointer; cursor: hand;',
	    'title' => 'A&ntilde;adir usuario',
		);
	
		echo '<p style="font-size: 11px;"><b>A&ntilde;adir usuario registrado</b><br>Buscar:&nbsp;&nbsp;&nbsp;<input type="hidden" id="id_user2" name="id_user2">'.form_input($search_user).' '.img($image_properties)."\r\n";
		echo '<br><b>A&ntilde;adir an&oacute;nimo</b><br>Nombre: '.form_input($user_desc).' '.img($image_properties2).'<br>Telefono:'.form_input($user_phone).' '.'</p>'."\r\n";

?>