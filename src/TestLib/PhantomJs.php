<?php
namespace TestLib;

/**
 * Class PhantomJs
 * @package TestLib
 */
class PhantomJs
{
    /**
     * @var PhantomJs
     */
    private static $instance;
    /**
     * @var bool|resource
     */
    private $phantomProcess = false;
    private $port = 8510;

    private $pageWidth = 1280;
    private $pageHeight = 1024;

    /**
     * @return PhantomJs
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof PhantomJs) {
            self::$instance = new PhantomJs();
        }
        return self::$instance;
    }

    /**
     *
     */
    private function __construct()
    {
        if (!$this->isPhantomRunning()) {
            $this->runPhantomServer();
        } else {
            throw new Exception('alredy running');
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->phantomProcess) {
            proc_terminate($this->phantomProcess);
            proc_close($this->phantomProcess);
        }
    }

    private function isPhantomRunning()
    {
        try {
            return fsockopen("localhost", $this->port);
        } catch (Exception $e) {
        }
    }

    private function runPhantomServer()
    {
        $command = 'exec bin/phantomjs --ssl-protocol=any --ignore-ssl-errors=true vendor/jcalderonzumba/gastonjs/src/Client/main.js ' . $this->port . ' ' . $this->pageWidth . ' ' . $this->pageHeight;

        $descriptors = [
            ["pipe", "r"],
            ["file", ROOT . "/log/ph-output.txt", "a"],
            ["file", ROOT . "/log/ph-error.txt", "a"]
        ];


        $this->phantomProcess = proc_open($command, $descriptors, $pipes, ROOT);
        sleep(1);
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }
}