<?php
	$weekdays_names	= array(0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 7 => 'Domingo' );
	$groups	= array( 1, 2, 3, 4, 5, 6, 7, 8, 9 );
	
	$time_array=array(
	 "00:00"=>"0:00","00:30"=>"0:30","01:00"=>"1:00","01:30"=>"1:30","02:00"=>"2:00","02:30"=>"2:30",
	 "03:00"=>"3:00","03:30"=>"3:30","04:00"=>"4:00","04:30"=>"4:30","05:00"=>"5:00","05:30"=>"5:30",
	 "06:00"=>"6:00","06:30"=>"6:30","07:00"=>"7:00","07:30"=>"7:30","08:00"=>"8:00","08:30"=>"8:30",
	 "09:00"=>"9:00","09:30"=>"9:30","10:00"=>"10:00","10:30"=>"10:30","11:00"=>"11:00","11:30"=>"11:30",
	 "12:00"=>"12:00","12:30"=>"12:30","13:00"=>"13:00","13:30"=>"13:30","14:00"=>"14:00","14:30"=>"14:30",
	 "15:00"=>"15:00","15:30"=>"15:30","16:00"=>"16:00","16:30"=>"16:30","17:00"=>"17:00","17:30"=>"17:30",
	 "18:00"=>"18:00","18:30"=>"18:30","19:00"=>"19:00","19:30"=>"19:30","20:00"=>"20:00","20:30"=>"20:30",
	 "21:00"=>"21:00","21:30"=>"21:30","22:00"=>"22:00","22:30"=>"22:30","23:00"=>"23:00","23:30"=>"23:30"
	);


