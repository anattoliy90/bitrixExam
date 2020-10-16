<?
use Bitrix\Main\Localization\Loc;

AddEventHandler("main", "OnBeforeEventAdd", ["MyClass", "OnBeforeEventAddHandler"]);
AddEventHandler("main", "OnBuildGlobalMenu", ["MyClass", "OnBuildGlobalMenuHandler"]);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["MyClass", "OnBeforeIBlockElementUpdateHandler"]);
AddEventHandler("main", "OnEpilog", ["MyClass", "OnEpilogHandler"]);

class MyClass
{
	function OnBeforeEventAddHandler(&$event, &$lid, &$arFields) {
        if ($event == "FEEDBACK_FORM") {
            global $USER;
            
            $mess = Loc::getMessage("USET_NOT_AUTHORIZED") . ', ' . Loc::getMessage("FORM_DATA") . ': ' . $arFields["AUTHOR"];
            
            if ($USER->IsAuthorized()) {
                $mess = Loc::getMessage("USET_AUTHORIZED") . ': ' . $USER->GetID() . ' (' . $USER->GetLogin() . ') ' . $USER->GetFullName() . ', ' . Loc::getMessage("FORM_DATA") . ': ' . $arFields["AUTHOR"];
            }
            
            $arFields["AUTHOR"] = $mess;
        
            CEventLog::Add(array(
                "AUDIT_TYPE_ID" => Loc::getMessage("REPLACE_DATA_IN_MAIL"),
                "MODULE_ID" => "main",
                "DESCRIPTION" => Loc::getMessage("REPLACE_DATA_IN_MAIL") . ' - ' . $arFields["AUTHOR"],
            ));
        }
	}
	
	function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu) {
		global $USER;
		$groups = CUser::GetUserGroup($USER->GetID());
		
		if (in_array(5, $groups) && !$USER->IsAdmin()) {
			unset($aGlobalMenu['global_menu_desktop']);
			
			foreach ($aModuleMenu as $menuKey => $menu) {
				if ($menu['items_id'] == 'menu_iblock' && $menu['parent_menu'] == 'global_menu_content') {
					unset($aModuleMenu[$menuKey]);
				}
			}
		}
	}
	
	function OnBeforeIBlockElementUpdateHandler(&$arFields) {
		// 3 - Id инфоблока Услуги
		if ($arFields['IBLOCK_ID'] == 3) {
			AddMessage2Log($arFields);
		}
		
		$showCounter = 0;
		
		$res = CIBlockElement::GetList([], ['IBLOCK_ID' => 2, 'ID' => $arFields['ID']], false, false, ['ID', 'SHOW_COUNTER']);
		if ($ob = $res->GetNext()) {
			$showCounter = $ob['SHOW_COUNTER'];
		}
		
		if ($showCounter > 2 && $arFields['ACTIVE'] == 'N') {
			global $APPLICATION;
			$APPLICATION->throwException(Loc::getMessage("ERROR_TEXT_1") . $showCounter . Loc::getMessage("ERROR_TEXT_2"));
			return false;
		}
    }
	
	function OnEpilogHandler() {
		if (defined('ERROR_404') && ERROR_404 == 'Y') {
			CEventLog::Add(array(
				"SEVERITY" => "INFO",
				"AUDIT_TYPE_ID" => "ERROR_404",
				"MODULE_ID" => "main",
				"DESCRIPTION" => $_SERVER['REQUEST_URI'],
			));
		}
		
		$res = CIBlockElement::GetList([], ['IBLOCK_ID' => 5, '=NAME' => $_SERVER['REQUEST_URI']], false, false, ['ID', 'NAME', 'PROPERTY_TITLE', 'PROPERTY_DESCRIPTION']);
		if ($ob = $res->GetNext()) {
			global $APPLICATION;
			$APPLICATION->SetTitle($ob['PROPERTY_TITLE_VALUE']);
			$APPLICATION->SetPageProperty('description', $ob['PROPERTY_DESCRIPTION_VALUE']);
		}
	}
}
