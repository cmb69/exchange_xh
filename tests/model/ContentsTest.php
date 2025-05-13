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
}
