<?php

/**
 * Created by IntelliJ IDEA.
 * User: z.wieczorek
 * Date: 07.10.15
 * Time: 15:31
 */
class ExampleTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobalsBlacklist = array('mink');

    public function testLogin()
    {
        $baseUrl = 'http://localhost/';


        $mink = \TestLib\Mink::getInstance();
        $session = $mink->getSession();


        $session->visit($baseUrl);
        $mink->ss();
    }
}
