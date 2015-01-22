<center>
  <h3>Curso: <?php echo htmlentities($datos['nombre']); ?><br />
  	Fecha: <?php echo date($this->config->item('reserve_date_filter_format')); ?>&nbsp;&nbsp;&nbsp;
    Horario: <?php echo $datos['horario']; ?><br />
    Pista: <?php echo htmlentities($datos['pista']); ?><br />
  Profesor: <?php echo htmlentities($datos['profesor']); ?></h3>
  <p><br />
  </p>
</center>
<table style="font-size:12px" width="90%" border="1" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td width="30%" align="center" style="font-weight: bold; font-size:12px">Alumno</td>
    <td width="6%" align="center" style="font-weight: bold; font-size:12px">Asiste</td>
    <td align="center" style="font-weight: bold; font-size:12px">Observaciones</td>
  </tr>
  <?php
  	foreach($datos['alumnos'] as $alumno) {
  ?>
  <tr>
    <td style="font-size:11px"><?php echo utf8_decode ($alumno); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <?php
		}
	?>


</table>
<p><table width="90%" border="1" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td width="30%"><p>Observaciones:</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>
<p><table width="40%" border="0" align="right" cellpadding="10" cellspacing="0">
  <tr>
    <td width="30%" align="left"><p>Firma:</p>
    <p>&nbsp;</p></td>
  </tr>
</table>

