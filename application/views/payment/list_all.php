<?php

if(isset($menu_lateral)) echo $menu_lateral;


//if($this->uri->segment(3)=='remesa_pend') 
echo '<div align="right">Busqueda r&aacute;pida: '.form_input(array('name' => 'quicksearch', 'id'=> 'quicksearch', 'value'=> '', 'maxlength'=> '40', 'size'=> '20')).'</div>';
			
if(isset($grid_code)) echo $grid_code;

?>

<div id="repagar_dialog" title="Volver a pagar">
	<p>Desea volver a pagar este pago devuelto?</p>
</div>

<?php if($this->uri->segment(3)=='remesa_pend') { ?>
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
	<?php
		echo form_close();
	?>
</div>
<?php } ?>

<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>

	$(function() {

		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'};
		$.mask.masks.dni = {mask : 'a-99999999', type : 'reverse'};
		$('input:text').setMask();


		$("#quicksearch").keypress(function(e) {
			if(e.which == 13) {

				var grid = $("#grid_name");
				if ($("#quicksearch").val() == '') {
					grid[0].p.search = false;
					$.extend(grid[0].p.postData,{filters:""});
					$.extend(grid[0].p.postData,{searchField:"",searchString:"",searchOper:""});
				}
				//f = {groupOp:"OR",rules:[]};
				//f.rules.push({field:"ticket_number",op:"cn",data:searchFiler});
				grid[0].p.search = true;
				//$.extend(grid[0].p.postData,{filters:JSON.stringify(f)});
				$.extend(grid[0].p.postData,{searchString:$("#quicksearch").val()});
				$.extend(grid[0].p.postData,{searchOper:'cn'});
				$.extend(grid[0].p.postData,{searchField:'ticket_number'});
				grid.trigger("reloadGrid",[{page:1,current:true}]);


			}
		});

	
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
		<?php
			}
		?>


	
		<?php
		
		
			if(isset($permisos['change_payed']) && $permisos['change_payed']) {
		?>
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
						location.href='<?php echo site_url('payment/add_payment/'); ?>';
	
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
						location.href='<?php echo site_url('payment/add_payment/'); ?>/6';
	
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

				$('#pagar_remesa_dialog').dialog({
					autoOpen: false,
					width: 375,
					show: 'blind',
					modal: true,
					buttons: {
						'Pagar': function() {
								var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
								//alert(gsr);
								document.forms["frmPagoRemesa"].action='<?php echo site_url('payment/change/remesa/9');?>/'+gsr;
								document.forms["frmPagoRemesa"].submit(); 

								//if(gsr) location.href='<?php echo site_url('payment/change/status/9');?>/'+gsr;
								//else alert("Seleccione un pago");

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

<?php
	if(isset($permisos['change_payed']) && $permisos['change_payed']) {
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
<!-- <div id="activar_dialog" title="Validar Reserva">
	<p>Est&aacute; seguro de querer validar la reserva?</p>
</div> -->

