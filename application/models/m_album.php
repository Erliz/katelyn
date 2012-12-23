<?php
/**
 * User: Elio
 * Date: 23.12.12
 *
 */
class M_Album
{

    public function createAlbum($title) {
        $title=trim(strip_tags($title));
        $album=MO_Album::getByTitle($title);
        if(!empty($album)){
            return array('error'=>'Альбом с таким названием уже существует');
        }
        $album=MO_Album::createFromArray(
            array('title'=>$title,'description'=>'')
        );
        if($album->saveToBase()){
            return MO_Album::getByTitle($title);
        } else {
            return false;
        }
    }

    public function getAll() {
        $collection=new MO_Albums();
        $stmt=Registry::$db->query("SELECT * FROM `album`");
        while($row = $stmt->fetch()){
            $collection->add(MO_Album::fromArray($row));
        }

        return $collection;
    }
}
