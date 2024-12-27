<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use \Bitrix\Main\IO\Directory;

class dvk_admin_detailpage extends CModule
{
    var $MODULE_ID = "dvk.admin.detailpage";
    var $MODULE_NAME;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;


    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = Loc::getMessage('DVK_ADMIN_DETAIL_PAGE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DVK_ADMIN_DETAIL_PAGE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('DVK_ADMIN_DETAIL_PAGE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('DVK_ADMIN_DETAIL_PAGE_PARTNER_URI');
    }

    public function DoInstall(): void
    {
        $this->installFiles();

        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall(): void
    {
        $this->unInstallFiles();

        \Bitrix\Main\ModuleManager::unregisterModule($this->MODULE_ID);
    }


    public function installFiles(): void
    {
        CopyDirFiles(
            __DIR__ . '/components',
            Application::getDocumentRoot() . '/local/components/dvk',
            false,
            true
        );
    }

    public function unInstallFiles(): void
    {
        Directory::deleteDirectory(Application::getDocumentRoot() . '/local/components/dvk/admin.detailpage.tabs');
        Directory::deleteDirectory(Application::getDocumentRoot() . '/local/components/dvk/admin.detailpage.toggle');
        Directory::deleteDirectory(Application::getDocumentRoot() . '/local/components/dvk/admin.detailpage.stepper');

        $directory = new Directory(Application::getDocumentRoot() . '/local/components/dvk');

        if (empty($directory->getChildren())) {
            $directory->delete();
        }
    }
}
