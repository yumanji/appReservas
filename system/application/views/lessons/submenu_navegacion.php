<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'detail' => FALSE, 'calendar' => FALSE, 'assistants' => FALSE, 'waiting' => FALSE, 'erased' => FALSE, 'assistance' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	elseif($this->uri->segment(2)=='new_daily_report' || $this->uri->segment(2)=='detail_daily_report' || $this->uri->segment(2)=='recover_daily_report') $selected_option['assistance'] = TRUE;
	else $selected_option['detail'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('lessons/detail/'.$this->uri->segment(3)); ?>" <?php if($selected_option['detail']) echo 'class="actual"'; ?>>detalles</a></li>
<li><a href="<?php echo site_url('lessons/calendar/'.$this->uri->segment(3)); ?>" <?php if($selected_option['calendar']) echo 'class="actual"'; ?>>calendario</a></li>
<li><a href="<?php echo site_url('lessons/assistants/'.$this->uri->segment(3)); ?>" <?php if($selected_option['assistants']) echo 'class="actual"'; ?>>inscritos</a></li>
<li><a href="<?php echo site_url('lessons/waiting/'.$this->uri->segment(3)); ?>" <?php if($selected_option['waiting']) echo 'class="actual"'; ?>>lista de espera</a></li>
<li><a href="<?php echo site_url('lessons/erased/'.$this->uri->segment(3)); ?>" <?php if($selected_option['erased']) echo 'class="actual"'; ?>>bajas</a></li>
<li><a href="<?php echo site_url('lessons/assistance/'.$this->uri->segment(3)); ?>" <?php if($selected_option['assistance']) echo 'class="actual"'; ?>>partes</a></li>
</ul>
</div>