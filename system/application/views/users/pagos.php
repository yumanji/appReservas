
<?php
$data = array(
              'returnUrl'  => current_url(),
              'id_user' => $code_user
            );

echo form_hidden($data);

if(isset($menu_lateral)) echo $menu_lateral;


if(isset($grid_code)) echo $grid_code;







if(isset($enable_buttons) && $enable_buttons) {

?>
<div id="repagar_dialog" title="Volver a pagar">
	<p>Desea volver a pagar este pago devuelto?</p>
</div>

<?php
	echo form_close();
?>
	<div id="pagar_remesa_dialog" title="Pagar remesa">
	<?php
		$attributes = array('class' => 'frmPagoRemesa', 'id' => 'frmPagoRemesa');
		echo form_open('', $attributes);	
	?>
	<p>Opciones para modificar el pago en la misma acci&oacute;n: 
	<br>Cantidad: <input name="payable_quantity_tmp" type="text" id="payable_quantity_tmp" value="" size="7" alt="dinero"/>
	<br>Fecha: <input name="payable_date_tmp" type="text" id="payable_date_tmp" value="" size="12" /> 
	<br> Forma de pago: <input type="radio" id="radio1" name="payable_paymentway_tmp" value="1"><label for="radio1">Efectivo</label>
						<input type="radio" id="radio2" name="payable_paymentway_tmp" value="2"><label for="radio2">Tarjeta</label>
						<input type="radio" id="radio3" name="payable_paymentway_tmp" value="4" checked="checked"><label for="radio3">Banco</label>
	<br>&nbsp;<br>(dejar campos vac&iacute;os para no cambiar)</p>

</div>

<script type="text/javascript">

$(function() {
		
		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'};
		$.mask.masks.dni = {mask : 'a-99999999', type : 'reverse'};
		$('input:text').setMask();

		
		<?php
			if(isset($permisos['change_status']) && $permisos['change_status']) {
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


			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-suitcase", title:'Cambiar a estado pagado',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						if(gsr) $('#pagar_remesa_dialog').dialog('open');					
						else alert("Seleccione un pago");
						//if(gsr) location.href='<?php echo site_url('payment/change/status/9'); ?>/'+gsr;
	
				  	//identificador = gsr;  
								
				} 	
			});	
			<?php
			}
		?>
			
			<?php
			if(isset($permisos['repay_payment']) && $permisos['repay_payment']) {
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
			if(isset($permisos['return_payment']) && $permisos['return_payment']) {
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
			if(isset($permisos['cancel_payment']) && $permisos['cancel_payment']) {
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
			if(isset($permisos['new_payment']) && $permisos['new_payment']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Nuevo pago',
				onClickButton:function(){
						//var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						document.getElementById('frmGrid').action='<?php echo site_url('payment/add_payment');?>/';
						document.getElementById('frmGrid').submit();
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>			
		
		<?php
			if(isset($permisos['new_payment']) && $permisos['new_payment']) {
		?>
			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-minusthick", title:'Nueva devolucion',
				onClickButton:function(){
						//var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						document.getElementById('frmGrid').action='<?php echo site_url('payment/add_payment');?>/6';
						document.getElementById('frmGrid').submit();
						//location.href='<?php echo site_url('payment/add_payment/'); ?>/6';
	
				  	//identificador = gsr;  
								
				} 	
			});		
		<?php
			}
		?>			<?php
			if(isset($permisos['view_receipt']) && $permisos['view_receipt']) {
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


			jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-contact", title:'Imprimir recibo extendido',
				onClickButton:function(){
						var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
						//alert(gsr);
						
						/*
						document.getElementById('hora_inicio_view').value = horaInicio;
						document.getElementById('hora_fin_view').value = horaFin;
						*/
						if(gsr) window.open('<?php echo site_url('payment/view_receipt/'); ?>/'+gsr+'/extended', "ticket_ext", "width=600,height=700,scrollbars=yes");
						else alert("Seleccione un pago");
				  	//identificador = gsr;  
								
				} 	
			});	

			<?php
			}
		?>			
	
	
			<?php
			if(isset($permisos['export_excel']) && $permisos['export_excel']) {
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



				$('#pagar_remesa_dialog').dialog({
					autoOpen: false,
					width: 375,
					show: 'blind',
					modal: true,
					buttons: {
						'Pagar': function() {
								var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
								//alert(gsr);
								//document.forms["frmPagoRemesa"].action='<?php echo site_url('payment/change/status/9');?>/'+gsr;
								$("#frmPagoRemesa").attr("action", '<?php echo site_url('payment/change/remesa/9');?>/'+gsr);
								$("#frmPagoRemesa").submit(); 
								//alert($("#frmPagoRemesa").attr("action"));
								//if(gsr) location.href='<?php echo site_url('payment/change/status/9');?>/'+gsr;
								//else alert('<?php echo site_url('payment/change/status/9');?>/'+gsr);

						},
						'Cancelar': function() {
							$(this).dialog('close');
						}
					}
				});
				
}
);
</script>

<?php

}

	if(isset($permisos['change_status']) && $permisos['change_status']) {
		echo "	<script type=\"text/javascript\">"."\r\n";
		echo "	$(function() {
							$(\"#payable_date_tmp\").datepicker({
								showOn: 'button',
								buttonImage: '".base_url()."/images/calendar.gif',
								buttonImageOnly: true,
								constrainInput: true,
								dateFormat: 'dd-mm-yy',
								dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
								monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
								firstDay: 1,
								minDate: 0		}
								
								);
						});"."\r\n";
		echo "	</script>"."\r\n";
	}
?>
