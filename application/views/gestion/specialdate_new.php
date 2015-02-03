  <form class="form_user" name="formUser" method="post">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="405" valign="top">

						<label><span>Pista</span>
							<select name="id_court" id="id_court">
								<option value="0">--Todas--</option>
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($array_courts	 as $code => $value)
									{
										echo '<option  value="'.$code.'">'.$value.'</option>';
									}
								?>
							</select>
						</label>
						
						<label><span>Horario</span>
							<select name="time_table" id="time_table">
								<option value="">--Seleccionar--</option>
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($array_times	 as $code => $value)
									{
										echo '<option  value="'.$code.'">'.$value.'</option>';
									}
								?>
							</select>
						</label>
				</td>
        <td width="476" >
           <label><span>Fecha</span>
            <input type="text" name="date" id="date"  value="<?php echo date($this->config->item('reserve_date_filter_format'), time()); ?>" />
          </label>

				</td>
      </tr>
    </table>
    <input type="hidden" name="action" id="action" value="guardar">  
    <input type="hidden" name="status" id="status" value="1">  
    <input type="hidden" name="type" id="type" value="2">  
    <br clear="all" />
    

    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Enviar"/>
      <input type="button" id="cancelar_button" class="boton" value="Cancelar"/>
    </div>
    &nbsp;
    <br clear="all" />

	<script type="text/javascript">
			$(function() {


			var dates = $( "#date" ).datepicker({
				showOn: 'button',
				buttonImage: '<?php echo base_url(); ?>/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 2,
				dateFormat: 'dd-mm-yy',
				dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
				monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				firstDay: 1,
				

			}
				
				);


			});


			</script>

<script type="text/javascript">
		$('#guardar_button')
		.click(function() {
			  $('#activar_dialog').dialog('open');
		});
		
		$('#cancelar_button')
		.click(function() {
			$('#cancelar_dialog').dialog('open');
		});
		
		

</script>
<script type="text/javascript">
	$(function() 
	{



		$('#activar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Guardar': function() {
					//chequeo de usuarios
					var bValid = true;
					if($('#time_table').val()=='') bValid = false;
					if($('#id_court').val()=='') bValid = false;
					if (bValid) {
						document.forms["formUser"].action='<?php echo site_url('gestion/new_specialdate');?>';
						document.forms["formUser"].submit();
					}	
					else
					{
						$(this).dialog('close');
					}			 
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
	});
	
	$(function() 
	{
		$('#cancelar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Salir': function() {
					document.forms["formUser"].action='<?php echo site_url('gestion/pistas');?>';
					document.forms["formUser"].submit(); 
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
	});
</script>

<div id="activar_dialog" title="Crear horario especial">
	<p>Est&aacute; seguro de querer cambiar el horario para ese dia?</p>
</div>

<div id="cancelar_dialog" title="Salir">
	<p>Est&aacute; seguro de cancelar la operaci&oacute;n?</p>
</div>