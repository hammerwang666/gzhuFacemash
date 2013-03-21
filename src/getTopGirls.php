<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fan
 * Date: 13-3-20
 * Time: 下午5:14
 * To change this template use File | Settings | File Templates.
 */


include_once(dirname(__FILE__) . "/getPhotoHandler.php");
require_once'../lib/Log4PHP/Log4PHP.php';

$conn_ = oci_connect("LYPAY", "LYPAY", "//202.192.18.39/kdb", "UTF8");
$result = getPhotoHandler::getTop100Girls($conn_);

$echoJson['isSuccess']  =true;
$echoJson['photo']  =$result;
echo (json_encode($echoJson, JSON_UNESCAPED_UNICODE));
exit;