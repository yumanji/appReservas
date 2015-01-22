<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'reservas_gest' => FALSE, 'users' => FALSE, 'facturacion' => FALSE, 'retos' => FALSE, 'ranking' => FALSE, 'lessons' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(1) && isset($selected_option[$this->uri->segment(1)])) $selected_option[$this->uri->segment(1)] = TRUE;
	else $selected_option['detail'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('reservas_gest/list_all'); ?>" <?php if($selected_option['reservas_gest']) echo 'class="actual"'; ?>>Reservas</a></li>
<li><a href="<?php echo site_url('facturacion/list_all'); ?>" <?php if($selected_option['facturacion']) echo 'class="actual"'; ?>>Facturacion</a></li>
<li><a href="<?php echo site_url('users'); ?>" <?php if($selected_option['users']) echo 'class="actual"'; ?>>Usuarios</a></li>
<li><a href="<?php echo site_url('lessons'); ?>" <?php if($selected_option['lessons']) echo 'class="actual"'; ?>>Clases</a></li>
<li><a href="<?php echo site_url('retos'); ?>" <?php if($selected_option['retos']) echo 'class="actual"'; ?>>Retos</a></li>
<li><a href="<?php echo site_url('ranking'); ?>" <?php if($selected_option['ranking']) echo 'class="actual"'; ?>><?php echo $this->lang->line('ranking_name'); ?></a></li>
</ul>
</div>