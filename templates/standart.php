<?php

use Bitrix\Main\Localization\Loc;
use \DVK\Admin\DetailPage\Blocks\Block;
use \DVK\Admin\DetailPage\Fields\StandartField;
use \DVK\Admin\DetailPage\IBlockSettings;

\Bitrix\Main\Loader::includeModule('dvk.admin.detailpage');

$page = new \DVK\Admin\DetailPage\ElementEditPage();

$page->setTabs([
    [
        'DIV'    => 'params',
        'TAB'    => 'Параметры',
        'ICON'   => 'iblock_element',
        'TITLE'  => 'Основные параметры',
        'FIELDS' => [],
    ],
    [
        'DIV'    => 'preview',
        'TAB'    => 'Анонс',
        'ICON'   => 'iblock_element',
        'TITLE'  => 'Информация для анонса',
        'FIELDS' => [],
    ],
    [
        'DIV'    => 'detail',
        'TAB'    => 'Подробно',
        'ICON'   => 'iblock_element',
        'TITLE'  => 'Детальная информация',
        'FIELDS' => [],
    ],
    [
        'DIV'    => 'seo',
        'TAB'    => 'SEO',
        'ICON'   => 'iblock_element',
        'TITLE'  => 'Настройки SEO информации',
        'FIELDS' => [],
        "ONSELECT" => "InheritedPropertiesTemplates.onTabSelect();",
    ]
]);

if (IBlockSettings::hasSections()) {
    $page->addTab([
        'DIV'    => 'sections',
        'TAB'    => 'Разделы',
        'ICON'   => 'iblock_element',
        'TITLE'  => 'Разделы',
        'FIELDS' => [],
    ]);
}


$page->begin();

/* PARAMS TAB */
StandartField::id();
StandartField::dateCreate();
StandartField::timestampX();
StandartField::active();
StandartField::activeFromTo();
StandartField::name();
StandartField::code();
StandartField::xmlId();
StandartField::sort();


/* PREVIEW TAB */
$page->nextTab();

StandartField::previewPicture();
Block::section(Loc::getMessage('IBLOCK_FIELD_PREVIEW_TEXT'), 'PREVIEW_TEXT_LABEL');
StandartField::previewText();


/* DETAIL TAB */
$page->nextTab();

StandartField::detailPicture();
Block::section(Loc::getMessage('IBLOCK_FIELD_DETAIL_TEXT'), 'DETAIL_TEXT_LABEL');
StandartField::detailText();


/* SEO TAB */
$page->nextTab();

Block::seo();


/* SECTIONS TAB */
if (IBlockSettings::hasSections()) {
    $page->nextTab();
    StandartField::sections();
}


$page->addButtons();
$page->show();
