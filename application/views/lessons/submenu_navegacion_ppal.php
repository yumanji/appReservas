<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'calendar' => FALSE, 'lista' => FALSE, 'waiting_all' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	else $selected_option['calendar'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>
<li><a href="<?php echo site_url('lessons/index'); ?>" <?php if($selected_option['calendar']) echo 'class="actual"'; ?>>calendario</a></li>
<li><a href="<?php echo site_url('lessons/lista'); ?>" <?php if($selected_option['lista']) echo 'class="actual"'; ?>>listado</a></li>
<li><a href="<?php echo site_url('lessons/waiting_all'); ?>" <?php if($selected_option['waiting_all']) echo 'class="actual"'; ?>>lista espera</a></li>
</ul>
</div>