<?php

namespace eazy\base;

use Symfony\Component\Console\Application;

interface BootstrapCommandInterface
{

    /**
     * @param Application  $console
     *
     * @return mixed
     */
    public function bootstrap(Application &$console);
}