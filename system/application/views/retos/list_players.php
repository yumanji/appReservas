<?php

if(isset($menu_lateral)) echo $menu_lateral;

if(isset($grid_code)) echo $grid_code;

?>
<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>
var identificador = '';

$(function() {
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-closethick", title:'Eliminar jugador',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					location.href='<?php echo site_url('retos/remove_player/'.$id_transaction.'/'); ?>/'+gsr;
			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Nuevo jugador',
			onClickButton:function(){
				location.href='<?php echo site_url('retos/add_player/'.$id_transaction); ?>';
			} 
		});		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-cart", title:'Cobrar jugador',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//location.href='<?php echo site_url('retos/pay_player/'.$id_transaction.'/'); ?>/'+gsr;
			  	identificador = gsr;  
			  	$('#pagar_dialog').dialog('open');
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		
		

		
});
<?php

}

?>

	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;
	
	$(function() 
	{
		$('#pagar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Efectivo': function() {
					//modificar para que mantenga el filtro
					location.href='<?php echo site_url('retos/pay_player/'.$id_transaction);?>/'+identificador+'/1';
				},
				'Tarjeta': function() {
					//modificar para que mantenga el filtro
					location.href='<?php echo site_url('retos/pay_player/'.$id_transaction);?>/'+identificador+'/2';
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
				
			});
	
	$(function() 
	{
		$('#cobrar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Cobrar': function() {
					location.href='<?php echo site_url('reservas_gest/payment_request');?>/'+identificador;
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
		
	});
	
	$(function() 
	{
		$('#luz_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Activar': function() {
					<?php $this->session->set_userdata('redirect','/reservas_gest/list_all');?>
					location.href='<?php echo site_url('reservas_gest/light_reserve_get');?>/'+identificador+'/null/null/'+ <?php echo date("U");?>;
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
		
	});
	</script>

<div id="pagar_dialog" title="Pagar reto">
	<p>Elija m&eacute;todo de pago.</p>
</div>



<div id="cancelar_dialog" title="Cancelar Reserva">
	<p>&iquest;Est&aacute; seguro de querer cancelar la reserva?</p>
		<fieldset>
			<label for="text_cancel_view">Motivo:</label>
			<input type="text" name="text_cancel_view" id="text_cancel_view" value=""/>
		</fieldset>
</div>

<div id="luz_dialog" title="Activar Luz">
	<p>&iquest;Est&aacute; seguro de querer activar la luz para la reserva?</p>
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

<div id="cobrar_dialog" title="Cobrar Reserva">
	<p>&iquest;Est&aacute; seguro de querer cobrar la reserva?</p>
</div>
<table id="flex1" style="display:none"></table>
