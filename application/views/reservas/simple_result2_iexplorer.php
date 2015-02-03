<?php
	$this->lang->load('reservas');
?>
	<script type="text/javascript">
	$(function() {
		$("#accordion").accordion({
			autoHeight: false,
			navigation: true
		});
	$("#accordion").accordion("activate" , 1);
	});
	</script>	

<?php 
	echo '<table border="0">'."\r\n";
	echo '<tr>'."\r\n";
	echo '<td>'.img( array('src'=>'images/target.png', 'border'=>'0', 'alt' => 'Reservas', 'align'=>'left')).'</td><td valign="middle">'.$this->lang->line('welcome').', '.$user_name.'. '.$this->lang->line('reserve_index_text')."\r\n";
	echo '</td>'."\r\n";
	echo '</tr>'."\r\n";
	echo '</table>'; 
	
	$attributes = array('class' => 'frmReserva', 'id' => 'frmReserva');
	echo form_open(site_url('reservas/index/'.time()), $attributes);

	
	//if(isset($search_fields) && $search_fields!="") echo $search_fields;		
	
	//if(isset($result) && $result!="") echo $result;		
	
 ?>
 	
	





<div id="accordion" style="overflow:auto;">
	<h3><a href="#"><?php echo $this->lang->line('court_date_filters');?></a></h3>
	<div id="search_filters">
		
		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Si quiere reservar de nuevo, entre de nuevo en la secci&oacute;n de reservas o haga click <?php echo anchor('reservas', 'aqu&iacute;'); ?></p>  </div> </div>
		
	</div>
	
	<h3><a href="#"><?php echo $this->lang->line('interval_selection');?></a></h3>
	<div id="search_intervals" style="padding: 0.5em 1em;">


		
		<div id="search_result">
			<?php  
			//echo '<table border="0" width="100%"><tr><td valign="top">'.img( array('src'=>'images/reloj.png', 'border'=>'0', 'alt' => 'Selecciona la hora')).'</td><td valign="top" width="100%">';
			//echo '<table border="0" width="100%"><tr><td valign="top" width="100%">';
			
			echo '<p align="right">'.$this->lang->line('selected_date').': '.$date.'</p>'."\r\n";
			if(is_array($availability)) {
		
				# Calculo el maximo de intervalos de las pistas
				$max_cell=0;
				foreach($availability as $name => $avail) {
					$counter=0;
					foreach($avail as $code => $value) {
						$counter++;
					}
					if($counter > $max_cell) $max_cell=$counter;
				}  
				$cell_per_row=ceil($max_cell/2) +1;
				//echo $cell_per_row;
				
				foreach($availability as $name => $avail) {
					print('<div id="court_availability"><table id="availability"><tr>');
					echo '<td class="courtname" rowspan="2">'.$name.'</td>';
					$i=2;
					$cell_per_row=ceil(count($avail)/2) +1;
					foreach($avail as $code => $value) {
						$text = str_replace('-', '<br>', $value[4]);
						/*
						if(strstr($text, ':30')) $text = $this->config->item('half_hour_simbol');
						else $text = '&nbsp;'.substr($text, 0, 2).'&nbsp;';
						*/
						if($value[1]=="0") print('<td class="disable" >'.$text.'</td>');
						else print('<td class="free" id="'.$code.'" onClick="javascript: reservar(\''.$code.'\', \''.$user_id.'\', this, \''.time().'\');">'.$text.'</td>'."\r\n");
						if($i%$cell_per_row===0) {
							$i=1;
							echo '</tr><tr>'."\r\n";
						}
						$i++;
					}
					echo "</tr></table></div>";   
				}   	 
			}
			?>
		<table border="0" width="100%"><tr><td width="40%"><span id="coste">Coste de la reserva: sin coste</span><input type="hidden" name="price" id="numCoste" value="0">
			</td>
			
			
		<td width="100px">
			<?php
				//echo '</td></tr><tr><td valign="top">';
				$js = 'id="buttonConfirma" disabled ';
				echo form_button('buttonConfirma', 'Confirma tu reserva!', $js);
				//echo '</td></tr></table>'; 					
		
			?>		
		</td>
			</tr></table>
		
		</div>
		




 	</div>
	<h3><a href="#"><?php echo $this->lang->line('booking_extra_selection');?></a></h3>
	<div id="search_extra">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes seleccionar pista, fecha y hora.</p>
      </div>
    </div>
	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_selection');?></a></h3>
	<div id="search_payment">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes seleccionar pista, fecha y hora.</p>
      </div>
    </div>
	</div>
	<h3><a href="#"><?php echo $this->lang->line('payment_confirmation');?></a></h3>
	<div id="confirm_payment">
		<div class="ui-widget">
      <div class="ui-state-highlight ui-corner-all" > 
        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
        Primero debes confirmar la reserva.</p>
      </div>
    </div>
	</div>
