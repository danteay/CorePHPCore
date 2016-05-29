<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Abs;

use CorePHPCore\Exceptions\ConexionException;
use CorePHPCore\Libs\ConexionConfig;

abstract class Conexion
{
    use ConexionConfig;
    
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
    public abstract function setRequest();
    

    /**
     * Ejecuta un query que regresa informacion (Select, Describe ...)
     *
     * @return array
     * @throws ConexionException
     */
    public abstract function getRequest();


    /**
     * Regresa un arreglo de objetos con la informacion de una consulta ejecutada
     *
     * @param $data
     * @return array
     */
    protected static abstract function normalizeData($data);
    

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