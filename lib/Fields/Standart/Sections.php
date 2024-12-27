<?php

namespace DVK\Admin\DetailPage\Fields\Standart;

use DVK\Admin\DetailPage\Fields\AbstractField;
use Bitrix\Main\Localization\Loc;
use DVK\Admin\DetailPage\GlobalValue;

class Sections extends AbstractField
{
    protected string $name = 'Разделы';
    protected string $code = 'SECTIONS';

    public function __construct()
    {
        parent::__construct();

        $this->value = GlobalValue::get('str_IBLOCK_ELEMENT_SECTION');
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="tr_<?= $this->code ?>">
            <?if(GlobalValue::get('arIBlock')["SECTION_CHOOSER"] != "D" && GlobalValue::get('arIBlock')["SECTION_CHOOSER"] != "P"):?>

                <?$treeList = \CIBlockSection::GetTreeList(["IBLOCK_ID" => GlobalValue::get('IBLOCK_ID')], ["ID", "NAME", "DEPTH_LEVEL"]);?>
                <td class="adm-detail-content-cell-l adm-detail-valign-top" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
                <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left">
                    <select name="IBLOCK_SECTION[]" size="14" multiple onchange="onSectionChanged()">
                        <option value="0"<?if(is_array($this->value) && in_array(0, $this->value)) echo " selected"?>><?= Loc::getMessage("IBLOCK_UPPER_LEVEL")?></option>
                        <?
                        while($arList = $treeList->GetNext()):
                            ?><option value="<?= $arList["ID"]?>"<?if(is_array($this->value) && in_array($arList["ID"], $this->value)) echo " selected"?>><?= str_repeat(" . ", $arList["DEPTH_LEVEL"]) ?><?= $arList["NAME"] ?></option><?
                        endwhile;
                        ?>
                    </select>
                </td>

            <?elseif(GlobalValue::get('arIBlock')["SECTION_CHOOSER"] == "D"):?>
                <td class="adm-detail-content-cell-l adm-detail-valign-top" style="width: 40%"><?= $this->getTitleTemplate() ?></td>
                <td class="adm-detail-content-cell-r" style="width: 60%; text-align: left">
                    <table class="internal" id="sections">
                        <?php
                        if(is_array($this->value))
                        {
                            $i = 0;
                            foreach($this->value as $sectionId)
                            {
                                $rsChain = \CIBlockSection::GetNavChain(
                                    GlobalValue::get('IBLOCK_ID'),
                                    $sectionId,
                                    [
                                        'ID',
                                        'NAME',
                                    ],
                                    true
                                );
                                $strPath = "";
                                foreach ($rsChain as $arChain)
                                {
                                    $strPath .= htmlspecialcharsbx($arChain["NAME"]) . "&nbsp;/&nbsp;";
                                }
                                if ($strPath !== '')
                                {
                                    ?><tr>
                                    <td nowrap><?= $strPath; ?></td>
                                    <td>
                                        <input type="button" value="<?= Loc::getMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">
                                        <input type="hidden" name="IBLOCK_SECTION[]" value="<?= (int)$sectionId; ?>">
                                    </td>
                                    </tr><?php
                                }
                                $i++;
                            }
                        }
                        ?>
                        <tr>
                            <td>
                                <script>
                                    function deleteRow(button)
                                    {
                                        var my_row = button.parentNode.parentNode;
                                        var table = document.getElementById('sections');
                                        if(table)
                                        {
                                            for(var i=0; i<table.rows.length; i++)
                                            {
                                                if(table.rows[i] == my_row)
                                                {
                                                    table.deleteRow(i);
                                                    onSectionChanged();
                                                }
                                            }
                                        }
                                    }
                                    function addPathRow()
                                    {
                                        var table = document.getElementById('sections');
                                        if(table)
                                        {
                                            var section_id = 0;
                                            var html = '';
                                            var lev = 0;
                                            var oSelect;
                                            while(oSelect = document.getElementById('select_IBLOCK_SECTION_'+lev))
                                            {
                                                if(oSelect.value < 1)
                                                    break;
                                                html += oSelect.options[oSelect.selectedIndex].text+'&nbsp;/&nbsp;';
                                                section_id = oSelect.value;
                                                lev++;
                                            }
                                            if(section_id > 0)
                                            {
                                                var cnt = table.rows.length;
                                                var oRow = table.insertRow(cnt-1);

                                                var i=0;
                                                var oCell = oRow.insertCell(i++);
                                                oCell.innerHTML = html;

                                                oCell = oRow.insertCell(i++);
                                                oCell.innerHTML =
                                                    '<input type="button" value="<?= Loc::getMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">'+
                                                    '<input type="hidden" name="IBLOCK_SECTION[]" value="'+section_id+'">';
                                                onSectionChanged();
                                            }
                                        }
                                    }
                                    function find_path(item, value)
                                    {
                                        if(item.id==value)
                                        {
                                            var a = Array(1);
                                            a[0] = item.id;
                                            return a;
                                        }
                                        else
                                        {
                                            for(var s in item.children)
                                            {
                                                if(ar = find_path(item.children[s], value))
                                                {
                                                    var a = Array(1);
                                                    a[0] = item.id;
                                                    return a.concat(ar);
                                                }
                                            }
                                            return null;
                                        }
                                    }
                                    function find_children(level, value, item)
                                    {
                                        if(level==-1 && item.id==value)
                                            return item;
                                        else
                                        {
                                            for(var s in item.children)
                                            {
                                                if(ch = find_children(level-1,value,item.children[s]))
                                                    return ch;
                                            }
                                            return null;
                                        }
                                    }
                                    function change_selection(name_prefix, prop_id,value,level,id)
                                    {
                                        var lev = level+1;
                                        var oSelect;

                                        while(oSelect = document.getElementById(name_prefix+lev))
                                        {
                                            jsSelectUtils.deleteAllOptions(oSelect);
                                            jsSelectUtils.addNewOption(oSelect, '0', '(<?= Loc::getMessage("MAIN_NO")?>)');
                                            lev++;
                                        }

                                        oSelect = document.getElementById(name_prefix+(level+1))
                                        if(oSelect && (value!=0||level==-1))
                                        {
                                            var item = find_children(level,value,window['sectionListsFor'+prop_id]);
                                            for(var s in item.children)
                                            {
                                                var obj = item.children[s];
                                                jsSelectUtils.addNewOption(oSelect, obj.id, obj.name, true);
                                            }
                                        }
                                        if(document.getElementById(id))
                                            document.getElementById(id).value = value;
                                    }
                                    function init_selection(name_prefix, prop_id, value, id)
                                    {
                                        var a = find_path(window['sectionListsFor'+prop_id], value);
                                        change_selection(name_prefix, prop_id, 0, -1, id);
                                        for(var i=1;i<a.length;i++)
                                        {
                                            if(oSelect = document.getElementById(name_prefix+(i-1)))
                                            {
                                                for(var j=0;j<oSelect.length;j++)
                                                {
                                                    if(oSelect[j].value==a[i])
                                                    {
                                                        oSelect[j].selected=true;
                                                        break;
                                                    }
                                                }
                                            }
                                            change_selection(name_prefix, prop_id, a[i], i-1, id);
                                        }
                                    }
                                    var sectionListsFor0 = {id:0,name:'',children:Array()};

                                    <?
                                    $rsItems = \CIBlockSection::GetTreeList(["IBLOCK_ID" => GlobalValue::get('IBLOCK_ID')], ["ID", "NAME", "DEPTH_LEVEL"]);
                                    $depth = 0;
                                    $max_depth = 0;
                                    $arChain = array();
                                    while($arItem = $rsItems->GetNext())
                                    {
                                        if($max_depth < $arItem["DEPTH_LEVEL"])
                                        {
                                            $max_depth = $arItem["DEPTH_LEVEL"];
                                        }
                                        if($depth < $arItem["DEPTH_LEVEL"])
                                        {
                                            $arChain[] = $arItem["ID"];

                                        }
                                        while($depth > $arItem["DEPTH_LEVEL"])
                                        {
                                            array_pop($arChain);
                                            $depth--;
                                        }
                                        $arChain[count($arChain)-1] = $arItem["ID"];
                                        echo "sectionListsFor0";
                                        foreach($arChain as $i)
                                            echo ".children['".intval($i)."']";

                                        echo " = { id : ".$arItem["ID"].", name : '".\CUtil::JSEscape($arItem["NAME"])."', children : Array() };\n";
                                        $depth = $arItem["DEPTH_LEVEL"];
                                    }
                                    ?>
                                </script>
                                <?
                                for ($i = 0; $i < $max_depth; $i++)
                                    echo '<select id="select_IBLOCK_SECTION_'.$i.'" onchange="change_selection(\'select_IBLOCK_SECTION_\',  0, this.value, '.$i.', \'IBLOCK_SECTION[n'.$key.']\')"><option value="0">('.Loc::getMessage("MAIN_NO").')</option></select>&nbsp;';
                                ?>
                                <script>
                                    init_selection('select_IBLOCK_SECTION_', 0, '', 0);
                                </script>
                            </td>
                            <td><input type="button" value="<?= Loc::getMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD")?>" onClick="addPathRow()"></td>
                        </tr>
                    </table>
                </td>

