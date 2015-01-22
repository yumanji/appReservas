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

?>$(function() {
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
		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-lightbulb", title:'Activar luz',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					$('#luz_dialog').dialog('open');
			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		
		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-cart", title:'Cobrar reserva',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					$('#cobrar_dialog').dialog('open');
			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		
		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-clock ", title:'Modificar reserva',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					document.getElementById('id_transaction').value = gsr;
					
					
					$('#cambiar_dialog').dialog('open');
			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		
		
		
		
		jQuery("#grid_name").jqGrid('setGridParam', {
			ondblClickRow:function(id, ri, ci){
			
	      location.href='<?php echo site_url('reservas_gest/detail');?>/'+id; 
	   	}
	  });	


	$('#files').tree({
		expanded: '#mnu_reserva'
	});

		
});
<?php

}

?>


function buttons(com,grid)
{
    if (com=='<?php echo $this->lang->line('new_reserve'); ?>')
    {
			location.href='<?php echo site_url('reservas'); ?>';
    }
    
    if (com=='DeSelect All')
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }
    
    if (com=='Delete')
        {
           if($('.trSelected',grid).length>0){
			   if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
		            var items = $('.trSelected',grid);
		            var itemlist ='';
		        	for(i=0;i<items.length;i++){
						itemlist+= items[i].id.substr(3)+",";
					}
					$.ajax({
					   type: "POST",
					   url: "<?php echo site_url("/ajax/deletec");?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#flex1').flexReload();
					  	alert(data);
					   }
					});
				}
			} else {
				return false;
			} 
        }          
} 
</script>
<script type="text/javascript">
	var identificador = '';
	function rowClick(celDiv, id){
	    
		$(celDiv).click(
	  function() { 
			$('#modificar_dialog').dialog('open');

				  }
	    )
	}
		
	function validarReserva(celDiv, id){
		$(celDiv).click(
	  function() { 
			$('#activar_dialog').dialog('open');
		  	identificador = id;  
				  }
	    )
	}
		
	function cancelarReserva(celDiv, id)
	{
		$(celDiv).click
		(
		  	function() 
		  	{ 
				$('#cancelar_dialog').dialog('open');
				document.getElementById('id_transaction').value = id;
			  	identificador = id;  
			}
	    )
	}

	function modificarReserva(id,horaInicio, horaFin)
	{
		document.getElementById('hora_inicio_view').value = horaInicio;
		document.getElementById('hora_fin_view').value = horaFin;
		document.getElementById('id_transaction').value = id;
		
		
		$('#cambiar_dialog').dialog('open');
  		identificador = id;  
			
	}
		
	function cobrarReserva(celDiv, id){
		$(celDiv).click(
	  function() { 
			$('#cobrar_dialog').dialog('open');
		  	identificador = id;  
				  }
	    )
	}
		
	function setLight(celDiv, id)
	{
		$(celDiv).click
		(
		  	function() 
		  	{ 
				$('#luz_dialog').dialog('open');
			  	identificador = id;  
			}
	    )
	}
</script>
	<script type="text/javascript">
	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;
	
	$(function() 
	{
		$('#activar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Validar': function() {
					//modificar para que mantenga el filtro
					location.href='<?php echo site_url('reservas_gest/validate_reserve');?>/'+identificador;
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
					location.href='<?php echo site_url('reservas_gest/payment_request');?>/'+identificador+'/light_only';
				},
				'Cancelar': function() {
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
