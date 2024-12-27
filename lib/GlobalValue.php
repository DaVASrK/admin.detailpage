<?php

namespace DVK\Admin\DetailPage;

class GlobalValue
{
    public static function get(string $name): mixed
    {
        global $$name;
        return $$name;
    }
}
