<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var string $version
 * @var list<object{state:string,label:string,stateLabel:string}> $checks
 */
?>

<section class="exchange_plugininfo">
  <h1>Exchange <?=$this->esc($version)?></h1>
  <section class="exchange_syscheck">
    <h2><?=$this->text('syscheck_title')?></h2>
<?foreach ($checks as $check):?>
    <p class="xh_<?=$this->esc($check->state)?>"><?=$this->text('syscheck_message', $check->label, $check->stateLabel)?></p>
<?endforeach?>
  </section>
</section>
