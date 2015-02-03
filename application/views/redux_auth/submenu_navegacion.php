<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'detail' => FALSE, 'calendar' => FALSE, 'assistants' => FALSE, 'waiting' => FALSE, 'reports' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	else $selected_option['detail'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('lessons/detail/'.$this->uri->segment(3)); ?>" <?php if($selected_option['detail']) echo 'class="actual"'; ?>>detalles</a></li>
<li><a href="<?php echo site_url('lessons/calendar/'.$this->uri->segment(3)); ?>" <?php if($selected_option['calendar']) echo 'class="actual"'; ?>>calendario</a></li>
<li><a href="<?php echo site_url('lessons/assistants/'.$this->uri->segment(3)); ?>" <?php if($selected_option['assistants']) echo 'class="actual"'; ?>>inscritos</a></li>
<li><a href="<?php echo site_url('lessons/waiting/'.$this->uri->segment(3)); ?>" <?php if($selected_option['waiting']) echo 'class="actual"'; ?>>lista de espera</a></li>
<li><a href="<?php echo site_url('lessons/reports/'.$this->uri->segment(3)); ?>" <?php if($selected_option['waiting']) echo 'class="actual"'; ?>>partes</a></li>
</ul>
</div>