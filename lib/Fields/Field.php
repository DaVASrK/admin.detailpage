<?php

namespace DVK\Admin\DetailPage\Fields;

class Field
{
    public static function get(string $code): \DVK\Admin\DetailPage\Fields\Custom\Field
    {
        return new \DVK\Admin\DetailPage\Fields\Custom\Field($code);
    }

    public static function show(string $code): void
    {
        (new \DVK\Admin\DetailPage\Fields\Custom\Field($code))->show();
    }
}
