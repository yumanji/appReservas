<?php 
echo doctype(); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	//$this->load->view('meta');
	echo $meta;
	if(isset($scripts)) echo $scripts;
?>
<script src="<?php echo base_url();?>js/jquery.contextMenu.js" type="text/javascript"></script>
<link href="<?php echo base_url();?>css/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>js/jquery.tooltip.js" type="text/javascript"></script>
<link href="<?php echo base_url();?>css/jquery.tooltip.css" rel="stylesheet" type="text/css" />

</head>
<body class="twoColHybRtHdr">

    <div id="container">
      <div id="header">
				<?php	
					
					# Cabecera
					if(isset($header)) echo $header;
					
					# Funcion gestion mensajes que vengan por session
		    	if(isset($info_message) && $info_message!="") {
		   	?>	
				<div id="dialog-message" class="ui-state-highlight ui-corner-all" style="margin-top: 10px; margin-left: 200px; padding: 0 .7em;">
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
						<?php echo $info_message; ?>
					</p>
				</div>    		
		    <?php
		    	}
		    	# Fin de la gestion info
		    
		
					# Funci򬟤e gesti򬟤e errores que vengan por session
		    	if(isset($error_message) && $error_message!="") {
		    ?>	
				<div id="dialog-message" class="ui-state-error ui-corner-all" style="margin-top: 10px; margin-left: 200px; padding: 0 .7em;">
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
						<?php echo $error_message; ?>
					</p>
				</div>    		
		    <?php
		    	}
		    	# Fin de la gesti򬟤e errores
			  ?> 

      <!-- end #header --></div>
      
			<?php
				if(isset($sidebar)) {
					echo '<div id="sidebar1">';
					//echo ' <h3>Contenido de sidebar1</h3  <p>La idea es sacar aquí un listado de las proximas reservas que se esperan, para tener acceso directo o algo así..</p>';
					echo $sidebar;
					echo "<!-- end #sidebar1 --></div>";
				}
			?>
      
      
      <div id="mainContent">
			<?php
				$attributes = array('class' => 'frmReserva', 'id' => 'frmReserva');
				echo form_open('reservas', $attributes);
				
				echo '<div id="search_result">'."\r\n";
				if(isset($grid)) echo $grid;
				echo '</div>'."\r\n";
				echo form_close();
			?>
			
    	<!-- end #mainContent -->
    	</div>
    	<!-- Este elemento de eliminaci򬟳iempre debe ir inmediatamente despu豠del div #mainContent para forzar al div #container a que contenga todos los elementos flotantes hijos -->
    	<br class="clearfloat" />
    	<div id="footer">
				<?php if(isset($footer)) echo $footer; ?>
      <!-- end #footer --></div>
    <!-- end #container --></div>
    </body>
</html>