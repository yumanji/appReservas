<?php

if(isset($menu_lateral)) echo $menu_lateral;


if(isset($grid_code)) echo $grid_code;

?>

<div id="repagar_dialog" title="Volver a pagar">
	<p>Desea volver a pagar este pago devuelto?</p>
</div>

<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>
	$(function() {
		
		<?php
			if($permisos['change_status']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-arrowreturnthick-1-w", title:'Cambiar a estado pendiente',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						if(gsr) location.href='<?php echo site_url('payment/change/status/2'); ?>/'+gsr;
						else alert("Seleccione un pago");
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>
			
			<?php
			if($permisos['repay_payment']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-arrowrefresh-1-e", title:'Volver a cobrar',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						$('#repagar_dialog').dialog('open');

	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>
			
		
		<?php
			if($permisos['return_payment']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-arrowrefresh-1-w", title:'Devolver pago',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						if(gsr) location.href='<?php echo site_url('payment/change/status/6'); ?>/'+gsr;
						else alert("Seleccione un pago");
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>
			
		
		<?php
			if($permisos['cancel_payment']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-closethick", title:'Cancelar pago',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						if(gsr) location.href='<?php echo site_url('payment/change/status/7'); ?>/'+gsr;
						else alert("Seleccione un pago");
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>
		
		<?php
			if($permisos['new_payment']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Nuevo pago',
				onClickButton:function(){
						//var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						location.href='<?php echo site_url('payment/add_payment/'); ?>';
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>			
		<?php
			if($permisos['view_receipt']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-print", title:'Imprimir recibo',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						if(gsr) location.href='<?php echo site_url('facturacion/view_receipt/'); ?>/'+gsr;
						else alert("Seleccione un pago");
						
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>			
	
	
			<?php
			if($permisos['export_excel']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-extlink", title:'Exportar a excel',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						location.href='<?php echo site_url($this->uri->uri_string().'/excel'); ?>';
						
						
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>			
		
		
		<?php
			if($this->uri->segment(3)=='remesa_pend') {
				#Solo mostraré el botón de generar remesas en la ventana adecuada
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-script", title:'Generar remesa',
				onClickButton:function(){
						//var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						location.href='<?php echo site_url('facturacion/genera_remesa'); ?>';
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>
		
	$('#files').tree({
		expanded: '#mnu_fact'
	});

				$('#repagar_dialog').dialog({
					autoOpen: false,
					width: 375,
					show: 'blind',
					modal: true,
					buttons: {
						'Tarjeta': function() {
								var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
								if(gsr) location.href='<?php echo site_url('payment/change/repay/2');?>/'+gsr;
								else alert("Seleccione un pago");

						},
						'Efectivo': function() {
								var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
								if(gsr) location.href='<?php echo site_url('payment/change/repay/1');?>/'+gsr;
								else alert("Seleccione un pago");

						},
						'Banco': function() {
								var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
								if(gsr) location.href='<?php echo site_url('payment/change/repay/4');?>/'+gsr;
								else alert("Seleccione un pago");

						},
						'Cancelar': function() {
							$(this).dialog('close');
						}
					}
				});
						
});
<?php

}

?>


</script>



<!-- <div id="activar_dialog" title="Validar Reserva">
	<p>Est&aacute; seguro de querer validar la reserva?</p>
</div> -->

