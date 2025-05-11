<?php

namespace Exchange;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\CsrfProtector;
use Plib\FakeRequest;
use Plib\View;

class MainAdminControllerTest extends TestCase
{
    /** @var CsrfProtector&Stub */
    private $csrfProtector;

    /** @var ExchangeService */
    private $exchangeService;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->csrfProtector = $this->createStub(CsrfProtector::class);
        $this->exchangeService = new ExchangeService("./content/content.xml");
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["exchange"]);
    }

    private function sut(): MainAdminController
    {
        return new MainAdminController($this->csrfProtector, $this->exchangeService, $this->view);
    }

    public function testShowsOverview(): void
    {
        $request = new FakeRequest();
        $response = $this->sut()->defaultAction($request);
        Approvals::verifyHtml($response->output());
    }
}
