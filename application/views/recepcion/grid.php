<input type="hidden" id="id_transaction" name="id_transaction">
<script type="text/javascript">
$(document).ready(function() {
	$(".payd,.nocost").mousedown(function(e) {
	    if (e.which === 3) {
				$("#myMenu").disableContextMenuItems("#pay,#light");
	    }
	});
	
	$(".shared").mousedown(function(e) {
	    if (e.which === 3) {
				$("#myMenu").disableContextMenuItems("#pay,#edit");
	    }
	});
	
	$(".reserved").mousedown(function(e) {
	    if (e.which === 3) {
				$("#myMenu").enableContextMenuItems("#light");
	    }
	});
	
	$(".full").mousedown(function(e) {
	    if (e.which === 3) {
				$("#myMenu").disableContextMenuItems();
				$("#myMenu").enableContextMenuItems("#delete,#light");
	    }
	});
	
	$(".disable").mousedown(function(e) {
	    if (e.which === 3) {
				$("#myMenu").enableContextMenuItems();
				$("#myMenu").disableContextMenuItems("#light");
	    }
	});
  
 
  $(".full").contextMenu({ menu: 'myMenu' }, function(action, el, pos) {         
		contextMenuWork(action, el, pos); 
	});
  $(".reserved").contextMenu({ menu: 'myMenu' }, function(action, el, pos) {         
		contextMenuWork(action, el, pos); 
	});
  $(".disable").contextMenu({ menu: 'myMenu' }, function(action, el, pos) {         
		contextMenuWork(action, el, pos); 
	});
  $(".payd,.shared,.nocost").contextMenu({ menu: 'myMenu' }, function(action, el, pos) {         
		contextMenuWork(action, el, pos); 
	});
});

  function contextMenuWork(action, el, pos) {
  	
			$('#id_transaction').val($(el).attr('id'));
			
      switch (action) {
          case "delete":
              {
								$('#cancelar_dialog').dialog('open');
                break;
              }
          case "light":
              {
                  //var msg = "Delete " + $(el).find("#contactname").text() + "?";
                  //$("#HiddenFieldRowId").val($(el).find("#customerid").text());
                  //confirm(msg);
                  alert('Deshabilitado temporalmente. Debera activar la luz desde Gestion->Reservas');
                  break;
              }
          case "edit":
              {
								location.href="<?php echo site_url('recepcion/selection'); ?>/"+$('#id_transaction').val()+"/"+$('#date').val();
                break;
              }

          case "pay":
              {
							// Acción a ejecutar al pulsar sobre la opción de 'pagar' en el menú contextual
							
								// Con esto desactivo la cuenta atrás de refresco de pantalla
								enable_countdown = 0;
								
								$('#proceso_reserva_dialog').dialog('open');
								var direccion2 = '<?php echo site_url('reservas/confirm2/'.time());?>/'+$('#id_transaction').val()+'/0/1';
								//alert(direccion2);
								//return;
								//alert( $( "#accordion" ).accordion( "option", "animated" ));
								$("#accordion").accordion({ animated: 'slide' });
								$("#search_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
								$("#accordion").accordion("activate" , 3);
								$.ajax({				
								  url: direccion2,
								  success: function(data) {
								  	//alert(data);
								    $("#search_extra").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda o confirmar la seleccion.</p>  </div> </div>');
								    $("#search_payment").html(data);
										//$("#accordion").accordion("activate" , 2);
								  }
								});
								direccion2 = <?php echo "'".site_url('reservas/confirm2')."'";?>; // Reseteo variable

              }
      }

  }

<?php
	# Función del control de fecha de jQuery
	
	$max_days = "";
	if(isset($filtro_fecha) && $filtro_fecha!='') $max_days = " 	maxDate: '+".$filtro_fecha."D', ";
	
	echo " $(function() {
						$(\"#date_view\").datepicker({
							showOn: 'button',
							buttonImage: '".base_url()."/images/calendar.gif',
							buttonImageOnly: true,
							dateFormat: 'dd-mm-yy',
							dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
							monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
							firstDay: 1,
							minDate: 0,
							".$max_days."
							}
							
							);
					});"."\r\n";
