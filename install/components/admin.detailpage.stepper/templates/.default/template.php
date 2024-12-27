<?php
/**
 * @var array $arResult
 * @var \DVK\Admin\DetailPage\Entities\Step $step
 */
?>

<div id="<?= $arResult['ID'] ?>" class="step-container">
    <div class="step-chain">
        <? for ($i = 0; $i < count($arResult['STEPS']); $i++): ?>
            <div class="step<?= ($i === 0) ? ' active' : '' ?>">
            <? if ($i === 0): ?>
                <div class="step-circle"><span><?= $i+1 ?></span></div>
            </div>
            <? continue; endif; ?>

                <div class="step-line"></div>
                <div class="step-circle"><span><?= $i+1 ?></span></div>
            </div>
        <? endfor; ?>
    </div>
    <div class="step-tabs">
        <? foreach ($arResult['STEPS'] as $index => $step): ?>
            <div class="step-tab<?= $index === 0 ? ' active' : '' ?>" data-id="tab_<?= $index+1 ?>">
                <? if ($arResult['SHOW_TITLE']): ?>
                    <span class="adm-detail-title"><?= $step->getName() ?></span>
                <? endif; ?>
                <table style="width:100%">
                    <tbody>
                        <? foreach ($step->getFields() as $field): ?>
                            <?= $field->getTemplate() ?>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        <? endforeach; ?>
        <div class="buttons">
            <input type="button" class="prev" value="Назад">
            <input type="button" class="next adm-btn-save" value="Далее">
        </div>
    </div>
    <script>
        BX.ready(function(){
            BX.DVK.Admin.StepperBlock.create('<?= $arResult['ID'] ?>');
        });
    </script>
</div>
