<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/12/2018
 * Time: 11:47
 */

namespace Rabbit\ORM\Builders\Entities\Sql;


final class MultiRequest
{

    protected $request = [];

    public function __construct(string ...$query)
    {
        $this->request = array_merge($this->request, $query);
    }

    public function add(string ...$query)
    {
        $this->request = array_merge($this->request, $query);
        return $this;
    }

    public function getQuery()
    {
        return implode(';', $this->request);
    }

    public function __toString()
    {
        return $this->getQuery();
    }

}