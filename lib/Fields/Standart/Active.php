<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

class Active extends AbstractField
{
    protected string $name = 'Активность';
    protected string $code = 'ACTIVE';

    public function __construct()
    {
        parent::__construct();

        $this->value = ['Y', 'N'];
    }


    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        if (is_array($this->value)) {
            $inputs = '<input type="hidden" name="' . $this->code . '" value="' . htmlspecialcharsbx($this->value[1]) . '">
				<input type="checkbox" name="' . $this->code . '" value="' . htmlspecialcharsbx($this->value[0]) . '"' .
                ($this->isChecked() ? ' checked' : '') .
                ($this->isDisabled ? ' disabled' : '');
        } else {
            $inputs = '<input type="checkbox" name="' . $this->code . '" value="' . htmlspecialcharsbx($this->value) . '"' .
                ($this->isChecked() ? ' checked' : '') .
                ($this->isDisabled ? ' disabled' : '');
        }
        $inputs .= '>';

        $view = '<td style="width: 40%" class="adm-detail-content-cell-l">' . $this->getTitleTemplate() . '</td>' .
            '<td class="adm-detail-content-cell-r" style="width: 60%; text-align: left">' . $inputs . '</td>';

        return $view;
    }

    protected function getHidden(): string
    {
        if (is_array($this->value)) {
            $hidden = '<input type="hidden" name="' . $this->code . '" value="' . htmlspecialcharsbx($this->isChecked() ? $this->value[0] : $this->value[1]) . '">';
        } else {
            $hidden = '<input type="hidden" name="' . $this->code . '" value="' . htmlspecialcharsbx($this->value) . '">';
        }

        return $hidden;
    }

    protected function isChecked(): bool
    {
        return GlobalValue::get('str_ACTIVE') == 'Y';
    }
}
