<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 13-3-19
 * Time: 下午9:32
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

$orgSql = "select * from LY_DM_ORGANIZATION";
$orgSql = "SELECT app_user.fullname , app_user.username , dmclass.bjmc ,
    dm_specialty.zymc ,
    xy.dmmc, xy.dm ,hostel.roomnum, app_user.idcard
  FROM ly_app_user app_user ,
    ly_dm_class dmclass ,
    ly_dm_specialty dm_specialty ,
    ly_dm_organization xy,
    ly_hostel hostel
  WHERE app_user.classdm = dmclass.bh
  AND dm_specialty.dm    = dmclass.zydm
  AND xy.dm              = app_user.orgdm
  AND hostel.userid = app_user.username
  AND app_user.title='2' AND app_user.classdm  != '无' AND app_user.username not like '%s%'";
$sql = "select * from T_GZHU_GIRLS";
$userArr_ = array();
try {
    $userArr_ = dBUtil::getJsonArrayDb($conn_, $orgSql);
//    $logger_->info(($userArr_));
} catch (Exception $e) {
    throw new DBException('db execute error');
}


//$conn_ = oci_connect("ZYMS_TEST", "ZYMS_TEST", "//172.22.71.148/kdb", "UTF8");
//$logger_->info('connected!');

$userArrSize = sizeof($userArr_);

for ($x = 0; $x < $userArrSize; $x++) {
    $userName_ = $userArr_[$x]['USERNAME'];
    $photoUrl = "http://172.22.21.1/photo/" . $userName_ . ".jpg";
    $logger_->info("execute: " . $x);
    if(!@fopen($photoUrl , 'b')){
        $logger_->info("cannot find by url,continue");
        continue;
    }

    $userNameSha = sha1($userName_);
    $fullName_ = $userArr_[$x]['FULLNAME'];
    $BJMC = $userArr_[$x]['BJMC'];
    $DMMC = $userArr_[$x]['DMMC'];
    $ZYMC = $userArr_[$x]['ZYMC'];
    $DM = $userArr_[$x]['DM'];
    $hostel_ = $userArr_[$x]['ROOMNUM'];
    $idcard = $userArr_[$x]['IDCARD'];
    $picturePath = "photo/" . $DMMC . "/" . $userName_ . ".jpg";
    $picturePathEncoded = "photo/" . $DMMC . "/" . $userNameSha . ".jpg";

    $insertSql = "INSERT INTO T_GZHU_GIRLS(ID, USERNAME, FULLNAME
, IDCARD, XYMC, SEX, XYDM, ZYMC, ZYDM, HOTEL, USERNAME_ENCODED
,PHOTO_PATH, PHOTO_PATH_ENCODED, LOVE, AVG_POINTS, MK_POINT_NUM, IS_SHOW )
 VALUES(S_GZHU_GIRLS.NEXTVAL, '$userName_','$fullName_','$idcard','$DMMC','1','$DM','$ZYMC'
 ,null,'$hostel_','$userNameSha' ,'$picturePath','$picturePathEncoded',null,null,null,'0')";

//    $logger_->info("insert sql:");
//    $logger_->info($insertSql);
    try {
        $insertResult = dbUtil::execSql($conn_, $insertSql);
//        var_dump($insertResult);
//        $logger_->info("exe result:" . $insertResult);
    } catch (Exception $e) {
        $logger_->error( $x . " insert error:" . $e->getMessage());

    }
}
