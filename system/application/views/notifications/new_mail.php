<?php
$options = array(
                  '1'  => 'Envio general',
                  '2'    => 'Mail a alumnos',
                  '3'   => 'Mail a ranking',
                  '4' => 'Mail a abonados',
                  '5' => 'Mail a NO abonados'
                );

?>

<form action="<?php echo site_url('notifications/new_mail'); ?>" id="new_mail" method="post" target="_self">
	<input type="hidden" id="action" name="action" value="">

	<div style="position:relative; width: 960px; height: 500px;">
		<div style="position:absolute; top:0; right:0; width: 960px;">
		  <p>
		    <label for="subject">Tipo env&iacute;o: </label>
		    <?php
		    	echo form_dropdown('comm_type', $options, '1');
				?>
		  </p>
		  <p>
		    <label for="subject">Asunto: </label>
		    <input type="text" name="subject" id="subject" />
		  </p>
		  <p>
				<label for="content">Contenido: </label><br>
				<div style="position:relative; top: -30px; right: 20px; height: 300px;">
					<?php echo $editors_code; ?>
				</div>
		  </p>
		  <p>
		  	<input type="button" name="enviar" id="enviar" value="Enviar mensaje" />
		  </p>
		</div>
	</div>
</form>
<script type="text/javascript">
		$('#enviar')
			.click(function() {
				$("#action").val('send');
				$('#new_mail').submit();
			});
</script>