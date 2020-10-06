<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if($this->StartResultCache(false, ($USER->GetGroups()))) {
	if(!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	$firm = [];
	
	$res = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['FIRM_IBLOCK_ID']], false, false, ['ID', 'NAME']);
	// $rsIBlockElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	while ($ob = $res->GetNext()) {
		$firm[$ob['ID']] = $ob['NAME'];
	}
	
	$result = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'], 'PROPERTY_' . $arParams['PROPERTY_CODE'] => array_keys($firm)], false, false, ['ID', 'NAME', 'PROPERTY_' . $arParams['PROPERTY_CODE'], 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER', 'DETAIL_PAGE_URL']);
	while ($obj = $result->GetNext()) {
		echo '<pre>';print_r($obj);echo '</pre>';
	}
	
	$this->SetResultCacheKeys([]);
	$this->IncludeComponentTemplate();
}
