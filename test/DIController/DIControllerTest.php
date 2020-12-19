<?php

namespace Fredde\DIController;

use Anax\DI\DIFactoryConfig;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;

/**
 * ValController test class.
 */
class DIControllerTest extends TestCase
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
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");
        $this->di = $di;
    }

    /**
     * testing the index get
     */
    public function testGet()
    {
        $contClass = new DIController();
        $contClass->setDI($this->di);

        $result = $contClass->indexActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $result);
    }

    /**
     * testing the index post with ipcheck and valid IP
     */
    public function testPostIP()
    {
        $contClass = new DIController();
        $contClass->setDI($this->di);

        $_POST['ipcheck'] = 'check';
        $_POST['ipstring'] = '8.8.8.8';

        $result = $contClass->indexActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $result);

        $_POST['ipcheck'] = null;
        $_POST['ipstring'] = null;
    }

    /**
     * testing the index post with coordcheck
     */
    public function testPostCoords()
    {
        $contClass = new DIController();
        $contClass->setDI($this->di);

        $_POST['coordcheck'] = 'check';

        $result = $contClass->indexActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $result);

        $_POST['coordcheck'] = null;
    }
}
