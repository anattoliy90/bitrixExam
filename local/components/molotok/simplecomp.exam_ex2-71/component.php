<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult['FIRM_COUNT'] = 0;

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if ($this->StartResultCache(false, $USER->GetGroups())) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	$arResult['FIRMS'] = [];
	$items = [];
	
	$res = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['FIRM_IBLOCK_ID'], 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y'], false, false, ['ID', 'NAME']);
	while ($ob = $res->GetNext()) {
		$arResult['FIRMS'][] = [
			'ID' => $ob['ID'],
			'NAME' => $ob['NAME'],					   
		];
	}
	
	$arResult['FIRM_COUNT'] = count($arResult['FIRMS']);
	$firmId = array_column($arResult['FIRMS'], 'ID');
	
	$result = CIBlockElement::GetList(['NAME' => 'ASC', 'SORT' => 'ASC'], ['IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'], 'PROPERTY_' . $arParams['PROPERTY_CODE'] => $firmId, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y'], false, false, ['ID', 'NAME', 'PROPERTY_' . $arParams['PROPERTY_CODE'], 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER', 'DETAIL_PAGE_URL']);
	$result->SetUrlTemplates($arParams['DETAIL_URL_TEMPLATE']);
	while ($obj = $result->GetNext()) {
		$items[] = [
			'ID' => $obj['ID'],
			'NAME' => $obj['NAME'],
			'FIRM_ID' => $obj['PROPERTY_FIRM_VALUE'],
			'PRICE' => $obj['PROPERTY_PRICE_VALUE'],
			'MATERIAL' => $obj['PROPERTY_MATERIAL_VALUE'],
			'ARTNUMBER' => $obj['PROPERTY_ARTNUMBER_VALUE'],
			'DETAIL_PAGE_URL' => $obj['DETAIL_PAGE_URL'],
		];
	}
	
	foreach ($arResult['FIRMS'] as $key => $firm) {
		foreach ($items as $item) {
			if ($firm['ID'] == $item['FIRM_ID']) {
				$arResult['FIRMS'][$key]['ITEMS'][] = $item;
			}
		}
	}
	
	$this->SetResultCacheKeys(['FIRM_COUNT']);
	$this->IncludeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SECTIONS') . $arResult['FIRM_COUNT']);
