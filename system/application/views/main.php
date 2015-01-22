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

<body>

<?php //$this->load->view('fb_login'); ?>
<?php include ("ie6.inc"); ?>

<?php if(isset($header)) echo $header; ?>

<?php if(isset($migas)) echo $migas; ?>
  
<!-- >>>> Inicio Main  <<<<<-->

<div class="main">
<br clear="all" />
<?php
if(isset($js_grid)) echo $js_grid;



	# Función de gestión de mensajes que vengan por session
	if(isset($info_message) && $info_message!="") {
	?>	
	
	<div id="dialog-message" style="width: 532px; z-index: 99; margin: auto; position:absolute; margin-left: 200px;">
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

	<div id="error-message" style="width: 532px; z-index: 99; margin: auto; position:absolute; margin-left: 200px;">
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
			$( "#error-message" ).hide( 'drop', options, 1000 );	
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
		echo form_open(current_url(), $attributes);
	}

    if(isset($page) && $page!="") $this->load->view($page);
		if(isset($main_content)) echo $main_content;
		
		
	if(isset($form_name) && $form_name!="") {
		echo form_close();
	}
	
	?>


 
 
  
  </div>

<div class="push"></div>
</div>  
    <!-- >>>> Fin Main  <<<<<-->
 
 
<?php if(isset($footer)) echo $footer; ?>

</body>
</html>