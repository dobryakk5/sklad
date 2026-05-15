<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$arResult["GALLERY"] = Array();

if(strlen($arParams["SQUARE_FROM"]) > 0) {
	$arParams["SQUARE_FROM"] = ceil($arParams["SQUARE_FROM"]);
    CFile::ResizeImageFile(
        $_SERVER['DOCUMENT_ROOT'].$this->__folder."/images/square_".$arParams["SQUARE_FROM"].".jpg",
        $destinationFile = $_SERVER['DOCUMENT_ROOT']."/upload/images/square_".$arParams["SQUARE_FROM"].".jpg",
        array("width"=>100,"height"=>100),
        BX_RESIZE_IMAGE_EXACT,
        array(),
        false,
        false
    );
    
    $arResult["GALLERY"][] = Array(
        "BIG"=>Array("src" => $this->__folder."/images/square_".$arParams["SQUARE_FROM"].".jpg"), 
        "MEDIUM"=>Array("src" => $this->__folder."/images/square_".$arParams["SQUARE_FROM"].".jpg"),
        "SMALL"=>Array("src" => "/upload/images/square_".$arParams["SQUARE_FROM"].".jpg"),
    );
}

foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {
		$arGallery = Array();
		$arGallery["BIG"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		$arGallery["MEDIUM"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width"=>427, "height"=>305), BX_RESIZE_IMAGE_EXACT, false);
		$arGallery["SMALL"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width"=>100, "height"=>100), BX_RESIZE_IMAGE_EXACT, false);
		if(strlen($arItem["PREVIEW_PICTURE"]["DESCRIPTION"]) > 0) {
			$arGallery["DESCRIPTION"] = $arItem["PREVIEW_PICTURE"]["DESCRIPTION"];
		} else {
			$arGallery["DESCRIPTION"] = $arItem["NAME"];
		}
		
		$arResult["GALLERY"][] = $arGallery;
	}
	if(!empty($arItem["PROPERTIES"]["GALLERY"]["VALUE"])) {
		foreach($arItem["PROPERTIES"]["GALLERY"]["VALUE"] as $key2=>$arPic) {
			$arGallery = Array();
			$arGallery["BIG"] = CFile::ResizeImageGet($arPic, array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
			$arGallery["MEDIUM"] = CFile::ResizeImageGet($arPic, array("width"=>427, "height"=>305), BX_RESIZE_IMAGE_EXACT, false);
			$arGallery["SMALL"] = CFile::ResizeImageGet($arPic, array("width"=>100, "height"=>100), BX_RESIZE_IMAGE_EXACT, false);
			if(strlen($arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$key2]) > 0) {
				$arGallery["DESCRIPTION"] = $arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$key2];
			} else {
				$arGallery["DESCRIPTION"] = $arItem["NAME"];
			}

			$arResult["GALLERY"][] = $arGallery;
		}
	}
}
?>