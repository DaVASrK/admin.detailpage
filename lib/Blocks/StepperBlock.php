<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\Entities\Step;
use DVK\Admin\DetailPage\GlobalValue;

class StepperBlock extends AbstractBlock
{
    protected array $steps;
    protected bool $showTitle = true;


    public function __construct(array $steps, bool $showTitle = true, string $id = null)
    {
        parent::__construct($id);

        if (!$this->validate($steps)) {
            throw new \InvalidArgumentException('Array must contains only instances of Step class');
        }

        $this->steps       = $steps;
        $this->showTitle   = $showTitle;
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        echo '<tr><td colspan="2">';

        GlobalValue::get('APPLICATION')->includeComponent(
            'dvk:admin.detailpage.stepper',
            '.default',
            [
                'ID'           => $this->id,
                'STEPS'        => $this->steps,
                'SHOW_TITLE'   => $this->showTitle
            ]
        );

        echo '</td></tr>';

        return ob_get_clean();
    }

    protected function validate(array $steps): bool
    {
        foreach ($steps as $step) {
            if (!$step instanceof Step) {
                return false;
            }
        }

        return true;
    }
}
