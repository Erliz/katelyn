<?php
Class M_Initialize
{
    public static function init()
    {
        $result = Registry::$db->query('SELECT * FROM `settings`');
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['name']] = $row['value'];
        }
        $settings['url'] = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

        return $settings;
    }
}

?>