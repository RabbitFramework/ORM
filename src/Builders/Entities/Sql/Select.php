<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 09:08
 */

namespace Rabbit\ORM\Builders\Entities\Sql;

use Rabbit\ORM\Builders\Entities\SelectEntityInterface;

final class Select extends BaseEntity implements SelectEntityInterface
{

    public function __construct(string ...$names)
    {
        $this->queryDatas['select'] = $names;
        $this->queryDatas['from'] = 'default';
    }

    public function andSelect(string ...$names)
    {
        $this->queryDatas['select'] = array_merge($this->queryDatas['select'], $names);
        return $this;
    }

    public function from(string $from)
    {
        $this->queryDatas['from'] = $from;
        return $this;
    }

    public function getSelect(): string
    {
        $sql = 'SELECT ';
        foreach ($this->queryDatas['select'] as $key => $select) {
            $sql .= "{$select}".(count($this->queryDatas['select'])-1 !== $key ? ', ' : '');
        }
        return $sql;
    }

    public function getFrom(): string
    {
        return " FROM {$this->queryDatas['from']}";
    }

    public function getQuery() : string {
        return $this->getSelect().$this->getFrom().$this->getWhere();
    }
}