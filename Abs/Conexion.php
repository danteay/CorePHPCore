<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Abs;

use CorePHPCore\Exceptions\ConexionException;

abstract class Conexion
{
    protected $host;
    protected $port;
    protected $dbas;
    protected $user;
    protected $pass;
    protected $engine;

    public $conx;

    protected $query;

    /**
     * Funcion donde se inicalizara la conexion
     *
     * @param array $config
     * @return mixed
     */
    public abstract function __init__(array $config = array());

    /**
     * Obtiene el query actualmente cargado
     *
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Busca y reeplaza valores para inicializar un query
     *
     * @param $query
     * @param array|null $replace
     */
    public function initializeQuery($query, array $replace = null)
    {
        if(!empty($replace)){
            foreach ($replace as $key => $value){
                $query = str_replace($key,$value,$query);
            }
        }

        $this->query = $query;
    }

    /**
     * Envia ejecuta un query que no retorna resultado (Insert, Update, Delete ...)
     *
     * @return bool
     * @throws ConexionException
     */
    public function setRequest()
    {
        try{
            $this->conx->query($this->query);
            $erros = $this->conx->errorInfo();

            if(empty($erros[2])){
                return true;
            }else{
                throw new ConexionException($erros[2]);
            }
        }catch (\Exception $e){
            throw new ConexionException($e->getMessage());
        }
    }

    /**
     * Ejecuta un query que regresa informacion (Select, Describe ...)
     *
     * @return array
     * @throws ConexionException
     */
    public function getRequest()
    {
        try{
            $data = $this->conx->query($this->query);
            $erros = $this->conx->errorInfo();

            if(empty($erros[2])){
                return self::normalizeData($data);
            }else{
                throw new ConexionException($erros[2]);
            }
        }catch (\Exception $e){
            throw new ConexionException($e->getMessage());
        }
    }

    /**
     * Regresa un arreglo de objetos con la informacion de una consulta ejecutada
     *
     * @param $data
     * @return array
     */
    protected static function normalizeData($data)
    {
        $res = array();

        while($fila = $data->fetch(PDO::FETCH_OBJ)){
            $res[] = $fila;
        }

        return $res;
    }

    /**
     * Valida la existencia de los campos requeridos para la configuracion de la conexion
     *
     * @param array $config
     * @throws ConexionException
     */
    public static function validateConfig(array $config)
    {
        $verify = ['host','port','dbas','user','pass'];
        foreach ($verify as $value){
            if(!array_key_exists($value,$config)){
                throw new ConexionException("Config is not valid");
            }
        }
    }

    /**
     * Codigo a ejecutarse cuando este objeto sea clonado
     */
    public function __clone()
    {
        throw new ConexionException("This instance can't be cloned");
    }

    /**
     * Cotigo a ejecutarse cuando este objeto sea serializado
     */
    public function __wakeup()
    {
        throw new ConexionException("This instance can't be serialize");
    }
}