<?php

if(isset($menu_lateral)) echo $menu_lateral;


if(isset($grid_code)) echo $grid_code;

?>
<script type="text/javascript">


<?php

if(isset($enable_buttons) && $enable_buttons) {

?>
$(function() {
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-circle-zoomin", title:'Ver detalle',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					location.href='<?php echo site_url('notifications/detail'); ?>/'+gsr;
				  
				  
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
