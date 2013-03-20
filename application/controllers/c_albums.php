<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 09.01.13
 */
class C_Albums extends C_Base
{

    public function index()
    {
        $page = 1;
        if (!empty($_GET['page']) && $_GET['page'] > 0 && preg_match('/[0-9]+/', $_GET['page'])) {
            $page = strip_tags($_GET['page']);
        }
        $model = new M_Album();
        Registry::$display->assign(
            array(
                'request' => array(
                    'albums'    => $model->getPage($page)->getCollection(),
                    'page'      => $page,
                    'pageCount' => $model->getPagesCount()
                )
            )
        );
        Registry::$display->disp('page/albums');
    }

    public function id($get)
    {
        $id = (int)array_shift($get);
        if (!preg_match('/[0-9]+/', $id)) {
            $this->index();
        }
        $page = (int)(!empty($get[0]) ? $get[0] : 0);
        /** @var $album MO_Album */
        $album = MO_Album::getById($id);
        $album->fillPhotos();

        $album->photos->renderPages();

        Registry::$display->assign(
            array(
                'request' => array(
                    'album' => $album,
                    'page'  => $page
                )
            )
        );
        Registry::$display->disp('page/album');
    }
}
