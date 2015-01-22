<div id="search_fields">
<?php
# Pintado de los filtros

//print("<pre>");print_r($search_fields);print("</pre>");
echo '<table border="0" width="100%" cellpadding="5"><tr><td valign="top">'.img( array('src'=>'images/calendar.png', 'border'=>'0', 'alt' => 'Selecciona pista y fecha')).'</td><td valign="top" width="100%">';
echo '<ol>';
foreach($search_fields as $field) {
	echo '<li>';
	switch($field['type']) {
		case 'select':
			$extra='';
			if(isset($field['onchange']) && $field['onchange']!="") $extra.=' onChange="'.$field['onchange'].'" ';
			if(isset($field['id']) && $field['id']!="") $extra.=' id="'.$field['id'].'" ';
			if(isset($field['enabled']) && !$field['enabled']) { $extra.=' disabled '; $field['default']='';}
			echo '<label for="'.$field['name'].'">'.$field['desc'].':</label>'.form_dropdown($field['name'], $field['value'], $field['default'], $extra);
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
									constrainInput: true,
									dateFormat: 'dd-mm-yy',
									dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
									monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
									firstDay: 1,
									minDate: 0, 
									maxDate: '+".$field['maxdays']."D'		}
									
									);
							});"."\r\n";
			echo $jquery_extra."\r\n";
			echo "	</script>"."\r\n";
			echo '<label for="'.$field['name'].'">'.$field['desc'].':</label>'.form_input($data)."\r\n";
		break;
	}
	echo '</li>';
}
echo '</ol>';

echo '</td><td valign="absmiddle">';
		//echo form_submit('mysubmit', 'Registrarse!');
		//$js = 'id="buttonSubmit" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url('reservas/search').'\'; buscar(); "';
		//if($disabled!="") $js.=$disabled;
		//echo form_button('buttonSubmit', $this->lang->line('court_search'), $js);
					
$js = 'id="buttonSubmit" class="busquedaPista"';
if($disabled!="") $js.=$disabled;
//echo form_button('buttonSubmit', $this->lang->line('court_search'), $js);
echo form_button('buttonSubmit', 'Buscar', $js);
					
echo '</td></tr></table>'; 					
	?>
</div>
				<script type="text/javascript">
					var direccion =<?php echo "'".site_url('reservas/search2')."'";?>;
				$(function() {
					// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
					$("#buttonSubmit")
						.click( function() {
							var sport=document.getElementById('sports').options[document.getElementById('sports').selectedIndex].value;
							if(sport=='') sport='null';
							var court_type=document.getElementById('court_type').options[document.getElementById('court_type').selectedIndex].value;
							if(court_type=='') court_type='null';
							var court=document.getElementById('court').options[document.getElementById('court').selectedIndex].value;
							if(court=='') court='null';
							var fecha=document.getElementById('date').value;
							if(fecha=='') fecha='null';
							direccion=direccion+'/'+fecha+'/'+court+'/'+sport+'/'+court_type+'/'+<?php echo time();?>;
							//alert(direccion);
							//return;
							//alert( $( "#accordion" ).accordion( "option", "animated" ));
			    		$("#frmReserva").attr("action", direccion);
			    		$("#frmReserva").submit();
							
							
						});
						
						$("#accordion").accordion("activate" , 0);
					});
				

				</script>