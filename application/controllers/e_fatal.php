<?php
/**
 * Класс наследник Исключентий, призванный рассылать fatal ошибки на почту
 */
class E_Fatal extends Exception
{

    function __construct($e)
    {
        $trace = print_r(debug_backtrace(), true);
        if (Registry::$debug) {
            M_Logger::echer($trace, $e);
        }
        //else M_Message::send(1, $e, $trace);
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit;
    }
}

?>