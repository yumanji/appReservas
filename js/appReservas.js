/*
function reservar2(value, usuario, celda) {
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=300, height=300, top=85, left=140";
	var pagina='index.php?/reservas/preselect/'+usuario+'/'+value;
	window.open(pagina,"",opciones);


	
}

function reservar(value, usuario, celda, dummy) {
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=300, height=300, top=85, left=140";
	var dt = new Date();
	var pagina='index.php?/reservas/preselect/'+usuario+'/'+value+'/'+dt.getTime();
	window.open(pagina,"",opciones);
	return;
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
function ObtenerNavegador($user_agent) {
     $navegadores = array(
          'Opera' => 'Opera',
          'Mozilla Firefox'=> '(Firebird)|(Firefox)',
          'Galeon' => 'Galeon',
          'Mozilla'=>'Gecko',
          'MyIE'=>'MyIE',
          'Lynx' => 'Lynx',
          'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
          'Konqueror'=>'Konqueror',
          'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',
          'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',
          'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
          'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
);
foreach($navegadores as $navegador=>$pattern){
       if (eregi($pattern, $user_agent))
       return $navegador;
    }
return 'Desconocido';
}
*/

function f_open_window_max( aURL, aWinName )
{
   var wOpen;
   var sOptions;

   sOptions = 'status=yes,menubar=no,scrollbars=yes,resizable=yes,toolbar=no';
   sOptions = sOptions + ',width=' + (screen.availWidth - 10).toString();
   sOptions = sOptions + ',height=' + (screen.availHeight - 122).toString();
   sOptions = sOptions + ',screenX=0,screenY=0,left=0,top=0';

   wOpen = window.open( '', aWinName, sOptions );
   wOpen.location = aURL;
   wOpen.focus();
   wOpen.moveTo( 0, 0 );
   wOpen.resizeTo( screen.availWidth, screen.availHeight );
   return wOpen;
}



