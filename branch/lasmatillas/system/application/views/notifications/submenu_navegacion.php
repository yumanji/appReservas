<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'detail' => FALSE, 'sended' => FALSE, 'result' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	else $selected_option['detail'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('notifications/detail/'.$this->uri->segment(3)); ?>" <?php if($selected_option['detail']) echo 'class="actual"'; ?>>Detalle</a></li>
<li><a href="<?php echo site_url('notifications/sended/'.$this->uri->segment(3)); ?>" <?php if($selected_option['sended']) echo 'class="actual"'; ?>>Lista de emails</a></li>
<li><a href="<?php echo site_url('notifications/result/'.$this->uri->segment(3)); ?>" <?php if($selected_option['result']) echo 'class="actual"'; ?>>Resultados</a></li>
</ul>
</div>