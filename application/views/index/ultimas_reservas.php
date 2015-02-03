<table cellspacing="0">
	<tr>
		<td><img src="images/reservas.jpg" align="left" alt="reservas"/></td>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center">
        <?php
        	if(isset($reservas_list) && count($reservas_list)>0 && count($reservas_list[0])) {
        		//print("<pre>");print_r($reservas_list);
        		$image_properties = array(
				    	'src' => 'images/information.png',
				      'align' => 'absmiddle',
				      'class' => 'btnInfo',
				      'height' => '16',
				      'width' => '16',
				      'id' => $reservas_list[0][0],
				      //'id' => 'btnInfo',
				      'style' => 'cursor: pointer; cursor: hand;',
				      'title' => 'Informacion de reserva',
						);
        		echo '<ul class="lista fclear"><li>'.img('images/arrow.gif').' '.$reservas_list[0][2].' - '.$reservas_list[0][3].' '.$reservas_list[0][4].' '.img($image_properties).'</li>';
        	} else echo 'No hay reservas futuras';
        
        	if(isset($reservas_list) && count($reservas_list)==2 && count($reservas_list[1])) {
        		$image_properties = array(
				    	'src' => 'images/information.png',
				      'align' => 'absmiddle',
				      'class' => 'btnInfo',
				      'height' => '16',
				      'width' => '16',
				      'id' => $reservas_list[1][0],
				      //'id' => 'btnInfo',
				      'style' => 'cursor: pointer; cursor: hand;',
				      'title' => 'Informacion de reserva',
						);
        		echo '<li>'.img('images/arrow.gif').' '.$reservas_list[1][2].' - '.$reservas_list[1][3].' '.$reservas_list[1][4].' '.img($image_properties).'</li><ul>';
        	} else echo '</ul>';
        
        ?>
		</td>
	</tr>
</table>
<span  class="separador">
<a href="<?php echo site_url('users/reservas');?>" class="boton" >Ver todas</a>&nbsp;
<a href="<?php echo site_url('reservas/index/'.time());?>" class="boton" >Reservar !</a>
</span>

    	<script type="text/javascript">
				$('.btnInfo').tooltip({ 
				    bodyHandler: function() { 
				        //return $($(this).attr("href")).html(); 
				        var html = $.ajax({
													  url: "<?php echo site_url('reservas/tooltip_info'); ?>/"+$(this).attr('id'),
													  async: false
													 }).responseText;
	
				        return html;
				    }, 
				    delay: 0,
				    track: true,
						showURL: false 
				});	
			</script>    
