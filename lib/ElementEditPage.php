<?php

namespace DVK\Admin\DetailPage;

use Bitrix\Main\Localization\Loc;

class ElementEditPage
{
    protected \CAdminForm $tabControl;

    public function __construct()
    {
        $this->tabControl = GlobalValue::get('tabControl');

        $this->initPage();
    }


    public function begin(): void
    {
        $linkParams = [
            'IBLOCK_ID' => GlobalValue::get('IBLOCK_ID'),
            'type'      => GlobalValue::get('type'),
            'lang'      => GlobalValue::get('lang'),
            'find_section_section' => GlobalValue::get('find_section_section'),
        ];

        if (GlobalValue::get('bAutocomplete')) {
            $linkParams['lookup'] = GlobalValue::get('strLookup');
        }

        if (GlobalValue::get('adminSidePanelHelper')->isPublicFrame()) {
            $linkParams['IFRAME']      = 'Y';
            $linkParams['IFRAME_TYPE'] = 'PUBLIC_FRAME';
        }

        $this->tabControl->Begin(['FORM_ACTION' => GlobalValue::get('urlBuilder')
            ->getElementSaveUrl(null, $linkParams)]);

        $this->tabControl->BeginNextFormTab();
    }

    public function addTab(array $tab): void
    {
        $this->tabControl->tabs[] = $tab;
    }

    public function setTabs(array $tabs): void
    {
        $this->tabControl->tabs = $tabs;
    }

    public function nextTab(): void
    {
        $this->tabControl->BeginNextFormTab();
    }

    public function addButtons(): void
    {
        $bDisabled =
            (GlobalValue::get('view') == 'Y')
            || (GlobalValue::get('bWorkflow') && GlobalValue::get('prn_LOCK_STATUS') == 'red')
            || (
                ((GlobalValue::get('ID') <= 0) || GlobalValue::get('bCopy'))
                && !\CIBlockSectionRights::UserHasRightTo(
                    GlobalValue::get('IBLOCK_ID'),
                    GlobalValue::get('MENU_SECTION_ID'),
                    'section_element_bind'
                )
            )
            || (
                ((GlobalValue::get('ID') > 0) && !GlobalValue::get('bCopy'))
                && !\CIBlockElementRights::UserHasRightTo(GlobalValue::get('IBLOCK_ID'), GlobalValue::get('ID'), 'element_edit')
            )
            || (
                GlobalValue::get('bBizproc')
                && !GlobalValue::get('canWrite')
            );

        $strLookup = (isset($_REQUEST['lookup']) && is_string($_REQUEST['lookup']) ? preg_replace("/[^a-zA-Z0-9_:]/", "", $_REQUEST['lookup']) : '');
        if ($strLookup != '')
        {
            define('BT_UT_AUTOCOMPLETE', 1);
        }
        $bAutocomplete = defined('BT_UT_AUTOCOMPLETE') && (BT_UT_AUTOCOMPLETE == 1);

        if (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1) {
            ob_start();

            echo '<input ' . ($bDisabled ? "disabled" : '') . ' type="submit" class="adm-btn-save" name="save" id="save"
                 value="' . Loc::getMessage("IBLOCK_EL_SAVE") . '">';

            if (!GlobalValue::get('bAutocomplete')) {
                echo '<input ' . ($bDisabled ? "disabled" : '') . 'type="submit" class="button" name="apply" id="apply"
                    value="' . Loc::getMessage("IBLOCK_APPLY") . '">';
            }

            echo '<input ' . ($bDisabled ? "disabled" : '') . 'type="submit" class="button" name="dontsave" id="dontsave"
                value="' . Loc::getMessage("IBLOCK_EL_CANC") . '">';

            if (!$bAutocomplete) {
                echo '<input ' . ($bDisabled ? "disabled" : '') . 'type="submit" class="adm-btn-add" name="save_and_add" id="save_and_add"
                    value="' . Loc::getMessage("IBLOCK_EL_SAVE_AND_ADD") . '">';
            }

            $buttons_add_html = ob_get_contents();
            ob_end_clean();
            $this->tabControl->Buttons(false, $buttons_add_html);
        } elseif (!GlobalValue::get('bPropertyAjax') && (!isset($_REQUEST['nobuttons']) || $_REQUEST['nobuttons'] !== 'Y')) {
            $wfClose = "{
                title: '" . \CUtil::JSEscape(Loc::getMessage('IBLOCK_EL_CANC')) . "',
                name: 'dontsave',
                id: 'dontsave',
                action: function () {
                    var FORM = this.parentWindow.GetForm();
                    FORM.appendChild(BX.create('INPUT', {
                        props: {
                            type: 'hidden',
                            name: this.name,
                            value: 'Y'
                        }
                    }));
                    this.disableUntilError();
                    this.parentWindow.Submit();
                }
            }";

            $save_and_add = "{
                title: '" . \CUtil::JSEscape(Loc::getMessage('IBLOCK_EL_SAVE_AND_ADD')) . "',
                name: 'save_and_add',
                id: 'save_and_add',
                className: 'adm-btn-add',
                action: function () {
                    var FORM = this.parentWindow.GetForm();
                    FORM.appendChild(BX.create('INPUT', {
                        props: {
                            type: 'hidden',
                            name: 'save_and_add',
                            value: 'Y'
                        }
                    }));
        
                    this.parentWindow.hideNotify();
                    this.disableUntilError();
                    this.parentWindow.Submit();
                }
            }";


