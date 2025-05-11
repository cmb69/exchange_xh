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

use Plib\View;

class InfoController
{
    /** @var string */
    private $pluginFolder;

    /** @var array<string,string> */
    private $lang;

    /** @var View */
    private $view;

    /** @param array<string,string> */
    public function __construct(string $pluginFolder, array $lang, View $view)
    {
        $this->pluginFolder = $pluginFolder;
        $this->lang = $lang;
        $this->view = $view;
    }

    public function __invoke(): string
    {
        return $this->view->render("info", [
            "version" => Plugin::VERSION,
            "logo" => $this->pluginFolder . "exchange.png",
            "checks" => $this->getChecks(),
        ]);
    }

    /** @return array<object> */
    public function getChecks()
    {
        return array(
            $this->checkPhpVersion('7.1.0'),
            $this->checkExtension('DOM'),
            $this->checkExtension('SimpleXML'),
            $this->checkXhVersion('1.7.0'),
            $this->checkWritability($this->pluginFolder . "css/"),
            $this->checkWritability($this->pluginFolder . "languages/")
        );
    }

    /** @return object{state:string,label:string,stateLabel:string} */
    private function checkPhpVersion(string $version)
    {
        $state = version_compare(PHP_VERSION, $version, 'ge') ? 'success' : 'fail';
        $label = sprintf($this->lang['syscheck_phpversion'], $version);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }

    /** @return object{state:string,label:string,stateLabel:string} */
    private function checkExtension(string $extension, bool $isMandatory = true)
    {
        $state = extension_loaded($extension) ? 'success' : ($isMandatory ? 'fail' : 'warning');
        $label = sprintf($this->lang['syscheck_extension'], $extension);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }

    /** @return object{state:string,label:string,stateLabel:string} */
    private function checkXhVersion(string $version)
    {
        $state = version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'ge') ? 'success' : 'fail';
        $label = sprintf($this->lang['syscheck_xhversion'], $version);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }

    /** @return object{state:string,label:string,stateLabel:string} */
    private function checkWritability(string $folder)
    {
        $state = is_writable($folder) ? 'success' : 'warning';
        $label = sprintf($this->lang['syscheck_writable'], $folder);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }
}
