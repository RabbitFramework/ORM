<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 10:59
 */

namespace Rabbit\ORM\Builders;

use Rabbit\ORM\Builders\Entities\AlterColumnEntityInterface;
use Rabbit\ORM\Builders\Entities\CreateColumnEntityInterface;
use Rabbit\ORM\Builders\Entities\InsertEntityInterface;
use Rabbit\ORM\Builders\Entities\SelectEntityInterface;
use Rabbit\ORM\Builders\Entities\Sql\MultiRequest;
use Rabbit\ORM\Builders\Entities\UpdateEntityInterface;
use Rabbit\ORM\Builders\Entities\Sql\AlterColumn;
use Rabbit\ORM\Builders\Entities\Sql\CreateColumn;
use Rabbit\ORM\Builders\Entities\Sql\Insert;
use Rabbit\ORM\Builders\Entities\Sql\Select;
use Rabbit\ORM\Builders\Entities\Sql\Update;

class Sql implements BuilderInterface
{

    public static function select(string $select = '') : SelectEntityInterface {
        return new Select($select);
    }

    public static function insert(string $insert = '') : InsertEntityInterface {
        return new Insert($insert);
    }

    public static function update(string $update = '') : UpdateEntityInterface {
        return new Update($update);
    }

    public static function alterColumn(string $alter = '') : AlterColumnEntityInterface {
        return new AlterColumn($alter);
    }

    public static function createColumn(string $create = '') : CreateColumnEntityInterface {
        return new CreateColumn($create);
    }

}