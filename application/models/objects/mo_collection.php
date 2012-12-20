<?php
/**
 * User: s.vetlovskiy
 * Date: 17.05.12
 *
 * Основной Класс для работы с коллекциями
 */
class MO_Collection
{
    /** @var array Массив в котором содержатся ссылки на объекты коллекции */
    protected $library = array();

    /**
     * Добавление нового объекта в коллекцию
     *
     * @param MO_Object $object
     *
     * @return bool
     */
    public function add(MO_Object $object)
    {
        array_push($this->library, $object);

        return true;
    }

    /**
     * Возвращает текущий размер коллекции
     *
     * @return int
     */
    public function getSize()
    {
        return count($this->library);
    }

    /**
     * Очистка коллекции
     *
     * @return bool
     */
    public function clear()
    {
        $this->library = Array();
        if (count($this->library) == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает ссылку на следующий Объект
     *
     * @return MO_Object
     */
    public function nextItem()
    {
        return next($this->library);
    }

    /**
     * Возвращает ссылку на текущий Объект
     *
     * @return MO_Object
     */
    public function curItem()
    {
        return current($this->library);
    }

    /**
     * Возвращает ссылку на предыдущий Объект
     *
     * @return MO_Object
     */
    public function prevItem()
    {
        return prev($this->library);
    }

    /**
     * Возвращает ссылку на объект под указанным порядковым номером
     *
     * @param int $id Порядковый номер
     *
     * @return bool
     */
    public function getByKey($id)
    {
        if (isset($this->library[$id])) {
            return $this->library[$id];
        } else {
            return false;
        }
    }

    /**
     * Возвращает массив с ссылками на Объекты коллекции
     * @return array
     */
    public function getCollection()
    {
        return $this->library;
    }

    /**
     * Генерация новой колекции на основании масива с данными
     *
     * @param string|int $var
     * @param array      $list
     *
     * @return MO_Collection
     */
    public function getNewCollectionByVal($var, $list)
    {
        $class = get_class($this);
        /** @var MO_Collection $lib */
        $lib = new $class;
        foreach ($this->library as $offer) {
            if (in_array($offer->{$var}, $list)) {
                $lib->add($offer);
            }
        }

        return $lib;
    }

    /**
     * Return List of all offers name in Collection
     *
     * @param      $var
     * @param bool $unique
     *
     * @return array
     */
    public function getVar($var, $unique = false)
    {
        $list = Array();
        foreach ($this->library as $offer) {
            $list[] = $offer->{$var};
        }
        if ($unique == true) {
            $list = array_unique($list);
        }

        return $list;
    }

    /**
     * Return List of all offers name in Collection with keys as serial number
     *
     * @param      $var
     * @param bool $unique
     *
     * @return array
     */
    protected function getVarWithSN($var, $unique = false)
    {
        $list = Array();
        foreach ($this->library as $key => $offer) {
            $list[$key] = $offer->{$var};
        }
        if ($unique == true) {
            $list = array_unique($list);
        }

        return $list;
    }

    /**
     * Находит и возвращает объект по соответствию параметра и значения
     * @param $key
     * @param $var
     *
     * @return bool
     */
    public function getItemByVar($key, $var)
    {
        foreach ($this->library as $item) {
            if (isset($item->{$key}) && $item->{$key} == $var) {
                return $item;
            }
        }

        return false;
    }

    /**
     * Находит и возвращает объект по соответствию параметра и поиском подстроки в строке
     * @param $key
     * @param $var
     *
     * @return bool
     */
    public function getItemBySubString($key, $var)
    {
        foreach ($this->library as $item) {
            if (isset($item->{$key})) {
                if (strpos($item->{$key}, $var) !== false) {
                    return $item;
                }
            }
        }

        return false;
    }
}
