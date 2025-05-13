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

trait XmlPage
{
    public function createPageElement(DOMDocument $doc): DOMElement
    {
        $elt = $doc->createElement('page');
        $elt->setAttribute('heading', $this->title);
        $elt->setAttribute('level', (string) $this->level);
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
        $element = $doc->createElement('pagedata');
        foreach ($pageData as $key => $value) {
            $element->setAttribute($key, $value);
        }
        return $element;
    }
}
