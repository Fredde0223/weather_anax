<?php

namespace Fredde\DIController;

use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;

/**
 * ValController test class.
 */
class DIApiControllerTest extends TestCase
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
     * testing the index get
     */
    public function testIndGet()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $result = $contClass->indexActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $result);
    }

    /**
     * testing the weather get
     */
    public function testWeatherGet()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $result = $contClass->weatherActionGet();
        $this->assertIsArray($result);
    }

    /**
     * testing the weather post
     */
    public function testWeatherPost()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $result = $contClass->weatherActionPost();
        $this->assertIsArray($result);
    }

    /**
     * testing the weather get with IP
     */
    public function testWeatherGetIP()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $_GET['ip'] = '8.8.8.8';

        $result = $contClass->weatherActionGet();
        $this->assertIsArray($result);

        $_GET['ip'] = null;
    }

    /**
     * testing the weather get with coords
     */
    public function testWeatherGetCoords()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $_GET['lat'] = '25';
        $_GET['lon'] = '25';

        $result = $contClass->weatherActionGet();
        $this->assertIsArray($result);

        $_GET['lat'] = null;
        $_GET['lon'] = null;
    }


    /**
     * testing the weather post with IP
     */
    public function testWeatherPostIP()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $_POST['ipstring'] = '8.8.8.8';

        $result = $contClass->weatherActionPost();
        $this->assertIsArray($result);

        $_POST['ipstring'] = null;
    }

    /**
     * testing the weather post with coords
     */
    public function testWeatherPostCoords()
    {
        $contClass = new DIApiController();
        $contClass->setDI($this->di);

        $_POST['latstring'] = '25';
        $_POST['lonstring'] = '25';

        $result = $contClass->weatherActionPost();
        $this->assertIsArray($result);

        $_POST['latstring'] = null;
        $_POST['lonstring'] = null;
    }
}
