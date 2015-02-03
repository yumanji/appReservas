  <tr>
    <td colspan="3"><span class="Estilo2"><?php echo $this->config->item('aeb19_business_name'); ?></span></td>
  </tr>

  <tr>
    <td colspan="3"><span class="Estilo2"><?php echo $this->config->item('aeb19_business_address'); ?></span></td>
  </tr>

  <tr>
    <td colspan="3"><span class="Estilo2">Fecha: <?php echo date($this->config->item('reserve_date_filter_format')); ?></span></div></td>
  </tr>
  
  <tr>
    <td colspan="3"><span class="Estilo2">N&ordm; Factura
simplificada: <?php if(isset($ticket_number)) echo $ticket_number; else echo 'Desconocido'; ?></span></td>
  </tr>



