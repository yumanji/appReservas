<?php
	$search_user = array(
    'name'        => 'destination',
    'id'          => 'destination',
    'class'          => '',
  	'size'        => '40'	
  );
?>
<form action="<?php echo site_url('notifications/create_single_notification_by_id'); ?>" method="post" target="_self">

		<div style="position:relative; width: 960px; height: 500px;">
		<div style="position:absolute; top:0; right:0; width: 960px;">
  <p>
    <label for="subject">Asunto: </label>
    <input type="text" name="subject" id="subject" />
  </p>
  <p>
    <label for="destination">Destinatario: </label>
    <?php echo '<input type="hidden" id="id_destination" name="id_destination">'.form_input($search_user);		?>
<!--<input type="text" name="destination" id="destination" />-->
  </p>
  <p>
		   <label for="content">Contenido: </label><br>
		   <div style="position:relative; top: -30px; right: 20px; height: 300px;"><?php echo $editors_code; ?></div>
  </p>
  <p><input type="submit" name="enviar" id="enviar" value="Enviar mensaje" />
  </p>
		</div></div>
</form>
	<script>

	$(function() {
		$( "#destination" ).autocomplete({
			source: function(req, add){
				//var parametros = req.split("=");
				//dumpProps(req);
				//alert(req.term);
				//pass request to server
				$.getJSON("<?php echo site_url('users/get_Names'); ?>/"+ req.term, function(data) {

					//create array for response objects
					var suggestions = [];

					//process response
					$.each(data, function(i, val){
						//{ data:val.id, value:val.name, result:val.name };
					suggestions.push({id:val.id, value:val.value});
				});

				//pass array to callback
				add(suggestions);
			});
		},
			minLength: 2,
			select: function( event, ui ) {
				$("#id_destination").val(ui.item.id);
			}
		});


				
	});
	</script>