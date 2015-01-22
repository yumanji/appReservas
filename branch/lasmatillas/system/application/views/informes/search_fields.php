<div id="informes_search_fields">
<?php
# Pintado de los filtros

//print("<pre>");print_r($search_fields);print("</pre>");

$js1 = 'class="reportSearchButton" style="background-image: url('.base_url().'images/refresh.png);" title="'.$this->lang->line('report_search').'" alt="'.$this->lang->line('report_search').'" ';
$js2 = 'class="reportSearchButton" style="background-image: url('.base_url().'images/pdf.png);" title="'.$this->lang->line('report_pdf').'" alt="'.$this->lang->line('report_search').'" ';
$js3 = 'class="reportSearchButton" style="background-image: url('.base_url().'images/excel.png);" title="'.$this->lang->line('report_excel').'" alt="'.$this->lang->line('report_search').'" onClick="javascript: document.getElementById(\'frmInforme\').action=\''.site_url($this->uri->uri_string().'/excel').'\'; document.getElementById(\'frmInforme\').submit();" ';
//echo form_submit('buttonSubmit', $this->lang->line('report_search'), $js);


echo '<fieldset>';
//echo '<legend>'.$this->lang->line('confirmation_reserved').nbs(2).form_submit('buttonSubmit', '', $js1).nbs(2).form_submit('buttonSubmit', '', $js2).nbs(2).form_button('buttonExcel', '', $js3).nbs(2).'</legend>';
echo '<legend>'.$this->lang->line('confirmation_reserved').nbs(2).form_submit('buttonSubmit', '', $js1).nbs(2).form_button('buttonExcel', '', $js3).nbs(2).'</legend>';
echo '<table border=0 width="100%"><tr>';
$i=1;
$time_array=array(
 "00:00:00"=>"0:00","00:30:00"=>"0:30","01:00:00"=>"1:00","01:30:00"=>"1:30","02:00:00"=>"2:00","02:30:00"=>"2:30",
 "03:00:00"=>"3:00","03:30:00"=>"3:30","04::00:00"=>"4:00","04::30:00"=>"4:30","05::00:00"=>"5:00","05::30:00"=>"5:30",
 "06:00:00"=>"6:00","06:30:00"=>"6:30","07:00:00"=>"7:00","07:30:00"=>"7:30","08:00:00"=>"8:00","08:30:00"=>"8:30",
 "09:00:00"=>"9:00","09:30:00"=>"9:30","10:00:00"=>"10:00","10:30:00"=>"10:30","11:00:00"=>"11:00","11:30:00"=>"11:30",
 "12:00:00"=>"12:00","12:30:00"=>"12:30","13:00:00"=>"13:00","13:30:00"=>"13:30","14:00:00"=>"14:00","14:30:00"=>"14:30",
 "15:00:00"=>"15:00","15:30:00"=>"15:30","16:00:00"=>"16:00","16:30:00"=>"16:30","17:00:00"=>"17:00","17:30:00"=>"17:30",
 "18:00:00"=>"18:00","18:30:00"=>"18:30","19:00:00"=>"19:00","19:30:00"=>"19:30","20:00:00"=>"20:00","20:30:00"=>"20:30",
 "21:00:00"=>"21:00","21:30:00"=>"21:30","22:00:00"=>"22:00","22:30:00"=>"22:30","23:00:00"=>"23:00","23:30:00"=>"23:30","23:59:00"=>"23:59"
);

foreach($search_fields as $field) {
	switch($field['type']) {
		case 'select':
			$extra='';
			if(isset($field['onchange']) && $field['onchange']!="") $extra.=' onChange="'.$field['onchange'].'" ';
			if(isset($field['id']) && $field['id']!="") $extra.=' id="'.$field['id'].'" ';
			if(isset($field['enabled']) && !$field['enabled']) { $extra.=' disabled '; $field['default']='';}
			echo '<td><label for="'.$field['name'].'">'.$field['desc'].':</label> '.form_dropdown($field['name'], $field['value'], $field['default'], $extra)."</td>\r\n";
		break;
		case 'time':
			$extra='';
			if(isset($field['onchange']) && $field['onchange']!="") $extra.=' onChange="'.$field['onchange'].'" ';
			if(isset($field['id']) && $field['id']!="") $extra.=' id="'.$field['id'].'" ';
			if(isset($field['enabled']) && !$field['enabled']) { $extra.=' disabled '; $field['default']='';}
			echo '<td><label for="'.$field['name'].'">'.$field['desc'].':</label> '.form_dropdown($field['name'], $time_array, $field['default'], $extra)."</td>\r\n";
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
									firstDay: 1 ";
			if(isset($field['maxdays']) && $field['maxdays']!="") echo "			,maxDate: '+".$field['maxdays']."D'";
			echo "		});
							});"."\r\n";
			echo $jquery_extra."\r\n";
			echo "	</script>"."\r\n";
			echo '<td><label for="'.$field['name'].'">'.$field['desc'].':</label> '.form_input($data)."</td>\r\n";
		break;
	}
	if($i%2==0) echo '</tr><tr>';
	
	$i++;
}
echo '</tr></table>';

		//echo form_submit('mysubmit', 'Registrarse!');
	
		echo '</fieldset>';
				
	?>
</div>