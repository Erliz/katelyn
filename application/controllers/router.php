<?php
Class Router
{
    private $path;
    private $args = array();

    function __construct($path)
    {
        $path .= DIRSEP;
        if (!is_dir($path)) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        $this->path = $path;
    }

    function delegate()
    {
        // Анализируем путь
        $this->getController($file, $controller, $action, $args);
        // Файл доступен?
        if (!is_readable($file)) {
            Registry::$display->disp('404');
        }
        // Подключаем файл
        include ($file);
        // Создаём экземпляр контроллера
        $class = 'C_' . $controller;
        $controller = new $class();
        // Действие доступно?
        if (!is_callable(array($controller, $action))) {
            Registry::$display->disp('404');
        }
        // Выполняем действие        
        $controller->$action($args);
    }

    private function getController(&$file, &$controller, &$action, &$args)
    {
        $route = (empty($_GET['app'])) ? 'index' : $_GET['app'];

        // Получаем раздельные части
        $route = trim($route, '/\\');
        $parts = explode('/', $route);

        // Находим правильный контроллер
        $cmd_path = $this->path;
        foreach ($parts as $part) {
            $fullpath = $cmd_path . 'c_' . $part;
            // Есть ли папка с таким путём?
            if (is_dir($fullpath)) {
                $cmd_path .= $part . DIRSEP;
                array_shift($parts);
                continue;
            }
            // Находим файл
            if (is_file($fullpath . '.php')) {
                $controller = $part;
                array_shift($parts);
                break;
            }
        }
        if (empty($controller)) {
            $controller = 'index';
        }
        // Получаем действие
        $action = array_shift($parts);

        if (empty($action)) {
            $action = 'index';
        }
        $file = $cmd_path . 'c_' . $controller . '.php';
        $args = $parts;
    }
}

?>