<?php

namespace DVK\Admin\DetailPage\Blocks;

use DVK\Admin\DetailPage\GlobalValue;

class Block
{
    protected static ?string $id = null;

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

    public static function delimiter(): void
    {
        (new Delimiter())->show();
    }

    public static function seo(): void
    {
        (new SeoBlock())->show();
    }

    public static function section($text, $id = null): void
    {
        (new Section($text, $id))->show();
    }

    public static function twoColumns($columns, $id = null): void
    {
        (new TwoColumns($columns, $id))->show();
    }

    public static function component(string $componentName, string $template = '.default', array $params = [], string $id = null): void
    {
        (new Component($componentName, $template, $params, $id))->show();
    }

    public static function html(string $content, string $id = null): void
    {
        (new HtmlBlock($content, $id))->show();
    }

    public static function toggle(string $title, array $fields = [], string $id = null): void
    {
        (new ToggleBlock($title, $fields, $id))->show();
    }

    public static function stepper(array $steps, bool $showTitle = true, string $id = null): void
    {
        (new StepperBlock($steps, $showTitle, $id))->show();
    }

    public static function tabs(array $tabs, string $id = null): void
    {
        (new TabsBlock($tabs, $id))->show();
    }
}
