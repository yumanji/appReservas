<?php
if(isset($noticias_list) && count($noticias_list)>0) {
	echo '<ul class="listado">'."\r\n";
	
	foreach($noticias as $noticia) {
		
		echo '<li><a href="'.$noticia[3].'">'.$noticia[0].'</a><p>'.$noticia[1].' (publicada el '.$noticia[2].')</p></li>'."\r\n";

	}	// Fin del foreach
	echo '</ul>';
} else echo 'No hay noticias';
?>