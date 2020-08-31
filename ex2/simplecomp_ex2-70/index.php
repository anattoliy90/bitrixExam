<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->IncludeComponent(
	"molotok:simplecomp.exam_ex2-70",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CATALOG_IBLOCK" => "2",
		"NEWS_IBLOCK" => "1",
		"PROP_NEWS_LINK" => "UF_NEWS_LINK"
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
