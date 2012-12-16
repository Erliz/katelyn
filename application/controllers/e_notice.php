<?php
/**
 *
 */
class E_Notice extends Exception
{

    function __construct($e)
    {
        M_Logger::echer(debug_backtrace(), $e);
        Registry::$display->assign(Array('error' => $e));
    }
}

?>