<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 13-1-26
 * Time: 下午4:02
 * To change this template use File | Settings | File Templates.
 */
//include_once '/../../lib/Log4PHP/Log4PHP.php';

class dbUtil
{
    const TORDERNAME = 'T_ORDER';
    //column name of T_ORDER
    const TORDERCOLUMN = 'ORDER_ID, ORDER_NO, MB_ID, SEATS_NUM, PRICE, CINEMA_ID, CINEMA_NAME, HALL_ID, HALL_NAME,
        FILM_ID, FILM_NAME, CFM_ID, SHOW__DATE, SHOW_TIME,REFUND_NUM, FEE, SERVICE_ID, SERVICE_NAME,
        PAY_ID, PAY_NAME, CFM_FLG, CUSTOMER_NAME, CUSTOMER_PHONE, PAY_STATUS, ORDER_TIME, POSTER_PATH,
         SEATS_STR, USER_IP, MBCARD_NO, MBCARD_BALANCE, TICKET_TYPE, PRICE_TYPE, PRICE_CONTENT, CARD_NO,
          USER_REALNAME, ADD1, ADD2, ADD3, ADD4, ADD5';
    //sequence name of T_ORDER
    const TORDERSQ = 'S_ORDER';


    /**
     * use for insert or update
     * @static
     * @param $arg_conn
     * @param $arg_sql
     * @return bool
     * @throws NullParamException
     * @throws NotStringException
     * @throws DBException
     */
    public static function execSql($arg_conn, $arg_sql){
        $logger_ = Log4PHP::getLogger("Fanz_execSql");
//        if(!isParamNull($logger_, $arg_sql)){
//            throw new NullParamException("arg_sql is null");
//        }
//        if(!is_string($arg_sql)){
//            throw new NotStringException('arg_sql is not string');
//        }
        try {
            $connResult_ = oci_parse($arg_conn, $arg_sql);
        } catch (Exception $e) {
            throw new DBException("execSql parseSql error: " . $e->getMessage());
        }
        try {
            $exeResultSingle_ = oci_execute($connResult_, OCI_COMMIT_ON_SUCCESS);
            if (!$exeResultSingle_) {
                $logger_->error("oci execute error");
                oci_rollback($arg_conn);
            }
        } catch (Exception $e) {
            throw new DBException("execSql execSql error: " . $e->getMessage());
        }
        //    $logger_->info('exeResult: ' . $exeResultSingle_);
        if (!$exeResultSingle_) $result_ = false;
        else $result_ = true;
        return $result_;
    }

    /**
     *  close db connection
     * @static
     */
//    public static function  closeDbConnection()
//    {
//        $db_ = new OracleDB();
//        return $db_->close();
//    }


    /**
     * @static
     * @param $arg_conn
     * @param $arg_sql
     * @param bool $arg_isTest
     * @param bool $arg_isJson
     * @return string
     * @throws NullParamException
     * @throws Exception
     * @throws NotStringException
     * @throws DBException
     */
    public static function getJsonArrayDb($arg_conn, $arg_sql, $arg_isTest = FALSE, $arg_isJson = FALSE)
    {
        $logger_ = Log4PHP::getLogger("Fan_getJsonArrayDb");
//        if (!isParamNull($logger_, $arg_sql)) {
//            throw new NullParamException("param arg_sql is null");
//        }
//        if(!is_string($arg_sql)){
//            throw new NotStringException('arg_sql is not string');
//        }
        $parseExeResult_ = self::parseNExecute($arg_conn, $arg_sql);
        if (! $parseExeResult_ ){
            throw new DBException("db parse error" . oci_error($parseExeResult_));
        }
        $fetchResult_ = oci_fetch_all($parseExeResult_, $result_, null, null, OCI_FETCHSTATEMENT_BY_ROW);
//        if (FALSE == $fetchResult_){
//            //throw new DBException("db execute error" . oci_error());
//            self::freeNClose($parseExeResult_);
//            return null;
//        }

        self::freeNClose($parseExeResult_);
        if($arg_isJson == FALSE){
            return $result_;
        }else{
            $jsonRes_ = json_encode($result_, JSON_UNESCAPED_UNICODE);
            if (!$jsonRes_) {
                throw new Exception("jsonEncode failed");
            }
            return $jsonRes_;
        }
    }



