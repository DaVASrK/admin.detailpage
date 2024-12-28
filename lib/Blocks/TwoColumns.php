<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\Contracts\ITemplate;
use DVK\Admin\DetailPage\Fields\AbstractField;

class TwoColumns extends AbstractBlock
{
    protected array $columns = [];

    public function __construct(array $firstColumn, array $secondColumn, string $id = null)
    {
        parent::__construct($id);

        if (!$this->validate([$firstColumn, $secondColumn])) {
            throw new \InvalidArgumentException('Unsupported type of field');
        }

        $this->columns = [$firstColumn, $secondColumn];
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        $view = '<tr>';

        foreach ($this->columns as $column) {
            $view .= '<td class="adm-detail-valign-top" style="width: 50%"><table width="100%"><tbody>';
            foreach ($column as $field) {
                $view .= $field->getTemplate();
            }
            $view .= '</tbody></table></td>';
        }

        $view .= '</tr>';

        return $view;
    }

    protected function validate(array $columns): bool
    {
        foreach ($columns as $column) {
            foreach ($column as $field) {
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
