<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
	#Titulo (con valor por defecto)
	if(isset($title) && $title!="") echo '<title>'.$title.' - '.$this->config->item('app_title').'</title>';
	else echo '<title>Recibo - '.$this->config->item('app_title').'</title>';
?>
</head>

<style type="text/css">

body {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #000;
	white-space: normal;
	display: block;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

.Titulo1 {
	font-size: 18px;
	font-weight: bold;
	color: #069;
	padding-left: 5px;
	text-transform: none;
	font-variant: small-caps;
}

.Titulo2 {
	font-size: 12px;
	font-weight: bold;
	color: #069;
}

.Estilo2 {font-size: 10px}

</style>
