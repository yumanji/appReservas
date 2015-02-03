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
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Nuevo parte diario',
			onClickButton:function(){
					location.href='<?php echo site_url('lessons/new_daily_report/'.$id_transaction); ?>/';
  
						
			} 
		});		

		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-clipboard", title:'Editar parte diario',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					location.href='<?php echo site_url('lessons/detail_daily_report/'); ?>/'+gsr;
			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		

		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-arrowrefresh-1-n", title:'Recuperar clase',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					location.href='<?php echo site_url('lessons/recover_daily_report/'); ?>/'+gsr;
			  	identificador = gsr;  
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});		

		
		<?php if($admincheck) {  ?>
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-check", title:'Confirmar realizacion',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
			  	identificador = gsr;  
			  	$('#admincheck_dialog').dialog('open');
				  
				} else {
					alert("Please select Row")
				}							
			} 
		});
	<?php } ?>




		$('#admincheck_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Confirmar': function() {
					//modificar para que mantenga el filtro
					document.getElementById('frmAdmin').action='<?php echo site_url('lessons/save_assistance/'.$id_transaction);?>/'+identificador;
					document.getElementById('frmAdmin').submit();
					$(this).dialog('close');
				},
				'Cerrar': function() {
					$(this).dialog('close');
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

<div id="admincheck_dialog" title="Confirmar realizacion">
	<p>Confirma los datos del parte de la clase.</p>
	<form action="" method="post" name="frmAdmin" id="frmAdmin">
		<input type="hidden" name="action" value="admin_obs">
		Observaciones: <input type="text" name="admin_obs" id="admin_obs" value="">
	</form>
</div>