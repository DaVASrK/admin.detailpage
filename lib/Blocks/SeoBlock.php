<?php

namespace DVK\Admin\DetailPage\Blocks;

use Bitrix\Main\Localization\Loc;
use DVK\Admin\DetailPage\Fields\Standart\Seo;
use DVK\Admin\DetailPage\Fields\StandartField;

class SeoBlock extends AbstractBlock
{
    public function show(bool|\Closure $value = true): void
    {
        if (!$this->isCanShow($value)) { return; }

        StandartField::metaTitle();
        StandartField::metaKeywords();
        StandartField::metaDescription();
        StandartField::pageTitle();

        Block::section(
            Loc::getMessage('IBEL_E_SEO_FOR_ELEMENTS_PREVIEW_PICTURE'),
            'IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE'
        );
        StandartField::previewPictureAlt();
        StandartField::previewPictureTitle();
        StandartField::previewPictureName();

        Block::section(
            Loc::getMessage('IBEL_E_SEO_FOR_ELEMENTS_DETAIL_PICTURE'),
            'IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE'
        );
        StandartField::detailPictureAlt();
        StandartField::detailPictureTitle();
        StandartField::detailPictureName();

        Block::section(
            Loc::getMessage('IBLOCK_EL_TAB_MO'),
            'SEO_ADDITIONAL'
        );
        StandartField::tags();
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        $view  = (new Seo\MetaTitle())->getTemplate();
        $view .= (new Seo\MetaKeywords())->getTemplate();
        $view .= (new Seo\MetaDescription())->getTemplate();
        $view .= (new Seo\PageTitle())->getTemplate();

        $view .= (new Section(
            Loc::getMessage('IBEL_E_SEO_FOR_ELEMENTS_PREVIEW_PICTURE'),
            'IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE'
        ))->getTemplate();

        $view .= (new Seo\PreviewPictureAlt())->getTemplate();
        $view .= (new Seo\PreviewPictureTitle())->getTemplate();
        $view .= (new Seo\PreviewPictureName())->getTemplate();

        $view .= (new Section(
            Loc::getMessage('IBEL_E_SEO_FOR_ELEMENTS_DETAIL_PICTURE'),
            'IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE'
        ))->getTemplate();

        $view .= (new Seo\DetailPictureAlt())->getTemplate();
        $view .= (new Seo\DetailPictureTitle())->getTemplate();
        $view .= (new Seo\DetailPictureName())->getTemplate();

        $view .= (new Section(
            Loc::getMessage('IBLOCK_EL_TAB_MO'),
            'SEO_ADDITIONAL'
        ))->getTemplate();

        return $view;
    }
}
