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
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-clipboard", title:'Ver detalle',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					location.href='<?php echo site_url('retos/detail_user/'.$code_user); ?>/'+gsr;

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
<script type="text/javascript">
	var identificador = '';
	function rowClick(celDiv, id){
	    
		$(celDiv).click(
	  function() { 
			$('#modificar_dialog').dialog('open');

				  }
	    )
	}
		

</script>


<!-- <div id="activar_dialog" title="Validar Reserva">
	<p>Est&aacute; seguro de querer validar la reserva?</p>
</div> -->
<input type="hidden" id="id_transaction" name="id_transaction" value="">
<input type="hidden" name="hora_inicio" id="hora_inicio" value=""/>
<input type="hidden" name="hora_fin" id="hora_fin" value="" />
<input type="hidden" name="text_cancel" id="text_cancel" value="" />

<table id="flex1" style="display:none"></table>
