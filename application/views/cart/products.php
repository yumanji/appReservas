<?php 
	$this->CI =& get_instance();
	
	$search_permission = $this->config->item('main_search_permission');
	$perfil=$this->CI->redux_auth->profile();
	
?>

	<div class="admin_cart">
		<a href="<?php echo site_url('cart/admin_cart');?>">Administrar Stock</a>
	</div>
	<div class="products" >
		<ul class="gallery clearfix products" style="width: 85%">
			<?php foreach($products as $p): ?>
			<li>
				<h3><?php echo $p['name']; ?></h3>
				<a href="<?php echo base_url().'images/products/'.$p['image']?>" rel="prettyPhoto" title="This is the description">
					<img src="<?php echo base_url().'images/products/'.$p['image']?>" alt="" width="100 px" height="100px"/>
								<?php //echo img(array('src' => 'images/products/'.$p['image'], 'alt' => '')); ?>
					
				</a>
				<small>&euro;<?php echo $p['price']; ?></small>
				<?php echo form_open('cart/add_cart_item'); ?>
					<fieldset>
						<p>
						<label>Cantidad</label>
						<?php echo form_input('quantity', '1', 'maxlength="3"'); ?>
						&nbsp;
						<?php 
							echo $p['stock']; 
							echo form_hidden('product_stock', $p['stock']);
						?>
						</p>
						<p>
							<label>PVD</label>
							<?php echo form_input(array('name' => 'price_pvd', 'size' => '5')); ?>&euro;
						</p>
						<?php echo form_hidden('product_id', $p['id']); ?>
						<?php echo form_submit('add', 'Add'); ?>
					</fieldset>
				<?php echo form_close(); ?>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
		<?php 
			$attributes = array('id' => 'update_cart_form'); 
			echo form_open('cart/update_cart',$attributes);
		?>
		<div class="cart_list" style="width: 85%">
			<h3>Tu Carro de la compra</h3>
			<div id="cart_content">
				<?php 
					echo $cart_board;
				?>
			</div>
		</div>
		<?php 
			echo form_close();
		?> 
	<?php echo form_open('cart/order_charge_cart'); ?>
		<div class="formas_pago" style="width: 85%">
			<p>
			<label>
				<span>Forma de pago*</span>
					<div class="formas_pago">
						<?php 
						if(isset($paymentMethods) && count($paymentMethods)>0) {
							echo '<select name="paymentway" id="paymentway">';
							foreach ($paymentMethods as $clave =>$method) {
								echo '<option value="'.$clave.'">'.$method.'</option>';
							}
							echo '</select>';
							echo form_submit('cart/order_charge_cart', 'Cobrar');
						}
						?>
					</div>
					<div class="shop_customer">
						<span>Cliente</span>
						 <!--buscador -->
						
						<?php if($search_permission[$perfil->group]) { 
							$search_user = array(
						    'name'        => 'buscausuariosshop',
						    'id'          => 'buscausuariosshop',
						    'class'          => 'buscar',
						  	'size'        => '20'	
						  );
						?>
						<?php echo '<input type="hidden" id="id_shop_user" name="id_shop_user">'.form_input($search_user);		?>
							<script>
						
							$(function() {
								$( "#buscausuariosshop" ).autocomplete({
									source: function(req, add){
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
										$("#id_shop_user").val(ui.item.id);
						
									}
								});
							});
							</script>
						<?php } ?>
					</div>
			</label>
		</p>
		</div>
	<?php 
		echo form_close();
	?>
	
		