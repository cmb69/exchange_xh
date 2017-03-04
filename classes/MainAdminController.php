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

class MainAdminController
{
    /**
     * @var string
     */
    private $scriptName;

    /**
     * @var array
     */
    private $lang;

    public function __construct()
    {
        global $sn, $plugin_tx;

        $this->scriptName = $sn;
        $this->lang = $plugin_tx['exchange'];
    }

    public function defaultAction()
    {
        $view = new View('main');
        $view->url = "{$this->scriptName}?&exchange&edit";
        $view->admin = 'plugin_main';
        $view->render();
    }

    public function exportAction()
    {
        $model = new Model;
        $model->export();
        header('Location: ' . CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=exported&normal', true, 303);
        exit;
    }

    public function exportedAction()
    {
        echo XH_message('success', $this->lang['message_exported']);
    }

    public function importAction()
    {
        header('Location: ' . CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=imported&normal', true, 303);
        exit;
    }

    public function importedAction()
    {
        echo XH_message('info', 'import is not yet implemented');
    }    
}
