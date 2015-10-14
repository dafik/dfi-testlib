<?php
namespace TestLib;

use Exception;

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
    private $pid;

    private $pageWidth = 1280;
    private $pageHeight = 1024;

    private $pidFile = './phantomjs.pid';

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
        $this->killServer();
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
        $this->killServer();
    }

    private function isPhantomRunning()
    {
        try {
            return @fsockopen("localhost", $this->port);
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

        $stat = proc_get_status($this->phantomProcess);

        $pid = $stat['pid'];
        $this->pid = $pid;

        $this->writePid($pid);

        register_shutdown_function(array($this, 'onShutdown'));

        sleep(1);
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    private function writePid($pid)
    {
        $filename = $this->pidFile;
        $res = file_put_contents($filename, $pid);
        if (!$res) {
            throw new Exception('can\'t write pid to file');
        }
    }

    private function getPid()
    {
        $filename = $this->pidFile;
        if (file_exists($filename)) {
            $pid = file_get_contents($filename);
            if (!$pid) {
                throw new Exception('can\'t write pid to file');
            }
            return $pid;
        }
    }

    private function killServer()
    {
        if ($this->phantomProcess) {
            proc_terminate($this->phantomProcess);
            proc_close($this->phantomProcess);
        }
        $this->pid = $this->getPid();
        if ($this->pid) {
            posix_kill($this->pid, SIGKILL);
        }
        if (file_exists($this->pidFile)) {
            unlink($this->pidFile);
        }
    }

    public function onShutdown()
    {
        if ($this->isPhantomRunning()) {
            $this->killServer();
        }
    }
}