<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if($arResult["SECTION"]["ID"] > 0) {
	$arResult["SECTION"]["FLOORS"][] = Array(
		"NAME" => "Все боксы склада",
		"CODE" => "",
		"URL" => "/rental_catalog/".$arResult["SECTION"]["CODE"]."/"
	);	
	
	//формируем массив этажей
	if(strlen($arResult["SECTION"]["UF_FLOORS"]) > 0) {
		$floorsCount = 0;
		$userField = CUserFieldEnum::GetList(array("SORT"=>"ASC"), array("ID"=>$arResult["SECTION"]["UF_FLOORS"]));
		if($arUserField = $userField->GetNext()) {
			$floorsCount = intval($arUserField["VALUE"]);
		}

		if($floorsCount > 0) {
			$propEnums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>"FLOOR"));
			$cnt = 0;
			while ($arEnum = $propEnums->GetNext()) {
				$cnt++;
				if($cnt <= $floorsCount) {
					$arResult["SECTION"]["FLOORS"][] = Array("NAME"=>"Боксы - ".$arEnum["VALUE"], "CODE"=>$arEnum["XML_ID"], "URL"=>"/rental_catalog/".$arResult["SECTION"]["CODE"]."/".$arEnum["XML_ID"]."/");
				}
			}			
		}
	}
	
	//формируем массив с данными о схеме этажа
	$arResult["SECTION"]["MAP_FLOOR"] = Array();
	$arFloorsXmlId = Array("floor-1", "floor-2", "floor-3", "floor-4", "floor-5");
	if(in_array($arParams["FLOOR_CODE"], $arFloorsXmlId)) {
		$ufPropCode = "UF_MAP_FLOOR_".preg_replace("/[^0-9]/", "", $arParams["FLOOR_CODE"]);
		if(strlen($arResult["SECTION"][$ufPropCode]) > 0) {
			//название этажа
			$arFloorNames = Array(1=>"первого", "второго", "третьего", "четвертого", "пятого");
			$arResult["SECTION"]["MAP_FLOOR"]["FLOOR_NAME"] = $arFloorNames[preg_replace("/[^0-9]/", "", $arParams["FLOOR_CODE"])];
			//картинка этажа
			$rsPic = CFile::GetByID($arResult["SECTION"][$ufPropCode]);
			$arPic = $rsPic->Fetch();
			$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["WIDTH"] = $arPic["WIDTH"];
			$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["HEIGHT"] = $arPic["HEIGHT"];
			$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["SRC"] = CFile::GetPath($arResult["SECTION"][$ufPropCode]);
			//инфо о количестве свободных боксов
			$openedBoxesCount = 0;
			$openedBoxesSquare = 0;
			$res = CIBlockElement::GetList(Array("ID"=>"asc"), Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ACTIVE"=>"Y", "SECTION_CODE"=>$arResult["SECTION"]["CODE"], "PROPERTY_FLOOR_VALUE"=>preg_replace("/[^0-9]/", "", $arParams["FLOOR_CODE"])." этаж", "PROPERTY_STATUS"=>BOX_STATUS_OPENED_ID), false, Array(), Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_SQUARE"));
			while($ob = $res->GetNextElement()) {
				$arFields = $ob->GetFields();
				$openedBoxesCount++;
				$openedBoxesSquare = $openedBoxesSquare + floatval($arFields["PROPERTY_SQUARE_VALUE"]);
			}
			$arResult["SECTION"]["MAP_FLOOR"]["OPENED_BOXES_COUNT"] = $openedBoxesCount;
			$arResult["SECTION"]["MAP_FLOOR"]["OPENED_BOXES_SQUARE"] = $openedBoxesSquare;
		}
	}
}
?>


<?$this->__component->SetResultCacheKeys(array("CACHED_TPL", "MAP_POINTS"));?>