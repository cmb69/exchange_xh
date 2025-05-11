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
use Plib\SystemChecker;
use Plib\View;
use XH\Pages;

class Dic
{
    public const VERSION = "1.1-dev";

    public static function mainAdminController(): MainAdminController
    {
        global $pth, $cf, $pd_router;
        return new MainAdminController(
            new CsrfProtector(),
            new ExchangeService($pth["folder"]["content"] . "content.xml"),
            new ExportService(
                $pth["folder"]["content"] . "content.xml",
                (int) $cf["menu"]["levels"],
                isset($cf["headings"]["show"]),
                new Pages(),
                $pd_router
            ),
            new ImportService($pth["folder"]["content"] . "content.xml"),
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
