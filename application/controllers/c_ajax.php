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

    private function sendAnswer($result){
        if(!empty($result['error'])){
            $answer = $result;
        } else {
            $answer['data'] = $result;
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

}