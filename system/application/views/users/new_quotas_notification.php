<?php
	
	echo '<table border="0" cellpadding="5">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.$this->load->view('email/email_header', true).'</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'."\r\n";
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">'."\r\n";
	echo 'Se han generado nuevas remesas bancarias en concepto de cuotas mensuales de abonado para los siguientes usuarios.<br>&nbsp;<br><ul>'."\r\n";
	foreach($usuarios as $usuario) { echo '<li>'.trim($usuario['first_name'].' '.$usuario['last_name']).' ('.$usuario['id'].')</li>'."\r\n"; }
	echo '</ul><br>Estan disponibles en el listado de Gestion -> Facturacion en las remesas pendientes.'."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.$this->load->view('email/email_footer', true).'</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'."\r\n";
?>

