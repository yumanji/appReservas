<?php

class Estatico extends Controller {

	function Estatico()
	{
		parent::Controller();	
	}
	
	function index( $option = NULL)
	{
		

	}






# -------------------------------------------------------------------
#  devuelve politica de privacidad
# -------------------------------------------------------------------
# -------------------------------------------------------------------


public function privacidad ()
	{

				# Carga de datos para la vista
				$data=array(
					'content' => $this->load->view('static/privacidad', '', true)
				);


				$this->load->view('main_clean', $data);
		
	}
	


# -------------------------------------------------------------------
#  devuelve informacion legal
# -------------------------------------------------------------------
# -------------------------------------------------------------------


public function legal ()
	{

				# Carga de datos para la vista
				$data=array(
					'content' => $this->load->view('static/legal', '', true)
				);


				$this->load->view('main_clean', $data);
		
	}
	



# -------------------------------------------------------------------
#  devuelve condiciones de uso o normativa dde alquiler
# -------------------------------------------------------------------
# -------------------------------------------------------------------


public function condiciones_uso ()
	{

				# Carga de datos para la vista
				$data=array(
					'content' => $this->load->view('static/condiciones_uso', '', true)
				);


				$this->load->view('main_clean', $data);
		
	}
	
}

/* End of file estatico.php */
/* Location: ./system/application/controllers/estatico.php */