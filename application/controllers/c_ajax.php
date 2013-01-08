<?php
/**
 * Контроллер отвечающий за вывод ajax сообщений
 */
class C_Ajax extends C_Base
{
    function index()
    {
        echo json_encode(false);
        exit;
    }
}