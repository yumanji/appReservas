<center>
  <h3>Curso: <?php echo htmlentities($datos['nombre']); ?><br />
    Horario: <?php echo $datos['horario']; ?>&nbsp;&nbsp;-&nbsp;&nbsp;
    Pista: <?php echo htmlentities($datos['pista']); ?><br />
  Profesor: <?php echo htmlentities($datos['profesor']); ?></h3>
  <p><br />
  </p>
</center>
<table style="font-size:12px" width="90%" border="1" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td align="center" style="font-weight: bold; font-size:12px">Alumno</td>
    <td width="15%" align="center" style="font-weight: bold; font-size:12px">DNI</td>
    <td width="15%" align="center" style="font-weight: bold; font-size:12px">Fecha Nacim.</td>
    <td width="15%" align="center" style="font-weight: bold; font-size:12px">Telefono</td>
  </tr>
  <?php
  	foreach($datos['alumnos'] as $alumno) {
  ?>
  <tr>
    <td style="font-size:11px"><?php echo utf8_decode ($alumno['nombre']); ?></td>
     <td style="font-size:11px"><?php echo utf8_decode ($alumno['nif']); ?></td>
    <td style="font-size:11px"><?php echo utf8_decode ($alumno['nacimiento']); ?></td>
    <td style="font-size:11px"><?php echo utf8_decode ($alumno['telefono']); ?></td>
  </tr>
  
  <?php
		}
	?>


</table>


