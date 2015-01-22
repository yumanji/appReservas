<?php
//$this->lang->load('lessons');
?>
  <form class="form_user" name="formDetail" id="formDetail" method="post">
  <?php
		//print_r($this->session->userdata);
		echo "	<script type=\"text/javascript\">"."\r\n";
		echo "	$(function() {
		
							var dates = $( \"#fecha_valor\" ).datepicker({
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
								
							$( \"#radio\" ).buttonset();	
						});"."\r\n";

		echo '	$( "#fecha_valor" ).datepicker( "option", "defaultDate", "'.date($this->config->item('reserve_date_filter_format')).'" );'."\r\n";
		echo "	</script>"."\r\n";
?>
  	<input name="id_user" id="id_user" type="hidden" value="<?php echo $id_user;?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td colspan="2" valign="top" align="center">
        	<label><?php echo $description; ?> <?php if($id_user!='' && $user_desc!='') echo ' de '.$user_desc; ?></label>
        </td>
      </tr>
      <tr>
        <td width="470" valign="top">
        	<label><span>Usuario*</span>
							<?php 
								if($id_user!='' && $user_desc!='') {
									echo '<input name="user_description" type="text" id="user_description" value="'.$user_desc.'" size="25" disabled/>';
								} else {
									$search_user = array(
								    'name'        => 'user_desc',
								    'id'          => 'user_desc',
								    'class'       => 'buscar',
								  	'size'        => '20'	
								  );
									
									echo form_input($search_user).'</label><label><span>Usuario externo</span>';
									echo '<input name="user_description" type="text" id="user_description" value="" size="25" /></label>';
								}
							?>
          </label>
        	<label><span>Concepto*</span>
              <input name="description" type="text" id="description" value="" size="25" maxlength="75"/>
          </label>
          <label><span>Forma de pago*</span>
					<?php
					if(isset($paymentMethods) && count($paymentMethods)>0) {
						echo '<select name="paymentway" id="paymentway">';
						echo '<option value=""></option>';
						foreach ($paymentMethods as $method => $active) {
							if($active) {
								echo '<option value="'.$method.'">'.$this->lang->line($method.'_button').'</option>';
							}
						}
						echo '</select>';
					}
					?>
				</td>
        <td width="476" >
          <label><span>Fecha valor*</span>
          <input type="text" name="fecha_valor" id="fecha_valor" value="<?php echo date($this->config->item('reserve_date_filter_format')); ?>" size="10" />
          </label>
      		<label> <span>Cantidad (&euro;)*</span>
            <input type="text" name="quantity" id="quantity" value="" size="10" alt="dinero" />
        	</label>

				</td>
      </tr>
    </table>
    
    <br clear="all" />
    
    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="refresh_button" class="boton" value="Reiniciar"/>
			<input type="button" id="guardar_button" class="boton" value="Guardar"/>
      <input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />


<script type="text/javascript">

	$(function() {
		
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


				
		
		$( "#user_desc" ).autocomplete({
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
				$("#user_description").attr('disabled', true);

			}
		});


		
		$('#user_description').change(function() {

				$("#user_desc").attr('disabled', true);
		
		});
		
				


		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'}
		$('input:text').setMask();
		
		$('#guardar_button')
		.click(function() {
			if($("#description").val() != '' && ($("#id_user").val() != '' || $("#user_description").val() != '')&& $("#fecha_valor").val() != '' && $("#quantity").val() != '' && $("#quantity").val() != '0.00') {
				$('#action').val('save');
				$('#formDetail').submit();
			}	else alert('Complete todos los campos de informacion antes de grabar.');
			return false;
			
		});
		

		
		$('#volver_button')
		.click(function() {
			location.href = '<?php if(isset($returnUrl) && $returnUrl!='') echo $returnUrl; else echo site_url('facturacion/list_all'); ?>';
		});
		
		$('#refresh_button')
		.click(function() {
			location.href = '<?php echo site_url('payment/add_payment'); ?>';
		});	
		
		});
</script>
