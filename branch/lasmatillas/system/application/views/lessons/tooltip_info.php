<?php
	//$this->lang->load('lessons');
	
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);
	
	//echo '<div id="reserve_resume">'."\r\n";
	echo '<p align="center">'."\r\n";
	echo 'Reserva para curso <b>'.$info->description.'</b><br>Profesor: '.$info->first_name.' '.$info->last_name.'<br>'."\r\n";
	if($info->current_vacancies != 0) echo 'Hay '.$info->current_vacancies.' plazas disponibles de '.$info->max_vacancies."\r\n"; else echo 'No hay plazas disponibles'."\r\n";
	
	echo '</p>'."\r\n";
?>

