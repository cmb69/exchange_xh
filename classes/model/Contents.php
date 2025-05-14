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

use LogicException;
use Plib\Document;
use Plib\DocumentStore;

final class Contents implements Document
{
    use XhContents;
    use XmlContents;

    /** @var string */
    private $extension;

    /** @var list<Page> */
    private $pages = [];

    public static function fromString(string $contents, string $key): self
    {
        switch (pathinfo($key, PATHINFO_EXTENSION)) {
            case "htm":
                return self::fromXhString($contents);
            case "xml":
                return self::fromXmlString($contents);
            default:
                throw new LogicException("only .htm and .xml are supported");
        }
    }

    public static function retrieve(string $extension, DocumentStore $store): self
    {
        $that = $store->retrieve("content.$extension", self::class);
        assert($that instanceof self);
        return $that;
    }

    public static function update(string $extension, DocumentStore $store): self
    {
        $that = $store->update("content.$extension", self::class);
        assert($that instanceof self);
        return $that;
    }

    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }

    public function pageCount(): int
    {
        return count($this->pages);
    }

    public function page(int $index): Page
    {
        assert($index >= 0 && $index < $this->pageCount());
        return $this->pages[$index];
    }

    /** @param array<string,string> $data */
    public function appendPage(string $title, array $data, string $content): Page
    {
        $page = new Page(1, $title, $data, $content);
        $this->pages[] = $page;
        return $page;
    }

    public function copy(self $other): void
    {
        $this->pages = $other->pages;
    }

    public function toString(): string
    {
        switch ($this->extension) {
            case "htm":
                return $this->toXhString();
            case "xml":
                return $this->toXmlString();
            default:
                return "";
        }
    }
}
