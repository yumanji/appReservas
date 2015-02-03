<?php 
// print("<pre>");print_r($info); 


$this->lang->load('lessons');
if(!isset($return_url) || $return_url == "") $return_url = site_url('lessons/lista');
?>
  <form class="form_user" name="formCurso" id="formCurso" method="post">
  	<input name="id" id="id" type="hidden" value="<?php echo $info->id;?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="470" valign="top">
        	<label><span>Nombre*</span>
              <input name="description" type="text" id="description" value="<?php echo $info->description; ?>" size="25" />
          </label>
            <label><span>Fecha inicio*</span>
            <input type="text" name="start_date" id="start_date" value="<?php echo date($this->config->item('reserve_date_filter_format'), strtotime($info->start_date)); ?>" size="10" />
            </label>
            <label><span>Fecha fin*</span>
            <input type="text" name="end_date" id="end_date" value="<?php echo date($this->config->item('reserve_date_filter_format'), strtotime($info->end_date)); ?>" size="10" />
            </label>
			<label><span>Inicio*</span><input type="text" size="5" name="start_time" id="start_time" value="<?php echo substr($info->start_time, 0, 5); ?>" alt="time" /></label>
			<label><span>Fin*</span><input type="text" size="5" name="end_time" id="end_time"  value="<?php echo substr($info->end_time, 0, 5); ?>" alt="time" /></label>

            <label><span>Plazas*</span>
						<input type="text" name="max_vacancies" id="max_vacancies" size="2" value="<?php echo $info->max_vacancies; ?>" alt="integer" />
          	</label>
            <label><span>Dia de pago</span>
						<input type="text" name="monthly_payment_day" id="monthly_payment_day" size="2" value="<?php echo $info->monthly_payment_day; ?>"  alt="integer" />
          	</label>

				</td>
        <td width="476" >
            <label><span>Deporte</span>
							<select name="id_sport" id="id_sport">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($deportes	 as $code => $deporte)
									{
										if($info->id_sport==$code) echo '<option value="'.$code.'" selected>'.$deporte.'</option>';
										else echo '<option value="'.$code.'">'.$deporte.'</option>';
									}
								?>
							</select>
            </label>
            <label><span>Pista*</span>
							<select name="id_court" id="id_court">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($pistas	 as $code => $pista)
									{
										if($info->id_court==$code) echo '<option value="'.$code.'" selected>'.$pista.'</option>';
										else echo '<option value="'.$code.'">'.$pista.'</option>';
									}
								?>
							</select>
            </label>
						<label><span>D&iacute;a semana*</span>
							L <input type="checkbox" name="L" id="L" value="1" <?php if($info->L) echo 'checked'; ?>/>&nbsp;&nbsp;
							M <input type="checkbox" name="M" id="M" value="1" <?php if($info->M) echo 'checked'; ?>/>&nbsp;&nbsp;
							X <input type="checkbox" name="X" id="X" value="1" <?php if($info->X) echo 'checked'; ?>/>&nbsp;&nbsp;
							J <input type="checkbox" name="J" id="J" value="1" <?php if($info->J) echo 'checked'; ?>/>&nbsp;&nbsp;
							V <input type="checkbox" name="V" id="V" value="1" <?php if($info->V) echo 'checked'; ?>/>&nbsp;&nbsp;
							S <input type="checkbox" name="S" id="S" value="1" <?php if($info->S) echo 'checked'; ?>/>&nbsp;&nbsp;
							D <input type="checkbox" name="D" id="D" value="1" <?php if($info->D) echo 'checked'; ?>/>&nbsp;<br/>
						</label>
						<label><span>G&eacute;nero</span>
							<select name="gender" id="gender">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($generos	 as $code => $genero)
									{
										if($info->gender==$code) echo '<option value="'.$code.'" selected>'.$genero.'</option>';
										else echo '<option value="'.$code.'">'.$genero.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Nivel*</span>
							<select name="level" id="level">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($niveles	 as $code => $nivel)
									{
										if($info->level==$code) echo '<option value="'.$code.'" selected>'.$nivel.'</option>';
										else echo '<option value="'.$code.'">'.$nivel.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Profesor*</span>
							<select name="id_instructor" id="id_instructor">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($profesores	 as $code => $profe)
									{
										if($info->id_instructor==$code) { echo '<option value="'.$code.'" selected>'; echo (strlen($profe)>25) ? (substr($profe, 0, 25).'..'):$profe; echo '</option>'; }
										else { echo '<option value="'.$code.'">'; echo (strlen($profe)>25) ? (substr($profe, 0, 25).'..'):$profe; echo '</option>'; }
									}
								?>
							</select>
						</label>
        		<label> <span>Cuota alta*</span>
              <input type="text" name="signin" id="signin" value="<?php echo $info->signin; ?>" size="10"  alt="dinero" />
          	</label>
						<label><span>Tarifa*</span>
							<select name="price" id="price">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($tarifas	 as $code => $tarifa)
									{
										if($info->price == $code) echo '<option value="'.$code.'" selected>'.$tarifa.'</option>';
										else echo '<option value="'.$code.'">'.$tarifa.'</option>';
									}
								?>
							</select>
						</label>
            <label><span>Activo</span>
            <input type="checkbox" name="active" id="active" value="1" <?php if($info->active) echo 'checked'; ?>/>
            </label>

				</td>
      </tr>
    </table>
    
    <br clear="all" />
    
    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Guardar"/>
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

  </script>
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

		echo '	$( "#start_date" ).datepicker( "option", "defaultDate", "'.date($this->config->item('reserve_date_filter_format'), strtotime($info->start_date)).'" );'."\r\n";
		echo '	$( "#end_date" ).datepicker( "option", "defaultDate", "'.date($this->config->item('reserve_date_filter_format'), strtotime($info->end_date)).'" );'."\r\n";
		echo "	</script>"."\r\n";
?>
<script type="text/javascript">
	$(function() {
		


		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'}
		$('input:text').setMask();
		
		$('#id_sport')
		.change(function() {
			$('#formCurso').submit();
		});
		
		$('#guardar_button')
		.click(function() {
			
			if($("#level").val() != '' && $("#price").val() != '' && $("#id_instructor").val() != '' && $("#id_sport").val() != '' && $("#id_court").val() != '' && $("#max_vacancies").val() > 0 && $("#description").val() != '' && $("#start_date").val() != ''  && $("#end_date").val() != '' && $("#start_time").val() != '' && $("#end_time").val() != ''  ) {
				$('#action').val('save');
				$('#formCurso').submit();
			}	else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		

		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo $return_url; ?>';
		});	
		
		});
</script>
