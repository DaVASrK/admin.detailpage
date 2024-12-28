<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\GlobalValue;

class Block
{
    protected static ?string $id = null;


    public static function __callStatic($name, $arguments): void
    {
        $className = self::getClassName(ucfirst($name));

        (new $className(...$arguments))->show();
    }

    public static function start(): void
    {
        if (self::$id !== null) {
            throw new \Exception("Block was started earlier");
        }

        self::$id = uniqid();
        GlobalValue::get('tabControl')->BeginCustomField(self::$id, '');

        echo '<tr><td colspan="2">';
    }

    public static function end(): void
    {
        echo '</td</tr>';

        GlobalValue::get('tabControl')->EndCustomField(self::$id);

        self::$id = null;
    }


    protected static function getClassName(string $name): string
    {
        $class = '';

        $className      = 'DVK\Admin\DetailPage\Blocks\\' . $name;
        $classNameBlock = 'DVK\Admin\DetailPage\Blocks\\' . $name . 'Block';

        if (class_exists($className)) {
            $class = $className;
        }
        if (class_exists($classNameBlock)) {
            $class = $classNameBlock;
        }

        if (!$class) {
            throw new \Exception('Block "' . $name . '" does not exist');
        }

        return $class;
    }
}
