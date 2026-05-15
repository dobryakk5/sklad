<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>


<?if(count($arResult["SECTIONS"]) > 0) {?>
	<div class="title">Выберите склад</div>
	<div class="sklad_list" ss-container>
		<?foreach($arResult["SECTIONS"] as $arSklad) {?>
			<div class="sklad">
				<div class="info">
					<div class="name"><?=$arSklad["NAME"]?></div>
					<div class="address"><?=$arSklad["UF_ADDRESS"]?></div>
				</div>
				<div class="checkbox_container">
					<div class="value"><input type="checkbox" value="Y" data-sklad-code="<?=$arSklad["CODE"]?>" /><label></label></div>											
				</div>
			</div>
		<?}?>										
	</div>
	<div class="button">
		<a class="btn btn-default disabled" href="#">Перейти к выбору бокса</a>
	</div>
<?}?>
