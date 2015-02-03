<?php

class Tools extends CI_Controller {

	function Tools()
	{
		parent::__construct();
	}
	
	function index()
	{
			
			echo "Testigo ".date('YmdHis');
			
	}



#####################
#
# Funci�n que genera los pagos relativos a las cuotas de usuarios
#
####################
		
	function convert_ccc_to_iban()
	{
			$this->load->library('users_lib');
			error_reporting(E_ALL);
			$usuarios = $this->usuario->get_data(array('where' => "bank is not null and rtrim(bank) <>''"))->result_array();
			//echo '<pre>';print_r($usuarios);
			echo 'aa'.count($usuarios);
			foreach($usuarios as $usuario) {
				$cuenta = $usuario['bank'].$usuario['bank_office'].$usuario['bank_dc'].$usuario['bank_account'];
				if(intval($cuenta) != 0) {
					echo '<br>Usuario '.$usuario['id'].' con n�mero de cuenta '.$cuenta;
					//$valor = sprintf("%02d", 98-bcmod('ES00'.$cuenta.'142800',97) );
					$iban = $this->users_lib->generarDCInToIban('ES00'.$cuenta);
					echo '<br>IBAN '.$iban.' ';
					$this->db->where('user_id', $usuario['id']);
					$this->db->update('meta', array('bank_iban' => $iban));

				}
			}

			exit();


	}



	
}

/* End of file tools.php */
/* Location: ./system/application/controllers/tools.php */