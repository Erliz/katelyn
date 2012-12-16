<?php
class ConfigPath
{
    public static $real;
    public static $url;
    public static $script;
    public static $application = 'application/';
    public static $core_cache = 'application/logs/cache/';
    public static $logs_core = 'application/logs/';
    public static $logic = 'application/logic/';
    // public settings
    public static $publicDomain = 'katelyn.ru';
    public static $publicPort = '80';
    public static $publicHost;
    public static $publicParserStat = 'automate/parser/stat/';
    public static $publicParserReesult = 'automate/parser/result/';

    public static function init()
    {
        ConfigServ::init();
        if (isset($_SERVER['SERVER_ADDR'])) {
            self::$real = $_SERVER['DOCUMENT_ROOT'] . '/';
            self::$url = 'http://' . $_SERVER['HTTP_HOST'] . '/';
            self::$script = $_SERVER['SCRIPT_FILENAME'];
        } elseif (PHP_SAPI === 'cli') {
            self::$real = ConfigServ::$server->dirRoot;
            self::$url = 'http://' . ConfigServ::$server->ipLocal . '/';
            self::$script = $_SERVER['SCRIPT_FILENAME'];
        }

        self::$publicHost = 'http://' . self::$publicDomain . ':' . (!empty(self::$publicPort) ? self::$publicPort : '80') . '/';
    }
}
