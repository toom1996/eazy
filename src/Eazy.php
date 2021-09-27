<?php

namespace eazy;

class Eazy
{
    public static function __callStatic($name, $arguments)
    {
        var_dump($name);
        var_dump($arguments);
        // TODO: Implement __callStatic() method.
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function getVersion()
    {
        return '1.0.1';
    }
}