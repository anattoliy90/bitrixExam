<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"CATALOG_IBLOCK_ID" => array(
			"NAME" => GetMessage("CATALOG_IBLOCK_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"FIRM_IBLOCK_ID" => array(
			"NAME" => GetMessage("FIRM_IBLOCK_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"DETAIL_URL_TEMPLATE" => array(
			"NAME" => GetMessage("DETAIL_URL_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"PROPERTY_CODE" => array(
			"NAME" => GetMessage("PROPERTY_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"ELEMENTS_ON_PAGE" => array(
			"NAME" => GetMessage("ELEMENTS_ON_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
