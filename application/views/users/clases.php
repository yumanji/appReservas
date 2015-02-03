
<?php
$data = array(
              'returnUrl'  => current_url(),
              'id_user' => $code_user
            );

echo form_hidden($data);

if(isset($menu_lateral)) echo $menu_lateral;


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
