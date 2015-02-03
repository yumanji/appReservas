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

<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!

	
	$("#buttonConfirma")
		.click( function() {
				
			//var direccion2 =<?php echo "'".site_url('reservas/confirm2/'.time())."/'+document.getElementById('numLight').value+'/'+document.getElementById('allow_light').checked";?>;
			var direccion2 =<?php echo "'".site_url('reservas/extras/'.time().'/'.$this->session->userdata('idTransaction'));?>';
			//alert(direccion2);
			//return;
			//alert( $( "#accordion" ).accordion( "option", "animated" ));
			$("#accordion").accordion({ animated: 'slide' });
			$("#search_extra").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
			$("#accordion").accordion("activate" , 2);
			$.ajax({
				
			  url: direccion2,
			  success: function(data) {
			  	//alert(data);
			    $("#search_intervals").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda o confirmar la seleccion.</p>  </div> </div>');
			    //alert('Load was performed');
			    //$("#accordion").accordion("activate" , 2);
			    $("#search_extra").html(data);
			  },
			  error: function(data) {
			  	alert("error");
			  }
			});
			direccion2 =<?php echo "'".site_url('reservas/extras')."'";?>; // Reseteo variable
			
			
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


function handleReservar_original(xmlhttp) {
	try{
    if((xmlhttp.readyState == 4)&&(xmlhttp.status == 200)){
				//var response = xmlhttp.responseText;
				//celda.className=response;

    	var response = xmlhttp.responseXML.documentElement;
    	var estilo = response.getElementsByTagName('estilo')[0].firstChild.nodeValue;
    	var estado = response.getElementsByTagName('estado')[0].firstChild.nodeValue;
    	var celda = response.getElementsByTagName('celda')[0].firstChild.nodeValue;
    	var coste = response.getElementsByTagName('coste')[0].firstChild.nodeValue;
    	var luz = response.getElementsByTagName('luz')[0].firstChild.nodeValue;

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



			} else {
				alert('No se pudo realizar la reserva');
			}
				document.getElementById(celda).className=estilo;
			
    	// write out response
      //document.getElementById("returned_value").innerHTML =
      //'Returned: '+n+' ('+e+') Random: '+r;

      // re-enable the button
      //document.getElementById('go').disabled = false;
      //document.getElementById('go').value = "Submit";
      //document.getElementById('returned_value').style.display="";
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