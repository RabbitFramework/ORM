<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/12/2018
 * Time: 18:26
 */

namespace Rabbit\ORM\Builders\Entities\Sql;

use Rabbit\ORM\Builders\Entities\CreateColumnEntityInterface;
use Rabbit\ORM\Mapper\EntityInterface;

final class CreateColumn extends BaseEntity implements CreateColumnEntityInterface
{

    public function __construct(string $table = '')
    {
        $this->queryDatas['alter'] = $table;
        $this->queryDatas['column'] = '';
        $this->queryDatas['datatype'] = '';
    }

    public function table(string $table = '')
    {
        $this->queryDatas['alter'] = $table;
        return $this;
    }

    public function column(string $name = '')
    {
        $this->queryDatas['column'] = $name;
        return $this;
    }

    public function datatype(string $type = '')
    {
        $this->queryDatas['datatype'] = $type;
        return $this;
    }

    public function getAlter(): string
    {
        return "ALTER TABLE {$this->queryDatas['alter']}";
    }

    public function getAdd(): string
    {
        return " ADD {$this->queryDatas['column']} {$this->queryDatas['datatype']}";
    }

    public function getQuery(): string
    {
        return $this->getAlter().$this->getAdd();
    }

}