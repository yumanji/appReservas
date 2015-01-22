<?php
	if(!isset($header_style) || $header_style=="") $header_style = "cabecera";
	//print_r($profile);
	if(isset($enable_menu) && $enable_menu) {
		if(!isset($enable_menu_superior)) $enable_menu_superior = 1; 
		if(!isset($enable_navegacion)) $enable_navegacion = 1;		
	}	else {
		$enable_menu_superior = 0;
		$enable_navegacion = 0;
	}
?>

<div class="wrapper">

<!-- >>>> Inicio Cabecera <<<<<-->
<div class="<?php echo $header_style; ?>">
<!--logo cliente -->
<div  class="logo">
<?php 
	$imagen= img( array('src'=>'images/logo_cliente.png', 'border'=>'0', 'alt' => 'logo cliente', 'align'=>'left')); 
	$direccion = $this->config->item('club_url');
	if(!isset($direccion) || $direccion=='') $direccion = site_url();
	echo anchor($direccion, $imagen, 'title="Ir a pagina del club" target="_blank"');
?>
</div>

<div style="float:right; width: 720px;">
<?php
	if(isset($enable_menu_superior) && $enable_menu_superior) {
		
		echo $this->load->view('menu_superior', array(), true);
		
	}
?>
</div>
<div class="lrd">
	<?php 
	$logo_reserva = img( array('src'=>'images/logo_reservadeportiva.png', 'border'=>'0', 'alt' => 'logo')); 
	echo anchor(site_url(), $logo_reserva, 'title="Inicio"');
	?>
</div>
<!--nivel de navegacion 1 -->
<?php
	if(isset($enable_navegacion) && $enable_navegacion) {
		echo $this->load->view('menu_navegacion', array(), true);
	}
?>
<?php
	if(isset($enable_submenu) && $enable_submenu != "") {
		echo $enable_submenu;
	}
?>
 
<?php 
	$migas = $this->load->view('migas', '', true);
	if(isset($migas)) echo $migas; 
?> 
</div>
 <!-- >>>> FinCabecera <<<<<-->


    
