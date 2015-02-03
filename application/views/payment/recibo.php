<?php 
//$this->lang->load('common', 'spanish');
echo doctype(); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		//$this->load->view('meta');
		if(isset($meta)) echo $meta;
	?>
</head>
<script language="Javascript1.2">
  <!--
  function printpage() {
  window.print();
  }
  //-->
</script>
<body onload="printpage()">
	<table border="0" class="Estilo2">
	  <tr height="1">
	    <td width="129" height="1"></td>
	    <td width="70"></td>
	    <td width="84"></td>
	  </tr>

	<?php if(isset($header)) echo $header; ?>

  
	<!-- >>>> Inicio Main  <<<<<-->
	<tr>
		<td colspan="3">
			<?php
		    
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
		</td>
	</tr>

   <!-- >>>> Fin Main  <<<<<-->
 
 
	<?php if(isset($footer)) echo $footer; ?>
	

	</table>
</body>
</html>