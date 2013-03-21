<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 7/14/12
 * Time: 2:58 PM
 * To change this template use File | Settings | File Templates.
 */

include_once("/../../lib/log4php/Logger.php");
include_once "poolConnect.php";
class DB {
    private $conn_;
    private $testConn_;
    private static $logger;


    public function __construct(){
        self::$logger =  Logger::getLogger("xie DB");
        $this->openConnection();

    }

    private function openConnection(){

        try{
            $this->conn_ = poolConnect();
            return $this->conn_;

        }catch (Exception $e){
            DB::$logger->error("数据库连接异常");
            throw new Exception($e->getMessage());

        }
    }

    public function getConnection($arg_isTest=FALSE){
        if($arg_isTest!=FALSE)
        $this->conn_ = poolConnect();
        return $this->conn_;


    }

    public function getTestConnection(){
        $this->testConn_ = poolConnect("CTMS_TEST_V1","CTMS_TEST_V1","//172.22.71.148/KDB", "UTF8");
        return $this->testConn_;
    }


    public function closeConnection(){
        oci_close($this->conn_);
    }
}
