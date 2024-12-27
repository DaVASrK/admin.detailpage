<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\Entities\Tab;
use DVK\Admin\DetailPage\GlobalValue;

class TabsBlock extends AbstractBlock
{
    protected array $tabs;

    public function __construct(array $tabs, string $id = null)
    {
        parent::__construct($id);

        if (!$this->validate($tabs)) {
            throw new \InvalidArgumentException('Array must contains only instances of Tab class');
        }

        $this->tabs = $tabs;
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        echo '<tr><td colspan="2">';

        GlobalValue::get('APPLICATION')->includeComponent(
            'dvk:admin.detailpage.tabs',
            '.default',
            [
                'ID'   => $this->id,
                'TABS' => $this->tabs,
            ]
        );

        echo '</td></tr>';

        return ob_get_clean();
    }

    protected function validate(array $tabs): bool
    {
        foreach ($tabs as $tab) {
            if (!$tab instanceof Tab) {
                return false;
            }
        }

        return true;
    }
}
