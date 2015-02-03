<?php
header("content-type: text/xml");
echo '<?xml version="1.0"?><respuesta>';
foreach($reservas as $reserva) {
	echo '<reserva><estado>'.$reserva['estado'].'</estado><estilo>'.$reserva['estilo'].'</estilo><celda>'.$reserva['id'].'</celda><coste>'.$reserva['coste'].'</coste><luz>'.$reserva['luz'].'</luz></reserva>';
}
echo '</respuesta>';
die();
?>