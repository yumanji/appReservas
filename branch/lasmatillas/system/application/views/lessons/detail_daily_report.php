<?php 
// print("<pre>");print_r($info); 
$this->lang->load('lessons');
//print("<pre>");print_r($info);
?>
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
<form class="form_user" name="formCurso" id="formCurso" method="post">
<input name="id_transaction" type="hidden" value="<?php echo $info['id'];?>"/>
<input name="action" id="action" type="hidden" value=""/>
<input name="returnUrl" id="returnUrl" type="hidden" value="<?php echo site_url(); ?>"/>
<table width="560" border="0" cellspacing="4" cellpadding="0" align="center" bordercolor="#000000" style="border:solid; border-color:#EEEEEE; border-width:1px;">
		<tbody>
		<tr>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Curso: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['description']; ?></span></td>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Plazas: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['plazas']; ?></span></td>
		</tr>																			
		<tr>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Fechas: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['rango_fechas']; ?></span></td>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Horario: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['rango_horas']; ?></span></td>
		</tr>																			
		<tr>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Deporte: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['sport_desc']; ?></span></td>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Pista: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['court_desc']; ?></span></td>
		</tr>																			
		<tr>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;D&iacute;a semana: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['dia_semana']; ?></span></td>
			<td class="Cabecera_Tablas" style="font-size:11px;color:#044364;padding-top:12px;">&nbsp;Profesor: <span class="Letra_Granate" style="font-size:12px;"><?php echo $info['instructor_desc']; ?></span></td>
		</tr>																			
		</tbody>
</table>
<br><br>	
        		
	<table width="560" bgcolor="#F0F0F0" border="0" align="center" style="border:solid; border-color:#CCCCCC; border-width:1px; vertical-align:top;">
        <tbody>
        <tr>
            <td colspan="4" class="Carteles" align="center">Parte diario de clase</td>
        </tr>
        <tr>
            <td colspan="4" class="Letra_Pequeña2" align="center">Asegurese de completar todos los datos solicitados. Tras introducirlos, deber&aacute;n ser validados por un supervisor.</td>
        </tr>
        <tr>
            <td colspan="4" class="Letra_Pequeña" align="center">&nbsp;</td>
        </tr>				
    <tr>
        <td width="7%">&nbsp;</td>
        <td class="Cabecera_Tablas" width="40%" align="left">Fecha* : </td>
        <td width="56%" class="Letra_Mediana"><?php echo $info['date_lesson']; ?>
        </td>					
        <td width="7%">&nbsp;</td>
    </tr>
    <tr>
        <td width="7%">&nbsp;</td>
        <td class="Cabecera_Tablas" width="40%" align="left">Profesor* : </td>
        <td width="56%" class="Letra_Mediana">
        	<?php if(!$editable || (isset($recovery) && $recovery)) echo $info['instructor_desc'].'<input type="hidden" name="id_instructor" value="'.$info['id_instructor'].'">'; else { ?>
						<select name="id_instructor" id="id_instructor" class="Letra_Mediana">
							<?php 
							//pinto combo de niveles
								$seleccionar = "";
								//print_r($fechas_disponibles);
								foreach($profesores	 as $code => $desc)
								{
									if($code == $info['id_instructor']) echo '<option value="'.$code.'" selected>'.$desc.'</option>';
									else echo '<option value="'.$code.'">'.$desc.'</option>';
								}
							?>
						</select>
					<?php } ?>
        </td>					
        <td width="7%">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Clase impartida: </td>
        <td class="Letra_Mediana"><?php if(!$editable || (isset($recovery) && $recovery)) echo '<input type="hidden" name="done" value="'.$info['done'].'">';?><input type="checkbox" name="done<?php if(!$editable || (isset($recovery) && $recovery)) echo '_disabled';?>" value="1" <?php if(stristr($info['done'], 's')) echo 'checked';?> <?php if(!$editable || (isset($recovery) && $recovery)) echo 'disabled';?>></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Observaciones* : </td>
        <td class="Letra_Mediana"><?php if(!$editable || (isset($recovery) && $recovery)) echo $info['observations'].'<input type="hidden" name="observations" value="'.$info['observations'].'">'; else echo form_textarea(array('name' => 'observations', 'id' => 'observations', 'maxlength' => 255, 'value' => $info['observations'], 'rows' => '6', 'cols' => '50', 'class' => 'Letra_Mediana')).'<br><span class="charsRemaining">Quedan 255 caracteres.<br>'; ?></td>
        <td>&nbsp;</td>
    </tr>
    <?php
    	if(isset($info['recovered']) && $info['recovered']=="1") {
    ?>				
    <tr>
        <td colspan="4" class="Letra_Pequeña" align="center">&nbsp;</td>
    </tr>				
    <tr>
        <td colspan="4" class="Carteles" align="center">Clase recuperada</td>
    </tr>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Fecha: </td>
        <td class="Letra_Mediana"><?php echo date($this->config->item('reserve_date_filter_format'), strtotime($info['recovered_date'])); ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas">Observaciones* : </td>
        <td class="Letra_Mediana"><?php echo $info['recovered_obs']; ?></td>
        <td>&nbsp;</td>
    </tr>
  <?php
		//print_r($this->session->userdata);
		echo "	<script type=\"text/javascript\">"."\r\n";
		echo "	$(function() {
		
							var dates = $( \"#recovered_date\" ).datepicker({
								showOn: 'button',
								buttonImage: '".base_url()."/images/calendar.gif',
								buttonImageOnly: true,
								changeMonth: true,
								numberOfMonths: 2,
								dateFormat: 'dd-mm-yy',
								dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
								monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
								firstDay: 1,
								
								onSelect: function( selectedDate ) {
									var option = this.id == \"start_date\" ? \"minDate\" : \"maxDate\",
										instance = $( this ).data( \"datepicker\" );
										date = $.datepicker.parseDate(
											instance.settings.dateFormat ||
											$.datepicker._defaults.dateFormat,
											selectedDate, instance.settings );
									dates.not( this ).datepicker( \"option\", option, date );
								}
										}
								
								);
						});"."\r\n";

		echo "	</script>"."\r\n";
		?>
    <?php
    	}
    ?>
    <tr>
        <td colspan="4" class="Letra_Pequeña" align="center">&nbsp;</td>
    </tr>				
    <tr>
        <td colspan="4" class="Carteles" align="center">Asistentes</td>
    </tr>
    <tr>
        <td colspan="4" class="Letra_Pequeña" align="center">&nbsp;</td>
    </tr>				
