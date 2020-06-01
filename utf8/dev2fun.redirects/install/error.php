<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 */
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
if (!check_bitrix_sessid()) return;

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

Loader::includeModule('main');

CAdminMessage::ShowMessage([
    "MESSAGE" => $GLOBALS['D2F_REDIRECTS_ERROR'],
    "TYPE" => "ERROR",
]);
echo BeginNote();
echo $GLOBALS['D2F_REDIRECTS_ERROR_NOTES'];
echo EndNote();