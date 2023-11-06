<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
class DefaultAction extends Action {
    public function __construct() {}
    public function execute(): string {
        return "Test";
    }
}