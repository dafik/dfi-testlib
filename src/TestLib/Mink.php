<?php
/**
 * Created by IntelliJ IDEA.
 * User: z.wieczorek
 * Date: 07.10.15
 * Time: 13:41
 */

namespace TestLib;


use Behat\Mink\Session;
use Zumba\Mink\Driver\PhantomJSDriver;

class Mink
{
    /**
     * @var Mink
     */
    private static $instance;
    /**
     * @var
     */
    private $port;

    /**
     * @var PhantomJSDriver
     */
    private $driver;

    /**
     * @var array
     */
    protected $cookies = [];


    /**
     * @return Mink
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Mink) {
            self::$instance = new Mink();
        }
        return self::$instance;
    }

    /**
     * Mink constructor.
     */
    protected function __construct()
    {

    }

    /**
     * @param string $port
     */
    public function setUp($port)
    {
        $this->port = $port;

        $phantomServer = 'http://localhost:' . $this->port . '/api';
        $templateCache = ROOT . '/tmp/phantomjs';

        $this->driver = new PhantomJSDriver($phantomServer, $templateCache);

    }

    /**
     * @return Session
     */
    public function getSession()
    {
        $session = new Session($this->driver);
        $session->start();

        return $session;
    }

    /**
     * @return PhantomJSDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }


    /**
     * @param string $alias
     * @throws \Behat\Mink\Exception\DriverException
     */
    public function ss($alias = null)
    {

        $bt = debug_backtrace(false);

        $from = $bt[1]['class'] . '::' . $bt[1]['function'];
        $from = str_replace('\\', ':', $from);


        $screenShot = $this->driver->getScreenshot();

        $time = microtime(true);
        list($time, $usec) = explode('.', $time);
        $d = new \DateTime(null,new \DateTimeZone('Europe/Warsaw'));
        $time = $d->format('Y-m-d H:i:s') . ':' . $usec;


        $path = str_replace('\\', '/', ROOT . '/tmp/img/' . $time . '-' . $from . ($alias ? '--' . $alias : '') . '.jpg');
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $wRes = file_put_contents($path, $screenShot);
        if (!$wRes) {
            throw new \DomainException('cant write ss');
        }
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
    }


    /**
     * @param string $name
     * @return string|null
     */
    public function getCookie($name)
    {
        if (array_key_exists($name, $this->cookies)) {
            return $this->cookies[$name];
        }
        return null;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setCookie($name, $value)
    {
        $this->cookies[$name] = $value;
    }


}