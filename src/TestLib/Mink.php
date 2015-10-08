<?php
/**
 * Created by IntelliJ IDEA.
 * User: z.wieczorek
 * Date: 07.10.15
 * Time: 13:41
 */

namespace TestLib;


use Zumba\Mink\Driver\PhantomJSDriver;

class Mink
{
    /**
     * @var Mink
     */
    private static $instance;
    private $port;
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
        $session = new \Behat\Mink\Session($this->driver);
        $session->start();

        return $session;
    }

    public function ss()
    {

        $bt = debug_backtrace(false);

        $from = $bt[1]['class'] . '::' . $bt[1]['function'];


        $screenShot = $this->driver->getScreenshot();
        $path = ROOT . '/tmp/' . $from . '-' . microtime() . '.jpg';
        $wRes = file_put_contents($path, $screenShot);
    }

}