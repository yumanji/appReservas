 <!-- >>>>Inicio Pie de pagina <<<<<-->
 <div class="pie">
 	<div class="footer">
 		<a href="#">Servicio Tecnico</a>  | 
 		<a target="_blank" href="<?php echo site_url('estatico/legal'); ?>">Informacion legal</a>  | 
 		<a target="_blank" href="<?php echo site_url('estatico/privacidad'); ?>">Politica de privacidad</a>  | 
 		<a target="_blank" href="<?php echo site_url('estatico/condiciones_uso'); ?>">Condiciones de uso</a><br />
 		Inter6 Gesti&oacute;n Integral S.L. &copy; 2011 - Todos los derechos reservados - <a href="www.reservadeportiva.com">www.reservadeportiva.com</a>
 		<?php
 			if(isset($added_footer)) echo $added_footer;
 		?>
 		</div>
 </div>
<!-- >>>>Fin Pie de pagina <<<<<-->
<?php
$profile=$this->redux_auth->profile();
//print_r($profile);
$perm_general = $this->config->item('chat_permission');
$perm_grupo = $this->config->item('chat_group_permission');
if($this->redux_auth->logged_in() && isset($perm_general) && $perm_general && isset($perm_grupo) && $perm_grupo[$profile->group]) {
	?>
	<!-- Zopim Live Chat Script -->
	<script type="text/javascript">
	document.write(unescape("%3Cscript src='" + document.location.protocol + "//zopim.com/?g67smHyn9r0hFMmmRE0GKLB3BNfdzQQc' charset='utf-8' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<!-- End of Zopim Live Chat Script -->
	<?php
	}
	?>

<!-- Analytics here -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $this->config->item('google_analytics_ID'); ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>