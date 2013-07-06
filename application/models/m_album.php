<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 23.12.12
 */
class M_Album
{
    const ON_PAGE = 6;

    /**
     * @param $title
     *
     * @return array|bool
     */
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

    /**
     * @return MO_Albums
     */
    public function getAll()
    {
        $collection = new MO_Albums();
        $stmt = Registry::$db->query("SELECT * FROM `album` ORDER BY `pos`");
        while ($row = $stmt->fetch()) {
            $collection->add(MO_Album::fromArray($row));
        }

        return $collection;
    }

    /**
     * @param int $page
     *
     * @return MO_Albums
     */
    public function getPage($page = 1)
    {
        $collection = new MO_Albums();
        $from = self::ON_PAGE * ($page - 1);
        $stmt = Registry::$db->query("SELECT * FROM `album` ORDER BY `pos` LIMIT $from, {self::ON_PAGE}");
        while ($row = $stmt->fetch()) {
            $collection->add(MO_Album::fromArray($row));
        }

        return $collection;
    }

    /**
     * @return int
     */
    public function getPagesCount()
    {
        $stmt = Registry::$db->query("SELECT COUNT(*) as count FROM `album`");
        $result = $stmt->fetch();

        return ceil($result['count'] / self::ON_PAGE);
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
