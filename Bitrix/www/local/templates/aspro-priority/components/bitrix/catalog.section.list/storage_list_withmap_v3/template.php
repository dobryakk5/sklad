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
	<div class="hero-block__map" data-info='<?= json_encode($arResult["SECTIONS_JSON"], JSON_UNESCAPED_UNICODE) ?>'>
		<div id="map"></div>
	</div>

	<div class="hero-block__tabs">
		<button class="hero-block__tab hero-block__tab_active text_s text_medium" data-type="all">
			Все
		</button>

		<?php foreach ($arResult['TYPES'] as $type): ?>
			<button class="hero-block__tab text_s text_medium" data-type="<?= $type['code'] ?>">
				<?= $type['value'] ?>
			</button>
		<?php endforeach; ?>
	</div>
<?php endif; ?>