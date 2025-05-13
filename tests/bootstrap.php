<?php

require_once "./vendor/autoload.php";

require_once "../../cmsimple/classes/PageDataRouter.php";
require_once "../../cmsimple/classes/Pages.php";
require_once "../../cmsimple/functions.php";

require_once "../plib/classes/CsrfProtector.php";
require_once "../plib/classes/Document.php";
require_once "../plib/classes/DocumentStore.php";
require_once "../plib/classes/Request.php";
require_once "../plib/classes/Response.php";
require_once "../plib/classes/SystemChecker.php";
require_once "../plib/classes/Url.php";
require_once "../plib/classes/View.php";
require_once "../plib/classes/FakeRequest.php";
require_once "../plib/classes/FakeSystemChecker.php";

require_once "./classes/model/XhContents.php";
require_once "./classes/model/XhPage.php";
require_once "./classes/model/XmlContents.php";
require_once "./classes/model/XmlPage.php";
require_once "./classes/model/Contents.php";
require_once "./classes/model/Page.php";
require_once "./classes/Dic.php";
require_once "./classes/InfoController.php";
require_once "./classes/MainAdminController.php";

const CMSIMPLE_XH_VERSION = "1.8";
const CMSIMPLE_URL = "http://example.com/";