if(isset($_POST) && count($_POST)>0) {
	# Procesado de lo recibido
	//print("<pre>");print_r($_POST);print("</pre>");
	$consultas = array();
	
	
	$activo = $_POST['active'];
	if($activo=='') $activo = 0;
	
	$everyday = $_POST['everyday'];
	if($everyday=='') $everyday = 0;
	
	$horario = "INSERT INTO time_tables (id, description, active, everyday, create_user, create_time) VALUES (".$_POST['id'].", ".$_POST['description'].", ".$activo.", ".$everyday.", 1, '".date('Y-m-d H:i:s')."')";
	array_push($consultas, $horario);
	
	if(isset($_POST['everyday']) && $_POST['everyday']=="1") {
		# El mismo horario para todos los dias
		
		
		
		#Calculo de maximos y minimos
		$max = ''; $min = '';
		foreach($_POST as $code => $value) {
			# Proceso los globales
			if(strstr($code, 'Global_')) {
				$datos = explode('_', $code);
				if($max < $datos[1]) $max = $datos[1];
				if($min == '' || $min > $datos[1]) $min = $datos[1];
			}
		}
		
		//echo 'max: '.$max.'  min: '.$min;
		$horario_det = array();
		foreach($time_array as $code => $value) {
			if($code >= $min && $code<= $max) {
				foreach($_POST as $code2 => $value2) {
					# Proceso los globales
					if(strstr($code2, 'Global_')) {
						$datos = explode('_', $code2);
						if($datos[1] == $code) {
							//echo $datos[1].'<br>';
							$horario_det[$code] = 1;
						}
					}
				}
				
				if(!isset($horario_det[$code])) $horario_det[$code] = 0;
			}	

		}
		
		//print("<pre>");print_r($horario_det);
		foreach($horario_det as $hora => $check) {
			$horario = "INSERT INTO time_tables_detail (id_time_table, weekday, interval, status, create_user, create_time) VALUES (".$_POST['id'].", 0, '".$hora.":00', ".$check.", 1, '".date('Y-m-d H:i:s')."')";
			array_push($consultas, $horario);

		}
		
		
	} else {
		
		
		
		# Si se aplica un horario diferente por cada día de la semana
		
		#Calculo de maximos y minimos
		$max = ''; $min = '';
		foreach($_POST as $code => $value) {
			# Proceso los globales
			if(strstr($code, 'Day#')) {
				$datos = explode('_', $code);
				if($max < $datos[1]) $max = $datos[1];
				if($min == '' || $min > $datos[1]) $min = $datos[1];
			}
		}
	
			//echo 'max: '.$max.'  min: '.$min;
			
				
			for($dia=1; $dia <=7; $dia++) {
				$horario_det = array();
				foreach($time_array as $code => $value) {
					if($code >= $min && $code<= $max) {
						foreach($_POST as $code2 => $value2) {
							# Proceso los globales
							if(strstr($code2, 'Day#'.$dia.'_')) {
								$datos = explode('_', $code2);
								if($datos[1] == $code) {
									//echo $datos[1].'<br>';
									$horario_det[$code] = 1;
								}
							}
						}
						
						if(!isset($horario_det[$code])) $horario_det[$code] = 0;
					}	
		
				}
				
				//print("<pre>");print_r($horario_det);
				foreach($horario_det as $hora => $check) {
					$horario = "INSERT INTO time_tables_detail (id_time_table, weekday, interval, status, create_user, create_time) VALUES (".$_POST['id'].", ".$dia.", '".$hora.":00', ".$check.", 1, '".date('Y-m-d H:i:s')."')";
					array_push($consultas, $horario);
				}			
			
		}
			
			
	}
	
	echo '<b>Resultado en SQL de los horarios especificados</b><br>';
	foreach ($consultas as $consulta) echo '<br>'.$consulta;
	echo '<hr>';
	//print("<pre>"); print_r($consultas);print("</pre>");
	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://localhost/appReservas/js/jquery-1.4.2.js"></script>

<script type="text/javascript" src="js/jquery.meio.mask.min.js"></script>

<title>Definicion de horarios</title>
<style type="text/css">
div {
	padding: 5px;
	border: thin solid #333;
}
body {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	font-style: normal;
	font-weight: normal;
	color: #333;
	background-color: #CCC;
}
input {
	margin-top: 2px; vertical-align: middle;
}
</style>
</head>

<body>
	
<script type="text/javascript">
	$(function() {
		//Definici�n de m�scaras del formulario
		$.mask.masks.dinero = {mask : '99.99', type : 'reverse', defaultValue: '000'}
		$.mask.masks.fecha = {mask : '9999-19-39'}
		$('input:text').setMask();
});
</script>

<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="">
	<h1>Definicion de tarifas para una instalacion</h1>
  <fieldset>
    <legend>Datos del horario</legend>
			<label for="id">Id</label>
      <input type="text" name="id" id="id" size="3"/>
			<label for="description">Descripcion</label>
      <input type="text" name="description" id="description" />
			<label for="type">Tipo</label>
      <select name="type" id="type" />
      	<option value="1">Cuota socio</option>
      	<option value="2">Reservas</option>
      	<option value="3">Extras alquiler</option>
      	<option value="4">Ranking</option>
      	<option value="5">Clases</option>
      	<option value="9">Otros</option>
      </select>
			<label for="active">Activo:</label>
      <input type="checkbox" name="active" id="active"  checked value="1"/>
			<label for="include_holiday">Fiestas:</label>
      <input type="checkbox" name="include_holiday" id="include_holiday"  checked value="1"/>
			<label for="by_group">por grupos:</label>
      <input type="checkbox" name="by_group" id="by_group"  value="1"/>
			<label for="by_weekday">por dia semana:</label>
      <input type="checkbox" name="by_weekday" id="by_weekday"   value="1"/>
			<label for="by_time">por horas:</label>
      <input type="checkbox" name="by_time" id="by_time"   value="1"/>
			<label for="start_date">Fecha inicio:</label>
      <input type="text" name="start_date" id="start_date"  size="10" value="2009-01-01" alt="fecha"/>
			<label for="end_date">Fecha fin:</label>
      <input type="text" name="end_date" id="end_date"  size="10" value="2009-01-01" alt="fecha"/>
			<label for="quantity">Cantidad:</label>
      <input type="text" name="quantity" id="quantity"  size="5" alt="dinero"/>
			<label for="duration">Duracion:</label>
      <input type="text" name="duration" id="duration"  size="2" alt="integer"/>
    <input type="submit" value="Crear" />
  </fieldset>
  <div id="divgroup" style="float:left;"><strong>por Grupo</strong><br /><label>&nbsp;</label><br />
  	<?php
  		foreach($groups as $grupo) {
  			echo '<label for="Grupo_'.$grupo.'">Grupo '.$grupo.'</label><input id="Grupo_'.$grupo.'" name="Grupo_'.$grupo.'" type="text" class="grupo"  size="5" title="'.$grupo.'" value=""  alt="dinero"/><br/>';
  		}
  	?>
  	
  </div>
  <div id="divweekday" style="float:left;"><strong>por dia semana</strong><br /><label>&nbsp;</label><br />
  	<?php
  	$i =0;
  		foreach($weekdays_names as $dia => $dia_desc) {
  			if($i>0) echo '<label for="Weekday_'.$dia.'">'.$dia_desc.'</label><input id="Weekday_'.$dia.'" name="Weekday_'.$dia.'" type="text" class="weekday"  size="5" title="'.$dia_desc.'" value=""  alt="dinero"/><br/>';
  			$i++;
  		}
  	?>
  	
  </div>
  <div id="divtime" style="float:left;"><strong>por Horas</strong><br /><label>&nbsp;</label><br />
  	<?php
  		foreach($time_array as $time => $hora) {
  			if($time > '08:00' && $time < '23:00') $checked = 'checked';
  			else $checked = '';
  			echo '<label for="Global_'.$time.'">'.$time.'</label><input id="Global_'.$time.'" name="Global_'.$time.'" type="text" class="global" title="'.$time.'"  value=""  alt="dinero" /><br/>';
  		}
  	?>
  	
  </div>

 <?php
 	for($i=1; $i<=7; $i++) {
 		echo '<div id="global" style="float:left"><strong>'.$weekdays_names[$i].'</strong><br />'."\r\n";
 		if($i==1) echo '&nbsp;<br/>';
 		else echo '<label for="same_'.$i.'">Igual ant.:</label><input name="same_'.$i.'" type="checkbox" value="1" /><br/>'."\r\n";
 		
  		foreach($time_array as $time => $hora) {
  			if($time > '08:00' && $time < '23:00') $checked = 'checked';
  			else $checked = '';
  			echo '<label for="Day#'.$i.'_'.$time.'">'.$time.'</label><input id="Day#'.$i.'_'.$time.'" name="Day#'.$i.'_'.$time.'" type="checkbox" class="bytime" '.$checked.' title="'.$time.'" value="Day#'.$i.'_'.$time.'" /><br/>';
  		}
 		
 		
 		echo '</div>'."\r\n";
 	}
 ?>

  <p>&nbsp;</p>
  </form>


<script type="text/javascript">
$(function() {
		$('.grupo').attr('disabled', 'disabled');
		$('.weekday').attr('disabled', 'disabled');
		$('.bytime').attr('disabled', 'disabled');
		$('#divgroup').hide(100);
		$('#divweekday').hide(100);
		$('#divtime').hide(100);

		$('#by_group').change(function() {
			if ($('#by_group').is (':checked')) {
  			$('.grupo').removeAttr('disabled');
  			$('.weekday').attr('disabled', 'disabled');
  			$('.bytime').attr('disabled', 'disabled');
  			$('#divgroup').show(100);
  			$('#divweekday').hide(100);
  		} else {
  			$('#divgroup').hide(100);
  			$('.grupo').attr('disabled', 'disabled');
  		}
		});		

		$('#by_weekday').change(function() {
			if ($('#by_weekday').is (':checked')) {
  			$('.weekday').removeAttr('disabled');
  			$('.grupo').attr('disabled', 'disabled');
  			$('.bytime').attr('disabled', 'disabled');
				$('#divgroup').hide(100);
  			$('#divweekday').show(100);
  		} else {
  			$('.weekday').attr('disabled', 'disabled');
				$('#divweekday').hide(100);
  		}
		});		

		$('#by_time').change(function() {
			if ($('#by_time').is (':checked')) {
  			$('.global').removeAttr('disabled');
  			$('.grupo').attr('disabled', 'disabled');
  			$('.weekday').attr('disabled', 'disabled');
  			$('.bytime').attr('disabled', 'disabled');
				$('#divgroup').hide(100);
				$('#divweekday').hide(100);
  			$('#divtime').show(100);
  		} else {
  			$('.global').attr('disabled', 'disabled');
				$('#divtime').hide(100);
  		}
		});		

		$('#by_time,#by_weekday,#by_group').change(function() {
			if ($('#by_time').is (':checked') && $('#by_weekday').is (':checked') && $('#by_group').is (':checked')) {

					alert('aa');

  		} 
		});		




	}
);
</script>


</body>
</html>
