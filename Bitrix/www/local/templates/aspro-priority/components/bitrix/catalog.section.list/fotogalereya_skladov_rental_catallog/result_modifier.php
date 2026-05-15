<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$destinationFile = $_SERVER['DOCUMENT_ROOT']."/upload/images/square_".$arParams["SIZE_FROM"].".jpg";
if(strlen($arParams["SIZE_FROM"]) > 0) {
	CFile::ResizeImageFile(
		$_SERVER['DOCUMENT_ROOT'].$this->__folder."/images/square_".$arParams["SIZE_FROM"].".jpg",
        $destinationFile,
		array("width"=>100,"height"=>100),
		BX_RESIZE_IMAGE_EXACT,
		array(),
		false,
		false
	);
	
	$arResult["GALLERY_SQUARE_PICTURE"] = Array(
		"BIG"=>$this->__folder."/images/square_".$arParams["SIZE_FROM"].".jpg", 
		"MEDIUM"=>$this->__folder."/images/square_".$arParams["SIZE_FROM"].".jpg",
		"SMALL"=>"/upload/images/square_".$arParams["SIZE_FROM"].".jpg",
	);
}


$arResult["GALLERY"] = Array();
if(strlen($arResult["SECTION"]["UF_PHOTOGALLERY"]) > 0) {
    $arSelect = Array("ID", "NAME", "DETAIL_PICTURE");
    $arFilter = Array("IBLOCK_ID"=>43, "ACTIVE"=>"Y", "SECTION_ID"=>$arResult["SECTION"]["UF_PHOTOGALLERY"]);
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
    $cnt = 0;
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        $arResult["GALLERY"][$cnt]["BIG"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
        $arResult["GALLERY"][$cnt]["MEDIUM"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>427, "height"=>305), BX_RESIZE_IMAGE_EXACT, false);
        $arResult["GALLERY"][$cnt]["SMALL"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>100, "height"=>100), BX_RESIZE_IMAGE_EXACT, false);
        $arResult["GALLERY"][$cnt]["DESCRIPTION"] = $arFields["NAME"];
        $cnt++;
    }    
} else {
	$cnt = 0;
	foreach($arResult["SECTIONS"] as $arSection) {
		if(strlen($arSection["UF_PHOTOGALLERY"]) > 0) {
			$arSelect = Array("ID", "NAME", "DETAIL_PICTURE");
			$arFilter = Array("IBLOCK_ID"=>43, "ACTIVE"=>"Y", "SECTION_ID"=>$arSection["UF_PHOTOGALLERY"]);
			$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
			while($ob = $res->GetNextElement())
			{
				$arFields = $ob->GetFields();
				$arResult["GALLERY"][$cnt]["BIG"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
				$arResult["GALLERY"][$cnt]["MEDIUM"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>427, "height"=>305), BX_RESIZE_IMAGE_EXACT, false);
				$arResult["GALLERY"][$cnt]["SMALL"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>100, "height"=>100), BX_RESIZE_IMAGE_EXACT, false);
				$arResult["GALLERY"][$cnt]["DESCRIPTION"] = $arFields["NAME"];
				$cnt++;
			}    
		}	
	}
}

?>