<?php

include_once 'Logger.php';
/**
 * 
 * @author Administrator
 *
 */
//echo dirname(__FILE__)."/CTWS.log";
//exit;
class Log4PHP{
    /**
     *
     * @param string Name of the PhpFile
     * @return Logger Case
     */
    public static function getLogger($class)
   {
        Logger::configure(array (
            'appenders' =>
            array (
                'myConsoleAppender' =>
                array (
                    'class' => 'LoggerAppenderConsole',
                    'layout' =>
                    array (
                        'class' => 'LoggerLayoutTTCC'
//                        'params'=>array(
//                            'ConversionPattern'=>"%d{ISO8601} [%p] %c : %m  (at %F line %L) %n"
//                        )
                    ),
                ),
                'myFileAppender' =>
                array (
                    'class' => 'LoggerAppenderFile',
                    'layout' =>
                    array (
                        'class' => 'LoggerLayoutTTCC',
                    ),
                    'params' =>
                    array (
//                        'file' => 'D:/temp/CTWS.log',
                        'file'=>dirname(__FILE__).'/log.log' ,
                    ),
                ),
            ),
            'loggers' =>
            array (
            ),
            'renderers' =>
            array (
            ),
            'rootLogger' =>
            array (
                'level' => 'INFO',
                'appenders' =>
                array (
                    0 => 'myConsoleAppender',
                    1 => 'myFileAppender',
                ),
            ),
        ));
        $logger = Logger::getLogger($class);
        return $logger;
   }

}
