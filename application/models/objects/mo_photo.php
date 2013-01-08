<?php
/**
 * User: s.vetlovskiy
 * Date: 21.12.12
 *
 */
class MO_Photo extends MO_DbObject
{
    protected $id;
    protected $album;
    protected $title;
    protected $description;
    protected $time_upload;
    protected $is_vertical;

    function __construct($isNew = true)
    {
        $this->setIsNew($isNew);
    }

    public function saveToBase()
    {
        $this->id=parent::saveToBase();
    }

    static public function createFromArray($array)
    {
        if (empty($array)) {
            throw new E_Fatal('empty create variable');
        }

        $array = array_merge(
            // todo: find better variant
            get_class_vars(__CLASS__),
            $array
        );
        $instance = new self();

        $instance->setAlbum($array['album']);
        $instance->setTitle($array['title']);
        $instance->setDescription($array['description']);
        // todo: now it`s hard code
        $instance->setTimeUpload($array['time_upload']);
        $instance->setIsVertical($array['is_vertical']);

        return $instance;
    }

    public static function fromArray(array $array){
        $array = array_merge(
        // todo: find better variant
            get_class_vars(__CLASS__),
            $array
        );
        $instance = new self(false);
        $instance->id=$array['id'];
        $instance->setAlbum($array['album']);
        $instance->setTitle($array['title']);
        $instance->setDescription($array['description']);
        $instance->setTimeUpload($array['time_upload']);
        $instance->setIsVertical($array['is_vertical']);

        return $instance;
    }

    // setters & getters
    public function getId()
    {
        return $this->id;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function setAlbum($album)
    {
        $this->album = $album;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    private function setTimeUpload($time_upload = null)
    {
        $this->time_upload = $time_upload?:time();
    }

    public function getTimeUpload()
    {
        return date('d.m.Y', $this->time_upload);
    }

    public function setIsVertical($is_vertical)
    {
        $this->is_vertical = (bool)$is_vertical;
    }

    public function getIsVertical()
    {
        return $this->is_vertical;
    }

    /**
     * @inherit
     */
    public function getCurrentClassProperty()
    {
        return get_object_vars($this);
    }

}
