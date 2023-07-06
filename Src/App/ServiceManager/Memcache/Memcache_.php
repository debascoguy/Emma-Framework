<?php

namespace Emma\App\ServiceManager\Memcache;

use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\Common\Singleton\Interfaces\SingletonInterface;

/**
 * @Author: Ademola Aina
 * User: ademola.aina
 * Date: 7/29/2016
 * Time: 9:10 AM
 */
class Memcache_ extends \Memcache implements SingletonInterface
{
    /**
     * @var ConnectionProperty
     */
    private $connectionProperty;

    /**
     * @var bool
     */
    private $cacheAvailable = false;

    /**
     * @var Memcache_|null
     */
    private static $_instance = null;


    /**
     * @param ConnectionProperty $connectionProperty
     */
    public function __construct(ConnectionProperty $connectionProperty = null)
    {
        if (!$connectionProperty instanceof ConnectionProperty) {
            $connectionProperty = ConnectionProperty::createFromDefault();
        }
        //Single Server Connection
        $this->connect($connectionProperty);
        if (!$this->isCacheAvailable()) {
            //Multi-Server Connection.
            $this->addServers(array(
                $connectionProperty, //Server 1
            ));
        }
        self::$_instance = $this;
    }


    /**
     * @param ConnectionProperty|null $connectionProperty
     * @return Memcache_|null
     */
    public static function getInstance(ConnectionProperty $connectionProperty = null)
    {
        if (!self::$_instance) {
            self::$_instance = new self($connectionProperty);
        }
        return self::$_instance;
    }


    /**
     * @param ConnectionProperty[]|array $multi_app_memcache
     * @return bool
     */
    public function addServers($multi_app_memcache = array())
    {
        if (count($multi_app_memcache) == 0) {
            $connectionProperty = ConnectionProperty::createFromDefault();
            $this->cacheAvailable = $this->addserver($connectionProperty->getHost(), $connectionProperty->getPort());
        } else {
            $this->cacheAvailable = true;
            foreach ($multi_app_memcache as $connectionProperty) {
                $this->addserver($connectionProperty->getHost(), $connectionProperty->getPort());
            }
        }
        return $this->cacheAvailable;
    }

    /**
     * @param ConnectionProperty $connectionProperty
     * @return bool
     * @throws BaseException
     */
    public function connect(ConnectionProperty $connectionProperty)
    {
        if (!$connectionProperty instanceof ConnectionProperty) {
            throw new BaseException("Invalid Parameter: Memcache ConnectionProperty", 409);
        }
        $this->cacheAvailable = parent::connect($connectionProperty->getHost(),
            $connectionProperty->getPort(),
            $connectionProperty->getTimeout()
        );
        return $this->cacheAvailable;
    }

    /**
     * @return boolean
     */
    public function isCacheAvailable()
    {
        return $this->cacheAvailable;
    }

    /**
     * @param boolean $cacheAvailable
     * @return Memcache_
     */
    public function setCacheAvailable($cacheAvailable)
    {
        $this->cacheAvailable = $cacheAvailable;
        return $this;
    }

    /**
     * @param $key
     * @param $val
     * @param null $expire
     * @param bool $compress
     * @return bool
     */
    public function setOrReplace($key, $val, $expire = null, $compress = true)
    {
        if (!parent::replace($key, $val, ($compress ? MEMCACHE_COMPRESSED : null), $expire)) {
            return parent::set($key, $val, ($compress ? MEMCACHE_COMPRESSED : null), $expire);
        }
        return true;
    }

    /**
     * @return ConnectionProperty
     */
    public function getConnectionProperty()
    {
        return $this->connectionProperty;
    }

    /**
     * @param ConnectionProperty $connectionProperty
     * @return Memcache_
     */
    public function setConnectionProperty($connectionProperty)
    {
        $this->connectionProperty = $connectionProperty;
        return $this;
    }

}