<?php
/**
 * User: Elio
 * Date: 21.12.12
 *
 */
abstract class MO_DbObject extends MO_Object
{
    private $objectPrefix = 'MO_';
    /** @var $dbh PDO */
    private $dbh;
    private $currentClass;
    private $tableName;
    private $tableColumns;
    private $tableColumnsFields;
    private $is_new;

    public function saveToBase()
    {
        $table = $this->getTableName();
        $columns = $this->generateColumnsString($this->getTableColumnsFields());
        $placeholders = $this->generatePlaceholersString($this->getTableColumnsFields());
        $sql = "
          INSERT INTO $table
            ($columns)
          VALUES
            ($placeholders)
        ";

        /** @var $dbh PDO */
        try {
            $dbh = $this->getDbh();
            $stmt = $dbh->prepare($sql);
            if($stmt->execute($this->getTableColumns())){
                return $dbh->lastInsertId();
            }
        } catch (PDOException $e) {
            M_Logger::echer($sql, 'SQL');
            M_Logger::echer($e);
            exit;
        }
    }

    protected function getBy(array $array, $limit = 1){
        if(!is_numeric($limit)){
            throw new E_Fatal('$limit is not numeric');
        }
        $table = $this->getTableName();
        $where = $this->generateWhereString(array_keys($array));
        $sql = "
          SELECT *
          FROM $table
          WHERE $where
          LIMIT $limit;
        ";
        /** @var $dbh PDO */
        try {
            $dbh = $this->getDbh();
            $stmt = $dbh->prepare($sql);

            $stmt->execute($array);
            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            M_Logger::echer($sql, 'SQL');
            M_Logger::echer($e);
            exit;
        }
        if (is_array($result) && count($result) > 0) {
            if ($limit == 1) {
                return $result[0];
            } else {
                return $result;
            }
        }

        return false;
    }

    private function generateWhereString(array $array){
        $columns=array();
        foreach($array as $column){
            $columns[]="`$column`=:$column";
        }
        return join(' AND ', $columns);
    }

    private function generateColumnsString(array $array){
        return '`' . join('`,`', $array) . '`';
    }

    private function generatePlaceholersString(array $array){
        return ':' . join(', :', $array);
    }

    /**
     * return get_object_vars($this);
     * @return array
     */
    abstract public function getCurrentClassProperty();

    // setters & getters
    private function setDbh($dbh = null)
    {
        if (empty($dbh)) {
            $dbh = Registry::$db;
        }
        $this->dbh = $dbh;
    }

    /**
     * @return PDO
     */
    private function getDbh()
    {
        if (empty($this->dbh)) {
            $this->setDbh();
        }

        return $this->dbh;
    }

    private function setTableName()
    {
        $this->tableName = strtolower(str_replace($this->objectPrefix, '', $this->getCurrentClass()));
    }

    protected function getTableName()
    {
        if (empty($this->tableName)) {
            $this->setTableName();
            if (empty($this->tableName)) {
                throw new E_Fatal($this->getCurrentClass() . ' must have a $tableName');
            }
        }

        return $this->tableName;
    }

    private function setTableColumns()
    {
        //$currentColumns = forward_static_call(array($this->getCurrentClass(), 'getCurrentClassProperty'));
        $currentColumns = $this->getCurrentClassProperty();
        $columns = array_keys(get_class_vars(__CLASS__));
        foreach ($columns as $field) {
            if (isset($currentColumns[$field])) {
                unset($currentColumns[$field]);
            }
        }

        $this->tableColumns = $currentColumns;
    }

    private function getTableColumns()
    {
        if (empty($this->tableColumns)) {
            $this->setTableColumns();
        }

        return $this->tableColumns;
    }

    private function setTableColumnsFields()
    {
        $currentColumns = array_keys($this->getTableColumns());
        $columns = array_keys(get_class_vars(__CLASS__));
        $this->tableColumnsFields = array_diff($currentColumns, $columns);
    }

    private function getTableColumnsFields()
    {
        if (empty($this->tableColumnsFields)) {
            $this->setTableColumnsFields();
        }

        return $this->tableColumnsFields;
    }

    public function getIsNew()
    {
        return $this->is_new;
    }

    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;
    }

    private function getCurrentClass()
    {
        if (empty($this->currentClass)) {
            $this->setCurrentClass();
        }

        return $this->currentClass;
    }

    private function setCurrentClass()
    {
        $this->currentClass = get_class($this);
    }
}
