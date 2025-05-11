<?php

namespace Exchange;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\View;

class InfoControllerTest extends TestCase
{
    /** @var array<string,string> */
    private $lang;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->lang = XH_includeVar("./languages/en.php", "plugin_tx")["exchange"];
        $this->view = new View("./views/", $this->lang);
    }

    private function sut(): InfoController
    {
        return new InfoController(
            "./plugins/exchange/",
            $this->lang,
            $this->view
        );
    }

    public function testShowsPluginInfo(): void
    {
        $response = $this->sut()();
        Approvals::verifyHtml($response);
    }
}
