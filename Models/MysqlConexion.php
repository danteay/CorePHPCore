<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Models;


use CorePHPCore\Abs\Conexion;
use CorePHPCore\Exceptions\ConexionException;

class MysqlConexion extends Conexion
{
    /**
     * MysqlConexion constructor.
     *
     * @param MysqlConexion|null $conx
     */
    public function __construct(MysqlConexion $conx = null)
    {
        if(!empty($conx))
            $this->conx = $conx;
    }

    /**
     * Funcion donde se inicalizara la conexion
     *
     * @param array $config
     * @throws ConexionException
     * @return mixed
     */
    public function __init__(array $config = array())
    {
        $conf = self::getConfig();

        if(!empty($config)) {
            if($this->validateConfig($config)){
                $conf = $config;
            }
        }

        $this->conx = new \mysqli($conf['host'], $conf['user'], $conf['pass'], $conf['dbas'], $conf['port']);

        if ($this->conx->connect_errno) {
            throw new ConexionException("Fail connect to MySQL: (" . $this->conx->connect_errno . ") " . $this->conx->connect_error);
        }
    }

    /**
     * Envia ejecuta un query que no retorna resultado (Insert, Update, Delete ...)
     *
     * @return bool
     * @throws ConexionException
     */
    public function setRequest()
    {
        $this->conx->query($this->query);

        if(empty($this->conx->error)){
            return true;
        }else{
            throw new ConexionException("<b>Error:</b> ".$this->conx->error);
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
        $data = $this->conx->query($this->query);
        if(empty($this->conx->error)){
            return self::normalizeData($data);
        }else{
            throw new ConexionException("<b>Error:</b> ".$this->conx->error);
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
        $final = array();

        while($fila = $data->fetch_object()){
            $final[] = $fila;
        }

        return $fila;
    }
}