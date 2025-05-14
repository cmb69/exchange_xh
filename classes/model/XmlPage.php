<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Exchange_XH.
 *
 * Exchange_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exchange_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Exchange_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Exchange\Model;

use DOMDocument;
use DOMElement;
use DOMNode;

trait XmlPage
{
    public static function fromXml(DOMElement $elt, int $level): self
    {
        $node = $elt->getElementsByTagName("content")->item(0);
        assert($node instanceof DOMNode);
        $that = new self($level, $elt->getAttribute("title"), self::pageDataFromXml($elt), $node->nodeValue ?? "");
        foreach ($elt->childNodes as $child) {
            if ($child instanceof DOMElement && $child->nodeName === "page") {
                $that->children[] = self::fromXml($child, $level + 1);
            }
        }
        return $that;
    }

    /** @return array<string,string> */
    private static function pageDataFromXml(DOMElement $elt): array
    {
        $result = [];
        $dataNode = $elt->getElementsByTagName("data")->item(0);
        assert($dataNode instanceof DOMElement);
        foreach ($dataNode->attributes as $name => $node) {
            if ($node instanceof DOMNode) {
                $result[$name] = $node->nodeValue ?? "";
            }
        }
        return $result;
    }

    public function createPageElement(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement('page');
        $elt->setAttribute('title', $this->filter($this->title));
        // do we need the URL?
        // $page->setAttribute('url', $this->pages->url($pageIndex));
        $elt->appendChild($this->createPageDataElement($doc, $this->data));
        $content = $doc->createElement('content');
        $cdata = $doc->createCDATASection($this->filter($this->content));
        $content->appendChild($cdata);
        $elt->appendChild($content);
        foreach ($this->children as $page) {
            $elt->appendChild($page->createPageElement($doc));
        }
        return $elt;
    }

    /** @param array<string,string> $pageData */
    private function createPageDataElement(DOMDocument $doc, array $pageData): DOMElement
    {
        $element = $doc->createElement('data');
        foreach ($pageData as $key => $value) {
            $element->setAttribute($this->filter($key), $this->filter($value));
        }
        return $element;
    }

    private function filter(string $string): string
    {
        return (string) preg_replace('/[\x00-\x08\x0b-\x0c\x0e-\x1f]/u', "", $string);
    }
}
