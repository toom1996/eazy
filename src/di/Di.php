<?php

namespace eazy\di;

use DI\Container;
use DI\ContainerBuilder;
use eazy\base\BaseObject;
use function DI\create;

class Di extends BaseObject
{

    /**
     * @var ContainerBuilder
     */
    private static $builder;

    /**
     * @var Container
     */
    public static $container;

    private $_singletons = [];

    public function init()
    {
        static::$container = new Container;
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function get($class, $params = [], $config = [])
    {
        if (isset($this->_singletons[$class])) {
            // singleton
            return static::$container->get($class);
            return $this->_singletons[$class];
        } elseif (!isset($this->_definitions[$class])) {


        }
    }

    public function normalizeDefinition($class, $definition)
    {
//        if (empty($definition)) {
//            return ['class' => $class];
//        } elseif (is_string($definition)) {
//            return ['class' => $definition];
//        } else
          if (is_array($definition)) {
            if (!isset($definition['class'])) {
                if (strpos($class, '\\') !== false) {
                    $definition['class'] = $class;
                } else {
                    throw new InvalidConfigException('A class definition requires a "class" member.');
                }
            }
//            var_dump($definition);
            return $definition;
        }
    }


    public function set($class, $definition = [])
    {
        $this->_singletons[$class] = $this->normalizeDefinition($class, $definition);
        static::$container->set($class,create($this->_singletons[$class]['class'])->constructor());
        var_dump(static::$container->get('urlManager'));
//        $this->_params[$class] = $params;
//        unset($this->_singletons[$class]);
        return $this;
    }
    
    public function build()
    {
        
    }
}