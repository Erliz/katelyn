<?php
Class Display
{
    /** @var Twig_Environment */
    public $twig;
    public $assign = array();

    function init($style)
    {
        if ($style == null) {
            $style = 'default';
        }
        $cfg = array(
            'cache' => ConfigPath::$real . ConfigPath::$cacheTwig,
            'auto_reload' => true,
            'strict_variables' => false,
            'debug' => Registry::$debug
        );
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(sprintf(
            ConfigPath::$real . ConfigPath::$twigTpl,
            $style
        )), $cfg);
        $this->includeFilters();
        if(Registry::$debug){
            $this->twig->addExtension(new Twig_Extensions_Extension_Debug());
        }
    }

    public function assign($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $val) {
                $this->assign[$k] = $val;
            }
        }
    }

    public function disp($tpl = 'index')
    {
        $this->init($this->assign['path']['style']);
        $template = $this->twig->loadTemplate($tpl . '.twig');
        $template->display($this->assign);
        exit;
    }

    private function includeFilters()
    {
        $chunkFilter = new Twig_SimpleFilter('chunk', function ($array, $size) {
            return array_chunk($array, $size);
        });
        $this->twig->addFilter($chunkFilter);
    }
}
