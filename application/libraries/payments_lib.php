<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
/**
* Redux Authentication 2
*/
class payments_lib
{
	public function payments_lib()
	{
		$this->CI =& get_instance();
		log_message('debug', "payment Class Initialized");
	}



	/**
	 * Generar el array de datos a exportar
	 *
	 * @return boolean
	 * @author 
	 **/
	public function export_data($opciones = NULL)
	{
			$this->CI->load->model('payment_model', 'payment2', TRUE);

			//$records = $this->CI->payment2->get_data(array('page' => 1, 'num_rows' => 25));
			$resultado = $this->CI->payment2->get_data_to_export(array('where' => "payments.status not in (5, 7)"));
			//$resultado = $records->result_array();
			
			for($i = 0; $i < count($resultado); $i++) {
				$resultado[$i]['Importe'] = str_replace('.', ',', $resultado[$i]['Importe']);
			}
			
			/*
			$nombres_semana = $this->CI->config->item('weekdays_names');
			//print('<pre>');print_r($records->result_array());exit();
			for($i = 0; $i < count($resultado); $i++) {
				$fecha_part = explode('-', $resultado[$i]['date']);
				$resultado[$i]['año'] = $fecha_part[2];
				$resultado[$i]['mes'] = $fecha_part[1];
				$resultado[$i]['dia'] = $fecha_part[0];
				$resultado[$i]['dia_semana'] = date('w', strtotime($resultado[$i]['date']));
				$resultado[$i]['dia_semana_nombre'] = $nombres_semana[$resultado[$i]['dia_semana']];
				
			}
			*/

			return $resultado;

	}



# -------------------------------------------------------------------
#  genera un fichero de texto en el servidor con los datos de facturacion
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	public function exportacion ($opciones = NULL)
	{
		ini_set('memory_limit', '512M');
		$exportacion = $this->export_data(array('formato' => 'array', 'opcion' => $opciones));
		//if(isset($exportacion) && is_array($exportacion) && count($exportacion) > 0) exit();
		$texto = $this->CI->app_common->to_csv($exportacion);
		//$data->rows = $records;
		unset($exportacion);
		$fp = fopen($this->CI->config->item('root_path').'data/payment_'.md5($this->CI->config->item('club_name')).'.txt', 'w');
		fwrite($fp, utf8_decode($texto));
		unset($texto);
		fclose($fp);
	}


# -------------------------------------------------------------------
#  genera un fichero de salida en formato PDF asociado a un Id de pago
# -------------------------------------------------------------------
# -------------------------------------------------------------------
	public function recibo_extendido ($pago, $detalle, $usuario)
	{

		$this->CI->load->model('redux_auth_model', 'users', TRUE);
		$this->CI->load->helper('jqgrid');
		$this->CI->load->library('calendario');
		$this->CI->load->model('Lessons_model', 'lessons', TRUE);
		$this->CI->load->library('users_lib');
		ini_set('memory_limit', '512M');
		require($this->CI->config->item('root_path').'system/libraries/barcode/BCGFontFile.php');
		require($this->CI->config->item('root_path').'system/libraries/barcode/BCGColor.php');
		require($this->CI->config->item('root_path').'system/libraries/barcode/BCGDrawing.php');
		require($this->CI->config->item('root_path').'system/libraries/barcode/BCGcode128.barcode.php');
		require($this->CI->config->item('root_path').'system/libraries/fpdf/fpdf.php');
		$debug = FALSE;
		//echo '<pre>'; echo '<br>Pago'; print_r($pago); echo '<br>Detalle'; print_r($detalle); echo '<br>Usuario'; print_r($usuario);exit();
		$pdf = new FPDF();	
		$imgPath = $this->CI->config->item('root_path').'images/templates/plantilla.jpg';
		//$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
		$font = $this->CI->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		$doc_title = $pago['id_type_desc'];
		//print_r($array_result);
		//$imgPath = $this->config->item('root_path').'images/templates/'.$carnet_permission[$array_result['group_id']];
		//$imgStampPath = $this->config->item('root_path').'images/users/'.$array_result['avatar'];
		if(!file_exists($imgPath) || !file_exists($font)) exit ('Fallo en la carga de las plantillas necesarias');
		
		# Abro plantilla de carnet
		$size=getimagesize($imgPath);
		switch($size["mime"]){
			case "image/jpeg":
				$image = imagecreatefromjpeg($imgPath); //jpeg file
			break;
			case "image/gif":
				$image = imagecreatefromgif($imgPath); //gif file
		  break;
		  case "image/png":
			  $image = imagecreatefrompng($imgPath); //png file
		  break;
		  default: 
			$image=false;
		  break;
		}
		if(!$image) exit ('Fallo en la carga de las plantillas necesarias');


		


		// Set the margins for the stamp and get the height/width of the stamp image
		$marge_right = 200;
		$marge_bottom = 1900;
		//imagecopy($image, $fotocarnet_thumb, imagesx($image) - $ancho_fotocarnet_thumb - $marge_right, imagesy($image) - $alto_fotocarnet_thumb - $marge_bottom, 0, 0, $ancho_fotocarnet_thumb, $alto_fotocarnet_thumb);

		
		# Escribo los datos del usuario
		$white = imagecolorallocate($image, 255, 255, 255);
		$grey = imagecolorallocate($image, 128, 128, 128);
		$black = imagecolorallocate($image, 0, 0, 0);
		$fontSize = 46;	// Tamaño de texto normal
		$text_xpos = 270;	// Margen izquierdo

		imagettftext($image, 72, 0, 1200, 300, $black, $font, $doc_title);	// Titulo
		//exit();
		//$array_result= array('user_lastname' => 'Nieto Castellano', 'user_name' => 'Juan José', 'cif' => '50107654S', 'birthdate' => '20/08/1977', 'address' => 'Calle de Constancia, 17, 3º C', 'population' => 'Torrijos de arribarrigota', 'cp' => '28058', 'phone' => '915092162', 'phone2' => '656424453', 'email' => 'juanjitojuanjitoo0000o.nieto@gmail.com');

		imagettftext($image, $fontSize, 0, $text_xpos, 665, $black, $font, $usuario['user_lastname'].', '.$usuario['user_name']);	// Nombre
		imagettftext($image, $fontSize, 0, $text_xpos+1600, 665, $black, $font, $usuario['nif']);	// DNI
		imagettftext($image, $fontSize, 0, $text_xpos, 850, $black, $font, date($this->CI->config->item('reserve_date_filter_format'),strtotime($usuario['birth_date'])));	// fecha nacimiento
		imagettftext($image, $fontSize-5, 0, $text_xpos+400, 850, $black, $font, $usuario['address']);	// direccion
		if(strlen($usuario['population'])<= 15 ) imagettftext($image, $fontSize, 0, $text_xpos+1600, 850, $black, $font, $usuario['population']);	// telefono movil
		elseif(strlen($usuario['population'])<= 20 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1600, 850, $black, $font, $usuario['population']);
		elseif(strlen($usuario['population'])<= 23 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1600, 850, $black, $font, $usuario['population']);
		else imagettftext($image, $fontSize-20, 0, $text_xpos+1600, 850, $black, $font, $usuario['population']);
		imagettftext($image, $fontSize, 0, $text_xpos, 1030, $black, $font, $usuario['cp']);	// codigo postal
		//imagettftext($image, $fontSize, 0, $text_xpos+450, 1030, $black, $font, $usuario['user_phone']);	// telefono fijo
		imagettftext($image, $fontSize, 0, $text_xpos+400, 1030, $black, $font, $usuario['user_phone']);	// telefono movil
		imagettftext($image, $fontSize, 0, $text_xpos+900, 1030, $black, $font, $usuario['user_id']);	// telefono movil
		if(strlen($usuario['user_email'])<= 22 ) imagettftext($image, $fontSize, 0, $text_xpos+1175, 1030, $black, $font, $usuario['user_email']);	// telefono movil
		elseif(strlen($usuario['user_email'])<= 28 ) imagettftext($image, $fontSize-10, 0, $text_xpos+1175, 1030, $black, $font, $usuario['user_email']);
		elseif(strlen($usuario['user_email'])<= 33 ) imagettftext($image, $fontSize-15, 0, $text_xpos+1175, 1030, $black, $font, $usuario['user_email']);
		else imagettftext($image, $fontSize-20, 0, $text_xpos+1175, 1030, $black, $font, $usuario['user_email']);

		if($pago['status'] == '2' ) {
			imagettftext($image, $fontSize-10, 0, $text_xpos, 1300, $black, $font, $pago['description']);	// Nombre
			imagettftext($image, $fontSize-5, 0, $text_xpos, 1450, $black, $font, 'Importe: '.$pago['quantity'].' euros. ');	// Nombre
			imagettftext($image, $fontSize-5, 0, $text_xpos, 1800, $black, $font, 'El ingreso deberá realizarse en alguno de los siguientes números de cuenta:');	// Nombre
			imagettftext($image, $fontSize, 0, $text_xpos+50, 1900, $black, $font, '2105 3039 97 3400014378 (Liberbank, antigua CCM)');	// Nombre
			//imagettftext($image, $fontSize, 0, $text_xpos+50, 1900, $black, $font, '2105 0039 34 1290022090 (Caja Castilla-La Mancha)');	// Nombre
			imagettftext($image, $fontSize, 0, $text_xpos+50, 2000, $black, $font, '3081 0181 03 2563768528 (Caja Rural)');	// Nombre
			imagettftext($image, $fontSize-5, 0, $text_xpos, 2100, $black, $font, 'En el concepto de pago deberá poner \''.$pago['ticket_number'].'\'');	// Nombre
			imagettftext($image, $fontSize-10, 0, $text_xpos, 2250, $black, $font, 'Deberá acompañarse la presente solicitud con el justificante del ingreso');	// Nombre
		} elseif($pago['status'] == '9' ) {
			imagettftext($image, $fontSize-5, 0, $text_xpos, 1300, $black, $font, 'JUSTIFICANTE DE PAGO ');	// Nombre
			imagettftext($image, $fontSize-10, 0, $text_xpos, 1500, $black, $font, $pago['description']);	// Nombre
			imagettftext($image, $fontSize-5, 0, $text_xpos, 1650, $black, $font, 'Importe: '.$pago['quantity'].' euros. ');	// Nombre
			imagettftext($image, $fontSize-5, 0, $text_xpos, 1800, $black, $font, 'Pagado el '.date($this->CI->config->item('reserve_date_filter_format'),strtotime($pago['datetime'])).'. ');	// Nombre
			imagettftext($image, $fontSize-5, 0, $text_xpos, 1950, $black, $font, 'La referencia de este pago es \''.$pago['ticket_number'].'\'');	// Nombre
		} else exit('No se pueden generar justificantes impresos de los pagos en este estado');
		





		 
		$font = $this->CI->config->item('root_path').'system/fonts/FreeSansBold.ttf';
		$font = new BCGFontFile($this->CI->config->item('root_path').'system/fonts/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		 
		// Barcode Part
		$code = new BCGcode128();
		$code->setScale(5);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setStart(NULL);
		$code->setTilde(true);
		$code->setOffsetX(1);
		$code->setOffsetX(1);
		//$code->clearLabels();
		$code->parse($pago['ticket_number']);
		$tamaño_barcode = $code->getDimension(0, 0);
		$ancho_barcode = $tamaño_barcode[0];
		$alto_barcode = $tamaño_barcode[1];
		//exit('aa');
		$barcode = imagecreatetruecolor($ancho_barcode, $alto_barcode);
		$background_color = imagecolorallocate($barcode, 255, 255, 255);
		imagefill($barcode, 0, 0, $background_color);
		// Drawing Part
		$drawing = new BCGDrawing('', $color_white);
		$drawing->set_im($barcode);
		$drawing->setBarcode($code);
		$drawing->draw($barcode);
		//$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);		
		$marge_right = 1200;
		$marge_bottom = 800;
		imagecopy($image, $barcode, imagesx($image) - $ancho_barcode - $marge_right, imagesy($image) - $alto_barcode - $marge_bottom, 0, 0, $ancho_barcode, $alto_barcode);

		
		//imagettftext($image, $fontSize, 0, $text_xpos, 140, $black, $font, 'ID: '.$usuario['user_id']);


		$anchoo = imagesx($image);
		$altoo = imagesy($image);
		$proporcion = 1.4143;
		$imagen_final = imagecreatetruecolor($anchoo, $altoo);
		$image = imagerotate($image, 90, 0);
		imagecopyresized ($imagen_final, $image, 0, 0, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);
		imagecopyresized ($imagen_final, $image, 0, $altoo / 2, 0, 0,  $anchoo, $anchoo/$proporcion, $altoo, $anchoo);


		
		
		//header("Content-type: image/jpeg");
		//header("Content-type: image/jpeg");
		//header("Content-Length: " . $size);
		// NOTE: Possible header injection via $basename
		//header("Content-Disposition: attachment; filename=cuota_" . $code_user .'.jpg');
		//header('Content-Transfer-Encoding: binary');
		//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		
		# Rota la imagen 90 grados
		//$image = imagerotate($image, 90, 0);
		$recibo_path  = $this->CI->config->item('root_path').'data/recibos/'.$pago->ticket_number.'.jpg';
		imagejpeg($imagen_final,$recibo_path, 100);
		// Liberar memoria
		imagedestroy($image);
		imagedestroy($imagen_final);

		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		//$pdf->Cell(40,10,'¡Hola, Mundo!');
		$pdf->Image($recibo_path, 0, 0, 210);
		header("Content-Disposition: attachment; filename=pago_" . $code_user .'.pdf');
		header('Content-Transfer-Encoding: binary');
		$pdf->Output();
		unlink($recibo_path);
	
	}
}