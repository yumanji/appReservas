<?php 
	$this->CI =& get_instance();
	$perfil=$this->CI->redux_auth->profile();
	
	$search_permission = $this->config->item('main_search_permission');
	
	# Permiso para ver los informes
	$report_permission_array = $this->config->item('reports_permission');
	$report_permission = FALSE;
	if(isset($report_permission_array[$perfil->group])) $report_permission = $report_permission_array[$perfil->group];
	
	# Permiso para ver calendario
	$calendar_permission_array = $this->config->item('calendar_permission');
	$calendar_permission = FALSE;
	if(isset($calendar_permission_array[$perfil->group])) $calendar_permission = $calendar_permission_array[$perfil->group];
	
	# Permiso para ver actividades
	$activities_permission_array = $this->config->item('activities_permission');
	$activities_permission = FALSE;
	if(isset($activities_permission_array[$perfil->group])) $activities_permission = $activities_permission_array[$perfil->group];
	
	# Permiso para ver tienda
	$shop_permission_array = $this->config->item('shop_permission');
	$shop_permission = FALSE;
	if(isset($shop_permission_array[$perfil->group])) $shop_permission = $shop_permission_array[$perfil->group];
	
	//print_r($perfil);
	
	# Array de posibles opciones del menu seleccionadas
	$selected_option = array( 'welcome' => FALSE, 'reservas' => FALSE, 'gestion' => FALSE, 'informes' => FALSE, 'calendar' => FALSE, 'activities' => FALSE, 'notifications' => FALSE, 'cart' => FALSE, 'help' => FALSE);
	
# Gestión de la opción marcada en función de la Url
	if($this->uri->segment(1) && isset($selected_option[$this->uri->segment(1)])) $selected_option[$this->uri->segment(1)] = TRUE;
	elseif($this->uri->segment(1)) {
		# casos especiales
		switch($this->uri->segment(1)) {
			case 'reservas_gest':
			case 'facturacion':
			case 'lessons':
			case 'ranking':
			case 'retos':
			case 'users':
				$selected_option['gestion'] = TRUE;
			break;
			
			default:
				$selected_option['welcome'] = TRUE;
			break;
		}
	}	
	else $selected_option['welcome'] = TRUE;
	
		
?>

<!--navegacion  -->
<div class="navega1">
<?php echo img( array('src'=>'images/nav1iqda.jpg', 'border'=>'0',  'alt' => 'borde',  'class'=>'nimg', 'align'=>'absmiddle')); ?>

<ul style="margin-top: -2px;">

<li><a href="<?php echo site_url(); ?>" <?php if($selected_option['welcome']) echo 'class="actual"'; ?>>inicio</a></li>
<?php if($perfil->group > 4) { ?><li><a href="<?php echo site_url('reservas/index/'.time()); ?>" <?php if($selected_option['reservas']) echo 'class="actual"'; ?>>reservar</a></li><?php } ?>
<?php if($perfil->group <= 4) { ?><li><a href="<?php echo site_url('gestion'); ?>" <?php if($selected_option['gestion']) echo 'class="actual"'; ?>>gesti&oacute;n</a></li><?php } ?>
<?php if($report_permission) { ?><li><a href="<?php echo site_url('informes'); ?>" <?php if($selected_option['informes']) echo 'class="actual"'; ?>>informes</a></li><?php } ?>
<?php if($calendar_permission) { ?><li><a href="<?php echo site_url('calendar'); ?>" <?php if($selected_option['calendar']) echo 'class="actual"'; ?>>calendario</a></li><?php } ?>
<?php if($activities_permission) { ?><li><a href="<?php echo site_url('activities'); ?>" <?php if($selected_option['activities']) echo 'class="actual"'; ?>>actividades</a></li><?php } ?>
<?php if($perfil->group <= 4) { ?><li><a href="<?php echo site_url('notifications'); ?>" <?php if($selected_option['notifications']) echo 'class="actual"'; ?>>comunicaciones</a></li><?php } ?>
<?php if($shop_permission) { ?><li><a href="<?php echo site_url('cart'); ?>" <?php if($selected_option['cart']) echo 'class="actual"'; ?>>tienda</a></li><?php } ?>
<li><a href="<?php echo site_url('help'); ?>" <?php if($selected_option['help']) echo 'class="actual"'; ?>>ayuda</a></li>
</ul>
<?php echo img( array('src'=>'images/nav1dcha.jpg', 'border'=>'0',  'alt' => 'borde',  'class'=>'nimg', 'align'=>'absmiddle')); ?>

 <!--buscador -->

<?php if($search_permission[$perfil->group]) { 
	


	$search_user = array(
    'name'        => 'buscausuarios',
    'id'          => 'buscausuarios',
    'class'          => 'buscar',
  	'size'        => '20'	
  );
	
	
?>
	<form class="buscador" method="POST">
<?php echo '<input type="hidden" id="id_search_user" name="id_search_user">'.form_input($search_user);		?>
		<!--<input name="" type="text" class="buscar" /> -->
		<img src="<?php echo base_url(); ?>images/btn_buscar.jpg" onClick="if($('#id_search_user').val()!='') location.href='<?php echo site_url('users/detail'); ?>/'+$('#id_search_user').val();"/>
	</form>
	<script>

	$(function() {
		$( "#buscausuarios" ).autocomplete({
			source: function(req, add){
				//var parametros = req.split("=");
				//dumpProps(req);
				//alert(req.term);
				//pass request to server
				$.getJSON("<?php echo site_url('users/get_Names'); ?>/"+ req.term, function(data) {

					//create array for response objects
					var suggestions = [];

					//process response
					$.each(data, function(i, val){
						//{ data:val.id, value:val.name, result:val.name };
					suggestions.push({id:val.id, value:val.value});
				});

				//pass array to callback
				add(suggestions);
			});
		},
			minLength: 2,
			select: function( event, ui ) {
				$("#id_search_user").val(ui.item.id);

			}
		});


				
	});
	</script>

<?php } ?>

</div>
<!--fin navegacion  -->
