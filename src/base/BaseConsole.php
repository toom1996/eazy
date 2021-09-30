<?php

namespace eazy\base;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class BaseConsole extends Application
{
    /**
     * @var array
     */
    public array $extensions = [];

    public function __construct(
        string $name = 'Eazy Console',
        string $version = '1.0.0 (dev)'
    ) {
        if (!$this->extensions) {
            $file = APP_PATH . '/vendor/eazysoft/extensions.php';
            $this->extensions = is_file($file) ? include $file : [];
        }
        $this->init();
        parent::__construct($name, $version);
    }

    public function init()
    {
        
    }
}