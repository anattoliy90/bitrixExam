<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if($this->StartResultCache()) {
	if(!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	$sections = [];
	$catalogItems = [];
	$newsItems = [];
	
	$qSect = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['CATALOG_IBLOCK'], 'ACTIVE' => 'Y', '!' . $arParams['PROP_NEWS_LINK'] => false], false, ['ID', 'NAME'], false);
	while ($rSect = $qSect->GetNext()) {
		$sections[$rSect['ID']] = $rSect;
	}
	
	$qElem = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['CATALOG_IBLOCK'], 'ACTIVE' => 'Y', 'SECTION_ID' => array_keys($sections)], false, false, ['ID', 'NAME', 'IBLOCK_SECTION_ID', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER']);
	while ($rElem = $qElem->GetNext()) {
		$catalogItems[] = $rElem;
	}
	
	$qNews = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['NEWS_IBLOCK'], 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME']);
	while ($rNews = $qNews->GetNext()) {
		$newsItems[] = $rNews;
	}
	
	echo '<pre>';print_r($newsItems);echo '</pre>';

	$this->SetResultCacheKeys([]);
	$this->IncludeComponentTemplate();
}
