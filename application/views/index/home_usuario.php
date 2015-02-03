<script type="text/javascript">
$(function()
{

$("#mostrar").click(function(event) {
event.preventDefault();
$("#caja").slideToggle();
});

$("#caja a").click(function(event) {
event.preventDefault();
$("#caja").slideUp();
});




});
</script>

<style>
#caja2 { display: block; }
#caja_foto { display: block; }
#caja_reservas { display: block; }
#caja_pagos { display: block; }
#caja_clima { display: block; }
#caja_saldo { display: block; }
#caja3 { display: block; }
#caja4 { display: block; }
#caja6 { display: block; }
#caja { display: block; }
</style>
     
<!--proximas reservas -->
<div class="box_foto">
<a href="#" id="mostrar_foto" class="titulo">Perfil de usuario</a>
<div id="caja_foto">
<table cellspacing="0">
	<tr>
		<td>
        	<?php 
        		if(trim($usuario['avatar'])!='') echo img( array('src'=>'images/users/'.$usuario['avatar'], 'border'=>'0',  'width'=>'90',  'alt' => 'Avatar',  'align'=>'absmiddle')); 
        		else echo img( array('src'=>'images/avatar.jpg', 'border'=>'0',  'width'=>'90',  'height'=>'110"', 'alt' => 'foto',  'align'=>'absmiddle'));
        	?>		
		</td>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center">
			<span  class="separador">
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url('users/profile');?>" class="boton" >Ver perfil</a>&nbsp;&nbsp;&nbsp;
			</span><br>
			<span  class="separador">
			<a href="#" class="boton" >Cambiar foto</a>
			</span>
		</td>
	</tr>
</table>
</div>
</div>

<!--saldo disponible -->
<div class="box_reservas">
<a href="#" id="mostrar_reservas" class="titulo">Pr&oacute;ximas reservas</a>
<div id="caja_reservas"  class="fclear">
<?php if(isset($reservas)) echo $reservas; ?>
</div>    
</div>    
<!--proximas actividades -->
<div class="box_pagos">
<a href="#" id="mostrar_pagos" class="titulo">Pr&oacute;ximos pagos</a>
<div id="caja_pagos">
<?php if(isset($pagos)) echo $pagos; ?>
</div>  
</div>      
   <br clear="all" /> 
      <br clear="all" /> 
      
      
<div class="box_clima">
<a href="#" id="mostrar_clima" class="titulo">El tiempo</a>
<div id="caja_clima">
			<!-- www.TuTiempo.net - Ancho: 305px - Alto:113px -->
			<?php echo $this->config->item('club_weather'); ?>
</div>
</div>

<!--saldo disponible -->
<div class="box_saldo">
<a href="#" id="mostrar_saldo" class="titulo">Saldo disponible</a>
<div id="caja_saldo"  class="fclear">
<?php if(isset($saldo_bono)) echo $saldo_bono; ?>
</div>    
</div>    
<!--proximas actividades -->
<div class="box4">
<a href="#" id="mostrar4" class="titulo">Pr&oacute;ximas actividades</a>
<div id="caja4">
<!--
	<span  class="separador"><a href="#" class="close"><img src="images/cerrar.gif" /> cerrar</a>
	</span>
-->
<table cellspacing="0">
	<tr>
		<td><img src="images/actividades.jpg" align="left" alt="Actividades"/></td>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="center">
			<ul class="lista fclear">
			<li>03/10/2010  /   15:00 - 19:00</li>
			<li>03/10/2010  /   15:00 - 19:00</li>
			</ul>
		</td>
	</tr>
</table>
   <br clear="all" /> 
<span  class="separador">
<a href="#" class="boton" >Ver mas</a>&nbsp;&nbsp;<a href="<?php echo site_url('retos/publico'); ?>" class="boton" >Ver retos</a>&nbsp;&nbsp;<a href="<?php echo site_url('ranking/publico'); ?>" class="boton" >Ranking</a>
</span>
</div>  
</div>      
   <br clear="all" /> 
      <br clear="all" />       



   <!--wanaplay -->
   <!--
<div class="box5">
<a href="#" id="mostrar5" class="titulo"><img src="images/arrow.gif"  />Wanaplay</a>
<div id="caja5">
<span  class="separador"><a href="#" class="close"><img src="images/cerrar.gif" /> cerrar</a></span>
 

<table width="100%" border="0" class="tablist fclear">
  <tr>
    <td><img src="images/bullet.jpg" width="11" height="10" />Partida nivel 3	</td>
    <td>Lunes, 18:00h	</td>
    <td>2 plazas libres	</td>
    <td><a href="#">Apuntarme</a></td>
  </tr>
  <tr>
    <td><img src="images/bullet.jpg" width="11" height="10" />Partida nivel 3 </td>
    <td>Lunes, 18:00h </td>
    <td>2 plazas libres </td>
    <td><a href="#">Apuntarme</a></td>
  </tr>
</table>

</div>  
</div>      
  --> 
     <!--proximos juegos -->
 <!--    
<div class="box6">
<a href="#" id="mostrar6" class="titulo"><img src="images/arrow.gif"  />Ranking</a>
<div id="caja6">
<span  class="separador">
<a href="#" class="close"><img src="images/cerrar.gif" /> cerrar</a></span>
<img src="images/ranking.jpg" align="left" />
<p class="titulo2">Actualmente ocupas la posición <span class="verde"> 17</span></p>
 
<span  class="separador">
<a href="#" class="boton" >Ver ranking</a>
</span>
</div>  
</div>    
  --> 
  <!--Novedades --> 
  <br clear="all" />  
  

      <a href="#" id="mostrar" class="titulo"><img src="images/arrow.gif"  />Novedades</a>

    <div id="caja">
<span  class="separador"><a href="#" class="close"><img src="images/cerrar.gif" /> cerrar</a></span>
<br />
<?php if(isset($noticias)) echo $noticias; ?>
</div>
    
<!-- DIV para el tooltip creado desde el home -->    
<div id="tooltip" style="display:none;"></div>
<!-- Fin del div -->
