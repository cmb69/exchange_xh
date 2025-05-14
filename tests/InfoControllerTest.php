<?php

namespace Exchange;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\FakeSystemChecker;
use Plib\View;

class InfoControllerTest extends TestCase
{
    /** @var array<string,string> */
    private $lang;

    /** @var SystemChecker */
    private $systemChecker;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->systemChecker = new FakeSystemChecker();
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["exchange"];
        $this->view = new View("./views/", $this->lang);
    }

    private function sut(): InfoController
    {
        return new InfoController(
            "./plugins/exchange/",
            $this->systemChecker,
            $this->view
        );
    }

    public function testShowsPluginInfo(): void
    {
        $response = $this->sut()();
        $this->assertSame("Exchange 2.0", $response->title());
        Approvals::verifyHtml($response->output());
    }
}
