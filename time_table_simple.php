<?php
	$weekdays_names	= array(1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 7 => 'Domingo' );
	

	echo "INSERT INTO `time_tables_detail` (`id_time_table`, `weekday`, `interval`, `status`, `create_user`, `create_time`, `modify_user`, `modify_time`) VALUES ";
	
	$id_time_table = '3';
	$i=0;
	foreach($weekdays_names as $code => $name) {
		$estado = '1';
		$starttime = '10:00:00';
		$endtime = '23:00:00';
		if($code == 6 || $code == 7) { $starttime = '08:00:00'; $endtime = '22:00:00'; }
		$time = new DateTime($starttime);
		$interval = new DateInterval('PT15M');
		$temptime = $time->format('H:i:s');
		do {
			if($i != 0) echo ", " . '<br />';
		   echo "( ".$id_time_table.", '".$code."', '".$temptime."', '".$estado."', '1', '2010-01-01 00:00:00', NULL, NULL)";
		   $time->add($interval);
		   $temptime = $time->format('H:i:s');
		   $i++;
		} while ($temptime <= $endtime);
	}
	echo ";";
	exit();







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
	
	$horario = "INSERT INTO time_tables (id, description, active, everyday, create_user, create_time) VALUES (".$_POST['id'].", ".$_POST['description'].", ".$activo.", ".$everyday.", 1, '".date('Y-m-d H:i:s')."');";
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
			$horario = "INSERT INTO time_tables_detail (id_time_table, weekday, `interval`, `status`, create_user, create_time) VALUES (".$_POST['id'].", 0, '".$hora.":00', ".$check.", 1, '".date('Y-m-d H:i:s')."');";
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
<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="">
	<h1>Definicion de un horario para una instalacion</h1>
  <fieldset>
    <legend>Datos del horario</legend>
			<label for="id">Id</label>
      <input type="text" name="id" id="id" size="3"/>
			<label for="description">Descripcion</label>
      <input type="text" name="description" id="description" />
			<label for="active">Activo</label>
      <input type="checkbox" name="active" id="active"  value="1"/>
      <label for="everyday">Todos los dias</label>
    <input type="checkbox" name="everyday" id="everyday" value="1"/>
    <input type="submit" value="Crear" />
  </fieldset>
  <div id="global" style="float:left;"><strong>Global</strong><br /><label>&nbsp;</label><br />
  	<?php
  		foreach($time_array as $time => $hora) {
  			if($time > '08:00' && $time < '23:00') $checked = 'checked';
  			else $checked = '';
  			echo '<label for="Global_'.$time.'">'.$time.'</label><input id="Global_'.$time.'" name="Global_'.$time.'" type="checkbox" class="global" '.$checked.' title="'.$time.'" value="Global_'.$time.'" /><br/>';
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
  			echo '<label for="Day#'.$i.'_'.$time.'">'.$time.'</label><input id="Day#'.$i.'_'.$time.'" name="Day#'.$i.'_'.$time.'" type="checkbox" class="Day#'.$i.'" '.$checked.' title="'.$time.'" value="Day#'.$i.'_'.$time.'" /><br/>';
  		}
 		
 		
 		echo '</div>'."\r\n";
 	}
 ?>

  <p>&nbsp;</p>
  </form>
</body>
</html>
