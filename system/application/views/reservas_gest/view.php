<style type="text/css">
.Carteles {
	font-family: Verdana, Geneva, sans-serif;
	color: #004D71;
    font-size: 12px;
    font-weight: bold;
}
.Letra_Pequeña {

    font-family: Arial, Helvetica, sans-serif;

    font-size: 10px;

    font-weight: normal;

    color: #FFFFFF;

}

.Letra_Pequeña2 {

    font-family: Arial, Helvetica, sans-serif;

    font-size: 10px;

    font-weight: normal;

    color: #004D71;

}

.Letra_Pequeña3 {

    font-family: Arial, Helvetica, sans-serif;

    font-size: 10px;

    font-weight: normal;

    color: #999999;

}

.Letra_Negra {

    font-family: Arial, Helvetica, sans-serif;

    font-size: 14px;

    font-weight:normal;

    color: #000000;

}
.Cabecera_Tablas{
    font-size: 12;
    color: #39396D;
    font-weight: bold;
}
.Lineas_Tablas{
    font-size: 12;
    color: #FFFFFF;
    font-weight: bold;
}   
.Letra_Media {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
    font-weight: normal;
    color: #004D71;
}

.Letra_Granate {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight:bold;
    color: #6F0207;
}

.Letra_Mediana2 {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: normal;
    color: #004D71;
}
.Letra_Mediana {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: normal;
    color:#666666;
}

</style>

<form class="form_user" name="formReserva" id="formReserva" method="post" action="">
 	<input name="id_transaction" type="hidden" value="<?php echo $info['id_transaction'];?>"/>
 	<input name="action" id="action" type="hidden" value=""/>
	<table width="560" border="0" cellspacing="4" cellpadding="0" align="center" bordercolor="#000000" style="border:solid; border-color:#EEEEEE; border-width:1px;">
		<tbody>
		<tr>
			<td class="Cabecera_Tablas" colspan="3" align="center"><?php echo $info['operation_desc']; ?><br>&nbsp;</td>
		</tr>																			
		<tr>
			<td class="Cabecera_Tablas" align="center">&nbsp;Precio: <span class="Letra_Granate" style="font-size:14px;"><?php echo $info['total_price'];  ?> &euro;</span></td>
			<td class="Cabecera_Tablas" align="center">&nbsp;Estado: <span class="Letra_Granate" style="font-size:12px;"><?php echo ucfirst($info['status_desc']); ?></span></td>
			<td class="Cabecera_Tablas" align="center">&nbsp;</td>
		</tr>																			
		<tr>
			<td colspan="3" class="Cabecera_Tablas" align="center">&nbsp;Usuario: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['user_desc']; if($info['user_phone'] !='') echo ' ('.$info['user_phone'].')'; ?></span></td>
		</tr>																			
		<tr>
			<td colspan="3" align="center"><span class="Letra_Granate" style="font-size:9px;">&nbsp;Reservador en: <?php echo $info['create_time']; ?></span></td>
		</tr>																			
		<tr>
			<td colspan="2" align="center">
				<div id="jason30-561" style="background-color:#000011;display:none" fade="1"></div>
			</td>
		</tr>
		</tbody>
	</table>
	<br><br>	

