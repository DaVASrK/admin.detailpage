<?php
/**
 * @var array $arResult
 */
?>

<div id="<?= $arResult['ID'] ?>">
    <div class="toggle-top">
        <span class="adm-detail-title">
            <?= $arResult['TITLE'] ?>
        </span>
        <span class="adm-detail-title-setting-btn toggle-btn"></span>
    </div>
    <div class="toggle-content">
        <table>
            <tbody>
            <?php foreach ($arResult['FIELDS'] as $field) {
                echo $field->getTemplate();
            }?>
            </tbody>
        </table>
    </div>
    <script>
        BX.ready(function(){
            BX.DVK.Admin.ToggleBlock.create('<?= $arResult['ID'] ?>');
        });
    </script>
</div>
