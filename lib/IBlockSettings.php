<?php

namespace DVK\Admin\DetailPage;

use Bitrix\Main\Config\Option;

class IBlockSettings
{
    private static array $cache = [];
    private static bool $isShowSection;

    public static function get(string $code): bool
    {
        if (!isset(self::$cache[$code])) {
            self::$cache[$code] = Option::get('iblock', $code);
        }

        return self::$cache[$code];
    }

    public static function showXmlId(): bool
    {
        return self::get('show_xml_id') == 'Y';
    }

    public static function hasSections(): bool
    {
        if (!isset(self::$isShowSection)) {
            $ibType = \CIBlockType::GetByIDLang(GlobalValue::get('type'), LANGUAGE_ID);
            self::$isShowSection = ('Y' == $ibType["SECTIONS"]);
        }

        return self::$isShowSection;
    }
}
