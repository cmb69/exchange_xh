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
use Plib\Response;
use Plib\View;

class MainAdminController
{
    /** @var array */
    private $lang;

    /** @var object */
    private $csrfProtector;

    /** @var View */
    private $view;

    public function __construct(View $view)
    {
        global $plugin_tx, $title, $_XH_csrfProtection;

        $this->lang = $plugin_tx['exchange'];
        $this->csrfProtector = $_XH_csrfProtection;
        $title = XH_hsc($this->lang['menu_main']);
        $this->view = $view;
    }

    public function defaultAction(Request $request): Response
    {
        $service = new ExchangeService;
        return Response::create($this->view->render("main", [
            "url" => $request->url()->page("exchange")->with("edit")->relative(),
            "admin" => 'plugin_main',
            "csrfToken" => $this->csrfProtector->tokenInput(),
            "hasXmlFile" => file_exists($service->getXmlFilename()),
        ]));
    }

    public function exportAction(): Response
    {
        $this->csrfProtector->check();
        $exporter = new ExportService;
        if ($exporter->export()) {
            return Response::redirect(CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=exported&normal');
        } else {
            return Response::create(XH_message('fail', $this->lang['message_export_failed']));
        }
    }

    public function exportedAction(): Response
    {
        return Response::create(XH_message('success', $this->lang['message_exported']));
    }

    public function importAction(): Response
    {
        $this->csrfProtector->check();
        $importer = new ImportService;
        if ($importer->import()) {
            return Response::redirect(CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=imported&normal');
        } else {
            return Response::create(XH_message('fail', $this->lang['message_import_failed']));
        }
    }

    public function importedAction(): Response
    {
        return Response::create(XH_message('success', $this->lang['message_imported']));
    }
}
