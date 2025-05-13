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

final class Page
{
    use XhPage;
    use XmlPage;

    /** @var int */
    private $level;

    /** @var string */
    private $title;

    /** @var array<string,string> */
    private $data;

    /** @var string */
    private $content;

    /** @var list<self> */
    private $children = [];

    /** @param array<string,string> $data */
    public function __construct(int $level, string $title, array $data, string $content)
    {
        $this->level = $level;
        $this->title = $title;
        $this->data = $data;
        $this->content = $content;
    }

    public function level(): int
    {
        return $this->level;
    }

    /** @param array<string,string> $data */
    public function appendChild(string $title, array $data, string $content): self
    {
        $child = new self($this->level + 1, $title, $data, $content);
        $this->children[] = $child;
        return $child;
    }
}
