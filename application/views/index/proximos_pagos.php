<table cellspacing="0">
	<tr>
		<td><img src="images/saldo.jpg" align="left" alt="pagos"/></td>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center">
			
        <?php
        	if(count($pagos_list)>0) {
        		$image_properties = array(
				    	'src' => 'images/information.png',
				      'align' => 'absmiddle',
				      'class' => 'btnInfo2',
				      'id' => $pagos_list[0][0],
				      //'id' => 'btnInfo',
				      'style' => 'cursor: pointer; cursor: hand;',
				      'title' => 'Informacion de pago',
						);
        		echo '<ul class="lista fclear"><li>'.img('images/arrow.gif').' '.$pagos_list[0][7].' - '.number_format($pagos_list[0][5],2).'&euro;'.' '.img($image_properties).'</li>';
        	} else echo 'No hay pagos pendientes';
        
        	if(count($pagos_list)==2) {
        		$image_properties2 = array(
				    	'src' => 'images/information.png',
				      'align' => 'absmiddle',
				      'class' => 'btnInfo2',
				      'id' => $pagos_list[1][0],
				      //'id' => 'btnInfo',
				      'style' => 'cursor: pointer; cursor: hand;',
				      'title' => 'Informacion de pago',
						);
        		echo '<li>'.img('images/arrow.gif').' '.$pagos_list[1][7].' - '.number_format($pagos_list[1][5],2).'&euro;'.' '.img($image_properties2).'</li></ul>';
        	} else echo '</ul>';
        
        ?>
		</td>
	</tr>
</table>
<span  class="separador">
<a href="<?php echo site_url('users/pagos');?>" class="boton" >Ver mas</a>
</span>

   

    	<script type="text/javascript">
				$('.btnInfo2').tooltip({ 
				    bodyHandler: function() { 
				        //return $($(this).attr("href")).html(); 
				        var html = $.ajax({
													  url: "<?php echo site_url('payment/tooltip_info'); ?>/"+$(this).attr('id'),
													  async: false
													 }).responseText;
	
				        return html;
				    }, 
				    delay: 0,
				    track: true,
						showURL: false 
				});	
			</script>
			