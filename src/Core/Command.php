<?php
namespace App\Core;

use App\Core\Log;

abstract class Command
{
    /**
     * @var array
     */
    protected $args = [];
    /**
     * @var array
     */
    protected $opts = [];

    /**
     * @return void
     */
    public function __construct()
    {
        Log::init($this->commandName());
    }

    /**
     * @return void
     */
    abstract protected function exec();
    
    /**
     * @var array $argv
     * @return void
     */
    public function main($argv)
    {

        foreach ($argv as $key => $value) {
            if ($key > 1 && isset($value)) {
                if (preg_match('/^--[a-zA-Z0-9]+=[a-zA-Z0-9]+$/', $value)) {
                    $params = explode('=', $value);
                    $name = str_replace('--', '', $params[0]);
                    $this->opts[$name] = $params[1];
                } else {
                    $this->args[] = $value;
                }
            }
        }

        $lockfile = __DIR__ . '/../../logs/' . $this->commandName() . '.lock';
        if (file_exists($lockfile)) {
            Log::error('Process is running!');
            exit(1);
        }
        touch($lockfile);

        Log::info('[START]');
        try {
            $this->exec();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->handleError($e);
        }
        Log::info('[END]');

        unlink($lockfile);
    }

    /**
     * @return string
     */
    protected function commandName()
    {
        $namespace = explode('\\', get_class($this));
        return lcfirst(end($namespace));
    }

    /**
     * @param Exception $e
     * @return void
     */
    protected function handleError($e)
    {
        // Override if necessary
    }

    // Command Parameter
    //
    
    /**
     * @param string $key
     * @return string
     */
    protected function getArg($key)
    {
        if (!isset($this->args[$key])) return null;
        return $this->args[$key];
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getOpt($key)
    {
        if (!isset($this->opts[$key])) return null;
        return $this->opts[$key];    
    }
}
