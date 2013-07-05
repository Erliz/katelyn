<?php
Abstract Class C_Base
{
    protected $settings;

    function __construct()
    {
        session_name('KESES'); //Название сессии в браузере.
        session_start(); //Начало сессии.

        $this->settings = M_Initialize::init();
        Registry::$display->assign(
            Array(
                'general'  => Array(
                    'author'      => $this->settings['author'],
                    'title'       => $this->settings['title'],
                    'keywords'    => $this->settings['keywords'],
                    'description' => $this->settings['desc'],
                    'header_text' => $this->settings['header_text'],
                    'email'       => $this->settings['email']
                ),
                'path'     => Array(
                    'url'     => $this->settings['url'],
                    'style'   => 'default',
                //    'counter' => $this->settings['counter']
                )/*,
                'counters' => Array(
                    'yandex' => $this->settings['counterYandex'],
                    'google' => $this->settings['counterGoogle']
                )*/
            )
        );
    }

    public function forward($uri, $params = array())
    {
        $get = array();
        foreach ($params as $key => $var) {
            $get[] = $key . '=' . $var;
        }
        if (!empty($get)) {
            $uri .= '?' . join('&', $get);
        }
        header('Location: ' . $uri);
        exit;
    }

    abstract function index();
}
