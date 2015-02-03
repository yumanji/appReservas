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
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Nuevo',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');

					location.href='<?php echo site_url('gestion/new_specialdate'); ?>';


				  
						
			} 	
		});		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-closethick", title:'Borrar',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					location.href='<?php echo site_url('gestion/del_specialdate'); ?>/'+gsr;

			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 	
		});		
	<?php 
	# Botonoes que se verán cuando tengas permisos de edicion
	if($editar) { 
		
	?>				
		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Crear ranking',
			onClickButton:function(){

					location.href='<?php echo site_url('ranking/new_rank'); ?>';
						
			} 
		});		
		
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-mail-closed", title:'Notificar ranking creado',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					location.href='<?php echo site_url('ranking/notify'); ?>/'+gsr;

			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 	
		});		
				
		<?php 
			} // Fin del IF de edicion
			
			if($borrar) {
				
		?>
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-closethick", title:'Cancelar ranking',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					location.href='<?php echo site_url('ranking/cancel'); ?>/'+gsr;

			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 	
		});	
		
	<?php } // Fin del IF de borrado ?>	
				
		});
<?php

}

?>
 
</script>
