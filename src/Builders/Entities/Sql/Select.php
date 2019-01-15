<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 09:08
 */

namespace Rabbit\ORM\Builders\Entities\Sql;

use Rabbit\ORM\Builders\Entities\SelectEntityInterface;

/**
 * Class Select
 * @package Rabbit\ORM\Builders\Entities\Sql
 */
final class Select extends BaseEntity implements SelectEntityInterface
{

    /**
     * Select constructor.
     * @param string ...$names
     */
    public function __construct(string ...$names)
    {
        $this->queryDatas['select'] = $names;
        $this->queryDatas['from'] = 'default';
    }

    /**
     * @param string ...$names
     * @return $this|mixed
     */
    public function andSelect(string ...$names)
    {
        $this->queryDatas['select'] = array_merge($this->queryDatas['select'], $names);
        return $this;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function from(string $from)
    {
        $this->queryDatas['from'] = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelect(): string
    {
        $sql = 'SELECT ';
        foreach ($this->queryDatas['select'] as $key => $select) {
            $sql .= "{$select}".(count($this->queryDatas['select'])-1 !== $key ? ', ' : '');
        }
        return $sql;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return " FROM {$this->queryDatas['from']}";
    }

    /**
     * @return string
     */
    public function getQuery() : string {
        return $this->getSelect().$this->getFrom().$this->getWhere();
    }
}