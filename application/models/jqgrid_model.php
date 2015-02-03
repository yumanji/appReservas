<?php
class Jqgrid_model extends Model {
	
	public function get_data($params = "" , $page = "all")
		{
			
			$table = "booking";
			
			$this->db->select("id, date, intervalo, status, price")->from($table);
	
			if (!empty($params))
			{
				if ( (($params ["num_rows"] * $params ["page"]) >= 0 && $params ["num_rows"] > 0))
				{
					if  ($params ["search"] == TRUE)
					{
						$ops = array (
	
								"eq" => "=",
								"ne" => "<>",
								"lt" => "<",
								"le" => "<=",
								"gt" => ">",
								"ge" => ">="
						);
	
					}
	
					if ( !empty ($params['search_field']) && !empty ($params['search_operator']) && !empty ($params['search_str']))
					{
						$this->db->where ($params['search_field'].' '.$ops[$params['search_operator']], $params['search_str']);
					}
	
					if ( !empty ($params['search_field_1']))
					{
						//$this->db->where ($params['search_field_1'], $params['user_id']);
					}
	
					if ( !empty ($params['search_field_2']))
					{
						//$this->db->where ($params['search_field_2'], "1");
					}
	
					$this->db->order_by( "{$params['sort_by']}", $params ["sort_direction"] );
	
					if ($page != "all")
					{
						$this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
					}
	
					$query = $this->db->get();
	
				}
			}
			else
			{
					$this->db->limit(5);
					$query = $this->db->get();
	
			}
			//echo $this->db->last_query();
			return $query;
		}


	}
?>