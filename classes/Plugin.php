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

class Plugin
{
    /**
     * @var string
     */
    private $admin;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $plugin;

    /**
     * @var bool
     */
    private $exchange;

    public function __construct()
    {
        global $admin, $action, $plugin, $exchange;

        $this->admin = isset($_GET['admin']) ? $_GET['admin'] : (isset($_POST['admin']) ? $_POST['admin'] : null);
        $this->action = $action;
        $this->plugin = $plugin;
        $this->exchange = isset($exchange) ? true : false;
    }

    public function init()
    {
        if (XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(true);
            }
            if ($this->isAdministrationRequested()) {
                $this->handleAdministration();
            }
        }
    }

    /**
     * @return bool
     */
    private function isAdministrationRequested()
    {
        return function_exists('XH_wantsPluginAdministration') && XH_wantsPluginAdministration('exchange')
            || $this->exchange;
    }

    private function handleAdministration()
    {
        global $o;

        $o .= print_plugin_admin('on');
        switch ($this->admin) {
            case 'plugin_main':
                $this->handleMainAdministration();
                break;
            default:
                $o .= plugin_admin_common($this->admin, $this->action, $this->plugin);                
        }
    }

    private function handleMainAdministration()
    {
        global $o;

        $controller = new MainAdminController();
        $action = "{$this->action}Action";
        if (!method_exists($controller, $action)) {
            $action = 'defaultAction';
        }
        ob_start();
        $controller->$action();
        $o .= ob_get_clean();
    }
}
