<?php 

class Cart_model extends Model { // Our Cart_model class extends the Model class

	// Function to retrieve an array with all product information
	function retrieve_products(){
		$query = $this->db->get('products'); // Select the table products
		return $query->result_array(); // Return the results in a array.
	}			

	// Function to retrieve a product information
	function get_detail_product(){
		$id = $this->input->post('product_id');
		$this->db->where('id',$id);
		$query = $this->db->get('products'); // Select the product
		return $query->result(); // Return the results.
	}			

	
	//modify a product 
	function add_cart_product(){
		$data = array();
		
		$id = $this->input->post('product_id'); // Assign posted product_id to $id
		$stock = $this->input->post('stock'); // Assign posted quantity to $cty
		$price = $this->input->post('price');
		$iva = $this->input->post('iva');
		$name = $this->input->post('name');

		$data = array(
               		'id'      => $id,
               		'stock'   => $stock,
               		'price'   => $price,
               		'name'    => $name,
			    	'iva'	  => $iva
            	);
		
		$this->db->insert('products', $data); 

		return ($this->db->affected_rows() >= 1) ? true : false;
	}
	
	
	//modify a product 
	function update_cart_product(){
		$data = array();
		
		$id = $this->input->post('product_id'); // Assign posted product_id to $id
		$stock = $this->input->post('stock'); // Assign posted quantity to $cty
		$price = $this->input->post('price');
		$iva = $this->input->post('iva');
		$name = $this->input->post('name');

		$data = array(
               		'id'      => $id,
               		'stock'   => $stock,
               		'price'   => $price,
               		'name'    => $name,
			    	'iva'	  => $iva
            	);
		$this->db->where('id', $id); // Select where id matches the posted id
		
		$query = $this->db->update('products', $data); 

		return 	$query;
	}
	
	//modify a product 
	function delete_cart_product(){
		$data = array();
		
		$id = $this->input->post('element_id'); // Assign posted product_id to $id

		$data = array(
       		'id'      => $id
       	);
		//$this->db->where('id', $id); // Select where id matches the posted id
		
		$query = $this->db->delete('products', $data); 

		return 	$query;
	}
	
	
	//modify a product 
	function discount_cart_stock(){
		$data = array();
		
		$lista_elementos_vendidos = $this->input->post('sold_product_id'); // Assign posted product_id to $id
		$cty = $this->input->post('qty'); // Assign posted quantity to $cty
		$i=0;
		for ($i;$i<count($lista_elementos_vendidos);$i++)
		{
			$id = $lista_elementos_vendidos[$i];
			$this->db->where('id', $id);
			$query = $this->db->get('products', 1); // Select the products where a match is found and limit the query by 1
	
			// Check if a row has matched our product id
			if($query->num_rows > 0){
	
			// We have a match!
				foreach ($query->result() as $row)
				{
					$data = array(
			           	'id'      => $id,
			           	'stock'   => $row->stock - $cty[$i]
			   		);
							
					$this->db->where('id', $id); // Select where id matches the posted id
					$query = $this->db->update('products', $data); 
				}
			}
		}
		return 	$query;
	}
	
		//modify a product 
	function update_cart_image_product($image){
		$data = array();
		$id = $this->input->post('product_image_id');
		$data = array('image'=> $image);
		$this->db->where('id', $id); // Select where id matches the posted id
		
		$query = $this->db->update('products', $data); 
		
		return 	$query;
	}
	
	// Add an item to the cart
	function validate_add_cart_item(){

		$id = $this->input->post('product_id'); // Assign posted product_id to $id
		$cty = $this->input->post('quantity'); // Assign posted quantity to $cty
		$pvd = $this->input->post('price_pvd');

		$this->db->where('id', $id); // Select where id matches the posted id
		$query = $this->db->get('products', 1); // Select the products where a match is found and limit the query by 1

		// Check if a row has matched our product id
		if($query->num_rows > 0){

		// We have a match!
			foreach ($query->result() as $row)
			{
				// Create an array with product information
				$pvp = $row->price +($row->price * ($row->iva / 100));
			    $data = array(
               		'id'      => $id,
               		'qty'     => $cty,
               		'price'   => $row->price,
               		'name'    => $row->name,
			    	'pvd'    => $pvd,
			    	'pvp'    => $pvp,
			    	'iva'	  => $row->iva
            	);

				// Add the data to the cart using the insert function that is available because we loaded the cart library
				$this->cart->insert($data); 
				return TRUE; // Finally return TRUE
			}

		}else{
			// Nothing found! Return FALSE!
			return FALSE;
		}
	}

	// Updated the shopping cart
	function validate_update_cart(){

		// Get the total number of items in cart
		$total = $this->cart->total_items();

		// Retrieve the posted information
		$item = $this->input->post('rowid');
	    $qty = $this->input->post('qty');

		// Cycle true all items and update them
		for($i=0;$i < $total;$i++)
		{
			// Create an array with the products rowid's and quantities.
			$data = array(
               'rowid' => $item[$i],
               'qty'   => $qty[$i]
            );

            // Update the cart with the new information
			$this->cart->update($data);
		}

	}
}
	/* End of file cart_model.php */
	/* Location: ./application/models/cart_model.php */
	