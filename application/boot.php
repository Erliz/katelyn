<?php
include_once("autoload.php");
Autoload::init();
ConfigPath::init();
M_Logger::init();
if (isset($_GET['debug'])) {
    Registry::$debug = true;
}
try {
    Registry::$db = new PDO('mysql:host=' . ConfigDb::$host . ';dbname=' . ConfigDb::$dbname, ConfigDb::$login, ConfigDb::$passwd);
} catch (PDOException $e) {
    echo "Mysql Connection Fail!";
    exit;
}
Registry::$db->exec('SET CHARACTER SET ' . ConfigDb::$charset);
Registry::$db->exec('SET NAMES ' . ConfigDb::$charset);
Registry::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
Registry::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO :: FETCH_ASSOC);

Registry::$display = new Display();
Registry::$router = new Router(SITE_PATH . 'application' . DIRSEP . 'controllers');
Registry::$router->delegate();
