<?php
declare(strict_types=1);
namespace Iutncy\Sae;

require_once 'vendor/autoload.php';
use Iutncy\Sae\Dispatch\Dispatcher;
use Iutncy\Sae\Db\ConnectionFactory;

ConnectionFactory::setConfig("./config.ini");
$db = ConnectionFactory::makeConnection();
$dispatcher = new Dispatcher();
$dispatcher->run();