<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 15.09.12
 */
class M_Logger
{
    static private $serverName;
    static private $serverIp;
    static private $logRoot;
    static private $timeFormat;
    private $fileName;
    private $logPath;

    function __construct($file)
    {
        $this->fileName = $file . '.log';
        $this->logPath = self::$logRoot . $this->fileName;
    }

    public static function init()
    {
        self::$serverName = ConfigServ::$server->name;
        self::$serverIp = ConfigServ::$server->ipLocal;
        self::$logRoot = ConfigPath::$real . ConfigPath::$logs;
        self::$timeFormat = 'd/M/Y:H:i:s';
    }

    /**
     * @param string $text
     * @param int    $levelCode
     *
     * @throws E_Fatal
     * @return bool
     */
    public function log($text, $levelCode = 100)
    {
        if (!is_scalar($text)) {
            $text = 'JSON: ' . json_encode($text);
        }
        switch ($levelCode){
            case 100 : $levelText = 'DEBUG'; break;
            case 200 : $levelText = 'INFO'; break;
            case 250 : $levelText = 'NOTICE'; break;
            case 300 : $levelText = 'WARNING'; break;
            case 400 : $levelText = 'ERROR'; break;
            case 500 : $levelText = 'CRITICAL'; break;
            case 550 : $levelText = 'ALERT'; break;
            case 600 : $levelText = 'EMERGENCY'; break;
            default:
                throw new E_Fatal('No log level:' . $levelCode);
        }
        $logRow = self::$serverIp . ' ' . self::$serverName . ' [' . date(self::$timeFormat) . '] '.$levelText.': "' .
            $text . '" ' . "\n";
        file_put_contents($this->logPath, $logRow, FILE_APPEND | LOCK_EX);

        return true;
    }

    public static function echer($data, $title = 'TEMP')
    {
        if (Registry::$debug) {
            self::output($data, $title);
        }
    }

    public static function output($data, $title = 'TEMP'){
        if(PHP_SAPI === 'cli'){
            if(is_scalar($data)){
                echo "Logger: $title - $data\n";
            } else {
                echo "Logger: $title - size ".count($data)."\n";
                var_dump($data);
            }
        } else {
            echo '<br/><strong style="color:green;">#' . strtoupper(
                    $title
                ) . '#</strong><br/><pre style="color:brown; background-color: #EEE; word-wrap: break-word;">';
            var_dump($data);
            echo "</pre>";
        }
    }
}
