<?php

/**
 * Copyright 2017 Christoph M. Becker
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

namespace Exchange;

use DOMDocument;
use DOMElement;
use XH_Pages;

class Model
{
    /**
     * @var string
     */
    private $xmlFilename;

    /**
     * @var int
     */
    private $menuLevels;

    /**
     * @var object
     */
    private $pages;

    /**
     * @var DOMDocument
     */
    private $document;

    /**
     * @var object
     */
    private $pdRouter;

    public function __construct()
    {
        global $pth, $cf, $pd_router;

        $this->xmlFilename = "{$pth['folder']['content']}content.xml";
        $this->menuLevels = (int) $cf['menu']['levels'];
        $this->pdRouter = $pd_router;
        include_once "{$pth['folder']['classes']}Pages.php";
        $this->pages = new XH_Pages();
    }

    public function export()
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $contents = $this->document->createElement('contents');
        $this->document->appendChild($contents);
        $pageElements = $this->createPageElements($this->pages->toplevels(false));
        foreach ($pageElements as $pageElement) {
            $contents->appendChild($pageElement);
        }
        $this->save();
    }

    private function createPageElements($indexes)
    {
        $result = array();
        foreach ($indexes as $index) {
            $result[] = $this->createPageElement($index);
        }
        return $result;
    }

    /**
     * @param int $pageIndex
     * @return DOMElement
     */
    private function createPageElement($pageIndex)
    {
        $page = $this->document->createElement('page');
        $page->setAttribute('heading', $this->pages->heading($pageIndex));
        $page->setAttribute('level', $this->pages->level($pageIndex));
        $page->setAttribute('url', $this->pages->url($pageIndex));
        $page->appendChild($this->createPageDataElement($this->pdRouter->find_page($pageIndex)));
        $content = $this->document->createElement('content');
        $cdata = $this->document->createCDATASection($this->getActualContent($pageIndex));
        $content->appendChild($cdata);
        $page->appendChild($content);
        $children = $this->createPageElements($this->pages->children($pageIndex, false));
        foreach ($children as $child) {
            $page->appendChild($child);
        }
        return $page;
    }

    /**
     * @return DOMElement
     */
    private function createPageDataElement(array $pageData)
    {
        $element = $this->document->createElement('pagedata');
        foreach ($pageData as $key => $value) {
            $element->setAttribute($key, $value);
        }
        return $element;
    }

    /**
     * @param int $pageIndex
     * @return string
     */
    private function getActualContent($pageIndex)
    {
        $pattern = "/<h[1-{$this->menuLevels}][^>]*>.*?<\\/h[1-{$this->menuLevels}]>/";
        $content = $this->pages->content($pageIndex);
        return ltrim(preg_replace($pattern, '', $content));
    }

    private function save()
    {
        $this->document->formatOutput = true;
        $this->document->save($this->xmlFilename);
    }
}
