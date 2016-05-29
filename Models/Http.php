<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Models;


class Http
{
    /**
     * Inicializa el objeto Http para las peticiones
     *
     * @param null $url
     * @return resource
     */
    public static function initHttp($url = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $ch;
    }

    /**
     * Define el metodo que ejecutara en la peticion
     *
     * @param $ch               resource    Instancia del Objeto Http
     * @param $method           string      Tipo de peticion a ejecutar
     * @throws \Exception
     */
    public static function setMethod(&$ch,$method)
    {
        switch($method){
            case 'GET':
            case 'POST':
            case 'PUT':
            case 'DELETE':
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST, $method);
                break;
            default:
                throw new \Exception("Metodo no soportado");
                break;
        }
    }

    /**
     * Estable las claves de autentificacion a usar
     *
     * @param $ch       resource    Instancia del Objeto Http
     * @param $auth     string      Cadena de autentificacion del cliente
     */
    public static function setAuth(&$ch, $auth)
    {
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
    }

    /**
     * Carga los campos que se enviaran dentro de la peticion
     * El formato de envio es el sieguinte:
     * campo1=valor1&campo2=valor2&.....campox=valorx
     *
     * @param $ch               resource    Instancia del Objeto Http
     * @param string $fields                Campos a incluir en la peticion
     */
    public static function setPostFields(&$ch, $fields="")
    {
        if(!empty($fields)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
    }

    /**
     * Ejecuta la peticion Http que se le especifique
     *
     * @param $ch
     * @return mixed
     * @throws \Exception
     */
    public static function execHttp(&$ch)
    {
        $response = curl_exec($ch);
        return $response;
    }

    /**
     * Retorna un string con la descripcion del error
     *
     * @param $ch
     * @return mixed
     */
    public static function getHttpError(&$ch)
    {
        return curl_error($ch);
    }

    /**
     * Regresa el numero de error 
     *
     * @param $ch
     * @return mixed
     */
    public static function getHttpErrorNum(&$ch)
    {
        return curl_errno($ch);
    }
}