<?php
/**
 * Контроллер отвечающий за вывод ajax сообщений
 */
class C_Ajax extends C_Base
{
    function index()
    {
        $this->sendAnswer(false);
    }

    private function sendAnswer($result, $wrap=true){
        if($wrap){
            if(!empty($result['error'])){
                $answer = $result;
            } else {
                $answer['data'] = $result;
            }
        } else {
            $answer = $result;
        }
        echo json_encode($answer);
        exit;
    }

    public function admin($get){
        if (empty($_SESSION['admin_key']) || $_SESSION['admin_key'] != $this->settings['admin_key']) {
            $this->index();
        }
        $result=false;
        $module=array_shift($get);
        switch($module){
            case 'album': $result=$this->album($get); break;
            case 'photo': $result=$this->photo($get); break;
        }

        $this->sendAnswer($result);
    }

    private function album($get) {
        $model=new M_Album();
        $action = array_shift($get);
        $result=false;
        switch($action){
            case 'new': $result=$model->createAlbum($_POST['title']); break;
        }

        return $result;
    }

    private function photo($get) {
        $model = new M_Album();
        $album = $model->getById((int)$_GET['album']);
        if(!$album) return array('error'=>'No album "'.(int)$_GET['album'].'"');
        $model = new M_Photo();
        $model->setAlbum($album->getId());
        $action = array_shift($get);
        $result = false;
        switch($action){
            case 'upload': $this->sendAnswer($model->upload(), false); break;
        }

        return $result;
    }
}