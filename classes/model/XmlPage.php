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
use SimpleXMLElement;

trait XmlPage
{
    public static function fromXml(SimpleXMLElement $elt, int $level): self
    {
        $that = new self($level, $elt["title"] ?? "unknown", self::pageDataFromXml($elt), $elt->content);
        if (isset($elt->page)) {
            foreach ($elt->page as $child) {
                $that->children[] = self::fromXml($child, $level + 1);
            }
        }
        return $that;
    }

    /** @return array<string,string> */
    private static function pageDataFromXml(SimpleXMLElement $elt): array
    {
        $result = array();
        foreach ($elt->data->attributes() as $name => $value) {
            $result[$name] = (string) $value;
        }
        return $result;
    }

    public function createPageElement(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement('page');
        $elt->setAttribute('title', $this->title);
        // do we need the URL?
        // $page->setAttribute('url', $this->pages->url($pageIndex));
        $elt->appendChild($this->createPageDataElement($doc, $this->data));
        $content = $doc->createElement('content');
        $cdata = $doc->createCDATASection($this->content);
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
            $element->setAttribute($key, $value);
        }
        return $element;
    }
}