            <?else:?>
                <td style="width: 40%" class="adm-detail-valign-top"><?= $this->getTitleTemplate() ?></td>
                <td style="width: 60%">
                    <table id="sections" class="internal">
                        <?php
                        if(is_array($this->value))
                        {
                            $i = 0;
                            foreach($this->value as $sectionId)
                            {
                                $rsChain = \CIBlockSection::GetNavChain(
                                    GlobalValue::get('IBLOCK_ID'),
                                    $sectionId,
                                    [
                                        'ID',
                                        'NAME',
                                    ],
                                    true
                                );
                                $strPath = "";
                                foreach ($rsChain as $arChain)
                                {
                                    $strPath .= htmlspecialcharsbx($arChain["NAME"]) . "&nbsp;/&nbsp;";
                                }
                                if ($strPath !== '')
                                {
                                    ?><tr>
                                    <td><?= $strPath; ?></td>
                                    <td>
                                        <input type="button" value="<?= Loc::getMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">
                                        <input type="hidden" name="IBLOCK_SECTION[]" value="<?= (int)$sectionId; ?>">
                                    </td>
                                    </tr><?php
                                }
                                $i++;
                            }
                        }
                        ?>
                    </table>
                    <script>
                        function deleteRow(button)
                        {
                            var my_row = button.parentNode.parentNode;
                            var table = document.getElementById('sections');
                            if(table)
                            {
                                for(var i=0; i<table.rows.length; i++)
                                {
                                    if(table.rows[i] == my_row)
                                    {
                                        table.deleteRow(i);
                                        onSectionChanged();
                                    }
                                }
                            }
                        }
                        function InS<?= md5("input_IBLOCK_SECTION")?>(section_id, html)
                        {
                            var table = document.getElementById('sections');
                            if(table)
                            {
                                if(section_id > 0 && html)
                                {
                                    var cnt = table.rows.length;
                                    var oRow = table.insertRow(cnt-1);

                                    var i=0;
                                    var oCell = oRow.insertCell(i++);
                                    oCell.innerHTML = html;

                                    oCell = oRow.insertCell(i++);
                                    oCell.innerHTML =
                                        '<input type="button" value="<?= Loc::getMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">'+
                                        '<input type="hidden" name="IBLOCK_SECTION[]" value="'+section_id+'">';
                                    onSectionChanged();
                                }
                            }
                        }
                    </script>
                    <input name="input_IBLOCK_SECTION" id="input_IBLOCK_SECTION" type="hidden">
                    <input type="button" value="<?= Loc::getMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD")?>..." onClick="jsUtils.OpenWindow('<?= GlobalValue::get('selfFolderUrl') ?>iblock_section_search.php?lang=<?= LANGUAGE_ID ?>&amp;IBLOCK_ID=<?= GlobalValue::get('IBLOCK_ID') ?>&amp;n=input_IBLOCK_SECTION&amp;m=y&amp;iblockfix=y&amp;tableId=iblocksection-<?= GlobalValue::get('IBLOCK_ID') ?>', 900, 700);">
                </td>
            <?endif;?>
        </tr>
        <?

        return ob_get_clean();
    }

    protected function getHidden(): string
    {
        $hidden = "";
        if (is_array($this->value)) {
            foreach ($this->value as $sectionId) {
                $hidden .= '<input type="hidden" name="IBLOCK_SECTION[]" value="' . intval($sectionId) . '">';
            }
        }

        return $hidden;
    }
}
