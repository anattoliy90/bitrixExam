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
	
	$rsIBlockElement = CIBlockElement::GetList([], [], false, false, []);
	// $rsIBlockElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	while ($arResult = $rsIBlockElement->GetNext()) {
	
	}
	
	$this->SetResultCacheKeys([]);
	$this->IncludeComponentTemplate();
}
