<?php

namespace eazy\console;

use eazy\base\BaseConsole;
use eazy\base\BootstrapCommandInterface;
use eazy\Eazy;
use Symfony\Component\Console\Command\Command;

class Console extends BaseConsole
{
    public function init()
    {
        try {
            foreach ($this->extensions as $v) {
                $bootstrapClass = $v['bootstrap'];
                $ref = new \ReflectionClass($bootstrapClass);
                if ($ref->implementsInterface(BootstrapCommandInterface::class)) {
                    $bootstrap = $ref->getMethod('bootstrap');
                    $bootstrap->invokeArgs($ref->newInstance(),[&$this, $v['name']]);
                }
            }
        }catch (\Throwable $exception) {
            
        }
    }
}