<?php
	if(!isset($header_style) || $header_style=="") $header_style = "cabecera";
?>
<!-- >>>> Inicio Cabecera <<<<<-->
<div class="<?php echo $header_style; ?>">
<!--logo cliente -->
<div  class="logo">
<?php 
	$imagen= img( array('src'=>'images/logo_cliente.png', 'border'=>'0', 'alt' => 'logo cliente', 'align'=>'left')); 
	//echo $this->config->item('club_url');
	$direccion = $this->config->item('club_url');
	if(!isset($direccion) || $direccion=='') $direccion = site_url();
	echo anchor($direccion, $imagen, 'title="Ir a pagina del club" target="_blank"');
?>
</div>

  
<div class="lrd">	<?php 
	$logo_reserva = img( array('src'=>'images/logo_reservadeportiva.png', 'border'=>'0', 'alt' => 'logo')); 
	echo anchor(site_url(), $logo_reserva, 'title="Inicio"');
	?>
</div>
<!--nivel de navegacion 1 -->
 
 
</div>
 <!-- >>>> FinCabecera <<<<<-->
    
