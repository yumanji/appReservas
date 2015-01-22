<?php
	
	echo '<table border="0" cellpadding="5">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.$this->load->view('email/email_header', true).'</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'."\r\n";
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">'."\r\n";
	echo '<b>NO</b> se ha podido generar las remesas bancarias de las coutas mensuales de los siguientes usuarios (probablemente por faltarles datos bancarios).<br>&nbsp;<br><ul>'."\r\n";
	foreach($usuarios as $usuario) { echo '<li>'.$usuario['nombre'].' ('.$usuario['id'].')</li>'."\r\n"; }
	echo '</ul><br>'."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.$this->load->view('email/email_footer', true).'</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'."\r\n";
?>

