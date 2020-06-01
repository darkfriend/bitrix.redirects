<?php
/**
 * @author dev2fun (darkfriend)
 * @copyright darkfriend
 * @version 1.0.0
 */

namespace Dev2fun\Redirects;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

IncludeModuleLangFile(__FILE__);
include_once __DIR__.'/vendor/autoload.php';

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Darkfriend\HLHelpers;

Loader::registerAutoLoadClasses(
    "dev2fun.redirects",
    [
        'Dev2fun\Redirects\Base' => __FILE__,
    ]
);

class Base
{
    public static $module_id = 'dev2fun.redirects';

    public static function InitRedirects()
    {
        global $APPLICATION;
        if (!Loader::includeModule('highloadblock')) {
            throw new Exception(Loc::getMessage("NO_INSTALL_HIGHLOADBLOCK"));
        }
        if (!Loader::includeModule('iblock')) {
            throw new Exception(Loc::getMessage("NO_INSTALL_IBLOCK"));
        }
        if (!Loader::includeModule('main')) {
            throw new Exception(Loc::getMessage("NO_INSTALL_IBLOCK"));
        }

        $enabled = Option::get(self::$module_id, 'enable', 'N') === 'Y';
        if(!$enabled) {
            return true;
        }

        if(
            !defined('ADMIN_SECTION')
            && defined('ERROR_404')
            && \ERROR_404 === 'Y'
            && empty($GLOBALS['OnEpilogRedirectCheck'])
        ) {
            $GLOBALS['OnEpilogRedirectCheck'] = 1;
            $element = HLHelpers::getInstance()->getElementList(
                Option::get(self::$module_id, 'highload_redirects'),
                [
                    'UF_URL_FROM' => $APPLICATION->GetCurPage(),
                ]
            );
            if($element) {
                $element = \array_shift($element);
                $urlTo = \trim($element['UF_URL_TO']);
                if($urlTo) {
                    \LocalRedirect(
                        $urlTo,
                        false,
                        '301 Moved Permanently'
                    );
                }
            }
        }

        return true;
    }
}