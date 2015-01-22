<?php 
echo '<fieldset>'."\r\n";
echo '<legend>Proximas reservas</legend>'."\r\n";
$i = 0;
if(count($registros)>0) {
	foreach($registros as $registro) {
		if($i<7) {
			echo $this->load->view('reservas/tooltip_info', array('info' => $registro), TRUE);
			echo '<hr>'."\r\n";
			echo ''."\r\n";
		}
		$i++;
	}
} else {
	echo '<p align="center">No se encontraron</p>';
}
//print("<pre>");print_r($registros);
echo '</fieldset>'."\r\n";
?>