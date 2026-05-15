<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
?>

<?php if (count($arResult["SECTIONS"]) > 0): ?>
	<section class="addresses">
		<div class="card">
			<div class="card__inner">
				<div class="card__header">
					<div class="card__title">
						<span class="text_accent">Адреса</span> складов
					</div>
				</div>

				<div class="card__blocks">
					<div class="card__block">
						<div id="map" style="width: 100%; height: 400px;"></div>
					</div>

					<div class="card__block">
						<div class="addresses__tabs">
							<button class="addresses__tab addresses__tab_all addresses__tab_active text_s" data-region="all">
								Все округа
							</button>

							<?php if (count($arResult["DISTRICTS"]) > 0): ?>
								<?php foreach ($arResult["DISTRICTS"] as $did => $d): ?>
									<button class="addresses__tab addresses__tab_<?= $did ?> text_s" data-region="d<?= $did ?>">
										<?= $d ?>
									</button>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<div class="addresses__list" data-addresses='<?= json_encode($arResult["SECTIONS_JSON"], JSON_UNESCAPED_UNICODE) ?>'></div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>