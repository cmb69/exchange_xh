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

<section class="exchange_main">
  <h1><?=$this->text('menu_main')?></h1>
  <form method="post">
    <fieldset>
      <legend><?=$this->text('label_export')?></legend>
      <input type="hidden" name="exchange_token" value="<?=$this->esc($csrfToken)?>">
<?if ($hasXmlFile):?>
      <p class="xh_warning"><?=$this->text('message_export_overwrite')?></p>
<?endif?>
      <p>
        <button formaction="<?=$this->esc($export_url)?>"><?=$this->text('label_export')?></button>
      </p>
    </fieldset>
    <fieldset>
      <legend><?=$this->text('label_import')?></legend>
<?if ($hasXmlFile):?>
      <p class="xh_warning"><?=$this->text('message_import_overwrite')?></p>
      <p>
        <button formaction="<?=$this->esc($import_url)?>"><?=$this->text('label_import')?></button>
      </p>
<?else:?>
      <p class="xh_info"><?=$this->text('message_no_import')?></p>
<?endif?>
    </fieldset>
  </form>
</section>
