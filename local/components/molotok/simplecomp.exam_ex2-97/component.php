<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if(intval($arParams["NEWS_IBLOCK_ID"]) > 0 && $this->StartResultCache(false, $USER->GetID())) {
	// current user
	$rsUser = CUser::GetByID($USER->GetID());
	$curUser = $rsUser->Fetch();
	
	// user
	$arOrderUser = array("id");
	$sortOrder = "asc";
	$arFilterUser = array(
		"ACTIVE" => "Y",
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
	
	// news
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
		//array(
		//	"LOGIC" => "OR",
		//	array("<PROPERTY_RADIUS" => 50, "=PROPERTY_CONDITION" => "Y"),
		//	array(">=PROPERTY_RADIUS" => 50, "!=PROPERTY_CONDITION" => "Y"),
		//),
	);
	$arSortElems = array (
		"NAME" => "ASC",
	);
	
	$curUserNews = [];
	$arResult["NEWS"] = array();
	$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
	while($arElement = $rsElements->GetNext()) {
		$arResult["NEWS"][] = $arElement;
		
		if ($arElement["PROPERTY_" . $arParams["AUTHOR_PROP_CODE"] . "_VALUE"] == $curUser["ID"]) {
			$curUserNews[] = $arElement["ID"];
		}
	}
	
	foreach ($arResult["USERS"] as $userKey => $user) {
		foreach ($arResult["NEWS"] as $news) {
			if (
				$user["ID"] == $curUser["ID"] && $user["ID"] == $news["PROPERTY_" . $arParams["AUTHOR_PROP_CODE"] . "_VALUE"]
				|| $user["ID"] != $curUser["ID"] && !in_array($news["ID"], $curUserNews) && $user["ID"] == $news["PROPERTY_" . $arParams["AUTHOR_PROP_CODE"] . "_VALUE"]
				) {
				$arResult["USERS"][$userKey]["NEWS"][] = $news;
			}
		}
	}
	
	echo "<pre>";print_r($curUserNews);echo "</pre>";
	echo "<pre>";print_r($arResult["USERS"]);echo "</pre>";
}

$this->includeComponentTemplate();	
