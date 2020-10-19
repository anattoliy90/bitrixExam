<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

global $CACHE_MANAGER;

$arResult['FIRM_COUNT'] = 0;
$addFilter = [];

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if (isset($_REQUEST['F'])) {
	$arParams["CACHE_TIME"] = 0;
	
	$addFilter[] = [
		'LOGIC' => 'OR',
		['<=PROPERTY_PRICE' => 1700, 'PROPERTY_MATERIAL' => 'Дерево, ткань'],
		['<PROPERTY_PRICE' => 1500, 'PROPERTY_MATERIAL' => 'Металл, пластик'],
	];
}

$arNavParams = false;

if (!empty($arParams['ELEMENTS_ON_PAGE'])) {
	$arNavParams = ['nPageSize' => $arParams['ELEMENTS_ON_PAGE']];
	$arNavigation = CDBResult::GetNavParams($arNavParams);
}

if ($this->StartResultCache(false, [$USER->GetGroups(), $arNavigation])) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	$arResult['FIRMS'] = [];
	$items = [];
	
	$res = CIBlockElement::GetList([], ['IBLOCK_ID' => $arParams['FIRM_IBLOCK_ID'], 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y'], false, $arNavParams, ['ID', 'NAME']);
	while ($ob = $res->GetNext()) {
		// Тегированный кеш для инфоблока Услуги
		$CACHE_MANAGER->RegisterTag('iblock_id_3');
		
		$arResult['FIRMS'][] = [
			'ID' => $ob['ID'],
			'NAME' => $ob['NAME'],					   
		];
	}
	
	$arResult['FIRM_COUNT'] = count($arResult['FIRMS']);
	$firmId = array_column($arResult['FIRMS'], 'ID');
	$filter = [
		'IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'],
		'PROPERTY_' . $arParams['PROPERTY_CODE'] => $firmId,
		'ACTIVE' => 'Y',
		'CHECK_PERMISSIONS' => 'Y',
	];
	
	if (!empty($addFilter)) {
		$filter = array_merge($filter, $addFilter);
	}
	
	$result = CIBlockElement::GetList(['NAME' => 'ASC', 'SORT' => 'ASC'], $filter, false, false, ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_' . $arParams['PROPERTY_CODE'], 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER', 'DETAIL_PAGE_URL']);
	$result->SetUrlTemplates($arParams['DETAIL_URL_TEMPLATE']);
	while ($obj = $result->GetNext()) {
		$arButtons = CIBlock::GetPanelButtons(
			$obj['IBLOCK_ID'],
			$obj['ID'],
			0,
			['SECTION_BUTTONS' => false, 'SESSID' => false]
		);
		
		$obj['EDIT_LINK'] = $arButtons['edit']['edit_element']['ACTION_URL'];
		$obj['DELETE_LINK'] = $arButtons['edit']['delete_element']['ACTION_URL'];
		
		$items[] = [
			'ID' => $obj['ID'],
			'IBLOCK_ID' => $obj['IBLOCK_ID'],
			'NAME' => $obj['NAME'],
			'FIRM_ID' => $obj['PROPERTY_FIRM_VALUE'],
			'PRICE' => $obj['PROPERTY_PRICE_VALUE'],
			'MATERIAL' => $obj['PROPERTY_MATERIAL_VALUE'],
			'ARTNUMBER' => $obj['PROPERTY_ARTNUMBER_VALUE'],
			'DETAIL_PAGE_URL' => $obj['DETAIL_PAGE_URL'],
			'EDIT_LINK' => $obj['EDIT_LINK'],
			'DELETE_LINK' => $obj['DELETE_LINK'],
		];
	}
	
	// Добавление кнопки Добавить товар в Эрмитаж
	if($USER->IsAuthorized()) {
		if($APPLICATION->GetShowIncludeAreas()) {
			if(Loader::includeModule('iblock')) {
				$arButtons = CIBlock::GetPanelButtons(
					$arParams['CATALOG_IBLOCK_ID'],
					0,
					0,
					['SECTION_BUTTONS' => false]
				);
			
				if($APPLICATION->GetShowIncludeAreas()) {
					$this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
				}
			}
		}
	}
	
	foreach ($arResult['FIRMS'] as $key => $firm) {
		foreach ($items as $item) {
			if ($firm['ID'] == $item['FIRM_ID']) {
				$arResult['FIRMS'][$key]['ITEMS'][] = $item;
			}
		}
	}
	
	$arResult['NAV_STRING'] = $res->GetPageNavStringEx(
		$navComponentObject,
		GetMessage('PAGES'),
		''
	);
	
	$this->SetResultCacheKeys(['FIRM_COUNT']);
	$this->IncludeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SECTIONS') . $arResult['FIRM_COUNT']);
