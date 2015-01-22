<?php
	
	echo '<table border="0" cellpadding="5">'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_header', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'."\r\n";
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">'."\r\n";
	echo 'Estimado [#param_3#]:<br />
	  nos complace comunicarte que hemos creado un nuevo partido de '.$info['sport'].' con el que queremos retarte a demostrar tu nivel de juego frente a otros usuarios del club.<br />
	El partido est&aacute; planificado para el pr&oacute;ximo '.$info['fecha'].' en la pista '.$info['court'].' para jugar entre las '.$info['inicio'].' y las '.$info['fin'].'. El nivel propuesto para los jugadores es de '.$info['low_player_level'].' a '.$info['high_player_level'].'.</br>&nbsp;<br>
	&iquest;Te atreves a aceptar el reto? <a style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: 000;" href="'.$suscribe_url.'">S&iacute;, acepto el reto!</a><br>&nbsp;<br>'."\r\n";
	echo $this->config->item('club_name')."\r\n";
	echo '</p>'."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_footer', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '</table>'."\r\n";
?>

