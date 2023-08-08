<?php
IncludeModuleLangFile(__FILE__);
/**
 * @author dev2fun (darkfriend)
 * @copyright darkfriend
 * @version 1.1.0
 */
include_once __DIR__ . '/../vendor/autoload.php';
if (class_exists('dev2fun_redirects')) {
    return;
}

use Bitrix\Main\ModuleManager,
    Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option;

Loader::registerAutoLoadClasses(
    'dev2fun.redirects',
    [
        'Dev2fun\\Redirects\\Base' => 'include.php',
        'Dev2fun\\Redirects\\Config' => 'classes/general/Config.php',
    ]
);

class dev2fun_redirects extends CModule
{
    var $MODULE_ID = 'dev2fun.redirects';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    public function __construct()
    {
        include(__DIR__ . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('D2F_MODULE_NAME_REDIRECTS');
        $this->MODULE_DESCRIPTION = Loc::getMessage('D2F_MODULE_DESCRIPTION_REDIRECTS');
        $this->PARTNER_NAME = 'dev2fun';
        $this->PARTNER_URI = 'http://dev2fun.com';
    }

    public function DoInstall()
    {
        global $APPLICATION, $DB;
        if (!check_bitrix_sessid()) return;
        $DB->StartTransaction();
        try {
            if (!Loader::includeModule('highloadblock')) {
                throw new Exception(Loc::getMessage("NO_INSTALL_HIGHLOADBLOCK"));
            }
            if (!Loader::includeModule('iblock')) {
                throw new Exception(Loc::getMessage("NO_INSTALL_IBLOCK"));
            }
            $this->installDB();
            $this->registerEvents();
            $DB->Commit();
            ModuleManager::registerModule($this->MODULE_ID);
            \CAdminNotify::Add([
                'MESSAGE' => Loc::getMessage('D2F_REDIRECTS_NOTICE_THANKS'),
                'TAG' => $this->MODULE_ID . '_install',
                'MODULE_ID' => $this->MODULE_ID,
            ]);
        } catch (Exception $e) {
            $DB->Rollback();
            $GLOBALS['D2F_REDIRECTS_ERROR'] = $e->getMessage();
            $GLOBALS['D2F_REDIRECTS_ERROR_NOTES'] = Loc::getMessage('D2F_REDIRECTS_INSTALL_ERROR_NOTES');
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("D2F_REDIRECTS_STEP_ERROR"),
                __DIR__ . "/error.php"
            );
            return false;
        }
        $APPLICATION->IncludeAdminFile(Loc::getMessage("D2F_REDIRECTS_STEP1"), __DIR__ . "/step1.php");
    }

    public function installDB()
    {
        $hlId = $this->_installHighload();
        if (!$hlId) throw new Exception(\Darkfriend\HLHelpers::$LAST_ERROR);
        Option::set($this->MODULE_ID, 'highload_redirects', $hlId);
        Option::set($this->MODULE_ID, 'enable', 'Y');
        return true;
    }

    private function _installHighload()
    {
        $hl = \Darkfriend\HLHelpers::getInstance();
        $hlId = $hl->create('Dev2funRedirects', 'dev2fun_redirects');
        if (!$hlId) {
            throw new Exception(\Darkfriend\HLHelpers::$LAST_ERROR);
        }
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_URL_FROM',
            'USER_TYPE_ID' => 'string',
            'SORT' => 100,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'IS_SEARCHABLE' => 'Y',
            'EDIT_FORM_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_URL_FROM_EDIT_FORM_LABEL', null, 'ru'),
                'en' => 'From URL',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_URL_FROM_EDIT_FORM_LABEL', null, 'ru'),
                'en' => 'From URL',
            ],
        ]);
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_URL_TO',
            'USER_TYPE_ID' => 'string',
            'SORT' => 200,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'IS_SEARCHABLE' => 'Y',
            'EDIT_FORM_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_URL_TO_EDIT_FORM_LABEL', null, 'ru'),
                'en' => 'To URL',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_URL_TO_EDIT_FORM_LABEL', null, 'ru'),
                'en' => 'To URL',
            ],
        ]);
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_SITE_ID',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'SITE_ID',
            'SORT' => 300,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'IS_SEARCHABLE' => 'Y',
            'EDIT_FORM_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_SITE_ID_EDIT_FORM_LABEL', null, 'ru'),
//                'ru' => 'Идентификатор сайта',
                'en' => 'Site id',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_SITE_ID_LIST_COLUMN_LABEL', null, 'ru'),
