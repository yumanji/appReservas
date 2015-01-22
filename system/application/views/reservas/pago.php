<?php
	$this->lang->load('reservas');

	# Cargo el listado de formas de pago
	
	foreach ($methods as $method => $active) {
		if($active) {
			//$link='reservas/pay/'.$method.'/'.$transaction_id;
			
			switch	($method) {
				case "reserve":
					//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('reserve_button'), $js).'&nbsp;';
				break;
				
				case "cash":
					//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('cash_button'), $js).'&nbsp;';
				break;
				
				case "prepaid":
					//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('prepaid_button'), $js).'&nbsp;';
					
				break;
				
				case "paypal":					
					//echo img('https://www.paypal.com/es_ES/ES/i/bnr/horizontal_solution_PP.gif', FALSE);
					//echo anchor($link, img('images/paypal.jpg'), array('title' => $this->lang->line('paypal_button'), 'onClick' => "javascript: pago('".$method."');"));
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('paypal_button'), $js).'&nbsp;';
				break;
				
				case "creditcard":
					//echo anchor($link, img('images/creditcard.jpg'), array('title' => 'Pague con Tarjeta de Crédito!'));
					//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('creditcard_button'), $js).'&nbsp;';
				break;
				
				case "tpv":
					//echo anchor($link, img('images/creditcard.jpg'), array('title' => 'Pague con Tarjeta de Crédito!'));
					//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('tpv_button'), $js).'&nbsp;';
				break;
				
				case "bank":
					//$js = 'id="buttonReserva" onClick="javascript: document.getElementById(\'frmReserva\').action=\''.site_url($link).'\'; document.getElementById(\'frmReserva\').submit(); "';
					$js = 'id="buttonReserva" onClick="javascript: pago(\''.$method.'\'); "';
					echo form_button('buttonReserva', $this->lang->line('bank_button'), $js).'&nbsp;';
				break;
				
				default:
				break;
			}
			
		}
		
		
	}
?>