?>
</script>    

	<?php  
	//echo '<table border="0" width="100%"><tr><td valign="top">'.img( array('src'=>'images/reloj.png', 'border'=>'0', 'alt' => 'Selecciona la hora')).'</td><td valign="top" width="100%">';
	//echo '<table border="0" width="100%"><tr><td valign="top" width="100%">';
	
	//echo '<p align="right">'.$this->lang->line('selected_date').': '.$date.'</p>'."\r\n";
	if(is_array($availability)) {
		
		//print("<pre>");print_r($availability);
		
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
		$ancho_disponible = 660; // Resultado de restar de la resolucion total (en este caso 1000) los 220 del menu lateral y los 140 del nombre de pista.. más algo de margen)
		
		$ancho_celda = ceil($ancho_disponible / $max_cell);
		
		print('<div id="court_availability"><table id="availability" align="center">');
		
		# Pinto una fila inicial de celdas vacías para fijar el ancho de cada casilla
		print('<tr>');
		echo '<td class="courtname"></td>';
		foreach($avail as $code => $value) { echo '<td style="border: 1px none #FFF;" width="'.$ancho_celda.'px"></td>';}
		print('</tr>');
		
		foreach($availability as $name => $avail) {
			$colspan=0; $id_trans=''; $init_text = ''; $estado='';
			
			
			print('<tr>');
			echo '<td class="courtname">'.$name.'</td>';
			$i=2; $contador = 1;
			
			
			foreach($avail as $code => $value) {
				$ultima_pintada = '';
				/*
				if(isset($value[0])) $text = $value[0];
				else $text = '-';
				*/
				$text = str_replace('-', '<br>', $value[4]);
				
				/*
				if(strstr($text, ':30')) $text = $this->config->item('half_hour_simbol');
				else $text = '&nbsp;'.substr($text, 0, 2).'&nbsp;';
				*/
					# Entro a pintar si el id_trans ha cambido.. o si, aunque no haya cambiado, es la última celda de la linea y estoy pintando una reserva.
					if(($id_trans != $value[2] || $contador == count($avail)) && $id_trans != '') {
						# Si es diferente id_transaction que el anterior.. pinto la celda acumulada y reinicio variables
						//echo $value[2];
						switch($estado) {
							case '9':
							case '8':
								$class='payd';
							break;
							case '7':
								$class='reserved';
							break;
							case 'l':
								$class='lesson';
							break;
							default:
								$class='full';
							break;
						}
						
						#Celdas 'sin coste'
						if(isset($nocost) && $nocost == '1' ) $class = 'nocost';
						
						#Celdas de retos
						if(isset($shared) && $shared == '1' ) $class = 'shared';
						
						
						if($contador == count($avail) && $id_trans == $value[2]) $colspan++;	// Si es la ultima celda y estoy acumulando colspan por una reserva anterior, sumo uno para que pinte hasta el final

						$celda_a_pintar = '<td class="'.$class.'" colspan="'.$colspan.'" id="'.$id_trans.'">'.$init_text.'</td>';
						print($celda_a_pintar);
						$ultima_pintada = $id_trans;
						$colspan=0;
						/*
						if(strstr($value[0], ':30')) $init_text = $value[0];
						else $init_text = '&nbsp;'.substr($value[0], 0, 2).'&nbsp;';
						*/
						$init_text = $value[4];
						//$text = '';
						$id_trans = $value[2];
					}
					
				if(isset($value[1])) $el1=$value[1];
				else $el1='';
				if($el1=="0" && $value[2]!='') {
					# Si la pista está ocupada.. proceso otros parámetros
					
					
 					if($init_text == '') {
						/*
						if(strstr($value[0], ':30')) $init_text = $value[0];
						else $init_text = '&nbsp;'.substr($value[0], 0, 2).'&nbsp;';
						*/
						//print('<pre>');print_r($value);
						$init_text = $value[4];
 					}
					if($contador == count($avail) && $colspan == 0 && $ultima_pintada != $id_trans) {
	 					$id_trans = $value[2];
	 					$estado = $value[3];
						$colspan++;
						$celda_a_pintar = '<td class="'.$class.'" colspan="'.$colspan.'" id="'.$id_trans.'">'.$init_text.'</td>';
						print($celda_a_pintar);
						$colspan=0;
					}
					$nocost = $value[6];
					$shared = $value[5];
 					$id_trans = $value[2];
 					$estado = $value[3];
					$colspan++;
					
					
				} elseif($el1=="0" && $value[2] == '' ) {
					print('<td class="disable" id="0">'.$text.'</td>');
					$id_trans=''; $init_text = ''; $estado='';
				}
				else {
					print('<td class="free" id="'.$code.'" onClick="javascript: reservar(\''.$code.'\', \''.$user_id.'\', this, \''.time().'\');">'.$text.'</td>'."\r\n");
					$id_trans=''; $init_text = ''; $estado='';
				}
				
				
				/*
				# Permite que se pongan los segmentos de cada pista en dos líneas
				if($i%$cell_per_row===0) {
					$i=1;
					echo '</tr><tr><td class="courtname">&nbsp;</td>'."\r\n";
				}
				*/
				
				$i++;
				$contador++;
			}
			
			//if(isset($celda_a_pintar)) print($celda_a_pintar);
			
			echo "</tr>";   
		}   	 
		echo "</table></div>";   
	}
	?>
	
				<!-- Right Click Menu -->
			<ul id="myMenu" class="contextMenu">
				<li class="pay"><a href="#pay">Pagar</a></li>		
			  <li class="edit"><a href="#edit">Modificar</a></li>			        
			  <li class="light"><a href="#light">Luz</a></li>			        
			  <li class="delete"><a href="#delete">Cancelar</a></li>			
			</ul>
			<input type="hidden" id="HiddenFieldRowId" name="HiddenFieldRowId">

