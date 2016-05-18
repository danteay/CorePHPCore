<?php
/**
 * Created by PhpStorm.
 * User: eduardoay
 * Date: 18/05/16
 * Time: 12:09 AM
 */

namespace CorePHPCore\Test;


use CorePHPCore\Models\MysqlConexion;

class MysqlConexionTest extends \PHPUnit_Framework_TestCase
{

    public function testInstanceConexion()
    {
        $config = [
            'host' => 'localhost',
            'port' => '3306',
            'dbas' => 'Lucio',
            'user' => 'root',
            'pass' => 'root'
        ];

        $conx = new MysqlConexion();

        try{
            $conx->__init__($config);
            $this->assertTrue(true);
        }catch(\Exception $e){
            $this->assertTrue(false);
        }

        return $conx;
    }

}
