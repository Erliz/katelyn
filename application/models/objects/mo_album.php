<?php
/**
 * User: Elio
 * Date: 23.12.12
 *
 */
class MO_Album extends MO_DbObject
{
    protected $id;
    protected $title;
    protected $description;
    protected $cover;
    protected $time_create;
    protected $time_modify;

    static public function getByTitle($title){
        $model = new self();
        return $model->getBy(array('title'=>$title));
    }

    /**
     * @inherit
     */
    public function getCurrentClassProperty(){
        return get_object_vars($this);
    }

    public static function createFromArray($array) {
        if (empty($array)) {
            throw new E_Fatal('empty create variable');
        }

        $array = array_merge(
        // todo: find better variant
            get_class_vars(__CLASS__),
            $array
        );
        $instance = new self();

        $instance->setTitle($array['title']);
        $instance->setDescription($array['description']);
        // todo: now it`s hard code
        $instance->setTimeCreate(time());

        return $instance;
    }

    public static function fromArray(array $array){
        $array = array_merge(
        // todo: find better variant
            get_class_vars(__CLASS__),
            $array
        );
        $instance = new self();
        $instance->setId($array['id']);
        $instance->setTitle($array['title']);
        $instance->setDescription($array['description']);
        $instance->setCover(!empty($array['cover'])?MO_Photo::createFromArray($array['cover']):null);
        $instance->setTimeCreate($array['time_create']);
        $instance->setTimeModify($array['time_modify']);

        return $instance;
    }

    public function getId()
    {
        return $this->id;
    }

    private function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCover()
    {
        return $this->cover;
    }

    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    public function getTimeCreate()
    {
        return $this->time_create;
    }

    public function setTimeCreate($time_create)
    {
        $this->time_create = $time_create;
        $this->setTimeModify($time_create);
    }

    public function getTimeModify()
    {
        return $this->time_modify;
    }

    public function setTimeModify($time_modify)
    {
        $this->time_modify = $time_modify;
    }
}
