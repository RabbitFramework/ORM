<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 10:01
 */

namespace Rabbit\ORM\Builders\Entities\Sql;

use Rabbit\ORM\Builders\Entities\UpdateEntityInterface;

/**
 * Class Update
 * @package Rabbit\Database\Builders\Entities\Sql
 */
final class Update extends BaseEntity implements UpdateEntityInterface
{

    /**
     * Update constructor.
     * @param string $name
     */
    public function __construct(string $name = '')
    {
        $this->queryDatas['update'] = $name;
        $this->queryDatas['columns'] = [];
        $this->queryDatas['values'] = [];
    }

    /**
     * @param string $name
     * @return $this|UpdateEntityInterface
     */
    public function update(string $name) {
        $this->queryDatas['update'] = $name;
        return $this;
    }

    /**
     * @param string ...$names
     * @return $this|UpdateEntityInterface
     */
    public function column(string ...$names) {
        $this->queryDatas['columns'] = array_merge($this->queryDatas['columns'], $names);
        return $this;
    }

    /**
     * @param string ...$values
     * @return $this|UpdateEntityInterface
     */
    public function values(string ...$values) {
        $this->queryDatas['values'] = array_merge($this->queryDatas['values'], $values);
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdate() : string
    {
        return "UPDATE {$this->queryDatas['update']} SET ";
    }

    /**
     * @return string
     */
    public function getColumn() : string
    {
        $sql = '';
        foreach ($this->queryDatas['columns'] as $key => $column) {
            if(isset($this->queryDatas['values'][$key])) {
                $sql .= "{$column}='".htmlspecialchars(addslashes($this->queryDatas['values'][$key]))."'".(count($this->queryDatas['columns'])-1 !== $key ? ', ' : '');
            }
        }
        return $sql;
    }

    /**
     * @return string
     */
    public function getQuery() : string {
        return $this->getUpdate().$this->getColumn().$this->getWhere();
    }
}