<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Esta clase se usa para generar las domiciliaciones en formato AEB19
//Vamos a tener un array para la cabecera de presentador, otro para la del ordenante (que pueden ser varios)
//otro de registros individuales obligatorios, otro de opcionales, otro del total ordenante (pueden ser varios) y
//otro del total general; las longitudes de las subzonas, junto con otras propiedades, las almacenamos en arrays aparte
//Esos arrays de propiedades contendr�n: longitud y relleno (el caracter de relleno, espacio, 0...)
class aeb19writter
{
    //Car�cter por defecto para rellenar las zonas libres
    private $rellenoLibre = '';

    //Declaramos los miembros (privados en PHP5) de la clase

    //El array de la estructura de la cabecera presentador
    private $estructuraPresentador;
    //El array con las longitudes de las zonas del presentador
    private $lenPresentador;
    //El array de la estructura de la cabecera ordenante
    private $estructuraOrdenante;
    //El array con las longitudes de las zonas del ordenante
    private $lenOrdenante;
    //El array de la estructura del registro individual obligatorio
    private $estructuraObligatorio;
    //El array con las longitudes de las zonas del obligatorio
    private $lenObligatorio;
    //El array de la estructura de los registros opcionales (menos del sexto, que es distinto)
    private $estructuraOpcional;
    //El array con las longitudes de las zonas de los opcionales < 6
    private $lenOpcional;
    //El array de la estructura del sexto registro opcional
    private $estructuraSextoOpcional;
    //El array con las longitudes de las zonas del sexto opcional
    private $lenSextoOpcional;
    //El array de la estructura del registro del total ordenante
    private $estructuraTotalOrdenante;
    //El array con las longitudes de las zonas del total ordenante
    private $lenTotalOrdenante;
    //El array de la estructura del registro del total general
    private $estructuraTotalGeneral;
    //El array con las longitudes de las zonas del total general
    private $lenTotalGeneral;
    //El array con los conceptos de las domiciliaciones
    private $conceptos;
    //El array de LAS cabeceras de ordenante; se define como array, pero se le ir�n a�adiendo registros de cabecera ordenante
    private $ordenantes;
    //El array con LOS registros obligatorios, que contendr� arrays con el registro y el ordenante
    private $obligatorios;
    //El array con LOS registros opcionales, que contendr� arrays con el registro y el �ndice del obligatorio
    private $opcionales;

