<div id="main">
   <noscript>This site just doesn't work, period, without JavaScript</noscript>

   <!-- IF LOGGED IN -->

          <!-- Content here -->

   <!-- IF LOGGED OUT -->


	<script type="text/javascript">
	$(function() {
		$("#date").datepicker({
			showOn: 'button',
			buttonImage: '<?php echo base_url();?>/images/calendar.gif',
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			minDate: 0, 
			maxDate: '+14D'		});
	});
	</script>


<p><?php echo $this->lang->line('welcome_text'); ?></p>
	<div id="search_fields">
      	<?php  
      		$attributes = array('class' => 'frmReserva', 'id' => 'frmReserva');
					echo form_open('reservas', $attributes);
      	 ?>
      	<?php  
					foreach($search_fields as $field) echo $field;
      	 ?>
      	<?php  
					//echo form_submit('mysubmit', 'Registrarse!');
					$js = 'id="buttonSubmit" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url('reservas/search').'\'; document.getElementById(\'frmReserva\').submit(); "';
					echo form_button('buttonSubmit', 'Busca tu pista!', $js);
					
					if(is_array($availability)) {
						print('<table id="availability"><tr>');
						foreach($availability as $code => $value) {
							if($value[1]=="0") print('<td class="full" >'.$value[0].'</td>');
							else print('<td class="free" id="'.$code.'" onClick="javascript: reservar(\''.$code.'\', \''.$user_id.'\', this);">'.$value[0].'</td>'."\r\n");
						}
						echo "</tr></table>";      	 
					}
				?>
				<div id="coste"></div><input type="hidden" name="price" id="numCoste" value="0">
				<?php
					$js = 'id="buttonConfirma" disabled onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url('reservas/confirm').'\'; document.getElementById(\'frmReserva\').submit(); "';
					echo form_button('buttonConfirma', 'Confirma tu reserva!', $js);

					echo form_close();
      	 ?>

	</div>

</div>

