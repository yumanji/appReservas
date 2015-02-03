<?php
	echo "	<script type=\"text/javascript\">"."\r\n";
	
	# Función del control de fecha de jQuery
	echo "	$(function() {
						$(\"#date\").datepicker({
							showOn: 'button',
							buttonImage: '".base_url()."/images/calendar.gif',
							buttonImageOnly: true,
							dateFormat: 'dd-mm-yy',
							dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
							monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
							firstDay: 1,
							minDate: 0,
							onSelect: function(dateText, inst) {
									$(\"#search_result\").html('		<p align=\"center\">Loading....&nbsp;".img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"))."</p>');
									location.href = '".site_url('recepcion/selection')."/".$id_transaction."/'+dateText;
								}
							}
							
							);
					});"."\r\n";
		echo "	</script>"."\r\n";
					

	if(!isset($selected_date)) $selected_date = date($this->config->item('reserve_date_filter_format'));
	echo '<table width="100%"><tr><td><label for="date">'.$this->lang->line('select_date').':</label>'.form_input(array('name' => 'date', 'id' => 'date', 'value' => $selected_date)).'</td>'."\r\n";

	echo '<td align="center"><h3>Seleccione una nueva reserva</h3></td>';
	
	echo "	</tr></table>"."\r\n";
?>		
<input type="hidden" id="id_transaction" name="id_transaction">
   
<?php  
//echo '<p align="right">'.$this->lang->line('selected_date').': '.$date.'</p>'."\r\n";
if(is_array($availability)) {
	
	//print("<pre>");print_r($availability);
	
	# Calculo el maximo de intervalos de las pistas
	$max_cell=0;
	foreach($availability as $name => $avail) {
		$counter=0;
		foreach($avail as $code => $value) {
			$counter++;
		}
		if($counter > $max_cell) $max_cell=$counter;
	}  
	$cell_per_row=ceil($max_cell/2) +1;
	//echo $cell_per_row;
	
	print('<div id="court_availability"><table id="availability">');
	foreach($availability as $name => $avail) {
		$colspan=0; $id_trans=''; $init_text = ''; $estado='';
		
		print('<tr>');
		echo '<td class="courtname">'.$name.'</td>';
		$i=2;
		foreach($avail as $code => $value) {
			$text = str_replace('-', '<br>', $value[4]);
			//print('<pre>');print_r($value);
			/*
			if(strstr($text, ':30')) $text = $this->config->item('half_hour_simbol');
			else $text = '&nbsp;'.substr($text, 0, 2).'&nbsp;';
			*/
				if($id_trans != $value[2] && $id_trans != '') {
					# Si es diferente id_transaction que el anterior.. pinto la celda acumulada y reinicio variables
					
					switch($estado) {
						case '9':
						case '8':
							$class='payd';
						break;
						case '7':
							$class='reserved';
						break;
						default:
							$class='full';
						break;
					}
						#Celdas 'sin coste'
						if(isset($nocost) && $nocost == '1' ) $class = 'nocost';
						
						#Celdas de retos
						if(isset($shared) && $shared == '1' ) $class = 'shared';
						

					print('<td class="'.$class.'" colspan="'.$colspan.'" id="'.$id_trans.'">'.$init_text.'</td>');
					
					$colspan=0;
					$init_text = $value[4];
					//$text = '';
					$id_trans = $value[2];
				} 

			if($value[1]=="0" && $value[2]!='') {
				# Si la pista está ocupada.. proceso otros parámetros
				
				
				if($init_text == '') $init_text = $value[4];
				$id_trans = $value[2];
					$nocost = $value[6];
					$shared = $value[5];
				$estado = $value[3];
				$colspan++;

				
				
			} elseif($value[1]=="0" && $value[2] == '' ) {
				print('<td class="disable" id="0">'.$value[4].'</td>');
				$id_trans=''; $init_text = ''; $estado='';
			}
			else {
				$datos=explode('-', $code);
				print('<td class="free" id="'.$code.'" onClick="javascript: seleccionar(\''.$datos[0].'\', fecha, \''.$value[0].'\',  \''.$id_transaction.'\');">'.$text.'</td>'."\r\n");
				$id_trans=''; $init_text = ''; $estado='';
			}
			
			$i++;
		}
		echo "</tr>";   
	}   	 
	echo "</table></div>";   
}
?>
	
<script type="text/javascript">
	var fecha = $("#date").val();	
	function seleccionar(pista, fecha, intervalo, transaccion, dummy) {
		var resultado = $.ajax({
					  url: "<?php echo site_url('reservas_gest/change_reserve_get'); ?>/"+transaccion+"/"+fecha+"/"+intervalo+"/"+pista+"/<?php echo time();?>",
					  async: false
					 }).responseText;	
		if(resultado=="1") location.href="<?php echo site_url('recepcion/index/'.$date); ?>";
		else alert("Error en el cambio de fecha y hora. Seleccione otra opci&oacute;n");
	}
</script>