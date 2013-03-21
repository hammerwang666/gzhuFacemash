<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 12-10-31
 * Time: 上午10:11
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(dirname(__FILE__)) . '/getUserInfoHandler.php');
include_once("/../../../lib/log4php/Logger.php");
class testGetUserInfo extends PHPUnit_Framework_TestCase
{
    public function testGetUserInfoHandler(){
        try {
            $result_ = getUserInfoHandler::getUserInfo();
            var_dump($result_) ;
        } catch (Exception $e) {

        }

    }

}
