<div style="position:relative; width: 960px; height: 300px;">
<div style="position:absolute; top:0; right:0;">
	<?php
	
	if(!isset($default) || $default == "") $default = 'Escribir aqui';
	if(!isset($textbox_id) || $textbox_id == "") $textbox_id = 'textarea_id';
	if(!isset($cols) || $cols == "") $cols = '100';
	if(!isset($size) || $size == "") $size = '50';
	if(!isset($style) || $style == "") $style = 'width:60%';
	
	$data = array(
              'name'        => $textbox_id,
              'id'          => 'textarea_id',
              'value'       => $default,
              'cols'   => $cols,
              'size'        => $size,
              'style'       => $style,
            );

	echo form_textarea($data); 
	echo form_ckeditor(array('id'=>'textarea_id'));
?>
</div></div>