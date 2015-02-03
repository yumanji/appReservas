<?php
//$this->lang->load('lessons');

?>
  <form class="form_user" name="formRanking" id="formRanking" method="post" action="<?php echo current_url(); ?>">
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="470" valign="top">
        	<label><span>Nombre*</span>
              <input name="description" type="text" id="description" value="" size="25" />
          </label>
            <label><span>Fecha inicio*</span>
            <input type="text" name="start_date" id="start_date" value="" size="10" />
            </label>
            <label><span>Fecha fin*</span>
            <input type="text" name="end_date" id="end_date" value="" size="10" />
            </label>
            <label><span>N&ordm; grupos*</span>
						<input type="text" name="groups" id="groups" size="2" value="" alt="integer" />
          	</label>
            <label><span>Equipos / Grupo*</span>
						<input type="text" name="teams" id="teams" size="2" value="" alt="integer" />
          	</label>
            <label><span>Usuarios / Equipo*</span>
						<input type="text" name="team_mates" id="team_mates" size="2" value="" alt="integer" />
          	</label>
            <label><span>Activo</span>
            <input type="checkbox" name="active" id="active" value="1" />
            </label>
				</td>
        <td width="476" >
            <label><span>Deporte</span>
							<?php 
								echo form_dropdown("sport", $deportes, '', 'id="sport"');
							?>
            </label>
						<label><span>G&eacute;nero</span>
							<?php 
								echo form_dropdown("gender", $generos, '', 'id="gender"');
							?>
						</label>
						<label><span>Tipo promocion*</span>
							<?php 
								echo form_dropdown("promotion_type", $promociones, '', 'id="promotion_type"');
							?>
						</label>
						<label><span>Tarifa*</span>
							<?php 
								echo form_dropdown("price", $tarifas, '', 'id="price"');
							?>
						</label>
						<label><span>Periodicidad pago*</span>
							<?php 
								echo form_dropdown("payment_freq", $frecuencias, '', 'id="payment_freq"');
							?>
						</label>        		
						<label><span>Cuota alta</span>
              <input type="text" name="signin" id="signin" value="" size="10"  alt="dinero" />
          	</label>

            <label><span>Parciales / Partido*</span>
						<input type="text" name="score_parts" id="score_parts" size="2" value="" alt="integer" />
          	</label>
            <label><span>Duracion partidos</span>
						<input type="text" name="match_duration" id="match_duration" size="2" value="" alt="integer" /> (en fragmentos de 30 minutos)
          	</label>

				</td>
      </tr>
      <tr>
      	<td colspan="2">
      		<center>Para definir la longitud de cada jornada, rellene uno de los siguientes campos</center>
      	</td>
      </tr>
      <tr>
      	<td>
            <label><span>N&ordm; rondas*</span>
						<input type="text" name="rounds" id="rounds" size="2" value="0" alt="integer" />
          	</label>
      	</td>
      	<td>
            <label><span>Duraci&oacute;n ronda*</span>
						<input type="text" name="round_duration" id="round_duration" size="2" value="0" alt="integer" /> (dias)
          	</label>
      		
      	</td>
      </tr>
    </table>
    
    <br clear="all" />
    
    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Crear"/>
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

		//echo '	$( "#start_date" ).datepicker( "option", "defaultDate", "'.$info['inicio'].'" );'."\r\n";
		//echo '	$( "#end_date" ).datepicker( "option", "defaultDate", "'.$info['final'].'" );'."\r\n";
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
		
		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'}
		$('input:text').setMask();
		
		$('#guardar_button')
		.click(function() {
			
			if($("#price").val() != '' && $("#payment_freq").val() != '' && $("#groups").val() != '' && parseInt($("#groups").val()) > 0 && $("#score_parts").val() != '' && parseInt($("#score_parts").val()) > 0 && $("#teams").val() != '' && parseInt($("#teams").val()) > 0 && $("#team_mates").val() != '' && parseInt($("#team_mates").val()) > 0 && $("#promotion_type").val() != '' && $("#gender").val() != '' && $("#sport").val() != '' && $("#description").val() != '' && $("#start_date").val() != ''  && $("#end_date").val() != '' && ($("#end_date").val() >= $("#start_date").val()) && ( $("#rounds").val() != '0' || $("#round_duration").val() != '0' )  ) {
				$('#action').val('save');
				$('#formRanking').submit();
			} else if($("#end_date").val() < $("#start_date").val()) alert('La fecha de finalizacion debe ser posterior a la de inicio.');
				else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('ranking'); ?>';
		});	
		
		});
</script>
