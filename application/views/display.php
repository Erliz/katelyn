<?php
Class Display
{
    public $assign = Array();
    public $twig;

    function init($style)
    {
        if ($style == null) {
            $style = 'default';
        }
        $path = SITE_PATH . 'application' . DIRSEP . 'views' . DIRSEP;
        $loader = new Twig_Loader_Filesystem($path . 'tpl' . DIRSEP . $style . DIRSEP);
        $cfg['cache'] = $path . 'tpl_c';
        $cfg['auto_reload'] = true;
        $cfg['strict_variables'] = false;
        $cfg['debug'] = true;
        $this->twig = new Twig_Environment($loader, $cfg);
        $this->twig->addExtension(new Twig_Extensions_Extension_Debug());
    }

    public function assign($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $val) {
                $this->assign[$k] = $val;
            }
        }
    }

    function disp($tpl = 'index')
    {
        $this->init($this->assign['path']['style']);
        $template = $this->twig->loadTemplate($tpl . '.twig');
        $template->display($this->assign);
        exit;
    }
}
