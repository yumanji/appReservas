<form class="form_user" name="formReto" id="formReto" method="post">
 	<input name="id_transaction" type="hidden" value="<?php echo $info['id_transaction'];?>"/>
 	<input name="action" id="action" type="hidden" value=""/>
	<table width="560" border="0" cellspacing="4" cellpadding="0" align="center" bordercolor="#000000" style="border:solid; border-color:#EEEEEE; border-width:1px;">
		<tbody>
		<tr>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Fecha: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['fecha']; ?></span></td>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Hora: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['inicio'].' - '.$info['fin']; ?></span></td>
		</tr>																			
		<tr>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Deporte: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['sport']; ?></span></td>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Pista: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['court']; ?></span></td>
		</tr>																			
		<tr>
			<td colspan="4" align="center">
				<div id="jason30-561" style="background-color:#000011;display:none" fade="1"></div>
			</td>
		</tr>
		</tbody>
	</table>
	<br><br>	
  <pre><?php print_r($info); ?></pre>     		
	<table width="560" bgcolor="#F0F0F0" border="0" align="center" style="border:solid; border-color:#CCCCCC; border-width:1px; vertical-align:top;">
        <tbody><tr>
            <td colspan="4" class="Carteles" align="center">Datos de la persona que se apunta al partido</td>
        </tr>
        <tr>
            <td colspan="4" class="Letra_Peque�a2" align="center">Asegurese de que los datos que mostramos son correctos. Si detecta alg&uacute;n error puede modificar los datos en el apartado de 'Mi Perfil'.</td>
        </tr>
        <tr>
            <td colspan="4" class="Letra_Peque�a" align="center">&nbsp;</td>
        </tr>				
    <tr>
        <td width="7%">&nbsp;</td>
        <td class="Cabecera_Tablas" width="40%" align="left">Nombre: </td>
        <td width="56%" class="Letra_Mediana"><?php echo $profile->first_name.' '.$profile->last_name; ?></td>					
        <td width="7%">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">E-Mail: </td>
        <td class="Letra_Mediana"><?php echo $profile->email; ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Nivel de Juego: </td>
        <td class="Letra_Mediana"><?php echo $profile->player_level; ?></td>
        <td>&nbsp;</td>
    </tr>				
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Tel&eacute;fono: </td>
        <td class="Letra_Mediana"><?php echo $profile->phone; ?></td>
        <td>&nbsp;</td>
    </tr>
                                <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Total a Pagar: </td>
        <td class="Letra_Mediana"><?php echo $info['price_by_player']; ?> &euro;</td>
        <td>&nbsp;</td>
    </tr>							
                                <tr>
        <td colspan="4" class="Letra_Peque�a" align="center">&nbsp;</td>
    </tr>
</tbody></table>
<br><br>	
        		
	<table width="560" bgcolor="#F0F0F0" border="0" align="center" style="border:solid; border-color:#CCCCCC; border-width:1px; vertical-align:top;">
        <tbody><tr>
            <td colspan="6" class="Carteles" align="center">Jugadores inscritos al reto</td>
        </tr>
        <tr>
            <td colspan="6" class="Letra_Peque�a" align="center">&nbsp;</td>
        </tr>				
    <tr>
        <td width="7%">&nbsp;</td>
        <td align="center"><b>Nombre</b></td>
        <td align="center"><b>Email</b></td>
        <td align="center"><b>Tel&eacute;fono</b></td>
        <td align="center"><b>Nivel</b></td>
        <td width="7%">&nbsp;</td>
    </tr>
    <?php
    	isset($this->CI) || $this->CI =& get_instance();
    	foreach($info['signed_users'] as $usuario) {
    		$datos = $this->CI->usuario->get_user($usuario['id_user']);
    		
    		if($datos['user_name']!="") $nombre = $datos['user_name'];
				if($datos['user_lastname']!="") {
					if($nombre!="") $nombre.= ' ';
					$nombre .= $datos['user_lastname'];
				}
    		
    ?>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas" align="center"><?php echo $nombre; ?></td>
        <td class="Letra_Mediana" align="center"><?php echo $datos['user_email']; ?></td>
        <td class="Letra_Mediana" align="center"><?php echo $datos['user_phone'] ?></td>
        <td class="Letra_Mediana" align="center"><?php echo $datos['player_level'] ?></td>
        <td>&nbsp;</td>
    </tr>
    <?php
    	}
    ?>
						
                                <tr>
        <td colspan="6" class="Letra_Peque�a" align="center">&nbsp;</td>
    </tr>
</tbody></table>
    
    <br clear="all" />
    
<?php 
	//print("<pre>");print_r($info); 
?>
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
    	<?php
    		if($activo) {
    	?>
			<input type="button" id="cancelar_button" class="boton" value="Borrarme del partido"/>
			<script type="text/javascript">
				$(function() {
					$('#cancelar_button')
					.click(function() {
						location.href = '<?php echo site_url('retos/remove_player/'.$id_transaction.'/'.$profile->id); ?>';
					});
				});
			</script>
			
			<?php } else { ?>
			
			<input type="button" id="guardar_button" class="boton" value="Apuntarme al partido"/>	
			<script type="text/javascript">
				$(function() {
					$('#guardar_button')
					.click(function() {
						$('#action').val('suscribe');
						$('#formReto').submit();
					});
				});
			</script>
		<?php }  ?>
		
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		

		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url(); ?>';
		});	});
</script>
