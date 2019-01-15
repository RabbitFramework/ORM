<?php

namespace Rabbit\ORM\globals;

use Rabbit\ORM\DatabaseConfigContainer;
use Rabbit\ORM\Repository\EntityConfigContainer;

$GLOBALS['databaseConfig'] = DatabaseConfigContainer::getInstance();
$GLOBALS['repositoryEntityConfig'] = EntityConfigContainer::getInstance();
