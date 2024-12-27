<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

class TabsBlockComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult['ID']   = $this->arParams['ID'];
        $this->arResult['TABS'] = $this->arParams['TABS'];

        if ($this->arResult['TABS']) {
            $this->includeComponentTemplate();
        }
    }
}
