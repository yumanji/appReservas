<?php
//	print("<pre>");print_r($info);print("</pre>");
?>
  <form class="form_user" name="formReto" id="formReto" method="post" action="<?php echo current_url(); ?>">
  	<input name="action" id="action" type="hidden" value=""/>
    <input type="hidden" id="main_user" name="main_user">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="50%" valign="top">
            <label><span>Nombre del equipo:</span>
						<?php 
							echo $info['description'];
						?>
            </label>
				</td>
        <td width="50%" valign="top">
            <label><span>Usuario principal:</span>
						<?php 
							echo $info['main_user_description'];
						?>
            </label>
				</td>
      </tr>
      <tr>
        <td width="50%" valign="top">
            <label><span>Inscripcion:</span>
						<?php 
							echo $info['fecha_alta'];
						?>
            </label>
				</td>
        <td width="50%" valign="top">
            <label><span>Telefono:</span>
						<?php 
							echo $info['phone'];
						?>
            </label>

				</td>
      </tr>
      <tr>
        <td width="50%" valign="top">
            <label><span>Grupo:</span>
						<?php 
							echo $info['group'];
						?>
            </label>
				</td>
        <td width="50%" valign="top">
            <label><span>Posicion:</span>
						<?php 
							echo $info['order'];
						?>
            </label>
				</td>
      </tr>      
      <tr>
        <td colspan="2" valign="top">
<?php
		if(count($info['players'])>1) echo '<fieldset><legend>Miembros adicionales del equipo</legend><table border="0"><tr><td></td><td></td></tr>';
		for($i=1; $i < count($info['players']); $i++) {
?>  		<tr>
        <td valign="top">
            <label><span>Usuario <?php echo $i; ?>:</span>
						<?php 
							echo $info['players'][$i]['main_user_description'];
						?>
            </label>
           </td>

        </tr>
<?php
	}
		if(count($info['players'])>1) echo '</table></fieldset>';
?>  		
				</td>
      </tr>
   </table>
    
    <br clear="all" />
    


	</form>
	<script>

	$(function() {
		$( "#buscausuarios2" ).autocomplete({
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
				$("#main_user").val(ui.item.id);

			}
		});
				
	});
	</script>
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
      <input type="button" id="cancelar_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		
		$('#cancelar_button')
		.click(function() {
			$("#formReto").attr("action", "<?php echo site_url('ranking/assistants/'.$info['id_ranking']); ?>");
			$('#formReto').submit();
		});
	});
</script>
