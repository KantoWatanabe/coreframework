<?php
namespace App\Core;

final class Log
{
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARN = 'WARN';
    const ERROR = 'ERROR';

    /**
     * @var string
     */
    private static $log_filename = 'app';

    /**
     * @param string
     * @return void
     */
    public static function init($log_filename)
    {
        self::$log_filename = $log_filename;
    }

    /**
     * @param string $msg
     * @param mixed
     * @return void
     */
    public static function debug($msg, $obj = null)
    {
        self::write(self::DEBUG, $msg, $obj);
    }

    /**
     * @param string $msg
     * @param mixed
     * @return void
     */
    public static function info($msg, $obj = null)
    {
        self::write(self::INFO, $msg, $obj);
    }

    /**
     * @param string $msg
     * @param mixed
     * @return void
     */
    public static function warn($msg, $obj = null)
    {
        self::write(self::WARN, $msg, $obj);
    }

    /**
     * @param string $msg
     * @param mixed
     * @return void
     */
    public static function error($msg, $obj = null)
    {
        self::write(self::ERROR, $msg, $obj);
    }

    /**
     * @param string $log_level
     * @param string $msg
     * @param mixed
     * @return void
     */
    private static function write($log_level, $msg, $obj)
    {
        $logfile = __DIR__ . '/../../logs/' . self::$log_filename . '-' . date("Y-m-d") . '.log';
        $msg .= PHP_EOL;
        if ($obj !== null) {
            ob_start();
            var_dump($obj);
            $msg .= ob_get_contents();
            ob_end_clean();
        }
        $tarray = explode('.', microtime(true));
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $log = sprintf('[%s.%s][%s][%s][%s::%s]%s', date('Y-m-d H:i:s', $tarray[0]), $tarray[1], getmypid(), $log_level, $trace[2]['class'], $trace[2]['function'], $msg);
        //$log = sprintf('[%s.%s][%s][%s]%s', date('Y-m-d H:i:s', $tarray[0]), $tarray[1], getmypid(), $log_level, $msg);
        file_put_contents($logfile, $log, FILE_APPEND);
    }
}
