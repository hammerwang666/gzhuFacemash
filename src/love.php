<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fan
 * Date: 13-3-20
 * Time: 下午12:04
 * To change this template use File | Settings | File Templates.
 */

include_once(dirname(__FILE__) . "/getPhotoHandler.php");
require_once'../lib/Log4PHP/Log4PHP.php';

$conn_ = oci_connect("LYPAY", "LYPAY", "//202.192.18.39/kdb", "UTF8");
if(isset($_GET['userName']) && isset($_GET['love'])){
    $userName = $_GET['userName'];
    $love = $_GET['love'];
    $result = getPhotoHandler::loveIt($conn_, $userName, $love);
    if($result == 1){
        $echoJson['isSuccess']  =true;
        $echoJson['msg']  =$result;
        echo (json_encode($echoJson, JSON_UNESCAPED_UNICODE));
        exit;
    }
} else{
    $echoJson['isSuccess']  =false;
    $echoJson['msg']  ='user is null';
    echo (json_encode($echoJson, JSON_UNESCAPED_UNICODE));
    exit;
}


