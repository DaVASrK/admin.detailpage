<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\Contracts\ITemplate;
use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

class ToggleBlock extends AbstractBlock
{
    protected string $title;
    protected array $fields;

    public function __construct(string $title, array $fields = [], string $id = null)
    {
        parent::__construct($id);
        $this->title  = $title;
        $this->fields = $fields;

        $this->validate();
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        echo '<tr><td colspan="2">';

        GlobalValue::get('APPLICATION')->includeComponent(
            'dvk:admin.detailpage.toggle',
            '.default',
            [
                'ID'     => $this->id,
                'TITLE'  => $this->title,
                'FIELDS' => $this->fields,
            ]
        );

        echo '</td></tr>';

        return ob_get_clean();
    }

    protected function validate(): void
    {
        foreach ($this->fields as $field) {
            if (!$field instanceof AbstractField &&
                !$field instanceof AbstractBlock &&
                !$field instanceof ITemplate
            ) {
                throw new \InvalidArgumentException('Unsupported type of field');
            }
        }
    }
}
