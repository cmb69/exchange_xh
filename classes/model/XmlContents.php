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

trait XmlContents
{
    private static function fromXmlString(string $contents): self
    {
        $that = new self("xml");
        if ($contents === "") {
            return $that;
        }
        $doc = new DOMDocument("1.0", "UTF-8");
        if (!$doc->loadXML($contents)) {
            return $that;
        }
        if (!$doc->relaxNGValidate(__DIR__ . "/../../contents.rng")) {
            return $that;
        }
        assert($doc->documentElement instanceof DOMElement);
        foreach ($doc->documentElement->childNodes as $node) {
            if ($node instanceof DOMElement && $node->nodeName === "page") {
                $that->pages[] = Page::fromXml($node, 1);
            }
        }
        return $that;
    }

    private function toXmlString(): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $contents = $doc->createElement('contents');
        $contents->setAttribute("version", "2.0");
        $doc->appendChild($contents);
        foreach ($this->pages as $page) {
            $contents->appendChild($page->createPageElement($doc));
        }
        if (!$doc->relaxNGValidate(__DIR__ . "/../../contents.rng")) {
            return "";
        }
        $doc->formatOutput = true;
        return (string) $doc->saveXML();
    }
}
