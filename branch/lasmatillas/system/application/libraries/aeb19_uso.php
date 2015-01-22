<?php
require_once('AEB19Writter.php');

$aeb19 = new AEB19Writter('.');
//N�mero de cuenta ficticio, para el ordenante y el presentador
$cuenta = array('1111', '1111', '11', '1111111111');
//CIF ficticio, para el ordenante y el presentador
$cif = 'B11111111';
//Nombre del presentador y del ordenante
$empresa = 'Mi empresa SA';

//Asignamos los campos del presentador
//El c�digo presentador hay que indicarlo con ceros a la derecha, as� que lo hacemos a mano
$aeb19->insertarCampo('codigo_presentador', str_pad($cif, 12, '0', STR_PAD_RIGHT));
$aeb19->insertarCampo('fecha_fichero', date('dmy'));
$aeb19->insertarCampo('nombre_presentador', $empresa);
$aeb19->insertarCampo('entidad_receptora', $cuenta[0]);
$aeb19->insertarCampo('oficina_presentador', $cuenta[1]);

//La fecha de cargo, que ser� dentro de 2 d�as
$fechaCargo = date('dmy', strtotime('+2 day'));

//Asignamos los campos del ordenante y guardamos el registro
$aeb19->insertarCampo('codigo_ordenante', str_pad($cif, 12, '0', STR_PAD_RIGHT));
$aeb19->insertarCampo('fecha_cargo', $fechaCargo);
$aeb19->insertarCampo('nombre_ordenante', $empresa);
$aeb19->insertarCampo('cuenta_abono_ordenante', implode('', $cuenta));
$aeb19->guardarRegistro('ordenante');

//Establecemos el c�digo del ordenante para los registros obligatorios
$aeb19->insertarCampo('ordenante_domiciliacion' , str_pad($cif, 12, '0', STR_PAD_RIGHT));

//Insertamos varias domiciliaciones en un bucle
for ($i = 1; $i < 5; ++$i){
    //El % IVA aplicado en la factura
    $iva = 0.0;
    //El importe de IVA aplicado en la factura
    $importeIva = round($i * $iva, 2);
    //Total de la factura, IVA incluido
    $totalFactura = $i + $importeIva;

    //Con el codigo_referencia_domiciliacion podremos referenciar la domiciliaci�n
    $aeb19->insertarCampo('codigo_referencia_domiciliacion', "fra-$i");
    //Cliente al que le domiciliamos
    $aeb19->insertarCampo('nombre_cliente_domiciliacion', 'Titular domiciliacion');
    //Cuenta del cliente en la que se domiciliar� la factura
    $aeb19->insertarCampo('cuenta_adeudo_cliente', '22222222222222222222');
    //El importe de la domiciliaci�n (tiene que ser en c�ntimos de euro y con el IVA aplicado)
    $aeb19->insertarCampo('importe_domiciliacion', ($totalFactura * 100));
    //C�digo para asociar la devoluci�n en caso de que ocurra
    $aeb19->insertarCampo('codigo_devolucion_domiciliacion', $i);
    //C�digo interno para saber a qu� corresponde la domiciliaci�n
    $aeb19->insertarCampo('codigo_referencia_interna', "fra-$i");

    //Preparamos los conceptos de la domiciliaci�n, en un array
    //Disponemos de 80 caracteres por l�nea (elemento del array). M�s caracteres ser�n cortados
    //El �ndice 8 y 9 contendr�an el sexto registro opcional, que es distinto a los dem�s
    $conceptosDom = array();
    //Los dos primeros �ndices ser�n el primer registro opcional
    $conceptosDom[] = str_pad("Factura $i", 40, ' ', STR_PAD_RIGHT) . str_pad("emitida por: $empresa", 40, ' ', STR_PAD_RIGHT);
    $conceptosDom[] = str_pad('emitida el ' . date('d/m/Y') . ' para: ', 40, ' ', STR_PAD_RIGHT) . str_pad("CIF: ES-$cif", 40, ' ', STR_PAD_RIGHT);
    //Los dos segundos �ndices ser�n el segundo registro opcional
    $conceptosDom[] = str_pad('titular domiciliacion', 40, ' ', STR_PAD_RIGHT);
    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT) . 'Base imponible:' . str_pad(number_format($i, 2, ',', '.') . ' EUR', 25, ' ', STR_PAD_LEFT);
    //Los dos terceros �ndices ser�n el tercer registro opcional
    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
        'IVA ' . str_pad(number_format($iva * 100, 2, ',', '.'), 2, '0', STR_PAD_LEFT) . '%:'.
        str_pad(number_format($importeIva, 2, ',', '.') . ' EUR', 29, ' ', STR_PAD_LEFT);
    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
        'Total:' . str_pad(number_format($totalFactura, 2, ',', '.') . ' EUR', 34, ' ', STR_PAD_LEFT);

    //A�adimos la domiciliaci�n
    $aeb19->guardarRegistro('domiciliacion', $conceptosDom);
}

//Construimos el documento y lo mostramos por pantalla
echo "<pre>{$aeb19->construirArchivo()}</pre>";
?>