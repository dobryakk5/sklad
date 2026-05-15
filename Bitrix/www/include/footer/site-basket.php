<? $isAjax = (((isset($_POST["itemData"]) || (isset($_POST["ajaxPost"]) && $_POST["ajaxPost"] == "Y")) && $_SERVER["REQUEST_METHOD"] == "POST") || ((isset($_GET["itemData"]) || isset($_GET["remove"])) && $_SERVER["REQUEST_METHOD"] == "GET")); ?>

<? global $arTheme, $showFlyCallback, $showFlyAskQuestion, $showFlyAddReview, $showFlyMap; ?>

<? if ($isAjax): ?>
	<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
	<? \Bitrix\Main\Loader::includeModule('aspro.priority');

	$arTheme = CPriority::GetFrontParametrsValues(SITE_ID);
	$arBasketItems = CPriority::processBasket();
	$template = strtolower($arTheme["ORDER_BASKET_VIEW"]);
	$bUseBasket = (isset($arTheme["ORDER_VIEW"]['VALUE']) && $arTheme["ORDER_VIEW"]['VALUE'] == 'Y' || $arTheme["ORDER_VIEW"] == "Y");
	$bFormLateral = (isset($arTheme["FORM_TYPE"]['VALUE']) && $arTheme["FORM_TYPE"]['VALUE'] == 'LATERAL' || $arTheme["ORDER_VIEW"] == "LATERAL");
	$bHideBasketPage = (CPriority::IsBasketPage($arTheme["URL_BASKET_SECTION"]) || CPriority::IsOrderPage($arTheme["URL_ORDER_SECTION"]));
	$pathOrder = (isset($arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']) && $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_ORDER_SECTION']['VALUE'] ? $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_ORDER_SECTION']['VALUE'] : $arTheme['URL_ORDER_SECTION']);
	$pathBasket = (isset($arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']) && $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'] ? $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'] : $arTheme['URL_BASKET_SECTION']);
	$pathCatalog = (isset($arTheme['URL_CATALOG_SECTION']['VALUE']) && $arTheme['URL_CATALOG_SECTION']['VALUE'] ? $arTheme['URL_CATALOG_SECTION']['VALUE'] : $arTheme['URL_CATALOG_SECTION']);
	?>
<? else: ?>
	<?
	if (is_array($arTheme["ORDER_VIEW"])) {
		$template = strtolower($arTheme["ORDER_VIEW"]["DEPENDENT_PARAMS"]["ORDER_BASKET_VIEW"]["VALUE"]);
		$bUseBasket = (isset($arTheme["ORDER_VIEW"]['VALUE']) && $arTheme["ORDER_VIEW"]['VALUE'] == 'Y' || $arTheme["ORDER_VIEW"] == "Y");
	}
	$bHideBasketPage = (CPriority::IsBasketPage($arTheme["URL_BASKET_SECTION"]) || CPriority::IsOrderPage($arTheme["URL_ORDER_SECTION"]));
	$bFormLateral = (isset($arTheme["FORM_TYPE"]['VALUE']) && $arTheme["FORM_TYPE"]['VALUE'] == 'LATERAL' || $arTheme["ORDER_VIEW"] == "LATERAL");
	$pathOrder = (isset($arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']) && $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_ORDER_SECTION']['VALUE'] ? $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_ORDER_SECTION']['VALUE'] : $arTheme['URL_ORDER_SECTION']);
	$pathBasket = (isset($arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']) && $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'] ? $arTheme["ORDER_VIEW"]['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'] : $arTheme['URL_BASKET_SECTION']);
	$pathCatalog = (isset($arTheme['URL_CATALOG_SECTION']['VALUE']) && $arTheme['URL_CATALOG_SECTION']['VALUE'] ? $arTheme['URL_CATALOG_SECTION']['VALUE'] : $arTheme['URL_CATALOG_SECTION']);
	?>
	<!-- noindex -->
	<div class="ajax_basket">
	<? endif; ?>
	<? $checkBasketUrl = 'Y'; ?>
	<? $APPLICATION->IncludeComponent(
		"aspro:basket.priority",
		$template,
		array(
			"COMPONENT_TEMPLATE" => $template,
			"NO_REDIRECT" => "Y",
			"CHECK_BASKET_URL" => $checkBasketUrl,
			"PATH_TO_CATALOG" => $pathCatalog,
			"PATH_TO_ORDER" => $pathOrder,
			"PATH_TO_BASKET" => $pathBasket,
		),
		false,
		array("HIDE_ICONS" => "Y")
	); ?>
	<? if (!$isAjax): ?>
	</div>
	<!-- /noindex -->
<? endif; ?>

<? if ($APPLICATION->GetCurPage() == '/about/vacancies/'): ?>
	<? if ($template == "header" || !$bUseBasket || ($bHideBasketPage && $checkBasketUrl == "Y")): ?>
		<? if ($showFlyCallback || $showFlyAskQuestion || $showFlyAddReview || $showFlyMap): ?>
			<div class="basket fly">
				<div class="fly_forms">

					<? $APPLICATION->IncludeComponent(
						"bitrix:sale.basket.basket.line",
						"fly_basket",
						array(
							"COMPONENT_TEMPLATE" => "fly_basket",
							"PATH_TO_BASKET" => SITE_DIR . "cart/",
							"PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
							"SHOW_NUM_PRODUCTS" => "Y",
							"SHOW_TOTAL_PRICE" => "Y",
							"SHOW_EMPTY_VALUES" => "Y",
							"SHOW_PERSONAL_LINK" => "N",
							"PATH_TO_PERSONAL" => SITE_DIR . "personal/",
							"SHOW_AUTHOR" => "N",
							"PATH_TO_AUTHORIZE" => "",
							"SHOW_REGISTRATION" => "N",
							"PATH_TO_REGISTER" => SITE_DIR . "login/",
							"PATH_TO_PROFILE" => SITE_DIR . "personal/",
							"SHOW_PRODUCTS" => "N",
							"POSITION_FIXED" => "N",
							"HIDE_ON_BASKET_PAGES" => "Y"
						),
						false,
						array("HIDE_ICONS" => "Y")
					); ?>

					<? CPriority::checkShowForm($showFlyCallback, array('ICON_CLASS' => 'callback_icon', 'FORM_CODE' => 'aspro_priority_callback', 'FORM_NAME' => 'callback', 'FORM_TEXT' => GetMessage('CALLBACK_FORM_BUTTON_TEXT'))); ?>
					<? CPriority::checkShowForm($showFlyAskQuestion, array('ICON_CLASS' => 'question_icon', 'FORM_CODE' => 'aspro_priority_question', 'FORM_NAME' => 'question', 'FORM_TEXT' => GetMessage('ASK_QUESTION_FORM_BUTTON_TEXT'))); ?>
					<? CPriority::checkShowForm($showFlyAddReview, array('ICON_CLASS' => 'add_review_icon', 'FORM_CODE' => 'aspro_priority_add_review', 'FORM_NAME' => 'add_review', 'FORM_TEXT' => GetMessage('ADD_REVIEW_FORM_BUTTON_TEXT'))); ?>
					<? CPriority::checkShowForm($showFlyMap, array('ICON_CLASS' => 'map_icon', 'FORM_CODE' => 'map', 'FORM_NAME' => 'map', 'FORM_TEXT' => GetMessage('MAP_FORM_BUTTON_TEXT'))); ?>
				</div>
			</div>
		<? endif; ?>
	<? endif; ?>
<? endif; ?>