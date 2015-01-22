<?php
$data = array(
              'returnUrl'  => current_url(),
              'id_user' => $code_user
            );

echo form_hidden($data);

if(isset($menu_lateral)) echo $menu_lateral;


if(isset($grid_code)) echo $grid_code;


if(isset($enable_buttons) && $enable_buttons) {

?>
<script type="text/javascript">

$(function() {
		jQuery("#grid_name").jqGrid('navButtonAdd','#pager2',{caption:"", buttonicon:"ui-icon-plus", title:'Nuevo pago',
			onClickButton:function(){
			
				document.getElementById('frmGrid').action='<?php echo site_url('payment/add_payment');?>/';
				document.getElementById('frmGrid').submit();
					
			} 
		});
		


		
});
</script>
<?php

}

?>
