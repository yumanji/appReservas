<?php

if(isset($menu_lateral)) echo $menu_lateral;

?>
	<script type="text/javascript">
	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;
	$(function() {
		$('#modificar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			hide: 'explode'
		});
		
		$('.bDiv img').click(function() {
			alert('aa');
			$('#modificar_dialog').dialog('open');
			return false;
		});
	});
	</script>
	
<?php

if(isset($grid_code)) echo $grid_code;

?>
<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>
$(function() {
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-circle-close", title:'Cancelar reserva',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					$('#cancelar_dialog').dialog('open');
					document.getElementById('id_transaction').value = gsr;
				  identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});
		

		
		
		
		jQuery("#grid_name").jqGrid('setGridParam', {
			ondblClickRow:function(id, ri, ci){
			
	      alert(id); 
	   	}
	  });	
		
});
<?php

}

?>

</script>
	<script type="text/javascript">
	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;
	
	
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
	
	</script>

<!-- <div id="activar_dialog" title="Validar Reserva">
	<p>Est&aacute; seguro de querer validar la reserva?</p>
</div> -->
<input type="hidden" id="id_transaction" name="id_transaction" value="">
<input type="hidden" name="hora_inicio" id="hora_inicio" value=""/>
<input type="hidden" name="hora_fin" id="hora_fin" value="" />
<input type="hidden" name="text_cancel" id="text_cancel" value="" />

<div id="cancelar_dialog" title="Cancelar Reserva">
	<p>&iquest;Est&aacute; seguro de querer cancelar la reserva?</p>
		<fieldset>
			<label for="text_cancel_view">Motivo:</label>
			<input type="text" name="text_cancel_view" id="text_cancel_view" value=""/>
		</fieldset>
</div>

<div id="confirmar_dialog" title="Confirmar">
	<p>&iquest;Est&aacute; seguro de querer modificar la reserva?</p>
</div>


