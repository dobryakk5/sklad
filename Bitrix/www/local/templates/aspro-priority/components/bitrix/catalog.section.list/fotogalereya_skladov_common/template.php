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
ob_start();
?>


<?if(count($arResult["SECTIONS"]) > 0) {?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="photogallery_list_sections">
                <?foreach($arResult["SECTIONS"] as $arItem) {?>
                    <?
                    $arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
                    $this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
                    $this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="photogallery_list_item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                        <div class="body-info">
                            <div class="h3 title"><?=$arItem["NAME"]?></div>
                        </div>
                        <div class="wrap">
                            <? foreach ($arItem["PHOTOGALLERY"] as $photo):?>
                                <a href="<?=$photo["PICTURE"]["RESIZE"]["BIG"]["src"]?>" class="fancybox photogallery_item" data-fancybox-group="gallery-section-<?=$arItem["ID"]?>">
                                    <img src="<?=$photo["PICTURE"]["RESIZE"]["SMALL"]["src"]?>" alt="<?=$photo["NAME"]?>" title="<?=$photo["NAME"]?>">
                                </a>
                            <?endforeach?>

                        </div>
                    </div>
                <?}?>
			</div>
		</div>
		<?/*
		<div class="col-md-3 col-xs-12">
			#SKLAD_LIST#
		</div>
		*/?>
	</div>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CreativeWork",
            "name": "Фотогалерея складов",
            "description": "Коллекция фото складских комплексов компании АльфаСклад",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
            },
            "image": [
                <?php foreach ($arResult["SECTIONS"] as $ks => $arItem): ?>
                    <?php foreach ($arItem["PHOTOGALLERY"] as $kp => $photo): ?>
                        {
                            "@type": "ImageObject",
                            "url": "<?=$photo["PICTURE"]["RESIZE"]["BIG"]["src"]?>",
                            "contentUrl": "<?=$photo["PICTURE"]["RESIZE"]["SMALL"]["src"]?>",
                            "caption": "<?=$arItem["NAME"]?><?php if ($kp > 0): ?> - <?= $kp ?><?php endif; ?>"
                        }
                        <?php if ($ks == (count($arResult["SECTIONS"]) - 1) && $kp == (count($arItem["PHOTOGALLERY"]) - 1)): ?>
                        <?php else: ?>
                        ,
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            ]
        }
    </script>
<?} else {?>
	Складов не найдено
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>