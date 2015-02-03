<h1><?php echo $this->lang->line('welcome_title').', '.$this->session->userdata('user_name'); ?></h1>
	<script type="text/javascript">
	
	function buscar() {
		<?php
			echo $validation_script;
		?>
		document.getElementById('frmReserva').action='<?php echo site_url('reservas/search'); ?>'; 
		document.getElementById('frmReserva').submit();
	}
	</script>
<p><?php echo $this->lang->line('welcome_text'); ?></p>
	<div id="search_fields">
      	<?php  
      		$attributes = array('class' => 'frmReserva', 'id' => 'frmReserva');
					echo form_open(site_url('reservas'), $attributes);

					if(isset($search_fields) && $search_fields!="") echo $search_fields;

					//echo form_submit('mysubmit', 'Registrarse!');
					/*
					$data = array(
					    'name' => 'button',
					    'id' => 'button',
					    'value' => 'true',
					    'type' => 'reset',
					    'content' => 'Reset'
					);
					echo form_button($data);
					
					$js = 'id="buttonSubmit" onClick="buscar();"';
					echo form_button('buttonSubmit', 'Busca tu pista!', $js); 
					*/     	 
				?>
      	<?php  
					echo form_close();
      	 ?>

	</div>


