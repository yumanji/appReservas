<div id="main">
   <noscript>This site just doesn't work, period, without JavaScript</noscript>

   <!-- IF LOGGED IN -->

          <!-- Content here -->

   <!-- IF LOGGED OUT -->


</h1>
Vas a confirmar una reserva de <?php echo $this->app_common->IntervalToTime($info['intervals']); ?> por un importe total de <?php echo $info['total_price']; ?> euros.<br>
La información de la reserva es la siguiente:
<?php
//print_r($info['reserva']);
	print('<ul id="pistas">'."\r\n");
	foreach($info['reserva'] as $pista => $datos) {
		print('<li id="pista">'.$pista."\r\n");
		print('<ul id="intervalos">'."\r\n");
		foreach($datos as $dato) {
			print('<li id="intervalo">De '.$dato[0].' a '.$dato[1].' ('.$dato[2].'&euro;)'."\r\n");			
		}
		print('</ul>'."\r\n");
		print('</li>'."\r\n");
	}
	print('</ul>'."\r\n");

# Con las variables no_cost, pay y reserve .. pinto o no los diferentes elementos opcionales.. como si admito el check de reservar sin costo, el boton de reservar o el de pagar.
?>
</div>

