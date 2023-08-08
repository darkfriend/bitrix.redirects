<?php
/**
 * @author dev2fun (darkfriend)
 * @copyright darkfriend
 * @version 1.1.0
 */

namespace Dev2fun\Redirects;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

IncludeModuleLangFile(__FILE__);
include_once __DIR__.'/vendor/autoload.php';

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Darkfriend\HLHelpers;

Loader::registerAutoLoadClasses(
    "dev2fun.redirects",
    [
        'Dev2fun\\Redirects\\Base' => __FILE__,
    ]
);

class Base
{
    public static $module_id = 'dev2fun.redirects';

    /**
     * @return bool|null
     * @throws \Exception
     */
    public static function InitRedirects()
    {
        global $APPLICATION;
        if (defined('ADMIN_SECTION')) {
            return null;
        }

        $enabled = Option::get(self::$module_id, 'enable', 'N') === 'Y';
        if (!$enabled) {
            return true;
        }

        if (!empty($GLOBALS['OnEpilogRedirectCheck'])) {
            return null;
        }

        if (!Loader::includeModule('highloadblock')) {
            throw new \Exception(Loc::getMessage("NO_INSTALL_HIGHLOADBLOCK"));
        }
        if (!Loader::includeModule('iblock')) {
            throw new \Exception(Loc::getMessage("NO_INSTALL_IBLOCK"));
        }
        if (!Loader::includeModule('main')) {
            throw new \Exception(Loc::getMessage("NO_INSTALL_IBLOCK"));
        }

        $siteId = Application::getInstance()->getContext()->getSite();
        $GLOBALS['OnEpilogRedirectCheck'] = 1;
        $element = HLHelpers::getInstance()->getElementList(
            Option::get(self::$module_id, 'highload_redirects'),
            [
                'UF_URL_FROM' => $_SERVER['REQUEST_URI'] ?? $APPLICATION->GetCurPage(),
                'UF_SITE_ID' => $siteId,
            ],
            [
                'UF_SORT' => 'ASC',
            ]
        );
        if ($element) {
            $element = \array_shift($element);
            if ($element['UF_NOT_FOUND_MODE'] === '1') {
                if (!defined('ERROR_404')) {
                    return null;
                } elseif (\ERROR_404 !== 'Y') {
                    return null;
                }
            }
            $urlTo = \trim($element['UF_URL_TO']);
            \LocalRedirect(
                $urlTo,
                true,
                self::getHeaderCodeString($element['UF_STATUS_CODE'] ?? 302)
//                    '301 Moved Permanently'
            );
        }

        return true;
    }

    /**
     * @param int $code
     * @return string
     */
    protected static function getHeaderCodeString(int $code): string
    {
        if ($code === 301) {
            return '301 Moved Permanently';
        }

        return '302 Found';
    }

    /**
     * @return void
     */
    public static function ShowThanksNotice()
    {
        \CAdminNotify::Add([
            'MESSAGE' => Loc::getMessage('D2F_REDIRECTS_DONATE_MESSAGE', ['#URL#' => '/bitrix/admin/settings.php?lang=ru&mid=dev2fun.redirects&mid_menu=1&tabControl_active_tab=donate']),
            'TAG' => 'dev2fun_opengraph_update',
            'MODULE_ID' => 'dev2fun.opengraph',
        ]);
    }
}