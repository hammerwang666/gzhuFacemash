<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fan
 * Date: 13-3-20
 * Time: 上午9:24
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(__FILE__) . "/util/dBUtil.php");

class getPhotoHandler
{
    public static function getPhotoByDm($arg_conn,  $arg_dmCode = null){
          if($arg_conn == null){
              throw new Exception("arg_conn is nul");
          }

        $getSql = "SELECT ROWNUM, USERNAME, FULLNAME,
         PHOTO_PATH, IDCARD, XYMC,XYDM,LOVE,HOTEL FROM T_GZHU_GIRLS WHERE 1=1 AND IS_SHOW='0' ";
        if($arg_dmCode != null && $arg_dmCode != ""){
            $getSql .= " AND XYDM='$arg_dmCode'  ORDER BY to_number(LOVE) DESC";
        }

        try {
            $pictureArr_ = dbUtil::getJsonArrayDb($arg_conn, $getSql);
        } catch (Exception $e) {
            throw $e;
        }
        return $pictureArr_;


    }

    public static function getPhotoGroup($arg_conn){
        $logger_ = Log4PHP::getLogger("Fanz_getPhotoGroup");
        if($arg_conn == null){
            throw new Exception("arg_conn is nul");
        }

        $xySql = "SELECT DISTINCT XYMC FROM T_GZHU_GIRLS";
//        $logger_->info("$xySql: ");
//        $logger_->info($xySql);
        $xyArr_ = array();
        try {
            $xyArr_ = dbUtil::getJsonArrayDb($arg_conn, $xySql);
        } catch (Exception $e) {
            throw $e;
        }

        $pictureArr_ = array();
        $returnArr_ = array();
        foreach($xyArr_ as $xy) {
            $xyName = $xy['XYMC'];
            $picSql_ = "SELECT * FROM T_GZHU_GIRLS WHERE XYMC='$xyName'";
         //   $picSql_ = "SELECT PHOTO_PATH FROM T_GZHU_GIRLS WHERE XYMC='新闻与传播学院'";
            try {
                $pictureArr_ = dbUtil::getJsonArrayDb($arg_conn, $picSql_);
            } catch (Exception $e) {
                throw $e;
            }
            array_push($returnArr_, $pictureArr_);
        }


        return $returnArr_;


    }


    public static function getXYAll($arg_conn){
        $logger_ = Log4PHP::getLogger("Fanz_getXYAll");
        if($arg_conn == null){
            throw new Exception("arg_conn is nul");
        }

        $xySql = "SELECT DISTINCT XYMC, XYDM FROM T_GZHU_GIRLS";
        try {
            $resultArr_ = dbUtil::getJsonArrayDb($arg_conn, $xySql);
        } catch (Exception $e) {
            throw $e;
        }
        return $resultArr_;

    }

    public static function loveIt($arg_conn, $arg_userName, $arg_love){
        $logger_ = Log4PHP::getLogger("Fanz_loveIt");
        if($arg_conn == null){
            throw new Exception("arg_conn is nul");
        }

        $xySql = "UPDATE T_GZHU_GIRLS SET LOVE='$arg_love' WHERE USERNAME='$arg_userName'";
        try {
            $resultArr_ = dbUtil::execSql($arg_conn, $xySql);
        } catch (Exception $e) {
            throw $e;
        }
        return $resultArr_;

}

    public static function getTop100Girls($arg_conn){
        if($arg_conn == null){
            throw new Exception("arg_conn is null");
        }
        $topSql = "SELECT rownum, USERNAME, FULLNAME, PHOTO_PATH, IDCARD, XYMC,XYDM,LOVE,HOTEL
         FROM T_GZHU_GIRLS  WHERE LOVE>'0' AND IS_SHOW='0' AND rownum  BETWEEN 0 AND 1000 ORDER BY to_number(LOVE) DESC ";

        try {
            $resultArr = dbUtil::getJsonArrayDb($arg_conn, $topSql);
        } catch (Exception $e) {
            throw $e;
        }

        return $resultArr;
    }

}
