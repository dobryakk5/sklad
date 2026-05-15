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
	'BRAND_ITEM' => $arResult['BRAND_ITEM'],
	'GALLERY_BIG' => $arResult['GALLERY_BIG'],
	'VIDEO' => $arResult['VIDEO'],
	'VIDEO_IFRAME' => $arResult['VIDEO_IFRAME'],
	'DETAIL_TEXT' => $arResult['FIELDS']['DETAIL_TEXT'],
	'ORDER' => $bOrderViewBasket,
	'FORM_QUESTION' => $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE'],
	'CATALOG_LINKED_TEMPLATE' => $catalogLinkedTemplate,
);
if(isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES')
	$templateData['SECTION_BNR_CONTENT'] = true;
?>


<?// shot top banners start?>
<?$bShowTopBanner = (isset($arResult['SECTION_BNR_CONTENT'] ) && $arResult['SECTION_BNR_CONTENT'] == true);?>
<?if($bShowTopBanner):?>
	<?$this->SetViewTarget("section_bnr_content");?>
		<?CPriority::ShowTopDetailBanner($arResult, $arParams);?>
	<?$this->EndViewTarget();?>
<?endif;?>
<?$bShowFormQuestion = ($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES');?>


<?ob_start();?>
	<div class="ask_a_question border shadow">
		<div class="inner">
			<div class="text-block">
				<?$APPLICATION->IncludeComponent(
					 'bitrix:main.include',
					 '',
					 Array(
						  'AREA_FILE_SHOW' => 'file',
						  'PATH' => SITE_DIR.'include/ask_question.php',
						  'EDIT_TEMPLATE' => ''
					 )
				);?>
			</div>
		</div>
		<div class="outer">
			<span class="font_upper animate-load" data-event="jqm" data-param-id="<?=CPriority::getFormID("aspro_priority_question");?>" data-autoload-need_product="<?=CPriority::formatJsName($arResult['NAME'])?>" data-name="question"><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span>
		</div>
	</div>
	
<?$sFormQuestion = ob_get_contents();
ob_end_clean();?>

<div class="detail news fixed_wrapper">	
	<div class="row">
		<div class="col-md-9">
			<?// element name?>
			<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
				<h2><?=$arResult['NAME']?></h2>
			<?endif;?>

			<?// single detail image?>
			<?if($arResult['FIELDS']['DETAIL_PICTURE']):?>
				<?
				$atrTitle = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME']));
				$atrAlt = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME']));
				?>
				<?if($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'LEFT'):?>
					<div class="detailimage image-left col-md-4 col-sm-4 col-xs-12"><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancybox" title="<?=$atrTitle?>"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a></div>
				<?elseif($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'RIGHT'):?>
					<div class="detailimage image-right col-md-4 col-sm-4 col-xs-12"><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancybox" title="<?=$atrTitle?>"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a></div>
				<?elseif($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'TOP'):?>
					<?$this->SetViewTarget('top_section_filter_content');?>
					<div class="detailimage image-head"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>"/></div>
					<?$this->EndViewTarget();?>
				<?else:?>
					<div class="detailimage image-wide"><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancybox" title="<?=$atrTitle?>"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a></div>
				<?endif;?>
			<?endif;?>

			<?// date active from or dates period active?>
			<?
			$bdate = (strlen($arResult['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arResult['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE'])) ? true : false)
			?>
			<?if($bdate || $arResult['SECTION']):?>
				<div class="top-wrapper">
					<?if($bdate):?>
						<div class="period font_xs">
							<?if(strlen($arResult['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
								<span class="date"><?=$arResult['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
							<?else:?>
								<span class="date"><?=$arResult['DISPLAY_ACTIVE_FROM']?></span>
							<?endif;?>
						</div>
					<?endif;?>
					<?if($arResult['SECTION']):?>
						<div class="section_name font_upper_md"><?=$arResult['SECTION']['PATH'][0]['NAME']?></div>
					<?endif;?>
				</div>
			<?endif;?>
			
			<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
				<div class="content">
					<?if(!$bShowTopBanner && strlen($arResult['FIELDS']['PREVIEW_TEXT'])):?>
						<div class="introtext">
							<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
								<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
							<?else:?>
								<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
							<?endif;?>
						</div>
					<?endif;?>
				
					<?// element detail text?>
					<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
						<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
							<p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
						<?else:?>
							<?=$arResult['FIELDS']['DETAIL_TEXT'];?>
						<?endif;?>
					<?endif;?>
				</div>
			<?endif;?>

			<?// order block?>
			<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' || $bShowFormQuestion):?>
				<table class="order-block">
					<tr>
						<td class="col-md-9 col-sm-8 col-xs-7 valign">
							<div class="text">
								<?$APPLICATION->IncludeComponent(
									'bitrix:main.include',
									'',
									Array(
										'AREA_FILE_SHOW' => 'file',
										'PATH' => SITE_DIR.'include/ask_services.php',
										'EDIT_TEMPLATE' => ''
									)
								);?>
							</div>
						</td>
						<td class="col-md-3 col-sm-4 col-xs-5 valign">
							<div class="btns">
								<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
									<span><span class="btn btn-default btn-lg animate-load order" data-event="jqm" data-param-id="<?=CPriority::getFormID("aspro_priority_order_services")?>" data-name="order_services" data-autoload-service="<?=CPriority::formatJsName($arResult['NAME'])?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
								<?endif;?>
								<?if($bShowFormQuestion):?>
									<span><span class="btn btn-default btn-lg btn-transparent animate-load question" data-event="jqm" data-param-id="<?=CPriority::getFormID("aspro_priority_question")?>" data-name="order_services" data-autoload-service="<?=CPriority::formatJsName($arResult['NAME']);?>" data-autoload-project="<?=CPriority::formatJsName($arResult['NAME']);?>">
										<?=CPriority::showIconSvg(SITE_TEMPLATE_PATH.'/images/include_svg/qmark.svg');?>
									</span></span>
								<?endif;?>
							</div>
						</td>
					</tr>
				</table>
			<?endif;?>
		</div>
		<div class="col-md-3">
			<div class="fixed_block_fix"></div>
			<div class="ask_a_question_wrapper">
				<?=$sFormQuestion;?>
			</div>
		</div>	
	</div>
</div>	

<br>
<div class="row">
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-6 share">
				<div class="shares-block">
					<svg class="svg svg-share" id="share.svg" xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14 16"><path id="Ellipse_223_copy_8" data-name="Ellipse 223 copy 8" class="cls-1" d="M1613,203a2.967,2.967,0,0,1-1.86-.661l-3.22,2.01a2.689,2.689,0,0,1,0,1.3l3.22,2.01A2.961,2.961,0,0,1,1613,207a3,3,0,1,1-3,3,3.47,3.47,0,0,1,.07-0.651l-3.21-2.01a3,3,0,1,1,0-4.678l3.21-2.01A3.472,3.472,0,0,1,1610,200,3,3,0,1,1,1613,203Zm0,8a1,1,0,1,0-1-1A1,1,0,0,0,1613,211Zm-8-7a1,1,0,1,0,1,1A1,1,0,0,0,1605,204Zm8-5a1,1,0,1,0,1,1A1,1,0,0,0,1613,199Z" transform="translate(-1602 -197)"/></svg>
					<span class="text"><?=GetMessage('SHARE_TEXT')?></span>
					<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
					<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<a class="back-url url-block font_upper" href="<?=$arResult['SECTION']['PATH'][0]['SECTION_PAGE_URL']?>"><span>Назад к списку</span></a>
			</div>
		</div>
	</div>
</div>