<?php 
	foreach($info['assistants'] as $codigo => $asistente) {
?>
    <tr>
        <td>&nbsp;</td>											
        <td class="Cabecera_Tablas"><input type="hidden" name="<?php echo 'user_'.$asistente['id'];?>" value="<?php echo $asistente['id'];?>"><input type="checkbox" name="<?php echo 'assistant_'.$asistente['id'];?>" <?php if($asistente['asistance']=='1') echo 'checked';?> value="1"  <?php if(!$editable) echo 'disabled';?>>&nbsp;&nbsp;<?php echo $asistente['user_desc'];?></td>
        <td class="Letra_Mediana">Obs: <?php if(!$editable) echo $asistente['observations']; else echo form_input(array('name' => 'obs_'.$asistente['id'], 'id' => 'obs_'.$asistente['id'], 'maxlength' => 255, 'value' => $asistente['observations'], 'size' => '30', 'class' => 'Letra_Mediana')); ?></td>
        <td>&nbsp;</td>
    </tr>
<?php } ?>

                                <tr>
        <td colspan="4" class="Letra_Pequeña" align="center">&nbsp;</td>
    </tr>
</tbody>
</table>


<script type="text/javascript">
	$(function() {
		
$(document).ready(function(){
	$('textarea[maxlength]').keyup(function(){
		var max = parseInt($(this).attr('maxlength'));
		if($(this).val().length > max){
			$(this).val($(this).val().substr(0, $(this).attr('maxlength')));
		}

		$(this).parent().find('.charsRemaining2').html('Quedan ' + (max - $(this).val().length) + ' caracteres.');
	});
});
		
		});
</script>    
    <br clear="all" />



    <br clear="all" />
      <?php if($editable) { ?><p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p><?php } ?>
    <div class="separador">
			<?php if($editable) { ?><input type="button" id="save_button" class="boton" value="Guardar"/><?php } ?>
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		

		
		$('#save_button')
		.click(function() {
			
			if($("#date_lesson").val() != '' && $("#observations").val() != '' && $("#id_instructor").val() != '') {
				$('#action').val('save');
				$("#formCurso").attr("action", "<?php echo site_url('lessons/detail_daily_report/'.$info['id']); ?>");
				$('#formCurso').submit();
			}	else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		

		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('lessons/assistance/'.$info['id_lesson']); ?>';
		});	
		
		});
</script>
</form>
