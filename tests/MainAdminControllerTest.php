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

    public function testExportIsCsrfProtected(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()->exportAction($request);
        $this->assertSame("not authorized", $response->output());
    }

    public function testReportsFailureToExport(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $this->exportService->method("export")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()->exportAction($request);
        $this->assertStringContainsString("Exporting the contents failed!", $response->output());
    }

    public function testSuccessfulExportRedirects(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $this->exportService->method("export")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()->exportAction($request);
        $this->assertSame(
            "http://example.com/?&exchange&admin=plugin_main&action=exported&normal",
            $response->location()
        );
    }

    public function testShowsExportSuccessMessageAfterRedirect(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=exported",
        ]);
        $response = $this->sut()->exportedAction($request);
        $this->assertStringContainsString(
            "The current content file has been exported.",
            $response->output()
        );
    }

    public function testImportIsCsrfProtected(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import",
        ]);
        $response = $this->sut()->importAction($request);
        $this->assertSame("not authorized", $response->output());
    }

    public function testReportsFailureToImport(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $this->importService->method("import")->willReturn(false);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import",
        ]);
        $response = $this->sut()->importAction($request);
        $this->assertStringContainsString("Importing the contents failed!", $response->output());
    }

    public function testSuccessfulImportRedirects(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $this->importService->method("import")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import",
        ]);
        $response = $this->sut()->importAction($request);
        $this->assertSame(
            "http://example.com/?&exchange&admin=plugin_main&action=imported&normal",
            $response->location()
        );
    }

    public function testShowsImportSuccessMessageAfterRedirect(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=imported",
        ]);
        $response = $this->sut()->importedAction($request);
        $this->assertStringContainsString(
            "The contents have been imported.",
            $response->output()
        );
    }
}
