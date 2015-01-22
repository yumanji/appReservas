<?php

	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'detail' => FALSE, 'assistants' => FALSE, 'rounds' => FALSE, 'matchs' => FALSE, 'assistance' => FALSE);
	
	# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(2) && isset($selected_option[$this->uri->segment(2)])) $selected_option[$this->uri->segment(2)] = TRUE;
	elseif($this->uri->segment(2) == 'match_detail') $selected_option['matchs'] = TRUE;
	else $selected_option['detail'] = TRUE;

?>
<!--nivel de navegacion 2 --> 
 <div class="navega2">
<ul>

<li><a href="<?php echo site_url('ranking/detail/'.$id); ?>" <?php if($selected_option['detail']) echo 'class="actual"'; ?>>Detalles</a></li>
<li><a href="<?php echo site_url('ranking/assistants/'.$id); ?>" <?php if($selected_option['assistants']) echo 'class="actual"'; ?>>Equipos</a></li>
<li><a href="<?php echo site_url('ranking/matchs/'.$id); ?>" <?php if($selected_option['matchs']) echo 'class="actual"'; ?>>Partidos</a></li>
</ul>
</div>