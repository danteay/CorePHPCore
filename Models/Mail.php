<?php
/**
 * @author Eduardo Aguilar <dante.aguilar41@gmail.com>
 */

namespace CorePHPCore\Models;


class Mail
{
    /**
     * @var string
     */
    public $from;
    /**
     * @var string
     */
    public $to;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $message;
    /**
     * @var bool
     */
    public $is_html;
    /**
     * @var string
     */
    public $encoding;
    /**
     * @var string
     */
    public $extra_headers;
    /**
     * @var bool
     */
    public $clean_msg;
    /**
     * @var array
     */
    public $template_keys;
    /**
     * @var string
     */
    public $reply_to;
    
    /**
     * MailUtils constructor.
     * 
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->from = '';
        $this->to = '';
        $this->title = '';
        $this->message = '';
        $this->is_html = false;
        $this->encoding = 'iso-8859-1';
        $this->extra_headers = '';
        $this->clean_msg = false;
        $this->template_keys = array();
        $this->reply_to = '';
        
        if(!empty($config)){
            foreach($config as $key => $value){
                if(isset($this->$key)){
                    $this->$key = $value;
                }
            }
        }
    }
    
    /**
     * Limpia y separa el mensaje en renglones de 100 caracteres agregando un salto de line entre ellos
     */
    public function cleanupMessage()
    {
        $this->message = wordwrap($this->message, 100, "\r\n");
    }
    
    /**
     * @param $template
     * @return bool
     * Procesa un Template para crear el cuerpo del mensaje
     * Requiere que antes se inicialize la variable $template_keys
     */
    public function fromTemplate($template)
    {
        if (file_exists($template)) {
            $gettemplate = file_get_contents($template);
            foreach ($this->template_keys as $key => $value) {
                $gettemplate = str_replace($key, $value, $gettemplate);
            }
            $this->message = $gettemplate;
            return true;
        }
        return false;
    }
    
    /**
     * @return bool
     * Envia un email en formato de texto plano
     */
    private function sendPlain()
    {
        $headers = 'From: '.$this->from."\r\n".
            'Reply-to: '.$this->reply_to."\r\n".
            $this->extra_headers;
        return mail($this->to, $this->title, $this->message, $headers);
    }
    
    /**
     * @return bool
     * Envia un email en formato HTML
     */
    private function sendHTML()
    {
        $headers = 'From: '.$this->from."\r\n".
            'Reply-to: '.$this->reply_to."\r\n".
            'MIME-Version: 1.0' . "\r\n".
            'Content-type: text/html; charset='.$this->encoding."\r\n".
            $this->extra_headers;

        return mail($this->to, $this->title, $this->message, $headers);
    }
    
    /**
     * @return bool
     * Ejecuta el envio de email dependiendo de el tipo de mensaje a enviar
     */
    public function sendEmail()
    {
        if ($this->is_html) {
            return $this->sendHTML();
        } else {
            return $this->sendPlain();
        }
    }
    
    /**
     * @param string $email
     * @return bool
     * Comprueba la valides de un Email
     */
    public function validarMail($email)
    {
        $mail_correcto = false;
        if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") 
            && (substr($email,strlen($email)-1,1) != "@")){
            
            if ((!strstr($email,"'")) && (!strstr($email,'/')) && (!strstr($email,"\$")) && (!strstr($email," "))){
                if (substr_count($email,".")>= 1){
                    $term_dom = substr(strrchr ($email, '.'),1);
                    if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                        $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                        $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                        if ($caracter_ult != "@" && $caracter_ult != "."){
                            $mail_correcto = true;
                        }
                    }
                }
            }
        }
        
        return $mail_correcto;
    }

}