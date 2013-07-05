<?php
/**
 * Include needing class.
 * check all directors in 'application' and include class.php
 */
class Autoload
{
    private static $instance;
    private $cache;

    /**
     * Initialize the autoload class
     *
     * @return Autoload
     */
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Put the custom functions in the autoload register when the class is initialized
     */
    function __construct()
    {
        $this->cache = $this->getAllDirectoriesRecursive(SITE_PATH . 'application');
        spl_autoload_register(array($this, 'load'));
        require_once SITE_PATH . 'vendor' . DIRSEP . 'autoload.php';
    }

    /**
     * Load controller, model, logic
     *
     * @param string $className class name
     *
     * @return bool
     */
    private function load($className)
    {
        $filename = strtolower($className) . '.php';
        foreach($this->cache as $dir) {
            if($this->includeFile($dir . DIRSEP . $filename)){
                return true;
            }
        }
        return false;
    }

    private function getAllDirectoriesRecursive($path)
    {
        $directories = array();
        $currentDirFiles = glob($path . '/*.php');
        if (!empty($currentDirFiles)) {
            $directories[] = $path;
        }
        foreach (glob($path . '/*', GLOB_ONLYDIR) as $subDir) {
            $directories = array_merge($directories, $this->getAllDirectoriesRecursive($subDir));
        }

        return $directories;
    }

    private function includeFile($file )
    {
        if (is_file($file)) {
            include_once($file);
            return true;
        } else {
            return false;
        }
    }
}
