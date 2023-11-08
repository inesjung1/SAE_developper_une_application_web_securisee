<?php
declare(strict_types=1);
namespace Iutncy\Sae;

require_once 'vendor/autoload.php';
use Iutncy\Sae\Dispatch\Dispatcher;
use Iutncy\Sae\Db\ConnectionFactory;

ConnectionFactory::setConfig("./config.ini");
$db = ConnectionFactory::makeConnection();
//création du cookie user
if (!isset($_COOKIE['user'])) {
    setcookie('user', "0", time() + 3600, '/');
}
$dispatcher = new Dispatcher();
$dispatcher->run();

