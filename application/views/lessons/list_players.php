<?php

if(isset($menu_lateral)) echo $menu_lateral;

if(isset($grid_code)) echo $grid_code;
//print_r($motivos_baja); 
?>
<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>

function jqGrid_ondblClickRow() {
	var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
	if(gsr){
		location.href='<?php echo site_url('lessons/asistant_info/'.$id_transaction);?>/'+gsr;
		identificador = gsr;  
	}
}

var identificador = '';

$(function() {

		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'};
		$('input:text').setMask();
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-closethick", title:'Eliminar alumno',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//location.href='<?php echo site_url('lessons/unsubscribe_assistant/'.$id_transaction.'/'); ?>/'+gsr;
			  	//identificador = gsr;  
				  	identificador = gsr;  
				  	$('#baja_dialog').dialog('open');
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Nuevo alumno',
			onClickButton:function(){
				location.href='<?php echo site_url('lessons/add_assistant/'.$id_transaction); ?>';
			} 
		});		
		<?php  if($alta) {  ?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-flag", title:'Pagar alta de alumno',
				onClickButton:function(){
					var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
					if(gsr){
				  	identificador = gsr;  
				  	$('#alta_dialog').dialog('open');
					  
					} else {
						alert("Please select Row")
					}							
				} 
			});		
	<?php }  ?>
	
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-cart", title:'Cobrar a alumno',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//location.href='<?php echo site_url('retos/pay_player/'.$id_transaction.'/'); ?>/'+gsr;
			  	identificador = gsr;  
			  	$('#pagar_dialog').dialog('open');
				  
				} else {
					alert("Please select Row");
				}							
			} 
		});	
	
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-document", title:'Listado de alumnos',
			onClickButton:function(){
				location.href='<?php echo site_url('informes/clases_listado/'.$id_transaction.'/excel'); ?>';
							
			} 
		});			
		
		<?php  if(isset($carnet) && $carnet) {  ?>
	
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-contact", title:'Carnet de alumno',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					location.href='<?php echo site_url('lessons/carnet/'.$id_transaction.'/'); ?>/'+gsr;
				} else {
					alert("Debe seleccionar un alumno");
				}							
			} 
		});	
	<?php }  ?>
	
		
});
<?php

}