</div>
<?php
	echo form_close();
?>
















<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!


	$("#buttonConfirma")
		.click( function() {
				
			var direccion2 =<?php echo "'".site_url('reservas/extras/'.time().'/'.$id_transaction);?>';

  		$("#frmReserva").attr("action", direccion2);
  		$("#frmReserva").submit();
			
		})
	});
				
function reservar(value, usuario, celda, dummy) {
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=300, height=300, top=85, left=140";
	var dt = new Date();
	var pagina='index.php?/reservas/preselect/'+usuario+'/'+value+'/'+dt.getTime();
	//window.open(pagina,"",opciones);
	//return;
//alert(pagina);
	var xmlhttp = createRequestObject();
	
	try{
		celda.className='processing';	
    xmlhttp.open("GET", pagina, true);
    xmlhttp.setRequestHeader('Content-Type',  "text/xml");
    xmlhttp.onreadystatechange = function () { handleReservar(xmlhttp)}
    
    
		xmlhttp.send(null);
	}
	catch(e){
		// caught an error
		alert('Request send failed.');
	}
	finally{
	}
	
}

function handleReservar(xmlhttp) {
	try{
    if((xmlhttp.readyState == 4)&&(xmlhttp.status == 200)){
    
    
    
    	var response = xmlhttp.responseXML.documentElement;
    	//alert(response);
    	
    	reserva=response.getElementsByTagName('reserva');
			//alert(reserva.length);
						
			for (i=0; i < reserva.length;i++)
			{ 
				//alert(reserva[i]);
				//alert(reserva[i].getElementsByTagName('estilo')[0].firstChild.nodeValue);
	    	var estilo = reserva[i].getElementsByTagName('estilo')[0].firstChild.nodeValue;
	    	var estado = reserva[i].getElementsByTagName('estado')[0].firstChild.nodeValue;
	    	var celda = reserva[i].getElementsByTagName('celda')[0].firstChild.nodeValue;
	    	var coste = reserva[i].getElementsByTagName('coste')[0].firstChild.nodeValue;
	    	var luz = reserva[i].getElementsByTagName('luz')[0].firstChild.nodeValue;
	
				//alert (estilo + ' - ' + estado + ' - ' + celda + ' - ' + coste);
				if(estado==1) {
					//alert(document.getElementById('numCoste').value +  '  ' + coste);
					var acumulado=format_number((1*document.getElementById('numCoste').value)+(1*coste),2);
					document.getElementById('numCoste').value=""+acumulado;
					if(document.getElementById('numCoste').value==0) {
						document.getElementById('coste').innerHTML='Sin seleccion';
						document.getElementById('buttonConfirma').disabled=true;
					}	else {
						document.getElementById('coste').innerHTML='Coste estimado de la reserva: <b>'+document.getElementById('numCoste').value+'</b> euros';
						document.getElementById('buttonConfirma').disabled=false;
					}
	
					document.getElementById(celda).className=estilo;
	
				}			//Process only element nodes

			  
			}		// Fin del FOR de las respuestass	
      
      
      
      
      
      
		}
  }
	catch(e){
		// caught an error
		alert('Response failed:'+e);
	}
	finally{}
}



// Formateo de numericos
function format_number(pnumber,decimals){
	if (isNaN(pnumber)) { return 0};
	if (pnumber=='') { return 0};
	
	var snum = new String(pnumber);
	var sec = snum.split('.');
	var whole = parseFloat(sec[0]);
	var result = '';
	
	if(sec.length > 1){
		var dec = new String(sec[1]);
		dec = String(parseFloat(sec[1])/Math.pow(10,(dec.length - decimals)));
		dec = String(whole + Math.round(parseFloat(dec))/Math.pow(10,decimals));
		var dot = dec.indexOf('.');
		if(dot == -1){
			dec += '.'; 
			dot = dec.indexOf('.');
		}
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	} else{
		var dot;
		var dec = new String(whole);
		dec += '.';
		dot = dec.indexOf('.');		
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	}	
	return result;
}
</script>