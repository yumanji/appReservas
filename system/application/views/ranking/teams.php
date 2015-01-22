<?php 
// print("<pre>");print_r($info); print("</pre>");
?>
<?php
//$this->lang->load('lessons');

?>
  <form class="form_user" name="formRanking" id="formRanking" method="post" action="<?php echo current_url(); ?>">
  	<input name="action" id="action" type="hidden" value=""/>
    <input type="hidden" id="selected_team" name="selected_team">
    <table width="100%" border="0" cellspacing="5" class="nota">
      <tr>
        <td width="200" height="1" valign="top"></td>
        <td valign="top" height="1"></td>
        <td width="200" height="1" valign="top"></td>
     </tr>
      <tr>
        <td width="200" valign="top">
        <?php
					$options = array();
					foreach($info['rondas'] as $ronda) {
						$options[$ronda['id']] = 'Jornada '.$ronda['round'];
						//if($ronda['round']==$info['current_round']) break;
					}

        	$js = 'id="round_selector"';
					echo 'Seleccione jornada: '.form_dropdown('round', $options, $ronda_visualizar, $js);
        ?>	
        </td>
        <td valign="top" colspan="2" align="right"	>
        <?php
        	//echo '&nbsp;'.img(array('src' => 'images/2arrows.png', 'alt' => 'Reordenar equipos', 'class' => 'reordenar',  'width' => '26', 'height' => '26', 'title' => 'Reordenar equipos'));
        	if($permisos['new']) echo '&nbsp;&nbsp;'.img(array('src' => 'images/nuevo_usuario.png', 'alt' => 'A&ntilde;adir equipo', 'class' => 'alta_equipo',  'width' => '32', 'height' => '32', 'title' => 'A&ntilde;adir equipo'));
        ?>
        </td>
     </tr>
