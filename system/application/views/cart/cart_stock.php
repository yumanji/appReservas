<form id="products_form" action="<?php echo base_url(); ?>index.php" method="POST">
	<input id="element_id" type="hidden" name="element_id"/>
</form>

<div class="products_stock" >
	<?php echo anchor('#', "Crear Producto",array('class'=>'new_product')); ?>
	<table id="products_list" class="tablesorter" border="1" width="85%">
		<thead>
			<tr>
				<th>Imagen</th>
				<th>Nombre</th>
				<th>Stock</th>
				<th>Precio</th>
				<th>IVA</th>
				<th class="table_icons"> </th>
			</tr>
		</thead>
		
		<tbody>
		<?php 
			$edit_img = img(array('src'=>'images/buttons/edit.png' ,'border'=>'0' ,'title'=>'Editar'));
			$delete_img = img(array('src'=>'images/buttons/delete.png' ,'border'=>'0' ,'title'=>'Borrar'));
			foreach($products as $p){	?>	
			<tr id="<?php echo $p['id'] ?>">
				<td>
					<a href="<?php echo base_url().'images/products/'.$p['image']?>" rel="prettyPhoto" title="This is the description">
						<img src="<?php echo base_url().'images/products/'.$p['image']?>" alt="" width="100 px" height="100px"/>
					</a>
				</td>
				<td><?php echo $p['name']?></td>
				<td><?php echo $p['stock']?></td>
				<td><?php echo $p['price']?></td>
				<td><?php echo $p['iva']?></td>
				<td class="table_icons">
					<?php echo anchor('#', $edit_img,array('class'=>'edit_link')); ?>
					<?php echo anchor('#', $delete_img,array('class'=>'delete_link')); ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<div id="update_stock_element">
	<span>Gestion de Stock</span>
	<fieldset>
		<?php echo form_open('cart/stock_update_item',array('id'=>'gestion_stock','name'=>'gestion_stock')); ?>
			<input id="product_id" type="hidden" name="product_id"/>
			<label>Stock</label>
			<?php 
				$dataS = array(
	              'name'        => 'stock',
	              'id'          => 'stock',
	              'value'       => ''
	            );
				echo form_input($dataS); ?>
			<br/><label>Precio</label>
			<?php $dataP = array(
	              'name'        => 'price',
	              'id'          => 'price',
	              'value'       => ''
	            );
	            echo form_input($dataP); ?>
			<br/><label>IVA</label>
			<?php $dataI = array(
	              'name'        => 'iva',
	              'id'          => 'iva',
	              'value'       => ''
	            );
	            echo form_input($dataI); ?>
			<br/><label>Nombre</label>
			<?php $dataN = array(
	              'name'        => 'name',
	              'id'          => 'name',
	              'value'       => ''
	            );
	            echo form_input($dataN); ?>
			&nbsp;<?php echo anchor('#', "Modificar Producto",array('class'=>'update_link')); ?>
			&nbsp;<?php //echo form_submit('modify', 'Modify'); ?>
			<?php echo anchor('#', "Alta Producto",array('class'=>'add_product')); ?>
			<div>
				<a id="cambiar_foto" style="cursor: pointer; text-decoration: underline;">cambiar imagen</a>
			</div>
			<?php echo form_close(); ?>
			<div id="upload_dialog" title="Subir fotografia">
				<p>Subida de fotograf&iacute;a<br/>
					<?php 
						echo form_open_multipart('cart/upload_photo', array('name' => 'frmUpload', 'id' => 'frmUpload'));
						echo form_upload(array('name' => 'archivo', 'id' => 'archivo', 'style' => 'width:200px'));
					?>
						<input id="product_image_id" type="hidden" name="product_image_id"/>
					<?php 
						echo form_close();
					?>
				</p>
			</div>
		<br/>
	</fieldset>
</div>
	