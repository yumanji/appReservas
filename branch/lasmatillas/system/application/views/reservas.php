<?php 
//$this->lang->load('common', 'spanish');
echo doctype(); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		//$this->load->view('meta');
		echo $meta;
		if(isset($scripts)) echo $scripts;
	?>

</head>
<body>
	<?php 
		if(isset($error) && $error!="") $data=array('error_message' => $error);
		else $data=array('error_message' => '');
		//echo $this->session->userdata('session_id');
		
		if(isset($header)) echo $header;
		//$this->load->view('header', $data);
		if(isset($menu)) echo $menu;
		//$this->load->view('menu', $menu);
		//print_r($info);
	?>
	
  <div id="main">
	<?php	

			# Función de gestión de mensajes que vengan por session
    	if(isset($info_message) && $info_message!="") {
    	?>	

			
			<div id="dialog-message" class="ui-state-highlight ui-corner-all" style="margin-top: 10px; margin-left: 200px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					<?php echo $info_message; ?>
				</p>
			</div>    		
    <?php
    	}
    	# Fin de la gestión de info
    

			# Función de gestión de errores que vengan por session
    	if(isset($error_message) && $error_message!="") {
    	?>	

			
			<div id="dialog-message" class="ui-state-error ui-corner-all" style="margin-top: 10px; margin-left: 200px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<?php echo $error_message; ?>
				</p>
			</div>    		
    <?php
    	}
    	# Fin de la gestión de errores
    

		$attributes = array('class' => 'frmReserva', 'id' => 'frmReserva');
		echo form_open('reservas', $attributes);

		if(isset($filters)) echo $filters;
		
        if(isset($page) && $page!="") $this->load->view($page);
		if(isset($main_content)) echo $main_content;

		echo form_close();
	?>
		
  </div>
	<?php	
		if(isset($footer)) echo $footer;
		//$this->load->view('footer');
	?>
</body>
</html>