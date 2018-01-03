<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Variables;

use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Variables\Specific\Cookie;
use MS\LightFramework\Variables\Specific\Env;
use MS\LightFramework\Variables\Specific\Files;
use MS\LightFramework\Variables\Specific\Get;
use MS\LightFramework\Variables\Specific\Post;
use MS\LightFramework\Variables\Specific\Server;
use MS\LightFramework\Variables\Specific\Session;
use MS\LightFramework\Variables\Specific\Url;


/**
 * Class Variables
 *
 * @package MS\LightFramework\Variables
 */
final class Variables
{
    const   TYPE_INT = 1;
    const   TYPE_FLOAT = 2;
    const   TYPE_STRING = 3;
    const   TYPE_ARRAY = 4;
    const   TYPE_JSON_DECODED = 5;
    const   TYPE_AUTO = 99;

    private static $instance;
    private $handlers = [];

    /**
     * This method returns class instance.
     *
     * @return Variables
     */
    public static function getInstance(): Variables
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * Variables constructor.
     */
    private function __construct()
    {
        $this->handlers['cookie'] = Cookie::getInstance();
        $this->handlers['env'] = Env::getInstance();
        $this->handlers['files'] = Files::getInstance();
        $this->handlers['get'] = Get::getInstance();
        $this->handlers['post'] = Post::getInstance();
        $this->handlers['server'] = Server::getInstance();
        $this->handlers['session'] = Session::getInstance();
        $this->handlers['url'] = Url::getInstance();
    }

    /**
     * Class destructor
     */
    public function __destruct()
    {
        $this->handlers = [];
    }

    /**
     * __clone overload
     */
    public function __clone()
    {
        throw new RuntimeException('Clone of '.__CLASS__.' is not allowed');
    }

    /**
     * __get overload
     *
     * @param null $name
     * @return mixed
     */
    public function __get($name = null)
    {
        if (!isset($this->handlers[$name])) {
            $exMsg = \sprintf(
                'Unknown handler: %s, allowed handlers are: %s',
                $name,
                \implode(', ', \array_keys($this->handlers))
            );
            throw new InvalidArgumentException($exMsg);
        }
        return $this->handlers[$name];
    }

    /**
     * __set overload
     *
     * @param $name
     * @param $val
     */
    public function __set($name, $val): void
    {
        throw new RuntimeException('Setting properties of '.__CLASS__.' is not allowed');
    }

    /**
     * __unset overload
     *
     * @param $name
     */
    public function __unset($name): void
    {
        throw new RuntimeException('Unsetting properties of '.__CLASS__.' is not allowed');
    }
}
