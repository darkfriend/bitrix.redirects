<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 08.08.2023
 * Time: 23:47
 */

include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$curModuleName = 'dev2fun.redirects';

\Bitrix\Main\Loader::includeModule('main');
\Bitrix\Main\Loader::includeModule('highloadblock');
\Bitrix\Main\Loader::includeModule($curModuleName);

\Bitrix\Main\Loader::registerAutoLoadClasses(
    "dev2fun.redirects",
    [
        'Dev2fun\\Redirects\\Base' => 'include.php',
    ]
);

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->unRegisterEventHandler('main', 'OnEpilog', $curModuleName);
$eventManager->registerEventHandler(
    'main',
    'OnPageStart',
    $curModuleName,
    'Dev2fun\\Redirects\\Base',
    'InitRedirects'
);

$hlId = \Bitrix\Main\Config\Option::get($curModuleName, 'highload_redirects');
if ($hlId) {
    $hl = \Darkfriend\HLHelpers::getInstance();
    $fields = $hl->getFields($hlId);
    if (empty($fields['UF_SITE_ID'])) {
        $hl->addField($hlId, [
            'FIELD_NAME' => 'UF_SITE_ID',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'SITE_ID',
            'SORT' => 300,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'IS_SEARCHABLE' => 'Y',
            'EDIT_FORM_LABEL' => [
                'ru' => 'Идентификатор сайта',
                'en' => 'Site id',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Cайт',
                'en' => 'Site id',
            ],
        ]);
    }

    if (empty($fields['UF_STATUS_CODE'])) {
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
                'ru' => 'Код статуса (301 или 302)',
                'en' => 'Status code (301 or 302)',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Код статуса',
                'en' => 'Status code',
            ],
        ]);
    }

    if (empty($fields['UF_NOT_FOUND_MODE'])) {
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
                'LABEL_CHECKBOX' => 'Срабатывать только при 404',
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => 'Срабатывать только при 404 ошибке',
                'en' => 'Work only to 404 error',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Только при 404',
                'en' => 'Only to 404',
            ],
            'HELP_MESSAGE' => [
                'ru' => 'При "Да" редирект срабатывает только если код статуса 404',
                'en' => '',
            ],
        ]);
    }
}

echo '1.1.0 - DONE'.PHP_EOL;