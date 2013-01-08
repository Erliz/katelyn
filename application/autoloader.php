<?php
/**
 * Include needing class.
 * check all directors in 'application' and include class.php
 */
Class Autoloader
{
    public static $instance;

    /* initialize the autoloader class */
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /* put the custom functions in the autoload register when the class is initialized */
    function __construct()
    {
        spl_autoload_register(array($this, 'load'));
        spl_autoload_register(array($this, 'load_twig'));
        spl_autoload_register(array($this, 'load_vendor'));
    }

    /**
     * load controller
     *
     * @param $class string class name
     *
     * @return bool
     */
    private function load($class)
    {
        $filename = strtolower($class) . '.php';
        if ($filename[0] . $filename[1] == "mo") {
            if ($this->load_object($filename)) {
                return true;
            }
        }
        foreach (glob('application' . DIRSEP . '*', GLOB_ONLYDIR) as $dir) {
            if (is_file($file = $dir . DIRSEP . $filename)) {
                include_once ($file);

                return true;
            }
        }

        return false;
    }

    private function load_twig($class)
    {
        if (0 !== strpos($class, 'Twig')) {
            return false;
        }
        $dir = 'application' . DIRSEP . 'views' . DIRSEP;
        if (is_file($file = $dir . str_replace(array('_', "\0"), array('/', ''), $class) . '.php')) {
            include_once ($file);

            return true;
        }
        throw new Exception('Not find class:' . $class . ', on path:' . $file);
    }

    private function load_vendor($class)
    {
        $filename = $class . '.php';
        $dir = 'application' . DIRSEP . 'vendors' . DIRSEP;
        if (is_file($file = $dir . $filename)) {
            include_once ($file);

            return true;
        }

        return false;
    }

    private function load_object($filename)
    {
        if (is_file($file = 'application' . DIRSEP . 'models' . DIRSEP . 'objects' . DIRSEP . $filename)) {
            include_once ($file);

            return true;
        } else {
            return false;
        }
    }
}
