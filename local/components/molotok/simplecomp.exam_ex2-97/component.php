<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if($USER->IsAuthorized() && intval($arParams["NEWS_IBLOCK_ID"]) > 0 && $this->StartResultCache(false, $USER->GetID())) {
	// current user
	$rsUser = CUser::GetByID($USER->GetID());
	$curUser = $rsUser->Fetch();
	
	// get users
	$arOrderUser = array("id");
	$sortOrder = "asc";
	$arFilterUser = array(
		"ACTIVE" => "Y",
		"!ID" => $curUser["ID"],
		$arParams["AUTHOR_TYPE_PROP_CODE"] => $curUser["UF_AUTHOR_TYPE"],
	);
	$arSelect = [
		"FIELDS" => ["ID", "LOGIN"],
		"SELECT" => [$arParams["AUTHOR_TYPE_PROP_CODE"]],
	];
	
	$arResult["USERS"] = array();
	$rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser, $arSelect);
	while($arUser = $rsUsers->GetNext()) {
		$arResult["USERS"][] = $arUser;
	}
	
	// get news
	$arSelectElems = array (
		"ID",
		"IBLOCK_ID",
		"NAME",
		"ACTIVE_FROM",
		"PROPERTY_" . $arParams["AUTHOR_PROP_CODE"],
	);
	$arFilterElems = array (
		"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
		"ACTIVE" => "Y",
	);
	$arSortElems = array (
		"NAME" => "ASC",
	);
	
	$curUserNews = [];
	$arResult["NEWS"] = [];
	
	$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
	while($arElement = $rsElements->GetNext()) {
		$arResult["NEWS"][] = $arElement;
		
		if ($arElement["PROPERTY_" . $arParams["AUTHOR_PROP_CODE"] . "_VALUE"] == $curUser["ID"]) {
			$curUserNews[] = $arElement["ID"];
		}
	}
	
	$arResult["COUNT"] = [];
	
	foreach ($arResult["USERS"] as $userKey => $user) {
		foreach ($arResult["NEWS"] as $news) {
			if ($user["ID"] == $news["PROPERTY_" . $arParams["AUTHOR_PROP_CODE"] . "_VALUE"] && !in_array($news["ID"], $curUserNews)) {
				$arResult["USERS"][$userKey]["NEWS"][] = $news;
				
				if (!in_array($news["ID"], $arResult["COUNT"])) {
					$arResult["COUNT"][] = $news["ID"];
				}
			}
		}
	}
	
	$arResult["COUNT"] = count($arResult["COUNT"]);
	
	$this->SetResultCacheKeys(["COUNT"]);
	$this->includeComponentTemplate();
}

$APPLICATION->SetTitle('Новостей [' . $arResult["COUNT"] . ']');
