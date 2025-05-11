<?php

namespace Exchange;

use PHPUnit\Framework\TestCase;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $pth, $cf, $plugin_tx, $c;
        $pth = ["folder" => ["content" => "", "plugins" => ""]];
        $cf = ["headings" => ["show" => ""], "menu" => ["levels" => ""]];
        $plugin_tx = ["exchange" => ["menu_main" => ""]];
        $c = [];
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
