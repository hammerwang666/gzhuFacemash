<?php
/*
 * @author: luoxiongqing 
 */

// 【变量定义规则】：‘C_’=字符型,‘I_’=整型,‘N_’=数字型,‘B_’=布尔型,‘A_’=数组型,'R_'=资源变量

include_once '../../lib/Log4PHP/Log4PHP.php';
include_once 'poolConnect.php';

class OracleDB
{

    private $C_DB_user; //数据库用户名
    private $C_DB_password; //数据库密码
    private $C_DB_url; //数据库地址
    private $C_encoding; //数据库编码格式
    private $R_conn; //连接句柄资源
    private $R_queryState; //SQL编译句柄
    private $A_queryResult; //结果集
    private $B_exeResult = false; //execute执行结果
    private $C_message; //错误信息
    private $B_isSuccess = false;
    private $R_logger;
    public  $keyArray_;
    private $bToArrayByKey_ = false;
    public  $A_queryResultByKey;

//    public function __construct($C_arg_user = 'ctms_user', $C_arg_password = 'cst4Ever', $C_arg_dataBase = '//QNear.com/KDB', $C_arg_encode = 'UTF8')
//    {
//        $this->C_DB_user = $C_arg_user;
//        $this->C_DB_password = $C_arg_password;
//        $this->C_DB_user = $C_arg_user;
//        $this->C_DB_password = $C_arg_password;
//        $this->C_DB_url = $C_arg_dataBase;
//        $this->C_encoding = $C_arg_encode;
//        $this->R_logger = Log4PHP::getLogger("Xiong");
////        $this->R_logger->info('construct OracleDB.');
//
//    }


    //连接数据库
    public function connect()
    {
        try{
        $this->R_conn = poolConnect(/*$this->C_DB_user, $this->C_DB_password, $this->C_DB_url, $this->C_encoding*/);
        }catch (Exception $e)
        {
            $this->R_logger->error('数据库连接异常');
            return false;
        }
         if (!$this->R_conn) {
            $m = oci_error();
            $this->C_message = '数据库连接异常.';
            $this->R_logger->error('connect Oracle error.');
            return false;

        }

    }

    //执行sql语句
    public function query($C_arg_querySQL)
    {
//    	$this->R_logger->info('begin query.');

        if (!isset($C_arg_querySQL) || trim($C_arg_querySQL) == "") {
            $this->C_message = 'SQL语句为空.';
            $this->R_logger->error('SQL语句为空.');
            return false;

        }
        $this->connect();
        if (!$this->R_conn) {
            $this->C_message = '连接数据库出错.';
            $this->R_logger->error('connect Oracle error.');
            return false;
        }
        try{
        $this->R_queryState = oci_parse($this->R_conn, $C_arg_querySQL);
        }catch (Exception $e) {

            $this->C_message = 'oci_parse SQL have exception.';
            $this->R_logger->error('oci_parse SQL have exception');
            return false;
        }
        if (!$this->R_queryState) {
            $this->message = 'SQL格式错误.';
            $this->R_logger->error('SQL格式错误.');
            return false;
        }

        try {
            $this->B_exeResult = oci_execute($this->R_queryState);
        } catch (Exception $e) {
            $this->C_message = 'execute SQL have exception.';
            $this->R_logger->error('execute SQL have exception');
            return false;
        }


        if (!$this->B_exeResult) {
            $this->C_message = '执行SQL出错.';
            $this->R_logger->error('执行SQL出错.');
            return false;

        }

        return $this->B_exeResult;
    }


    //将结果集转为 数组
    public function toArray()
    {
        if (!$this->B_exeResult || !$this->R_queryState) {

            $this->C_message = '没有查询结果.';

        }
        $rows = Array();
        while ($row = oci_fetch_assoc($this->R_queryState)) {
            array_push($rows, $row);
        }
        $this->C_message = '操作成功.';
        return $rows;
    }

    /**
     * 获取数据  返回结果集
     * @param $keyArray  关键词
     * @return array   数据
     */
    public function toArrayByKey()
    {
        if (!$this->B_exeResult || !$this->R_queryState) {
            $this->C_message = '没有查询结果.';
        }
        if (count($this->keyArray_) != oci_num_fields($this->R_queryState)) {
            $this->C_message = '输入键值数量与列数不对应';
            $this->R_logger->error('输入键值数量与列数不对应');
            $this->bToArrayByKey_ = false;
            return $this->bToArrayByKey_;
        }
        $this->A_queryResultByKey = array();
        while ($row = oci_fetch_row($this->R_queryState)) {
            $arr = array_combine($this->keyArray_,$row);
            $this->A_queryResultByKey [] = $arr;
        }
        $this->bToArrayByKey_ = true;
        return $this->bToArrayByKey_;
    }


    //将查询结果集转为json格式
    public function toJson()
    {
//
        return json_encode($this->toArray());
    }


    //释放资源
    public function free_result()
    {
        @ocifreestatement($this->R_queryState);
        $this->R_queryState = 0;
        $this->B_exeResult = false;
        $this->C_message = '';
    }

    //关闭连接
    public function close()
    {
        if ($this->R_queryState) {
            $this->free_result();
        }
        if ($this->R_conn) {
            OCILogoff($this->R_conn);
        }
//        $this->R_logger->info(' Close the Oracle connection');
    }

    public function __destruct()
    {
        $this->close();
    }


    /**
     * @return the $B_exeResult
     */
    public function getB_exeResult()
    {
        return $this->B_exeResult;
    }

    /**
     * @return the $A_queryResult
     */
    public function getA_queryResult()
    {
        return $this->A_queryResult;
    }

    /**
     * @return the $C_DB_user
     */
    public function getC_DB_user()
    {
        return $this->C_DB_user;
    }

    /**
     * @return the $C_DB_password
     */
    public function getC_DB_password()
    {
        return $this->C_DB_password;
    }

    /**
     * @return the $C_DB_url
     */
    public function getC_DB_url()
    {
        return $this->C_DB_url;
    }

    /**
     * @return the $C_encoding
     */
    public function getC_encoding()
    {
        return $this->C_encoding;
    }

    /**
     * @return the $C_message
     */
    public function getC_message()
    {
        return $this->C_message;
    }

    /**
     * @param field_type $C_DB_user
     */
    public function setC_DB_user($C_DB_user)
    {
        $this->C_DB_user = $C_DB_user;
    }

    /**
     * @param field_type $C_DB_password
     */
    public function setC_DB_password($C_DB_password)
    {
        $this->C_DB_password = $C_DB_password;
    }

    /**
     * @param field_type $C_DB_url
     */
    public function setC_DB_url($C_DB_url)
    {
        $this->C_DB_url = $C_DB_url;
    }

    /**
     * @param field_type $C_encoding
     */
    public function setC_encoding($C_encoding)
    {
        $this->C_encoding = $C_encoding;
    }

    /**
     * @return the $R_conn
     */
    public function getR_conn()
    {
        return $this->R_conn;
    }

    /**
     * @return the $R_queryState
     */
    public function getR_queryState()
    {
        return $this->R_queryState;
    }


}