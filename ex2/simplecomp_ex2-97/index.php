<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->IncludeComponent(
	"molotok:simplecomp.exam_ex2-97",
	"",
	Array(
		"AUTHOR_PROP_CODE" => "AUTHOR",
		"AUTHOR_TYPE_PROP_CODE" => "UF_AUTHOR_TYPE",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"NEWS_IBLOCK_ID" => "1"
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
