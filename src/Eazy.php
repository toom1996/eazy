<?php

namespace eazy;

use eazy\console\StdoutLogger;
use eazy\di\Di;
use eazy\http\exceptions\InvalidConfigException;
use eazy\http\exceptions\UnknownClassException;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutput;

class Eazy
{
    /**
     * @var StdoutLogger
     */
    private static $output;

    private static $aliases;

    /**
     * @var Di
     */
    public static $container;

    public static $classMap;

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

    public static function info($message)
    {
        self::getOutput()->log(LogLevel::INFO, $message);
    }

    public static function error($message)
    {
        self::getOutput()->log(LogLevel::ERROR, $message);
    }

    /**
     * Get alias.
     *
     * @param        $alias
     * @param  bool  $throwException
     *
     * @return bool|string
     */
    public static function getAlias($alias, $throwException = true)
    {
        if (strpos($alias, '@') !== 0) {
            // not an alias
            return $alias;
        }

        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);

        if (isset(static::$aliases[$root])) {
            if (is_string(static::$aliases[$root])) {
                return $pos === false ? static::$aliases[$root]
                    : static::$aliases[$root].substr($alias, $pos);
            }

            foreach (static::$aliases[$root] as $name => $path) {
                if (strpos($alias.'/', $name.'/') === 0) {
                    return $path.substr($alias, strlen($name));
                }
            }
        }

        return false;
    }


    /**
     * Registers a path alias.
     *
     * A path alias is a short name representing a long path (a file path, a URL, etc.)
     * For example, we use '@yii' as the alias of the path to the Yii framework directory.
     *
     * A path alias must start with the character '@' so that it can be easily differentiated
     * from non-alias paths.
     *
     * Note that this method does not check if the given path exists or not. All it does is
     * to associate the alias with the path.
     *
     * Any trailing '/' and '\' characters in the given path will be trimmed.
     *
     * See the [guide article on aliases](guide:concept-aliases) for more information.
     *
     * @param  string  $alias  the alias name (e.g. "@yii"). It must start with a '@' character.
     * It may contain the forward slash '/' which serves as boundary character when performing
     * alias translation by [[getAlias()]].
     * @param  string  $path  the path corresponding to the alias. If this is null, the alias will
     * be removed. Trailing '/' and '\' characters will be trimmed. This can be
     *
     * - a directory or a file path (e.g. `/tmp`, `/tmp/main.txt`)
     * - a URL (e.g. `http://www.yiiframework.com`)
     * - a path alias (e.g. `@yii/base`). In this case, the path alias will be converted into the
     *   actual path first by calling [[getAlias()]].
     *
     * @throws InvalidArgumentException if $path is an invalid alias.
     * @see getAlias()
     */
    public static function setAlias($alias, $path)
    {
        if (strncmp($alias, '@', 1)) {
            $alias = '@'.$alias;
        }
        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);
        if ($path !== null) {
            $path = strncmp($path, '@', 1) ? rtrim($path, '\\/')
                : static::getAlias($path);
            if ( ! isset(static::$aliases[$root])) {
                if ($pos === false) {
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [$alias => $path];
                }
            } elseif (is_string(static::$aliases[$root])) {
                if ($pos === false) {
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [
                        $alias => $path, $root => static::$aliases[$root],
                    ];
                }
            } else {
                static::$aliases[$root][$alias] = $path;
                krsort(static::$aliases[$root]);
            }
        } elseif (isset(static::$aliases[$root])) {
            if (is_array(static::$aliases[$root])) {
                unset(static::$aliases[$root][$alias]);
            } elseif ($pos === false) {
                unset(static::$aliases[$root]);
            }
        }
    }

    /**
     * 实例化对象
     * `Eazy::createObject('classname', [
     *      'foo' => 'bar',
     * ]);`
     *
     * `Eazy::createObject([
     *      'class' => classname
     *      'foo' => 'bar',
     * ]);`
     *
     * @param         $type
     * @param  array  $params
     *
     * @return \#o#Э#A#M#C\eazy\di\Di.get.0|mixed|void
     * @throws InvalidConfigException
     */
    public static function createObject($type, $params = [])
    {
        if(is_array($type) && isset($type['class'])) {
            $params = $type;
            $type = $params['class'];
            unset($params['class']);
        }

        if (is_string($type)) {
            $ref = new \ReflectionClass($type);
            return $ref->newInstanceArgs([$params]);
        }
    }
    
    public static function autoload($className)
    {
        if (isset(static::$classMap[$className])) {
            $classFile = static::$classMap[$className];
            if ($classFile[0] === '@') {
                $classFile = static::getAlias($classFile);
            }
        } elseif (strpos($className, '\\') !== false) {
            $classFile = static::getAlias('@' . str_replace('\\', '/', $className) . '.php', false);
            if ($classFile === false || !is_file($classFile)) {
                return;
            }
        } else {
            return;
        }

        include $classFile;

        if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
            throw new UnknownClassException("Unable to find '$className' in file: $classFile. Namespace missing?");
        }
    }
}