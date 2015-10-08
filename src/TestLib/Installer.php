<?php
/**
 * Created by IntelliJ IDEA.
 * User: z.wieczorek
 * Date: 08.10.15
 * Time: 08:35
 */

namespace TestLib;

use Composer\Config;
use Composer\Script\Event;


class Installer
{
    public static function install(Event $event)
    {
        $ds = DIRECTORY_SEPARATOR;
        $dirs = [
            'log',
            'tests',
            'tests' . $ds . 'integration',
            'tmp',
            'tmp' . $ds . 'phantomjs'
        ];

        $coposer = $event->getComposer();
        $config = $coposer->getConfig();
        $vendorDir = $config->get('vendor-dir');
        $baseDir = getcwd();

        foreach ($dirs as $dir) {
            $path = $baseDir . $ds . $dir;
            if (!file_exists($path)) {
                mkdir($path);
            }
        }

        $templateDir = $vendorDir . $ds . 'dafik' . $ds . 'testlib' . $ds . 'template';

        $files = [
            $templateDir . $ds . 'bootstrap.php' => $baseDir . $ds . 'bootstrap.php',
            $templateDir . $ds . 'phpunit.xml' => $baseDir . $ds . 'phpunit.xml',
            $templateDir . $ds . 'ExampleTest.php' => $baseDir . $ds . 'tests' . $ds . 'integration' . $ds . 'ExampleTest.php',
        ];

        foreach ($files as $src => $dst) {

            if (!file_exists($dst)) {
                copy($src, $dst);
            }
        }

    }
}