<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;

class PreviewText extends AbstractField
{
    protected string $name = '';
    protected string $code = 'PREVIEW_TEXT';
    protected string $typeValue;

    public function __construct()
    {
        parent::__construct();

        $this->typeValue = GlobalValue::get('str_PREVIEW_TEXT_TYPE');
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr>
            <td colspan="2" align="center">
                <? \CFileMan::AddHTMLEditorFrame(
                    $this->code,
                    $this->value,
                    $this->code . '_TYPE',
                    $this->typeValue,
                    [
                        'height' => 450,
                        'width'  => '100%',
                    ]
                ); ?>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }
}
