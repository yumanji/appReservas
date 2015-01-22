<?php
	$this->lang->load('reservas');
	
	echo '<table border="0" cellpadding="5">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.$this->load->view('email/email_header', true).'</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'."\r\n";
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;">'."\r\n";
	echo $this->lang->line('mail_booking_intro_change_player').' <b>'. $info_antigua['booking_code'].'</b>'.$this->lang->line('mail_booking_intro2_change_player').' <b>'.$info['booking_code'].'</b>.<br>&nbsp;<br>'."\r\n";
	echo $this->lang->line('mail_booking_day').' '. date($this->config->item('reserve_date_filter_format'), strtotime($info['date'])).'.<br>'."\r\n";
	echo $this->lang->line('mail_booking_time').' '. $info['inicio'].'-'.$info['fin'].'.<br>'."\r\n";
	echo $this->lang->line('mail_booking_court').' '. $info['court'].'.<br>&nbsp;<br>'."\r\n";
	//echo 'El c&oacute;digo de su reserva es <b>'.$info['booking_code'].'</b>.<br>&nbsp;<br>'."\r\n";
	echo $this->lang->line('mail_booking_greetings_player').'<br>&nbsp;<br>'."\r\n";
	echo $this->config->item('club_name')."\r\n";

	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.$this->load->view('email/email_footer', true).'</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'."\r\n";
?>

