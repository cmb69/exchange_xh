<?php

namespace Exchange;

use ApprovalTests\Approvals;
use PHPUnit\Framework\MockObject\Stub;
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

    /** @var ExportService&Stub */
    private $exportService;

    /** @var ImportService&Stub */
    private $importService;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        $this->csrfProtector = $this->createStub(CsrfProtector::class);
        $this->csrfProtector->method("token")->willReturn("123456789ABCDEF");
        $this->exchangeService = new ExchangeService("./content/content.xml");
        $this->exportService = $this->createStub(ExportService::class);
        $this->importService = $this->createStub(ImportService::class);
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["exchange"]);
    }

    private function sut(): MainAdminController
    {
        return new MainAdminController(
            $this->csrfProtector,
            $this->exchangeService,
            $this->exportService,
            $this->importService,
            $this->view
        );
    }

    public function testShowsOverview(): void
    {
        $request = new FakeRequest();
        $response = $this->sut()->defaultAction($request);
        Approvals::verifyHtml($response->output());
    }
}
