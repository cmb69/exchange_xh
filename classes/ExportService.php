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
use XH\PageDataRouter;
use XH\Pages;

class ExportService extends ExchangeService
{
    /** @var int */
    private $menuLevels;

    /** @var bool */
    private $newSplitMode;

    /** @var Pages */
    private $pages;

    /** @var DOMDocument */
    private $document;

    /** @var PageDataRouter */
    private $pdRouter;

    public function __construct(string $xmlFilename)
    {
        global $cf, $pd_router;

        parent::__construct($xmlFilename);
        $this->menuLevels = (int) $cf['menu']['levels'];
        $this->newSplitMode = isset($cf['headings']['show']);
        $this->pdRouter = $pd_router;
        $this->pages = new Pages();
    }

    public function export(): bool
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $contents = $this->document->createElement('contents');
        $contents->setAttribute('version', (string) preg_replace('/^CMSimple_XH /', '', CMSIMPLE_XH_VERSION));
        $this->document->appendChild($contents);
        $pageElements = $this->createPageElements($this->pages->toplevels(false));
        foreach ($pageElements as $pageElement) {
            $contents->appendChild($pageElement);
        }
        return $this->save();
    }

    /**
     * @param list<int> $indexes
     * @return list<DOMElement>
     */
    private function createPageElements(array $indexes): array
    {
        $result = array();
        foreach ($indexes as $index) {
            $result[] = $this->createPageElement($index);
        }
        return $result;
    }

    private function createPageElement(int $pageIndex): DOMElement
    {
        $page = $this->document->createElement('page');
        $page->setAttribute('heading', $this->pages->heading($pageIndex));
        $page->setAttribute('level', (string) $this->pages->level($pageIndex));
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

    /** @param array<string,string> $pageData */
    private function createPageDataElement(array $pageData): DOMElement
    {
        $element = $this->document->createElement('pagedata');
        foreach ($pageData as $key => $value) {
            $element->setAttribute($key, $value);
        }
        return $element;
    }

    private function getActualContent(int $pageIndex): string
    {
        if ($this->newSplitMode) {
            $pattern = '/<!--XH_ml[1-9]:.*?-->/';
        } else {
            $pattern = "/<h[1-{$this->menuLevels}][^>]*>.*?<\\/h[1-{$this->menuLevels}]>/";
        }
        $content = $this->pages->content($pageIndex);
        return ltrim((string) preg_replace($pattern, '', $content));
    }

    private function save(): bool
    {
        $this->document->formatOutput = true;
        return $this->document->save($this->xmlFilename) !== false;
    }
}
