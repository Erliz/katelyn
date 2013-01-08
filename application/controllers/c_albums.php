<?php
/**
 *
 */
class C_Albums extends C_Base
{

    public function index()
    {
        $model = new M_Album();
        Registry::$display->assign(
            array('request'=>
                  array('albums'=>$model->getAll()->getCollection())
            )
        );
        Registry::$display->disp('page/albums');
    }

    public function id($get)
    {
        $id=(int)array_shift($get);
        if(!preg_match('/[0-9]+/',$id)) $this->index();
        $page = (int)(!empty($get[0]) ? $get[0] : 0);
        /** @var $album MO_Album */
        $album=MO_Album::getById($id);
        $album->fillPhotos();

        $album->photos->renderPages();

        Registry::$display->assign(
            array('request'=>
                  array(
                      'album'=>$album,
                      'page'=>$page
                  )
            )
        );
        Registry::$display->disp('page/album');
    }
}
