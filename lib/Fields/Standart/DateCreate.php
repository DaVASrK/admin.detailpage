<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\GlobalValue;

use DVK\Admin\DetailPage\Fields\AbstractField;

class DateCreate extends AbstractField
{
    protected string $name = 'Создан';
    protected string $code = 'DATE_CREATE';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        $createdBy = GlobalValue::get('str_CREATED_BY');

        ob_start();

        if (GlobalValue::get('ID') > 0 && !GlobalValue::get('bCopy')) {
            if ($this->value != ''):?>
                <tr>
                    <td class="adm-detail-content-cell-l" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
                    <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left"><?= $this->value ?><?
                        if (intval($createdBy) > 0):
                            ?>&nbsp;&nbsp;&nbsp;[<a
                            href="user_edit.php?lang=<?= LANGUAGE_ID; ?>&amp;ID=<?= $createdBy; ?>"><?= $createdBy ?></a>]<?
                            $user = \CUser::GetByID($createdBy)->Fetch();
                            if ($user):
                                echo '&nbsp;' . \CUser::FormatName(\CSite::GetNameFormat(), $user);
                            endif;
                        endif;
                        ?></td>
                </tr>
            <?endif;
        }

        return ob_get_clean();
    }
}
