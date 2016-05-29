<?php
/**
 * Created by PhpStorm.
 * User: eduardoay
 * Date: 28/05/16
 * Time: 06:36 PM
 */

namespace CorePHPCore\Exceptions;


class DirectoryException extends \Exception
{
    /**
     * Mensaje final de la excepsion.
     *
     * @var string
     */
    private $finalMessage;

    /**
     * DirectoryUtilsExeptions constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($rutaOrigen, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->finalMessage = $message . "\n<b>Ruta no encontrada: </b>" . $rutaOrigen;
        parent::__construct($this->finalMessage, $code, $previous);
    }

    /**
     * Impresion personalizada del objeto
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}