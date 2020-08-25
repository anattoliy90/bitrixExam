<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("NEWS_IBLOCK_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"AUTHOR_PROP_CODE" => array(
			"NAME" => GetMessage("AUTHOR_PROP_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"AUTHOR_TYPE_PROP_CODE" => array(
			"NAME" => GetMessage("AUTHOR_TYPE_PROP_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
