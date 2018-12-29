<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/12/2018
 * Time: 21:42
 */

namespace Rabbit\ORM\Drivers\Attributes;


class MySqlAttributeCollection
{

    public $attributes = [];

    public function __construct()
    {
        $this->attributes = [
            "BUFFERED_QUERY" => \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY
        ];
    }

    public function get(string $attribute)
    {
        if($this->has($attribute))
            return $this->attributes[$attribute];
        return false;
    }

    public function set(string $attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    public function has(string $attribute)
    {
        return isset($this->attributes[$attribute]);
    }

}