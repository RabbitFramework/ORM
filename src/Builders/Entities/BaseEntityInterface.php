<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/12/2018
 * Time: 10:28
 */

namespace Rabbit\ORM\Builders\Entities;


interface BaseEntityInterface
{

    public function getQuery() : string;

    public function where(string $where);

    public function andWhere(string ...$where);

    public function orWhere(string ...$where);

    public function getWhere() : string;

    public function __toString();

}