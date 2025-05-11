<?php

namespace Exchange;

use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $pth, $cf, $plugin_tx, $c, $pd_router;
        $pth = ["folder" => ["content" => "", "plugins" => ""]];
        $cf = ["headings" => ["show" => ""], "menu" => ["levels" => ""]];
        $plugin_tx = ["exchange" => ["menu_main" => ""]];
        $c = [];
        $pd_router = $this->createStub(PageDataRouter::class);
    }

    public function testMakesMainAdminController(): void
    {
        $this->assertInstanceOf(MainAdminController::class, Dic::mainAdminController());
    }

    public function testMakesInfoController(): void
    {
        $this->assertInstanceOf(InfoController::class, Dic::infoController());
    }
}
