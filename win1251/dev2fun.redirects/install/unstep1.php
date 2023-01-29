<?php
/**
 * @author dev2fun (darkfriend)
 * @copyright darkfriend
 * @version 1.0.1
 */
if (!check_bitrix_sessid()) return;
IncludeModuleLangFile(__FILE__);

CModule::IncludeModule("main");

use \Bitrix\Main\Localization\Loc;

$admMsg = new CAdminMessage(false);
$admMsg->ShowMessage([
    "MESSAGE" => Loc::getMessage('D2F_REDIRECTS_UNINSTALL_SUCCESS'),
    "TYPE" => "OK",
]);
echo BeginNote();
echo Loc::getMessage("D2F_REDIRECTS_UNINSTALL_LAST_MSG");
EndNote();