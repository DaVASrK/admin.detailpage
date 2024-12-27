<?php

use \DVK\Admin\DetailPage\Fields\StandartField;

\Bitrix\Main\Loader::includeModule('dvk.admin.detailpage');

$page = new \DVK\Admin\DetailPage\ElementEditPage();

$page->setTabs([
    [
        'DIV'    => 'params',
        'TAB'    => 'Параметры',
        'ICON'   => 'iblock_element',
        'TITLE'  => 'Основные параметры',
        'FIELDS' => [],
    ]
]);


$page->begin();

/* FIRST TAB */
StandartField::id();


$page->addButtons();
$page->show();
