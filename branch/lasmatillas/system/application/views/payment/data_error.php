<?php
	$this->lang->load('payment');

	echo '<p>'.img( array('src'=>'images/warning.png', "align"=>"absmiddle"));
	echo $this->lang->line('payment_data_fail_message').' (Code '.$error_code.')</p>';
	
?>