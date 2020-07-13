<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"CATALOG_IBLOCK" => array(
			"NAME" => GetMessage("CATALOG_IBLOCK"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"NEWS_IBLOCK" => array(
			"NAME" => GetMessage("NEWS_IBLOCK"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"PROP_NEWS_LINK" => array(
			"NAME" => GetMessage("PROP_NEWS_LINK"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
