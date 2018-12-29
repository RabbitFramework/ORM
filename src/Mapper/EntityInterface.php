<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:08
 */

namespace Rabbit\ORM\Mapper;


interface EntityInterface
{

    public function saveValue($ref);

    public function saveAll();

    public function pull();

}