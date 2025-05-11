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

use SimpleXMLElement;

class ImportService extends ExchangeService
{
    /** @var bool */
    private $newSplitMode;

    /** @var object */
    private $pdRouter;

    public function __construct(string $xmlFilename)
    {
        global $pd_router;

        parent::__construct($xmlFilename);
        $this->newSplitMode = version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.7', 'ge');
        $this->pdRouter = $pd_router;
    }

    public function import(): bool
    {
        $root = simplexml_load_file($this->xmlFilename);
        if (!$root) {
            return false;
        }
        $this->purgeContents();
        foreach ($root->page as $page) {
            $this->createPage($page);
        }
        return XH_saveContents();
    }

    private function purgeContents()
    {
        global $c, $cl;

        $c = array();
        for ($i = $cl - 1; $i >= 0; $i--) {
            $this->pdRouter->destroy($i);
        }
        $cl = 0;
    }

    private function createPage(SimpleXMLElement $page)
    {
        global $c, $cl;

        $pageData = $this->getPageData($page);
        $c[] = $this->getConstructedContent($page, $pageData);
        $cl++;
        $this->pdRouter->appendNewPage($pageData);
        if (isset($page->page)) {
            foreach ($page->page as $child) {
                $this->createPage($child);
            }
        }
    }

    private function getConstructedContent(SimpleXMLElement $page, array &$pageData): string
    {
        if ($this->newSplitMode) {
            if ((string) $pageData['show_heading']) {
                $name = $pageData['heading'];
            } else {
                $name = $page['heading'];
            }
            unset($pageData['show_heading'], $pageData['heading']);
            $pageData['publication_date'] = $this->normalizeDateTime($pageData['publication_date']);
            $pageData['expires'] = $this->normalizeDateTime($pageData['expires']);
            $heading = "<!--XH_ml{$page['level']}:{$page['heading']}-->\n<h1>$name</h1>";
        } else {
            $heading = "<h{$page['level']}>{$page['heading']}</h{$page['level']}>";
        }
        return "$heading\n{$page->content}";
    }

    private function normalizeDateTime(string $value): string
    {
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        } else {
            return date('Y-m-d\TH:i', $timestamp);
        }
    }

    private function getPageData(SimpleXMLElement $page): array
    {
        $result = array();
        foreach ($page->pagedata->attributes() as $name => $value) {
            $result[$name] = $value;
        }
        return $result;
    }
}
