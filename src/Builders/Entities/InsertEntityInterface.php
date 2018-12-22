<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:44
 */

namespace Rabbit\ORM\Builders\Entities;


interface InsertEntityInterface extends BaseEntityInterface
{

    public function insert(string $name = '');

    public function column(string ...$names);

    public function values(string ...$values);

    public function getInsert() : string;

    public function getColumns() : string;

    public function getValues() : string;

}