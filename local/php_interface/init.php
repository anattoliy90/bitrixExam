<?
use Bitrix\Main\Localization\Loc;

AddEventHandler("main", "OnBeforeEventAdd", ["MyClass", "OnBeforeEventAddHandler"]);
AddEventHandler("main", "OnBuildGlobalMenu", ["MyClass", "OnBuildGlobalMenuHandler"]);

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
                "AUDIT_TYPE_ID" => "Замена данных в отсылаемом письме",
                "MODULE_ID" => "main",
                "DESCRIPTION" => "Замена данных в отсылаемом письме – " . $arFields["AUTHOR"],
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
}
