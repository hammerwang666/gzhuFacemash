<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 11/4/12
 * Time: 9:07 PM
 * To change this template use File | Settings | File Templates.
 */
class DBException extends Exception{
    protected $dbConnError;
    public $dbQryError;
    public $dbInsertError;
    public function __construct($message = null, $code = 0){
        $this->dbConnError = oci_error() . 'more info ' . $this->getLine();
        $this->dbQryError = oci_error() . 'more info ' . $this->getLine();
        $this->dbInsertError = oci_error() . 'more info ' . $this->getLine();
        parent::__construct($message, $code);
        Log4PHP::getLogger("Fanz" . __CLASS__)->error(":[{ $this->code }]: {$this->message} \r\n");
    }

    public function __toString(){
        return __CLASS__ . ":[{ $this->code }]: {$this->message} \r\n";
    }

    public function dbConnError(){
      return $this->dbConnError;
    }

    public function dbQryError(){
        return $this->dbQryError;
    }

    public function dbInsertError(){
        return $this->dbInsertError;
    }
}

class NullParamException extends Exception{
    public function __construct($message, $code = 0){
        parent::__construct($message, $code);
        Log4PHP::getLogger("Fanz" . __CLASS__)->error("[{ $this->code }] {$this->message} \r\n");
    }

    public function __toString(){
        return __CLASS__ . ":[{ $this->code }]: {$this->message} \r\n";
    }
}