<div id="reserve_info" style="display:none; width:100%">
<table border="0" width="600" align="center"><tr><td width="300"><span id="coste">Coste de la reserva: sin coste</span><input type="hidden" name="price" id="numCoste" value="0">
	</td><td width="100px">
	<?php
		//echo '</td></tr><tr><td valign="top">';
		$js = 'id="buttonConfirma" disabled ';
		echo form_button('buttonConfirma', 'Confirma tu reserva!', $js);
		//echo '</td></tr></table>'; 					

	?>		
</td>
	</tr></table>
</div>


<!-- Acordeon de jQuery para los diferentes pasos de la reserva -->
<div id="proceso_reserva_dialog" title="Reservar" style="text-align: left">
	<div id="accordion" style="overflow:auto; width:680px; position: relative;">
		<h3><a href="#"><?php echo $this->lang->line('court_date_filters');?></a></h3>
		<div id="search_filters">
			
			<div class="ui-widget">
	      <div class="ui-state-highlight ui-corner-all" > 
	        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
	        Pesta&ntilde;a inhabilitada</p>
	      </div>
	    </div>
			
		</div>
		<h3><a href="#" id="aa"><?php echo $this->lang->line('interval_selection');?></a></h3>
		<div id="search_intervals" style="padding: 0.5em 1em;">
			<?php
				//if(isset($result) && $result!="") echo '<p>'.$result.'</p>';
			?>
			<div class="ui-widget">
	      <div class="ui-state-highlight ui-corner-all" > 
	        <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>
	        Pesta&ntilde;a inhabilitada</p>
	      </div>
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
</div>
<script type="text/javascript">
	$(function() {
		$( "#proceso_reserva_dialog" ).dialog( "destroy" );
		$("#proceso_reserva_dialog").dialog({
			autoOpen: false,
			modal: true,
			height: 500,
			width: 700,
			close: function(event, ui) { 
				enable_countdown = 1;
				location.href = location.href ;
			}
		});		
		$("#accordion").accordion({
			autoHeight: false,
			navigation: true
		});

	});
</script>	
<!-- Fin del acordeón -->

<div id="reservar_dialog" title="Reservar" style="text-align: left">
</div>

<div id="cancelar_dialog" title="Cancelar Reserva">
	<p>Est&aacute; seguro de querer cancelar la reserva?</p>
			<label for="text_cancel_view">Motivo:</label>
			<input type="text" name="text_cancel_view" id="text_cancel_view" value=""/>
</div>

<input type="hidden" name="text_cancel" id="text_cancel" value="" />
<input type="hidden" name="hora_inicio" id="hora_inicio" value=""/>


<script type="text/javascript">
	
