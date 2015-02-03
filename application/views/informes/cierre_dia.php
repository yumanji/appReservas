<?php

$this->lang->load('informes');

//echo $search_fields;
echo '<div id="informes_result">'."\r\n";


//print("<p align='left'><pre>");print_r($resultado);print("</pre></p>");

echo '<p class="titulo">'.$this->lang->line('report_cierre_dia2').' del '.date($this->config->item('reserve_date_filter_format')).'</p>'."\r\n";
echo '<table cellpaddin=0 cellspacing=0 border=0 style="border: 0px none;"><tr><td style="border: 0px none;">'."\r\n";
if(isset($resultado) && count($resultado)>0) {
	$grafico=array();
	foreach($resultado as $forma_pago) {
		echo '<table cellpaddin=0 cellspacing=0><tr><td class="cabecera_informe" colspan="3">'.$forma_pago['tipo'].'</td></tr>';
		if(count($forma_pago['detalle'])>0) {
			# si detalle tiene elementos, cada uno es el resumen de una pista
			asort($forma_pago['detalle']);
			foreach($forma_pago['detalle'] as $pista) {
			echo '<tr><td width="70%">'.$pista['pista'].'</td><td>'.$pista['cantidad'].' '.$this->lang->line('hours').'</td><td align="right">'.number_format($pista['euros'],2).' '.$this->lang->line('currency').'</td></tr>';
				
				
				
			}
		} else 	echo '<tr><td colspan="3">Sin resultados</td></tr>';
		$grafico[$forma_pago['tipo']]=number_format($forma_pago['total_euro'],2);
			echo '<tr><td class="total">Total</td><td class="total">'.$forma_pago['total_cantidad'].' '.$this->lang->line('hours').'</td><td align="right" class="total">'.number_format($forma_pago['total_euro'],2).' '.$this->lang->line('currency').'</td></tr>';
		
		echo '</table>&nbsp;<br>';
	}
} else {
	echo 'No hay resultados';
}
echo '</td></tr><tr><td style="border: 0px none;">'."\r\n";

if(isset($grafico) && count($grafico)) {
	$campos=array_keys($grafico);
	$valores=array_values($grafico);
	$campo=implode('|', $campos);
	$valor=implode(',', $valores);
	//$valor2=implode('|', $valores2);
	$valor2=''; $valores2=array();
	foreach($grafico as $key => $value) array_push($valores2, $key.' - '.$value);
	$valor2=implode('|', $valores2);
	$total=array_sum($valores);
	echo '<p align="center"><img src="http://chart.apis.google.com/chart?chs=550x400&cht=pc&chco=34A963&chd=s:Uf9a&&chds=0,'.$total.'&chd=t:'.$valor.'&chl='.$valor2.'&chdl='.$campo.'&chdlp=b&chtt=Distribuci&oacute;n+de+facturaci&oacute;n+diaria"></p>';
}
echo '</td></tr></table>'."\r\n";
?>
</div>
