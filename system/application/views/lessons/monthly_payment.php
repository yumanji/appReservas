	<script>
	$(function() {
		$( "button").button();
		$( "button" ).click(function() { 
			$("#frmDetail").attr("action", "<?php echo site_url('lessons/'.$funcion_destino.'/'.$id.'/save/1'); ?>/"+$(this).attr('id'));
			$("#frmDetail").submit();
			return false;
			});
	});
	</script>
<fieldset style="width: 600px; 	border-color: #2D588B; 	color: #2D588B; 	font-size: 11px; padding-left: 10px;">
	<legend style="color: #2D588B;">Pago de cuota mensual</legend>
	Pago de cuota mensual del curso '<?php echo $info->description; ?>': <?php echo $info->monthly.$this->lang->line('currency');  ?>
	<br>Est&aacute; pagado hasta el <?php echo $last_day_payed; ?>. Quiere realizar el pago hasta el <?php echo $next_day_payed; ?>?<br>Forma de pago: <button id="1"><?php echo $this->lang->line('Contado'); ?></button>&nbsp;<button id="2"><?php echo $this->lang->line('Tarjeta');?></button><br>&nbsp;
</fieldset>
<?php 
	$data = array(
              'name'        => 'monthly',
              'id'          => 'monthly',
              'value'       => $info->monthly
              
            );
	echo form_hidden($data)."\r\n";


?>
<input type="button" value="Cancelar" onClick="javascript: location.href='<?php echo site_url('lessons/detail/'.$info->id);?>'">
