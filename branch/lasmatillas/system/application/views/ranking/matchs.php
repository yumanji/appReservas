<?php

if(isset($menu_lateral)) echo $menu_lateral;

$options = array();
//Print(count($info['rondas'])."<pre>");print_r($info);
/*
for($i=1; $i <= count($info['rondas']); $i++) {
	$options[$i] = 'Jornada '.$i;
}
*/
foreach($info['rondas'] as $ronda) {
	$options[$ronda['id']] = 'Jornada '.$ronda['round'];
	if($ronda['round']==$info['current_round']) break;
}
//print($ronda_visualizar."<pre>");print_r($options);

$attributes = array('id' => 'partidos');
if(!isset($form_action) || $form_action == '') $form_action = 'ranking/matchs/';
echo form_open($form_action.$info['id'], $attributes);

$js = 'id="round_selector"';
echo 'Seleccione jornada: '.form_dropdown('round', $options, $ronda_visualizar, $js);
echo form_close();

if(isset($grid_code)) echo $grid_code;

?>
<script type="text/javascript">

		
	$('#round_selector').change(function() {
		$('#partidos').submit();
	});
<?php

if(isset($enable_buttons) && $enable_buttons) {

?>

function jqGrid_ondblClickRow() {
	var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
	if(gsr){

		location.href='<?php echo site_url('ranking/match_detail'); ?>/'+gsr;

  	identificador = gsr;  
	  
	}
}

$(function() {


		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-calendar", title:'Grabar resultado del partido',
			onClickButton:function(){
				var gsr = jQuery("#grid_name").jqGrid('getGridParam','selrow');
				if(gsr){
					//alert(gsr);
					
					/*
					document.getElementById('hora_inicio_view').value = horaInicio;
					document.getElementById('hora_fin_view').value = horaFin;
					*/
					location.href='<?php echo site_url('ranking/match_detail'); ?>/'+gsr;

			  	identificador = gsr;  
				  
				} else {
					alert("Seleccione un partido.")
				}							
			} 	
		});


	<?php 
	# Botonoes que se verán cuando tengas permisos de edicion
	if(isset($editar) && $editar) { 
		
	?>				
		
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plusthick", title:'Crear ranking',
			onClickButton:function(){

					location.href='<?php echo site_url('ranking/new'); ?>';
						
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
			
			if(isset($borrar) && $borrar) {
				
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
