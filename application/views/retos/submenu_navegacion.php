<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'detail' => FALSE, 'players' => FALSE, 'waiting' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	elseif($this->uri->segment(2) == 'add_player') $selected_option['players'] = TRUE;
	else $selected_option['detail'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>
<li><a href="<?php echo site_url('retos/detail/'.$id); ?>" <?php if($selected_option['detail']) echo 'class="actual"'; ?>>detalle</a></li>
<li><a href="<?php echo site_url('retos/players/'.$id); ?>" <?php if($selected_option['players']) echo 'class="actual"'; ?>>jugadores</a></li>
<li><a href="<?php echo site_url('retos/waiting/'.$id); ?>" <?php if($selected_option['waiting']) echo 'class="actual"'; ?>>lista de espera</a></li>
</ul>
</div>