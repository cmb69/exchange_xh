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

trait XmlContents
{
    private static function fromXmlString(string $contents): ?self
    {
        if ($contents === "") {
            return new self("xml");
        }
        $root = simplexml_load_string($contents);
        if (!$root) {
            return null;
        }
        $that = new self("xml");
        foreach ($root->page as $page) {
            $that->pages[] = Page::fromXml($page, 1);
        }
        return $that;
    }

    private function toXmlString(): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $contents = $doc->createElement('contents');
        // do we want to use the version of the running system?
        $contents->setAttribute('version', (string) preg_replace('/^CMSimple_XH /', '', CMSIMPLE_XH_VERSION));
        $doc->appendChild($contents);
        foreach ($this->pages as $page) {
            $contents->appendChild($page->createPageElement($doc));
        }
        $doc->formatOutput = true;
        return (string) $doc->saveXML();
    }
}
