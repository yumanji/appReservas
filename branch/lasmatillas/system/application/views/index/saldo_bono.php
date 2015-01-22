<img src="images/saldo.jpg" align="left" />
<?php if(floatval($saldo)>0) { ?><p class="titulo2">Te quedan<span class="euros"><?php echo number_format($saldo, 2); ?> &euro;</span><span class="titulop">&Uacute;lt. recarga: <?php if(isset($ultimo_pago)) echo $ultimo_pago; else echo ' - '; ?></span></p>
<?php } else { ?><p class="titulo2">No tienes saldo</p>
<?php } ?>
<span  class="separador"></span>
<?php 
/*
<a href="<?php echo site_url('users/add_prepaid/'.$user_id);?>" class="boton" >Recargar</a>

*/
?>
