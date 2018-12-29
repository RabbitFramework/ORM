<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/12/2018
 * Time: 21:45
 */

namespace Rabbit\ORM\Builders\Entities;


interface AlterColumnEntityInterface extends BaseEntityInterface
{
    public function table(string $table = '');

    public function column(string $name = '');

    public function datatype(string $type = '');

    public function getAlter(): string;

    public function getAdd(): string;
}