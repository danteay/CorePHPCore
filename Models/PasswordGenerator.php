<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */


namespace CorePHPCore\Models;


class PasswordGenerator
{
    /**
     * Arrego de inicializacion para generar la cadena aleatorea
     *
     * @var array
     */
    public $args = array(
        'lenght'        => 8,       // Tamaño de la cadena resultante
        'alpha_upper'   => true,    // Incluir letras mayusculas
        'alpha_lower'   => true,    // Incluir letras minusculas
        'number'        => true,    // Incluir numeros
        'symbol'        => true,    // Incluir caracteres especiales
    );

    /**
     * Arreglo de letras mayusculas permitidas
     *
     * @var array
     */
    private $alpha_upper = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q",
        "R", "S", "T", "U", "V", "W", "X", "Y", "Z" );

    /**
     * @var array
     * Arreglo de letras minusculas permitidas
     */
    private $alpha_lower = array( "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q",
        "r", "s", "t", "u", "v", "w", "x", "y", "z" );

    /**
     * @var array
     * Arreglo de numeros permitidos
     */
    private $number = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );

    /**
     * @var array
     * Arreglo de caracteres especiales permitidos
     */
    private $symbol = array( "-", "_", "^", "~", "@", "&", "|", "=", "+", ";", "!", ",", "(", ")", "{", "}", "[", "]",
        ".", "?", "%", "*", "#" );

    /**
     * @param array $args
     * Constructor de clase
     */
    public function __construct( $args = array() ) {
        $this->set_args( $args );
    }

    /**
     * Compara el arreglo de opciones por defecto con el que se pasa al constructor de la clase y reemplaza por los
     * nuevos valores establecidos
     *
     * @param array $args
     * @param array $defaults
     * @return array
     */
    private function chip_parse_args( $args = array(), $defaults = array() )
    {
        return array_merge( $defaults, $args );
    }

    /**
     * Configura los parametros pasados en el arreglo principal
     *
     * @param array $args
     */
    private function set_args( $args = array() )
    {
        $defaults = $this->args;
        $args = $this->chip_parse_args( $args, $defaults );
        $this->args = $args;
    }

    /**
     * Genera la cadena de caracteres aleatorios segun la configuracion establecida
     *
     * @return int|string
     */
    private function set_password()
    {
        $temp = array();
        $exec = array();
        $args = $this->args;

        if( $args['lenght'] <= 0 ) {
            return 0;
        }
        if( $args['alpha_upper'] == TRUE ) {
            $alpha_upper = $this->alpha_upper;
            $exec[] = 1;
        }
        if( $args['alpha_lower'] == TRUE ) {
            $alpha_lower = $this->alpha_lower;
            $exec[] = 2;
        }
        if( $args['number'] == TRUE ) {
            $number = $this->number;
            $exec[] = 3;
        }
        if( $args['symbol'] == TRUE ) {
            $symbol = $this->symbol;
            $exec[] = 4;
        }

        $exec_count = count( $exec ) - 1;
        $input_index = 0;

        for ( $i = 0; $i < $args['lenght']; $i++ ) {
            switch( $exec[$input_index] ) {
                case 1:
                    shuffle( $alpha_upper );
                    $temp[] = $alpha_upper[0];
                    unset( $alpha_upper[0] );
                    break;
                case 2:
                    shuffle( $alpha_lower );
                    $temp[] = $alpha_lower[0];
                    unset( $alpha_lower[0] );
                    break;
                case 3:
                    shuffle( $number );
                    $temp[] = $number[0];
                    unset( $number[0] );
                    break;
                case 4:
                    shuffle( $symbol );
                    $temp[] = $symbol[0];
                    unset( $symbol[0] );
                    break;
            }

            if ( $input_index < $exec_count ) {
                $input_index++;
            } else {
                $input_index = 0;
            }
        }

        shuffle($temp);
        $password = implode( $temp );

        return $password;
    }

    /**
     * Regresa la cadena aleatoria generada
     *
     * @return int|string
     */
    public function get_password() {
        return $this->set_password();
    }
}