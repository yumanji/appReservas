<div id="gestion_search_fields">
<?php
# Pintado de los filtros

//print("<pre>");print_r($search_fields);print("</pre>");

$js1 = 'class="reportSearchButton" style="background-image: url('.base_url().'images/refresh.png);" title="'.$this->lang->line('report_search').'" alt="'.$this->lang->line('report_search').'" ';
$js2 = 'class="reportSearchButton" style="background-image: url('.base_url().'images/pdf.png);" title="'.$this->lang->line('report_pdf').'" alt="'.$this->lang->line('report_search').'" ';
$js3 = 'class="reportSearchButton" style="background-image: url('.base_url().'images/excel.png);" title="'.$this->lang->line('report_excel').'" alt="'.$this->lang->line('report_search').'" ';
//echo form_submit('buttonSubmit', $this->lang->line('report_search'), $js);

if(isset($search_fields) && count($search_fields)) {	
	echo '<fieldset>';
	echo '<legend>'.$this->lang->line('confirmation_reserved').nbs(2).form_submit('buttonSubmit', '', $js1).nbs(2).form_submit('buttonSubmit', '', $js2).nbs(2).form_submit('buttonSubmit', '', $js3).nbs(2).'</legend>';
	echo '<table border=0 width="100%"><tr>';
	$i=1;
	foreach($search_fields as $field) {
		switch($field['type']) {
			case 'select':
				$extra='';
				if(isset($field['onchange']) && $field['onchange']!="") $extra.=' onChange="'.$field['onchange'].'" ';
				if(isset($field['id']) && $field['id']!="") $extra.=' id="'.$field['id'].'" ';
				if(isset($field['enabled']) && !$field['enabled']) { $extra.=' disabled '; $field['default']='';}
				echo '<td><label for="'.$field['name'].'">'.$field['desc'].':</label> '.form_dropdown($field['name'], $field['value'], $field['default'], $extra)."</td>\r\n";
			break;
			case 'date':
				$data=array();
				$jquery_extra=' ';
				if(isset($field['name']) && $field['name']!="") $data['name']=$field['name'];
				if(isset($field['default']) && $field['default']!="") $data['value']=$field['default'];
				if(isset($field['onchange']) && $field['onchange']!="") $data['onChange']=$field['onchange'];
				if(isset($field['id']) && $field['id']!="") $data['id']=$field['id'];
				if(isset($field['enabled']) && !$field['enabled']) { $data['disabled']='true'; $data['value']=''; $jquery_extra=' $("#'.$field['id'].'").datepicker( "option", "disabled", true );';}
				
				echo "	<script type=\"text/javascript\">"."\r\n";
				echo "	$(function() {
									$(\"#".$field['id']."\").datepicker({
										showOn: 'button',
										buttonImage: '".base_url()."/images/calendar.gif',
										buttonImageOnly: true,
										dateFormat: 'dd-mm-yy',
										dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
										monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
										firstDay: 1
										";
				if(isset($field['maxdays']) && $field['maxdays']!="") echo "			,maxDate: '+".$field['maxdays']."D'";
				echo "		});
								});"."\r\n";
				echo $jquery_extra."\r\n";
				echo "	</script>"."\r\n";
				echo '<td><label for="'.$field['name'].'">'.$field['desc'].':</label> '.form_input($data)."</td>\r\n";
			break;
		}
		if($i%3==0) echo '</tr><tr>';
		
		$i++;
	}
	echo '</tr></table>';
	
			//echo form_submit('mysubmit', 'Registrarse!');
		
			echo '</fieldset>';
}				
	?>
</div>