<?php

$this->lang->load('informes');

echo $search_fields;
echo '<div id="informes_result">'."\r\n";


//print("<p align='left'><pre>");print_r($resultado);print("</pre></p>");

echo '<p class="titulo">'.$this->lang->line('report_reserva_ocupacion').' del '.$filtros[0]['default'].' al '.$filtros[1]['default'].'</p>'."\r\n";
if(isset($resultado) && count($resultado)>0) {
echo '<table cellpaddin=0 cellspacing=0 border=0 style="border: 0px none;"><tr><td style="border: 0px none;">'."\r\n";

$grafico=array();
echo '<table cellpaddin=0 cellspacing=0>';
echo '<tr>';
	foreach($campos as $campo) {
		echo '<td class="cabecera_informe">'.$campo.'</td>';
	}
echo '</tr>';
//print_r($resultado);
$total_horas=0; $maximas_horas=0;$total_facturado=0;$total_facturable=0;
	foreach($resultado as $reserva) {
		echo '<tr><td>'.$reserva['name'].'</td><td>'.number_format($reserva['total_horas'],1,',', '.').'</td><td>'.number_format(($reserva['total_horas']*100/$reserva['maximo_horas']),1,',', '.').'% &nbsp; <img align="absmiddle" src="http://chart.apis.google.com/chart?chs=40x20&cht=gm&chd=t:'.($reserva['total_horas']*100/$reserva['maximo_horas']).'"></td><td>'.number_format($reserva['total_facturado'],2,',', '.').'</td><td>'.number_format($reserva['total_facturable'],2,',', '.').'</td></tr>';
		$grafico[$reserva['name']]=$reserva['total_horas'];
		$total_horas+=$reserva['total_horas'];
		$maximas_horas+=$reserva['maximo_horas'];
		$total_facturado+=$reserva['total_facturado'];
		$total_facturable+=$reserva['total_facturable'];
	}

	echo '<tr><td class="total">Total</td><td class="total">'.number_format($total_horas,1,',', '.').'</td><td class="total">'.number_format(($total_horas*100/$maximas_horas),1,',', '.').'%</td><td class="total">'.number_format($total_facturado,2,',', '.').'</td><td class="total">'.number_format($total_facturable,2,',', '.').'</td></tr>';
	echo '</table>&nbsp;<br>';
	echo '<h6>Nota: La columna "Total facturable" es la suma del coste de todas las reservas realizadas en el sistema, incluyendo las que se marcaron como "sin coste" de ah&iacute; que siempre ser&aacute; igual o mayor que la "Facturaci&oacute;n"..<h6>';

} else {
	echo 'No hay resultados';
}

if(isset($grafico) && count($grafico)) {
	echo '</td></tr><tr><td style="border: 0px none;">'."\r\n";
//print_r($grafico);

	$campos=array_keys($grafico);
	$valores=array_values($grafico);
	$campo=implode('|', $campos);
	$valor=implode(',', $valores);
	$valor2=implode('|', $valores);
	$total=array_sum($valores);
	echo '<p align="center"><img src="http://chart.apis.google.com/chart?chs=450x400&cht=pc&chco=34A963&chd=s:Uf9a&&chds=0,'.$maximas_horas.'&chd=t:'.$total_horas.','.($maximas_horas-$total_horas).'&chl='.number_format(($total_horas*100/$maximas_horas),1).'%|'.number_format((($maximas_horas-$total_horas)*100/$maximas_horas),1).'%&chdl=Usado|Libre&chdlp=b&chtt=Distribuci&oacute;n+de+ocupaci&oacute;n+de+pistas"></p>';
}
echo '</td></tr></table>'."\r\n";
?>
</div>
