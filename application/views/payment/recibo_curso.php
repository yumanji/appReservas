  <tr>
    <td>Pagado:</td>
    <td colspan="2"><?php echo $pago->datetime; ?></td>
  </tr>
  <tr>
    <td colspan="3">Por: <?php echo $pago->desc_user; ?></td>
  </tr>

  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>

  <tr>
    <td height="20"><div align="center"><strong>Concepto</strong></div></td>
    <!--<td><div align="center"><strong>Cantidad</strong></div></td>-->
    <td><div align="center"><strong>Importe</strong></div></td>
  </tr>

  <tr>
    <td height="15"><div align="center">Cuota curso</div></td>
    <!--<td><div align="center">1</div></td>-->
    <td><div align="center"><?php echo number_format(($pago->quantity),2); ?>&euro;</div></td>
  </tr>

  <tr>
    <td height="20" colspan="2"><strong>TOTAL (IVA inc)</strong></td>
    <td><div align="center"><strong><?php echo number_format(($pago->quantity),2); ?>&euro;</strong></div></td>
  </tr>

  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="3">Forma de pago: <?php echo $this->lang->line($pago->paymentway_desc); ?></td>
  </tr>

	<?php
		if(1==2) {
	?>
  <tr>
    <td>Nº Tarjeta:</td>
    <td colspan="2">****8596
    	<?php
			//print("<pre>");print_r($info);
			?>
		</td>
  </tr>
	<?php
	}
	?>

  <tr>
    <td colspan="3">Nota: <?php echo $pago->description; ?></td>
  </tr>
  <!--
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
	-->
