<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

class ToggleBlockComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult['ID']     = $this->arParams['ID'];
        $this->arResult['TITLE']  = $this->arParams['TITLE'];
        $this->arResult['FIELDS'] = $this->arParams['FIELDS'];
        $this->includeComponentTemplate();
    }
}
