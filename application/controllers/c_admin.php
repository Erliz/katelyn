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

    public function edit($get)
    {
        if(!is_array($get) || count($get)==0){
            $this->index();
        }
        $action = array_shift($get);
        switch($action){
            case 'albums': $this->albums(); break;
            case 'album': $this->album($get); break;
        }
    }

    private function albums()
    {
        $model=new M_Album();
        Registry::$display->assign(
            Array('albums'=>$model->getAll())
        );
        Registry::$display->disp($this->twigFolder . 'albums');
    }

    private function album($get)
    {
        $id = array_shift($get);
        $model=new M_Album();
        $album=$model->getById($id);
        if(!empty($_POST['title']) /*&& !empty($_POST['description'])*/){
            $title=trim(strip_tags($_POST['title']));
            $description=trim(strip_tags($_POST['description']));
            $album->setTitle($title);
            $album->setDescription($description);
            if(!empty($_POST['cover'])){
                if(preg_match('/([0-9]+)/',$_POST['cover'],$match)){
                    $album->setCover($match[1]);
                }
            }
            $album->saveToBase();
        }
        $album->fillPhotos();

        Registry::$display->assign(
            Array('album'=>$album)
        );
        Registry::$display->disp($this->twigFolder . 'album');
    }
}
