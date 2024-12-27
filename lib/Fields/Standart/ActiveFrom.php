<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;
use Bitrix\Main\Localization\Loc;

class ActiveFrom extends AbstractField
{
    protected string $name = 'Начало активности';
    protected string $code = 'ACTIVE_FROM';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_<?= $this->code ?>">
            <td class="adm-detail-content-cell-l"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="text-align: left">
                <div class="adm-input-wrap adm-input-wrap-calendar">
                    <input class="adm-input adm-input-calendar" type="text" name="<?= $this->code ?>" size="22"
                        value="<?= htmlspecialcharsbx($this->value) ?>"
                        <?= $this->getDisabled() ?>
                    >
                    <? if (!$this->isDisabled && !$this->isReadonly): ?>
                        <span class="adm-calendar-icon" title="<?= Loc::getMessage("admin_lib_calend_title") ?>"
                            onclick="BX.calendar({node:this, field:'<?= $this->code ?>', form: '', bTime: 'true', bHideTime: false});"></span>
                    <? endif; ?>
                </div>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }

    protected function getHidden(): string
    {
        return '<input type="hidden" id="' . $this->code . '" name="' . $this->code . '" value="' . $this->value . '">';
    }
}
