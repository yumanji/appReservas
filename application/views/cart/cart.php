	<?php if(!$this->cart->contents()):
		echo 'No hay productos en el carro de compra.';
	else:
	?>

	<table width="100%" cellpadding="0" cellspacing="0" border="1" id="cart_products">
		<thead>
			<tr>
				<td>cantidad</td>
				<td>Descripcion</td>
				<td>Precio</td>
				<td>IVA</td>
				<td>PVP</td>
				<td>PVD</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; 
				$delete_img = img(array('src'=>'images/buttons/delete.png' ,'border'=>'0' ,'title'=>'Borrar'));
				
				foreach($this->cart->contents() as $items): ?>

			<?php echo form_hidden('rowid[]', $items['rowid']); ?>
			<tr <?php if($i&1){ echo 'class="alt"'; }?> id="<?php echo $items['id'] ?>">
		  		<td>
		  			<?php echo form_input(array('name' => 'qty[]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?>
		  		</td>

		  		<td><?php echo $items['name']; ?></td>

		  		<td><?php echo $this->cart->format_number($items['price']); ?>&euro;</td>
		  		<td><?php echo $this->cart->format_number($items['iva']); ?>%</td>
		  		<td><?php echo $this->cart->format_number($items['pvp']); ?></td>
		  		<td><?php echo $this->cart->format_number($items['pvd']); ?>&euro;
		  			<?php echo form_hidden(array('name' => 'pvd[]','value' => $items['pvd'], 'size' => '10')); ?>
		  		</td>
		  		<td class="table_icons">
					<?php echo anchor('#', $delete_img,array('class'=>'delete_cart_item_link')); ?>
				</td>
		  		<?php echo form_hidden('sold_product_id[]', $items['id']); ?>
		  	</tr>

		  	<?php $i++; ?>
			<?php endforeach; ?>

			<tr>
	 		 	<td></td>
	 		 	<td><strong>Total</strong></td>
	 		 	<td>
	 		 		<?php echo $this->cart->format_number($this->cart->total()); ?>&euro;
	 		 		<?php echo form_hidden('total',$this->cart->format_number($this->cart->total()));?>
	 		 	</td>
				<td></td>
				<td>
					<?php echo $this->cart->format_number($this->cart->total_pvp()); ?>&euro;
				<?php echo form_hidden('total_pvp',$this->cart->format_number($this->cart->total_pvp()));?>
				</td>
				<td>
					<?php //echo $this->cart->format_number($this->cart->total_pvd()); ?>
					<?php echo form_input('total_pvd',$this->cart->format_number($this->cart->total_pvd()));?>&euro;
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<p><?php 
		$data = array(
			    'name'        => 'update_cart_button',
			    'id'          => 'update_cart_button',
			    'value'       => 'Actualizar Carrito'
			    );
		echo form_submit($data); 
		echo anchor('cart/empty_cart', 'Empty Cart', 'class="empty"');?></p>
	<?php
	endif;
	?>
	