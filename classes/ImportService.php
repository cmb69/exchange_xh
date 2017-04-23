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

class ImportService
{
    /**
     * @var string
     */
    private $xmlFilename;

    /**
     * @var bool
     */
    private $newSplitMode;

    /**
     * @var object
     */
    private $pdRouter;

    public function __construct()
    {
        global $pth, $cf, $pd_router;

        $this->xmlFilename = "{$pth['folder']['content']}content.xml";
        $this->newSplitMode = isset($cf['headings']['show']);
        $this->pdRouter = $pd_router;
    }

    /**
     * @return bool
     */
    public function import()
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

        $c[] = $this->getConstructedContent($page);
        $cl++;
        $this->pdRouter->appendNewPage($this->getPageData($page));
        if (isset($page->page)) {
            foreach ($page->page as $child) {
                $this->createPage($child);
            }
        }
    }

    /**
     * @return string
     */
    private function getConstructedContent(SimpleXMLElement $page)
    {
        if ($this->newSplitMode) {
            $heading = "<!--XH_ml{$page['level']}:{$page['heading']}-->";
        } else {
            $heading = "<h{$page['level']}>{$page['heading']}</h{$page['level']}>";
        }
        return "$heading\n{$page->content}";
    }

    /**
     * @return array
     */
    private function getPageData(SimpleXMLElement $page)
    {
        $result = array();
        foreach ($page->pagedata->attributes() as $name => $value) {
            $result[$name] = $value;
        }
        return $result;
    }
}
