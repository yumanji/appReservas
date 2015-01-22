<style type="text/css">
<!--
.ui-button { background: #DAE6F3 url(<?php echo base_url().'/images/luz_no.gif';?>) 50% 50% no-repeat; display: inline-block; position: relative; padding: 0; margin-right: .1em; text-decoration: none !important; cursor: pointer; text-align: center; zoom: 1; overflow: visible; }
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #DAE6F3; font-weight: bold; color: #2d588b; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus { border: 1px solid #2d588b; font-weight: bold; color: #ffffff; }
-->
</style>	
<?php  
	//echo '<table border="0" width="100%"><tr><td valign="top">'.img( array('src'=>'images/reloj.png', 'border'=>'0', 'alt' => 'Selecciona la hora')).'</td><td valign="top" width="100%">';
	//echo '<table border="0" width="100%"><tr><td valign="top" width="100%">';
	
	echo '<table border="0" width="100%">'."\r\n";
	echo '<tr>'."\r\n";
	foreach($options as $option => $value) {
		if($value) {
			switch($option) {
				case "light":
				
				break;
				
				default:
					echo '&nbsp;';
				break;				
			}
		}		
	}
	echo '<td>'."\r\n";
	echo '</td>'."\r\n";
	echo ''."\r\n";
	echo ''."\r\n";
	echo ''."\r\n";
	echo ''."\r\n";
	echo '<td class="courtname" rowspan="2">'.$name.'</td>';
	
?>
<table border="0" width="100%"><tr><td width="40%"><span id="coste">Coste de la reserva: sin coste</span><input type="hidden" name="price" id="numCoste" value="0">
	</td><td>

<input type="checkbox" name="allow_light" id="allow_light"><label for="allow_light" id="light_button" style="width: 25px; background-repeat: no-repeat; ">&nbsp;</label><span id="light_prev"> Solicitar luz </span><span id="light">(0&euro;)</span><input type="hidden" name="price_light" id="numLight" value="0"></td>
<td width="100px">
	<?php
		//echo '</td></tr><tr><td valign="top">';
		$js = 'id="buttonConfirma" disabled ';
		echo form_button('buttonConfirma', 'Confirma tu reserva!', $js);
		//echo '</td></tr></table>'; 					

	?>		
</td>
	</tr></table>


<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#allow_light" ).button();
	$("#allow_light").click( function() {
			//alert($('#light_button').css('background-image'));
			//window.alert( $('#allow_light').attr('checked')  );
			if($('#allow_light').attr('checked') == true) {
				$('#light_button').css('background-image','url(../images/luz.gif)');
				document.getElementById('light_prev').innerHTML=' Luz seleccionada ';
			}
			if($('#allow_light').attr('checked') == false) {
				$('#light_button').css('background-image','url(../images/luz_no.gif)');
				document.getElementById('light_prev').innerHTML=' Solicitar luz ';
			}
			//$('#light_button').css('background-image','url(../images/luz.gif)');
			})
	
	$("#buttonConfirma")
		.click( function() {
				
			var direccion2 =<?php echo "'".site_url('reservas/confirm2/'.time())."/'+document.getElementById('numLight').value+'/'+document.getElementById('allow_light').checked";?>;
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
			  }
			});
			direccion2 =<?php echo "'".site_url('reservas/confirm2')."'";?>; // Reseteo variable
			
			
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
					document.getElementById('coste').innerHTML='Coste de la reserva: <b>'+document.getElementById('numCoste').value+'</b> euros';
					document.getElementById('buttonConfirma').disabled=false;
				}

				var acum_luz=format_number((1*document.getElementById('numLight').value)+(1*luz),2);
				document.getElementById('numLight').value=""+acum_luz;
				if(document.getElementById('numLight').value==0) {
					document.getElementById('light').innerHTML=' ';
				}	else {
					document.getElementById('light').innerHTML='<span id="light">('+document.getElementById('numLight').value+'&euro;)</span>';
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