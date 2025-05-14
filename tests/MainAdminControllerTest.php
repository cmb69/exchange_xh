<?php

namespace Exchange;

use ApprovalTests\Approvals;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\CsrfProtector;
use Plib\DocumentStore;
use Plib\FakeRequest;
use Plib\View;

class MainAdminControllerTest extends TestCase
{
    /** @var CsrfProtector&Stub */
    private $csrfProtector;

    /** @var DocumentStore */
    private $store;

    /** @var View */
    private $view;

    public function setUp(): void
    {
        vfsStream::setup("root");
        $this->csrfProtector = $this->createStub(CsrfProtector::class);
        $this->csrfProtector->method("token")->willReturn("123456789ABCDEF");
        $this->store = new DocumentStore(vfsStream::url("root/"));
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["exchange"]);
    }

    private function sut(): MainAdminController
    {
        return new MainAdminController(
            $this->csrfProtector,
            $this->store,
            $this->view
        );
    }

    public function testShowsOverview(): void
    {
        $request = new FakeRequest(["url" => "http://example.com/?&exchange&admin=plugin_main&action=plugin_tx"]);
        $response = $this->sut()($request);
        $this->assertSame("Import/Export", $response->title());
        Approvals::verifyHtml($response->output());
    }

    public function testExportIsCsrfProtected(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized for this action!", $response->output());
    }

    public function testReportsFailureToExport(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Exporting the contents failed!", $response->output());
    }

    public function testSuccessfulExportRedirects(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=export",
        ]);
        $response = $this->sut()($request);
        $this->assertFileExists(vfsStream::url("root/content.xml"));
        $this->assertSame(
            "http://example.com/?&exchange&admin=plugin_main&action=exported",
            $response->location()
        );
    }

    public function testShowsExportSuccessMessageAfterRedirect(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=exported",
        ]);
        $response = $this->sut()($request);
        $this->assertSame("Import/Export", $response->title());
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
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized for this action!", $response->output());
    }

    public function testReportsFailureToImport(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Importing the contents failed!", $response->output());
    }

    public function testSuccessfulImportRedirects(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import",
        ]);
        $response = $this->sut()($request);
        $this->assertFileExists(vfsStream::url("root/content.htm"));
        $this->assertSame(
            "http://example.com/?&exchange&admin=plugin_main&action=imported",
            $response->location()
        );
    }

    public function testShowsImportSuccessMessageAfterRedirect(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=imported",
        ]);
        $response = $this->sut()($request);
        $this->assertSame("Import/Export", $response->title());
        $this->assertStringContainsString(
            "The contents have been imported.",
            $response->output()
        );
    }

    public function testXh16ImportIsCsrfProtected(): void
    {
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import16",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("You are not authorized for this action!", $response->output());
    }

    public function testReportsFailureToImportXh16(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        vfsStream::setQuota(0);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import16",
        ]);
        $response = $this->sut()($request);
        $this->assertStringContainsString("Importing the contents failed!", $response->output());
    }

    public function testSuccessfulXh16ImportRedirects(): void
    {
        $this->csrfProtector->method("check")->willReturn(true);
        $request = new FakeRequest([
            "url" => "http://example.com/?&exchange&admin=plugin_main&action=import16",
        ]);
        $response = $this->sut()($request);
        $this->assertFileExists(vfsStream::url("root/content.htm"));
        $this->assertSame(
            "http://example.com/?&exchange&admin=plugin_main&action=imported",
            $response->location()
        );
    }
}