<?php
	$i = 1; $cont_equip = 1;
	foreach($resultado as $id_grupo => $grupo) {
?>
     <tr>
     	<?php if($i%2 == 0) echo '<td width="150" valign="top" align="center">'; ?>
        <td colspan = "2" valign="top" <?php if($i%2 == 0) echo 'align="left"'; else echo 'align="left"'; ?> >
        	<?php

        		echo '<p class="tituloListaGrupo">'.'Grupo '.$id_grupo.'</p><table class="EquiposGrupo">'."\r\n";
        		echo '<tr class="CabeceraEquipoGrupo"><td>Equipo</td><td>Usuario titular</td><td width="15px">PJ</td><td width="15px">PG</td><td width="15px">PP</td><td width="15px">SG</td><td width="15px">SE</td><td width="15px">SP</td><td width="15px">JG</td><td width="15px">JE</td><td width="15px">JP</td><td width="15px">Ptos</td>';
        		$nivel = 'admin';
        		if($nivel == 'admin' && $permisos) echo '<td width="25px" nowrap align="right">Acciones</td>';
        		echo '</tr>'."\r\n";
        		foreach($grupo as $id_equipo => $equipo) {
        			echo '<tr class="EquipoGrupo"><td>'.$equipo['description'].'</td>'."\r\n";
        			echo '<td>'.$equipo['main_user_description'];
        			if(isset($equipo['phone']) && trim($equipo['phone'])!='') echo ' ('.$equipo['phone'].')';
        			echo '</td>'."\r\n";
        			echo '<td>'.$equipo['PJ'].'</td>'."\r\n";
        			echo '<td>'.$equipo['PG'].'</td>'."\r\n";
        			echo '<td>'.$equipo['PP'].'</td>'."\r\n";
        			echo '<td>'.$equipo['SG'].'</td>'."\r\n";
        			echo '<td>'.$equipo['SE'].'</td>'."\r\n";
        			echo '<td>'.$equipo['SP'].'</td>'."\r\n";
        			echo '<td>'.$equipo['JG'].'</td>'."\r\n";
        			echo '<td>'.$equipo['JE'].'</td>'."\r\n";
        			echo '<td>'.$equipo['JP'].'</td>'."\r\n";
        			echo '<td>'.$equipo['puntos'].'</td>'."\r\n";
      				if($nivel == 'admin' && $permisos) echo '<td nowrap>';
        			if($nivel == 'admin' && $permisos && $equipo['id']!=0) {
        				if($permisos['detail']) echo '&nbsp;'.img(array('src' => 'images/group.png', 'id' => $equipo['id'], 'alt' => 'Ver equipo', 'class' => 'detalle_equipo',  'width' => '16', 'height' => '16', 'title' => 'Ver equipo'));
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));

        				if($permisos['unsubscribe']) echo '&nbsp;'.img(array('src' => 'images/close.png', 'id' => $equipo['id'], 'alt' => 'Dar de baja', 'class' => 'baja_equipo',  'width' => '16', 'height' => '16', 'title' => 'Dar de baja'));
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));

        				/*
        				if($permisos['notification']) echo '&nbsp;'.img(array('src' => 'images/email.png', 'alt' => 'Enviar mensaje a equipo', 'class' => 'notificar_equipo', 'width' => '16', 'height' => '16', 'title' => 'Enviar mensaje a equipo'));
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));

        				if($permisos['calendar']) echo '&nbsp;'.img(array('src' => 'images/calendar_view_day.png', 'alt' => 'Ver calendario', 'class' => 'calendario_equipo', 'width' => '16', 'height' => '16', 'title' => 'Ver calendario'));
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));
								*/
        				if($cont_equip != 1 && $permisos['up']) echo '&nbsp;'.img(array('src' => 'images/up-arrow2.gif', 'id' => $equipo['id'], 'alt' => 'Subir de grupo', 'class' => 'up_team', 'width' => '16', 'height' => '16', 'title' => 'Subir de grupo'));
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));

        				if($cont_equip < $info['max_vacancies'] && $permisos['down']) echo '&nbsp;'.img(array('src' => 'images/down-arrow2.gif', 'id' => $equipo['id'], 'alt' => 'Bajar de grupo', 'class' => 'down_team', 'width' => '16', 'height' => '16', 'title' => 'Bajar de grupo'));
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));
        			}
      				if($nivel == 'admin' && $permisos) echo '</td>';
        			echo '</tr>'."\r\n";
        			$cont_equip++;
        		}
        		echo '</table>'."\r\n";
        		echo ''."\r\n";
        	
        	?>
				</td>
     	<?php if($i%2 == 1) echo '<td width="150" valign="top">'; ?>
      </tr>
<?php
		$i++;

	} // Fin del grupo
?>

    </table>
    
    <br clear="all" />
    
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

<div id="dialog-message" title="Bot&oacute;n sin funci&oacute;n">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		Este bot&oacute;n no est&aacute; habilitado.
	</p>
</div>

<script type="text/javascript">
	$(function() {

		$('#round_selector').change(function() {
			$('#formRanking').submit();
		});
	
		$("#dialog-message").dialog({
			autoOpen: false,
			height: 125,
			width: 250,
			modal: true,
		});		

		$('.notificar_equipo,.calendario_equipo')
			.click(function() {
				$('#dialog-message').dialog('open');
			});		
			
		$('.baja_equipo')
			.click(function() {
			$('#action').val('unsubscribe_team');
			$('#selected_team').val($(this).attr('id'));
			$('#formRanking').submit();
			});		
			
		
		$('.down_team')
		.click(function() {
			$('#action').val('down_team');
			$('#selected_team').val($(this).attr('id'));
			$('#formRanking').submit();
		});	
		
		$('.up_team')
		.click(function() {
			$('#action').val('up_team');
			$('#selected_team').val($(this).attr('id'));
			$('#formRanking').submit();
		});	

		$('.detalle_equipo')
		.click(function() {
			location.href = '<?php echo site_url('ranking/team'); ?>/'+$(this).attr('id');
		});	

		$('.alta_equipo')
		.click(function() {
			location.href = '<?php echo site_url('ranking/new_team/'.$info['id']); ?>';
		});	

		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('ranking'); ?>';
		});	
		
		});
</script>
