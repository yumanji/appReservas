	<script>
	$(function() {
		$( "button").button();
		$( "button" ).click(function() { 
			$("#frmDetail").attr("action", "<?php echo site_url('lessons/'.$funcion_destino.'/'.$id.'/subscribe/save'); ?>/"+$(this).attr('id'));
			$("#frmDetail").submit();
			return false;
			});
	});
	</script>
<fieldset style="width: 600px; 	border-color: #2D588B; 	color: #2D588B; 	font-size: 11px; padding-left: 10px;">
	<legend style="color: #2D588B;">Pago de cuota de alta</legend>
	Pago de cuota de alta del curso '<?php echo $info->description; ?>': <?php echo $info->signin.$this->lang->line('currency');  ?>
	<br>Forma de pago: <button id="1"><?php echo $this->lang->line('Contado'); ?></button>&nbsp;<button id="2"><?php echo $this->lang->line('Tarjeta');?></button><br>&nbsp;
</fieldset>
<?php echo form_hidden('id_user', $id_user).form_hidden('user_desc', $user_desc).form_hidden('user_phone', $user_phone);?>
<input type="button" value="Cancelar" onClick="javascript: location.href='<?php echo site_url('lessons/detail/'.$info->id);?>'">
