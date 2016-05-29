<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Models;

use CorePHPCore\Exceptions\DirectoryException;

class Directory
{
    /**
     * Crea un directorio en la ruta especificada
     *
     * @param string $ruta
     */
    public function makeDir($ruta)
    {
        if(!file_exists($ruta)){
            mkdir($ruta);
        }
    }

    /**
     * Regresa el numero total de elementos contenidos en un directorio
     *
     * @param string $ruta
     * @return int
     */
    public function countElements($ruta)
    {
        $directorio = opendir($ruta);
        $cont = 0;

        while($archivo = readdir($directorio)){
            if($archivo != '.' && $archivo != '..'){
                $cont++;
            }
        }

        return $cont;
    }

    /**
     * Genera el listado con los nombres de los archivos contenidos en un directorio
     *
     * @param string $ruta
     * @param string|null $patern
     * @return array
     * @throws DirectoryException
     */
    public function listFiles($ruta, $patern = null)
    {
        if(!is_dir($ruta)){
            throw new DirectoryException($ruta,"La ruta no es un directorio valido.");
        }
        $directorio = opendir($ruta);
        $lista = array();
        $cont = 0;

        while($archivo = readdir($directorio)){
            if($archivo != '.' && $archivo != '..'){
                if(!empty($patern)){
                    if(preg_match($patern,$archivo)){
                        $lista[$cont] = $archivo;
                        $cont++;
                    }
                }else{
                    $lista[$cont] = $archivo;
                    $cont++;
                }
            }
        }

        return $lista;
    }
    
    /**
     * Borra un archivo
     *
     * @param string $ruta
     * @throws DirectoryException
     */
    public function deleteFile($ruta)
    {
        if(file_exists($ruta)){
            unlink($ruta);
        }else{
            throw new DirectoryException($ruta,"El archivo espesificado no existe.");
        }
    }

    /**
     * Borra un directorio especificado
     *
     * @param $ruta
     * @throws DirectoryException
     */
    public function deleteDirectory($ruta)
    {
        if(is_dir($ruta)){
            $lista = $this->listFiles($ruta);

            foreach($lista as $item){
                if(is_dir($ruta."/".$item)){
                    try{
                        $this->deleteDirectory($ruta."/".$item);
                    }catch(\Exception $e){
                        throw new DirectoryException($ruta,$e);
                    }
                }else{
                    $this->deleteFile($ruta."/".$item);
                }
            }

            rmdir($ruta);
        }else{
            throw new DirectoryException($ruta, "La ruta no existe o no es un directorio.");
        }
    }

    /**
     * Copia el contenido de una carpeta y su contenido en una ruta especificada
     *
     * @param string $origen
     * @param string $destino
     * @throws DirectoryException
     */
    public function fullCopy($origen, $destino)
    {
        if ( is_dir( $origen ) ) {
            mkdir( $destino );
            $d = dir( $origen );

            while ( FALSE !== ( $entry = $d->read() ) ) {
                if ( $entry == '.' || $entry == '..' ) {
                    continue;
                }
                $Entry = $origen . '/' . $entry;
                if ( is_dir( $Entry ) ) {
                    $this->fullCopy( $Entry, $destino . '/' . $entry );
                    continue;
                }
                copy( $Entry, $destino . '/' . $entry );
            }

            $d->close();
        }elseif(is_file($origen)) {
            copy( $origen, $destino );
        }else{
            throw new DirectoryException($origen, "Ruta de origen invalida.");
        }
    }

}