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

class View
{
    /** @var string */
    private $template;

    /** @var array */
    private $data = array();

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    /** @param mixed $value */
    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    /** @return mixed */
    public function __get(string $name)
    {
        return $this->data[$name];
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __call(string $name, array $args): string
    {
        return $this->escape($this->data[$name]);
    }

    public function __toString(): string
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }
    
    protected function text(string $key): string
    {
        global $plugin_tx;

        $args = func_get_args();
        array_shift($args);
        return $this->escape(vsprintf($plugin_tx['exchange'][$key], $args));
    }

    protected function plural(string $key, int $count)
    {
        global $plugin_tx;

        if ($count == 0) {
            $key .= '_0';
        } else {
            $key .= XH_numberSuffix($count);
        }
        $args = func_get_args();
        array_shift($args);
        return $this->escape(vsprintf($plugin_tx['exchange'][$key], $args));
    }

    public function render(): void
    {
        global $pth;

        echo "<!-- {$this->template} -->", PHP_EOL;
        include "{$pth['folder']['plugins']}exchange/views/{$this->template}.php";
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function escape($value)
    {
        if ($value instanceof HtmlString) {
            return $value;
        } else {
            return XH_hsc($value);
        }
    }
}
