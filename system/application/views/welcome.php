<?php 
//$this->lang->load('common', 'spanish');
echo doctype(); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		//$this->load->view('meta');
		if(isset($meta)) echo $meta;
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
      	if(isset($mainDiv)) {
      		echo '<div id="'.$mainDiv.'">';
      	} else {
      		if($this->redux_auth->logged_in()) {
	      		echo '<div id="mainContent">';
	      	} else {
	      		echo '<div id="mainContent_index">';
	      	}
	      }

			# Función de gestión de mensajes que vengan por session
    	if(isset($info_message) && $info_message!="") {
    	?>	
			
			<div id="dialog-message" style="width: 532px; margin: auto;">
				<div class="ui-state-highlight ui-corner-all" style="width: 530px; padding: 4px; display: table-cell;">
					<div style="width: 500px; float: left; display: block;  padding: 5px 5px;">
						<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
							<?php echo $info_message; ?>
						</p>
					</div>
					<a style=" top: 1px; cursor: pointer; display: block; float: right; background-image: url(<?php echo base_url(); ?>images/1Be-brvKO2y.png); background-repeat: no-repeat; background-position: 0px -11px; width: 11px; height: 11px;" id="closeInfo"></a>
				</div>
			</div> 		
			<script>
				$( "#closeInfo" ).click(function () {
					options = { };
					$( "#dialog-message" ).hide( 'drop', options, 1000 );	
				});
			</script>
    <?php
    	}
    	# Fin de la gestión de info
    

			# Función de gestión de errores que vengan por session
    	if(isset($error_message) && $error_message!="") {
    	?>	

			<div id="dialog-message" style="width: 532px; margin: auto;">
				<div class="ui-state-error ui-corner-all" style="width: 530px; padding: 4px; display: table-cell;">
					<div style="width: 500px; float: left; display: block;  padding: 5px 5px;">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
							<?php echo $error_message; ?>
						</p>
					</div>
					<a style=" top: 1px; cursor: pointer; display: block; float: right; background-image: url(<?php echo base_url(); ?>images/1Be-brvKO2y.png); background-repeat: no-repeat; background-position: 0px -11px; width: 11px; height: 11px;" id="closeError"></a>
				</div>
			</div>    		
			<script>
				$( "#closeError" ).click(function () {
					options = { };
					$( "#dialog-message" ).hide( 'drop', options, 1000 );	
				});
				$('#closeError').mouseover(function() {
				  $( "#closeError" ).css('background-position', '0px -33px');
				});
				$('#closeError').mouseout(function() {
				  $( "#closeError" ).css('background-position', '0px -11px');
				});
			</script>
    <?php
    	}
    	# Fin de la gestión de errores
    
			if(isset($form_name) && $form_name!="") {
				$attributes = array('class' => $form_name, 'id' => $form_name);
				echo form_open('', $attributes);
			}

		    if(isset($page) && $page!="") $this->load->view($page);
				if(isset($main_content)) echo $main_content;
				
				
			if(isset($form_name) && $form_name!="") {
				echo form_close();
			}
	
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