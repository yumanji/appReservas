<?php
	
	echo '<table border="0" cellpadding="5">'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_header', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'."\r\n";
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">'."\r\n";
	echo 'Estimado '.$usuario.':<br />
	  Eres afortunado! Alguno de los participantes del siguiente reto (al que te suscribiste en la lista de espera) ha causado baja y has pasado a formar parte de los jugadores participantes.<br>&nbsp;<br>
	Te recordamos que el reto est&aacute; planificado para el pr&oacute;ximo '.$info['fecha'].' en la pista '.$info['court'].' para jugar entre las '.$info['inicio'].' y las '.$info['fin'].'. El nivel propuesto para los jugadores es de '.$info['low_player_level'].' a '.$info['high_player_level'].'.</br>&nbsp;<br>
	Si tuvieras alg&uacute; inconveniente para participar en el mismo, por favor, ponte en contacto con el club.<br>&nbsp;</br>&nbsp;<br>'."\r\n";
	echo $this->config->item('club_name')."\r\n";
	echo '</p>'."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_footer', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '</table>'."\r\n";
?>

