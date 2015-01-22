<?php
	$this->lang->load('reservas');
	$this->CI =& get_instance();
	
	
	//$attributes = array('class' => $form, 'id' => $form);
	//echo form_open(site_url('reservas'), $attributes);

	echo '<input type="hidden" name="id_transaction" value="'.$id_transaction.'">'."\r\n";
	
?>
<script type="text/javascript">
$(function() {
		var direccion2 =<?php echo "'".site_url('reservas/pay2/'.time().'/'.$id_transaction);?>';
		//alert(direccion2);
		//return;
		//alert( $( "#accordion" ).accordion( "option", "animated" ));
		$("#accordion").accordion({ animated: 'slide' });
		$("#confirm_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p align="center">Loading....&nbsp;<?php echo img( array('src'=>'images/load.gif', "align"=>"absmiddle", "border"=>"0"));?></p>  </div> </div>');
		$("#accordion").accordion("activate" , 5);
		$.ajax({
			
		  type: 'POST',
		  url: direccion2,
		  data: {id_user: $("#id_user").val(), user_desc: $("#user_desc").val(), user_phone: $("#user_phone").val(), allow_light: $("#allow_light").attr('checked'), no_cost: $("#no_cost").attr('checked'), no_cost_desc: $("#no_cost_desc").val(), id_transaction: $("#id_transaction").val() }, 
		  success: function(data) {
		  	//alert(data);
		    $("#search_extra").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda.</p>  </div> </div>');
		    $("#search_payment").html('		<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>Ya solo puedes hacer una nueva b&uacute;squeda.</p>  </div> </div>');

		    $("#confirm_payment").html(data);
		  }
		});
		direccion2 =<?php echo "'".site_url('reservas/extras')."'";?>; // Reseteo variable
});
</script>
