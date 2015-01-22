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



<br />

<?php

if(isset($grid_code)) echo $grid_code;

?>
<script type="text/javascript">

$(function() {
	jQuery("#grid_name").filterToolbar({stringResult: true, searchOnEnter: true, defaultSearch : "cn"});
	})
<?php

if(isset($enable_buttons) && $enable_buttons) {

?>

function jqGrid_ondblClickRow() {
	var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
	if(gsr){

		location.href='<?php echo site_url('lessons/detail'); ?>/'+gsr;

  	identificador = gsr;  
	  
	}
}

$(function() {
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-clipboard", title:'Ver detalle',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					location.href='<?php echo site_url('lessons/detail'); ?>/'+gsr;

			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
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

