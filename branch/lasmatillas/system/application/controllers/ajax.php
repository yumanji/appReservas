<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends Controller {

	function Ajax ()
	{
		parent::Controller();	
		$this->load->model('ajax_model');
		$this->load->library('flexigrid');
	}
	
	function index()
	{
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('id','iso','name','printable_name','iso3','numcode');
		
		$this->flexigrid->validate_post('id','asc',$valid_fields);

		$records = $this->ajax_model->get_countries();
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		foreach ($records['records']->result() as $row)
		{
			$record_items[] = array($row->id,
			$row->id,
			$row->iso,
			$row->name,
			'<span style=\'color:#ff4400\'>'.addslashes($row->printable_name).'</span>',
			$row->iso3,
			$row->numcode,
			'<a href=\'#\'><img border=\'0\' src=\''.$this->config->item('base_url').'images/close.png\'></a> '
			);
		}
		//Print please
		//print("<pre>");print_r($records);print_r($record_items);print("</pre>");exit();
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	

	
	
# -------------------------------------------------------------------
# Funcion que devuelve listado de todas las reservas de HOY
# -------------------------------------------------------------------
	function reserve_list_today()
	{
		$this->load->model('Reservas_model', 'reservas', TRUE);

		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('id_booking','date','intervalo','courts.name','status','price','id_user','user_desc','no_cost');
		
		$this->flexigrid->validate_post('id_booking','asc',$valid_fields);

		$records = $this->reservas->get_list_by_day(date($this->config->item('reserve_date_filter_format')));
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		if(isset($records)) {
			foreach ($records['records']->result() as $row)
			{
				if($row->no_cost==0) $no_cost='';
				else $no_cost=img( array('src'=>'images/accept.png', "align"=>"absmiddle", "border"=>"0"));

				if($row->id_user) $usuario = $row->first_name." ".$row->last_name;
				else $usuario = $row->user_desc;
				if(trim($usuario)=="") $usuario="No registrado";

				$record_items[] = array($row->id_booking,
				$row->id_booking,
				$row->date,
				$row->intervalo,
				$usuario,
				$row->status,
				$row->price,
				$row->id_user,
				$row->user_desc,
				$no_cost,
				'<a href=\'#\' onClick="javascript: alert(\'eliminar\');"><img border=\'0\' src=\''.$this->config->item('base_url').'images/close.png\'></a> '
				);
			}
			if(isset($record_items)) $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		}
		//Print please
//		print("<pre>");print_r($records);print_r($record_items);print("</pre>");exit();
	}
	
	

	//Delete Country
	function deletec()
	{
		$countries_ids_post_array = split(",",$this->input->post('items'));
		
		foreach($countries_ids_post_array as $index => $country_id)
			if (is_numeric($country_id) && $country_id > 1) 
				$this->ajax_model->delete_country($country_id);
						
			
		$error = "Selected countries (id's: ".$this->input->post('items').") deleted with success";

		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}
}
?>