$('.full,.reserved,.payd,.shared,.nocost').tooltip({ 
    bodyHandler: function() { 
        //return $($(this).attr("href")).html(); 
        var html = $.ajax({
									  url: "<?php echo site_url('reservas/tooltip_info'); ?>/"+$(this).attr('id'),
									  async: false
									 }).responseText;
        return html;
    }, 
    delay: 0,
    track: true,
		showURL: false 
});	
	
$('.lesson').tooltip({ 
    bodyHandler: function() { 
        //return $($(this).attr("href")).html(); 
        var html = $.ajax({
									  url: "<?php echo site_url('lessons/tooltip_info'); ?>/"+$(this).attr('id'),
									  async: false
									 }).responseText;
        return html;
    }, 
    delay: 0,
    track: true,
		showURL: false 
});	
	
function dumpProps(obj, parent) {
   // Go through all the properties of the passed-in object 
   for (var i in obj) {
      // if a parent (2nd parameter) was passed in, then use that to 
      // build the message. Message includes i (the object's property name) 
      // then the object's property value on a new line 
      if (parent) { var msg = parent + "." + i + "\n" + obj[i]; } else { var msg = i + "\n" + obj[i]; }
      // Display the message. If the user clicks "OK", then continue. If they 
      // click "CANCEL" then quit this level of recursion 
      if (!confirm(msg)) { return; }
      // If this property (i) is an object, then recursively process the object 
      if (typeof obj[i] == "object") { 
         if (parent) { dumpProps(obj[i], parent + "." + i); } else { dumpProps(obj[i], i); }
      }
   }
}
	
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#allow_light" ).button();

	$("#allow_light")
		.click( function() {
			//alert(document.getElementById('bombilla').src);
			if($('#allow_light').attr('checked') == true) {
				$('#light_button').css('background-image','url(<?php echo base_url();?>/images/luz.gif)');
				document.getElementById('light_prev').innerHTML=' Luz seleccionada ';
			}
			if($('#allow_light').attr('checked') == false) {
				$('#light_button').css('background-image','url(<?php echo base_url();?>/images/luz_no.gif)');
				document.getElementById('light_prev').innerHTML=' Solicitar luz ';
			}
		})
	
	/*
	$("#buttonConfirma")
		.click( function() {
				
			var direccion2 =<?php echo "'".site_url('reservas/confirm3/'.time())."/'+document.getElementById('numLight').value+'/'+document.getElementById('allow_light').checked";?>;
			//alert(direccion2);
			//return;
			//alert( $( "#accordion" ).accordion( "option", "animated" ));
			$('#reservar_dialog').html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
			$('#reservar_dialog').dialog('open');			

			$('#reservar_dialog').load(direccion2);
			var direccion2 =<?php echo "'".site_url('reservas/confirm3/'.time())."/'+document.getElementById('numLight').value+'/'+document.getElementById('allow_light').checked";?>;
			
			
		})
		*/

	$('#buttonConfirma').click( function() {
				
			//var direccion2 =<?php echo "'".site_url('reservas/confirm2/'.time())."/'+document.getElementById('numLight').value+'/'+document.getElementById('allow_light').checked";?>;
			
			// Con esto desactivo la cuenta atrás de refresco de pantalla
			enable_countdown = 0;
			
			$('#proceso_reserva_dialog').dialog('open');
			var direccion2 = '<?php echo site_url('reservas/extras/'.time().'/'.$this->session->userdata('idTransaction'));?>';
			//alert(direccion2);
			//return;
			//alert( $( "#accordion" ).accordion( "option", "animated" ));
			$("#accordion").accordion({ animated: 'slide' });
			$("#search_extra").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
			$.ajax({				
			  url: direccion2,
			  success: function(data) {
			  	//alert(data);
			    $("#search_intervals").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda o confirmar la seleccion.</p>  </div> </div>');
			    $("#search_extra").html(data);
					//$("#accordion").accordion("activate" , 2);
			  }
			});
			$("#accordion").accordion("activate" , 2);
			direccion2 = <?php echo "'".site_url('reservas/extras')."'";?>; // Reseteo variable
		})		


		$('#cancelar_dialog').dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				'Cancelar Reserva': function() {


          $.ajax({
						  url: "<?php echo site_url('reservas_gest/cancel_reserve_get'); ?>/"+$('#id_transaction').val()+"/"+$('#text_cancel_view').val()+"/<?php echo time();?>"
						 });
 					$("#search_result").html('		<p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0")); ?></p>');
					$("#search_result").load('<?php echo site_url('recepcion/grid'); ?>/'+$("#date").val());
					$('#text_cancel_view').val('');
					$(this).dialog('close');
				},
				'Cerrar': function() {
					$(this).dialog('close');
				}
			}
		});				


		$("#cambiar_dialog").dialog({
			autoOpen: false,
			show: 'blind',
			height: 300,
			width: 350,
			modal: true,
			buttons: 
			{
				'Modificar': function() 
				{
					//FALTA VALIDACIÓN DE CAMPOS
					document.getElementById('hora_inicio').value = document.getElementById('hora_inicio_view').value;

          $.ajax({
						  url: "<?php echo site_url('reservas_gest/change_reserve_get'); ?>/"+$('#id_transaction').val()+"/"+$('#date_view').val()+"/"+$('#hora_inicio_view').val()+"/<?php echo time();?>"
						 });
 					$("#search_result").html('		<p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0")); ?></p>');
					$("#search_result").load('<?php echo site_url('recepcion/grid'); ?>/'+$("#date").val());
					$('#text_cancel_view').val('');
					$(this).dialog('close');
				},
				'Cancelar': function() 
				{
					$(this).dialog('close');
				}
			}
		});
		
	});
				
