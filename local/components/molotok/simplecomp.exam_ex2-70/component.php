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
	$prices = array_column($catalogItems, 'PROPERTY_PRICE_VALUE');
	$minPrice = GetMessage('MIN_PRICE') . min($prices);
	$maxPrice = GetMessage('MAX_PRICE') . max($prices);
	$arResult['NEWS'] = $newsItems;

	$this->SetResultCacheKeys([]);
	$this->IncludeComponentTemplate();
}

$this->AddIncludeAreaIcons(
    array(
        array(
            'URL' => '/bitrix/admin/iblock_element_admin.php?IBLOCK_ID=' . $arParams['CATALOG_IBLOCK'] . '&type=products&lang=ru&find_el_y=Y&clear_filter=Y&apply_filter=Y',
            'TITLE' => 'ИБ в админке',
			'IN_PARAMS_MENU' => true,
        ),
    )
);

$APPLICATION->SetTitle(GetMessage('COUNT_CATALOG_ITEMS') . $count);

$APPLICATION->AddViewContent('minPrice', '<div style="color:red; margin: 34px 15px 35px 15px">' . $minPrice . '</div>');
$APPLICATION->AddViewContent('maxPrice', '<div style="color:red; margin: 34px 15px 35px 15px">' . $maxPrice . '</div>');
