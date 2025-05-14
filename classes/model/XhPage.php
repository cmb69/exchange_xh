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

trait XhPage
{
    public static function fromXhString(?self $parent, string $title, string $heading, string $contents): ?self
    {
        if (!preg_match('/\s*<\?php(.*?)\?>(.*)/isu', $contents, $submatches)) {
            return null;
        }
        $content = (string) preg_replace('/^\s*|\s*(?:<\/body.*)?$/su', "", $heading . $submatches[2]);
        if ($parent === null) {
            return new self(1, $title, self::pageData($submatches[1]), $content);
        } else {
            return $parent->appendChild($title, self::pageData($submatches[1]), $content);
        }
    }

    /** @return array<string,string> */
    private static function pageData(string $php): array
    {
        eval($php);
        if (isset($page_data[0])) { // @phpstan-ignore-line
            return $page_data[0]; // @phpstan-ignore-line
        }
        return [];
    }

    public function toXhString(): string
    {
        $res = "<!--XH_ml{$this->level}:{$this->title}-->\n";
        $res .= "<?php\n\$page_data[]=array(\n";
        foreach ($this->data as $key => $value) {
            $res .= "'{$this->escape($key)}'=>'{$this->escape($value)}',\n";
        }
        $res .= ");\n?>\n";
        $res .= "{$this->content}\n";
        foreach ($this->children as $child) {
            $res .= $child->toXhString();
        }
        return $res;
    }

    private function escape(string $value): string
    {
        return addcslashes($value, "\'\\");
    }
}
