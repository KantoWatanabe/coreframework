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
     * @param string $key1
     * @param string|null $key2
     * @return mixed
     */ 
    public static function get($key1, $key2 = null)
    {
        if (!isset(self::$config[$key1])) return null;
        if ($key2 === null) return self::$config[$key1];
        if (!isset(self::$config[$key1][$key2])) return null;
        return self::$config[$key1][$key2];
    }
}
