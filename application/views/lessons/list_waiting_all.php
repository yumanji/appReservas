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
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-clipboard", title:'Ver curso',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					location.href='<?php echo site_url('lessons/assistant_redirect/'); ?>/'+gsr;
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

	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;

	</script>

