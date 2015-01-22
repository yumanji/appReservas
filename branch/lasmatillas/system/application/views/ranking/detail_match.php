<?php 
 //print("<pre>");print_r($info); print("</pre>");
?>
<?php
//$this->lang->load('lessons');

$played = false;
if($partido['status'] >= 5) $played = true;
?>
  <form class="form_user" name="formRanking" id="formRanking" method="post" action="<?php echo current_url(); ?>">
  	<input name="id" id="id" type="hidden" value="<?php echo $partido['id'];?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <?php if(!$played) { ?>
	      <tr>
	        <td colspan="2" valign="top" align="center"><label>Grabar resultado del partido</label></td>
	      </tr>
			<?php } ?>
      <tr>
        <td width="470" valign="top">
        	<label><span>Equipo 1:</span> <?php echo $partido['equipo1']; ?></label>
          <?php if(!$played) { ?>
          <label><span>Fecha estimada:</span> <?php echo $partido['fecha_estimada']; ?></label>
					<?php } else { ?>
          <label><span>Jugado:</span> <?php echo $partido['fecha_jugado']; ?></label>
					<?php } ?>
				</td>
        <td width="476" >
        	<label><span>Equipo 2:</span> <?php echo $partido['equipo2']; ?></label>
        	<label><span>Estado:</span> <?php echo $partido['estado']; ?></label>
				</td>
      </tr>
<?php if($played) { ?>
      <tr>
      	<td colspan="2" align="center">
      		<center>Resultado</center><br/>
      		<table border="1" cellpadding="10" style="border-collapse: collapse;">
      			<tr>
      				<td>&nbsp;</td>
      				<?php  for($i=1; $i<=$info['score_parts']; $i++) echo '<td align="center"><b>'.$i.'</b></td>'."\r\n"; ?>
      			</tr>
      			<tr>
      				<td><table><tr><td><?php echo $partido['equipo1']; ?></td></tr><tr><td><?php echo $partido['equipo2']; ?></td></tr></table></td>
      				<?php  
      					foreach($partido['tanteo'] as $ronda) { 
      						echo '<td><table><tr><td>'.$ronda['team1'].'</td></tr><tr><td>'.$ronda['team2'].'</td></tr></table></td>'."\r\n";
      					} 
      				?>
      			</tr>
      		</table>
      		<br/>
      		<?php if($partido['status']=='6') { echo 'Partido perdido por lesi&oacute;n: '; if($partido['winner']==$partido['team1']) echo $partido['equipo2']; else echo $partido['equipo1']; } ?>
      		<?php if($partido['status']=='8') { echo 'Partido perdido por incomparecencia: '; if($partido['winner']==$partido['team1']) echo $partido['equipo2']; else echo $partido['equipo1']; } ?>
      	</td>
      </tr>
<?php } else { ?>
      <tr>
      	<td colspan="2" align="center">
      		<center>Resultado</center><br/>
      		<table border="0" cellpadding="3">
      			<tr>
      				<td>&nbsp;</td>
      				<?php  for($i=1; $i<=$info['score_parts']; $i++) echo '<td align="center"><b>'.$i.'</b></td>'."\r\n"; ?>
      			</tr>
      			<tr>
      				<td><?php echo $partido['equipo1']; ?></td>
      				<?php  for($i=1; $i<=$info['score_parts']; $i++) { echo '<td><input type="text" name="result_1_'.$i.'" class="resultado_set" size="2" value="" alt="integer" /></td>'."\r\n";} ?>
      			</tr>
      			<tr>
      				<td><?php echo $partido['equipo2']; ?></td>
      				<?php  for($i=1; $i<=$info['score_parts']; $i++) { echo '<td><input type="text" name="result_2_'.$i.'" class="resultado_set" size="2" value="" alt="integer" /></td>'."\r\n";} ?>
      			</tr>
      		</table>
      		<br/>
      		Fecha: &nbsp;&nbsp;<input type="textbox" name="played_date" id="played_date" value="<?php echo date($this->config->item('reserve_date_filter_format')); ?>" size="10"/>&nbsp;&nbsp;
      		<br/>
      		Lesi&oacute;n? &nbsp;&nbsp;<input type="checkbox" name="lesion" id="lesion" value="1"/>&nbsp;&nbsp;
      		<?php
							$options = array(
									''=>'',
	                $partido['team1']  => $partido['equipo1'],
	                $partido['team2']  => $partido['equipo2']
	              );
	            $js = 'id="lesionado" disabled';
							echo form_dropdown('lesionado', $options, '', $js);      		
      		?>
      		<br/>
      		No presentado? &nbsp;&nbsp;<input type="checkbox" name="ausencia" id="ausencia"  value="1"/>&nbsp;&nbsp;
      		<?php
							$options = array(
									''=>'',
	                $partido['team1']  => $partido['equipo1'],
	                $partido['team2']  => $partido['equipo2']
	              );
	            $js = 'id="ausente" disabled';
							echo form_dropdown('ausente', $options, '', $js);      		
      		?>
      		<br/>
      	</td>
      </tr>
<?php } ?>
    </table>
    
    <br clear="all" />
    
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
			<?php if(!$played) { ?><input type="button" id="guardar_button" class="boton" value="Guardar"/><?php } ?>
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

  <?php
		//print_r($this->session->userdata);
		echo "	<script type=\"text/javascript\">"."\r\n";
		echo "	$(function() {
		
							var dates = $( \"#played_date\" ).datepicker({
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
<script type="text/javascript">
	$(function() {

		$('#lesion').change(function() {
			if ($('#lesion').is (':checked')) {
  			$('#lesionado').removeAttr('disabled');
  			$('#ausencia').attr('checked', false);
  			$('#ausencia').attr('disabled', 'disabled');
  			$('#ausente').val('');
  			$('#ausente').attr('disabled', 'disabled');
  			$('.resultado_set').attr('disabled', 'disabled');
  		} else {
  			$('#ausencia').removeAttr('disabled');
  			$('#lesionado').val('');
  			$('#lesionado').attr('disabled', 'disabled');
  			$('.resultado_set').removeAttr('disabled');
  		}
		});		

		$('#ausencia').change(function() {
			if ($('#ausencia').is (':checked')) {
  			$('#ausente').removeAttr('disabled');
  			$('#lesion').attr('checked', false);
  			$('#lesion').attr('disabled', 'disabled');
  			$('#lesionado').val('');
  			$('#lesionado').attr('disabled', 'disabled');
  			$('.resultado_set').attr('disabled', 'disabled');
  		} else {
  			$('#lesion').removeAttr('disabled');
  			$('#ausente').val('');
  			$('#ausente').attr('disabled', 'disabled');
  			$('.resultado_set').removeAttr('disabled');
  		}
		});		

		
		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.99', type : 'reverse', defaultValue: '000'}
		$('input:text').setMask();
		
		$('#guardar_button')
		.click(function() {
			var resultado = 1;
			
			$('.resultado_set').each(function(){
   			if($(this).val() == '') resultado = 0;
			});			
			
			if( ((!$('#lesion').is(':checked') &&resultado == 1 ) || (!$('#ausencia').is(':checked') && resultado == 1) || ($('#lesion').is(':checked') && $('#lesionado').val()!='') || ($('#ausencia').is(':checked') && $('#ausente').val()!='')    ) ) {
				//$('#action').val('save');
				//$('#formRanking').submit();
				if(confirm('Estas seguro de querer guardar este resultado?')) {
					$('#action').val('save');
					$('#formRanking').submit();					
				}
			} else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		

		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('ranking/matchs/'.$info['id']); ?>';
		});	
		
		});
</script>
