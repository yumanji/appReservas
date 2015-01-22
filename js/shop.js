	$(document).ready(function() {
		
		
		/*place jQuery actions here*/
	    $("a[rel^='prettyPhoto']").prettyPhoto();

	    $('#update_stock_element').hide();
	    
		var link = "http://localhost/appReservas/index.php/"; // Url to your application (including index.php/)

		/**
		 * Definicion de las variables jQuery que se utilzan en le proyecto
		 * 
		 */
		$table 	= $('#products_list');
		$table_cart_products 	= $('#cart_products');
		$form	= $('#products_form');
		$form_update_cart	= $('#update_cart_form');
		$gestion_stock = $('#gestion_stock');
		
		
/**
 * Funciones pertenecientes al carro de la compra
 * 
 * 
 */
		$("ul.products form").submit(function() {
			// Get the product ID and the quantity
			var id = $(this).find('input[name=product_id]').val();
			var qty = $(this).find('input[name=quantity]').val();
			var price_pvd = $(this).find('input[name=price_pvd]').val();
			var stock = $(this).find('input[name=product_stock]').val();
			
			if (qty >= stock)
			{
				qty = stock;
			}
		 	$.post(link + "cart/add_cart_item", { product_id: id, quantity: qty, price_pvd:price_pvd, ajax: '1' },
  				function(data){
	    			$.get(link + "cart/show_cart", function(cart){
	  					$("#cart_content").html(cart);
						});		
 			 });
			return false; // Stop the browser of loading the page defined in the form "action" parameter.
		});
	
		$(".empty").live("click", function(){
			$.get(link + "cart/empty_cart", function(){
				$.get(link + "cart/show_cart", function(cart){
					$("#cart_content").html(cart);
				});
			});
			return false;
	    });
		
		$table_cart_products.find('TBODY TR').each(function(){
			alert('asdasdads');
			$(this).find('.delete_cart_item_link').bind('click',function(p_event){
				p_event.preventDefault();
				var qty = $(this).find('input[name=quantity]').val();
				alert(qty);
			});
		});
		
		
		$table.find('TBODY TR').each(function(){
			var idElement = this.id;
			var form_url = $form.get(0).getAttribute("action");
			
			$(this).find('.delete_link').bind('click',function(p_event){
				p_event.preventDefault();
				$('#element_id').val(idElement);
				
				$form.get(0).setAttribute("action",form_url + "/cart/stock_delete_item");
				if(confirm("Â¿Seguro que desea borrar este producto?")){
					$form.submit();
				}
			});
			
			$(this).find('.edit_link').bind('click',function(p_event){
				p_event.preventDefault();
				$('#element_id').val(idElement);
				$('#product_id').val(idElement);

				$('.add_product').css({'display':'none'})
				$('.update_link').css({'display':'block'}) 

				
				$.post(link + "cart/get_detail_product", { product_id: idElement}, function(p_data){
					if ( p_data )
					{
						datos = eval(p_data);
						$('#stock').val(datos[0].stock);
						$('#price').val(datos[0].price);
						$('#iva').val(datos[0].iva);
						$('#name').val(datos[0].name);
					}
				});
				$('#update_stock_element').show();

			});
		});

/**
 * FIN FUNCIONES CARRITO DE LA COMPRA
 * 
 */		
		
		
/**
 * FUNCIONES MANTENIMIENTO STOCK
 * 
 */		
		$('#cambiar_foto').click(function() {
			var id = $('#product_id').val();
			$('#product_image_id').val(id);
			$('#upload_dialog').dialog('open');
		});

		$('#upload_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Subir': function() {
						document.forms["frmUpload"].submit(); 

				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});			

		/**
		 * Definicion de operación que muestra el div para dar de alta un 
		 * nuevo elemento en la tienda y oculta el boton modificar
		 * 
		 */
		$('.new_product').bind('click',function(p_event){
			p_event.preventDefault();
			$gestion_stock.each (function(){
				  this.reset();
				});
			$('.add_product').css({'display':'block'})
			$('.update_link').css({'display':'none'}) 
			$('#update_stock_element').show();
		});
		
		$('.update_link').bind('click',function(p_event){
			p_event.preventDefault();
			
			$gestion_stock.get(0).setAttribute("action",link + "cart/stock_update_item");
			$gestion_stock.submit();
			$('#update_stock_element').hide();
		});
		
		$('.add_product').bind('click',function(p_event){
			p_event.preventDefault();
			
			$gestion_stock.get(0).setAttribute("action",link + "cart/stock_add_item");
			$gestion_stock.submit();
			$('#update_stock_element').hide();
		});
		
});
	