<?php

namespace DVK\Admin\DetailPage\Fields\Standart\Seo;

use Bitrix\Main\Localization\Loc;
use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

abstract class AbstractSeoField extends AbstractField
{
    public function __construct()
    {
        parent::__construct();

        $this->value = GlobalValue::get('str_IPROPERTY_TEMPLATES');
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr class="adm-detail-valign-top">
            <td class="adm-detail-content-cell-l" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left;"><?= IBlockInheritedPropertyInput(
                GlobalValue::get('IBLOCK_ID'),
                $this->code,
                $this->value,
                "E",
                Loc::getMessage("IBEL_E_SEO_OVERWRITE"))?>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }

    public function show(bool|\Closure $value = true): void
    {
        if (!$this->isCanShow($value)) { return; }

        $this->tabControl->BeginCustomField("IPROPERTY_TEMPLATES_" . $this->code, $this->name . ':', $this->required);
        echo $this->getTemplate();
        $this->tabControl->EndCustomField("IPROPERTY_TEMPLATES_" . $this->code, $this->getHidden());
    }

    protected function getHidden(): string
    {
        return IBlockInheritedPropertyHidden(
            $this->IBLOCK_ID,
            $this->code,
            $this->value,
            "E",
            Loc::getMessage("IBEL_E_SEO_OVERWRITE")
        );
    }
}
