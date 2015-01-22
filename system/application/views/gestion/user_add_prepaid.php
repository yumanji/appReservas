	<script>
	$(function() {
		$( "button").button();
		$( "button" ).click(function() { 
			$("#formDetail").attr("action", "<?php echo site_url($this->uri->uri_string()); ?>/"+$(this).attr('id')+"/<?php echo $control;?>");
			//alert("<?php echo site_url($this->uri->uri_string()); ?>/"+$(this).attr('id')+"/<?php echo $control;?>"); 
			$("#formDetail").submit();
			return false;
			});
	});
	</script>
<fieldset style="width: 600px; 	border-color: #2D588B; 	color: #2D588B; 	font-size: 11px; padding-left: 10px;">
	<legend style="color: #2D588B;">Recarga saldo bono para <?php echo $user_desc;?></legend>

	&nbsp;<br>Cantidad: <input type="text" size="10" id="amount" name="amount">&nbsp;(Saldo previo: <?php echo $pre_ammount.$this->lang->line('currency'); ?>)<br>&nbsp;<br>
	Forma de pago: <?php //<button id="1">Efectivo</button>&nbsp;?><button id="2"><?php echo $this->lang->line('Tarjeta');?></button><br>&nbsp;
</fieldset>
<input type="button" value="Cancelar" onClick="javascript: location.href='<?php echo site_url('users');?>'">
