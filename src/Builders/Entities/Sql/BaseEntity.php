<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 09:08
 */

namespace Rabbit\ORM\Builders\Entities\Sql;


/**
 * Class BaseEntity
 * @package Rabbit\Database\Builders\Entities\Sql
 */
class BaseEntity
{

    /**
     * @var array
     */
    protected $queryDatas = [
        'where' => [
            'single' => '',
            'and' => [],
            'or' => []
        ]
    ];

    /**
     * @param string $where
     * @return $this
     */
    public function where(string $where) {
        $this->queryDatas['where']['single'] = $where;
        return $this;
    }

    /**
     * @param string ...$where
     * @return $this
     */
    public function andWhere(string ...$where)
    {
        $this->queryDatas['where']['and'] = array_merge($this->queryDatas['where']['and'], $where);
        return $this;
    }

    /**
     * @param string ...$where
     * @return $this
     */
    public function orWhere(string ...$where)
    {
        $this->queryDatas['where']['or'] = array_merge($this->queryDatas['where']['or'], $where);
        return $this;
    }

    /**
     * @return string
     */
    public function getWhere() : string {
        if(!empty($this->queryDatas['where']['single'])) {
            $sql = " WHERE {$this->queryDatas['where']['single']}";
            if(!empty($this->queryDatas['where']['and'])) {
                foreach ($this->queryDatas['where']['and'] as $where) {
                    $sql .= " AND {$where}";
                }
            }
            if(!empty($this->queryDatas['where']['or'])) {
                foreach ($this->queryDatas['where']['or'] as $queryData) {
                    $sql .= " OR {$where}";
                }
            }
            return $sql;
        }
        return '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getQuery();
    }

}