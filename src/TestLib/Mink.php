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
    private $port;

    /**
     * @var PhantomJSDriver
     */
    private $driver;


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

    private function __construct()
    {

    }

    public function setUp($port)
    {
        $this->port = $port;

        $phantomServer = 'http://localhost:' . $this->port . '/api';
        $templateCache = ROOT . "/tmp/phantomjs";

        $this->driver = new PhantomJSDriver($phantomServer, $templateCache);

    }

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


    public function ss($alias = null)
    {

        $bt = debug_backtrace(false);

        $from = $bt[1]['class'] . '::' . $bt[1]['function'];
        $from = str_replace('\\', ':', $from);


        $screenShot = $this->driver->getScreenshot();
        $path = str_replace('\\', '/', ROOT . '/tmp/img/' . microtime(true) . '-' . $from . ($alias ? '--' . $alias : '') . '.jpg');
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $wRes = file_put_contents($path, $screenShot);
    }

}