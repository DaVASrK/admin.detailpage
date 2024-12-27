<?php

namespace DVK\Admin\DetailPage\Fields;

use DVK\Admin\DetailPage\Contracts\IField;
use DVK\Admin\DetailPage\GlobalValue;

abstract class AbstractField implements IField
{
    protected readonly \CAdminForm $tabControl;
    protected string $name;
    protected string $code;
    protected mixed $value = null;
    protected string $hint = '';
    protected bool $required = false;
    protected bool $showHint = true;
    protected bool $isReadonly = false;
    protected bool $isDisabled = false;
    protected bool $isShow = true;


    public function __construct()
    {
        $this->tabControl = GlobalValue::get('tabControl');
        $this->value      = GlobalValue::get('str_' . $this->code);

        if (isset(GlobalValue::get('arIBlock')['FIELDS'][$this->code]['IS_REQUIRED'])) {
            $this->required = GlobalValue::get('arIBlock')['FIELDS'][$this->code]['IS_REQUIRED'] === 'Y';
        }
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getHint(): string
    {
        return $this->hint;
    }

    public function name(string $value): self
    {
        $this->name = $value;
        return $this;
    }

    public function value(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function hint(string|bool|\Closure $value): self
    {
        if ($value instanceof \Closure) {
            $value = (bool)$value();
        }

        switch (gettype($value)) {
            case 'boolean':
                $this->showHint = $value; break;
            case 'string':
                $this->hint = $value; break;
        }

        return $this;
    }

    public function required(bool|\Closure $value): self
    {
        if ($value instanceof \Closure) {
            $value = (bool)$value();
        }

        $this->required = $value;

        return $this;
    }

    public function onlyFor(array $values): self
    {
        $this->isShow = $this->inGroup($values);

        return $this;
    }

    public function deniedFor(array $values): self
    {
        $this->isShow = !$this->inGroup($values);

        return $this;
    }

    public function readonly(bool|\Closure $value = true): self
    {
        if ($value instanceof \Closure) {
            $value = (bool)$value();
        }

        $this->isReadonly = $value;


        return $this;
    }

    public function disabled(bool|\Closure $value = true): self
    {
        if ($value instanceof \Closure) {
            $value = (bool)$value();
        }

        $this->isDisabled = $value;

        return $this;
    }

    public function show(bool|\Closure $value = true): void
    {
        if (!$this->isCanShow($value)) { return; }

        $this->tabControl->BeginCustomField($this->code, $this->name . ':', $this->required);
        echo $this->getTemplate();
        $this->tabControl->EndCustomField($this->code, $this->getHidden());
    }


    protected function getTitleTemplate(): string
    {
        $view = $this->getHintTemplate();

        if ($this->required) {
            $view .= '<span class="adm-required-field">' . $this->name . ':</span>';
        } else {
            $view .= $this->name . ':</span>';
        }

        return $view;
    }

    protected function getHintTemplate(): string
    {
        if (!$this->showHint) { return ''; }

        $hint = '<span id="hint_' . $this->code . '"></span>';
        $hint .= '<script type="text/javascript">BX.hint_replace(BX(\'hint_' .$this->code . '\'), \'' .
            \CUtil::JSEscape(htmlspecialcharsbx($this->hint)) . '\');</script>&nbsp;';

        return $hint;
    }

    protected function getHidden(): string
    {
        return '';
    }

    protected function getDisabled(): string
    {
        if ($this->isDisabled) { return ' disabled'; }
        if ($this->isReadonly) { return ' readonly'; }

        return '';
    }

    protected function inGroup(array $values): bool
    {
        $ids   = [];
        $codes = [];

        foreach ($values as $value) {
            if (is_numeric($value)) {
                $ids[] = $value;
                continue;
            }

            $codes[] = $value;
        }

        global $USER;
        $filter = ['USER_ID' => $USER->GetID()];

        if (!empty($ids) && !empty($codes)) {
            $filter[] = [
                'LOGIC'      => 'OR',
                'GROUP_ID'   => $ids,
                'GROUP_CODE' => $codes,
            ];
        } elseif (!empty($ids)) {
            $filter['GROUP_ID'] = $ids;
        } else {
            $filter['GROUP_CODE'] = $codes;
        }

        $res = \Bitrix\Main\UserGroupTable::getList([
            'select' => ['GROUP_ID', 'GROUP_CODE' => 'GROUP.STRING_ID'],
            'filter' => $filter,
            'limit'  => 1
        ]);

        return $res->getSelectedRowsCount() !== 0;
    }

    protected function isCanShow(bool|\Closure $value = true): bool
    {
        global $USER;
        if ($USER->isAdmin()) {
            return true;
        }

        if (!$this->isShow) { return false; }

        if ($value instanceof \Closure) {
            $value = (bool)$value();
        }

        return $value;
    }
}
