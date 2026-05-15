<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div class="mobileheader-v1">
	<?CPriority::ShowBurger();?>
	<div class="logo-block pull-left">
		<div class="logo<?=($arTheme["COLORED_LOGO"]["VALUE"] !== "Y" ? '' : ' colored')?>">
			<?=CPriority::ShowLogo();?>
		</div>
	</div>
	<div class="right-icons pull-right">
		<div class="pull-right">
			<div class="wrap_icon wrap_basket">
				<?=CPriority::showBasketLink('', '', '', '', true);?>
			</div>
		</div>
		<?/*if($arTheme["CABINET"]["VALUE"]=='Y'):?>
			<div class="pull-right">
				<div class="wrap_icon wrap_cabinet">
					<?=CPriority::showCabinetLink(true, false);?>
				</div>
			</div>
		<?endif;*/?>
		<div class="pull-right hidden-xs">
			<div class="wrap_icon">
				<?=CPriority::ShowSearch();?>
			</div>
		</div>
		<div class="pull-right">
			<?/*
			<div class="header-button wrap_icon">
				<a class="btn btn-default btn-xs" href="/payment/">Оплата</a>
			</div>
			*/?>
			<div style="margin-top:8px;">
				<?CPriority_ext::ShowHeaderPhones('mask');?>
			</div>
            <? /*<div class="whatsapp-block">
                <span class="whatsapp-info"><a href="https://wa.me/79855800640" target="_blank">WhatsApp</a></span>
                <span class="whatsapp-icon"><a href="https://wa.me/79855800640" target="_blank"><img src="/upload/webpult/whatsapp-header.svg"></a></span>
            </div> */ ?>
		</div>		
	</div>
</div>