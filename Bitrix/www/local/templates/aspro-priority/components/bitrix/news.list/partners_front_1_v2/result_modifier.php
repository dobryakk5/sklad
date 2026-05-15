<?
foreach($arResult['ITEMS'] as $key => $arItem){
	CPriority::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL"));?>