<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

$count = 0;

if($this->StartResultCache()) {
	if(!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	$sections = [];
	$catalogItems = [];
	$newsItems = [];
	
	$qSect = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['CATALOG_IBLOCK'], 'ACTIVE' => 'Y', '!' . $arParams['PROP_NEWS_LINK'] => false], false, ['ID', 'NAME', $arParams['PROP_NEWS_LINK']], false);
	while ($rSect = $qSect->GetNext()) {
		$sections[$rSect['ID']] = $rSect;
	}
	
	$qElem = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['CATALOG_IBLOCK'], 'ACTIVE' => 'Y', 'SECTION_ID' => array_keys($sections)], false, false, ['ID', 'NAME', 'IBLOCK_SECTION_ID', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER']);
	while ($rElem = $qElem->GetNext()) {
		$catalogItems[] = $rElem;
	}
	
	$qNews = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['NEWS_IBLOCK'], 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME', 'ACTIVE_FROM']);
	while ($rNews = $qNews->GetNext()) {
		$newsItems[] = $rNews;
	}
	
	foreach ($newsItems as $newsK => $newsV) {
		$sectionIds = [];
		
		foreach ($sections as $section) {
			if (in_array($newsV['ID'], $section['UF_NEWS_LINK'])) {
				$sectionIds[] = $section['ID'];
				$newsItems[$newsK]['SECTIONS'][] = $section['NAME'];
			}
		}
		
		foreach ($catalogItems as $item) {
			if (in_array($item['IBLOCK_SECTION_ID'], $sectionIds)) {
				$newsItems[$newsK]['ITEMS'][] = $item;
			}
		}
	}
	
	$count = count($catalogItems);
	$arResult['NEWS'] = $newsItems;

	$this->SetResultCacheKeys([]);
	$this->IncludeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('COUNT_CATALOG_ITEMS') . $count);
