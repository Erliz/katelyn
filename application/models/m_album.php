<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 23.12.12
 *
 */
class M_Album
{
    private $onPage = 6;

    public function createAlbum($title)
    {
        $title = trim(strip_tags($title));
        $album = MO_Album::getByTitle($title);
        if (!empty($album)) {
            return array('error' => 'Альбом с таким названием уже существует');
        }
        $album = MO_Album::createFromArray(
            array('title' => $title, 'description' => '')
        );
        if ($album->saveToBase()) {
            return MO_Album::getByTitle($title);
        } else {
            return false;
        }
    }

    public function getAll()
    {
        $collection = new MO_Albums();
        $stmt = Registry::$db->query("SELECT * FROM `album`");
        while ($row = $stmt->fetch()) {
            $collection->add(MO_Album::fromArray($row));
        }

        return $collection;
    }

    public function getPage($page = 1)
    {
        $collection = new MO_Albums();
        $from = $this->onPage * ($page - 1);
        $stmt = Registry::$db->query("SELECT * FROM `album` LIMIT $from, {$this->onPage}");
        while ($row = $stmt->fetch()) {
            $collection->add(MO_Album::fromArray($row));
        }

        return $collection;
    }

    public function getPagesCount()
    {
        $stmt = Registry::$db->query("SELECT COUNT(*) as count FROM `album`");
        $result = $stmt->fetch();

        return ceil($result['count'] / $this->onPage);
    }

    /**
     * @param int $id
     *
     * @return MO_Album
     */
    public function getById($id)
    {
        $collection = $this->getAll();

        return $collection->getItemByVar('id', $id);
    }
}
