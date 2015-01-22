  <form class="form_user" name="formReto" id="formReto" method="post">
  	<input name="id_transaction" type="hidden" value="<?php echo $id_transaction;?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="470" valign="top">
        	<label><span>Fecha</span>
              <input name="date" type="text" id="date" disabled value="<?php echo date($this->config->item('reserve_date_filter_format'), strtotime($info['date'])); ?>" size="10" />
          </label>
            <label><span>Pista</span>
            <input type="text" name="court" id="court" value="<?php echo $info['court'];?>" disabled size="15" />
            </label>
            <label><span>Fecha limite <?php echo img(array('src' =>'images/information.png', 'title' => 'Fecha l&iacute;mite hasta la cual se aceptar&aacute;n suscripciones al partido.')); ?></span>
            <input type="text" name="limit_date" id="limit_date" value="<?php echo date($this->config->item('reserve_date_filter_format'), strtotime($info['limit_date'])); ?>" size="10" />
            </label>
						<label><span>G&eacute;nero</span>
							<select name="gender" id="gender">
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($generos	 as $code => $genero)
									{
										if($info['gender']==$code) echo '<option value="'.$code.'" selected>'.$genero.'</option>';
										else echo '<option value="'.$code.'">'.$genero.'</option>';
									}
								?>
							</select>
						</label>
            <label><span>Nivel de juego 1</span>
						<input type="text" name="low_player_level" id="low_player_level" disabled size="1" value=""  />
						<div id="slider1" style="margin-left:5px; margin-top:5px; margin-right:10px; width:90px; float:left;"></div>
          	</label>
            <!--
            <label><span>Dias preaviso</span>
            <input type="text" name="last_notify" id="last_notify" value="<?php echo $info['last_notify']; ?>" size="2"  alt="integer"/>
            </label>
            -->
            <input type="hidden" name="last_notify" id="last_notify" value="<?php echo $info['last_notify']; ?>" size="2"  alt="integer"/>

				</td>
        <td width="476" >
        		<label> <span>Horario</span>
              <input type="text" name="horario" id="horario" value="<?php echo $info['inicio'].' - '.$info['fin']?>" disabled size="10" />
          	</label>
            <label><span>Coste Reserva</span>
            <input type="text" name="price" id="price" value="<?php echo ($info['total_price']*100);?>"  size="5" disabled alt="dinero" />
            </label>
            <label><span>N&ordm; jugadores</span>
            <input type="text" name="players" id="players" value="<?php echo $info['players']; ?>" size="5" alt="integer"/>
          	</label>
            <label><span>Coste/Jugador</span>
            <input type="text" name="price_by_player" id="price_by_player" value="<?php echo $info['price_by_player']; ?>" size="5" alt="dinero" />
            </label>
            <label><span>Nivel de juego 2</span>
						<input type="text" name="high_player_level" id="high_player_level" disabled size="1" value=""  />
						<div id="slider2" style="margin-left:5px; margin-top:5px; margin-right:10px; width:90px; float:left;"></div>
          	</label>
            <!--
            <label><span>Visible</span>
            <input type="checkbox" name="visible" id="visible" <?php echo $info['visible']; ?>/>
            </label>
						-->
				</td>
      </tr>
    </table>
    
    <br clear="all" />
    
<?php //print("<pre>");print_r($info); ?>
    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Guardar"/>
      <input type="button" id="volver_button" class="boton" value="Volver"/>
      <input type="button" id="notificar_button" class="boton" value="Notificar"/>
      <input type="button" id="cancelar_button" class="boton" value="Cancelar reto"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		
		$("#slider1").slider({
			value: <?php echo $info['low_player_level']; ?> ,
			min: 1,
			max: 6,
			step: 0.1,
			slide: function(event, ui) {
				$("#low_player_level").val( ui.value);
			}
		});
		
		$("#slider2").slider({
			value: <?php echo $info['high_player_level']; ?>,
			min: 1,
			max: 6,
			step: 0.1,
			slide: function(event, ui) {
				$("#high_player_level").val( ui.value);
			}
		});
		
		$("#low_player_level").val( $("#slider1").slider("value"));
		$("#high_player_level").val( $("#slider2").slider("value"));


		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.99', type : 'reverse', defaultValue: '000'}
		$.mask.masks.dni = {mask : '99999999-a'}
		$('input:text').setMask();
		
		// Al perder foco el numero de jugadores, recalculo el precio por jugador
		$('#players').blur(function() {
		  if($('#players').val()!='' && $('#players').val()!=0) {
		  	valor = parseInt($('#price').val() * 100 / $('#players').val());
		  	$('#price_by_player').val($.mask.string( valor, 'dinero' ));
		  }
		});
		
		$("#limit_date").datepicker({
			showOn: 'button',
			buttonImage: '<?php echo base_url().'images/calendar.gif';?>',
			buttonImageOnly: true,
			dateFormat: 'dd-mm-yy',
			dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			firstDay: 1,
			minDate: 0, 
			}
			
			);

		$('#guardar_button')
		.click(function() {
			$('#low_player_level').removeAttr('disabled')
			$('#high_player_level').removeAttr('disabled')
			$('#action').val('save');
			$('#formReto').submit();
		});
		
		$('#cancelar_button')
		.click(function() {
			$("#formReto").attr("action", "<?php echo site_url('retos/cancel/'.$id_transaction); ?>");
			$('#formReto').submit();
		});
		
		$('#notificar_button')
		.click(function() {
			$("#formReto").attr("action", "<?php echo site_url('retos/notify/'.$id_transaction); ?>");
			$('#formReto').submit();
		});
		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('retos/'); ?>';
		});	});
</script>
