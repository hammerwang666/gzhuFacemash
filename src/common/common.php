<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 12-10-31
 * Time: 上午10:20
 * To change this template use File | Settings | File Templates.
 */
    /**
     * @param $arg_logger
     * @param $arg_param
     * @return bool
     */
    function isParamNull($arg_logger, $arg_param){
        if(!isset($arg_param)|| $arg_param==null || !$arg_param || empty($arg_param)){
            $arg_logger->info("param " . $arg_param . "is null" );
            return false;
        }
    }

