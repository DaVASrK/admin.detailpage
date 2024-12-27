<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\Contracts\ITemplate;
use DVK\Admin\DetailPage\Fields\AbstractField;

class TwoColumns extends AbstractBlock
{
    const COLUMNS_COUNT = 2;
    protected array $columns = [];

    public function __construct(array $columns, string $id = null)
    {
        parent::__construct($id);

        if (!$this->validate($columns)) {
            throw new \InvalidArgumentException('Unsupported type of field');
        }

        $this->columns = $columns;
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        $view = '<tr>';

        for ($i = 0; $i < self::COLUMNS_COUNT; $i++) {
            $view .= '<td class="adm-detail-valign-top" style="width: 50%"><table width="100%"><tbody>';
            foreach ($this->columns[$i] as $field) {
                $view .= $field->getTemplate();
            }
            $view .= '</tbody></table></td>';
        }

        $view .= '</tr>';

        return $view;
    }

    protected function validate(array $columns): bool
    {
        for ($i = 0; $i < self::COLUMNS_COUNT; $i++) {
            foreach ($columns[$i] as $field) {
                if (!$field instanceof AbstractField &&
                    !$field instanceof AbstractBlock &&
                    !$field instanceof ITemplate
                ) {
                    return false;
                }
            }
        }

        return true;
    }
}
