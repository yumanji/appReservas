<?php

	class Cart extends Controller { // Our Cart class extends the Controller class

		function Cart()
		{
			parent::Controller(); // We define the the Controller class is the parent.
			$this->load->library('cart'); // Load our cart model for our entire class 
			$this->load->model('cart_model'); // Load our cart model for our entire class 
			$this->load->model('payment_model');
		}


		function index()
		{
			$extra = link_tag(base_url().'css/shop.css').link_tag(base_url().'css/prettyPhoto.css')."\r\n".'<script src="'.base_url().'js/shop.js" type="text/javascript"></script>'.'<script src="'.base_url().'js/jquery.prettyPhoto.js" type="text/javascript"></script>';
			$data=array(
				'meta' => $this->load->view('meta', array('lib_jqgrid' => FALSE, 'extra' => $extra), true),
				'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => FALSE), true),
				'navigation' => $this->load->view('navigation', '', true),
				'footer' => $this->load->view('footer', '', true),				
				'info_message' => $this->session->userdata('info_message'),
				'error_message' => $this->session->userdata('error_message')
			);
			$this->session->unset_userdata('info_message');
			$this->session->unset_userdata('error_message');
		    
		    $cart_board = $this->load->view('cart/cart.php', array('paymentMethods'=>$this->payment_model->getPaymentWaysArray()), true);
			# Especificaciones de métodos de pago de esta pantalla
		    //print_r($this->payment_model->getPaymentWaysArray()); // Print out the array to see if it works (Remove this line when done testing)
		    $data['main_content']=$this->load->view('cart/products', array('products' => $this->cart_model->retrieve_products(), 'cart_board' => $cart_board), true); // Select our view file that will display our products
			
    		$this->load->view('main', $data); // Display the page with the above defined content  
		    
		}
		
	function add_cart_item(){
		if($this->cart_model->validate_add_cart_item() == TRUE){
			// Check if user has javascript enabled
			if($this->input->post('ajax') != '1'){
				redirect('cart'); // If javascript is not enabled, reload the page with new data
			}else{
				echo 'true'; // If javascript is enabled, return true, so the cart gets updated
			}
		}
	}

	function order_charge_cart(){
		$user_id;
		$profile;
		if($this->redux_auth->logged_in()) {
			$profile=$this->redux_auth->profile();
			$user_id=$profile->id;
		}
		$idTransaction = '6'.$this->session->userdata('session_id').'-'.time();
		$this->payment_model->id_type=6; //Reserva de pista
		$this->payment_model->id_transaction=$idTransaction;
		$this->payment_model->id_user=$this->input->post('id_shop_user');
		$this->payment_model->desc_user=$this->input->post('buscausuariosshop');
		$this->payment_model->status=9;
		//$this->payment_model->id_paymentway=$this->app_common->changePaymentwayNotation($this->input->post('paymentway'));
		$this->payment_model->id_paymentway=$this->input->post('paymentway');
		$total_price;
		/* Si se quiere poner un pvd por elemento del carrito
		if ($this->input->post('total_pvd') AND $this->input->post('total_pvd') > 0)
		{
			$total_price = $this->input->post('total_pvd') + $this->input->post('total_pvp');
		}else{
			$total_price = $this->input->post('total_pvp');
		}
		*/
		if ($this->input->post('total_pvd') AND $this->input->post('total_pvd') > 0)
		{
			$total_price = $this->input->post('total_pvd');
		}else{
			$total_price = $this->input->post('total_pvp');
		}
		
		$this->payment_model->quantity=$total_price;
		$this->payment_model->datetime=date($this->config->item('log_date_format'));
		$this->payment_model->description='Pago de Tienda';
		$this->payment_model->create_user=$user_id;
		$this->payment_model->create_time=date($this->config->item('log_date_format'));
		
		if($this->payment_model->setPayment())
		{ 
			$this->cart_model->discount_cart_stock();
			$this->cart->destroy();
			$this->session->set_userdata('info_message', 'Se ha producido el pago de la compra.');
			redirect('cart');
		}	
		
	}		
	
	function show_cart(){
		$this->load->view('cart/cart');
	}
	
	function update_cart(){
		$this->cart_model->validate_update_cart();
		redirect('cart');
	}
			
	function empty_cart(){
		$this->cart->destroy(); // Destroy all cart data
		redirect('cart'); // Refresh te page
	}
				
	function admin_cart(){
		$extra = link_tag(base_url().'css/shop.css').link_tag(base_url().'css/prettyPhoto.css')."\r\n".'<script src="'.base_url().'js/shop.js" type="text/javascript"></script>'.'<script src="'.base_url().'js/jquery.prettyPhoto.js" type="text/javascript"></script>';
		$data=array(
			'meta' => $this->load->view('meta', array('lib_jqgrid' => FALSE, 'extra' => $extra), true),
			'header' => $this->load->view('header', array('enable_menu' => $this->redux_auth->logged_in(), 'enable_submenu' => FALSE), true),
			'navigation' => $this->load->view('navigation', '', true),
			'footer' => $this->load->view('footer', '', true),				
			'info_message' => $this->session->userdata('info_message'),
			'error_message' => $this->session->userdata('error_message')
		);
		$this->session->unset_userdata('info_message');
		$this->session->unset_userdata('error_message');
		    
	    $data['main_content']=$this->load->view('cart/cart_stock', array('products' => $this->cart_model->retrieve_products()), true); // Select our view file that will display our products
			
   		$this->load->view('main', $data); // Display the page with the above defined content  
	}	

	function stock_add_item()
	{
		$this->cart_model->add_cart_product();
		redirect('cart/admin_cart');
	}
	
	function stock_update_item()
	{
		$this->cart_model->update_cart_product();
		redirect('cart/admin_cart');
	}
	
	function stock_delete_item (){
		$this->cart_model->delete_cart_product();
		redirect('cart/admin_cart');
	}
	
	function get_detail_product(){
		echo json_encode($this->cart_model->get_detail_product()); 
	}
	
# -------------------------------------------------------------------
# Funcion que guarda la imagen del usuario activo
# -------------------------------------------------------------------
	function upload_photo()
	{
		if($_FILES['archivo']['size'] > 300000) {				
			$this->session->set_userdata('error_message', 'El tama&ntilde;o del fichero excede el m&aacute;ximo (300KB).');
			redirect('cart/admin_cart'); 
			exit();
		}
		if(strstr($_FILES['archivo']['size'], 'image')) {
			$this->session->set_userdata('error_message', 'El fichero enviado no es una imagen con formato v&aacute;lido.');
			redirect('cart/admin_cart'); 
			exit();
		}
		
		$raiz = $this->config->item('root_path');
		if(!isset($raiz) || $raiz=='') {
			$this->session->set_userdata('error_message', 'Problema en la configuraci&oacute;n. Contacte con el administrador.');
			redirect('cart/admin_cart'); 
			exit();
		}
		
		$troceo = explode('.', $_FILES['archivo']['name']);
		print_r ($troceo);
		//$extension = $troceo[count($troceo)-1];
		$image = $troceo[0].'.'.$troceo[1];
		$ruta_destino = $raiz.'images/products/'.$image;
		//echo $ruta_destino; exit();
		@unlink($ruta_destino);
		@copy($_FILES['archivo']['tmp_name'], $ruta_destino);
		//$this->users->setAvatar($usuario, $extension);
		
		$resultado = $this->cart_model->update_cart_image_product($image);
		if($resultado){
			$this->session->set_userdata('info_message', 'Fotograf&iacute;a actualizada.');
		}else{
			$this->session->set_userdata('info_message', 'Error en la subida de la fotograf&iacute;a.');
		}
		redirect('cart/admin_cart'); 
		exit();
	}
}
/* End of file cart.php */
/* Location: ./application/controllers/cart.php */