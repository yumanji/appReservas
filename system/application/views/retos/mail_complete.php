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
	  nos ponemos en contacto contigo para comunicarte que el siguiente reto al que estabas suscrito como participante.. ha sido completado!<br>&nbsp;<br>
	Reto para el '.$info['fecha'].' en la pista '.$info['court'].' para jugar entre las '.$info['inicio'].' y las '.$info['fin'].'. </br>&nbsp;<br>
	Si hubiera alguna incidencia nos pondremos en contacto contigo.<br>&nbsp;</br>&nbsp;<br>'."\r\n";
	echo $this->config->item('club_name')."\r\n";
	echo '</p>'."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_footer', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '</table>'."\r\n";
?>

