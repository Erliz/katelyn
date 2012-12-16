<?php
error_reporting(7);
if(isset($_GET['debug']))error_reporting (E_ALL);
//set_time_limit(3600);
if (version_compare(phpversion(), '5.1.0', '<') == true) { die ('PHP5.1 And Over'); }
// Константы:
define ('DIRSEP', DIRECTORY_SEPARATOR);
// Узнаём путь до файлов сайта
$site_path = realpath(dirname(__FILE__) . DIRSEP);
define ('SITE_PATH', $site_path.DIRSEP);
include_once (SITE_PATH.'application'.DIRSEP.'boot.php');
exit;
?>