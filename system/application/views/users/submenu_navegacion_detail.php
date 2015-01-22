<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'profile' => FALSE, 'calendario' => FALSE, 'pagos' => FALSE, 'reservas' => FALSE, 'partidos' => FALSE, 'clases' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	else $selected_option['profile'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('users/detail/'.$this->uri->segment(3)); ?>" <?php if($selected_option['profile']) echo 'class="actual"'; ?>>Mis datos</a></li>
<li><a href="<?php echo site_url('users/reservas/'.$this->uri->segment(3)); ?>" <?php if($selected_option['reservas']) echo 'class="actual"'; ?>>Reservas</a></li>
<li><a href="<?php echo site_url('users/pagos/'.$this->uri->segment(3)); ?>" <?php if($selected_option['pagos']) echo 'class="actual"'; ?>>Pagos</a></li>
<li><a href="<?php echo site_url('users/clases/'.$this->uri->segment(3)); ?>" <?php if($selected_option['clases']) echo 'class="actual"'; ?>>Clases</a></li>
<li><a href="<?php echo site_url('users/partidos/'.$this->uri->segment(3)); ?>" <?php if($selected_option['partidos']) echo 'class="actual"'; ?>>Partidos</a></li>
<!--
<li><a href="#" <?php if($selected_option['calendario']) echo 'class="actual"'; ?>>calendario</a></li>
<li><a href="<?php echo site_url('users/reservas/'.$this->uri->segment(3)); ?>" <?php if($selected_option['reservas']) echo 'class="actual"'; ?>>reservas</a></li>
<li><a href="<?php echo site_url('users/pagos/'.$this->uri->segment(3)); ?>" <?php if($selected_option['pagos']) echo 'class="actual"'; ?>>pagos</a></li>
<li><a href="<?php echo site_url('users/calendario/'.$this->uri->segment(3)); ?>" <?php if($selected_option['calendario']) echo 'class="actual"'; ?>>calendario</a></li>
-->
</ul>
</div>