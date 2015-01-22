<div id="informes_result">
<?php
# Pintado de los filtros

//print("<pre>");print_r($search_fields);print("</pre>");

echo '<table cellpaddin=0 cellspacing=0><tr>';
$i=0;
foreach($campos as $cabecera) {
	echo '<td class="cabecera_informe">'.$cabecera.'</td>';
	$i++;
	
}

echo '</tr><tr>';

if(isset($resultado) && count($resultado)>0) {
	foreach($resultado as $registro) {
		foreach($registro as $campo) {
			echo '<td>'.$campo.'</td>';
		}
		echo '</tr><tr>';
	}
} else {
	echo '<td colspan="'.$i.'" align="center">No hay resultados</td>';
}
echo '</tr></table>';


?>
</div>