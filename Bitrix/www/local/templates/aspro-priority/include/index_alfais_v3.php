<?php

if (count($arParams['ITEMS']) > 0 && !empty($arParams['ITEMS'][0])) {
	$resItems = CIBlockElement::GetList(
		["SORT" => "ASC"],
		['ID' => $arParams['ITEMS'], "ACTIVE" => "Y"],
	);

	$advantages = [];
	while ($item = $resItems->GetNext()) {
		$advantages[] = [
			'img' => CFile::GetPath($item['PREVIEW_PICTURE']),
			'name' => $item['PREVIEW_TEXT'],
		];
	}

?>

	<section class="advantages">
		<div class="card">
			<div class="card__inner">
				<div class="card__header">
					<h2 class="card__title">
						<?= html_entity_decode($arParams['CAPTION']) ?>
					</h2>
				</div>
			</div>

			<div class="advantages__columns">
				<div class="advantages__column">
					<?php foreach ($advantages as $item): ?>
						<div class="advantages__item">
							<?php if ($item['img']): ?>
								<img class="advantages__item-icon lazy" src="/images/0.gif" data-src="<?= $item['img'] ?>" alt="">
							<?php endif; ?>
							<p class="advantages__item-description text_m">
								<?= html_entity_decode($item['name']) ?>
							</p>
						</div>
					<?php endforeach; ?>

				</div>
			</div>
		</div>
	</section>
<?php } ?>