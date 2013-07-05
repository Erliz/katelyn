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
            if(is_object($result)){
                if (is_subclass_of($result,'MO_Object')){
                    $answer['data'] = $result->toArray();
                } elseif (is_subclass_of($result, 'MO_Collection')){
                    $answer['data'] = $result->getCollection();
                }
            } else {
                if(!empty($result['error'])){
                    $answer = $result;
                } else {
                    $answer['data'] = $result;
                }
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
            case 'album': $result=$this->admAlbum($get); break;
            case 'photo': $result=$this->admPhoto($get); break;
        }

        $this->sendAnswer($result);
    }

    public function album($get){
        $model=new M_Album();
        $action = array_shift($get);
        $result=false;
        switch($action){
            case 'collection': $result=$model->getById($get[0]);$result->fillPhotos(); break;
        }

        $this->sendAnswer($result);
    }

    public function sendMail(){
        if (empty($_POST)) {
            $this->sendAnswer(false);
        }
        $text = '';
        foreach ($_POST as $key => $value) {
            $text[$key] = trim(strip_tags($value));
        }
        $message = "Об отправителе:\n";
        $message .= 'Имя: ' . $text['name'] . "\n";
        $message .= 'email: ' . $text['email'] . "\n";
        $message .= 'Телефон: ' . $text['phone'] . "\n";
        $message .= 'Тело сообщения:' . "\n";
        $message .= $text['content'];
        $mailer = new M_Mailer();
        $result = $mailer->send($message);
        $this->sendAnswer($result);
    }

    private function admAlbum($get) {
        $model=new M_Album();
        $action = array_shift($get);
        $result=false;
        switch($action){
            case 'new': $result=$model->createAlbum($_POST['title']); break;
        }

        return $result;
    }

    private function admPhoto($get) {
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