// Recibe el 'id' del elemento HTML para proceder a la validación, si es correcta devuelve 'true' y sino saca un alert y devuelve 'false'
//Requiere del framework jQuery
function valida_nif_cif_nie(a) {
    var resul = true;
    var temp = jQuery('#'+a).val().toUpperCase();
    var cadenadni = "TRWAGMYFPDXBNJZSQVHLCKE";
    if (temp !== '') {
        //algoritmo para comprobacion de codigos tipo CIF
        suma = parseInt(temp[2]) + parseInt(temp[4]) + parseInt(temp[6]);
        for (i = 1; i < 8; i += 2) {
            temp1 = 2 * parseInt(temp[i]);
            temp1 += '';
            temp1 = temp1.substring(0,1);
            temp2 = 2 * parseInt(temp[i]);
            temp2 += '';
            temp2 = temp2.substring(1,2);
            if (temp2 == '') {
                temp2 = '0';
            }
            suma += (parseInt(temp1) + parseInt(temp2));
        }
        suma += '';
        n = 10 - parseInt(suma.substring(suma.length-1, suma.length));
        //si no tiene un formato valido devuelve error
        if ((!/^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$/.test(temp) && !/^[T]{1}[A-Z0-9]{8}$/.test(temp)) && !/^[0-9]{8}[A-Z]{1}$/.test(temp)) {
            if ((temp.length == 9) && (/^[0-9]{9}$/.test(temp))) {
                var posicion = temp.substring(8,0) % 23;
                var letra = cadenadni.charAt(posicion);
                var letradni = temp.charAt(8);
                //alert("La letra del NIF no es correcta. " + letradni + " es diferente a " + letra);
                jQuery('#'+a).val(temp.substr(0,8) + letra);
                resul = false;
            } else if (temp.length == 8) {
                if (/^[0-9]{1}/.test(temp)) {
                    var posicion = temp.substring(8,0) % 23;
                    var letra = cadenadni.charAt(posicion);
                    var tipo = 'NIF';
                } else if (/^[KLM]{1}/.test(temp)) {
                    var letra = String.fromCharCode(64 + n);
                    var tipo = 'NIF';
                } else if (/^[ABCDEFGHJNPQRSUVW]{1}/.test(temp)) {
                    var letra = String.fromCharCode(64 + n);
                    var tipo = 'CIF';
                } else if (/^[T]{1}/.test(temp)) {
                    var letra = String.fromCharCode(64 + n);
                    var tipo = 'NIE';
                } else if (/^[XYZ]{1}/.test(temp)) {
                    var pos = str_replace(['X', 'Y', 'Z'], ['0','1','2'], temp).substring(0, 8) % 23;
                    var letra = cadenadni.substring(pos, pos + 1);
                    var tipo = 'NIE';
                }
                if (letra !== '') {
                    //alert("Añadido la letra del " + tipo + ": " + letra);
                    jQuery('#'+a).val(temp + letra);
                } else {
                    //alert ("El CIF/NIF/NIE tiene que tener 9 caracteres");
                    jQuery('#'+a).val(temp);
                }
                resul = false;
            } else if (temp.length < 8) {
                //alert ("El CIF/NIF/NIE tiene que tener 9 caracteres");
                jQuery('#'+a).val(temp);
                resul = false;
            } else {
                //alert ("CIF/NIF/NIE incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de NIFs estandar
        else if (/^[0-9]{8}[A-Z]{1}$/.test(temp)) {
            var posicion = temp.substring(8,0) % 23;
            var letra = cadenadni.charAt(posicion);
            var letradni = temp.charAt(8);
            if (letra == letradni) {
                return 1;
            } else if (letra != letradni) {
                //alert("La letra del NIF no es correcta. " + letradni + " es diferente a " + letra);
                jQuery('#'+a).val(temp.substr(0,8) + letra);
                resul = false;
            } else {
                //alert ("NIF incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de NIFs especiales (se calculan como CIFs)
        else if (/^[KLM]{1}/.test(temp)) {
            if (temp[8] == String.fromCharCode(64 + n)) {
                return 1;
            } else if (temp[8] != String.fromCharCode(64 + n)) {
                //alert("La letra del NIF no es correcta. " + temp[8] + " es diferente a " + String.fromCharCode(64 + n));
                jQuery('#'+a).val(temp.substr(0,8) + String.fromCharCode(64 + n));
                resul = false;
            } else {
                //alert ("NIF incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de CIFs
        else if (/^[ABCDEFGHJNPQRSUVW]{1}/.test(temp)) {
            var temp_n = n + '';
            if (temp[8] == String.fromCharCode(64 + n) || temp[8] == parseInt(temp_n.substring(temp_n.length-1, temp_n.length))) {
                return 2;
            } else if (temp[8] != String.fromCharCode(64 + n)) {
                //alert("La letra del CIF no es correcta. " + temp[8] + " es diferente a " + String.fromCharCode(64 + n));
                jQuery('#'+a).val(temp.substr(0,8) + String.fromCharCode(64 + n));
                resul = false;
            } else if (temp[8] != parseInt(temp_n.substring(temp_n.length-1, temp_n.length))) {
                //alert("La letra del CIF no es correcta. " + temp[8] + " es diferente a " + parseInt(temp_n.substring(temp_n.length-1, temp_n.length)));
                jQuery('#'+a).val(temp.substr(0,8) + parseInt(temp_n.substring(temp_n.length-1, temp_n.length)));
                resul = false;
            } else {
                //alert ("CIF incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de NIEs
        //T
        else if (/^[T]{1}/.test(temp)) {
            if (temp[8] == /^[T]{1}[A-Z0-9]{8}$/.test(temp)) {
                return 3;
            } else if (temp[8] != /^[T]{1}[A-Z0-9]{8}$/.test(temp)) {
                var letra = String.fromCharCode(64 + n);
                var letranie = temp.charAt(8);
                if (letra != letranie) {
                    //alert("La letra del NIE no es correcta. " + letranie + " es diferente a " + letra);
                    jQuery('#'+a).val(temp.substr(0,8) + letra);
                    resul = false;
                } else {
                    //alert ("NIE incorrecto");
                    jQuery('#'+a).val(temp);
                    resul = false;
                }
            }
        }
        //XYZ
        else if (/^[XYZ]{1}/.test(temp)) {
            var pos = str_replace(['X', 'Y', 'Z'], ['0','1','2'], temp).substring(0, 8) % 23;
            var letra = cadenadni.substring(pos, pos + 1);
            var letranie = temp.charAt(8);
            if (letranie == letra) {
                return 3;
            } else if (letranie != letra) {
                //alert("La letra del NIE no es correcta. " + letranie + " es diferente a " + letra);
                jQuery('#'+a).val(temp.substr(0,8) + letra);
                resul = false;
            } else {
                //alert ("NIE incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
    }
    if (!resul) {      
        jQuery('#'+a).focus();
        return resul;
    }
}


function str_replace(search, replace, subject) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'
 
    var f = search, r = replace, s = subject;
    var ra = r instanceof Array, sa = s instanceof Array, f = [].concat(f), r = [].concat(r), i = (s = [].concat(s)).length;
 
    while (j = 0, i--) {
        if (s[i]) {
            while (s[i] = s[i].split(f[j]).join(ra ? r[j] || "" : r[0]), ++j in f){};
        }
    };
 
    return sa ? s : s[0];
}