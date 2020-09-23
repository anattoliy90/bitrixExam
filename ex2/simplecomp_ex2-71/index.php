<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");

$APPLICATION->IncludeComponent(
	"molotok:simplecomp.exam_ex2-71",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CATALOG_IBLOCK_ID" => "",
		"DETAIL_URL_TEMPLATE" => "",
		"FIRM_IBLOCK_ID" => "",
		"PROPERTY_CODE" => ""
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
