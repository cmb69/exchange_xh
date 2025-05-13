<?php

namespace Exchange\Model;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

/** @small */
class ContentsTest extends TestCase
{
    private function sut(): Contents
    {
        $sut = new Contents();
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
        Approvals::verifyHtml($this->sut()->toXhString());
    }

    public function testUnserializesXhString(): void
    {
        $expected = $this->sut();
        $actual = Contents::fromXhString($expected->toXhString());
        $this->assertEquals($expected, $actual);
    }

    public function testSerializesXmlString(): void
    {
        Approvals::verifyHtml($this->sut()->toXmlString());
    }
}
