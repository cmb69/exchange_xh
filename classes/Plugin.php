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

use Plib\Request;

class Plugin
{
    const VERSION = '1.1-dev';

    /** @var string */
    private $admin;

    public function __construct()
    {
        $this->admin = isset($_GET['admin']) ? $_GET['admin'] : (isset($_POST['admin']) ? $_POST['admin'] : null);
    }

    public function init(): void
    {
        if (XH_ADM) { // @phpstan-ignore-line
            XH_registerStandardPluginMenuItems(true);
            if (XH_wantsPluginAdministration('exchange')) {
                $this->handleAdministration();
            }
        }
    }

    private function handleAdministration(): void
    {
        global $o;

        $o .= print_plugin_admin('on');
        switch ($this->admin) {
            case '':
                $o .= Dic::infoController()();
                break;
            case 'plugin_main':
                $o .= Dic::mainAdminController()(Request::current())();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }
}