            $cancel = "{
                title: '" . \CUtil::JSEscape(Loc::getMessage('IBLOCK_EL_CANC')) . "',
                name: 'cancel',
                id: 'cancel',
                action: function () {
                    BX.WindowManager.Get().Close();
                    if(window.reloadAfterClose)
                        top.BX.reload(true);
                }
            }";

            $editInPanelParams = [
                'WF'                   => ($this->WF == 'Y' ? 'Y' : null),
                'find_section_section' => $this->find_section_section,
                'menu'                 => null,
            ];

            $edit_in_panel = "{
                title: '" . \CUtil::JSEscape(Loc::getMessage('IBLOCK_EL_EDIT_IN_PANEL')) . "',
                name: 'edit_in_panel',
                id: 'edit_in_panel',
                className: 'adm-btn-add',
                action: function () {
                    location.href = '" . GlobalValue::get('selfFolderUrl') . \CIBlock::GetAdminElementEditLink(
                    GlobalValue::get('IBLOCK_ID'),
                    GlobalValue::get('ID'),
                    []
                ) . "';
                }
            }";

            $this->tabControl->ButtonsPublic(
                [
                    '.btnSave',
                    (GlobalValue::get('ID') > 0 && GlobalValue::get('bWorkflow') ? $wfClose : $cancel),
                    $edit_in_panel,
                    $save_and_add,
                ]
            );
        }
    }

    public function show(): void
    {
        $this->tabControl->Show();
    }


    protected function initPage(): void
    {
        $this->addPrologContent();
        $this->addEpilogContent();

        GlobalValue::get('customTabber')->SetErrorState(GlobalValue::get('bVarsFromForm'));
    }

    protected function addPrologContent(): void
    {
        $this->tabControl->BeginPrologContent();
        $this->showPrologJS();
        $this->tabControl->EndPrologContent();
    }

    protected function addEpilogContent(): void
    {
        $this->tabControl->BeginEpilogContent();

        echo bitrix_sessid_post();
        echo GetFilterHiddens('find_');

        echo '<input type="hidden" name="linked_state" id="linked_state" value="' . (GlobalValue::get('bLinked') ? 'Y' : 'N') . '">';
        echo '<input type="hidden" name="Update" value="Y">';
        echo '<input type="hidden" name="from" value="' . htmlspecialcharsbx(GlobalValue::get('from')) . '">';
        echo '<input type="hidden" name="return_url" value="' . htmlspecialcharsbx(GlobalValue::get('return_url')) . '">';

        if (GlobalValue::get('WF') === 'Y') {
            echo '<input type="hidden" name="WF" value="Y">';
            echo '<input type="hidden" name="WF_STATUS_ID" value="1">';
        }

        if (GlobalValue::get('ID') > 0 && !GlobalValue::get('bCopy')) {
            echo '<input type="hidden" name="ID" value="' . GlobalValue::get('ID') . '">';
        }

        if (GlobalValue::get('bCopy')) {
            echo '<input type="hidden" name="copyID" value="' . GlobalValue::get('ID') . '">';
        } elseif (GlobalValue::get('copyID') > 0) {
            echo '<input type="hidden" name="copyID" value="' . GlobalValue::get('copyID') . '">';
        }

        echo '<input type="hidden" name="IBLOCK_SECTION_ID" value="' . intval(GlobalValue::get('IBLOCK_SECTION_ID')) . '">';
        echo '<input type="hidden" name="TMP_ID" value="' . intval(GlobalValue::get('TMP_ID')) . '">';

        $this->tabControl->EndEpilogContent();
    }

    protected function showPrologJS(): void
    {
        \CJSCore::Init(['date', 'vue', 'translit']);

        $tabControlName = $this->tabControl->GetName();
        $arTranslit = GlobalValue::get('arTranslit');

        echo "
            <script type=\"text/javascript\">
                var linked = " . (GlobalValue::get('bLinked') ? 'true' : 'false') . "
                    
                function set_linked() {
                    linked = !linked;
                    
                    var name_link = document.getElementById('name_link');
                    if (name_link) {
                        if (linked)
                            name_link.src = '/bitrix/themes/.default/icons/iblock/link.gif';
                        else
                            name_link.src = '/bitrix/themes/.default/icons/iblock/unlink.gif';
                    }
                    var code_link = document.getElementById('code_link');
                    if (code_link) {
                        if (linked)
                            code_link.src = '/bitrix/themes/.default/icons/iblock/link.gif';
                        else
                            code_link.src = '/bitrix/themes/.default/icons/iblock/unlink.gif';
                    }
                    var linked_state = document.getElementById('linked_state');
                    if (linked_state) {
                        if (linked)
                            linked_state.value = 'Y';
                        else
                            linked_state.value = 'N';
                    }
                }
                
                var oldValue = '';
                
                function transliterate() {
                    if (linked) {
                        var from = document.getElementById('NAME');
                        var to = document.getElementById('CODE');
                        if (from && to && oldValue != from.value) {
                            BX.translit(from.value, {
                                'max_len': " . intval($arTranslit['TRANS_LEN']) . ",
                                'change_case': '" . $arTranslit['TRANS_CASE'] . "',
                                'replace_space': '" . $arTranslit['TRANS_SPACE'] . "',
                                'replace_other': '" . $arTranslit['TRANS_OTHER'] . "',
                                'delete_repeat_replace': " . ($arTranslit['TRANS_EAT'] == 'Y' ? 'true' : 'false') . ",
                                'use_google': " . ($arTranslit['USE_GOOGLE'] == 'Y' ? 'true' : 'false') . ",
                                'callback': function (result) {
                                    to.value = result;
                                    setTimeout('transliterate()', 250);
                                }
                            });
                            oldValue = from.value;
                        } else {
                            setTimeout('transliterate()', 250);
                        }
                    } else {
                        setTimeout('transliterate()', 250);
                    }
                }

                transliterate();
            </script>
            <script type=\"text/javascript\">
                var InheritedPropertiesTemplates = new JCInheritedPropertiesTemplates(
                    '" . $tabControlName . "_form',
                    '" . GlobalValue::get('selfFolderUrl') . "iblock_templates.ajax.php?ENTITY_TYPE=E&IBLOCK_ID=" . intval(GlobalValue::get('IBLOCK_ID')) . "&ENTITY_ID=" . intval(GlobalValue::get('ID')) . "&bxpublic=y'
                );
                BX.ready(function () {
                    setTimeout(function () {
                        InheritedPropertiesTemplates.updateInheritedPropertiesTemplates(true);
                    }, 1000);
                });
            </script>
        ";
    }
}
