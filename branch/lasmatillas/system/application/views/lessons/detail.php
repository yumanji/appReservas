<?php 
// print("<pre>");print_r($info); 


$this->lang->load('lessons');
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
						<label><span>Inicio*</span>
							<select name="start_time" id="start_time">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									$time_array=array(
									 "00:00:00"=>"0:00","00:30:00"=>"0:30","01:00:00"=>"1:00","01:30:00"=>"1:30","02:00:00"=>"2:00","02:30:00"=>"2:30",
									 "03:00:00"=>"3:00","03:30:00"=>"3:30","04::00:00"=>"4:00","04::30:00"=>"4:30","05::00:00"=>"5:00","05::30:00"=>"5:30",
									 "06:00:00"=>"6:00","06:30:00"=>"6:30","07:00:00"=>"7:00","07:30:00"=>"7:30","08:00:00"=>"8:00","08:30:00"=>"8:30",
									 "09:00:00"=>"9:00","09:30:00"=>"9:30","10:00:00"=>"10:00","10:30:00"=>"10:30","11:00:00"=>"11:00","11:30:00"=>"11:30",
									 "12:00:00"=>"12:00","12:30:00"=>"12:30","13:00:00"=>"13:00","13:30:00"=>"13:30","14:00:00"=>"14:00","14:30:00"=>"14:30",
									 "15:00:00"=>"15:00","15:30:00"=>"15:30","16:00:00"=>"16:00","16:30:00"=>"16:30","17:00:00"=>"17:00","17:30:00"=>"17:30",
									 "18:00:00"=>"18:00","18:30:00"=>"18:30","19:00:00"=>"19:00","19:30:00"=>"19:30","20:00:00"=>"20:00","20:30:00"=>"20:30",
									 "21:00:00"=>"21:00","21:30:00"=>"21:30","22:00:00"=>"22:00","22:30:00"=>"22:30","23:00:00"=>"23:00","23:30:00"=>"23:30","23:59:00"=>"23:59"
									);
									foreach($time_array	 as $code => $time)
									{
										if($info->start_time===$code) echo '<option value="'.$code.'" selected>'.$time.'</option>';
										else echo '<option value="'.$code.'">'.$time.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Fin*</span>
							<select name="end_time" id="end_time">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($time_array	 as $code => $time)
									{
										if($info->end_time===$code) echo '<option value="'.$code.'" selected>'.$time.'</option>';
										else echo '<option value="'.$code.'">'.$time.'</option>';
									}
								?>
							</select>
						</label>
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
										if($info->id_instructor==$code) echo '<option value="'.$code.'" selected>'.$profe.'</option>';
										else echo '<option value="'.$code.'">'.$profe.'</option>';
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
		$.mask.masks.dinero = {mask : '99.99', type : 'reverse', defaultValue: '000'}
		$('input:text').setMask();
		
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
			location.href = '<?php echo site_url('lessons'); ?>';
		});	
		
		});
</script>
