<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fan
 * Date: 13-3-20
 * Time: 上午9:33
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(dirname(__FILE__)) . "/getPhotoHandler.php");
require_once'../../lib/Log4PHP/Log4PHP.php';

class testGetPhoto  extends PHPUnit_Framework_TestCase
{

    function testGetPhotoByGroup(){
       $logger_ = Log4PHP::getLogger("Fanz_getUserInfo");
       // $logger_->info('begin');
        $conn_ = oci_connect("LYPAY", "LYPAY", "//202.192.18.39/kdb", "UTF8");
      //  $logger_->info('connected!');
       $result = getPhotoHandler::getPhotoGroup($conn_) ;
        $logger_->info($result);
//        file_put_contents("photoArray.txt" , (String)$result);
        var_dump($result);
    }

}
