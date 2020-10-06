<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");

$APPLICATION->IncludeComponent(
	"molotok:simplecomp.exam_ex2-71", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CATALOG_IBLOCK_ID" => "2",
		"DETAIL_URL_TEMPLATE" => "",
		"FIRM_IBLOCK_ID" => "5",
		"PROPERTY_CODE" => "FIRM",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
