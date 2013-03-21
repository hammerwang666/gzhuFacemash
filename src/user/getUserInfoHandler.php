<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 12-10-31
 * Time: 上午10:07
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(dirname(__FILE__)) . "/common/DB.php");
include_once(dirname(dirname(__FILE__)) . "/common/common.php");
include_once(dirname(dirname(__FILE__)) . "/util/dBUtil.php");
include_once("/../../lib/log4php/Logger.php");
//class getUserInfoHandler
//{
//    public static function getUserInfo(){
$logger_ = Log4PHP::getLogger("Fanz_getUserInfo");
$logger_->info('begin');
$conn_ = oci_connect("LYPAY", "LYPAY", "//202.192.18.39/kdb", "UTF8");
$logger_->info('connected!');
if (!$conn_) {
    $m = oci_error();
    $logger_->error($m['message'] . "\n");
} else {
    $logger_->info("Connected to Oracle!");
}
//  $userSql_ = "select * from LY_V_USERINFO WHERE USERNAME='1006100012'";

//  $orgSql = "select * from LY_DM_ORGANIZATION";
$orgSql = "SELECT app_user.fullname , app_user.username , dmclass.bjmc ,
    dm_specialty.zymc ,
    xy.dmmc, xy.dm
  FROM ly_app_user app_user ,
    ly_dm_class dmclass ,
    ly_dm_specialty dm_specialty ,
    ly_dm_organization xy
  WHERE app_user.classdm = dmclass.bh
  AND dm_specialty.dm    = dmclass.zydm
  AND xy.dm              = app_user.orgdm
  AND app_user.title='2' AND app_user.classdm  != '无' AND app_user.username not like '%s%'";
$userArr_ = array();
try {
    $userArr_ = dBUtil::getJsonArrayDb($conn_, $orgSql);
//    $logger_->info(($userArr_));
} catch (Exception $e) {
    throw new DBException('db execute error');
}
if (!is_array($userArr_)) {
    throw new Exception('get User info error');
}
if (sizeof($userArr_) == 0) {
    throw new Exception('user info is null');
}
//        return $userArr_;
//
//
//    }
//
//}



$imagesURLArray = array(
    'http://172.22.21.1/photo/10061000122.jpg',
    'http://172.22.21.1/photo/1006100095.jpg'
);
$imagesURLArray = array_unique($imagesURLArray );



//foreach($userArr_ as $user) {
//    echo $user['USERNAME'];
////    echo $user['DMMC'];
//
//    $imagesURL =  'http://172.22.21.1/photo/' . $user['USERNAME'] . '.jpg';
////    $dirName = mb_convert_encoding($user['DMMC'], "gb2312", "UTF-8");
//    $dirName = iconv("UTF-8", "GBK", $user['DMMC']);
//    echo $dirName;
//    echo "\n";
//    if(!is_dir($dirName)){
//        mkdir($dirName);
//    }
//    if(@fopen($imagesURL, 'b') && !@fopen($user['DMMC'] .basename($imagesURL), 'b')){
//        file_put_contents( $dirName . '/' .basename($imagesURL), file_get_contents($imagesURL));
//    }
//
//}