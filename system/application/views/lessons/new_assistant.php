  <form class="form_user" name="formReto" id="formReto" method="post">
  	<input name="id_transaction" type="hidden" value="<?php echo $id;?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
  	<input name="paymentway" id="paymentway" type="hidden" value=""/>
    <input type="hidden" id="id_user" name="id_user">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="50%" valign="top">
            <label><span>Alumno interno</span>
						<?php 
						
							$search_user = array(
						    'name'        => 'buscausuarios2',
						    'id'          => 'buscausuarios2',
						    'class'       => 'buscar',
						  	'size'        => '20'	
						  );
							
							echo form_input($search_user);
						?>
						
            </label>
				</td>
        <td width="50%" valign="top">
            <label><span>Alumno externo</span>
						<?php 
						
							$search_user = array(
						    'name'        => 'user_desc',
						    'id'          => 'user_desc',
						    'class'       => 'buscar',
						  	'size'        => '20'	
						  );
							
							echo form_input($search_user);
						?>
						
            </label>
            <label><span>Tel&eacute;fono</span>
						<?php 
						
							$search_user = array(
						    'name'        => 'user_phone',
						    'id'          => 'user_phone',
						    'class'       => 'buscar',
						  	'size'        => '20'	
						  );
							
							echo form_input($search_user);
						?>
						
            </label>
				</td>
      </tr>
      <tr>
      	<td width="50%" valign="top">
            <label><span>Descuento (%)</span>
						<?php 
						
							$discount = array(
						    'name'        => 'discount',
						    'id'          => 'discount',
						    'class'       => 'buscar',
						    'alt'       => 'porcentaje',
						  	'size'        => '10'	
						  );
							
							echo form_input($discount);
						?>
						
            </label>
      	</td>
      	<td width="50%" valign="top">
            <label><span>Fecha de inicio</span>
						<?php 
						
							$discount = array(
						    'name'        => 'start_date',
						    'id'          => 'start_date',
						    'class'       => 'buscar',
						    'alt'       => 'date',
						  	'size'        => '10'	
						  );
							
							echo form_input($discount);
						?>
						
            </label>
      	</td>
      	<td width="50%" valign="top">
      	</td>
      </tr>
    </table>
    
    <br clear="all" />
    


	</form>
	<script>

	$(function() {
		// Mascara del porcentaje
		//Definición de máscaras del formulario
		$.mask.masks.porcentaje = {mask : '99.999', type : 'reverse', defaultValue: '000'}
		$('#discount').setMask();
		
		$( "#start_date" ).datepicker({
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
		
		$( "#buscausuarios2" ).autocomplete({
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
						//{ data:val.id, value:val.name, result:val.name };
					suggestions.push({id:val.id, value:val.value});
				});

				//pass array to callback
				add(suggestions);
			});
		},
			minLength: 2,
			select: function( event, ui ) {
				$("#id_user").val(ui.item.id);

			}
		});


				
	});
	</script>
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Suscribir"/>
			<!--<input type="button" id="pagar_button" class="boton" value="Suscribir y pagar"/>-->
      <input type="button" id="cancelar_button" class="boton" value="Cancelar"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		


		$('#guardar_button')
			.click(function() {
				$('#action').val('add');
				$('#formReto').submit();
			});
		

		$('#pagar_button')
			.click(function() {
				$('#action').val('save');
				$('#paymentway').val('1');
				$('#formReto').submit();
			});
		
		$('#cancelar_button')
		.click(function() {
			$("#formReto").attr("action", "<?php echo site_url('lessons/assistants/'.$id); ?>");
			$('#formReto').submit();
		});
	});
</script>
