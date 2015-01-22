  <?php
  //echo '<pre>';
  //print_r($info);
  //echo '</pre>';
  ?>
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
            <input type="text" name="limit_date" id="limit_date" disabled value="<?php echo date($this->config->item('reserve_date_filter_format'), strtotime($info['limit_date'])); ?>" size="10" />
            </label>
						<label><span>G&eacute;nero</span>
							<input type="text" name="gender" id="gender" value="<?php echo $generos[$info['gender']]; ?>" disabled size="15" />
						</label>
            <label><span>Nivel de juego 1</span>
						<input type="text" name="low_player_level" id="low_player_level" disabled size="1" value="<?php echo $info['low_player_level']; ?>"  />
          	</label>

				</td>
        <td width="476" >
        		<label> <span>Horario</span>
              <input type="text" name="horario" id="horario" value="<?php echo $info['inicio'].' - '.$info['fin']?>" disabled size="10" />
          	</label>
            <label><span>Coste Reserva</span>
            <input type="text" name="price" id="price" value="<?php echo ($info['total_price']*100);?>"  size="5" disabled alt="dinero" />
            </label>
            <label><span>N&ordm; jugadores</span>
            <input type="text" name="players" id="players" value="<?php echo $info['players']; ?>" size="5" disabled alt="integer"/>
          	</label>
            <label><span>Coste/Jugador</span>
            <input type="text" name="price_by_player" id="price_by_player" value="<?php echo $info['price_by_player']; ?>" disabled size="5" alt="dinero" />
            </label>
            <label><span>Nivel de juego 2</span>
						<input type="text" name="high_player_level" id="high_player_level" disabled size="1" value="<?php echo $info['high_player_level']; ?>"  />
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
	<h3>Jugadores</h3>
<?php 
//print("<pre>");print_r($jugadores); 
	echo '<ul>';
	foreach($jugadores as $jugador){
		echo '<li>'.trim($jugador['first_name'].' '.$jugador['last_name']);
		if($retos_save_results){
			if(!$info['winner_recorded']) echo '&nbsp;&nbsp;(Marcar si gan&oacute; el reto: <input type="checkbox" id="win_'.$jugador['id_user'].'" name="win_'.$jugador['id_user'].'" value="'.$jugador['id_user'].'">)';
			elseif($jugador['win_game']) echo '&nbsp;&nbsp;(Ganador)';
		}
		echo '</li>';
	}
	echo '</ul>';
?>
    <!--Fin Formulario usuario -->
    <br clear="all" />
    <div class="separador">
		<?php 
			if($retos_save_results){
				if(!$info['winner_recorded']) echo '<input type="button" id="resultados_button" class="boton" value="Guardar resultados"/>';
				else echo '<input type="button" id="borrar_resultados_button" class="boton" value="Borrar resultados"/>';	
			}				
		?>
		<input type="button" id="volver_button" class="boton" value="Volver"/>
    </div>
    &nbsp;
    <br clear="all" />

<script type="text/javascript">
	$(function() {
		
		//Definición de máscaras del formulario
		$.mask.masks.dinero = {mask : '99.99', type : 'reverse', defaultValue: '000'}
		$.mask.masks.dni = {mask : '99999999-a'}
		$('input:text').setMask();
		

		$('#resultados_button')
		.click(function() {
			$('#action').val('result');
			$('#formReto').submit();
		});
		$('#borrar_resultados_button')
		.click(function() {
			$('#action').val('del_result');
			$('#formReto').submit();
		});
		
		$('#volver_button')
		.click(function() {
			location.href = '<?php echo site_url('retos/'); ?>';
		});	});
</script>
