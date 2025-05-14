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

use Exchange\Model\Contents;
use Plib\CsrfProtector;
use Plib\DocumentStore;
use Plib\Request;
use Plib\Response;
use Plib\View;

class MainAdminController
{
    /** @var CsrfProtector */
    private $csrfProtector;

    /** @var DocumentStore */
    private $store;

    /** @var View */
    private $view;

    public function __construct(
        CsrfProtector $csrfProtector,
        DocumentStore $store,
        View $view
    ) {
        $this->csrfProtector = $csrfProtector;
        $this->store = $store;
        $this->view = $view;
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
            case "import16":
                return $this->import16Action($request);
        }
    }

    private function defaultAction(Request $request): Response
    {
        $url = $request->url();
        return Response::create($this->view->render("main", [
            "export_url" => $url->with("action", "export")->relative(),
            "import_url" => $url->with("action", "import")->relative(),
            "import16_url" => $url->with("action", "import16")->relative(),
            "csrfToken" => $this->csrfProtector->token(),
            "hasXmlFile" => !empty($this->store->find('/^content\.xml$/')),
            "has16File" => !empty($this->store->find('/^content\.1\.6\.htm$/')),
        ]))->withTitle($this->view->text("menu_main"));
    }

    private function exportAction(Request $request): Response
    {
        if (!$this->csrfProtector->check($request->post("exchange_token"))) {
            return Response::create($this->view->message("fail", "error_unauthorized"));
        }
        $xml = Contents::update("xml", $this->store);
        $xml->copy(Contents::retrieve("htm", $this->store));
        if ($this->store->commit()) {
            return Response::redirect($request->url()->with("action", "exported")->absolute());
        } else {
            return Response::create($this->view->message("fail", "message_export_failed"));
        }
    }

    private function exportedAction(): Response
    {
        return Response::create($this->view->message("success", "message_exported"))
            ->withTitle($this->view->text("menu_main"));
    }

    private function importAction(Request $request): Response
    {
        if (!$this->csrfProtector->check($request->post("exchange_token"))) {
            return Response::create($this->view->message("fail", "error_unauthorized"));
        }
        $htm = Contents::update("htm", $this->store);
        $htm->copy(Contents::retrieve("xml", $this->store));
        if ($this->store->commit()) {
            return Response::redirect($request->url()->with("action", "imported")->absolute());
        } else {
            return Response::create($this->view->message("fail", "message_import_failed"));
        }
    }

    private function importedAction(): Response
    {
        return Response::create($this->view->message("success", "message_imported"))
            ->withTitle($this->view->text("menu_main"));
    }

    private function import16Action(Request $request): Response
    {
        if (!$this->csrfProtector->check($request->post("exchange_token"))) {
            return Response::create($this->view->message("fail", "error_unauthorized"));
        }
        $htm = Contents::update("htm", $this->store);
        $string = (string) @file_get_contents($this->store->folder() . "/content.1.6.htm");
        $htm16 = Contents::fromXh16String($string, 3);
        $htm->copy($htm16);
        if ($this->store->commit()) {
            return Response::redirect($request->url()->with("action", "imported")->absolute());
        } else {
            return Response::create($this->view->message("fail", "message_import_failed"));
        }
    }
}
