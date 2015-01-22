<?php
	
	echo '<table border="0" cellpadding="5">'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_header', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'."\r\n";
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">'."\r\n";
	echo 'Estimado [#param_3#]:<br />&nbsp;<br />
	  nos complace comunicarte la lista de los retos que tenemos activos en este momento.<br />Esperamos poder contar con tu presencia en alguno de ellos.<br />&nbsp;<br />'."\r\n";
	echo '<table cellpadding="4" width="100%" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; border: 1px solid #000; border-collapse: collapse;">'."\r\n";
	echo '<tr><td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; background-color: #BEA; font-weight: bold; text-align: center; border: 1px solid #000;">Lista de partidos activos a '.date('d-m-Y').'</td></tr>'."\r\n";
	foreach($resumen_retos as $reto) {
		echo '<tr>'."\r\n";
		echo '<td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; text-align: center; border: 1px solid #000; ">'."\r\n";
		echo $reto."\r\n";
		echo '</td>'."\r\n";
		echo '<tr>'."\r\n";
	}
	echo '</table>'."\r\n";
	echo '&nbsp;<br />'.$this->config->item('club_name')."\r\n";
	echo '</p>'."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr><td>'."\r\n";
	echo $this->load->view('email/email_footer', true)."\r\n";
	echo '</td></tr>'."\r\n";
	echo '</table>'."\r\n";
?>

