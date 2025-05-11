<?php

namespace Exchange;

use ApprovalTests\Approvals;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use XH\PageDataRouter;
use XH\Pages;

class ExportServiceTest extends TestCase
{
    /** @var Pages&Stub */
    private $pages;

    /** @var PageDataRouter&Stub */
    private $pdRouter;

    public function setUp(): void
    {
        vfsStream::setup("root");
        $this->pages = $this->createStub(Pages::class);
        $this->pages->method("toplevels")->willReturn([0]);
        $this->pages->method("children")->willReturnMap([
            [0, false, [1]],
            [1, false, []],
        ]);
        $this->pages->method("content")->willReturnMap([
            [0, "<!--XH_ml1:Start-->\n<h1>Start<h1>"],
            [1, "<!--XH_ml1:Sub-->\n<h1>Sub<h1>"],
        ]);
        $this->pdRouter = $this->createStub(PageDataRouter::class);
        $this->pdRouter->method("find_page")->willReturnMap([
            [0, ["url" => "Start"]],
            [1, ["url" => "Sub"]],
        ]);
    }

    private function sut(): ExportService
    {
        return new ExportService(
            vfsStream::url("root/content.xml"),
            9,
            true,
            $this->pages,
            $this->pdRouter
        );
    }

    public function testExportsContents(): void
    {
        $result = $this->sut()->export();
        $this->assertTrue($result);
        Approvals::verifyStringWithFileExtension(file_get_contents(vfsStream::url("root/content.xml")), "xml");
    }
}
