<?php
/**
 * User: Elio
 * Date: 07.01.13
 *
 */
class M_Photo
{
    private $album;

    function setAlbum($num){
        $this->album=$num;
    }

    public function upload(){
        $model = new M_UploadHandlerExtend();
        $content=$model->getContent();
        $result = array();
        if(isset($content) && count($content['files'])>0){
            foreach($content['files'] as $file){
                $photo = $this->create($file);
                $file->url = preg_replace('/[^\/]+\.jpg$/', $photo->getId().'.jpg', $file->url);
                $file->thumbnail_url = preg_replace('/[^\/]+\.jpg$/', $photo->getId().'.jpg', $file->thumbnail_url);
                $result[]=$file;
            }
        }
        return array('files'=>$result);
    }

    private function create(stdClass $file){
        $pathTmp=ConfigPath::$real.ConfigPath::$photoTmp;
        $pathTmpThumbnail=ConfigPath::$real.ConfigPath::$photoTmpTbn;
        $path=ConfigPath::$real.ConfigPath::$photo;
        $pathThumbnail=ConfigPath::$real.ConfigPath::$photoTbn;

        $pathTmpFile=$pathTmp.$file->name;
        $pathTmpThumbnailFile=$pathTmpThumbnail.$file->name;
        if(!is_file($pathTmpFile)) throw new E_Fatal('No img file found "'.$pathTmpFile.'"');
        if(!is_file($pathTmpThumbnailFile)) throw new E_Fatal('No img file found "'.$pathTmpThumbnailFile.'"');
        $photoTmpName=trim(strip_tags(str_replace('.jpg','',$file->name)));
        $options=array(
            'album'=>$this->album,
            'title'=>$photoTmpName,
            'description'=>''
        );
        list($w,$h)=getimagesize($pathTmpFile);
        $photo=MO_Photo::createFromArray($options);
        $photo->setIsVertical($w<$h);
        $photo->saveToBase();
        $photoName=$photo->getId().'.jpg';
        $pathFile=$path.$photoName;
        $pathFileTbn=$pathThumbnail.$photoName;
        if(copy($pathTmpFile,$pathFile)){
            unlink($pathTmpFile);
        } else {
            throw new E_Fatal('Couldn`t copy file from "'.$pathTmpFile.'" to "'.$pathFile.'"');
        }
        if(!is_dir($pathThumbnail)){
            mkdir($pathThumbnail);
        }
        if(copy($pathTmpThumbnailFile,$pathFileTbn)){
            unlink($pathTmpThumbnailFile);
        } else {
            throw new E_Fatal('Couldn`t copy file from "'.$pathTmpThumbnailFile.'" to "'.$pathFileTbn.'"');
        }
        // replace old file name
        $file->url = str_replace(
            array($file->name, 'temp'),
            array($photoName, 'photo'),
            $file->url);
        $file->thumbnail_url = str_replace(
            array($file->name, 'temp'),
            array($photoName, 'photo'),
            $file->thumbnail_url
        );
        unset($file->delete_type, $file->delete_url);
        return $photo;
    }
}