    /**
     * @static
     * @param $arg_conn
     * @param $arg_sql
     * @return array
     * @throws NullParamException
     * @throws Exception
     * @throws DBException
     */
    public static function getJsonObjectDb($arg_conn, $arg_sql)
    {
        $logger_ = Log4PHP::getLogger("Fan_getJsonArrayDb");
//        if (!isParamNull($logger_, $arg_conn)) {
//            throw new NullParamException("param arg_conn is null");
//        }
//        if (!isParamNull($logger_, $arg_sql)) {
//            throw new NullParamException("param arg_sql is null");
//        }
        $parseExeResult_ = self::parseNExecute($arg_conn, $arg_sql);
        if (!$parseExeResult_) {
            throw new DBException("db parse error" . oci_error($parseExeResult_));
        }
        $fetchResult_ = oci_fetch_assoc($parseExeResult_);
        if (FALSE == $fetchResult_) {
            throw new DBException("db execute error" . oci_error());
        }
//        $jsonArray_ = json_encode($fetchResult_);
//        if (!$jsonArray_) {
//            throw new Exception("jsonEncode failed");
//        }
        self::freeNClose($parseExeResult_);
        return $fetchResult_;
    }

    /**
     * @static
     * @param $arg_conn
     * @param $arg_sql
     * @param bool $arg_isTest
     * @return resource
     * @throws NullParamException
     * @throws DBException
     */
    public static function parseNExecute($arg_conn, $arg_sql, $arg_isTest = FALSE)
    {

        $logger_ = Log4PHP::getLogger("Fan_parseExecute");
//        if (!isParamNull($logger_, $arg_sql)) {
//            throw new NullParamException("param {$arg_sql} is null");
//        }

        $parseResult_ = oci_parse($arg_conn, $arg_sql);

        if (!$parseResult_) {
            throw new DBException("db parse error");
        }

        $exeResultSingle_ = oci_execute($parseResult_, OCI_COMMIT_ON_SUCCESS);

        if (!$exeResultSingle_) {
            $logger_->error("oci execute error");
            oci_rollback($arg_conn);
        }
        return $parseResult_;
    }


    /**
     * @static
     * @param $arg_parseResult
     * @param bool $arg_isTest
     * @return bool|void
     * @throws NullParamException
     */
    public static function freeNClose($arg_parseResult, $arg_isTest = FALSE)
    {
        $logger_ = Log4PHP::getLogger("Fan_parseExecute");
//        if ((!isParamNull($logger_, $arg_parseResult)) || $arg_isTest == 'isTest') {
//            throw new NullParamException("param {$arg_parseResult} is null");
//        }

        $freeResult_ = oci_free_statement($arg_parseResult);
        if (!$freeResult_ || $arg_isTest == 'isTest') {
            //  $logger_->error("freeResult failed");
            return false;
        }
        //$logger_->info("freeNClose DB success");
        // return self::closeDbConnection();
    }

    /**
     * bind params to sql
     * @param $arg_connResult
     * @param $arg_bindName
     * @param $arg_bindValue
     * @return bool
     */
    public static function ociBinding($arg_connResult, $arg_bindName, $arg_bindValue)
    {
        $logger_ = Log4PHP::getLogger("Fan_SaveODetail_ociBinding");
        isParamNull($logger_, $arg_connResult);
        isParamNull($logger_, $arg_bindName);
        isParamNull($logger_, $arg_bindValue);
        if (!$arg_connResult) {
            $logger_->error("$arg_connResult is null");
        }

        $bindResult = oci_bind_by_name($arg_connResult, $arg_bindName, $arg_bindValue);
        if (!$bindResult) {
            $logger_->error("ociBinding failed with bindName: " . $arg_bindName . ", bindValue: " . $arg_bindValue);
            return false;
        } else return true;
    }

