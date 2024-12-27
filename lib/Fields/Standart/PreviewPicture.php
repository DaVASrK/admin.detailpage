<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;
use DVK\Admin\DetailPage\GlobalValue;
use DVK\Admin\DetailPage\IBlockSettings;

class PreviewPicture extends AbstractField
{
    protected string $name = 'Картинка для анонса';
    protected string $code = 'PREVIEW_PICTURE';

    public function __construct()
    {
        parent::__construct();

        if (GlobalValue::get('bVarsFromForm') &&
            !array_key_exists('PREVIEW_PICTURE', $_REQUEST) &&
            GlobalValue::get('arElement')
        ) {
            $this->value = intval(GlobalValue::get('arElement')['PREVIEW_PICTURE']);
        }
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_<?= $this->code ?>" class="adm-detail-file-row">
            <td class="adm-detail-content-cell-l adm-detail-valign-top" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
            <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left">
                <? if (GlobalValue::get('historyId') > 0):
                    echo \CFileInput::Show(
                        $this->code,
                        $this->value,
                        [
                            'IMAGE'       => 'Y',
                            'PATH'        => 'Y',
                            'FILE_SIZE'   => 'Y',
                            'DIMENSIONS'  => 'Y',
                            'IMAGE_POPUP' => 'Y',
                            'MAX_SIZE'    => [
                                'W' => IBlockSettings::get('detail_image_size'),
                                'H' => IBlockSettings::get('detail_image_size'),
                            ],
                        ]
                    );
                else:
                    echo \Bitrix\Main\UI\FileInput::createInstance(
                        [
                            'name'        => $this->code,
                            'description' => true,
                            'upload'      => true,
                            'allowUpload' => 'I',
                            'medialib'    => true,
                            'fileDialog'  => true,
                            'cloud'       => true,
                            'delete'      => true,
                            'maxCount'    => 1,
                        ]
                    )->show(
                        (GlobalValue::get('bVarsFromForm') ? $_REQUEST['PREVIEW_PICTURE']
                            : (GlobalValue::get('ID') > 0 && !GlobalValue::get('bCopy') ? $this->value : 0)),
                        GlobalValue::get('bVarsFromForm')
                    );
                endif; ?>
            </td>
        </tr>
        <?

        return ob_get_clean();
    }
}
