<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Libs;

use CorePHPCore\Exceptions\ConexionException;

/**
 * Trait que cotiene la configuracion global de las conexiones
 *
 * Class ConexionConfig
 * @package CorePHPCore\Libs
 */
trait ConexionConfig
{
    /**
     * Configuracion general
     *
     * @return array
     */
    public static function getConfig()
    {
        return array(
            'engine' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'pass' => 'root',
            'dbas' => 'Lucio'
        );
    }

    /**
     * Puertos por defecto de los motores soportados
     *
     * @return array
     */
    public static function defaultPorts()
    {
        return array(
            'mysql' => '3306'
        );
    }

    public static function validFields()
    {
        return ['engine', 'host', 'user', 'pass', 'dbas'];
    }

    public static function supportedEngines()
    {
        return ['mysql'];
    }

    /**
     * Validacion de n arreglo de configuracion
     *
     * @param array $config
     * @throws ConexionException
     * @return boolean
     */
    public function validateConfig(array &$config)
    {
        $validFields = self::validFields();
        $validEngine = self::supportedEngines();

        foreach ($validFields as $value){
            if(!array_key_exists($value, $config)){
                throw new ConexionException('Config array is not valid');
            }
        }

        $engineFlag = false;

        foreach ($validEngine as $value) {
            if($config['engine'] == $value)
                $engineFlag = true;
        }

        if(!$engineFlag)
            throw new ConexionException('Engine not supported');

        if(!isset($config['port'])){
            $config['port'] = self::defaultPorts()[$config['engine']];
        }

        return true;
    }

}