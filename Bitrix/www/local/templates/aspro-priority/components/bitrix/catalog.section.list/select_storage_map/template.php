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
	<div class="select_storage_map">
		<?/*
		<h3>Просто арендуйте бокс прямо сейчас!</h3>
		*/?>
		<div class="row">
			<?/*
			<div class="col-md-3">
				<div class="select_steps">
					<div class="step active">
						<div class="head">
							<div class="num">1</div>
							<div class="name">Выбор склада</div>
						</div>
						<div class="text">Каждый склад имеет удобное расположение, проезд и пункт разгрузки</div>
						<div class="text mt">
							Выберите склад <a class="link map" href="javascript:void(0);">на карте</a> или <a class="link list" href="javascript:void(0);" data-toggle="modal" data-target="#modalSkladList">из списка</a>
						</div>
					</div>
					<div class="step">
						<div class="head">
							<div class="num">2</div>
							<div class="name">Выбор бокса</div>
						</div>
						<div class="text">На выбор боксы от 1 до 50 куб. м, сроком от 1 дня до 11 месяцев</div>
					</div>	
					<div class="step">
						<div class="head">
							<div class="num">3</div>
							<div class="name">Аренда</div>
						</div>
						<div class="text">Для завершения аренды с Вами свяжется наш менеджер, спасибо!</div>
					</div>					
				</div>
			</div>
			*/?>
			<div class="col-md-12">
				#MAP#
			</div>		
		</div>
	</div>		
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>