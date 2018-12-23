<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09/12/2018
 * Time: 09:30
 */

namespace Rabbit\ORM\Builders;


/**
 * Class MySqlQuery
 * @package Rabbit\Database\Builders
 */
class MySqlQuery extends BaseBuilder implements QueryInterface
{

    /**
     * @param string ...$select
     * @return $this
     */
    public function select(string ...$select)
    {
        $this->select = $select;
        $this->type = $this->lastExecuted = self::SELECT;
        return $this;
    }

    /**
     * @param $reference
     * @param $alias
     * @return $this
     * @throws BuilderException
     */
    public function as($reference, $alias)
    {
        if(is_array($reference) && is_array($alias)) {
            if(count($reference) === count($alias)) {
                foreach ($reference as $key => $name) {
                    if(is_string($name) && $alias[$key]) {
                        if($this->lastExecuted === self::SELECT) {
                            if(in_array($name, $this->select)) {
                                $this->select[array_search($name, $this->select)] = ['column' => $name, 'alias' => $alias];
                            }
                        } elseif($this->lastExecuted === self::FROM) {
                            throw new BuilderException('[Rabbit => Database->MySqlQueryBuilder::as()] Unable to perform the function, the lastExecuted function is Database->MySqlQueryBuilder::from() and it can be only one $alias');
                        }
                    }
                }
            } else {
                throw new BuilderException('[Rabbit => Database->MySqlQueryBuilder::as()] Unable to perform the function, the count of array $columns and $aliases is not equal');
            }
        } elseif(is_string($reference) && is_string($alias)) {
            if($this->lastExecuted === self::SELECT) {
                if(in_array($reference, $this->select)) {
                    $this->select[array_search($reference, $this->select)] = ['column' => $reference, 'alias' => $alias];
                }
            } elseif($this->lastExecuted === self::FROM) {
                $this->from = ['table' => $reference, 'alias' => $alias];
            }
        } else {
            throw new BuilderException('[Rabbit => Database->MySqlQueryBuilder::as()] Unable to perform the function, the $column and/or $alias variables are not array or string');
        }
        $this->lastExecuted = self::AS;
        return $this;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function from(string $from)
    {
        if($this->type === self::SELECT && ($this->lastExecuted === self::SELECT || $this->lastExecuted === self::AS))
            $this->from = $from;
        $this->lastExecuted = self::FROM;
        return $this;
    }

    /**
     * @param string ...$where
     * @return $this
     */
    public function where(string ...$where)
    {
        $this->where = $where;
        $this->lastExecuted = self::WHERE;
        return $this;
    }

    /**
     * @param int ...$index
     * @return $this
     */
    public function and(int ...$index) {
        foreach ($index as $i) {
            if($this->lastExecuted === self::WHERE || $this->lastExecuted === self::OR) {
                if(isset($this->where[$i-1])) {
                    $this->where[$i-1] = ['where' => $this->where[$i-1], 'type' => 'and'];
                }
            }
        }
        $this->lastExecuted = self::AND;
        return $this;
    }

    /**
     * @param int ...$index
     * @return $this
     */
    public function or(int ...$index) {
        foreach ($index as $i) {
            if($this->lastExecuted === self::WHERE || $this->lastExecuted === self::AND) {
                if(isset($this->where[$i-1])) {
                    $this->where[$i-1] = ['where' => $this->where[$i-1], 'type' => 'or'];
                }
            }
        }
        $this->lastExecuted = self::OR;
        return $this;
    }

    /**
     * @param string $insert
     * @return $this
     */
    public function insert(string $insert)
    {
        $this->insert = $insert;
        $this->type = $this->lastExecuted = self::INSERT;
        return $this;
    }

    /**
     * @param string ...$column
     * @return $this
     */
    public function columns(string ...$column) {
        if(($this->type === self::INSERT || $this->type === self::UPDATE) && ($this->lastExecuted === self::INSERT || $this->lastExecuted === self::SET))
            $this->columns = $column;
        $this->lastExecuted = self::COLUMNS;
        return $this;
    }

    /**
     * This function can only be used to set only one column => only triggered if $this->type = ALTER | CREATE | DROP | INSERT
     *
     * @param string $column
     * @return $this
     */
    public function column(string $column) {
        if(($this->type === self::CREATE || $this->type === self::ALTER) || $this->lastExecuted === self::ALTER)
            $this->column = $column;
        elseif(($this->type === self::INSERT || $this->type === self::UPDATE) && ($this->lastExecuted === self::INSERT || $this->lastExecuted === self::SET))
            $this->columns[] = $column;
        $this->lastExecuted = self::COLUMN;
        return $this;
    }

    /**
     * @param $value
     */
    public function value($value) {
        if(($this->type === self::INSERT || $this->type === self::UPDATE) && ($this->lastExecuted === self::INSERT || $this->lastExecuted === self::VALUES || $this->lastExecuted === self::VALUE || $this->lastExecuted === self::COLUMNS || $this->lastExecuted === self::COLUMN))
            $this->values = $value;
        $this->lastExecuted = self::VALUE;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function values(string $value) {
        if(($this->type === self::INSERT || $this->type === self::UPDATE) && ($this->lastExecuted === self::INSERT || $this->lastExecuted === self::VALUES || $this->lastExecuted === self::VALUE || $this->lastExecuted === self::COLUMNS || $this->lastExecuted === self::COLUMN))
            $this->values = $value;
        $this->lastExecuted = self::VALUES;
        return $this;
    }

    /**
     * @return $this
     */
    public function update() {
        $this->type = $this->lastExecuted = self::UPDATE;
        return $this;
    }

    /**
     * @return $this
     */
    public function set() {
        $this->lastExecuted = self::SET;
        return $this;
    }

    /**
     * @return $this
     */
    public function alter() {
        $this->type = $this->lastExecuted = self::ALTER;
        return $this;
    }

    /**
     *
     */
    public function create() {
        $this->type = $this->lastExecuted = self::CREATE;
        return $this;
    }

    /**
     * @return $this|mixed
     */
    public function drop() {
        $this->type = $this->lastExecuted = self::DROP;
        return $this;
    }

    /**
     * This function can only be used to set only one table => only triggered if $this->type = ALTER | CREATE | DROP
     *
     * @param string $table
     * @return $this
     */
    public function table(string $table) {
        if(($this->type === self::CREATE || $this->type === self::ALTER || $this->type === self::UPDATE) && ($this->lastExecuted === self::ALTER || $this->lastExecuted === self::UPDATE))
            $this->table = $table;
        $this->lastExecuted = self::TABLE;
        return $this;
    }

    /**
     * @param string $type
     * @return $this;
     */
    public function dataType(string $type) {
        if(($this->type === self::CREATE || $this->type === self::ALTER) && $this->lastExecuted === self::COLUMN) {
            $this->dataType = $type;
            $this->lastExecuted = self::DATATYPE;
        }
        return $this;
    }

    /**
     * @return string
     */
    protected function generateSelect() {
        $sql = 'SELECT ';
        foreach ($this->select as $key => $select) {
            if(is_array($select)) $sql .= " {$select['column']} AS {$select['alias']},";
            else $sql .= " {$select}".(count($this->select)-1 !== $key ? ', ' : '');
        }
        $sql .= ' FROM '.(is_array($this->from) ? $this->from['table'].' AS '.$this->from['alias'] : $this->from);
        if(!empty($this->where)) {
            $sql .= ' WHERE';
            foreach ($this->where as $key => $item) {
                $sql .= (is_array($item) ? ($item['type'] === 'and' ? ' AND ' : ' OR ').$item['where'] : ' '.$item);
            }
        }
        return $sql;
    }

    /**
     * @return string
     */
    protected function generateUpdate() {
        $sql = "UPDATE {$this->table} SET ";
        foreach ($this->columns as $key => $column) {
            if(isset($this->values[$key])) {
                $sql .= $column.'='.$this->values[$key].(count($this->columns)-1 !== $key ? ', ' : '');
            }
        }
        if(!empty($this->where)) {
            $sql .= ' WHERE';
            foreach ($this->where as $key => $item) {
                $sql .= (is_array($item) ? ($item['type'] === 'and' ? ' AND ' : ' OR ').$item['where'] : ' '.$item);
            }
        }
        return $sql;
    }

    /**
     *
     */
    protected function generateModification() {
        $sql = '';
        if($this->type === self::ALTER) {
            if(isset($this->table)) {
                if(isset($this->column)) {
                    $sql = "ALTER TABLE {$this->table} ALTER COLUMN {$this->column} {$this->dataType()}";
                }
            }
        } elseif($this->type === self::CREATE) {
            if(isset($this->table)) {
                if(isset($this->column)) {
                    $sql = "ALTER TABLE {$this->table} ADD COLUMN {$this->column} {$this->dataType}";
                } else {
                    $sql = "CREATE TABLE {$this->table}";
                }
            }
        } elseif($this->type === self::DROP) {
            if(isset($this->table)) {
                if(isset($this->column)) {
                    $sql = "ALTER TABLE {$this->table} DROP COLUMN {$this->column}";
                } else {
                    $sql = "DROP TABLE {$this->table}";
                }
            }
        }
        return $sql;
    }

    /**
     * @return string
     */
    protected function generateInsert() {
        $sql = "INSERT INTO {$this->insert}";
        if(!empty($this->columns())) {
            $sql .= '(';
            foreach ($this->columns as $key => $column) $sql .= "{$column}".(count($this->columns)-1 !== $key ? ', ' : '');
            $sql .= ')';
        }
        if(!empty($this->values)) {
            $sql .= ' VALUES (';
            foreach ($this->values as $key => $value) $sql .= "'{$value}'".(count($this->values)-1 !== $key ? ', ' : '');
            $sql .= ')';
        }
        if(!empty($this->where)) {
            $sql .= ' WHERE';
            foreach ($this->where as $key => $where) {
                if(is_array($where)) $sql .= ($where['type'] === 'and' ? ' AND ' : ' OR ').$where['where'];
                else $sql .= " {$where}";
            }
        }
        return $sql;
    }

    /**
     * @return string
     */
    public function getSql() {
        if($this->type === self::SELECT) {
            $sql = $this->generateSelect();
        } elseif ($this->type === self::INSERT) {
            $sql = $this->generateInsert();
        } elseif($this->type === self::ALTER || $this->type === self::CREATE || $this->type === self::DROP) {
            $sql = $this->generateModification();
        } elseif($this->type === self::UPDATE) {
            $sql = $this->generateUpdate();
        }
        $this->initFields();
        return $sql;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSql();
    }

}