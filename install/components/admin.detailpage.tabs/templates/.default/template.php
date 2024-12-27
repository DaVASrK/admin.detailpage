<?php
/**
 * @var array $arResult
 * @var \DVK\Admin\DetailPage\Entities\Tab $tab
 */
?>

<div id="<?= $arResult['ID'] ?>">
    <div class="tabs-header adm-detail-tabs-block">
        <? foreach($arResult['TABS'] as $index => $tab): ?>
            <span class="adm-detail-tab" data-id="tab_<?= $index+1 ?>">
                <?= $tab->getName() ?>
            </span>
        <? endforeach; ?>
    </div>
    <div class="tabs-content">
        <? foreach($arResult['TABS'] as $index => $tab): ?>
            <div class="tab" data-id="tab_<?= $index+1 ?>">
                <table style="width: 100%">
                    <tbody>
                        <? foreach($tab->getFields() as $field): ?>
                            <?= $field->getTemplate() ?>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        <? endforeach; ?>
    </div>
    <script>
        BX.ready(function(){
            BX.DVK.Admin.TabsBlock.create('<?= $arResult['ID'] ?>');
        });
    </script>
</div>
