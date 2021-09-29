<?php

namespace eazy;

use eazy\console\StdoutLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

class Eazy
{
    /**
     * @var StdoutLogger
     */
    private static $output;

    /**
     * Configures an object with the initial property values.
     * @param $object
     * @param $properties
     *
     * @return mixed
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }

    public static function getOutput()
    {
        if (self::$output === null) {
            self::$output = new StdoutLogger();
        }

        return self::$output;
    }

    public static function info()
    {
        self::getOutput()->log('info');
    }

    public static function error()
    {
        self::getOutput()->log('error');
    }
}