<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$basketItems = $basket->getBasketItems();?>
    <script type="text/javascript">
        <?foreach ($basketItems as $item) {
        ?>

        $('.to-cart[data-item=<?=$item->getField('PRODUCT_ID')?>]').hide();
        $('.to-cart[data-item=<?=$item->getField('PRODUCT_ID')?>]').closest('.counter_wrapp').find('.counter_block').hide();
        $('.to-cart[data-item=<?=$item->getField('PRODUCT_ID')?>]').parents('tr').find('.counter_block_wr .counter_block').hide();
        $('.to-cart[data-item=<?=$item->getField('PRODUCT_ID')?>]').closest('.button_block').addClass('wide');
        $('.in-cart[data-item=<?=$item->getField('PRODUCT_ID')?>]').show();

        <?
        }?>
    </script>
<?
if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
	<script type="text/javascript">
		BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
	</script>
	<?}
}?>