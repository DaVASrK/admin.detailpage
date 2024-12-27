<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\GlobalValue;

class Component extends AbstractBlock
{
    protected string $componentName;
    protected string $template;
    protected array $params;

    public function __construct(string $componentName, string $template = '.default', array $params = [], string $id = null)
    {
        parent::__construct($id);
        $this->componentName = $componentName;
        $this->template      = $template;
        $this->params        = $params;
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr>
            <td colspan="2">
                <?= GlobalValue::get('APPLICATION')->IncludeComponent(
                    $this->componentName,
                    $this->template,
                    $this->params,
                    false
                ); ?>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }
}
