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
$this->setFrameMode(true);

?>

<section class="storage-gallery">
	<div class="card">
		<div class="card__inner">
			<div class="swiper storage-gallery__swiper">
				<div class="swiper-wrapper">

					<? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div class="swiper-slide storage-gallery__slide <?= $class ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
							<img class="storage-gallery__image" src="<?= $arItem['SMALL']['src'] ?>" alt="slide">
						</div>
					<?php endforeach; ?>

				</div>
			</div>

			<div class="swiper-pagination swiper-pagination_custom swiper-pagination_storage-gallery"></div>

			<div class="swiper-button_custom swiper-button-prev_custom swiper-button-prev_storage-gallery">
				<svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M7.22424 2.61952L2.86426 6.92952L7.19421 11.2075C7.36479 11.3978 7.45613 11.6462 7.44934 11.9016C7.44256 12.157 7.33815 12.4002 7.15771 12.5811C6.97728 12.7621 6.73443 12.8671 6.479 12.8746C6.22358 12.8821 5.97493 12.7916 5.78418 12.6215L0.734253 7.63652C0.546782 7.44899 0.441406 7.19469 0.441406 6.92952C0.441406 6.66436 0.546782 6.41005 0.734253 6.22252L5.81421 1.20552C5.90501 1.10424 6.01558 1.02253 6.13904 0.965425C6.2625 0.908318 6.39624 0.877011 6.53223 0.873399C6.66821 0.869788 6.80349 0.893956 6.92981 0.944429C7.05613 0.994902 7.17092 1.07061 7.26697 1.16693C7.36302 1.26326 7.43841 1.37818 7.48853 1.50464C7.53864 1.6311 7.56235 1.76645 7.55835 1.90242C7.55435 2.03839 7.52267 2.17213 7.46521 2.29543C7.40775 2.41873 7.32579 2.52901 7.22424 2.61952Z"
						fill="#EF5A54" />
				</svg>
			</div>
			<div class="swiper-button_custom swiper-button-next_custom swiper-button-next_storage-gallery">
				<svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M0.775757 11.1305L5.13574 6.82048L0.805786 2.54248C0.63521 2.35221 0.543875 2.10385 0.550659 1.8484C0.557444 1.59295 0.661849 1.3498 0.842285 1.16885C1.02272 0.987904 1.26557 0.882903 1.521 0.875395C1.77642 0.867887 2.02507 0.95844 2.21582 1.12848L7.26575 6.11348C7.45322 6.30101 7.55859 6.55531 7.55859 6.82048C7.55859 7.08564 7.45322 7.33995 7.26575 7.52748L2.18579 12.5445C2.09499 12.6458 1.98442 12.7275 1.86096 12.7846C1.7375 12.8417 1.60376 12.873 1.46777 12.8766C1.33179 12.8802 1.19651 12.856 1.07019 12.8056C0.943871 12.7551 0.829084 12.6794 0.733032 12.5831C0.636981 12.4867 0.56159 12.3718 0.511475 12.2454C0.46136 12.1189 0.437654 11.9835 0.44165 11.8476C0.445647 11.7116 0.477334 11.5779 0.53479 11.4546C0.592247 11.3313 0.674213 11.221 0.775757 11.1305Z"
						fill="#EF5A54" />
				</svg>
			</div>
		</div>
	</div>
</section>