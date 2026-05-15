<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(false); ?>

<?
global $isMenu, $arTheme;

use \Bitrix\Main\Localization\Loc;

$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (isset($arTheme['URL_BASKET_SECTION']) && strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? $arTheme['URL_BASKET_SECTION']['VALUE'] : SITE_DIR . 'cart/');
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
//    'DETAIL_TEXT' => $arResult['DETAIL_TEXT'],
    'ORDER' => $bOrderViewBasket,
    'CATALOG_LINKED_TEMPLATE' => $catalogLinkedTemplate,
    'FORM_ORDER' => $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'],
    'FORM_QUESTION' => $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'],
    'FORM_STORAGE' => $arResult['DISPLAY_PROPERTIES']['FORM_STORAGE']['VALUE_XML_ID'],
    'PRICE' => $arResult['PROPERTIES']['PRICE']['VALUE'],
    'PRICE_OLD' => $arResult['PROPERTIES']['PRICE_OLD']['VALUE'],
);
if (isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES')
    $templateData['SECTION_BNR_CONTENT'] = true;
?>

<? // shot top banners start?>
<? $bShowTopBanner = (isset($arResult['SECTION_BNR_CONTENT']) && $arResult['SECTION_BNR_CONTENT'] == true); ?>
<? if ($bShowTopBanner): ?>
    <? $this->SetViewTarget("section_bnr_content"); ?>
    <? CPriority::ShowTopDetailBanner($arResult, $arParams); ?>

    <? $this->EndViewTarget(); ?>
<? else:
    ?>

<? endif; ?>
<? // shot top banners end?>
<? /*<div class="breadcrumb_container">
        <? $APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "",
            array(
                "COMPONENT_TEMPLATE" => "corp",
                "PATH" => "",
                "SITE_ID" => "s1",
                "START_FROM" => "0"
            )
        ); ?>
    </div> */ ?>

<? //middle menu?>
<? /*$this->SetViewTarget("section_bnr_content");?>
	2323232323
<?$this->EndViewTarget();*/ ?>


<? // form question?>
<? // element name?>
<? if ($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])): ?>
    <h2><?= $arResult['NAME'] ?></h2>
<? endif; ?>

<? // single detail image?>
<? if ($arResult['FIELDS']['DETAIL_PICTURE']): ?>
    <?
    $atrTitle = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME']));
    $atrAlt = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME']));
    ?>
    <? if ($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'LEFT'): ?>
        <div class="detailimage image-left col-md-4 col-sm-4 col-xs-12"><a
                    href="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="fancybox" title="<?= $atrTitle ?>"><img
                        src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="img-responsive" title="<?= $atrTitle ?>"
                        alt="<?= $atrAlt ?>"/></a></div>
    <? elseif ($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'RIGHT'): ?>
        <div class="detailimage image-right col-md-4 col-sm-4 col-xs-12"><a
                    href="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="fancybox" title="<?= $atrTitle ?>"><img
                        src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="img-responsive" title="<?= $atrTitle ?>"
                        alt="<?= $atrAlt ?>"/></a></div>
    <? elseif ($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'TOP'): ?>
        <? $this->SetViewTarget('top_section_filter_content'); ?>
        <div class="detailimage image-head"><img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="img-responsive"
                                                 title="<?= $atrTitle ?>" alt="<?= $atrAlt ?>"/></div>
        <? $this->EndViewTarget(); ?>
    <? else: ?>
        <div class="detailimage image-wide"><a href="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="fancybox"
                                               title="<?= $atrTitle ?>"><img
                        src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" class="img-responsive" title="<?= $atrTitle ?>"
                        alt="<?= $atrAlt ?>"/></a></div>
    <? endif; ?>
<? endif; ?>



<?= $templateData['DETAIL_TEXT']; ?>


<style>
    .title.big-gallery {
        display: none;
    }

    .detail .galerys-block .title.small-gallery {
        display: block;
    }

    .big-gallery-block {
        display: none;
    }

    .detail .galerys-block .small-gallery-block {
        display: block;
    }
</style>

