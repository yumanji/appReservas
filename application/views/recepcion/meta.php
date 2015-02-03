<?php 
	$meta = array(
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'description', 'content' => 'Reserva Deportiva'),
        array('name' => 'keywords', 'content' => 'tenis, padel, reserva, pista, club, cristal, muro'),
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
    );

	echo meta($meta); 	
	
	#Favicon
	echo link_tag(base_url().'images/favicon.ico', 'shortcut icon', 'image/ico');
	
	# Hoja de estilo principal de la aplicación
	echo link_tag(base_url().'css/estilos_recepcion_v2.css');
	
	#Titulo (con valor por defecto)
	if(isset($title) && $title!="") echo '<title>'.$title.'</title>';
	else echo '<title>ReservaDeportiva</title>';
	
	echo link_tag(base_url().'css/custom-theme/jquery-ui-1.8.4.custom.css');
?>
  
<!-- Menu desplegable -->
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/menu.js"></script>  -->
<!-- Fin del menu -->

  <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.4.2.js"></script> 
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.6.custom.min.js"></script> 
	<!--<script type="text/javascript" src="<?php echo base_url(); ?>js/appReservas.js"></script>-->
<?php
	//Flexigrid
	if(isset($enable_grid) && $enable_grid) {
		echo link_tag(base_url().'css/flexigrid.css');
		echo '<script type="text/javascript" src="'.base_url().'js/flexigrid.pack.js"></script>';
	}

?>

  <script type="text/javascript">	
		function createRequestObject() {
			// find the correct xmlHTTP, works with IE, FF and Opera
			var xmlhttp;
			try {
		  	xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
		  }
		  catch(e) {
		    try {
		    	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    catch(e) {
		    	xmlhttp=null;
		    }
		  }
		  if(!xmlhttp&&typeof XMLHttpRequest!="undefined") {
		  	xmlhttp=new XMLHttpRequest();
		  }
			return  xmlhttp;
		}
  </script> 