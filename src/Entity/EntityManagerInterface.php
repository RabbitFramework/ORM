<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09/01/2019
 * Time: 15:44
 */

namespace Rabbit\ORM\Repository;


interface EntityManagerInterface
{

    public function persist(&$entity);

    public function retrieve(int $id): EntityInterface;

    public function delete(int $id);

    public function has(int $id);

    public function getRepository(string $entityName) : object;

    public function getRepositoryName(string $entityName) : string;

    public function getEntity(string $entityName) : object;

    public function getEntityName(string $entityName) : string;
}