function reservar(value, usuario, celda, dummy) {
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=300, height=300, top=85, left=140";
	var dt = new Date();
	var pagina='<?php echo site_url('reservas/preselect');?>/'+usuario+'/'+value+'/'+dt.getTime();
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
				if($('#reserve_info').is(':visible')) $("#reserve_info").hide('clip',null,1000);
				}	else {
					document.getElementById('coste').innerHTML='Coste de la reserva realizada: <b>'+document.getElementById('numCoste').value+'</b> euros';
					document.getElementById('buttonConfirma').disabled=false;
				}



			} else {
				alert('No se pudo realizar la reserva');
			}
				document.getElementById(celda).className=estilo;
			
			
			if($('#reserve_info').is(':hidden')) $("#reserve_info").show('clip',null,1000);
			
    	// write out response
      //document.getElementById("returned_value").innerHTML =
      //'Returned: '+n+' ('+e+') Random: '+r;

      // re-enable the button
      //document.getElementById('go').disabled = false;
      //document.getElementById('go').value = "Submit";
      //document.getElementById('returned_value').style.display="";
		}
		
		} // Fin del FOR
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
				

<?php
/*
	# Función que envía la petición del pago
	echo '<script type="text/javascript">'."\r\n";
	echo 'function pago(method) {'."\r\n";
	echo '  alert(method); var $ok=1;'."\r\n";
	//echo "    if(document.getElementById('no_cost').checked) alert(' marcado');"."\r\n";
	//echo "    if(document.getElementById('motivo').value=='' || document.getElementById('motivo').value==undefined) alert('vacio');"."\r\n";
	//echo "    return;"."\r\n";
	echo "  if(document.getElementById('no_cost').checked && (document.getElementById('no_cost_desc').value==''  || document.getElementById('no_cost_desc').value==undefined)) {"."\r\n";
	echo '    $ok=0;'."\r\n";
	echo "    alert('".$this->lang->line('no_cost_reason_required')."');"."\r\n";
	echo '  } '."\r\n";
	echo "  if( document.getElementById('id_user').value=='') {"."\r\n";
	echo '    $ok=0;'."\r\n";
	echo "    alert('".$this->lang->line('multiuser_value_required')."');"."\r\n";
	echo '  } '."\r\n";
	echo '  if($ok==1) {'."\r\n";
	echo "  	var no_cost_desc = document.getElementById('no_cost_desc').value;"."\r\n";
	echo "  	if(no_cost_desc == '') no_cost_desc = 'null';"."\r\n";
	echo "    var direccion3 = '".site_url('reservas/pay2')."/'+method+'/".$transaction_id."/'+document.getElementById('no_cost').checked+'/'+no_cost_desc+'/'+document.getElementById('id_user').value+'/null/null/".time()."';"."\r\n";
*/
?>