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

use Plib\CsrfProtector;
use Plib\Request;
use Plib\Response;
use Plib\View;

class MainAdminController
{
    /** @var CsrfProtector */
    private $csrfProtector;

    /** @var ExchangeService */
    private $exchangeService;

    /** @var ExportService */
    private $exportService;

    /** @var ImportService */
    private $importService;

    /** @var View */
    private $view;

    public function __construct(
        CsrfProtector $csrfProtector,
        ExchangeService $exchangeService,
        ExportService $exportService,
        ImportService $importService,
        View $view
    ) {
        global $title;

        $this->csrfProtector = $csrfProtector;
        $this->exchangeService = $exchangeService;
        $this->exportService = $exportService;
        $this->importService = $importService;
        $this->view = $view;
        $title = $this->view->plain("menu_main");
    }

    public function __invoke(Request $request): Response
    {
        switch ($request->get("action")) {
            default:
                return $this->defaultAction($request);
            case "export":
                return $this->exportAction($request);
            case "exported":
                return $this->exportedAction();
            case "import":
                return $this->importAction($request);
            case "imported":
                return $this->importedAction();
        }
    }

    private function defaultAction(Request $request): Response
    {
        $url = $request->url()->with("edit");
        return Response::create($this->view->render("main", [
            "export_url" => $url->with("action", "export")->relative(),
            "import_url" => $url->with("action", "import")->relative(),
            "csrfToken" => $this->csrfProtector->token(),
            "hasXmlFile" => file_exists($this->exchangeService->getXmlFilename()),
        ]));
    }

    private function exportAction(Request $request): Response
    {
        if (!$this->csrfProtector->check($request->post("exchange_token"))) {
            return Response::create("not authorized"); // TODO i18n
        }
        if ($this->exportService->export()) {
            return Response::redirect(CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=exported&normal');
        } else {
            return Response::create($this->view->message("fail", "message_export_failed"));
        }
    }

    private function exportedAction(): Response
    {
        return Response::create($this->view->message("success", "message_exported"));
    }

    private function importAction(Request $request): Response
    {
        if (!$this->csrfProtector->check($request->post("exchange_token"))) {
            return Response::create("not authorized"); // TODO i18n
        }
        if ($this->importService->import()) {
            return Response::redirect(CMSIMPLE_URL . '?&exchange&admin=plugin_main&action=imported&normal');
        } else {
            return Response::create($this->view->message("fail", "message_import_failed"));
        }
    }

    private function importedAction(): Response
    {
        return Response::create($this->view->message("success", "message_imported"));
    }
}