?>

	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;

	
	$(function() 
	{
		
		$('input:text').setMask();
		
		$('#pagar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Efectivo': function() {
					//modificar para que mantenga el filtro
					//location.href='<?php echo site_url('lessons/asistant_payment/'.$id_transaction);?>/'+identificador+'/1';
					document.getElementById('frmPago2').action='<?php echo site_url('lessons/asistant_payment/'.$id_transaction);?>/'+identificador+'/1';
					document.getElementById('frmPago2').submit();

				},
				'Tarjeta': function() {
					//modificar para que mantenga el filtro
					//location.href='<?php echo site_url('lessons/asistant_payment/'.$id_transaction);?>/'+identificador+'/2';
					document.getElementById('frmPago2').action='<?php echo site_url('lessons/asistant_payment/'.$id_transaction);?>/'+identificador+'/2';
					document.getElementById('frmPago2').submit();
				},
				'Banco': function() {
					//modificar para que mantenga el filtro
					//location.href='<?php echo site_url('lessons/asistant_payment/'.$id_transaction);?>/'+identificador+'/4';
					document.getElementById('frmPago2').action='<?php echo site_url('lessons/asistant_payment/'.$id_transaction);?>/'+identificador+'/4';
					document.getElementById('frmPago2').submit();
				},
			}
		});
		
			$('#alta_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Efectivo': function() {
					//modificar para que mantenga el filtro
					document.getElementById('frmPago').action='<?php echo site_url('lessons/sign_assistant/'.$id_transaction);?>/'+identificador+'/1';
					document.getElementById('frmPago').submit();
					$(this).dialog('close');
				},
				'Tarjeta': function() {
					//modificar para que mantenga el filtro
					document.getElementById('frmPago').action='<?php echo site_url('lessons/sign_assistant/'.$id_transaction);?>/'+identificador+'/2';
					document.getElementById('frmPago').submit();
					$(this).dialog('close');
				},
				'Banco': function() {
					//modificar para que mantenga el filtro
					document.getElementById('frmPago').action='<?php echo site_url('lessons/sign_assistant/'.$id_transaction);?>/'+identificador+'/4';
					document.getElementById('frmPago').submit();
					$(this).dialog('close');
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
				'Cancelar Reserva': function() {
					//modificar para que mantenga el filtro
					document.getElementById('text_cancel').value = document.getElementById('text_cancel_view').value;
					document.getElementById('frmGrid').action='<?php echo site_url('reservas_gest/cancel_reserve');?>/';
					document.getElementById('frmGrid').submit();
					$(this).dialog('close');
				},
				'Cerrar': function() {
					$(this).dialog('close');
				}
			}
		});



		$('#baja_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Baja del alumno': function() {
					//modificar para que mantenga el filtro
					//alert (document.getElementById('unsubscription_reason').value);
					document.getElementById('frmBaja').action='<?php echo site_url('lessons/unsubscribe_assistant/'.$id_transaction.'/'); ?>/'+identificador;
					document.getElementById('frmBaja').submit();
					//alert(document.getElementById('frmBaja').action);
					$(this).dialog('close');
				},
				'Cerrar': function() {
					$(this).dialog('close');
				}
			}
		});
		
	});
	
	$(function() 
	{	
		$("#cambiar_dialog").dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: 
			{
				'Modificar': function() 
				{
					//FALTA VALIDACIÓN DE CAMPOS
					document.getElementById('hora_inicio').value = document.getElementById('hora_inicio_view').value;
					document.getElementById('hora_fin').value = document.getElementById('hora_fin_view').value;
					$('#confirmar_dialog').dialog('open');
					$(this).dialog('close');
				},
				'Cancelar': function() 
				{
					$(this).dialog('close');
				}
			}
		});
		
	});
	
	$(function() 
			{
				$('#confirmar_dialog').dialog({
					autoOpen: false,
					show: 'blind',
					modal: true,
					buttons: {
						'Modificar': function() {
							//FALTA VALIDACIÓN DE CAMPOS
							document.getElementById('frmGrid').action='<?php echo site_url('reservas_gest/change_reserve');?>/';
							//alert(document.frmGrid.hora_inicio.value);
							document.getElementById('frmGrid').submit();
							$(this).dialog('close');
						},
						'Cerrar': function() {
							$(this).dialog('close');
						}
					}
				});



			var dates = $( "#payd_date_tmp" ).datepicker({
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
				
				onSelect: function( selectedDate ) {
					var option = this.id == "start_date" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" );
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			}
				
				);



				
			});

	

	</script>

<div id="pagar_dialog" title="Pagar cuota">
	<form action="" method=post name="frmPago2" id="frmPago2">
	<p>Pago de la cuota de socio.<br/>
		Pagado hasta: <input name="payd_date_tmp" type="text" id="payd_date_tmp" value="" /><br/>
		Cuota a cobrar <input name="payable_quota_tmp" type="text" id="payable_quota_tmp" value="<?php echo number_format(0,2); ?>" size="7" alt="dinero"/><br/>
		<span style="color: red; font-size:0.75em;">(Si se deja un campo vac&iacute;o se aplica el valor por defecto)</span></p>
	</form>
</div>


<div id="alta_dialog" title="Alta en curso">
	<p>Elija m&eacute;todo de pago para el alta de usuario.</p>
	<form action="" method=post name="frmPago" id="frmPago">
		Fecha alta: <input type="text" name="sign_date" id="sign_date" value="<?php echo date($this->config->item('reserve_date_filter_format')); ?>" alt="date">
	</form>
</div>



<div id="cancelar_dialog" title="Cancelar Reserva">
	<p>&iquest;Est&aacute; seguro de querer cancelar la reserva?</p>
		<fieldset>
			<label for="text_cancel_view">Motivo:</label>
			<input type="text" name="text_cancel_view" id="text_cancel_view" value=""/>
		</fieldset>
</div>



<div id="baja_dialog" title="Baja Alumno">
	<p>Elija el motivo por el que el alumno causa baja</p>
		<form action="" method=post name="frmBaja" id="frmBaja">
			<fieldset>
				<label for="text_cancel_view">Motivo:</label>
				<?php echo form_dropdown('unsubscription_reason', $motivos_baja, '','id="unsubscription_reason"'); ?>
			</fieldset>
		</form>
</div>



<div id="cambiar_dialog" title="Modificar Hora Reserva">
	<p class="validateTips">Todos los campos son obligatorios.</p>
		<fieldset>
			<label for="hora_inicio">Hora Inicio:</label>
			<input type="text" name="hora_inicio_view" id="hora_inicio_view" value=""/>
			<br>
			<label for="hora_fin">Hora Fin:</label>
			<input type="text" name="hora_fin_view" id="hora_fin_view" value="" />
		</fieldset>
</div>

<div id="confirmar_dialog" title="Confirmar">
	<p>&iquest;Est&aacute; seguro de querer modificar la reserva?</p>
</div>


<table id="flex1" style="display:none"></table>
