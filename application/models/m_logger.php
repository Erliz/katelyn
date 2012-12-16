<?php
/**
 * User: Elio
 * Date: 15.09.12
 * format 127.0.0.1 frank [10/Oct/2000:13:55:36 -0700] "GET /apache_pb.gif HTTP/1.0" 200 2326
 */
class M_Logger
{
    static private $serverName;
    static private $serverIp;
    static private $logRoot;
    static private $timeFormat;
    private $fileName;
    private $logPath;

    static function init()
    {
        self::$serverName = ConfigServ::$server->name;
        self::$serverIp = ConfigServ::$server->ipLocal;
        self::$logRoot = ConfigPath::$real . ConfigPath::$logs_core;
        self::$timeFormat = 'd/M/Y:H:i:s';
    }

    function __construct($file)
    {
        $this->fileName = $file . '_log';
        $this->logPath = self::$logRoot . $file;
    }

    public function log($text, $code = 0)
    {
        if (!is_scalar($text)) {
            $text = 'JSON: ' . json_encode($text);
        }
        $logRow = self::$serverIp . ' ' . self::$serverName . ' [' . date(self::$timeFormat) . '] "' . htmlentities(
            $text
        ) . '" ' . $code;
        file_put_contents($this->logPath, $logRow, FILE_APPEND | LOCK_EX);

        return true;
    }

    static function echer($data, $title = 'TEMP')
    {
        if (Registry::$debug) {
            echo '<br/><strong style="color:green;">#' . strtoupper(
                $title
            ) . '#</strong><br/><pre style="color:brown; background-color: #EEE; word-wrap: break-word;">';
            var_dump($data);
            echo "</pre>";
        }
    }
}
