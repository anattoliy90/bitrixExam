<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if ($arParams['SPECIALDATE'] == 'Y' && !empty($arResult['SPECIALDATE'])) {
	$APPLICATION->SetPageProperty('specialdate', $arResult['SPECIALDATE']);
}
