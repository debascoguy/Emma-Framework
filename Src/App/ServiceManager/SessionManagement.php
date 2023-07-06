<?php
namespace Emma\App\ServiceManager;

use Emma\Common\Singleton\Singleton;
use Emma\Http\Request\Containers\SessionContainer;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class SessionManagement extends AbstractService implements \IteratorAggregate, \Countable
{
    use Singleton;

    /**
     * @var array|SessionContainer
     */
    protected array|SessionContainer $session = [];

    /**
     * @return SessionManagement
     */
    public static function getInstance(): static
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        self::$instance->refreshSession();
        return self::$instance;
    }

    /**
     * @param int $expireAfter
     * Expire the session if user is inactive for 30 minutes or more.
     */
    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionContainer($_SESSION);
    }

    /**
     * @return array|SessionContainer
     */
    public function getSession(): SessionContainer|array
    {
        return $this->session;
    }

    /**
     * @param array|SessionContainer $session
     * @return SessionManagement
     */
    public function setSession(SessionContainer|array $session): static
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @param int $session_id
     * @return bool 
     */
    public function isValidsessionId($session_id): bool
    {
        return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
    }

    /**
     * @param int $expireAfter
     * @return $this
     */
    public function setExpireAfter($expireAfter = 30): static
    {
        $this->register("expire_after", $expireAfter);
        return $this;
    }

    /**
     * @return int
     */
    public function getExpireAfter(): int
    {
        return $this->session->get("expire_after");
    }

    /**
     * @return mixed
     */
    public function getIPAddress(): mixed
    {
        return $this->get("ip_address");
    }

    /**
     * @return string|null
     */
    public function getComputerName(): ?string
    {
        return $this->get("computer_name");
    }

    /**
     * @return bool
     */
    public function startSession(): bool
    {
        if (!$this->isSessionStarted()) {
            return session_start();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isSessionStarted(): bool
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE;
            } else {
                return session_id() === '';
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isActiveSession(): bool
    {
        $expireAfter = (int)$this->get("expire_after");
        //If $expireAfter==0, that is an unlimited session. i.e. Session will always be Active
        if ($expireAfter == 0) {
            return $this->refreshSession();
        }

        if (isset($_SESSION['last_action'])) {
            /** Figure out how many seconds have passed since the user was last active. */
            $secondsInactive = time() - $_SESSION['last_action'];
            /** Convert our minutes into seconds. */
            $expireAfterSeconds = $expireAfter * 60;
            /** Check to see if user have been inactive for too long. */
            if ($secondsInactive >= $expireAfterSeconds) {
                /** User has been inactive for too long. Kill their session. */
                $this->clear();
                $this->destroy();
                return false;
            }
            return $this->refreshSession();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function refreshSession(): bool
    {
        if ($this->startSession()) {
            //Assign the current timestamp as the user's latest activity
            $this->register('last_action', time());
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return session_id();
    }

    /**
     * @param $session_id
     * @return false|string
     */
    public function setSessionId($session_id): false|string
    {
        return session_id($session_id);
    }

    /**
     * @return string
     */
    public function getSessionName(): string
    {
        return session_name();
    }

    /**
     * @param string $session_name
     * @return false|string
     */
    public function setSessionName(string $session_name): false|string
    {
        return session_name($session_name);
    }

    /**
     * @param bool $delete_old_session
     * @return false|string
     */
    public function regenerateId(bool $delete_old_session = false): false|string
    {
        session_regenerate_id($delete_old_session);
        return session_id();
    }

    /**
     * @return string
     */
    public function encode(): string
    {
        return session_encode();
    }

    /**
     * @param $data
     * @return bool
     */
    public function decode($data): bool
    {
        return session_decode($data);
    }

    /**
     * @param $field
     * @return bool
     */
    public function has($field): bool
    {
        return $this->session->has($field);
    }

    /**
     * @param $field
     * @param $value
     */
    public function register($field, $value)
    {
        $this->session->register($field, $value);
    }

    /**
     * @param $data
     */
    public function setFlash($data)
    {
        $this->register("session-flash", $data);
    }

    /**
     * @return null
     */
    public function getFlash()
    {
        return $this->get("session-flash");
    }

    /**
     * @param $field
     * @param null $default
     * @return null
     */
    public function get($field = null, $default = null)
    {
        if (empty($field)) {
            return $this->session->getParameters();
        }
        return $this->has($field) ? $this->session->get($field) : $default;
    }

    /**
     * @return bool
     */
    public function reset()
    {
        $status = session_reset();
        if ($this->startSession()) {
            $this->session = new SessionContainer($_SESSION);
        }
        return $status;
    }

    /**
     *
     */
    public function clear()
    {
        session_unset();
        $this->getSession()->clear();
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        $status = session_destroy();
        $this->setSession(new SessionContainer($_SESSION));
        return $status;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->get());
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->session->getIterator();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->session->count();
    }


    public function __destruct() {
//        $_SESSION['expire_after'];
    }


}