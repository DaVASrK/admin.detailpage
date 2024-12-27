<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use Bitrix\Main\Localization\Loc;
use DVK\Admin\DetailPage\GlobalValue;

use DVK\Admin\DetailPage\Fields\AbstractField;

class Code extends AbstractField
{
    protected string $name = 'Символьный код';
    protected string $code = 'CODE';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_<?= $this->code ?>">
            <td class="adm-detail-content-cell-l"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="white-space: nowrap; text-align: left">
                <input type="text" size="70" name="<?= $this->code ?>" id="<?= $this->code ?>" maxlength="255"
                    value="<?= $this->value ?>"
                    <?= $this->getDisabled() ?>
                >
                <? if (!$this->isDisabled && !$this->isReadonly): ?>
                <img
                    id="code_link" title="<?= Loc::getMessage('IBEL_E_LINK_TIP') ?>" class="linked"
                    src="/bitrix/themes/.default/icons/iblock/<?= (GlobalValue::get('bLinked') ? 'link.gif' : 'unlink.gif') ?>"
                    onclick="set_linked()"/>
                <? endif; ?>
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
