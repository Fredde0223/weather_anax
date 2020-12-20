<?php

namespace Fredde\DI;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * ValCheck test class.
 */
class DIWeatherTest extends TestCase
{
    protected $di;

    /**
     * Setup $di
     */
    protected function setUp()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        //$di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");
        $this->di = $di;
    }

    /**
     * testing if class is created correctly through DI
     */
    public function testClass()
    {
        $class = $this->di->get("weather");
        $this->assertInstanceOf("\Fredde\DI\DIWeather", $class);
    }

    /**
     * testing to get some geo info using an IP
     */
    public function testGetLocation()
    {
        $class = $this->di->get("weather");

        //$locationinfo = $class->getLocation("8.8.8.8");
        //$this->assertEquals($locationinfo[2], "Mountain View");

        $locationinfo = $class->getLocation("hejsan");
        $this->assertIsArray($locationinfo);
    }

    /**
     * testing if coordinates are correct
     */
    public function testCheckCoords()
    {
        $class = $this->di->get("weather");

        $check = $class->checkCoords("50", "50");
        $this->assertEquals($check, true);

        $check = $class->checkCoords(null, null);
        $this->assertEquals($check, false);

        $check = $class->checkCoords("hej", "hej");
        $this->assertEquals($check, false);
    }

    /**
     * testing error messages
     */
    public function testErrMsg()
    {
        $class = $this->di->get("weather");

        $msg = $class->errMsg("ipcheck");
        $this->assertIsString($msg);

        $msg = $class->errMsg("coordcheck");
        $this->assertIsString($msg);

        $msg = $class->errMsg("urlcheck");
        $this->assertIsString($msg);

        $msg = $class->errMsg("hej");
        $this->assertIsString($msg);
    }

    /**
     * testing multicurl to get weather info
     */
    public function testGetWeather()
    {
        $class = $this->di->get("weather");

        $urlarray = $class->getHistoryUrls("48.86", "2.35");
        $weatherinfo = $class->getWeather($urlarray);
        $this->assertIsArray($weatherinfo);
    }

    /**
     * testing functions getting URL-arrays
     */
    public function testUrlGets()
    {
        $class = $this->di->get("weather");

        $urlarray = $class->getHistoryUrls("48.86", "2.35");
        $this->assertIsArray($urlarray);

        $urlarray = $class->getForecastUrls("48.86", "2.35");
        $this->assertIsArray($urlarray);
    }

    /**
     * testing function getting string for printing map
     */
    public function testGetOSM()
    {
        $class = $this->di->get("weather");

        $mapstring = $class->getOSM("48.86", "2.35");
        $this->assertIsString($mapstring);
    }
}
