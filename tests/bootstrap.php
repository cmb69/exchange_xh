<?php

require_once "./vendor/autoload.php";

require_once "../../cmsimple/classes/PageDataRouter.php";
require_once "../../cmsimple/classes/Pages.php";
require_once "../../cmsimple/functions.php";

require_once "../plib/classes/CsrfProtector.php";
require_once "../plib/classes/Request.php";
require_once "../plib/classes/Response.php";
require_once "../plib/classes/SystemChecker.php";
require_once "../plib/classes/Url.php";
require_once "../plib/classes/View.php";
require_once "../plib/classes/FakeRequest.php";
require_once "../plib/classes/FakeSystemChecker.php";

require_once "./classes/Dic.php";
require_once "./classes/ExchangeService.php";
require_once "./classes/ExportService.php";
require_once "./classes/ImportService.php";
require_once "./classes/InfoController.php";
require_once "./classes/MainAdminController.php";

const CMSIMPLE_XH_VERSION = "1.8";
const CMSIMPLE_URL = "http://example.com/";
