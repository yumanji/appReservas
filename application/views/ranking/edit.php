<?php 
 //print("<pre>");print_r($info); print("</pre>");
?>
<?php
//$this->lang->load('lessons');

$disabled = '';
if($info['started'] == '1') $disabled = 'disabled';
?>
  <form class="form_user" name="formRanking" id="formRanking" method="post" action="<?php echo current_url(); ?>">
  	<input name="id" id="id" type="hidden" value="<?php echo $info['id'];?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
  	<input name="id_round" id="id_round" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
 			<?php if($info['started'] == '1') { ?><tr><td colspan="2" align="center"><span style="color: red; ">El <?php echo $this->lang->line('ranking_name'); ?> no puede ser modificado una vez ha sido iniciado.</span></td></tr><?php } ?>
     <tr>
        <td width="470" valign="top">
        	<label><span>Nombre*</span>
              <input name="description" type="text" id="description" <?php echo $disabled; ?> value="<?php echo $info['description']; ?>" size="25" />
          </label>
            <label><span>Fecha inicio*</span>
            <input type="text" name="start_date" id="start_date" <?php echo $disabled; ?> value="<?php echo $info['inicio']; ?>" size="10" />
            </label>
            <label><span>Fecha fin*</span>
            <input type="text" name="end_date" id="end_date" <?php echo $disabled; ?> value="<?php echo $info['final']; ?>" size="10" />
            </label>
            <label><span>N&ordm; grupos*</span>
						<input type="text" name="groups" id="groups" <?php echo $disabled; ?> size="2" value="<?php echo $info['groups']; ?>" alt="integer" />
          	</label>
            <label><span>Equipos / Grupo*</span>
						<input type="text" name="teams" id="teams" <?php echo $disabled; ?> size="2" value="<?php echo $info['teams']; ?>" alt="integer" />
          	</label>
            <label><span>Usuarios / Equipo*</span>
						<input type="text" name="team_mates" id="team_mates" <?php echo $disabled; ?> size="2" value="<?php echo $info['team_mates']; ?>" alt="integer" />
          	</label>
            <label><span>Activo</span>
            <input type="checkbox" name="active" id="active" <?php echo $disabled; ?> value="1" <?php if($info['active']) echo 'checked'; ?>/>
            </label>
				</td>
        <td width="476" >
            <label><span>Deporte</span>
							<select name="sport" id="sport" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($deportes	 as $code => $deporte)
									{
										if($info['sport']==$code) echo '<option value="'.$code.'" selected>'.$deporte.'</option>';
										else echo '<option value="'.$code.'">'.$deporte.'</option>';
									}
								?>
							</select>
            </label>
						<label><span>G&eacute;nero</span>
							<select name="gender" id="gender" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($generos	 as $code => $genero)
									{
										if($info['gender']==$code) echo '<option value="'.$code.'" selected>'.$genero.'</option>';
										else echo '<option value="'.$code.'">'.$genero.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Tipo promocion*</span>
							<select name="promotion_type" id="promotion_type" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($promociones	 as $code => $promocion)
									{
										if($info['promotion_type']==$code) echo '<option value="'.$code.'" selected>'.$promocion.'</option>';
										else echo '<option value="'.$code.'">'.$promocion.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Tarifa*</span>
							<select name="price" id="price" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($tarifas	 as $code => $tarifa)
									{
										if($info['price']==$code) echo '<option value="'.$code.'" selected>'.$tarifa.'</option>';
										else echo '<option value="'.$code.'">'.$tarifa.'</option>';
									}
								?>
							</select>
						</label>
        		<label> <span>Cuota alta</span>
              <input type="text" name="signin" id="signin" <?php echo $disabled; ?> value="<?php echo $info['signin']; ?>" size="10"  alt="dinero" />
          	</label>
						<label><span>Tipo promocion*</span>
							<select name="promotion_type" id="promotion_type" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($promociones	 as $code => $promocion)
									{
										if($info['promotion_type']==$code) echo '<option value="'.$code.'" selected>'.$promocion.'</option>';
										else echo '<option value="'.$code.'">'.$promocion.'</option>';
									}
								?>
							</select>
						</label>
            <label><span>Sets*</span>
						<input type="text" name="score_parts" id="score_parts" <?php echo $disabled; ?> size="2" value="<?php echo $info['score_parts']; ?>" alt="integer" />
          	</label>
            <label><span>Duracion partidos</span>
						<input type="text" name="match_duration" id="match_duration" <?php echo $disabled; ?> size="2" value="<?php echo $info['match_duration']; ?>" alt="integer" /> (en fragmentos de 30 minutos)
          	</label>

				</td>
      </tr>
      <?php if($info['started'] != '1') { ?>
      <tr>
      	<td colspan="2">
      		<center>Para definir la longitud de cada jornada, rellene uno de los siguientes campos</center>
      	</td>
      </tr>
      <?php } ?>
      <tr>
      	<td>
            <label><span>N&ordm; rondas*</span>
						<input type="text" name="rounds" id="rounds" <?php echo $disabled; ?> size="2" value="<?php echo $info['rounds']; ?>" alt="integer" />
          	</label>
      	</td>
      	<td>
            <label><span>Duraci&oacute;n ronda*</span>
						<input type="text" name="round_duration" id="round_duration" <?php echo $disabled; ?> size="2" value="<?php echo $info['round_duration']; ?>" alt="integer" />
          	</label>
      		
      	</td>
      </tr>
<?php if($info['started'] == '1') { ?>
      <tr>
      	<td colspan="2" align="center">
      		<?php
      			echo '<label>Rondas</label>'."\r\n";
      			echo '<table border="1" cellpadding="5" style="border: 1px solid #B9B8AB; border-collapse: collapse;">'."\r\n";
      			echo '<tr>'."\r\n";      		
      			echo '<td align="center"><b>N&ordm;</b></td><td align="center"><b>Comienzo</b></td><td align="center"><b>Fin</b></td><td align="center"><b>Acciones</b></td>'."\r\n";
      			$i = 1; $iniciado = 0;
      			foreach($info['rondas'] as $ronda) {
	       			echo '<tr>'."\r\n";      		
	      			echo '<td align="center">'.$ronda['round'].'</td><td align="center">'.$ronda['fecha_inicio'].'</td><td align="center">'.$ronda['fecha_fin'].'</td>'."\r\n";
	      			echo '<td>'."\r\n";
	      			if($ronda['started']=='1' && $ronda['finished']=='0') $iniciado = 1;
        			if($ronda['current']=='1') echo '&nbsp;'.img(array('src' => 'images/flag_green.png', 'id' => $ronda['id'], 'alt' => 'Jornada actual', 'class' => 'current_round',  'width' => '16', 'height' => '16', 'title' => 'Jornada actual'))."\r\n";
        			elseif($ronda['started']=='0') {
        				//echo '--'.$info['rondas'][intval($i-2)]['started'].'<br>--'.$info['rondas'][intval($i-2)]['finished'].'<br>';
        				if((isset($info['rondas'][intval($i-2)]) && $info['rondas'][intval($i-2)]['started']=='1' && $info['rondas'][intval($i-2)]['finished']=='1') || $i == 1) echo '&nbsp;'.img(array('src' => 'images/control_play.png', 'id' => $ronda['id'], 'alt' => 'Iniciar jornada', 'class' => 'start_round',  'width' => '16', 'height' => '16', 'title' => 'Iniciar jornada'))."\r\n";
        				else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));
        			}
        			if($ronda['finished']=='0' && $ronda['started']=='1') echo '&nbsp;'.img(array('src' => 'images/lock.png', 'id' => $ronda['id'], 'alt' => 'Cerrar jornada', 'class' => 'close_round',  'width' => '16', 'height' => '16', 'title' => 'Cerrar jornada'))."\r\n";
        			else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));

        				//if($i != 1) echo '&nbsp;'.img(array('src' => 'images/up-arrow2.gif', 'alt' => 'Subir de grupo', 'class' => 'up_team', 'width' => '16', 'height' => '16', 'title' => 'Subir de grupo'));
        				//else echo '&nbsp;'.img(array('src' => 'images/spacer.png', 'alt' => '', 'class' => 'ranking_teams_action', 'id' => '', 'width' => '16', 'height' => '16', 'title' => ''));

	      			echo '</td>'."\r\n";
	      			echo '</tr>'."\r\n";
	      			$i++;
      			}    		
      			echo '</tr>'."\r\n";
      			echo '</table>'."\r\n";
      		?>
      	</td>
      </tr>
      <?php } ?>
      
    </table>
    
    <br clear="all" />
    
    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<?php if($info['started'] == '0') { ?><input type="button" id="guardar_button" class="boton" value="Guardar"/><?php } ?>
			<?php if($info['started'] == '0') { ?><input type="button" id="start_button" class="boton" value="Iniciar"/><?php } ?>
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

  <?php
		//print_r($this->session->userdata);
		echo "	<script type=\"text/javascript\">"."\r\n";
		echo "	$(function() {
		
							var dates = $( \"#start_date, #end_date\" ).datepicker({
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

		echo '	$( "#start_date" ).datepicker( "option", "defaultDate", "'.$info['inicio'].'" );'."\r\n";
		echo '	$( "#end_date" ).datepicker( "option", "defaultDate", "'.$info['final'].'" );'."\r\n";
		echo "	</script>"."\r\n";
?>
<script type="text/javascript">
	$(function() {

		//Gestion de la duracion de las rondas		
		$('#rounds').change(function() {
  		$('#round_duration').val('0');
		});		
		
		$('#round_duration').change(function() {
  		$('#rounds').val('0');
		});
		
		// Boton para iniciar una jornada
		$('.start_round')
		.css('cursor', 'pointer')
		.click(function() {
  		//alert($(this).attr('id'));
  			$('#id_round').val($(this).attr('id'));
  			$('#action').val('start_round');
				$('#formRanking').submit();

		});
		
		// Boton para cerrar una jornada
		$('.close_round')
		.css('cursor', 'pointer')
		.click(function() {
  			$('#id_round').val($(this).attr('id'));
  			$('#action').val('end_round');
				$('#formRanking').submit();
		});
		
		
		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'}
		$('input:text').setMask();
		
		$('#guardar_button')
		.click(function() {
			
			if($("#price").val() != '' && $("#groups").val() != '' && $("#teams").val() != '' && $("#team_mates").val() != '' && $("#promotion_type").val() != '' && $("#gender").val() != '' && $("#sport").val() != '' && $("#description").val() != '' && $("#start_date").val() != ''  && $("#end_date").val() != '' && ($("#end_date").val() >= $("#start_date").val()) && ( $("#rounds").val() != '' || $("#round_duration").val() != '' )  ) {
				$('#action').val('save');
				$('#formRanking').submit();
			} else if($("#end_date").val() < $("#start_date").val()) alert('La fecha de finalizacion debe ser posterior a la de inicio.');
				else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		
		$('#start_button')
		.click(function() {
			
			if($("#price").val() != '' && $("#groups").val() != '' && $("#teams").val() != '' && $("#team_mates").val() != '' && $("#promotion_type").val() != '' && $("#gender").val() != '' && $("#sport").val() != '' && $("#description").val() != '' && $("#start_date").val() != ''  && $("#end_date").val() != ''  && ($("#end_date").val() >= $("#start_date").val())  && ( $("#rounds").val() != '' || $("#round_duration").val() != '' ) ) {
				$('#action').val('start');
				$('#formRanking').submit();
			}	else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		

		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('ranking'); ?>';
		});	
		
		});
</script>
