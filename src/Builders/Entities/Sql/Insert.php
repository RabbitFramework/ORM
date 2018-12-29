<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 09:38
 */

namespace Rabbit\ORM\Builders\Entities\Sql;

use Rabbit\ORM\Builders\Entities\InsertEntityInterface;
use function Sodium\add;

final class Insert extends BaseEntity implements InsertEntityInterface
{
    public function __construct(string $name = '')
    {
        $this->queryDatas['insert'] = $name;
        $this->queryDatas['columns'] = [];
        $this->queryDatas['values'] = [];
    }

    public function insert(string $name = '')
    {
        $this->queryDatas['insert'] = $name;
        return $this;
    }

    public function column(string ...$names)
    {
        $this->queryDatas['columns'] = array_merge($this->queryDatas['columns'], $names);
        return $this;
    }

    public function values(string ...$values)
    {
        $this->queryDatas['values'] = array_merge($this->queryDatas['values'], $values);
        return $this;
    }

    public function getInsert(): string
    {
        return "INSERT INTO {$this->queryDatas['insert']}";
    }

    public function getColumns(): string
    {
        $sql = '';
        if(!empty($this->queryDatas['columns'])) {
            $sql .= '(';
            foreach ($this->queryDatas['columns'] as $key => $column) {
                $sql .= "{$column}".(count($this->queryDatas['columns'])-1 !== $key ? ', ' : '');
            }
            $sql .= ')';
        }
        return $sql;
    }

    public function getValues(): string
    {
        $sql = '';
        if(!empty($this->queryDatas['values'])) {
            $sql .= ' VALUES (';
            foreach ($this->queryDatas['values'] as $key => $value) {
                $sql .= "'".htmlspecialchars(addslashes($value))."'".(count($this->queryDatas['values'])-1 !== $key ? ', ' : '');
            }
            $sql .= ')';
        }
        return $sql;
    }

    public function getQuery() : string
    {
        return $this->getInsert().$this->getColumns().$this->getValues().$this->getWhere();
    }
}