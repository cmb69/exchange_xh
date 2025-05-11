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
    /** @var string */
    private $scriptName;

    /** @var array */
    private $lang;

    /** @var object */
    private $csrfProtector;

    public function __construct()
    {
        global $sn, $plugin_tx, $title, $_XH_csrfProtection;

        $this->scriptName = $sn;
        $this->lang = $plugin_tx['exchange'];
        $this->csrfProtector = $_XH_csrfProtection;
        $title = XH_hsc($this->lang['menu_main']);
    }

    public function defaultAction()
    {
        $service = new ExchangeService;
        $view = new View('main');
        $view->url = "{$this->scriptName}?&exchange&edit";
        $view->admin = 'plugin_main';
        $view->csrfToken = new HtmlString($this->csrfProtector->tokenInput());
        $view->hasXmlFile = file_exists($service->getXmlFilename());
        $view->render();
    }

    public function exportAction()
    {
        $this->csrfProtector->check();
        $exporter = new ExportService;
        if ($exporter->export()) {
            header('Location: ' . CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=exported&normal', true, 303);
            exit;
        } else {
            echo XH_message('fail', $this->lang['message_export_failed']);
        }
    }

    public function exportedAction()
    {
        echo XH_message('success', $this->lang['message_exported']);
    }

    public function importAction()
    {
        $this->csrfProtector->check();
        $importer = new ImportService;
        if ($importer->import()) {
            header('Location: ' . CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=imported&normal', true, 303);
            exit;
        } else {
            echo XH_message('fail', $this->lang['message_import_failed']);
        }
    }

    public function importedAction()
    {
        echo XH_message('success', $this->lang['message_imported']);
    }
}
