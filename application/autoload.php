<?php
/**
 * Include needing class.
 * check all directors in 'application' and include class.php
 */
class Autoload
{
    private static $instance;
    private $dirsCache;

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
        $this->dirsCache = $this->getAllDirectoriesRecursive(SITE_PATH . 'application');
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
        foreach($this->dirsCache as $dir) {
            if($this->includeFile($dir . DIRSEP . $filename)){
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $path
     *
     * @return string[]
     */
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
        $directories = $this->filterDirectories($directories);

        return $directories;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    private function includeFile($file)
    {
        if (is_file($file)) {
            include_once($file);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string[] $directories
     *
     * @return string[]
     */
    private function filterDirectories(array $directories)
    {
        foreach($directories as $key => $dir) {
            if(strpos($dir, 'cache/twig') !== false){
                unset($directories[$key]);
            }
        }

        return $directories;
    }
}
