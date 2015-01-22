<?php
	//print("<pre>");print_r($notification);
?>
  <form class="form_user" name="formUser" method="post">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="405" valign="top">
        	<input type="hidden" id="id_user" name="id_user" value="<?php echo $notification['id'];?>">
        	<label><span>Codigo: </span>
              <input name="id" type="text" id="code" disabled value="<?php echo $notification['id'];?>" size="30" />
          </label>
        	<label><span>Tipo</span>
              <input name="type_desc" type="text" id="type_desc" disabled value="<?php echo $notification['type_desc'];?>" size="30" />
          </label>
          <label><span>Destinatarios</span>
            <input type="text" name="destination_type_desc" id="destination_type_desc" disabled value="<?php echo $notification['destination_type_desc'];?>" size="30" />
          </label>
          <label><span>Estado</span>
            <input type="text" name="status_description" id="status_description" disabled value="<?php echo $notification['status_description'];?>" size="30" />
          </label>
				</td>
        <td width="476" >
        	<label><span>Origen</span>
             <input name="from" type="text" id="from" disabled value="<?php echo $notification['from'];?>" size="30" />
          </label>
        	<label><span>Asunto</span>
             <input name="subject" type="text" id="subject" disabled value="<?php echo $notification['subject'];?>" size="50" />
          </label>
        	<label><span>Inicio procesado </span>
             <input type="text" name="start_process" id="start_process" disabled value="<?php echo $notification['start_process'];?>" size="30" />
          </label>
        	<label><span>Fin procesado </span>
             <input type="text" name="end_process" id="end_process" disabled value="<?php echo $notification['end_process'];?>" size="30" />
          </label>
				</td>
      </tr>
    </table>
    <br clear="all" />

   <!--Fin Formulario usuario -->
    <br clear="all" />
    <!--
    <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Enviar"/>
      <input type="button" id="cancelar_button" class="boton" value="Cancelar"/>
    </div>
    -->
    &nbsp;
    <br clear="all" />

		<script type="text/javascript">
				$('#guardar_button')
					.click(function() {
						document.getElementById('player_level').disabled=false;
						$('#activar_dialog').dialog('open');
					});
				
				$('#cancelar_button')
				.click(function() {
					$(document.location).attr("href", "<?php echo site_url('users/index'); ?>");
				});
		</script>

  </form>
