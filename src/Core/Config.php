<?php
namespace App\Core;

final class Config
{
    /**
     * @var array
     */
    private static $config;

    /**
     * @param string|null $env
     * @return void
     */
    public static function create($env = null)
    {
        if (!self::$config) {
            $configdir = __DIR__ . '/../../config/';
            $env = ($env === null) ? '' : ('-' . $env);
            $configfile = $configdir . sprintf('config%s.php', $env);
            if (!file_exists($configfile)) {
                throw new \Exception('Unable to find config file -> ' . $configfile);
            }
            self::$config = require $configfile;
            $commonConfig = require $configdir . 'config-common.php';
            self::$config = array_merge(self::$config, $commonConfig);
        }
    }

    /**
     * @param string|null $key
     * @return mixed
     */ 
    public static function get($key = null)
    {
        if ($key === null) return self::$config;
        if (!isset(self::$config[$key])) return null;
        return self::$config[$key];
    }
}
