<?php
namespace Emma\App\ServiceManager\Memcache;


/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class ConnectionProperty
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var int
     */
    protected $timeout = 1;

    /**
     * @param $host
     * @param $port
     * @param int $timeout
     */
    public function __construct($host, $port, $timeout = 1)
    {
        $this->setHost($host)->setPort($port)->setTimeout($timeout);
    }

    /**
     * @return ConnectionProperty
     */
    public static function createFromDefault()
    {
        $app_memcache['host'] = $_SERVER["REMOTE_ADDR"];
        $app_memcache['port'] = 11211;
        return new self($app_memcache['host'], $app_memcache['port']);
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return ConnectionProperty
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return ConnectionProperty
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return ConnectionProperty
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

}