<br><br>	
        		
    <?php
    	if(isset($info['playing_users']) && count($info['playing_users']) > 0){
    ?>
		<table width="560" bgcolor="#F0F0F0" border="0" align="center" style="border:solid; border-color:#CCCCCC; border-width:1px; vertical-align:top;">
        <tbody><tr>
            <td colspan="6" class="Carteles" align="center">Jugadores participantes (<?php echo $info['players']; ?>)</td>
        </tr>
        <tr>
            <td colspan="6" class="Letra_Pequeña" align="center">&nbsp;</td>
        </tr>				
    <tr>
        <td width="7%">&nbsp;</td>
        <td align="center"><b>Nombre</b></td>
        <td align="center"><b>Tel&eacute;fono</b></td>
        <td width="7%">&nbsp;</td>
        <td width="7%">&nbsp;</td>
    </tr>
    <?php
    	//$this->CI =& get_instance();
    		$cont = 0;
	    	foreach($info['playing_users'] as $usuario) {
				
	    		
		    ?>
		    <tr>
		        <td>&nbsp;</td>											
		        <td class="Cabecera_Tablas" align="center"><?php if($usuario['id_user']!=0) echo img('images/pagador.png').'&nbsp;&nbsp;'; echo $usuario['user_desc']; ?></td>
		        <td class="Letra_Mediana" align="center"><?php echo $usuario['user_phone']; ?></td>
		        <td><?php $image_properties = array(
          'src' => 'images/close.png',
          'alt' => 'Borrar jugador del partido',
          'width' => '16',
          'class' => 'delete_player',
          'height' => '16',
          'title' => 'Borrar jugador del partido',
          'id' => $usuario['id']); if($cont>0) echo img($image_properties);  ?></td>
		        <td>&nbsp;</td>
		    </tr>
		    <?php
		    	$cont++;
	    	}
	    ?>
						
                                <tr>
        <td colspan="6" class="Letra_Pequeña" align="center">&nbsp;</td>
    </tr>
</tbody></table>
<?php
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
	  

		$img_prop = array(
          'src' => 'images/jugadores.png',
          'alt' => 'A&ntilde;adir jugador del partido',
          'width' => '16',
          'class' => 'add_player',
          'height' => '16',
          'title' => 'A&ntilde;adir jugador del partido',
          'id' => 'add_player');

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
		<table width="560" bgcolor="#F0F0F0" border="0" align="center" style="border:solid; border-color:#CCCCCC; border-width:1px; vertical-align:top;">
        <tbody>
        <tr>
            <td colspan="6" class="Carteles" align="center">A&ntilde;adir jugadores</td>
        </tr>
        
				<?php
					echo '<tr><td >'.$this->lang->line('confirmation_multiuser_desc').' : </td><td align="left"><input type="hidden" id="id_user" name="id_user">'.form_input($search_user).'</td><td></td><td align="left">A&ntilde;adir '.img($img_prop).'</td></tr>'."\r\n";
					echo '<tr><td>'.$this->lang->line('user_name').' : </td><td align="left">'.form_input($data3).'</td>'."\r\n";
					echo '<td align="right">'.$this->lang->line('user_phone').' : </td><td align="left">'.form_input($data2).'</td></tr>'."\r\n";

				?>
        </tr>				

</tbody></table>
<?php 
	} // Fin del IF de comprobacion de si hay jugadores
	//print("<pre>");print_r($info); 
?>
   <pre><?php //print_r($info); ?></pre>     		
   
    <br clear="all" />
    
<?php 
	//print("<pre>");print_r($info); 
?>
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">

			<script type="text/javascript">
				$(function() {
					$('.delete_player')
					.click(function() {
						//alert('a'+$(this).attr('id'));
						if(confirm('Esta seguro que desea eliminar este jugador del partido?')) location.href = '<?php echo site_url('reservas_gest/detail/'.$info['id_transaction'].'/delete'); ?>/'+$(this).attr('id');
					});
					
					
					$('#add_player')
					.click(function() {
						ok = 1;
						if( $("#id_user").val() == '' && ($("#user_desc").val() == '' || $("#user_phone").val() == '') ) {
							ok = 0;
							alert('<?php echo 'Si quiere agregar un usuario debe o buscarlo o, si no es socio, rellenar sus datos.'; ?>');
						}
						if(ok == 1) {
							if(confirm('Esta seguro de querer agregar este jugador al partido?')){
								$('#formReserva').get(0).setAttribute('action', '<?php echo site_url('reservas_gest/detail/'.$info['id_transaction'].'/add'); ?>');
								$('#formReserva').submit();
							}
						}
					});					
				});
			</script>
			
			
		
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		

		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('reservas_gest/list_all'); ?>';
		});	});
</script>
