<?php

namespace eazy\base;

use eazy\Eazy;

class BaseObject
{
    /**
     * BaseObject constructor.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            Eazy::configure($this, $config);
        }
        $this->init();
    }

    public function init()
    {
    }
}