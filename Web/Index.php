<?php
declare(strict_types=1);
namespace Iutncy\Sae;

require_once 'vendor/autoload.php';
use Iutncy\Sae\Dispatch\Dispatcher;

$dispatcher = new Dispatcher();
$dispatcher->run();