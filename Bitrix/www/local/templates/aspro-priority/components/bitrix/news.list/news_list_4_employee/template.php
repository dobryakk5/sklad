<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?

use \Bitrix\Main\Localization\Loc; ?>

<? if ($arResult['ITEMS']): ?>

	<?php if ($APPLICATION->GetCurPage() != '/team/'): ?>
		<h2 class="our-heam-h2">Клиентские менеджеры:</h2>
	<?php endif; ?>

	<div class="maxwidth-theme section-carts-employees">

		<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
			<div class="pagination_nav">
				<?= $arResult["NAV_STRING"] ?>
			</div>
		<? endif; ?>

		<div class="row wrapper-carts">
			<? $index = 1; ?>
			<? foreach ($arResult['ITEMS'] as $i => $arItem): ?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
				$imageSrc = ($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : '');

				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));

				?>
				<div class="cart-employee col-md-4 col-sm-6 col-xs-12">
					<div class="wrapper-cart-employee" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
						<? if ($imageSrc): ?>
							<div class="cart-image-employee">
								<div class="wrap-cart-image-employee">
									<? if ($bDetailLink): ?><a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><? endif; ?>
										<img class="img-responsive lazy" data-src="<?= $imageSrc ?>" alt="<?= ($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME']) ?>" title="<?= ($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME']) ?>" />
										<? if ($bDetailLink): ?></a><? endif; ?>
								</div>
							</div>
						<? endif; ?>
						<div class="cart-content-employee">

							<div class="cart-content-employee-top">
								<h3 class="name-employee"><?= $arItem['NAME'] ?></h3>
								<p class="job-title-employee">
									<?= $arItem['PROPERTIES']['POST'] && $arItem['PROPERTIES']['POST']['VALUE'] ? $arItem['PROPERTIES']['POST']['VALUE'] : 'Менеджер' ?><br>
									<a class="job-title-link-employee" href="<?= $arItem['DISPLAY_PROPERTIES']['LINK_SKLAD']['LINK_SECTION_VALUE']['SECTION_PAGE_URL'] ?>"><?= $arItem['ADDRESS'] ?? $arItem['DISPLAY_PROPERTIES']['LINK_SKLAD']['DISPLAY_VALUE'] ?></a>
								</p>
							</div>

							<p class="description-employee">
								<?= $arItem['PREVIEW_TEXT'] ?>
							</p>
						</div>
					</div>
				</div>
				<?
				$index = ($index == 6 ? 1 : $index);
				++$index;
				?>
			<? endforeach; ?>

			<? if (isset($arParams["DISPLAY_UNIVERSAVL_MANAGER"]) && $arParams["DISPLAY_UNIVERSAVL_MANAGER"] == 'Y'): ?>
				<div class="cart-employee col-md-4 col-sm-6 col-xs-12">
					<div class="wrapper-cart-employee">
						<div class="cart-image-employee">
							<div class="wrap-cart-image-employee">
								<img class="img-responsive lazy" data-src="/upload/CPriority/b35/guv308nx3nh9ugbbpt4m6252s40syyit/7a1c476d-cad7-430d-9aa4-ba0539ee28d1.jpeg" alt="Универсальный менеджер" />
							</div>
						</div>
						<div class="cart-content-employee">
							<div class="cart-content-employee-top">

								<h3 class="name-employee">Артем. К.</h3>
								<p class="job-title-employee">
									Универсальный менеджер
								</p>
							</div>
							<p class="description-employee">
								Артем – внимательный и доброжелательный. Всегда поможет разобраться, даже если это Ваш первый склад и вы пока не представляете, что именно нужно.
							</p>
						</div>
					</div>
				</div>
			<? endif; ?>
		</div>

		<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
			<div class="pagination_nav">
				<?= $arResult["NAV_STRING"] ?>
			</div>
		<? endif; ?>
	</div>
<? endif; ?>