//                'ru' => 'Cайт',
                'en' => 'Site id',
            ],
        ]);
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_SORT',
            'USER_TYPE_ID' => 'integer',
            'SORT' => 400,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'IS_SEARCHABLE' => 'Y',
            'SETTINGS' => [
                'DEFAULT_VALUE' => 500,
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_SORT_EDIT_FORM_LABEL', null, 'ru'),
//                'ru' => 'Сортировка',
                'en' => 'Sort',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_SORT_EDIT_FORM_LABEL', null, 'ru'),
//                'ru' => 'Сортировка',
                'en' => 'Sort',
            ],
        ]);
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_STATUS_CODE',
            'USER_TYPE_ID' => 'integer',
            'XML_ID' => 'STATUS_CODE',
            'SORT' => 500,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SETTINGS' => [
                'DEFAULT_VALUE' => 302,
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_STATUS_CODE_EDIT_FORM_LABEL', null, 'ru'),
//                'ru' => 'Код статуса (301 или 302)',
                'en' => 'Status code (301 or 302)',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_STATUS_CODE_LIST_COLUMN_LABEL', null, 'ru'),
//                'ru' => 'Код статуса',
                'en' => 'Status code',
            ],
        ]);
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_NOT_FOUND_MODE',
            'USER_TYPE_ID' => 'boolean',
            'XML_ID' => 'NOT_FOUND_MODE',
            'SORT' => 600,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SETTINGS' => [
                'DEFAULT_VALUE' => 0,
                'DISPLAY' => 'CHECKBOX',
                'LABEL' => [
                    '',
                    '',
                ],
                'LABEL_CHECKBOX' => Loc::getMessage('D2F_REDIRECTS_UF_NOT_FOUND_MODE_LABEL_CHECKBOX', null, 'ru'),
//                'LABEL_CHECKBOX' => 'Срабатывать только при 404',
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_NOT_FOUND_MODE_EDIT_FORM_LABEL', null, 'ru'),
//                'ru' => 'Срабатывать только при 404 ошибке',
                'en' => 'Work only to 404 error',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_NOT_FOUND_MODE_LIST_COLUMN_LABEL', null, 'ru'),
//                'ru' => 'Только при 404',
                'en' => 'Only to 404',
            ],
            'HELP_MESSAGE' => [
                'ru' => Loc::getMessage('D2F_REDIRECTS_UF_NOT_FOUND_MODE_HELP_MESSAGE', null, 'ru'),
//                'ru' => 'При "Да" редирект срабатывает только если код статуса 404',
                'en' => '',
            ],
        ]);
        return $hlId;
    }

    public function registerEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            'Dev2fun\\Redirects\\Base',
            'InitRedirects'
        );
        return true;
    }

    public function DoUninstall()
    {
        global $APPLICATION, $DB;
        if (!check_bitrix_sessid()) return;
        $DB->StartTransaction();
        try {
            if (!Loader::includeModule('highloadblock')) {
                throw new Exception(Loc::getMessage("NO_INSTALL_HIGHLOADBLOCK"));
            }
            if (!Loader::includeModule('iblock')) {
                throw new Exception(Loc::getMessage("NO_INSTALL_IBLOCK"));
            }
            $this->unInstallDB();
            $this->unRegisterEvents();
            $DB->Commit();
            \CAdminNotify::Add([
                'MESSAGE' => Loc::getMessage('D2F_REDIRECTS_NOTICE_WHY'),
                'TAG' => $this->MODULE_ID . '_uninstall',
                'MODULE_ID' => $this->MODULE_ID,
            ]);
            ModuleManager::unRegisterModule($this->MODULE_ID);
        } catch (Exception $e) {
            $DB->Rollback();
            $GLOBALS['D2F_COMPRESSIMAGE_ERROR'] = $e->getMessage();
            $GLOBALS['D2F_COMPRESSIMAGE_ERROR_NOTES'] = Loc::getMessage('D2F_REDIRECTS_UNINSTALL_ERROR_NOTES');
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("D2F_REDIRECTS_STEP_ERROR"),
                __DIR__ . "/error.php"
            );
            return false;
        }

        $APPLICATION->IncludeAdminFile(GetMessage("D2F_MULTIDOMAIN_UNSTEP1"), __DIR__ . "/unstep1.php");
    }

    public function unInstallDB()
    {
        $hlId = Option::get($this->MODULE_ID, 'highload_redirects');
        $hl = \Darkfriend\HLHelpers::getInstance();
        if (!$hl->deleteHighloadBlock($hlId)) {
            throw new Exception('Не смог удалить HighloadBlock #' . $hlId);
        }
        Option::delete($this->MODULE_ID);
        return true;
    }

    public function unRegisterEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'OnPageStart', $this->MODULE_ID);
        return true;
    }
}
