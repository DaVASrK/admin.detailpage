<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;

class Id extends AbstractField
{
    protected string $name = 'ID';
    protected string $code = 'ID';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();
        ?>
        <tr>
            <td class="adm-detail-content-cell-l" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left"><?= $this->value ?></td>
        </tr>
        <?

        return ob_get_clean();
    }
}
