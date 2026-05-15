<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
foreach($arResult["SECTIONS"] as $key=>$arItem) {
	if(strlen($arItem["PICTURE"]["ID"]) > 0) {
		//$arResult["SECTIONS"][$key]["PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arItem["PICTURE"]["ID"], array("width"=>275, "height"=>140), BX_RESIZE_IMAGE_EXACT, false);
		$arResult["SECTIONS"][$key]["PICTURE"]["RESIZE_SMALL"] = CFile::ResizeImageGet($arItem["PICTURE"]["ID"], array("width"=>70, "height"=>70), BX_RESIZE_IMAGE_EXACT, false);
	} else {
		//$arResult["SECTIONS"][$key]["PICTURE"]["RESIZE"]["src"] = $this->__folder."/images/no_pic.png";
		$arResult["SECTIONS"][$key]["PICTURE"]["RESIZE_SMALL"]["src"] = $this->__folder."/images/no_pic.png";
	}
}

?>