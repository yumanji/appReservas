<?php 
	$this->lang->load('reservas');
	echo "	<script type=\"text/javascript\">"."\r\n";
	echo "var marca = 15;"."\r\n";
	echo "var enable_countdown = 1;"."\r\n";
	
	# Función que genera un string rellenado con x caracteres. Lo uso para generar la lista de puntos suspensivos que van decreciendo.
	echo "function strPad(i,l,s) {
						var o = i.toString();
						if (!s) { s = '0'; }
						while (o.length < l) {
							o = s + o;
						}
						return o;
					};"."\r\n";
	
	# Función del control de fecha de jQuery
	$max_days = "";
	if(isset($filtro_fecha) && $filtro_fecha!='') $max_days = " 	maxDate: '+".$filtro_fecha."D', ";
	
	echo "	$(function() {
						$(\"#date\").datepicker({
							showOn: 'button',
							buttonImage: '".base_url()."/images/calendar.gif',
							buttonImageOnly: true,
							dateFormat: 'dd-mm-yy',
							dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
							monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
							firstDay: 1,
							".$max_days."
							minDate: 0,
							onSelect: function(dateText, inst) {
									marca = 15;
									location.href = '".site_url('recepcion/index')."/'+dateText;
								}
							}
							
							);
					});"."\r\n";
		echo "	</script>"."\r\n";
					

	if(!isset($selected_date)) $selected_date = date($this->config->item('reserve_date_filter_format'));
	echo '<table width="100%" style="	border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #2d588b;"><tr><td><label for="date">'.$this->lang->line('select_date').':</label>'.form_input(array('name' => 'date', 'id' => 'date', 'value' => $selected_date)).'</td>'."\r\n";

	if(isset($title) && $title)  echo '<td align="center"><h3>'.$title.'</h3></td>';
	
	if(isset($refresh) && $refresh) {
	# Función que va pintando la cuenta atrás hasta el refresco de la pantalla	
	echo "<td>	<script type=\"text/javascript\">"."\r\n";
	echo "var auto_refresh2 = setInterval(
				function ()
				{
					if(enable_countdown == 1) {
						marca = marca - 1;
					
						$(\"#contador\").html(strPad('', marca, '.')+' ".$this->lang->line('next_refresh')."');
					}
				}, 3000);"."\r\n";					
	
		# Función que refresca la pantalla
		echo "var auto_refresh = setInterval(
					function ()
					{
						marca = 15;
						$(\"#search_result\").html('		<p align=\"center\">Loading....&nbsp;".img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"))."</p>');
						$('#search_result').load('".site_url('recepcion/grid')."/'+$(\"#date\").val()).fadeIn(\"slow\");
					}, 90000);"."\r\n";
		echo "	</script>"."\r\n";
		echo '<div id="contador">............... '.$this->lang->line('next_refresh').'</div></td>';
	}
		echo "	</tr></table>"."\r\n";
?>