    //Constructor por defecto de la clase
    public function __construct($relleno = ' '){
        //Aqu� asignamos valores y creamos estructuras de los arrays
        if (strlen($relleno) == 1){
            $this->rellenoLibre = $relleno;
        }
        else
            $this->rellenoLibre = ' ';

        $this->estructuraPresentador = array(
            'A' => array('1' => '51', '2' => '80'),
            'B' => array('1' => '', '2' => '', '3' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => ''),
            'E' => array('1' => '', '2' => '', '3' => ''),
            'F' => array('1' => ''),
            'G' => array('1' => '')
        );
        $this->lenPresentador = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 6, 'relleno' => '0'),
                '3' => array('len' => 6, 'relleno' => $this->rellenoLibre)
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'D' => array(
                '1' => array('len' => 20, 'relleno' => $this->rellenoLibre)
            ),
            'E' => array(
                '1' => array('len' => 4, 'relleno' => '0'),
                '2' => array('len' => 4, 'relleno' => '0'),
                '3' => array('len' => 12, 'relleno' => $this->rellenoLibre)
            ),
            'F' => array(
                '1' => array('len' => 40, 'relleno' => $this->rellenoLibre)
            ),
            'G' => array(
                '1' => array('len' => 14, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->estructuraOrdenante = array(
            'A' => array('1' => '53', '2' => '80'),
            'B' => array('1' => '', '2' => '', '3' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => '', '2' => '', '3' => '', '4' => ''),
            'E' => array('1' => '', '2' => '', '3' => ''),
            'F' => array('1' => ''),
            'G' => array('1' => '')
        );
        $this->lenOrdenante = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 6, 'relleno' => '0'),
                '3' => array('len' => 6, 'relleno' => '0')
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'D' => array(
                '1' => array('len' => 4, 'relleno' => '0'),
                '2' => array('len' => 4, 'relleno' => '0'),
                '3' => array('len' => 2, 'relleno' => '0'),
                '4' => array('len' => 10, 'relleno' => '0')
            ),
            'E' => array(
                '1' => array('len' => 8, 'relleno' => $this->rellenoLibre),
                '2' => array('len' => 2, 'relleno' => '0'),
                '3' => array('len' => 10, 'relleno' => $this->rellenoLibre)
            ),
            'F' => array(
                '1' => array('len' => 40, 'relleno' => $this->rellenoLibre)
            ),
            'G' => array(
                '1' => array('len' => 14, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->estructuraObligatorio = array(
            'A' => array('1' => '56', '2' => '80'),
            'B' => array('1' => '', '2' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => '', '2' => '', '3' => '', '4' => ''),
            'E' => array('1' => ''),
            'F' => array('1' => '', '2' => ''),
            'G' => array('1' => ''),
            'H' => array('1' => '')
        );
        $this->lenObligatorio = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 12, 'relleno' => ' ')
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'D' => array(
                '1' => array('len' => 4, 'relleno' => '0'),
                '2' => array('len' => 4, 'relleno' => '0'),
                '3' => array('len' => 2, 'relleno' => '0'),
                '4' => array('len' => 10, 'relleno' => '0'),
            ),
            'E' => array(
                '1' => array('len' => 10, 'relleno' => '0')
            ),
            'F' => array(
                '1' => array('len' => 6, 'relleno' => ' '),
                '2' => array('len' => 10, 'relleno' => ' ')
            ),
            'G' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'H' => array(
                '1' => array('len' => 8, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->estructuraOpcional = array(
            'A' => array('1' => '56', '2' => ''),
            'B' => array('1' => '', '2' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => ''),
            'E' => array('1' => ''),
            'F' => array('1' => '')
        );
        $this->lenOpcional = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 12, 'relleno' => ' ')
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'D' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'E' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'F' => array(
                '1' => array('len' => 14, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->estructuraSextoOpcional = array(
            'A' => array('1' => '56', '2' => '86'),
            'B' => array('1' => '', '2' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => ''),
            'E' => array('1' => '', '2' => ''),
            'F' => array('1' => '')
        );
        $this->lenSextoOpcional = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 12, 'relleno' => ' ')
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'D' => array(
                '1' => array('len' => 40, 'relleno' => ' ')
            ),
            'E' => array(
                '1' => array('len' => 35, 'relleno' => ' '),
                '2' => array('len' => 5, 'relleno' => '0')
            ),
            'F' => array(
                '1' => array('len' => 14, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->estructuraTotalOrdenante = array(
            'A' => array('1' => '58', '2' => '80'),
            'B' => array('1' => '', '2' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => ''),
            'E' => array('1' => '', '2' => ''),
            'F' => array('1' => '', '2' => '', '3' => ''),
            'G' => array('1' => '')
        );
        $this->lenTotalOrdenante = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 12, 'relleno' => $this->rellenoLibre)
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => $this->rellenoLibre)
            ),
            'D' => array(
                '1' => array('len' => 20, 'relleno' => $this->rellenoLibre)
            ),
            'E' => array(
                '1' => array('len' => 10, 'relleno' => '0'),
                '2' => array('len' => 6, 'relleno' => $this->rellenoLibre)
            ),
            'F' => array(
                '1' => array('len' => 10, 'relleno' => '0'),
                '2' => array('len' => 10, 'relleno' => '0'),
                '3' => array('len' => 20, 'relleno' => $this->rellenoLibre)
            ),
            'G' => array(
                '1' => array('len' => 18, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->estructuraTotalGeneral = array(
            'A' => array('1' => '59', '2' => '80'),
            'B' => array('1' => '', '2' => ''),
            'C' => array('1' => ''),
            'D' => array('1' => '', '2' => ''),
            'E' => array('1' => '', '2' => ''),
            'F' => array('1' => '', '2' => '', '3' => ''),
            'G' => array('1' => '')
        );
        $this->lenTotalGeneral = array(
            'A' => array(
                '1' => array('len' => 2, 'relleno' => '0'),
                '2' => array('len' => 2, 'relleno' => '0')
            ),
            'B' => array(
                '1' => array('len' => 12, 'relleno' => ' '),
                '2' => array('len' => 12, 'relleno' => $this->rellenoLibre)
            ),
            'C' => array(
                '1' => array('len' => 40, 'relleno' => $this->rellenoLibre)
            ),
            'D' => array(
                '1' => array('len' => 4, 'relleno' => '0'),
                '2' => array('len' => 16, 'relleno' => $this->rellenoLibre)
            ),
            'E' => array(
                '1' => array('len' => 10, 'relleno' => '0'),
                '2' => array('len' => 6, 'relleno' => $this->rellenoLibre)
            ),
            'F' => array(
                '1' => array('len' => 10, 'relleno' => '0'),
                '2' => array('len' => 10, 'relleno' => '0'),
                '3' => array('len' => 20, 'relleno' => $this->rellenoLibre)
            ),
            'G' => array(
                '1' => array('len' => 18, 'relleno' => $this->rellenoLibre)
            )
        );

        $this->conceptos = array();
        $this->ordenantes = array();
        $this->obligatorios = array();
        $this->opcionales = array();
    }


    //Un m�todo para construir el registro indicado. $index se usa para construir registros que est�n almacenados en arrays
    //acumulativos de registros, como $obligatorios o $ordenantes; indica el �ndice num�rico del registro dentro del array
    private function construirRegistro($tipo, $index = 0){
        //La variable de retorno del m�todo
        $registro = '';
        //El entero que usaremos para asignarle la constante de alineaci�n de los caracteres de relleno de str_pad
        $alineacion = 0;

        //Seg�n el tipo de registro...
        switch ($tipo){
            case 'presentador':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->estructuraPresentador as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->estructuraPresentador[$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenPresentador[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenPresentador[$keyZona][$keySubzona]['len'],
                            $this->lenPresentador[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenPresentador[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            case 'ordenante':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->ordenantes[$index] as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->ordenantes[$index][$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenOrdenante[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenOrdenante[$keyZona][$keySubzona]['len'],
                            $this->lenOrdenante[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenOrdenante[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            case 'obligatorio':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->obligatorios[$index] as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->obligatorios[$index][$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenObligatorio[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenObligatorio[$keyZona][$keySubzona]['len'],
                            $this->lenObligatorio[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenObligatorio[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            case 'opcional':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->estructuraOpcional as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->estructuraOpcional[$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenOpcional[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenOpcional[$keyZona][$keySubzona]['len'],
                            $this->lenOpcional[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenOpcional[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            case 'opcional6':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->estructuraSextoOpcional as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->estructuraSextoOpcional[$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenSextoOpcional[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenSextoOpcional[$keyZona][$keySubzona]['len'],
                            $this->lenSextoOpcional[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenSextoOpcional[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            case 'totalordenante':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->estructuraTotalOrdenante as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->estructuraTotalOrdenante[$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenTotalOrdenante[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenTotalOrdenante[$keyZona][$keySubzona]['len'],
                            $this->lenTotalOrdenante[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenTotalOrdenante[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            case 'totalgeneral':{

                //Por cada zona del registro... ($keyZona tendr� la letra de la zona, y $valorZona lo que contenga ese �ndice)
                foreach ($this->estructuraTotalGeneral as $keyZona => $valorZona){

                    //Por cada subzona de la zona... ($keySubzona tendr� la subzona de la zona actual, y $valorSubzona su valor
                    foreach ($this->estructuraTotalGeneral[$keyZona] as $keySubzona => $valorSubzona){

                        //Sacamos el valor de la alineaci�n seg�n el caracter de relleno
                        switch ($this->lenTotalGeneral[$keyZona][$keySubzona]['relleno']){
                            //Si es un cero hay que rellenar por la izquierda con STR_PAD, ya que es un campo num�rico
                            case '0':{
                                $alineacion = STR_PAD_LEFT;
                            }
                            break;
                            //En el resto de casos, lo hacemos por la derecha
                            default:{
                                $alineacion = STR_PAD_RIGHT;
                            }
                            break;
                        }

                        //A�adimos el valor formateado a la cadena del registro
                        $registro .= substr(str_pad($valorSubzona, $this->lenTotalGeneral[$keyZona][$keySubzona]['len'] ,
                            $this->lenTotalGeneral[$keyZona][$keySubzona]['relleno'], $alineacion), 0, $this->lenTotalGeneral[$keyZona][$keySubzona]['len']);
                    }

                }

            }
            break;
            //Por defecto devolvemos FALSE, para que el mensaje pueda ser detectado bien en caso de error
            default:{
                $registro = false;
            }
            break;
        }

        return $registro;
    }


    //M�todo privado para desglosar los conceptos de una domiciliaci�n en los registros que sea necesario
    //El par�metro que recibe es el �ndice del array de obligatorios al que hacen referencia los conceptos
    //Ojo, porque la clase est� pensada para recibiar los conceptos por l�neas de m�ximo 80 caracteres
    private function construirConceptos($refObligatorio){
        //La variable de retorno, que devolver� un array con los OPCIONALES introducidos, o un array vac�o en caso de fallo
        $registros = array();

        //Primero comprobamos que exista el �ndice de referencia en el array de obligatorios y en el de conceptos
        if (isset($this->obligatorios[$refObligatorio], $this->conceptos[$refObligatorio])){
            //En esta variable guardaremos el n�mero de concepto opcional dentro del registro opcional (de 1 a 3)
            //Usaremos una conversi�n de ASCII sum�ndole este valor a 66, porque 67 es ya el caracter C y los sucesivos son
            //68 D y 69 E (que corresponden con las zonas donde van los conceptos)
            $numOpcional = 1;
            //A�adimos al registro obligatorio su concepto, que son los primeros 40 caracteres del primer concepto
            $this->obligatorios[$refObligatorio]['G']['1'] = substr($this->conceptos[$refObligatorio][0], 0, 40);

            //Construimos el obligatorio
            $registros[] = $this->construirRegistro('obligatorio', $refObligatorio);
            //Asignamos '81' al c�digo de dato de la estructura de opcionales y luego lo incrementaremos en 1 cada nuevo registro opcional
            $this->estructuraOpcional['A']['2'] = 81;
            //Aqu� asignamos el c�digo del ordenante y de referencia del registro obligatorio al que hacen referencia estos opcionales
            $this->estructuraOpcional['B']['1'] = $this->obligatorios[$refObligatorio]['B']['1'];
            $this->estructuraOpcional['B']['2'] = $this->obligatorios[$refObligatorio]['B']['2'];

            //Comprobamos si en el primer �ndice hab�a m�s de 40 caracteres, para guardarlo como concepto opcional
            if (strlen($this->conceptos[$refObligatorio][0]) > $this->lenOpcional['C']['1']['len']){
                $this->estructuraOpcional['C']['1'] = substr($this->conceptos[$refObligatorio][0], 40, $this->lenOpcional['C']['1']['len']);
            }
            else
                $this->estructuraOpcional['C']['1'] = str_repeat($this->lenOpcional['C']['1']['relleno'], $this->lenOpcional['C']['1']['len']);

            //Incrementamos el n�mero de campo opcional
            ++$numOpcional;

            //Recorremos el array de conceptos empezando por el elemento 1; el 0 ya lo hemos procesado
            //Como mucho registramos hasta el octavo, ya que el recibo se compone de 8 filas de 80 caracteres
            for ($i = 1, $maxOpcionales = count($this->conceptos[$refObligatorio]) + 1; $i < $maxOpcionales && $i < 8; ++$i){
                if (!isset($this->conceptos[$refObligatorio][$i])){
                    $this->conceptos[$refObligatorio][$i] = '';
                }
                $this->estructuraOpcional[chr(66 + $numOpcional)]['1'] = substr($this->conceptos[$refObligatorio][$i], 0, $this->lenOpcional[chr(66 + $numOpcional)]['1']['len']);
                //Avanzamos en el n�mero de campo opcional, que se rotar� entre 1 y 3
                $numOpcional = ($numOpcional % 3) + 1;
                //Ahora comprobamos si estamos en el campo opcional 1, en cuyo caso tendremos que construir el registro opcional
                if ($numOpcional == 1){
                    $registros[] = $this->construirRegistro('opcional');
                    //Incrementamos en 1 la zona A2, que tiene valores entre 81 y 85 en los opcionales (menos el sexto opcional)
                    ++$this->estructuraOpcional['A']['2'];
                }
                //Comprobamos si hay m�s de 40 caracteres en el concepto; si no los hay rellenaremos con espacios en blanco
                if (strlen($this->conceptos[$refObligatorio][$i]) > $this->lenOpcional[chr(66 + $numOpcional)]['1']['len']){
                    $this->estructuraOpcional[chr(66 + $numOpcional)]['1'] = substr($this->conceptos[$refObligatorio][$i], 40, 40);
                }
                else
                    $this->estructuraOpcional[chr(66 + $numOpcional)]['1'] = '';
                //Avanzamos en el n�mero de campo opcional, que se rotar� entre 1 y 3
                $numOpcional = ($numOpcional % 3) + 1;
                //Ahora comprobamos si estamos en el campo opcional 1, en cuyo caso tendremos que construir el registro opcional
                if ($numOpcional == 1){
                    $registros[] = $this->construirRegistro('opcional');
                    //Incrementamos en 1 la zona A2, que tiene valores entre 81 y 85 en los opcionales (menos el sexto opcional)
                    ++$this->estructuraOpcional['A']['2'];
                }
            }

            //Ahora tenemos que comprobar si hay m�s conceptos, en cuyo caso se tratar�n de los que hay en el sexto opcional
            //En $maxOpcionales tenemos el count de conceptos; lo hemos asignado en la cabecera del for anterior
            if ($maxOpcionales > 8){
                //A�adimos el primero campo del sexto registro opcional
                $this->estructuraSextoOpcional['C']['1'] = substr($this->conceptos[$iRefOblogatorio][8], 0, 40);
                //Si hay m�s caracteres, a�adimos los 40 siguientes como segundo campo
                if (strlen($this->conceptos[$refObligatorio][8]) > 40){
                    $this->estructuraSextoOpcional['D']['1'] = substr($this->conceptos[$iRefOblogatorio][8], 40, 40);
                    //Si existe el 9� �ndice dentro del array de conceptos, a�adimos los primeros 35 caracteres en el sexto opcional
                    if (isset($this->conceptos[9])){
                        $this->estructuraSextoOpcional['E']['1'] = substr($this->conceptos[$iRefOblogatorio][8], 0, 35);
                        //Si hay m�s caracteres, a�adimos los 5 siguientes en la zona E2
                        if (strlen($this->conceptos[$refObligatorio][9]) > 35){
                            $this->estructuraSextoOpcional['E']['2'] = substr($this->conceptos[$iRefOblogatorio][8], 35, 5);
                        }
                        else
                            $this->estructuraSextoOpcional['E']['2'] = '';
                    }
                    else{
                        $this->estructuraSextoOpcional['E']['1'] = '';
                        $this->estructuraSextoOpcional['E']['2'] = '';
                    }
                }
                else{
                    $this->estructuraSextoOpcional['D']['1'] = '';
                    $this->estructuraSextoOpcional['E']['1'] = '';
                    $this->estructuraSextoOpcional['E']['2'] = '';
                }
                //Contruimos el sexto opcional
                $registros[] = $this->construirRegistro('opcional6');
            }
        }

        return $registros;
    }


    //Un m�todo para guardar un registro en el array de registros; s�lo permitira almacenar los tipos de registro
    //que se pueden repetir por archivo (ordenante y domiciliaci�n).
    //El de ordenante se guarda como array dentro de su propio array, porque luego usaremos sus zonas para hacer comprobaciones
    //El par�metro $conceptos es para cuando se graba una domiciliaci�n
    public function guardarRegistro($tipo, $conceptos = ''){
        //La variable boolena de retorno; en principio a true
        $guardado = true;

        //Hacemos el switch para guardar el registro en el array apropiado; no hay caso por defecto porque en caso de
        //ser un tipo no v�lido ya ha devuelto FALSE el m�todo construirRegistro
        switch ($tipo){
            case 'ordenante':{
                $this->ordenantes[] = $this->estructuraOrdenante;
            }
            break;
            case 'domiciliacion':{
                //Guardamos el registro obligatorio; los conceptos se guardan con el �ndice del obligatorio al que se refieren,
                //porque se crean a la vez ambos elementos
                $this->obligatorios[] = $this->estructuraObligatorio;
                $this->conceptos[] = $conceptos;
            }
            break;
            default:{
                $guardado = false;
            }
            break;
        }

        return $guardado;
    }


    //M�todo para asignar valores a las zonas seg�n nombres de campo preestablecidos en la clase
    //Si el nombre de campo no est� definido en la clase, se devuelve false
    public function insertarCampo($campo, $valor){
        $insertado = true;

        switch ($campo){
            case 'codigo_presentador':{
                $this->estructuraPresentador['B']['1'] = $valor;
            }
            break;
            case 'fecha_fichero':{
                //La fecha de confecci�n del fichero va en la cabecera del presentador y en las de los ordenantes
                $this->estructuraPresentador['B']['2'] = $valor;
                $this->estructuraOrdenante['B']['2'] = $valor;
            }
            break;
            case 'nombre_presentador':{
                $this->estructuraPresentador['C']['1'] = $valor;
            }
            break;
            case 'entidad_receptora':{
                $this->estructuraPresentador['E']['1'] = $valor;
            }
            break;
            case 'oficina_presentador':{
                $this->estructuraPresentador['E']['2'] = $valor;
            }
            break;
            case 'fecha_control':{
                $this->estructuraPresentador['F']['1'] = $valor;
            }
            break;
            case 'codigo_ordenante':{
                $this->estructuraOrdenante['B']['1'] = $valor;
            }
            break;
            case 'fecha_cargo':{
                $this->estructuraOrdenante['B']['3'] = $valor;
            }
            break;
            case 'nombre_ordenante':{
                $this->estructuraOrdenante['C']['1'] = $valor;
            }
            break;
            case 'cuenta_abono_ordenante':{
                //Separamos la cuenta en sus cuatro valores
                $this->estructuraOrdenante['D']['1'] = substr($valor, 0, 4);
                $this->estructuraOrdenante['D']['2'] = substr($valor, 4, 4);
                $this->estructuraOrdenante['D']['3'] = substr($valor, 8, 2);
                $this->estructuraOrdenante['D']['4'] = substr($valor, 10, 10);
            }
            break;
            case 'ordenante_domiciliacion':{
                $this->estructuraObligatorio['B']['1'] = $valor;
            }
            break;
            case 'codigo_referencia_domiciliacion':{
                $this->estructuraObligatorio['B']['2'] = $valor;
            }
            break;
            case 'nombre_cliente_domiciliacion':{
                $this->estructuraObligatorio['C']['1'] = $valor;
            }
            break;
            case 'cuenta_adeudo_cliente':{
                //Separamos la cuenta en sus cuatro valores
                $this->estructuraObligatorio['D']['1'] = substr($valor, 0, 4);
                $this->estructuraObligatorio['D']['2'] = substr($valor, 4, 4);
                $this->estructuraObligatorio['D']['3'] = substr($valor, 8, 2);
                $this->estructuraObligatorio['D']['4'] = substr($valor, 10, 10);
            }
            break;
            case 'importe_domiciliacion':{
                $this->estructuraObligatorio['E']['1'] = $valor;
            }
            break;
            case 'codigo_devolucion_domiciliacion':{
                $this->estructuraObligatorio['F']['1'] = $valor;
            }
            break;
            case 'codigo_referencia_interna':{
                $this->estructuraObligatorio['F']['2'] = $valor;
            }
            break;
            default:{
                $insertado = false;
            }
            break;
        }

        return $insertado;
    }


    //M�todo para construir el archivo en formato AEB19; lo devuelve como una cadena, con un registro por l�nea.
    //Es importante comprender la estructura del m�todo, c�mo crea el archivo. Primero introduce el registro del presentador;
    //luego, por cada ordenante, obtiene sus registros obligatorios. Por cada obligatorio obtiene sus opcionales (s�lo obtendr� 
    //el sexto opcional cuando haya obtenido 5 opcionales). En cada bucle se van sumando tanto los totales generales como los
    //totales ordenante
    public function construirArchivo(){
        //La variable de retorno, que contendr� el archivo construido como una cadena o FALSE en caso de error
        $archivo = '';

        //Un array temporal para guardar los registros del ordenante; en caso de que un ordenante no tenga registros,
        //no debemos incluirlo en el archivo. El count de este array lo podemos usar para obtener f�cilmente el n�mero total de
        //registros del ordenante
        $regsOrdenante = array();
        //El contador de registros total
        $totalRegs = 0;
        //El contador de domiciliaciones total
        $domiciliacionesTotal = 0;
        //El sumatorio del importe total del archivo
        $importeTotal = 0;
        //El contador de ordenantes, que se multiplicar� por 2 para obtener el n�mero de registros de ordenantes y el de
        //registros de total ordenante (no olvidemos: un registro de total ordenante por cada ordenante)
        $contOrdenantes = 0;
        //El contador de domiciliaciones de cada ordenante (o sea, el de obligatorios)
        $contDomiciliacionesOrd = 0;
        //El sumatorio del total ordenante
        $importeOrdenante = 0;
        //Construimos el registro del presentador
        $archivo .= $this->construirRegistro('presentador') . "\r\n";
        //Primer registro insertado, sumamos el contador de registros
        ++$totalRegs;

        //Por cada ordenante...
        foreach ($this->ordenantes as $keyOrdenante => $valorOrdenante){
            //Reseteamos el array de registros del ordenante
            $regsOrdenante = array();
            //Ponemos a 0 el n� de domiciliaciones del ordenante
            $contDomiciliacionesOrd = 0;
            //Reseteamos el sumatorio del total ordenante
            $importeOrdenante = 0;

            //Por cada registro obligatorio...
            foreach ($this->obligatorios as $keyObligatorio => $valorObligatorio){
                //Si el c�digo ordenante de la cabecera ordenante coincide con el del obligatorio... (zona B1)
                if ($valorOrdenante['B']['1'] == $valorObligatorio['B']['1']){
                    //Incrementamos el contador de domiciliaciones por ordenante
                    ++$contDomiciliacionesOrd;
                    //Sumamos el importe de la domiciliaci�n (zona E1) al total del ordenante
                    $importeOrdenante += $valorObligatorio['E']['1'];
                    //Mandamos el mensaje para insertar los conceptos en la domiciliaci�n; esto nos devuelve un array
                    //con el registro obligatorio, y los opcionales si los hay, ya construidos
                    $regsOrdenante = array_merge($regsOrdenante, $this->construirConceptos($keyObligatorio));
                }
            }

            //Si se han encontrado domiciliaciones para el ordenante...
            if ($contDomiciliacionesOrd){
                //Incrementamos en 1 el n� de ordenantes
                ++$contOrdenantes;

                //Si el n� de registros del ordenante entre el n� de domiciliaciones da mayor que 1, hay opcionales
                //por lo que usamos el procedimiento primero; si no, usamos el segundo
                if ((count($regsOrdenante) / $contDomiciliacionesOrd) > 1){
                    $this->ordenantes[$keyOrdenante]['E']['2'] = 1;
                }
                else
                    $this->ordenantes[$keyOrdenante]['E']['2'] = 2;

                //A�adimos al archivo el registro del ordenante (recordemos que est� sin construir)
                $archivo .= $this->construirRegistro('ordenante', $keyOrdenante) . "\r\n";
                $importeTotal += $importeOrdenante;
                //A�adimos los registros del ordenante al archivo (separ�ndolos por \r\n)
                $archivo .= implode("\r\n", $regsOrdenante);
                //Preparamos el registro del total ordenante y lo a�adimos construido a la cadena del archivo
                $this->estructuraTotalOrdenante['B']['1'] = $valorOrdenante['B']['1'];
                $this->estructuraTotalOrdenante['E']['1'] = $importeOrdenante;
                $this->estructuraTotalOrdenante['F']['1'] = $contDomiciliacionesOrd;
                //N� de registros del ordenante + cabecera ordenante + total ordenante
                $this->estructuraTotalOrdenante['F']['2'] = count($regsOrdenante) + 2;
                //Sumamos al total del registros el n�mero de registros del ordenante +2 (el del ordenante y el total ordenante)
                $totalRegs += count($regsOrdenante) + 2;
                $archivo .= "\r\n" . $this->construirRegistro('totalordenante') . "\r\n";
                //Incrementamos el n�mero total de domiciliaciones
                $domiciliacionesTotal += $contDomiciliacionesOrd;
            }
        }

        //Si la longitud de la cadena de retorno es mayor que 162 (tama�o de un registro) es que se han incluido domiciliaciones
        if (strlen($archivo) > 162){
            //Preparamos el registro de total general
            $this->estructuraTotalGeneral['B']['1'] = $this->estructuraPresentador['B']['1'];
            $this->estructuraTotalGeneral['D']['1'] = $contOrdenantes;
            $this->estructuraTotalGeneral['E']['1'] = $importeTotal;
            $this->estructuraTotalGeneral['F']['1'] = $domiciliacionesTotal;
            //El n�mero total de registros + total general (el de la cabecera presentador ya lo hemos sumado antes)
            $this->estructuraTotalGeneral['F']['2'] = $totalRegs + 1;
            $archivo .= $this->construirRegistro('totalgeneral');
        }
        else
            $archivo = false;

        return $archivo;
    }
};
?>