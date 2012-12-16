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
                    'description' => $this->settings['desc']
                ),
                'path'     => Array(
                    'url'     => $this->settings['url'],
                    'style'   => 'default',
                    'counter' => $this->settings['counter']
                ),
                'counters' => Array(
                    'yandex' => $this->settings['counterYandex'],
                    'google' => $this->settings['counterGoogle']
                )
            )
        );
    }

    abstract function index();
}
