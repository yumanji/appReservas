<?php
	# Valor por defecto para la carga de la librería javascript de las reservas
	if(!isset($lib_reservas)) $lib_reservas = '1';

	$meta = array(
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'description', 'content' => 'Reserva Deportiva'),
        array('name' => 'keywords', 'content' => 'tenis, padel, reserva, pista, club, cristal, muro'),
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'content-script-type', 'content' => 'text/javascript', 'type' => 'equiv'),
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
    );

	echo meta($meta);

	#Favicon
	echo link_tag(base_url().'images/favicon.ico', 'shortcut icon', 'image/ico');

	# Hoja de estilo principal de la aplicación
	echo link_tag(base_url().'css/estilos.css');
	echo link_tag(base_url().'css/estilosv02.css');

	echo link_tag(base_url().'css/basic.css');
	
	# Hoja de estilo de cajas de inicio
	echo link_tag(base_url().'css/cajasinicio.css');

	# Mas hojas de estilo
	//echo link_tag(base_url().'css/print.css');

	#Titulo (con valor por defecto)
	if(isset($title) && $title!="") echo '<title>'.$title.'</title>';
	else echo '<title>'.$this->config->item('app_title').'</title>';

	echo link_tag(base_url().'css/custom-theme/jquery-ui-1.8.4.custom.css');

?>
<!--[if IE 6]> <link rel="stylesheet" type="text/css" href="css/ie6.css" media="all" /> <![endif]-->
<!--[if IE 7]> <link rel="stylesheet" type="text/css" href="css/ie7.css" media="all" /> <![endif]-->
<!--[if IE 8]> <link rel="stylesheet" type="text/css" href="css/ie8.css" media="all" /> <![endif]-->
<?php

  echo '<script type="text/javascript" src="'.base_url().'js/jquery-1.4.2.js"></script>'."\r\n";

	echo link_tag(base_url().'css/enhanced.css');
  echo '<script type="text/javascript" src="'.base_url().'js/jQuery.tree.js"></script>'."\r\n";
?>


  
<!-- Menu desplegable -->
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/menu.js"></script>  -->
<!-- Fin del menu -->

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.6.custom.min.js"></script> 
<?php if($lib_reservas) { ?><script type="text/javascript" src="<?php echo base_url(); ?>js/appReservas.js"></script><?php } ?>

<?php if(isset($lib_tooltip) && $lib_tooltip) { ?>
	<script src="<?php echo base_url();?>js/jquery.tooltip.js" type="text/javascript"></script>
	<link href="<?php echo base_url();?>css/jquery.tooltip.css" rel="stylesheet" type="text/css" />
<?php } ?>
	
<?php 

if(isset($lib_jqgrid) && $lib_jqgrid) { ?>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>css/ui.jqgrid.css" />
	<script src="<?php echo base_url(); ?>js/grid.locale-es.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/jquery.jqGrid.min.js" type="text/javascript"></script>
<?php } ?>
<?php 

if(isset($lib_calendar) && $lib_calendar) { 
	echo link_tag(base_url().'css/dailog.css');
	echo link_tag(base_url().'css/calendar.css');
	echo link_tag(base_url().'css/dp.css');
	echo link_tag(base_url().'css/alert.css');
	echo link_tag(base_url().'css/main.css');
	?>
	<script src="<?php echo base_url(); ?>js/calendar/datepicker_lang_US.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.datepicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.alert.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.ifrmdailog.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/Common.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/wdCalendar_lang_US.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.calendar.js" type="text/javascript"></script>
<?php } 

if(isset($lib_calendar2) && $lib_calendar2) { 
	echo link_tag(base_url().'css/dailog.css');
	echo link_tag(base_url().'css/calendar.css');
	echo link_tag(base_url().'css/dp.css');
	echo link_tag(base_url().'css/alert.css');
	echo link_tag(base_url().'css/main.css');
	?>
	<script src="<?php echo base_url(); ?>js/calendar/datepicker_lang_US.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.datepicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.alert.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.ifrmdailog.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/Common.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/wdCalendar_lang_US.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/calendar/jquery.calendar.v2.js" type="text/javascript"></script>
<?php } ?>

<?php
	//Flexigrid
	if(isset($enable_grid) && $enable_grid) {
		echo link_tag(base_url().'css/flexigrid.css');
		echo '<script type="text/javascript" src="'.base_url().'js/flexigrid.pack.js"></script>';
	}

	
	# Carga de elementos extra
	if(isset($extra)) echo $extra;
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
  

