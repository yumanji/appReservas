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

<body class="twoColHybLtHdr">
   <noscript>This site just doesn't work, period, without JavaScript</noscript>
<?php
if(isset($js_grid)) echo $js_grid;
?>

    <div id="container">
        <?php if(isset($header)) echo $header; ?>
      <!-- end #header -->

      
        <?php 
        	if(isset($menu) && trim($menu)!="") {
        		echo '<div id="sidebar1">';
        		echo $menu; 
        		echo '<!-- end #sidebar1 --></div>';
        	}
        	?>
      
      
	    <?php
      	if($this->redux_auth->logged_in()) {
      		echo '<div id="mainContent">';
      	} else {
      		echo '<div id="mainContent_index">';
      	}
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
    
      	
      	if(isset($form)) {
      		$attributes = array('class' => $form, 'id' => $form);
					echo form_open($this->uri->uri_string(), $attributes);
				}


		    if(isset($page) && $page!="") $this->load->view($page);
				if(isset($search_fields)) echo $search_fields;
				if(isset($main_content)) echo $main_content;
				
				if(isset($form)) echo form_close();

			?>
    	<!-- end #mainContent --></div>
    	<!-- Este elemento de eliminación siempre debe ir inmediatamente después del div #mainContent para forzar al div #container a que contenga todos los elementos flotantes hijos -->
    	<!--<br class="clearfloat" />-->
    	<div  class="clearfloat"></div>
    	<div id="footer">
			<?php if(isset($footer)) echo $footer; ?>
      <!-- end #footer --></div>
    <!-- end #container --></div>
    </body>
    

</html>