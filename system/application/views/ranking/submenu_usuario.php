<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'partidos' => FALSE, 'equipos' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;


?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('ranking/equipos/'.$id); ?>" <?php if($selected_option['equipos']) echo 'class="actual"'; ?>>Equipos</a></li>
<li><a href="<?php echo site_url('ranking/partidos/'.$id); ?>" <?php if($selected_option['partidos']) echo 'class="actual"'; ?>>Partidos</a></li>
</ul>
</div>