<?php
namespace App\Core;

class Application
{    
    /**
     * @var string
     */   
    private $basePath = '';
    /**
     * @var string
     */
    private $defaultController = 'Index';

    /**
     * @param string $basePath ex. 'mybasepath/'
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param string $defaultController
     * @return void
     */
    public function setDefaultController($defaultController)
    {
        $this->defaultController = $defaultController;
    }
    
    /**
     * @param string|null $path
     * @return void
     */
    public function run($path = null)
    {
        if ($path === null) $path = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($path, '?')) {
            $path = substr($path, 0, $pos);
        }
        $path = rawurldecode($path);
        $path = trim($path, '/');

        if (!empty($this->basePath) && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
    
        $parray = explode('/', $path);
    
        foreach ($parray as $i => $p) {
            $controller = implode('\\', array_map('ucfirst', array_slice($parray, 0, $i+1)));
            if ($controller === '') $controller = $this->defaultController;
            $namespace = 'App\\Application\\Controllers\\' . $controller;
            if (class_exists($namespace)) {
                $class = new $namespace();
                $args = array_slice($parray, $i+1);
                break;
            }
    
            if ($i === count($parray)-1) {
                http_response_code(404);
                exit;
            }
        }
    
        $class->main($controller, $args);      
    }

    /**
     * @param array $argv
     * @return void
     */
    public function runCmd($argv)
    {
        if (!isset($argv[1])) {
            throw new \Exception('Unable to find command name');
        }
        
        $conmmand = ucwords(str_replace('/', '\\', $argv[1]), '\\');
        $namespace = 'App\\Application\\Commands\\' . $conmmand;
        if (!class_exists($namespace)) {
            throw new \Exception('Unable to load command class ->' . $namespace);
        }
        
        $class = new $namespace();
        $class->main($argv);
    }
}
