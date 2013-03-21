<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 9/8/12
 * Time: 2:24 PM
 * To change this template use File | Settings | File Templates.
 */
include_once "/../../lib/Log4PHP/Log4PHP.php";

/*
 * 调用数据库持久连接
 */
const G_USER_NAME = "LYPAY";
const G_USER_PASS = "LYPAY";
const G_DB = "//202.192.18.39/kdb";

function poolConnect($arg_uName = G_USER_NAME,$arg_uPass = G_USER_PASS,$arg_DB = G_DB,$arg_encode = "UTF8")
{
    $conDB = "";
    try{
        $conDB = @oci_connect($arg_uName,$arg_uPass,$arg_DB,$arg_encode);
    }catch (Exception $e)
    {
        throw new InvalidArgumentException($e->getMessage());
    }
    if(!$conDB)
    {
        $e = oci_error();
        Log4PHP::getLogger("poolConnect") ->error($e['message']);
        throw new InvalidArgumentException("数据库连接异常.");
    }

    return $conDB;

}