<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $export_url
 * @var string $import_url
 * @var string $csrfToken
 * @var bool $hasXmlFile
 */
?>

<h1>Exchange – <?=$this->text('menu_main')?></h1>
<form method="post">
  <input type="hidden" name="exchange_token" value="<?=$this->raw($csrfToken)?>">
<?php if ($hasXmlFile):?>
  <p class="xh_warning">
    <?=$this->text('message_export_overwrite')?>
  </p>
<?php endif?>
  <p>
    <button formaction="<?=$this->esc($export_url)?>"><?=$this->text('label_export')?></button>
  </p>
  <hr>
<?php if ($hasXmlFile):?>
  <p class="xh_warning">
    <?=$this->text('message_import_overwrite')?>
  </p>
  <p>
    <button formaction="<?=$this->esc($import_url)?>"><?=$this->text('label_import')?></button>
<?php else:?>
  <p class="xh_info">
    <?=$this->text('message_no_import')?>
<?php endif?>
  </p>
</form>
