<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['SPECIALDATE'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];

$cp = $this->__component;

if (is_object($cp)) {
	$cp->SetResultCacheKeys(['SPECIALDATE']);
}
