<?php

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

?>
<h1>Exchange – <?=$this->text('menu_main')?></h1>
<form action="<?=$this->esc($url)?>" method="post">
    <input type="hidden" name="admin" value="<?=$this->esc($admin)?>">
    <?=$this->raw($csrfToken)?>
<?php if ($hasXmlFile):?>
    <p class="xh_warning">
        <?=$this->text('message_export_overwrite')?>
    </p>
<?php endif?>
    <p>
        <button name="action" value="export"><?=$this->text('label_export')?></button>
    </p>
    <hr>
<?php if ($hasXmlFile):?>
    <p class="xh_warning">
        <?=$this->text('message_import_overwrite')?>
    </p>
    <p>
        <button name="action" value="import"><?=$this->text('label_import')?></button>
<?php else:?>
    <p class="xh_info">
        <?=$this->text('message_no_import')?>
<?php endif?>
    </p>
</form>
