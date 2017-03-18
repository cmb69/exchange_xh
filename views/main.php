<h1>Exchange – <?=$this->text('menu_main')?></h1>
<form action="<?=$this->url()?>" method="post">
    <input type="hidden" name="admin" value="<?=$this->admin()?>">
    <p>
        <button name="action" value="export"><?=$this->text('label_export')?></button>
    </p>
    <p>
        <button name="action" value="import"><?=$this->text('label_import')?></button>
    </p>
</form>
