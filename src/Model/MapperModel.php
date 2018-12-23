<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/12/2018
 * Time: 19:36
 */
namespace Rabbit\ORM\Model;

if(class_exists('\Rabbit\MVC\Model')) {
    class MapperModel extends \Rabbit\ORM\Mapper\Mapper implements \Rabbit\MVC\ModelInterface
    {

    }
}