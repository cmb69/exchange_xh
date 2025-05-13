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

trait XhContents
{
    private static function fromXhString(string $contents): ?self
    {
        $matches = preg_split('/<!--XH_ml(\d):(.*?)-->/', $contents, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($matches === false) {
            return null;
        }
        $prevLevel = 0;
        $that = new self("htm");
        // ignoring the prolog ($matches[0]) since that doesn't seem to be necessary
        for ($i = 1; $i < count($matches); $i += 3) {
            $level = (int) $matches[$i];
            $title = $matches[$i + 1];
            $rest = $matches[$i + 2];
            if ($level > $prevLevel) {
                $parent = empty($that->pages) ? null : $that->peekPage();
            } else {
                do {
                    $page = $that->popPage();
                } while ($page->level() > max(2, $level));
                $parent = $level === 1 ? null : $that->peekPage();
            }
            $page = Page::fromXhString($parent, $title, $rest);
            if ($page === null) {
                continue; // ignore page; alternative: return null
            }
            $that->pages[] = $page;
            $prevLevel = $level;
        }
        $that->popNonToplevelPages();
        return $that;
    }

    private function peekPage(): Page
    {
        assert(!empty($this->pages));
        $page = end($this->pages);
        assert($page instanceof Page);
        return $page;
    }

    private function popPage(): Page
    {
        assert(!empty($this->pages));
        $page = array_pop($this->pages);
        assert($page instanceof Page);
        return $page;
    }

    private function popNonToplevelPages(): void
    {
        while (true) {
            $page = end($this->pages);
            if ($page === false || $page->level() === 1) {
                break;
            }
            array_pop($this->pages);
        }
    }

    private function toXhString(): string
    {
        $res = "<html><head><title>content file</title>\n";
        $res .= "</head><body>\n";
        foreach ($this->pages as $page) {
            $res .= $page->toXhString();
        }
        $res .= "</body></html>\n";
        return $res;
    }
}
