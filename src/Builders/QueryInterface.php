<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/11/2018
 * Time: 18:17
 */

namespace Rabbit\ORM\Builders;

use Rabbit\ORM\Builders\Entities\InsertEntityInterface;
use Rabbit\ORM\Builders\Entities\SelectEntityInterface;
use Rabbit\ORM\Builders\Entities\UpdateEntityInterface;

/**
 * Interface QueryInterface
 * @package Rabbit\ORM\Builders
 */
interface QueryInterface
{

    public static function select(string $select = '') : SelectEntityInterface;

    public static function insert(string $insert = '') : InsertEntityInterface;

    public static function update(string $update = '') : UpdateEntityInterface;

}