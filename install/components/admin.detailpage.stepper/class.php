<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

class StepperBlockComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult['ID']         = $this->arParams['ID'];
        $this->arResult['STEPS']      = $this->arParams['STEPS'];
        $this->arResult['SHOW_TITLE'] = !is_bool($this->arParams['SHOW_TITLE']) || $this->arParams['SHOW_TITLE'];

        if ($this->arResult['STEPS']) {
            $this->includeComponentTemplate();
        }
    }
}
