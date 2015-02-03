<?php
class App extends Model {

    function App()
    {
        // Call the Model constructor
        parent::Model();
    }
    
 
 
 
 	#####################
 	# Devuelve las posibles frecuencias de pago para actividades
 	######################
 
  function getPaymentFreq ($format = 'array')
  {
      //$query = $this->db->get('entries', 10);
      //return $query->result();
      if($format == 'array') $result=array(""=>"Selecciona opcion");
      $sql = "SELECT id, description FROM zz_payment_frequency WHERE active = 1 ORDER BY Description"; 
			$query = $this->db->query($sql);
			foreach ($query->result() as $row)
			{
				if($format == 'array') $result[$row->id]=$row->description;
			}	
				return $result;				
  }


 
 
    
 }
?>