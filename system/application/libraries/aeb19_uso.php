<?php
require_once('AEB19Writter.php');

$aeb19 = new AEB19Writter('.');
//Número de cuenta ficticio, para el ordenante y el presentador
$cuenta = array('1111', '1111', '11', '1111111111');
//CIF ficticio, para el ordenante y el presentador
$cif = 'B11111111';
//Nombre del presentador y del ordenante
$empresa = 'Mi empresa SA';

//Asignamos los campos del presentador
//El código presentador hay que indicarlo con ceros a la derecha, así que lo hacemos a mano
$aeb19->insertarCampo('codigo_presentador', str_pad($cif, 12, '0', STR_PAD_RIGHT));
$aeb19->insertarCampo('fecha_fichero', date('dmy'));
$aeb19->insertarCampo('nombre_presentador', $empresa);
$aeb19->insertarCampo('entidad_receptora', $cuenta[0]);
$aeb19->insertarCampo('oficina_presentador', $cuenta[1]);

//La fecha de cargo, que será dentro de 2 días
$fechaCargo = date('dmy', strtotime('+2 day'));

//Asignamos los campos del ordenante y guardamos el registro
$aeb19->insertarCampo('codigo_ordenante', str_pad($cif, 12, '0', STR_PAD_RIGHT));
$aeb19->insertarCampo('fecha_cargo', $fechaCargo);
$aeb19->insertarCampo('nombre_ordenante', $empresa);
$aeb19->insertarCampo('cuenta_abono_ordenante', implode('', $cuenta));
$aeb19->guardarRegistro('ordenante');

//Establecemos el código del ordenante para los registros obligatorios
$aeb19->insertarCampo('ordenante_domiciliacion' , str_pad($cif, 12, '0', STR_PAD_RIGHT));

//Insertamos varias domiciliaciones en un bucle
for ($i = 1; $i < 5; ++$i){
    //El % IVA aplicado en la factura
    $iva = 0.0;
    //El importe de IVA aplicado en la factura
    $importeIva = round($i * $iva, 2);
    //Total de la factura, IVA incluido
    $totalFactura = $i + $importeIva;

    //Con el codigo_referencia_domiciliacion podremos referenciar la domiciliación
    $aeb19->insertarCampo('codigo_referencia_domiciliacion', "fra-$i");
    //Cliente al que le domiciliamos
    $aeb19->insertarCampo('nombre_cliente_domiciliacion', 'Titular domiciliacion');
    //Cuenta del cliente en la que se domiciliará la factura
    $aeb19->insertarCampo('cuenta_adeudo_cliente', '22222222222222222222');
    //El importe de la domiciliación (tiene que ser en céntimos de euro y con el IVA aplicado)
    $aeb19->insertarCampo('importe_domiciliacion', ($totalFactura * 100));
    //Código para asociar la devolución en caso de que ocurra
    $aeb19->insertarCampo('codigo_devolucion_domiciliacion', $i);
    //Código interno para saber a qué corresponde la domiciliación
    $aeb19->insertarCampo('codigo_referencia_interna', "fra-$i");

    //Preparamos los conceptos de la domiciliación, en un array
    //Disponemos de 80 caracteres por línea (elemento del array). Más caracteres serán cortados
    //El índice 8 y 9 contendrían el sexto registro opcional, que es distinto a los demás
    $conceptosDom = array();
    //Los dos primeros índices serán el primer registro opcional
    $conceptosDom[] = str_pad("Factura $i", 40, ' ', STR_PAD_RIGHT) . str_pad("emitida por: $empresa", 40, ' ', STR_PAD_RIGHT);
    $conceptosDom[] = str_pad('emitida el ' . date('d/m/Y') . ' para: ', 40, ' ', STR_PAD_RIGHT) . str_pad("CIF: ES-$cif", 40, ' ', STR_PAD_RIGHT);
    //Los dos segundos índices serán el segundo registro opcional
    $conceptosDom[] = str_pad('titular domiciliacion', 40, ' ', STR_PAD_RIGHT);
    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT) . 'Base imponible:' . str_pad(number_format($i, 2, ',', '.') . ' EUR', 25, ' ', STR_PAD_LEFT);
    //Los dos terceros índices serán el tercer registro opcional
    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
        'IVA ' . str_pad(number_format($iva * 100, 2, ',', '.'), 2, '0', STR_PAD_LEFT) . '%:'.
        str_pad(number_format($importeIva, 2, ',', '.') . ' EUR', 29, ' ', STR_PAD_LEFT);
    $conceptosDom[] = str_pad('', 40, ' ', STR_PAD_RIGHT).
        'Total:' . str_pad(number_format($totalFactura, 2, ',', '.') . ' EUR', 34, ' ', STR_PAD_LEFT);

    //Añadimos la domiciliación
    $aeb19->guardarRegistro('domiciliacion', $conceptosDom);
}

//Construimos el documento y lo mostramos por pantalla
echo "<pre>{$aeb19->construirArchivo()}</pre>";
?>