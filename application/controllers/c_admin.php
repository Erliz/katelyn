<?php
/**
 * User: Elio
 * Date: 23.12.12
 *
 */
class C_Admin extends C_Base
{
    protected $twigFolder = 'admin/';

    function __construct()
    {
        parent::__construct();
        if (!empty($_POST['passwd'])) {
            $phrase = explode('_', trim(strip_tags($_POST['passwd'])));
            $public = md5($phrase[0]) . $phrase[1];
            $private = $this->settings['passwd'] . date('dH', time());
            if ($public == $private) {
                $_SESSION['admin_key'] = $this->settings['admin_key'];
            }
        }
        if (empty($_SESSION['admin_key']) || $_SESSION['admin_key'] != $this->settings['admin_key']) {
            Registry::$display->disp($this->twigFolder . 'signin');
        }
    }

    public function index()
    {
        Registry::$display->disp($this->twigFolder . 'index');
    }

    public function upload()
    {
        $model=new M_Album();
        Registry::$display->assign(
            Array('albums'=>$model->getAll())
        );
        Registry::$display->disp($this->twigFolder . 'upload');
    }
}
