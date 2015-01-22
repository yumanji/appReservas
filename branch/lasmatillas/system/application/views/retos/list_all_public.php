<?php

if(isset($menu_lateral)) echo $menu_lateral;

if(isset($grid_code)) echo $grid_code;

?>
<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>

function jqGrid_ondblClickRow() {
	var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
	if(gsr){

		location.href='<?php echo site_url('retos/view'); ?>/'+gsr;

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
					location.href='<?php echo site_url('retos/view'); ?>/'+gsr;

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
