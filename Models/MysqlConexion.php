<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Models;


use CorePHPCore\Abs\Conexion;

class MysqlConexion extends Conexion
{
    /**
     * Constrcutor de la conexion
     *
     * @param MysqlConexion|null $conx
     */
    public function __construct(MysqlConexion $conx = null)
    {
        if(!empty($conx)){
            $this->conx = $conx;
        }
    }

    /**
     * Funcion donde se inicalizara la conexion
     *
     * @param array $config
     * @return mixed
     */
    public function __init__(array $config = array())
    {
        if(!empty($config)){
            parent::validateConfig($config);

            foreach ($config as $key => $value){
                $this->$key = $value;
            }
        }else{
            $this->engine = "mysql";
            $this->port   = "3306";
            $this->host   = ":host:";
            $this->dbas   = ":dbas:";
            $this->user   = ":user:";
            $this->pass   = ":pass:";
        }

        $dns = "{$this->engine}:host={$this->host};port={$this->port};dbname={$this->dbas}";

        $this->conx = new \PDO($dns,$this->user,$this->pass);
    }
}