<?php
class ConfigPath
{
    public static $real;
    public static $url;
    public static $script;
    public static $application = 'application/';
    public static $cacheTwig   = 'application/cache/twig';
    public static $twigTpl     = 'application/views/tpl/%s/';
    public static $cache       = 'shared/cache/';
    public static $files       = 'shared/files/';
    public static $photo       = 'shared/files/photo/';
    public static $photoTbn    = 'shared/files/photo/thumbnail/';
    public static $photoTmp    = 'shared/files/temp/';
    public static $photoTmpTbn = 'shared/files/temp/thumbnail/';
    public static $logs        = 'shared/logs/';
    public static $web         = 'web/';
    // public settings
    public static $publicDomain = 'katelyn.ru';
    public static $publicPort   = '80';
    public static $publicHost;

    public static function init()
    {
        ConfigServ::init();
        self::$real = SITE_PATH;
        if (isset($_SERVER['SERVER_ADDR'])) {
            self::$url = 'http://' . $_SERVER['HTTP_HOST'] . '/';
            self::$script = $_SERVER['SCRIPT_FILENAME'];
        } elseif (PHP_SAPI === 'cli') {
            self::$url = 'http://' . ConfigServ::$server->ipLocal . '/';
            self::$script = $_SERVER['SCRIPT_FILENAME'];
        }

        self::$publicHost = 'http://' . self::$publicDomain . ':' . (!empty(self::$publicPort) ? self::$publicPort : '80') . '/';
    }
}
