<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

class TimestampX extends AbstractField
{
    protected string $name = 'Изменен';
    protected string $code = 'TIMESTAMP_X';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        $modifiedBy = GlobalValue::get('str_MODIFIED_BY');

        ob_start();

        if (GlobalValue::get('ID') > 0 && !GlobalValue::get('bCopy')) {
            ?>
            <tr>
            <td class="adm-detail-content-cell-l" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left"><?= $this->value; ?><?
                if (intval($modifiedBy) > 0):
                    ?>&nbsp;&nbsp;&nbsp;[<a
                    href="user_edit.php?lang=<?= LANGUAGE_ID; ?>&amp;ID=<?= $modifiedBy; ?>"><?= $modifiedBy ?></a>]<?
                    $user = \CUser::GetByID($modifiedBy)->Fetch();
                    if ($user):
                        echo '&nbsp;' . \CUser::FormatName(\CSite::GetNameFormat(), $user);
                    endif;
                endif ?></td>
            </tr><?
        }

        return ob_get_clean();
    }
}
