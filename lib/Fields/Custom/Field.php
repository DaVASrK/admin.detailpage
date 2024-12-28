<?php

namespace DVK\Admin\DetailPage\Fields\Custom;

use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

class Field extends AbstractField
{
    protected readonly \CAdminForm $tabControl;
    protected array $params;

    public function __construct(string $code)
    {
        $this->tabControl = GlobalValue::get('tabControl');
        $this->code = $code;
        $this->params = $this->getProperty();

        if ($this->params) {
            $this->id       = $this->params['ID'];
            $this->name     = $this->params['NAME'];
            $this->value    = $this->params['VALUE'];
            $this->hint     = $this->params['HINT'];
            $this->required = $this->params['IS_REQUIRED'] === 'Y';
        } else {
            $this->isShow = false;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_PROPERTY_<?= $this->id ?>">
            <td class="adm-detail-content-cell-l adm-detail-valign-top"
                style="text-align: right; padding-top: 11px; width: 40%">
                <?= $this->getTitleTemplate() ?>
            </td>
            <td style="width: 60%; text-align: left;" class="adm-detail-content-cell-r">
                <?
                $initDef = GlobalValue::get('historyId') <= 0 &&
                    !GlobalValue::get('bVarsFromForm') &&
                    GlobalValue::get('ID') <= 0 &&
                    !GlobalValue::get('bPropertyAjax');

                _ShowPropertyField(
                    'PROP[' . $this->id . ']',
                    $this->params,
                    $this->value,
                    $initDef,
                    GlobalValue::get('bVarsFromForm') || GlobalValue::get('bPropertyAjax'),
                    50000,
                    $this->tabControl->GetFormName(),
                    GlobalValue::get('bCopy')
                );
                ?>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }


    protected function isCanShow(bool|\Closure $value = true): bool
    {
        global $USER;
        if ($USER->isAdmin() && !empty($this->params)) {
            return true;
        }

        if (!$this->isShow || empty($this->params)) { return false; }

        if ($value instanceof \Closure) {
            $value = (bool)$value();
        }

        return $value;
    }

    protected function getProperty(): array
    {
        foreach (GlobalValue::get('PROP') as $property) {
            if ($this->code === $property['CODE']) { return $property; }
        }

        return [];
    }
}
