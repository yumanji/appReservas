<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function listado_clases($data)
{
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	date_default_timezone_set('Europe/London');

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/../libraries/PHPExcel_lib.php';

	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
	if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
		die($cacheMethod . " caching method is not available" . EOL);
	}
	//echo date('H:i:s') , " Enable Cell Caching using " , $cacheMethod , " method" , EOL;


	// Create new PHPExcel object
	//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
	$objPHPExcel = new PHPExcel();

	// Set document properties
	//echo date('H:i:s') , " Set properties" , EOL;
	$objPHPExcel->getProperties()->setCreator("Reserva Deportiva")
								 ->setLastModifiedBy("Reserva Deportiva")
								 ->setTitle("Titulo")
								 ->setSubject("Subject")
								 ->setDescription("Descripcion")
								 ->setKeywords("Reserva Deportiva Informe Alumnos Clases")
								 ->setCategory("Categoria");
	
	$hojas = 0;
	$nombre_fichero = 'listado_de_clase';
	foreach($data as $datos) {
		if(count($datos['alumnos']) > 0) {
			// Create a first sheet
			//exit($datos['nombre']);
			//echo date('H:i:s') , " Add data" , EOL;
			if($hojas!=0) $objPHPExcel->createSheet($hojas);
			$objPHPExcel->setActiveSheetIndex($hojas);
			$objPHPExcel->getActiveSheet()->setTitle(substr($datos['nombre'], 0, 31));
			$nombre_fichero = $datos['nombre'];
			
			$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
			$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);			
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);			
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);			
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->setCellValue('A1', $datos['nombre']);
			$objPHPExcel->getActiveSheet()->setCellValue('A2', $datos['horario'].' / '.$datos['pista']);
			$objPHPExcel->getActiveSheet()->setCellValue('A3', $datos['profesor']);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A5', "Alumno");
			$objPHPExcel->getActiveSheet()->setCellValue('B5', "DNI");
			$objPHPExcel->getActiveSheet()->setCellValue('C5', "Nacimiento");
			$objPHPExcel->getActiveSheet()->setCellValue('D5', "Telefono");
			//$objPHPExcel->getActiveSheet()->setCellValue('E5', "Is Client ?");


			// Hide "Phone" and "fax" column
			//echo date('H:i:s') , " Hide 'Phone' and 'fax' columns" , EOL;
			//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setVisible(false);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setVisible(false);


			// Set outline levels
			//echo date('H:i:s') , " Set outline levels" , EOL;
			//$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setOutlineLevel(1)
			//													   ->setVisible(false)
			//													   ->setCollapsed(true);

			// Freeze panes
			//echo date('H:i:s') , " Freeze panes" , EOL;
			$objPHPExcel->getActiveSheet()->freezePane('A6');


			// Rows to repeat at top
			//echo date('H:i:s') , " Rows to repeat at top" , EOL;
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 5);


			// Add data
			$linea = 6;
			foreach($datos['alumnos'] as $alumno) {
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $linea, $alumno['nombre'])
											  ->setCellValue('B' . $linea, $alumno['nif'])
											  ->setCellValue('C' . $linea, $alumno['nacimiento'])
											  ->setCellValue('D' . $linea, $alumno['telefono']);
				$linea++;
			}
			$hojas++;
		}
		//exit('aa');
		//break;
	}

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Save Excel 2007 file
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$callStartTime = microtime(true);

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	if(count($data)>1) header('Content-Disposition: attachment;filename="clases_listado_'.date('YmdHis').'.xls"');
	else header('Content-Disposition: attachment;filename="'.str_replace(' ', '_', $nombre_fichero).'_'.date('YmdHis').'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	$objWriter->save('php://output');
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;

	//echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
	//echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
	// Echo memory usage
	//echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


	// Echo memory peak usage
	//echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

	// Echo done
	//echo date('H:i:s') , " Done writing file" , EOL;
	//echo 'File has been created in ' , getcwd() , EOL;
}



?>