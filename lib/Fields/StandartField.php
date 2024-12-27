<?php

namespace DVK\Admin\DetailPage\Fields;

use DVK\Admin\DetailPage\Fields\Standart as Field;
use DVK\Admin\DetailPage\GlobalValue;

class StandartField
{
    protected \CAdminForm $tabControl;


    public function __construct()
    {
        $this->tabControl = GlobalValue::get('tabControl');
    }


    public static function get(string $code): AbstractField
    {
        switch ($code) {
            case 'ID':              return new Field\Id();
            case 'DATE_CREATE':     return new Field\DateCreate();
            case 'TIMESTAMP_X':     return new Field\TimestampX();
            case 'ACTIVE':          return new Field\Active();
            case 'ACTIVE_FROM':     return new Field\ActiveFrom();
            case 'ACTIVE_TO':       return new Field\ActiveTo();
            case 'NAME':            return new Field\Name();
            case 'CODE':            return new Field\Code();
            case 'XML_ID':          return new Field\XmlId();
            case 'SORT':            return new Field\Sort();
            case 'PREVIEW_PICTURE': return new Field\PreviewPicture();
            case 'PREVIEW_TEXT':    return new Field\PreviewText();
            case 'DETAIL_PICTURE':  return new Field\DetailPicture();
            case 'DETAIL_TEXT':     return new Field\DetailText();
            case 'ELEMENT_META_TITLE':       return new Field\Seo\MetaTitle();
            case 'ELEMENT_META_KEYWORDS':    return new Field\Seo\MetaKeywords();
            case 'ELEMENT_META_DESCRIPTION': return new Field\Seo\MetaDescription();
            case 'ELEMENT_PAGE_TITLE':       return new Field\Seo\PageTitle();
            case 'ELEMENT_PREVIEW_PICTURE_FILE_ALT':   return new Field\Seo\PreviewPictureAlt();
            case 'ELEMENT_PREVIEW_PICTURE_FILE_TITLE': return new Field\Seo\PreviewPictureTitle();
            case 'ELEMENT_PREVIEW_PICTURE_FILE_NAME':  return new Field\Seo\PreviewPictureName();
            case 'ELEMENT_DETAIL_PICTURE_FILE_ALT':    return new Field\Seo\DetailPictureAlt();
            case 'ELEMENT_DETAIL_PICTURE_FILE_TITLE':  return new Field\Seo\DetailPictureTitle();
            case 'ELEMENT_DETAIL_PICTURE_FILE_NAME':   return new Field\Seo\DetailPictureName();
            case 'TAGS':     return new Field\Seo\Tags();
            case 'SECTIONS': return new Field\Sections();
            default: throw new \Exception("Unknown field");
        }
    }

    public static function show(string $code): void
    {
        (self::get($code))->show();
    }

    public static function id(): void
    {
        (new Field\Id())->show();
    }

    public static function dateCreate(): void
    {
        (new Field\DateCreate())->show();
    }

    public static function timestampX(): void
    {
        (new Field\TimestampX())->show();
    }

    public static function active(): void
    {
        (new Field\Active())->show();
    }

    public static function activeFrom(): void
    {
        (new Field\ActiveFrom())->show();
    }

    public static function activeTo(): void
    {
        (new Field\ActiveTo())->show();
    }

    public static function activeFromTo(): void
    {
        self::activeFrom();
        self::activeTo();
    }

    public static function name(): void
    {
        (new Field\Name())->show();
    }

    public static function code(): void
    {
        (new Field\Code())->show();
    }

    public static function xmlId(): void
    {
        (new Field\XmlId())->show();
    }

    public static function sort(): void
    {
        (new Field\Sort())->show();
    }

    public static function previewPicture(): void
    {
        (new Field\PreviewPicture())->show();
    }

    public static function previewText(): void
    {
        (new Field\PreviewText())->show();
    }

    public static function detailPicture(): void
    {
        (new Field\DetailPicture())->show();
    }

    public static function detailText(): void
    {
        (new Field\DetailText())->show();
    }

    public static function metaTitle(): void
    {
        (new Field\Seo\MetaTitle())->show();
    }

    public static function metaKeywords(): void
    {
        (new Field\Seo\MetaKeywords())->show();
    }

    public static function metaDescription(): void
    {
        (new Field\Seo\MetaDescription())->show();
    }

    public static function pageTitle(): void
    {
        (new Field\Seo\PageTitle())->show();
    }

    public static function previewPictureAlt(): void
    {
        (new Field\Seo\PreviewPictureAlt())->show();
    }

    public static function previewPictureTitle(): void
    {
        (new Field\Seo\PreviewPictureTitle())->show();
    }

    public static function previewPictureName(): void
    {
        (new Field\Seo\PreviewPictureName())->show();
    }

    public static function detailPictureAlt(): void
    {
        (new Field\Seo\DetailPictureAlt())->show();
    }

    public static function detailPictureTitle(): void
    {
        (new Field\Seo\DetailPictureTitle())->show();
    }

    public static function detailPictureName(): void
    {
        (new Field\Seo\DetailPictureName())->show();
    }

    public static function tags(): void
    {
        (new Field\Seo\Tags())->show();
    }

    public static function sections(): void
    {
        (new Field\Sections())->show();
    }
}
