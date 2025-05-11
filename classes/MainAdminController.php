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
use Plib\View;

class MainAdminController
{
    /** @var array */
    private $lang;

    /** @var object */
    private $csrfProtector;

    public function __construct()
    {
        global $plugin_tx, $title, $_XH_csrfProtection;

        $this->lang = $plugin_tx['exchange'];
        $this->csrfProtector = $_XH_csrfProtection;
        $title = XH_hsc($this->lang['menu_main']);
    }

    public function defaultAction(Request $request)
    {
        global $pth, $plugin_tx;
        $service = new ExchangeService;
        $view = new View($pth["folder"]["plugins"] . "exchange/views/", $plugin_tx["exchange"]);
        echo $view->render("main", [
            "url" => $request->url()->page("exchange")->with("edit")->relative(),
            "admin" => 'plugin_main',
            "csrfToken" => $this->csrfProtector->tokenInput(),
            "hasXmlFile" => file_exists($service->getXmlFilename()),
        ]);
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
