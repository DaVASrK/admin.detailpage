<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;

class Sort extends AbstractField
{
    protected string $name = 'Сортировка';
    protected string $code = 'SORT';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_<?= $this->code ?>">
            <td class="adm-detail-content-cell-l" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left">
                <input type="text" name="<?= $this->code ?>" value="<?= $this->value ?>"
                    id="<?= $this->code ?>"
                    <?= $this->getDisabled() ?>
                >
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
