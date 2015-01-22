<?php

$this->lang->load('informes');

echo $search_fields;
echo '<div id="informes_result">'."\r\n";


//print("<p align='left'><pre>");print_r($resultado);print("</pre></p>");
echo '<p class="titulo">'.$this->lang->line('report_reserva_diaria').' del '.$filtros[0]['default'].' al '.$filtros[1]['default'].'</p>'."\r\n";
echo '<table cellpaddin=0 cellspacing=0 border=0 style="border: 0px none;"><tr><td style="border: 0px none;">'."\r\n";
if(isset($resultado) && count($resultado)>0) {
	$grafico=array();
	foreach($resultado as $pista => $reservas) {
		echo '<table cellpaddin=0 cellspacing=0><tr><td class="cabecera_informe" colspan="7" align="center">'.$pista.'</td></tr>';
		echo '<tr>';
			foreach($campos as $campo) {
				echo '<td class="cabecera_informe">'.$campo.'</td>';
			}
		echo '</tr>';
		
			foreach($reservas as $reserva) {
				echo '<tr><td>'.$reserva['id_user'].'</td><td>'.date($this->config->item('reserve_date_filter_format'), strtotime($reserva['fecha'])).'</td><td>'.$reserva['inicio'].'</td><td>'.$reserva['fin'].'</td><td>'.$reserva['user'].'</td><td>'.$reserva['phone'].'</td><td>'.$reserva['payment_way'].'</td></tr>';
			}
		if(!isset($grafico[$pista])) $grafico[$pista]=0;
		$grafico[$pista]+=$reserva['intervalos'];
		
		echo '</table>&nbsp;<br>';
	}
} else {
	echo 'No hay resultados';
}

if(isset($grafico)) {
	echo '</td></tr><tr><td style="border: 0px none;">'."\r\n";

	$campos=array_keys($grafico);
	$valores=array_values($grafico);
	$campo=implode('|', $campos);
	$valor=implode(',', $valores);
	$valor2=implode('|', $valores);
	$total=array_sum($valores);
	echo '<p align="center"><img src="http://chart.apis.google.com/chart?chs=450x400&cht=pc&chco=34A963&chd=s:Uf9a&&chds=0,'.$total.'&chd=t:'.$valor.'&chl='.$valor2.'&chdl='.$campo.'&chdlp=b&chtt=Distribuci&oacute;n+de+facturaci&oacute;n+diaria"></p>';
}
echo '</td></tr></table>'."\r\n";
?>
</div>
