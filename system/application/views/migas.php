 <!-- >>>>Inicio Rastro de migas <<<<<-->
<div class="rastromigas">
	 <ul class="migas">
	 <li><a href="<?php echo site_url(); ?>">Inicio</a></li>
<?php
	# Gestión en función de la Url
	
	# Primero las excepciones
	$exception = FALSE;
	if($this->uri->segment(2)=='profile') {
		echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('users/profile').'">Mi perfil</a></li>';
		$exception = TRUE;
	}
	if($this->uri->segment(1)=='retos' && $this->uri->segment(2)=='publico') {
		echo '<li>&gt;&nbsp;&nbsp;Mis retos</li>';
		$exception = TRUE;
	}
	if($this->uri->segment(1)=='retos' && $this->uri->segment(2)=='view') {
		echo '<li>&gt;&nbsp;&nbsp;Detalle de reto</li>';
		$exception = TRUE;
	}
	if($this->uri->segment(2)=='confirm_tpv_ko_serme' || $this->uri->segment(2)=='confirm_tpv_ok_serme') {
		$exception = TRUE;
	}
	if(!$exception && $this->uri->segment(1)) {
		switch($this->uri->segment(1)) {
			case "informes":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Informes</a></li>';
			break;
			case "gestion":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Gestion</a></li>';
			break;
			case "reservas":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Reservas</a></li>';
			break;
			case "calendar":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Calendario</a></li>';
			break;
			case "notifications":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Comunicaciones</a></li>';
			break;
			case "payment":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('gestion').'">Gestion</a></li><li>&gt;&nbsp;&nbsp;<a href="'.site_url('facturacion/list_all').'">Pagos</a></li>';
			break;
			case "facturacion":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('gestion').'">Gestion</a></li><li>&gt;&nbsp;&nbsp;<a href="'.site_url('facturacion/list_all').'">Facturacion</a></li>';
			break;
			case "reservas_gest":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('gestion').'">Gestion</a></li><li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Reservas</a></li>';
			break;
			case "users":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('gestion').'">Gestion</a></li><li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Usuarios</a></li>';
			break;
			case "lessons":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('gestion').'">Gestion</a></li><li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Clases</a></li>';
			break;
			case "cart":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Tienda</a></li>';
			break;
			case "activities":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Actividades</a></li>';
			break;
			case "help":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Ayuda</a></li>';
			break;
			case "retos":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">Retos</a></li>';
			break;
			case "ranking":
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">'.$this->lang->line('ranking_name').'</a></li>';
			break;
			
			
			default:
				echo '<li>&gt;&nbsp;&nbsp;<a href="'.site_url('gestion').'">Gestion</a></li><li>&gt;&nbsp;&nbsp;<a href="'.site_url($this->uri->segment(1)).'">'.$this->uri->segment(1).'</a></li>';
			break;
		}
		
	} elseif(!$exception) echo '<li>&gt;&nbsp;&nbsp;P&aacute;gina de inicio</li>';



	if(!$exception && $this->uri->segment(2)) {
		switch($this->uri->segment(2)) {
			case "profile":
				echo '<li>&gt;&nbsp;&nbsp;Perfil de usuario</li>';
			break;
			case "index":
				echo '<li>&gt;&nbsp;&nbsp;P&aacute;gina de inicio</li>';
			break;
			case "cierre_dia":
				echo '<li>&gt;&nbsp;&nbsp;Cierre caja</li>';
			break;
			case "reserva_diaria":
				echo '<li>&gt;&nbsp;&nbsp;Informe de reservas</li>';
			break;
			case "reserva_ocupacion":
				echo '<li>&gt;&nbsp;&nbsp;Informe de ocupacion</li>';
			break;
			case "list_all":
				echo '<li>&gt;&nbsp;&nbsp;Listado general</li>';
			break;
			case "list_all_canceled":
				echo '<li>&gt;&nbsp;&nbsp;Listado canceladas</li>';
			break;
			case "new_single_mail":
				echo '<li>&gt;&nbsp;&nbsp;Nuevo email</li>';
			break;
			case "new_user":
				echo '<li>&gt;&nbsp;&nbsp;Nuevo usuario</li>';
			break;
			case "new_reto":
				echo '<li>&gt;&nbsp;&nbsp;Nuevo reto</li>';
			break;
			case "new_rank":
				echo '<li>&gt;&nbsp;&nbsp;Nuevo '.$this->lang->line('ranking_name').'</li>';
			break;
			case "new_team":
				echo '<li>&gt;&nbsp;&nbsp;Nuevo equipo</li>';
			break;
			case "team":
				echo '<li>&gt;&nbsp;&nbsp;Equipo</li>';
			break;
			case "suscribe":
				echo '<li>&gt;&nbsp;&nbsp;Aceptar reto</li>';
			break;
			case "detail":
				echo '<li>&gt;&nbsp;&nbsp;Detalle</li>';
			break;
			case "match_detail":
				echo '<li>&gt;&nbsp;&nbsp;Detalle partido</li>';
			break;
			case "calendar":
				echo '<li>&gt;&nbsp;&nbsp;Calendario</li>';
			break;
			case "assistants":
				if($this->uri->segment(1)=='ranking') echo '<li>&gt;&nbsp;&nbsp;Equipos</li>';
				else echo '<li>&gt;&nbsp;&nbsp;Alumnos</li>';
			break;
			case "assistance":
				echo '<li>&gt;&nbsp;&nbsp;Partes diarios</li>';
			break;
			case "new_daily_report":
				echo '<li>&gt;&nbsp;&nbsp;Nuevo informe</li>';
			break;
			case "detail_daily_report":
			case "recover_daily_report":
				echo '<li>&gt;&nbsp;&nbsp;Detalle informe</li>';
			break;
			case "waiting":
			case "waiting_all":
				echo '<li>&gt;&nbsp;&nbsp;Lista espera</li>';
			break;
			
			
			default:
				echo '<li>&gt;&nbsp;&nbsp;'.$this->uri->segment(2).'</li>';
			break;
		}
		
	} elseif(!$exception && $this->uri->segment(1)) echo '<li>&gt;&nbsp;&nbsp;P&aacute;gina de inicio</li>';


?>
 </ul>
</div> 
 <!-- >>>>Fin Rastro de migas <<<<<-->