<?php

namespace DVK\Admin\DetailPage\Fields;

use DVK\Admin\DetailPage\GlobalValue;

class StandartField
{
    protected \CAdminForm $tabControl;


    public function __construct()
    {
        $this->tabControl = GlobalValue::get('tabControl');
    }


    public static function __callStatic($name, $arguments): void
    {
        $className = self::getClassName(ucfirst($name));

        (new $className())->show();
    }

    public static function get(string $code): AbstractField
    {
        $codeParts = explode('_', strtolower($code));
        array_walk($codeParts, function (&$el) { $el = ucfirst($el); });
        $name = implode('', $codeParts);

        $className = self::getClassName($name);

        return new $className;
    }

    public static function show(string $code): void
    {
        (self::get($code))->show();
    }

    public static function activeFromTo(): void
    {
        self::activeFrom();
        self::activeTo();
    }


    protected static function getClassName(string $name): string
    {
        $classNameStandart = 'DVK\Admin\DetailPage\Fields\Standart\\' . $name;
        $classNameSeo      = 'DVK\Admin\DetailPage\Fields\Standart\Seo\\' . $name;

        if (class_exists($classNameStandart)) {
            $className = $classNameStandart;
        }
        if (class_exists($classNameSeo)) {
            $className = $classNameSeo;
        }

        if (!$className) {
            throw new \Exception('Field "' . $name . '" does not exist');
        }

        return $className;
    }
}
