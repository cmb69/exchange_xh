<?php

namespace Exchange\Model;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

/** @small */
class ContentsTest extends TestCase
{
    private function sut(string $extension): Contents
    {
        $sut = new Contents($extension);
        $page1 = $sut->appendPage("Start", ["url" => "\\Start'"], "<h1>Start</h1>");
        $page2 = $page1->appendChild("Sub", [], "<h1>Sub</h1>");
        $page1->appendChild("Sub2", [], "<h1>Sub2</h1>");
        $page2->appendChild("SubSub", [], "<h1>SubSub</h1>");
        $page3 = $sut->appendPage("About", [], "<h1>About</h1>");
        $page3->appendChild("Me", [], "<h1>About me</h1>");
        return $sut;
    }

    public function testSerializesXhString(): void
    {
        Approvals::verifyHtml($this->sut("htm")->toString());
    }

    public function testUnserializesXhString(): void
    {
        $expected = $this->sut("htm");
        $actual = Contents::fromString($expected->toString(), "content.htm");
        $this->assertEquals($expected, $actual);
    }

    public function testSerializesXmlString(): void
    {
        Approvals::verifyHtml($this->sut("xml")->toString());
    }

    public function testUnserializesXmlString(): void
    {
        $expected = $this->sut("xml");
        $actual = Contents::fromString($expected->toString(), "content.xml");
        $this->assertEquals($expected, $actual);
    }

    /** @see <https://github.com/cmb69/exchange_xh/issues/12> */
    public function testFiltersInvalidControlWhenSerializingXml(): void
    {
        $sut = new Contents("xml");
        $sut->appendPage("Start\x011", ["start\x0b2" => "Start\x1b3"], "<h1>Start\x1f4</h1>");
        $actual = $sut->toString();
        $this->assertStringContainsString("Start1", $actual);
        $this->assertStringContainsString("start2", $actual);
        $this->assertStringContainsString("Start3", $actual);
        $this->assertStringContainsString("Start4", $actual);
    }

    public function testReadsTwoConsecutiveLevel1Pages(): void
    {
        $sut = new Contents("htm");
        $sut->appendPage("First", [], "<h1>First</h1>");
        $sut->appendPage("Second", [], "<h1>Second</h1>");
        $actual = Contents::fromString($sut->toString(), "content.htm");
        $this->assertSame(2, $actual->pageCount());
        $this->assertSame(1, $actual->page(1)->level());
    }

    public function testReadsXh16Contents(): void
    {
        $actual = Contents::fromXh16String(file_get_contents(__DIR__ . "/content.htm"), 3);
        Approvals::verifyHtml($actual->toString());
    }
}
