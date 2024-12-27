<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\Contracts\IBlock;
use DVK\Admin\DetailPage\GlobalValue;

abstract class AbstractBlock implements IBlock
{
    protected readonly \CAdminForm $tabControl;
    protected string $id;
    protected bool $isShow = true;

    public function __construct(string $id = null)
    {
        $this->id = ($id !== null) ? $id : uniqid();

        $this->tabControl = GlobalValue::get('tabControl');
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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

    public function show(bool|\Closure $value = true): void
    {
        if (!$this->isCanShow($value)) { return; }

        $this->tabControl->BeginCustomField($this->id, '');
        echo $this->getTemplate();
        $this->tabControl->EndCustomField($this->id);
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