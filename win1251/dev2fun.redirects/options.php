<?php
/**
 *
 * @author dev2fun (darkfriend)
 * @copyright darkfriend
 * @version 1.0.1
 */

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}
$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();
$curModuleName = "dev2fun.redirects";
//Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);
\Bitrix\Main\Loader::includeModule($curModuleName);

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "ICON" => "main_settings",
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
    ],
    [
        "DIV" => "donate",
        "TAB" => Loc::getMessage('SEC_DONATE_TAB'),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage('SEC_DONATE_TAB_TITLE'),
    ],
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($request->isPost() && check_bitrix_sessid()) {
    $arFields = $request->getPost('options');
    if(empty($arFields['enable'])) {
        $arFields['enable'] = 'N';
    }
    foreach ($arFields as $k => $arField) {
        Option::set($curModuleName, $k, $arField);
    }
    LocalRedirect($APPLICATION->GetCurUri());
}
$msg = new CAdminMessage([
    'MESSAGE' => Loc::getMessage("D2F_REDIRECTS_DONATE_MESSAGES", ['#LINK#' => 'https://www.tinkoff.ru/cf/36wVfnMf7mo']),
    'TYPE' => 'OK',
    'HTML' => true,
]);
echo $msg->Show();
$tabControl->begin();
?>

<form
    method="post"
    action="<?= sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID) ?>&<?= $tabControl->ActiveTabParam() ?>"
    enctype="multipart/form-data"
    name="editform"
    class="editform"
>
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();
    ?>
    <!--    <tr class="heading">-->
    <!--        <td colspan="2"><b>--><?php //echo GetMessage("D2F_COMPRESS_HEADER_SETTINGS")?><!--</b></td>-->
    <!--    </tr>-->

    <tr>
        <td width="40%">
            <label for="options[enable]">
                <?= Loc::getMessage("D2F_REDIRECTS_LABEL_ENABLE") ?>:
            </label>
        </td>
        <td width="60%">
            <?php
            $enabled = Option::get($curModuleName, 'enable', 'Y') === 'Y';
            ?>
            <input type="checkbox" value="Y" name="options[enable]" <?=$enabled?'checked':''?>>
        </td>
    </tr>

    <tr>
        <td width="40%">
            <label>
                <?= Loc::getMessage("D2F_REDIRECTS_LABEL_REDIRECTS") ?>:
            </label>
        </td>
        <td width="60%">
            <?php
            echo BeginNote();
            echo Loc::getMessage(
                "D2F_REDIRECTS_REDIRECTS_NOTE",
                ['#ID#' => Option::get($curModuleName, 'highload_redirects')]
            );
            EndNote();
            ?>
        </td>
    </tr>

    <?php include __DIR__.'/tabs/donate.php'?>

    <?php
    $tabControl->Buttons([
        "btnSave" => true,
        "btnApply" => true,
        "btnCancel" => true,
        "back_url" => $APPLICATION->GetCurUri(),
    ]);
    $tabControl->End();
    ?>
</form>