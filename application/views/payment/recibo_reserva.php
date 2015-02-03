<?php
			//print("<pre>");print_r($info);
			?>  <!--
  <tr>
    <td colspan="3">C&oacute;digo: &nbsp; <strong><?php echo $info['booking_code']; ?></strong></td>
  </tr>
  -->
  <tr>
    <td colspan="3"><?php echo $pago->description; ?></td>
  </tr>
  <tr>
    <td colspan="3">Por: <?php echo $pago->desc_user; ?></td>
  </tr>

  <!--
  <tr>
    <td>Horario<br>Reserva:</td>
    <td colspan="2"><?php echo $info['fecha']; ?><br><?php echo $info['inicio'].' - '.$info['fin']; ?></td>
  </tr>
	-->
  <!--
  <tr>
    <td>Horario:</td>
    <td colspan="2"><?php echo $info['inicio'].' - '.$info['fin']; ?></td>
  </tr>
	-->
	<?php
	#Desactivado
		if(1==2 && $info['light']!='') {
	?>
  <tr>
    <td>Extras:</td>
    <td colspan="2"><?php if($info['light']!='') echo 'Luz'; else echo 'No'; ?></td>
  </tr>
	<?php
	}
	?>

  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>

  <tr>
    <td height="20"><div align="center"><strong>Concepto</strong></div></td>
    <td><div align="center"><strong>Cant.</strong></div></td>
    <td><div align="center"><strong>Importe</strong></div></td>
  </tr>

  <tr>
    <td height="15"><div align="center">Alquiler</div></td>
    <td><div align="center"><?php echo number_format(($info['intervals']/2),1); ?></div></td>
    <td><div align="center"><?php echo number_format(($info['price']),2); ?>&euro;</div></td>
  </tr>
	<?php
		if($info['light']!='') {
	?>
	  <tr>
	    <td height="15"><div align="center">Luz</div></td>
	    <td><div align="center"><?php echo number_format(($info['intervals']/2),1); ?></div></td>
	    <td><div align="center"><?php echo number_format(($info['light_price']),2); ?>&euro;</div></td>
	  </tr>
	<?php
	}
	?>
	<?php
		if($info['precio_supl1']!='') {
	?>
	  <tr>
	    <td height="15"><div align="center">Suplemento</div></td>
	    <td><div align="center">1</div></td>
	    <td><div align="center"><?php echo number_format(($info['precio_supl1']),2); ?>&euro;</div></td>
	  </tr>
	<?php
	}
	?>

  <tr>
    <td height="20" colspan="2"><strong>TOTAL (IVA inc)</strong></td>
    <td><div align="center"><strong><?php echo number_format(($pago->quantity),2); ?>&euro;</strong></div></td>
  </tr>

  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="3">Forma de pago:    	
<?php echo $this->lang->line($pago->paymentway_desc); ?></td>
  </tr>

	<?php
		if(1==2) {
	?>
  <tr>
    <td>Nº Tarjeta:</td>
    <td colspan="2">****8596
<?php
			print("<pre>");print_r($info);
			?>		</td>
  </tr>
	<?php
	}
	?>

  <!--
  <tr>
    <td colspan="3">Nota: <?php echo $pago->description; ?></td>
  </tr>
  
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  -->
