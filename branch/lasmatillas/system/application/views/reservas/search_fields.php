<div id="search_fields">
<?php
# Pintado de los filtros

//print("<pre>");print_r($search_fields);print("</pre>");

foreach($search_fields as $field) {
	switch($field['type']) {
		case 'select':
			$extra='';
			if(isset($field['onchange']) && $field['onchange']!="") $extra.=' onChange="'.$field['onchange'].'" ';
			if(isset($field['id']) && $field['id']!="") $extra.=' id="'.$field['id'].'" ';
			if(isset($field['enabled']) && !$field['enabled']) { $extra.=' disabled '; $field['default']='';}
			echo $field['desc'].': '.form_dropdown($field['name'], $field['value'], $field['default'], $extra);
		break;
		case 'date':
			$data=array();
			$jquery_extra=' ';
			if(isset($field['name']) && $field['name']!="") $data['name']=$field['name'];
			if(isset($field['default']) && $field['default']!="") $data['value']=$field['default'];
			if(isset($field['onchange']) && $field['onchange']!="") $data['onChange']=$field['onchange'];
			if(isset($field['id']) && $field['id']!="") $data['id']=$field['id'];
			if(isset($field['enabled']) && !$field['enabled']) { $data['disabled']='true'; $data['value']=''; $jquery_extra=' $("#'.$field['id'].'").datepicker( "option", "disabled", true );';}

			$data['readonly'] = 'true';
			
			echo "	<script type=\"text/javascript\">"."\r\n";
			echo "	$(function() {
								$(\"#".$field['id']."\").datepicker({
									showOn: 'button',
									buttonImage: '".base_url()."/images/calendar.gif',
									buttonImageOnly: true,
									dateFormat: 'yy-mm-dd',
									minDate: 0, 
									maxDate: '+".$field['maxdays']."D'		});
							});"."\r\n";
			echo $jquery_extra."\r\n";
			echo "	</script>"."\r\n";
			echo $field['desc'].': '.form_input($data)."\r\n";
		break;
	}
}

		//echo form_submit('mysubmit', 'Registrarse!');
		$js = 'id="buttonSubmit" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url('reservas/search').'\'; buscar(); "';
		if($disabled!="") $js.=$disabled;
		echo form_button('buttonSubmit', $this->lang->line('court_search'), $js);
					
	?>
</div>