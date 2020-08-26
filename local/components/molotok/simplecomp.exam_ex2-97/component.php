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
	
	$arResult["USERS"] = array();
	$rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser);
	while($arUser = $rsUsers->GetNext()) {
		$arResult["USERS"][] = $arUser;
	}
	
	echo "<pre>";print_r($arResult["USERS"]);echo "</pre>";
	
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
	);
	$arSortElems = array (
		"NAME" => "ASC",
	);
	
	$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
	while($arElement = $rsElements->GetNext()) {
		$arResult["NEWS"][] = $arElement;
		
		echo "<pre>";print_r($arElement);echo "</pre>";
	}
}

$this->includeComponentTemplate();	
