<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>

<?
global $isMenu, $arTheme;
use \Bitrix\Main\Localization\Loc;

$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (isset($arTheme['URL_BASKET_SECTION']) && strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? $arTheme['URL_BASKET_SECTION']['VALUE'] : SITE_DIR.'cart/');
$dataItem = ($bOrderViewBasket ? CPriority::getDataItem($arResult) : false);
$bFormQuestion = (isset($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']) && $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE'] == 'Y');
$catalogLinkedTemplate = (isset($arTheme['ELEMENTS_TABLE_TYPE_VIEW']) && $arTheme['ELEMENTS_TABLE_TYPE_VIEW']['VALUE'] == 'catalog_table_2' ? 'catalog_linked_2' : 'catalog_linked');

/*set array props for component_epilog*/
$templateData = array(
	'DOCUMENTS' => $arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE'],
	'LINK_SALE' => $arResult['DISPLAY_PROPERTIES']['LINK_SALE']['VALUE'],
	'LINK_FAQ' => $arResult['DISPLAY_PROPERTIES']['LINK_FAQ']['VALUE'],
	'LINK_PROJECTS' => $arResult['DISPLAY_PROPERTIES']['LINK_PROJECTS']['VALUE'],
	'LINK_SERVICES' => $arResult['DISPLAY_PROPERTIES']['LINK_SERVICES']['VALUE'],
	'LINK_GOODS' => $arResult['DISPLAY_PROPERTIES']['LINK_GOODS']['VALUE'],
	'LINK_PARTNERS' => $arResult['DISPLAY_PROPERTIES']['LINK_PARTNERS']['VALUE'],
	'LINK_SERTIFICATES' => $arResult['DISPLAY_PROPERTIES']['LINK_SERTIFICATES']['VALUE'],
	'LINK_VACANCYS' => $arResult['DISPLAY_PROPERTIES']['LINK_VACANCYS']['VALUE'],
	'LINK_STAFF' => $arResult['DISPLAY_PROPERTIES']['LINK_STAFF']['VALUE'],
	'LINK_REVIEWS' => $arResult['DISPLAY_PROPERTIES']['LINK_REVIEWS']['VALUE'],
	'LINK_TARIFS' => $arResult['DISPLAY_PROPERTIES']['LINK_TARIFS']['VALUE'],
	'BRAND_ITEM' => $arResult['BRAND_ITEM'],
	'GALLERY_BIG' => $arResult['GALLERY'],
	'CHARACTERISTICS' => $arResult['CHARACTERISTICS'],
	'VIDEO' => $arResult['VIDEO'],
	'VIDEO_IFRAME' => $arResult['VIDEO_IFRAME'],
	'PREVIEW_TEXT' => $arResult['PREVIEW_TEXT'],
	'DETAIL_TEXT' => $arResult['FIELDS']['DETAIL_TEXT'],
	'ORDER' => $bOrderViewBasket,
	'CATALOG_LINKED_TEMPLATE' => $catalogLinkedTemplate,
	'FORM_ORDER' => $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'],
	'FORM_QUESTION' => $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'],
	'FORM_STORAGE' => $arResult['DISPLAY_PROPERTIES']['FORM_STORAGE']['VALUE_XML_ID'],
	'PRICE' => $arResult['PROPERTIES']['PRICE']['VALUE'],
	'PRICE_OLD' => $arResult['PROPERTIES']['PRICE_OLD']['VALUE'],
    'SLIDER_1' => $arResult['PHOTOS_SLIDER_1'],
    'SLIDER_2' => $arResult['PHOTOS_SLIDER_2'],
);

if(isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES')
	$templateData['SECTION_BNR_CONTENT'] = true;
?>

<?// shot top banners start?>
<?$bShowTopBanner = (isset($arResult['SECTION_BNR_CONTENT'] ) && $arResult['SECTION_BNR_CONTENT'] == true);?>
<?if($bShowTopBanner):?>
	<?$this->SetViewTarget("section_bnr_content");?>
    <?php
        $bg = ((isset($arResult['PROPERTIES']['BNR_TOP_BG']) && $arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) ? CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) : './images/main-img.png');
        $title = ($arResult['IPROPERTY_VALUES'] && strlen($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? htmlspecialchars_decode($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) : htmlspecialchars_decode($arResult['NAME']));
    ?>
    <section class="top">
        <div class="page-container">
            <div class="top_inner">
                <div class="top_col top_col--left">
                    <h2 class="top_title title"><?= $title ?></h2>
                    <p class="top_desc">
                        <?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
                            <?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
                        <?else:?>
                                <?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
                        <?endif;?>
                    </p>
                    <span>
                        <button class="top_btn btn-custom animate-load"
                              data-event="jqm"
                              data-param-id="62"
                              data-name="storage"
                              data-autoload-product="<?=CPriority::formatJsName($arResult['NAME'])?>">
                            <span>
                            заказать хранение под ключ
                            </span>
                        </button>
                    </span>
                    <div class="top_decor-line decor-line"></div>
                </div>
                <div class="top_col top_col--right">
 <span class="top_label">
			с доставкой «Под Ключ» </span>
                    <div class="top_img-wrap">
                        <img src="<?= $bg ?>" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
	<?$this->EndViewTarget();?>
<?endif;?>