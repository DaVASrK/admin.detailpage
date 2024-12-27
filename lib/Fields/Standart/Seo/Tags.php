<?php

namespace DVK\Admin\DetailPage\Fields\Standart\Seo;

use Bitrix\Main\Localization\Loc;
use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

class Tags extends AbstractField
{
    protected string $name = 'Теги';
    protected string $code = 'TAGS';

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_<?= $this->code ?>">
            <td class="adm-detail-content-cell-l"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="text-align: left;">
                <?if(\Bitrix\Main\Loader::includeModule('search')):
                    $arLID = [];
                    $rsSites = \CIBlock::GetSite(GlobalValue::get('IBLOCK_ID'));
                    while($arSite = $rsSites->Fetch())
                        $arLID[] = $arSite["LID"];
                    echo InputTags("TAGS", htmlspecialcharsback($this->value), $arLID, 'size="55"');
                else:?>
                    <input type="text" size="20" name="TAGS" maxlength="255" value="<?= $this->value?>">
                <?endif?>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }

    protected function getTitleTemplate(): string
    {
        $view = $this->getHintTemplate();

        if ($this->required) {
            $view .= '<span class="adm-required-field">' . $this->name . ':</span>';
        } else {
            $view .= $this->name . ':</span>';
        }

        $view .= '<br>' . Loc::getMessage("IBLOCK_ELEMENT_EDIT_TAGS_TIP");

        return $view;
    }
}
