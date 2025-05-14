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

namespace Exchange;

use Plib\CsrfProtector;
use Plib\DocumentStore;
use Plib\SystemChecker;
use Plib\View;

class Dic
{
    public const VERSION = "2.0";

    public static function mainAdminController(): MainAdminController
    {
        global $pth;
        return new MainAdminController(
            new CsrfProtector(),
            new DocumentStore($pth["folder"]["content"]),
            self::view()
        );
    }

    public static function infoController(): InfoController
    {
        global $pth;
        return new InfoController(
            $pth["folder"]["plugins"] . "exchange/",
            new SystemChecker(),
            self::view()
        );
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;
        return new View($pth["folder"]["plugins"] . "exchange/views/", $plugin_tx["exchange"]);
    }
}
