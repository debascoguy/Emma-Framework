<?php
namespace Emma\App\ServiceManager\Memcache;

use Emma\Common\Singleton\Interfaces\SingletonInterface;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/22/2017
 * Time: 12:53 AM
 */
class Memcached_ extends \Memcached implements SingletonInterface
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
     * @var Memcached_|null
     */
    private static $_instance = null;

    /**
     * @param ConnectionProperty|null $connectionProperty
     * @return Memcached_|null
     */
    public static function getInstance(ConnectionProperty $connectionProperty = null)
    {
        if (!self::$_instance) {
            self::$_instance = new self($connectionProperty);
        }
        return self::$_instance;
    }

    /**
     * @param ConnectionProperty|null $connectionProperty
     */
    public function __construct(ConnectionProperty $connectionProperty = null)
    {
        parent::__construct();
        if (!$connectionProperty instanceof ConnectionProperty) {
            $connectionProperty = ConnectionProperty::createFromDefault();
        }
        //Single Server Connection
        $this->addServer($connectionProperty->getHost(), $connectionProperty->getPort());
        if ($this->isCacheAvailable() == false) {
            //Multi-Server Connection.
            $this->addServers(array(
                array($connectionProperty->getHost(), $connectionProperty->getPort()), //Server 1
            ));
        }
        self::$_instance = $this;
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
     * @return Memcached_
     */
    public function setConnectionProperty($connectionProperty)
    {
        $this->connectionProperty = $connectionProperty;
        return $this;
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
     * @return Memcached_
     */
    public function setCacheAvailable($cacheAvailable)
    {
        $this->cacheAvailable = $cacheAvailable;
        return $this;
    }


}