    /**
     * @static
     * @param bool $arg_isTest
     * @param $arg_tableName
     * @param $arg_sqName
     * @param $arg_columnStr
     * @param $arg_insertValueStr
     * @return bool
     */
    public static function multiInsert($conn_, $arg_isTest = FALSE, $arg_tableName, $arg_sqName, $arg_columnStr, $arg_insertValueStr)
    {
        $result_ = TRUE;
        $logger_ = Log4PHP::getLogger("Fan_multiInsert");
        $logger_->info('isTest: ' . $arg_isTest);

//        $logger_->info('table name: ' . $arg_tableName);
//        $logger_->info('sequence name: ' . $arg_sqName);
//        $logger_->info('column array: ');
//        $logger_->info($arg_columnStr);
//        $logger_->info('insertValueArray:');
//        $logger_->info($arg_insertValueStr);


        if (!isParamNull($logger_, $arg_tableName) || !isParamNull($logger_, $arg_sqName)
            || !isParamNull($logger_, $arg_columnStr) || !isParamNull($logger_, $arg_tableName)
            || !isParamNull($logger_, $arg_insertValueStr)
        ) return false;


        $arg_insertValueStr = str_replace(chr(13), "", $arg_insertValueStr);
        $arg_insertValueStr = str_replace("\n", "", $arg_insertValueStr);
        $arg_insertValueStr = trim($arg_insertValueStr);
//        $insertValueArray_ = preg_replace("/s+([rn$])/", "\1", $insertValueArray_);
        $arg_insertValueStr = str_replace(' ', '', $arg_insertValueStr);
        //  $arg_insertValueStr = "'". $arg_insertValueStr;
//        $arg_insertValueStr .="'";
        $insertValueArray_ = explode(';', $arg_insertValueStr);
        // $insertValueArray_ = str_replace(',', "','",$insertValueArray_);
        //$insertValueArray_ = str_replace("'null'", "null",$insertValueArray_);

        //$logger_->info($insertValueArray_);
        $insertValueArrayCount_ = count($insertValueArray_);
        // $logger_->info('insertValueArrayCount' . $insertValueArrayCount_);

        for ($x = 0; $x < $insertValueArrayCount_; $x++) {
            // $insertValueArray_[$x] = "'".$insertValueArray_[$x];
            // $insertValueArray_[$x] = str_replace("''", "'", $insertValueArray_[$x]);
            $insertSql_ = "insert into $arg_tableName($arg_columnStr) values($arg_sqName.NEXTVAL," . "$insertValueArray_[$x])";
            //  $logger_->info($insertSql_);
            $connResult_ = oci_parse($conn_, $insertSql_);
            $exeResultSingle_ = oci_execute($connResult_, OCI_COMMIT_ON_SUCCESS);
            if (!$exeResultSingle_) {
                $logger_->error("oci execute error");
                oci_rollback($conn_);
            }

            //    $logger_->info('exeResult: ' . $exeResultSingle_);
            if (!$exeResultSingle_) $result_ = FALSE;

        }
        return $result_;

    }

    /**
     * @static
     * @param $conn_
     * @param $arg_table
     * @param bool $arg_isTest
     * @return bool
     */
    public static function truncateTable($conn_, $arg_table, $arg_isTest = FALSE)
    {
        $result_ = TRUE;
        //   $logger_ = Log4PHP::getLogger("Fan_multiInsert");
        //   if(!isParamNull($logger_, $arg_table)) return false;
        $truncateSql_ = "truncate TABLE $arg_table";
        $connResult_ = oci_parse($conn_, $truncateSql_);
        $exeResultSingle_ = oci_execute($connResult_, OCI_COMMIT_ON_SUCCESS);
        if (!$exeResultSingle_) {
            //     $logger_->error("oci execute error");
            oci_rollback($conn_);
        }

        //    $logger_->info('exeResult: ' . $exeResultSingle_);
        if (!$exeResultSingle_) $result_ = FALSE;
        return $result_;
    }

}
