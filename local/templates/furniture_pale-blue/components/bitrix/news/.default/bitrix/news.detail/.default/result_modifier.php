<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arParams['CANONICAL'])) {
	$res = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['CANONICAL'], 'PROPERTY_CANONICAL' => $arResult['ID']], false, false, ['ID', 'NAME']);
	if ($ob = $res->GetNext()) {
		$arResult['CANONICAL'] = $ob['NAME'];
		
		$this->__component->SetResultCacheKeys(['CANONICAL']);
	}
}
