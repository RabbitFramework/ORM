<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04/01/2019
 * Time: 19:39
 */

namespace Rabbit\ORM\Repository;


interface RepositoryInterface
{

//    public function findOneById($id);

    public function findAll() : array;

//    public function existsById($id) : bool;

}