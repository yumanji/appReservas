<?php
	//print("<pre>");print_r($info);print("</pre>");
?>
  <form class="form_user" name="formReto" id="formReto" method="post" action="<?php echo current_url(); ?>">
  	<input name="action" id="action" type="hidden" value=""/>
    <input type="hidden" id="main_user" name="main_user">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="50%" valign="top">
            <label><span>Nombre del equipo</span>
						<?php 
						
							$search_user = array(
						    'name'        => 'description',
						    'id'          => 'description',
						    'class'       => 'buscar',
						  	'size'        => '20'	
						  );
							
							echo form_input($search_user);
						?>
						
            </label>

				</td>
        <td width="50%" valign="top">
            <label><span>Usuario principal</span>
						<?php 
						
							$search_user = array(
						    'name'        => 'buscausuarios2',
						    'id'          => 'buscausuarios2',
						    'class'       => 'buscar',
						  	'size'        => '20'	
						  );
							
							echo form_input($search_user);
						?>
						
            </label>
				</td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
<?php
		if($info['team_mates']>1) echo '<fieldset><legend>Miembros adicionales del equipo</legend><table border="0"><tr><td></td><td></td></tr>';
		for($i=1; $i < $info['team_mates']; $i++) {
?>  		<tr>
        <td valign="top">
            <label><span>Usuario registrado</span>
						<?php 
							echo '<input type="hidden" name="id_user_'.$i.'" id="id_user_'.$i.'" />';
							echo form_input(array('name' => 'busca_busca_'.$i, 'id' => 'busca_busca_'.$i, 'class' => 'buscar', 'size' => '20'));
						?>
            </label>
           </td>
        <td valign="top">
            <label><span>Usuario externo</span>
						<?php 
							echo form_input(array('name' => 'user_desc_'.$i, 'id' => 'user_desc_'.$i, 'size' => '20'));
						?>
						
            </label>
           </td>
        </tr>
<?php
	}
		if($info['team_mates']>1) echo '</table></fieldset>';
?>  		
				</td>
      </tr>
  		<tr>
  			<td colspan="2" align="center">
  				<span style="color: red; ">Los equipos ser&aacute;n autom&aacute;ticamente asignados al primer hueco libre.</span>
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

<?php for($i=1; $i < $info['team_mates']; $i++) { ?>

		$( "#busca_busca_<?php echo $i; ?>" ).autocomplete({
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
				$("#id_user_<?php echo $i; ?>").val(ui.item.id);

			}
		});
		
<?php } ?>				
	});
	</script>
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Suscribir"/>
			<!--<input type="button" id="pagar_button" class="boton" value="Suscribir y pagar"/>-->
      <input type="button" id="cancelar_button" class="boton" value="Cancelar"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		


		$('#guardar_button')
			.click(function() {
				$('#action').val('save');
				if(confirm('Esta seguro de querer dar de alta a este equipo?')) $('#formReto').submit();
			});
		
		
		$('#cancelar_button')
		.click(function() {
			$("#formReto").attr("action", "<?php echo site_url('ranking/assistants/'.$info['id']); ?>");
			$('#formReto').submit();
		});
	});
</script>
